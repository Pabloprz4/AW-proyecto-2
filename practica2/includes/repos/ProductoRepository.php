<?php
declare(strict_types=1);

final class ProductoRepository
{
    public static function all(bool $includeNoOfertado = true): array
    {
        $sql = 'SELECT p.*, c.nombre as categoria_nombre 
                FROM productos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id';
        
        if (!$includeNoOfertado) {
            $sql .= ' WHERE p.ofertado = 1';
        }
        $sql .= ' ORDER BY p.categoria_id ASC, p.nombre ASC';

        return db()->query($sql)->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM productos WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO productos (categoria_id, nombre, descripcion, precio, iva, foto, ofertado) 
             VALUES (:categoria_id, :nombre, :descripcion, :precio, :iva, :foto, :ofertado)'
        );

        $stmt->execute([
            'categoria_id' => (int) $data['categoria_id'],
            'nombre' => (string) $data['nombre'],
            'descripcion' => $data['descripcion'] !== '' ? (string) $data['descripcion'] : null,
            'precio' => (float) $data['precio'],
            'iva' => (float) ($data['iva'] ?? 21.00),
            'foto' => $data['foto'] ?? null,
            'ofertado' => (int) ($data['ofertado'] ?? 1),
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = db()->prepare(
            'UPDATE productos 
             SET categoria_id = :categoria_id, nombre = :nombre, descripcion = :descripcion, 
                 precio = :precio, iva = :iva, ofertado = :ofertado 
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'categoria_id' => (int) $data['categoria_id'],
            'nombre' => (string) $data['nombre'],
            'descripcion' => $data['descripcion'] !== '' ? (string) $data['descripcion'] : null,
            'precio' => (float) $data['precio'],
            'iva' => (float) ($data['iva'] ?? 21.00),
            'ofertado' => (int) ($data['ofertado'] ?? 1),
        ]);
    }

    public static function setFoto(int $id, ?string $foto): bool
    {
        $stmt = db()->prepare('UPDATE productos SET foto = :foto WHERE id = :id');
        return $stmt->execute(['id' => $id, 'foto' => $foto]);
    }

    public static function setOfertado(int $id, bool $ofertado): bool
    {
        $stmt = db()->prepare('UPDATE productos SET ofertado = :ofertado WHERE id = :id');
        return $stmt->execute(['id' => $id, 'ofertado' => $ofertado ? 1 : 0]);
    }
}
