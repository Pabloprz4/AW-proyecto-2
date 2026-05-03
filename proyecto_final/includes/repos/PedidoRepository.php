<?php
declare(strict_types=1);

final class PedidoRepository
{
    private const ESTADOS = [
        'nuevo',
        'recibido',
        'en_preparacion',
        'cocinando',
        'listo_cocina',
        'terminado',
        'entregado',
        'cancelado',
    ];

    private const TIPOS = ['local', 'llevar'];
    private const METODOS_PAGO = ['tarjeta', 'camarero'];

    public static function createFromCart(array $cart, int $clienteId, string $metodoPago): int
    {
        if ($clienteId <= 0) {
            throw new InvalidArgumentException('Cliente invalido.');
        }

        $items = isset($cart['items']) && is_array($cart['items']) ? $cart['items'] : [];
        $recompensas = isset($cart['recompensas']) && is_array($cart['recompensas']) ? $cart['recompensas'] : [];
        if ($items === [] && $recompensas === []) {
            throw new InvalidArgumentException('El pedido no puede estar vacio.');
        }

        $tipo = self::normalizeTipo((string) ($cart['tipo'] ?? 'local'));
        $metodoPago = self::normalizeMetodoPago($metodoPago);
        $estadoInicial = $metodoPago === 'tarjeta' ? 'en_preparacion' : 'recibido';

        $pdo = db();
        $pdo->beginTransaction();

        try {
            $lineasPago = [];
            $lineasRecompensa = [];
            $totalSinDescuento = 0.0;
            $descuentoAplicado = 0.0;
            $bistrocoinsUsados = 0;

            $stmtProducto = $pdo->prepare(
                'SELECT id, nombre, precio, iva, ofertado, disponible FROM productos WHERE id = :id LIMIT 1'
            );

            foreach ($items as $productoId => $cantidad) {
                $id = (int) $productoId;
                $qty = (int) $cantidad;

                if ($id <= 0 || $qty <= 0) {
                    continue;
                }

                $stmtProducto->execute(['id' => $id]);
                $producto = $stmtProducto->fetch();

                if (
                    !$producto
                    || (int) $producto['ofertado'] !== 1
                    || (int) ($producto['disponible'] ?? 0) !== 1
                ) {
                    throw new RuntimeException('Uno de los productos ya no esta disponible.');
                }

                $precioBase = (float) $producto['precio'];
                $iva = (float) $producto['iva'];
                $precioFinalUnitario = round($precioBase * (1 + ($iva / 100)), 2);
                $subtotal = round($precioFinalUnitario * $qty, 2);
                $totalSinDescuento += $subtotal;

                $lineasPago[] = [
                    'producto_id' => (int) $producto['id'],
                    'producto_nombre' => (string) $producto['nombre'],
                    'precio_base' => round($precioBase, 2),
                    'iva' => round($iva, 2),
                    'precio_final_unitario' => $precioFinalUnitario,
                    'cantidad' => $qty,
                    'subtotal' => $subtotal,
                ];
            }

            $stmtRecompensa = $pdo->prepare(
                'SELECT r.id, r.producto_id, r.bistrocoins, r.activo, p.nombre, p.ofertado, p.disponible
                 FROM recompensas r
                 INNER JOIN productos p ON p.id = r.producto_id
                 WHERE r.id = :id
                 LIMIT 1'
            );

            foreach ($recompensas as $recompensaId => $cantidad) {
                $id = (int) $recompensaId;
                $qty = (int) $cantidad;
                if ($id <= 0 || $qty <= 0) {
                    continue;
                }

                $stmtRecompensa->execute(['id' => $id]);
                $recompensa = $stmtRecompensa->fetch();

                if (
                    !$recompensa
                    || (int) $recompensa['activo'] !== 1
                    || (int) $recompensa['ofertado'] !== 1
                    || (int) $recompensa['disponible'] !== 1
                ) {
                    throw new RuntimeException('Una de las recompensas ya no esta disponible.');
                }

                $coinsUnit = (int) $recompensa['bistrocoins'];
                $coinsTotal = $coinsUnit * $qty;
                $bistrocoinsUsados += $coinsTotal;

                $lineasRecompensa[] = [
                    'producto_id' => (int) $recompensa['producto_id'],
                    'producto_nombre' => (string) $recompensa['nombre'] . ' (Recompensa)',
                    'cantidad' => $qty,
                    'bistrocoins_unit' => $coinsUnit,
                    'bistrocoins_total' => $coinsTotal,
                ];
            }

            if ($lineasPago === [] && $lineasRecompensa === []) {
                throw new RuntimeException('No hay lineas validas para crear el pedido.');
            }

            // Aplicar oferta sobre lineas de pago normales
            $ofertaAplicada = $cart['oferta_aplicada'] ?? null;
            if ($ofertaAplicada && $lineasPago !== []) {
                $oferta = OfertaRepository::findByIdWithProducts((int) $ofertaAplicada);
                if ($oferta) {
                    $precioPack = OfertaRepository::calculatePackPrice($oferta['productos']);
                    $descuento = (float) $oferta['descuento'];
                    $descuentoAplicado = round($precioPack * ($descuento / 100), 2);
                }
            }

            $total = $totalSinDescuento - $descuentoAplicado;
            if ($total < 0) {
                $total = 0;
            }

            $stmtUsuario = $pdo->prepare('SELECT bistrocoins FROM usuarios WHERE id = :id FOR UPDATE');
            $stmtUsuario->execute(['id' => $clienteId]);
            $usuario = $stmtUsuario->fetch();
            if (!$usuario) {
                throw new RuntimeException('Cliente no encontrado.');
            }

            $saldoActual = (int) $usuario['bistrocoins'];
            $saldoReservado = self::pendingBistrocoinsByCliente($clienteId);
            $saldoDisponible = max(0, $saldoActual - $saldoReservado);
            if ($bistrocoinsUsados > $saldoDisponible) {
                throw new RuntimeException('No tienes BistroCoins suficientes para canjear esas recompensas.');
            }

            $bistrocoinsGanados = (int) floor($total);
            $liquidado = $metodoPago === 'tarjeta' ? 1 : 0;

            $fechaDia = date('Y-m-d');
            $stmtNumero = $pdo->prepare(
                'SELECT ultimo_numero FROM pedido_numeracion WHERE fecha_dia = :fecha_dia FOR UPDATE'
            );
            $stmtNumero->execute(['fecha_dia' => $fechaDia]);
            $rowNumero = $stmtNumero->fetch();

            if (is_array($rowNumero)) {
                $numeroDia = ((int) $rowNumero['ultimo_numero']) + 1;
                $stmtActualizaNumero = $pdo->prepare(
                    'UPDATE pedido_numeracion SET ultimo_numero = :ultimo_numero WHERE fecha_dia = :fecha_dia'
                );
                $stmtActualizaNumero->execute([
                    'ultimo_numero' => $numeroDia,
                    'fecha_dia' => $fechaDia,
                ]);
            } else {
                $numeroDia = 1;
                $stmtInsertaNumero = $pdo->prepare(
                    'INSERT INTO pedido_numeracion (fecha_dia, ultimo_numero) VALUES (:fecha_dia, :ultimo_numero)'
                );
                $stmtInsertaNumero->execute([
                    'fecha_dia' => $fechaDia,
                    'ultimo_numero' => $numeroDia,
                ]);
            }

            $stmtPedido = $pdo->prepare(
                'INSERT INTO pedidos (
                    numero_dia, fecha_dia, estado, tipo, metodo_pago, total,
                    total_sin_descuento, descuento_aplicado, bistrocoins_usados,
                    bistrocoins_ganados, bistrocoins_liquidados, oferta_id, cliente_id
                 ) VALUES (
                    :numero_dia, :fecha_dia, :estado, :tipo, :metodo_pago, :total,
                    :total_sin_descuento, :descuento_aplicado, :bistrocoins_usados,
                    :bistrocoins_ganados, :bistrocoins_liquidados, :oferta_id, :cliente_id
                 )'
            );

            $stmtPedido->execute([
                'numero_dia' => $numeroDia,
                'fecha_dia' => $fechaDia,
                'estado' => $estadoInicial,
                'tipo' => $tipo,
                'metodo_pago' => $metodoPago,
                'total' => round($total, 2),
                'total_sin_descuento' => $descuentoAplicado > 0 ? round($totalSinDescuento, 2) : null,
                'descuento_aplicado' => $descuentoAplicado > 0 ? round($descuentoAplicado, 2) : null,
                'bistrocoins_usados' => $bistrocoinsUsados,
                'bistrocoins_ganados' => $bistrocoinsGanados,
                'bistrocoins_liquidados' => $liquidado,
                'oferta_id' => $ofertaAplicada ? (int) $ofertaAplicada : null,
                'cliente_id' => $clienteId,
            ]);

            $pedidoId = (int) $pdo->lastInsertId();

            $stmtLinea = $pdo->prepare(
                'INSERT INTO pedido_lineas (
                    pedido_id, producto_id, producto_nombre, precio_base, iva,
                    precio_final_unitario, cantidad, subtotal, es_recompensa,
                    bistrocoins_unit, bistrocoins_total
                 ) VALUES (
                    :pedido_id, :producto_id, :producto_nombre, :precio_base, :iva,
                    :precio_final_unitario, :cantidad, :subtotal, :es_recompensa,
                    :bistrocoins_unit, :bistrocoins_total
                 )'
            );

