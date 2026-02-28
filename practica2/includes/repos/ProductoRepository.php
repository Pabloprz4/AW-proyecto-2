<?php
declare(strict_types=1);

final class ProductoRepository
{
    public static function all(bool $includeNoOfertado = true): array
    {
        // Hacemos un JOIN para sacar tmbn el nombre de la categoria
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

    public static function setOfertado(int $id, bool $ofertado): bool
    {
        $stmt = db()->prepare('UPDATE productos SET ofertado = :ofertado WHERE id = :id');
        return $stmt->execute(['id' => $id, 'ofertado' => $ofertado ? 1 : 0]);
    }
}
