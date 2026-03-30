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

    public static function createFromCart(int $clienteId, string $tipo, string $metodoPago, array $items): int
    {
        if ($clienteId <= 0) {
            throw new InvalidArgumentException('Cliente invalido.');
        }

        if (empty($items)) {
            throw new InvalidArgumentException('El pedido no puede estar vacio.');
        }

        $tipo = self::normalizeTipo($tipo);
        $metodoPago = self::normalizeMetodoPago($metodoPago);
        $estadoInicial = $metodoPago === 'tarjeta' ? 'en_preparacion' : 'recibido';

        $pdo = db();
        $pdo->beginTransaction();

        try {
            $lineas = [];
            $total = 0.0;
            $stmtProducto = $pdo->prepare(
                'SELECT id, nombre, precio, iva, ofertado, disponible FROM productos WHERE id = :id LIMIT 1'
            );

            foreach ($items as $item) {
                $productoId = (int) ($item['producto_id'] ?? 0);
                $cantidad = (int) ($item['cantidad'] ?? 0);

                if ($productoId <= 0 || $cantidad <= 0) {
                    continue;
                }

                $stmtProducto->execute(['id' => $productoId]);
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
                $subtotal = round($precioFinalUnitario * $cantidad, 2);
                $total += $subtotal;

                $lineas[] = [
                    'producto_id' => (int) $producto['id'],
                    'producto_nombre' => (string) $producto['nombre'],
                    'precio_base' => round($precioBase, 2),
                    'iva' => round($iva, 2),
                    'precio_final_unitario' => $precioFinalUnitario,
                    'cantidad' => $cantidad,
                    'subtotal' => $subtotal,
                ];
            }

            if (empty($lineas)) {
                throw new RuntimeException('No hay lineas validas para crear el pedido.');
            }

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
                'INSERT INTO pedidos (numero_dia, fecha_dia, estado, tipo, metodo_pago, total, cliente_id)
                 VALUES (:numero_dia, :fecha_dia, :estado, :tipo, :metodo_pago, :total, :cliente_id)'
            );

            $stmtPedido->execute([
                'numero_dia' => $numeroDia,
                'fecha_dia' => $fechaDia,
                'estado' => $estadoInicial,
                'tipo' => $tipo,
                'metodo_pago' => $metodoPago,
                'total' => round($total, 2),
                'cliente_id' => $clienteId,
            ]);

            $pedidoId = (int) $pdo->lastInsertId();

            $stmtLinea = $pdo->prepare(
                'INSERT INTO pedido_lineas (
                    pedido_id, producto_id, producto_nombre, precio_base, iva,
                    precio_final_unitario, cantidad, subtotal
                 ) VALUES (
                    :pedido_id, :producto_id, :producto_nombre, :precio_base, :iva,
                    :precio_final_unitario, :cantidad, :subtotal
                 )'
            );

            foreach ($lineas as $linea) {
                $stmtLinea->execute([
                    'pedido_id' => $pedidoId,
                    'producto_id' => $linea['producto_id'],
                    'producto_nombre' => $linea['producto_nombre'],
                    'precio_base' => $linea['precio_base'],
                    'iva' => $linea['iva'],
                    'precio_final_unitario' => $linea['precio_final_unitario'],
                    'cantidad' => $linea['cantidad'],
                    'subtotal' => $linea['subtotal'],
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
        $stmt = db()->prepare(
            "UPDATE pedidos 
             SET estado = 'en_preparacion', camarero_id = :camarero_id
             WHERE id = :id AND estado = 'recibido'"
        );
        $stmt->execute([
            'id' => $pedidoId,
            'camarero_id' => $camareroId,
        ]);

        return $stmt->rowCount() === 1;
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
        return in_array($tipo, self::TIPOS, true) ? $tipo : 'local';
    }

    private static function normalizeMetodoPago(string $metodoPago): string
    {
        return in_array($metodoPago, self::METODOS_PAGO, true) ? $metodoPago : 'camarero';
    }

    private static function normalizeEstado(string $estado): string
    {
        return in_array($estado, self::ESTADOS, true) ? $estado : 'nuevo';
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
            $estadoNorm = self::normalizeEstado((string) $estado);
            $key = 'estado_' . $i;
            $params[$key] = $estadoNorm;
            $partes[] = ':' . $key;
            $i++;
        }

        return ' AND ' . $columna . ' IN (' . implode(', ', $partes) . ')';
    }
}
