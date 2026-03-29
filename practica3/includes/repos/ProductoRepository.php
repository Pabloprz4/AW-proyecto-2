<?php
declare(strict_types=1);

final class ProductoRepository
{
    public static function all(bool $includeNoOfertado = true): array
    {
        $sql = 'SELECT p.*, c.nombre AS categoria_nombre,
                       (SELECT COUNT(*) FROM producto_imagenes pi WHERE pi.producto_id = p.id) AS imagenes_count
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id';

        $where = [];
        if (!$includeNoOfertado) {
            $where[] = 'p.ofertado = 1';
            $where[] = 'p.disponible = 1';
        }

        if ($where !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY p.categoria_id ASC, p.nombre ASC';

        return db()->query($sql)->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = db()->prepare(
            'SELECT p.*,
                    (SELECT COUNT(*) FROM producto_imagenes pi WHERE pi.producto_id = p.id) AS imagenes_count
             FROM productos p
             WHERE p.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function create(array $data): int
    {
        $stmt = db()->prepare(
            'INSERT INTO productos (categoria_id, nombre, descripcion, precio, iva, foto, disponible, ofertado)
             VALUES (:categoria_id, :nombre, :descripcion, :precio, :iva, :foto, :disponible, :ofertado)'
        );

        $stmt->execute([
            'categoria_id' => (int) $data['categoria_id'],
            'nombre' => (string) $data['nombre'],
            'descripcion' => $data['descripcion'] !== '' ? (string) $data['descripcion'] : null,
            'precio' => (float) $data['precio'],
            'iva' => (float) ($data['iva'] ?? 21.00),
            'foto' => $data['foto'] ?? null,
            'disponible' => (int) ($data['disponible'] ?? 1),
            'ofertado' => (int) ($data['ofertado'] ?? 1),
        ]);

        return (int) db()->lastInsertId();
    }

    public static function update(int $id, array $data): bool
    {
        $stmt = db()->prepare(
            'UPDATE productos 
             SET categoria_id = :categoria_id, nombre = :nombre, descripcion = :descripcion, 
                 precio = :precio, iva = :iva, disponible = :disponible, ofertado = :ofertado
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'categoria_id' => (int) $data['categoria_id'],
            'nombre' => (string) $data['nombre'],
            'descripcion' => $data['descripcion'] !== '' ? (string) $data['descripcion'] : null,
            'precio' => (float) $data['precio'],
            'iva' => (float) ($data['iva'] ?? 21.00),
            'disponible' => (int) ($data['disponible'] ?? 1),
            'ofertado' => (int) ($data['ofertado'] ?? 1),
        ]);
    }

    public static function setFoto(int $id, ?string $foto): bool
    {
        $stmt = db()->prepare('UPDATE productos SET foto = :foto WHERE id = :id');
        return $stmt->execute(['id' => $id, 'foto' => $foto]);
    }

    public static function setDisponible(int $id, bool $disponible): bool
    {
        $stmt = db()->prepare('UPDATE productos SET disponible = :disponible WHERE id = :id');
        return $stmt->execute(['id' => $id, 'disponible' => $disponible ? 1 : 0]);
    }

    public static function setOfertado(int $id, bool $ofertado): bool
    {
        $stmt = db()->prepare('UPDATE productos SET ofertado = :ofertado WHERE id = :id');
        return $stmt->execute(['id' => $id, 'ofertado' => $ofertado ? 1 : 0]);
    }

    public static function imagesByProducto(int $productoId): array
    {
        $stmt = db()->prepare(
            'SELECT id, producto_id, ruta, orden, creado_en
             FROM producto_imagenes
             WHERE producto_id = :producto_id
             ORDER BY orden ASC, id ASC'
        );
        $stmt->execute(['producto_id' => $productoId]);
        return $stmt->fetchAll();
    }

    public static function addImage(int $productoId, string $ruta): int
    {
        $stmtOrden = db()->prepare(
            'SELECT COALESCE(MAX(orden), 0) + 1 FROM producto_imagenes WHERE producto_id = :producto_id'
        );
        $stmtOrden->execute(['producto_id' => $productoId]);
        $orden = (int) $stmtOrden->fetchColumn();

        $stmt = db()->prepare(
            'INSERT INTO producto_imagenes (producto_id, ruta, orden)
             VALUES (:producto_id, :ruta, :orden)'
        );
        $stmt->execute([
            'producto_id' => $productoId,
            'ruta' => $ruta,
            'orden' => $orden,
        ]);

        return (int) db()->lastInsertId();
    }

    public static function addImages(int $productoId, array $rutas): void
    {
        foreach ($rutas as $ruta) {
            $rutaStr = trim((string) $ruta);
            if ($rutaStr === '') {
                continue;
            }
            self::addImage($productoId, $rutaStr);
        }
    }

    public static function deleteImages(int $productoId, array $imageIds): int
    {
        $ids = [];
        foreach ($imageIds as $id) {
            $imageId = (int) $id;
            if ($imageId > 0) {
                $ids[] = $imageId;
            }
        }

        if ($ids === []) {
            return 0;
        }

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));
        $sql = 'DELETE FROM producto_imagenes WHERE producto_id = ? AND id IN (' . $placeholders . ')';
        $stmt = db()->prepare($sql);
        $stmt->bindValue(1, $productoId, PDO::PARAM_INT);
        $pos = 2;
        foreach ($ids as $id) {
            $stmt->bindValue($pos, $id, PDO::PARAM_INT);
            $pos++;
        }
        $stmt->execute();

        return $stmt->rowCount();
    }

    public static function ensureMainImageInCollection(int $productoId, ?string $ruta): void
    {
        $ruta = trim((string) $ruta);
        if ($ruta === '') {
            return;
        }

        $stmt = db()->prepare(
            'SELECT COUNT(*) FROM producto_imagenes WHERE producto_id = :producto_id AND ruta = :ruta'
        );
        $stmt->execute([
            'producto_id' => $productoId,
            'ruta' => $ruta,
        ]);

        if ((int) $stmt->fetchColumn() === 0) {
            self::addImage($productoId, $ruta);
        }
    }

    public static function syncFotoWithImages(int $productoId): void
    {
        $stmt = db()->prepare(
            'SELECT ruta
             FROM producto_imagenes
             WHERE producto_id = :producto_id
             ORDER BY orden ASC, id ASC
             LIMIT 1'
        );
        $stmt->execute(['producto_id' => $productoId]);
        $ruta = $stmt->fetchColumn();

        self::setFoto($productoId, is_string($ruta) && $ruta !== '' ? $ruta : null);
    }
}
