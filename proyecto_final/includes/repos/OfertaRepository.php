<?php
declare(strict_types=1);

final class OfertaRepository
{
    public static function all(bool $includeInactive = false): array
    {
        $sql = 'SELECT o.* FROM ofertas o';

        $where = [];
        if (!$includeInactive) {
            $where[] = 'o.activo = 1';
        }

        if ($where !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY o.fecha_inicio DESC, o.nombre ASC';

        return db()->query($sql)->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = db()->prepare(
            'SELECT o.* FROM ofertas o WHERE o.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function findByIdWithProducts(int $id): ?array
    {
        $oferta = self::findById($id);
        if (!$oferta) {
            return null;
        }

        $stmt = db()->prepare(
            'SELECT op.*, p.nombre AS producto_nombre, p.precio, p.iva
             FROM oferta_productos op
             LEFT JOIN productos p ON op.producto_id = p.id
             WHERE op.oferta_id = :oferta_id
             ORDER BY op.id ASC'
        );
        $stmt->execute(['oferta_id' => $id]);
        $oferta['productos'] = $stmt->fetchAll();

        return $oferta;
    }

    public static function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO ofertas (nombre, descripcion, descuento, fecha_inicio, fecha_fin, activo)
             VALUES (:nombre, :descripcion, :descuento, :fecha_inicio, :fecha_fin, :activo)'
        );

        $stmt->execute([
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'descuento' => $data['descuento'],
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin' => $data['fecha_fin'],
            'activo' => $data['activo'] ?? 1,
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = db()->prepare(
            'UPDATE ofertas SET
             nombre = :nombre,
             descripcion = :descripcion,
             descuento = :descuento,
             fecha_inicio = :fecha_inicio,
             fecha_fin = :fecha_fin,
             activo = :activo,
             actualizado_en = NOW()
             WHERE id = :id'
        );

        $stmt->execute([
            'id' => $id,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'descuento' => $data['descuento'],
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin' => $data['fecha_fin'],
            'activo' => $data['activo'] ?? 1,
        ]);

        return $stmt->rowCount() > 0;
    }

    public static function delete(int $id): bool
    {
        $stmt = db()->prepare('DELETE FROM ofertas WHERE id = :id');
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public static function setProducts(int $ofertaId, array $productos): void
    {
        // Primero, borrar productos existentes
        $stmt = db()->prepare('DELETE FROM oferta_productos WHERE oferta_id = :oferta_id');
        $stmt->execute(['oferta_id' => $ofertaId]);

        // Insertar nuevos
        $stmt = db()->prepare(
            'INSERT INTO oferta_productos (oferta_id, producto_id, cantidad)
             VALUES (:oferta_id, :producto_id, :cantidad)'
        );

        foreach ($productos as $producto) {
            $stmt->execute([
                'oferta_id' => $ofertaId,
                'producto_id' => $producto['producto_id'],
                'cantidad' => $producto['cantidad'],
            ]);
        }
    }

    public static function getActiveOffers(): array
    {
        $today = date('Y-m-d');
        $stmt = db()->prepare(
            'SELECT o.* FROM ofertas o
             WHERE o.activo = 1 AND o.fecha_inicio <= ? AND o.fecha_fin >= ?
             ORDER BY o.nombre ASC'
        );
        $stmt->execute([$today, $today]);

        $ofertas = $stmt->fetchAll();
        
        // Cargar productos para cada oferta
        foreach ($ofertas as &$oferta) {
            $oferta['productos'] = self::getOfertaProducts((int) $oferta['id']);
        }

        return $ofertas;
    }

    private static function getOfertaProducts(int $ofertaId): array
    {
        $stmt = db()->prepare(
            'SELECT op.*, p.nombre AS producto_nombre, p.precio, p.iva
             FROM oferta_productos op
             LEFT JOIN productos p ON op.producto_id = p.id
             WHERE op.oferta_id = ?
             ORDER BY op.id ASC'
        );
        $stmt->execute([$ofertaId]);

        return $stmt->fetchAll();
    }

    public static function calculatePackPrice(array $productos): float
    {
        $total = 0.0;
        foreach ($productos as $prod) {
            $precio = (float) ($prod['precio'] ?? 0);
            $iva = (float) ($prod['iva'] ?? 0);
            $precioConIva = $precio * (1 + ($iva / 100));
            $total += $precioConIva * (int) ($prod['cantidad'] ?? 0);
        }
        return $total;
    }
}