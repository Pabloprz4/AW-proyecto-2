<?php
declare(strict_types=1);

final class RecompensaRepository
{
    public static function all(bool $includeInactive = true): array
    {
        $sql = 'SELECT r.*, p.nombre AS producto_nombre, p.precio, p.iva, p.ofertado, p.disponible
                FROM recompensas r
                INNER JOIN productos p ON p.id = r.producto_id';

        if (!$includeInactive) {
            $sql .= ' WHERE r.activo = 1';
        }

        $sql .= ' ORDER BY r.id ASC';

        return db()->query($sql)->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = db()->prepare(
            'SELECT r.*, p.nombre AS producto_nombre, p.precio, p.iva, p.ofertado, p.disponible
             FROM recompensas r
             INNER JOIN productos p ON p.id = r.producto_id
             WHERE r.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function findByProductId(int $productoId): ?array
    {
        $stmt = db()->prepare(
            'SELECT r.*, p.nombre AS producto_nombre, p.precio, p.iva, p.ofertado, p.disponible
             FROM recompensas r
             INNER JOIN productos p ON p.id = r.producto_id
             WHERE r.producto_id = :producto_id
             LIMIT 1'
        );
        $stmt->execute(['producto_id' => $productoId]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO recompensas (producto_id, bistrocoins, activo)
             VALUES (:producto_id, :bistrocoins, :activo)'
        );
        $stmt->execute([
            'producto_id' => (int) $data['producto_id'],
            'bistrocoins' => (int) $data['bistrocoins'],
            'activo' => (int) ($data['activo'] ?? 1),
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = db()->prepare(
            'UPDATE recompensas
             SET producto_id = :producto_id,
                 bistrocoins = :bistrocoins,
                 activo = :activo
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'producto_id' => (int) $data['producto_id'],
            'bistrocoins' => (int) $data['bistrocoins'],
            'activo' => (int) ($data['activo'] ?? 1),
        ]);

        return $stmt->rowCount() === 1;
    }

    public static function delete(int $id): bool
    {
        $stmt = db()->prepare('DELETE FROM recompensas WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount() === 1;
    }
}