            foreach ($lineasPago as $linea) {
                $stmtLinea->execute([
                    'pedido_id' => $pedidoId,
                    'producto_id' => $linea['producto_id'],
                    'producto_nombre' => $linea['producto_nombre'],
                    'precio_base' => $linea['precio_base'],
                    'iva' => $linea['iva'],
                    'precio_final_unitario' => $linea['precio_final_unitario'],
                    'cantidad' => $linea['cantidad'],
                    'subtotal' => $linea['subtotal'],
                    'es_recompensa' => 0,
                    'bistrocoins_unit' => null,
                    'bistrocoins_total' => null,
                ]);
            }

            foreach ($lineasRecompensa as $linea) {
                $stmtLinea->execute([
                    'pedido_id' => $pedidoId,
                    'producto_id' => $linea['producto_id'],
                    'producto_nombre' => $linea['producto_nombre'],
                    'precio_base' => 0,
                    'iva' => 0,
                    'precio_final_unitario' => 0,
                    'cantidad' => $linea['cantidad'],
                    'subtotal' => 0,
                    'es_recompensa' => 1,
                    'bistrocoins_unit' => $linea['bistrocoins_unit'],
                    'bistrocoins_total' => $linea['bistrocoins_total'],
                ]);
            }

            if ($liquidado === 1 && ($bistrocoinsUsados > 0 || $bistrocoinsGanados > 0)) {
                $nuevoSaldo = $saldoActual - $bistrocoinsUsados + $bistrocoinsGanados;
                if ($nuevoSaldo < 0) {
                    throw new RuntimeException('Saldo de BistroCoins insuficiente.');
                }

                $stmtSaldo = $pdo->prepare('UPDATE usuarios SET bistrocoins = :bistrocoins WHERE id = :id');
                $stmtSaldo->execute([
                    'id' => $clienteId,
                    'bistrocoins' => $nuevoSaldo,
                ]);
            }

