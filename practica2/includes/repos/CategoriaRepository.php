<?php
declare(strict_types=1);

final class CategoriaRepository
{
    public static function all(): array
    {
        $sql = 'SELECT * FROM categorias ORDER BY id ASC';
        return db()->query($sql)->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM categorias WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO categorias (nombre, descripcion) VALUES (:nombre, :descripcion)'
        );

        $stmt->execute([
            'nombre' => (string) $data['nombre'],
            'descripcion' => $data['descripcion'] !== '' ? (string) $data['descripcion'] : null,
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = db()->prepare(
            'UPDATE categorias SET nombre = :nombre, descripcion = :descripcion WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'nombre' => (string) $data['nombre'],
            'descripcion' => $data['descripcion'] !== '' ? (string) $data['descripcion'] : null,
        ]);
    }

    public static function delete(int $id): bool
    {
        try {
            // Intentamos borrarla , si tiene productos asociados, la base de datos 
            // dará error (por el ON DELETE RESTRICT que pusimos) y devolveremos false.
            $stmt = db()->prepare('DELETE FROM categorias WHERE id = :id');
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false; 
        }
    }
}