            $pdo->commit();
            return $pedidoId;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function findById(int $id): ?array
    {
        $stmt = db()->prepare(
            'SELECT p.*, 
                    u.nombre_usuario AS cliente_usuario,
                    u.nombre AS cliente_nombre,
                    c.nombre_usuario AS camarero_usuario,
                    co.nombre_usuario AS cocinero_usuario,
                    co.avatar AS cocinero_avatar
             FROM pedidos p
             INNER JOIN usuarios u ON u.id = p.cliente_id
             LEFT JOIN usuarios c ON c.id = p.camarero_id
             LEFT JOIN usuarios co ON co.id = p.cocinero_id
             WHERE p.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function lineasByPedido(int $pedidoId): array
    {
        $stmt = db()->prepare(
            'SELECT * FROM pedido_lineas WHERE pedido_id = :pedido_id ORDER BY id ASC'
        );
        $stmt->execute(['pedido_id' => $pedidoId]);
        return $stmt->fetchAll();
    }

    public static function forCliente(int $clienteId, ?array $estados = null): array
    {
        $params = ['cliente_id' => $clienteId];
        $whereEstado = self::buildEstadoWhere($estados, $params);

        $sql = 'SELECT p.*
                FROM pedidos p
                WHERE p.cliente_id = :cliente_id' . $whereEstado . '
                ORDER BY p.fecha_pedido DESC, p.id DESC';

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function all(?array $estados = null): array
    {
        $params = [];
        $whereEstado = self::buildEstadoWhere($estados, $params, 'p');

        $sql = 'SELECT p.*, 
                       u.nombre_usuario AS cliente_usuario, 
                       c.nombre_usuario AS camarero_usuario,
                       co.nombre_usuario AS cocinero_usuario,
                       co.avatar AS cocinero_avatar
                FROM pedidos p
                INNER JOIN usuarios u ON u.id = p.cliente_id
                LEFT JOIN usuarios c ON c.id = p.camarero_id
                LEFT JOIN usuarios co ON co.id = p.cocinero_id
                WHERE 1=1' . $whereEstado . '
                ORDER BY p.fecha_pedido DESC, p.id DESC';

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function pendingBistrocoinsByCliente(int $clienteId): int
    {
        $stmt = db()->prepare(
            "SELECT COALESCE(SUM(bistrocoins_usados), 0)
             FROM pedidos
             WHERE cliente_id = :cliente_id
               AND bistrocoins_liquidados = 0
               AND estado IN ('nuevo', 'recibido')"
        );
        $stmt->execute(['cliente_id' => $clienteId]);
        return (int) $stmt->fetchColumn();
    }

    public static function forCamareroPanel(): array
    {
        $stmt = db()->prepare(
            "SELECT p.*, u.nombre_usuario AS cliente_usuario
             FROM pedidos p
             INNER JOIN usuarios u ON u.id = p.cliente_id
             WHERE p.estado IN ('recibido', 'listo_cocina', 'terminado')
             ORDER BY p.fecha_pedido ASC, p.id ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function marcarEnPreparacion(int $pedidoId, int $camareroId): bool
    {
        $pdo = db();
        $pdo->beginTransaction();

        try {
            $stmtPedido = $pdo->prepare(
                "SELECT id, estado, cliente_id, bistrocoins_usados, bistrocoins_ganados, bistrocoins_liquidados
                 FROM pedidos
                 WHERE id = :id
                 FOR UPDATE"
            );
            $stmtPedido->execute(['id' => $pedidoId]);
            $pedido = $stmtPedido->fetch();

            if (!is_array($pedido) || (string) $pedido['estado'] !== 'recibido') {
                $pdo->rollBack();
                return false;
            }

            $clienteId = (int) $pedido['cliente_id'];
            $coinsUsados = (int) $pedido['bistrocoins_usados'];
            $coinsGanados = (int) $pedido['bistrocoins_ganados'];
            $liquidado = (int) $pedido['bistrocoins_liquidados'] === 1;

            if (!$liquidado && ($coinsUsados > 0 || $coinsGanados > 0)) {
                $stmtSaldo = $pdo->prepare('SELECT bistrocoins FROM usuarios WHERE id = :id FOR UPDATE');
                $stmtSaldo->execute(['id' => $clienteId]);
                $usuario = $stmtSaldo->fetch();
                if (!$usuario) {
                    $pdo->rollBack();
                    return false;
                }

                $saldoActual = (int) $usuario['bistrocoins'];
                if ($saldoActual < $coinsUsados) {
                    $pdo->rollBack();
                    return false;
                }

                $nuevoSaldo = $saldoActual - $coinsUsados + $coinsGanados;
                $stmtActualizaSaldo = $pdo->prepare('UPDATE usuarios SET bistrocoins = :bistrocoins WHERE id = :id');
                $stmtActualizaSaldo->execute([
                    'id' => $clienteId,
                    'bistrocoins' => $nuevoSaldo,
                ]);
            }

            $stmtUpdate = $pdo->prepare(
                "UPDATE pedidos
                 SET estado = 'en_preparacion',
                     camarero_id = :camarero_id,
                     bistrocoins_liquidados = 1
                 WHERE id = :id AND estado = 'recibido'"
            );
            $stmtUpdate->execute([
                'id' => $pedidoId,
                'camarero_id' => $camareroId,
            ]);

            $ok = $stmtUpdate->rowCount() === 1;
            if (!$ok) {
                $pdo->rollBack();
                return false;
            }

            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function marcarTerminado(int $pedidoId, int $camareroId): bool
    {
        $stmt = db()->prepare(
            "UPDATE pedidos 
             SET estado = 'terminado', camarero_id = :camarero_id
             WHERE id = :id AND estado = 'listo_cocina'"
        );
        $stmt->execute([
            'id' => $pedidoId,
            'camarero_id' => $camareroId,
        ]);

        return $stmt->rowCount() === 1;
    }

    public static function marcarEntregado(int $pedidoId, int $camareroId): bool
    {
        $stmt = db()->prepare(
            "UPDATE pedidos 
             SET estado = 'entregado', camarero_id = :camarero_id
             WHERE id = :id AND estado = 'terminado'"
        );
        $stmt->execute([
            'id' => $pedidoId,
            'camarero_id' => $camareroId,
        ]);

        return $stmt->rowCount() === 1;
    }

    public static function cancelarAbierto(int $pedidoId, ?int $clienteId = null): bool
    {
        $params = ['id' => $pedidoId];
        $sql = "UPDATE pedidos SET estado = 'cancelado' WHERE id = :id AND estado IN ('nuevo', 'recibido')";

        if ($clienteId !== null) {
            $sql .= ' AND cliente_id = :cliente_id';
            $params['cliente_id'] = $clienteId;
        }

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount() === 1;
    }

    public static function forCocineroPanel(): array
    {
        $stmt = db()->prepare(
            "SELECT p.*, u.nombre_usuario AS cliente_usuario
             FROM pedidos p
             INNER JOIN usuarios u ON u.id = p.cliente_id
             WHERE p.estado IN ('en_preparacion', 'cocinando')
             ORDER BY p.fecha_pedido ASC, p.id ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function tomarParaCocinar(int $pedidoId, int $cocineroId): bool
    {
        $stmt = db()->prepare(
            "UPDATE pedidos 
             SET estado = 'cocinando', cocinero_id = :cocinero_id
             WHERE id = :id AND estado = 'en_preparacion'"
        );
        $stmt->execute([
            'id' => $pedidoId,
            'cocinero_id' => $cocineroId,
        ]);

        return $stmt->rowCount() === 1;
    }

    public static function marcarLineaPreparada(int $lineaId, int $pedidoId): bool
    {
        $stmt = db()->prepare(
            "UPDATE pedido_lineas 
             SET preparado = 1 
             WHERE id = :id AND pedido_id = :pedido_id"
        );
        $stmt->execute([
            'id' => $lineaId,
            'pedido_id' => $pedidoId,
        ]);

        return $stmt->rowCount() === 1;
    }

    public static function marcarListoCocina(int $pedidoId, int $cocineroId): bool
    {
        $stmt = db()->prepare(
            "UPDATE pedidos 
             SET estado = 'listo_cocina'
             WHERE id = :id AND estado = 'cocinando' AND cocinero_id = :cocinero_id"
        );
        $stmt->execute([
            'id' => $pedidoId,
            'cocinero_id' => $cocineroId,
        ]);

        return $stmt->rowCount() === 1;
    }

    public static function estadoLabel(string $estado): string
    {
        return match ($estado) {
            'nuevo' => 'Nuevo',
            'recibido' => 'Recibido',
            'en_preparacion' => 'En preparacion',
            'cocinando' => 'Cocinando',
            'listo_cocina' => 'Listo cocina',
            'terminado' => 'Terminado',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
            default => 'Desconocido',
        };
    }

    public static function tipoLabel(string $tipo): string
    {
        return match ($tipo) {
            'local' => 'Local',
            'llevar' => 'Llevar',
            default => 'Desconocido',
        };
    }

    public static function metodoPagoLabel(string $metodo): string
    {
        return match ($metodo) {
            'tarjeta' => 'Tarjeta',
            'camarero' => 'Pagar al camarero',
            default => 'Desconocido',
        };
    }

    private static function normalizeTipo(string $tipo): string
    {
        if (!in_array($tipo, self::TIPOS, true)) {
            throw new InvalidArgumentException('Tipo de pedido invalido.');
        }

        return $tipo;
    }

    private static function normalizeMetodoPago(string $metodoPago): string
    {
        if (!in_array($metodoPago, self::METODOS_PAGO, true)) {
            throw new InvalidArgumentException('Metodo de pago invalido.');
        }

        return $metodoPago;
    }

    private static function isValidEstado(string $estado): bool
    {
        return in_array($estado, self::ESTADOS, true);
    }

    private static function buildEstadoWhere(?array $estados, array &$params, string $alias = ''): string
    {
        if ($estados === null || $estados === []) {
            return '';
        }

        $columna = $alias !== '' ? $alias . '.estado' : 'estado';
        $partes = [];
        $i = 0;

        foreach ($estados as $estado) {
            $estadoNorm = (string) $estado;
            if (!self::isValidEstado($estadoNorm)) {
                continue;
            }

            $key = 'estado_' . $i;
            $params[$key] = $estadoNorm;
            $partes[] = ':' . $key;
            $i++;
        }

        if ($partes === []) {
            return ' AND 1=0';
        }

        return ' AND ' . $columna . ' IN (' . implode(', ', $partes) . ')';
    }
}
