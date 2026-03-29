<?php
declare(strict_types=1);

final class UsuarioRepository
{
    private const ROLES = ['cliente', 'camarero', 'cocinero', 'gerente'];

    public static function all(bool $includeInactive = true): array
    {
        $sql = 'SELECT id, nombre_usuario, email, nombre, apellidos, rol, avatar, activo, creado_en, actualizado_en FROM usuarios';
        if (!$includeInactive) {
            $sql .= ' WHERE activo = 1';
        }
        $sql .= ' ORDER BY id ASC';

        return db()->query($sql)->fetchAll();
    }

    public static function findById(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM usuarios WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function findByUsername(string $username, bool $activeOnly = true): ?array
    {
        $sql = 'SELECT * FROM usuarios WHERE nombre_usuario = :nombre_usuario';
        if ($activeOnly) {
            $sql .= ' AND activo = 1';
        }
        $sql .= ' LIMIT 1';

        $stmt = db()->prepare($sql);
        $stmt->execute(['nombre_usuario' => $username]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public static function usernameExists(string $username, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM usuarios WHERE nombre_usuario = :nombre_usuario';
        $params = ['nombre_usuario' => $username];

        if ($excludeId !== null) {
            $sql .= ' AND id <> :exclude_id';
            $params['exclude_id'] = $excludeId;
        }

        $stmt = db()->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    public static function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM usuarios WHERE email = :email';
        $params = ['email' => $email];

        if ($excludeId !== null) {
            $sql .= ' AND id <> :exclude_id';
            $params['exclude_id'] = $excludeId;
        }

        $stmt = db()->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    public static function create(array $data): int
    {
        $rol = self::normalizeRole((string) ($data['rol'] ?? 'cliente'));
        $passwordHash = password_hash((string) $data['password'], PASSWORD_DEFAULT);

        $stmt = db()->prepare(
            'INSERT INTO usuarios (nombre_usuario, email, nombre, apellidos, password_hash, rol, avatar, activo)
             VALUES (:nombre_usuario, :email, :nombre, :apellidos, :password_hash, :rol, :avatar, :activo)'
        );

        $stmt->execute([
            'nombre_usuario' => (string) $data['nombre_usuario'],
            'email' => (string) $data['email'],
            'nombre' => (string) $data['nombre'],
            'apellidos' => (string) $data['apellidos'],
            'password_hash' => $passwordHash,
            'rol' => $rol,
            'avatar' => $data['avatar'] ?? null,
            'activo' => (int) ($data['activo'] ?? 1),
        ]);

        return (int) db()->lastInsertId();
    }

    public static function updateProfile(int $id, array $data): bool
    {
        $fields = [
            'nombre_usuario = :nombre_usuario',
            'email = :email',
            'nombre = :nombre',
            'apellidos = :apellidos',
        ];

        $params = [
            'id' => $id,
            'nombre_usuario' => (string) $data['nombre_usuario'],
            'email' => (string) $data['email'],
            'nombre' => (string) $data['nombre'],
            'apellidos' => (string) $data['apellidos'],
        ];

        if (!empty($data['password'])) {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = password_hash((string) $data['password'], PASSWORD_DEFAULT);
        }

        $sql = 'UPDATE usuarios SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $stmt = db()->prepare($sql);

        return $stmt->execute($params);
    }

    public static function setRole(int $id, string $rol): bool
    {
        $stmt = db()->prepare('UPDATE usuarios SET rol = :rol WHERE id = :id');
        return $stmt->execute(['id' => $id, 'rol' => self::normalizeRole($rol)]);
    }

    public static function setAvatar(int $id, ?string $avatar): bool
    {
        $stmt = db()->prepare('UPDATE usuarios SET avatar = :avatar WHERE id = :id');
        return $stmt->execute(['id' => $id, 'avatar' => $avatar]);
    }

    public static function setActivo(int $id, bool $activo): bool
    {
        $stmt = db()->prepare('UPDATE usuarios SET activo = :activo WHERE id = :id');
        return $stmt->execute(['id' => $id, 'activo' => $activo ? 1 : 0]);
    }

    private static function normalizeRole(string $role): string
    {
        if (!in_array($role, self::ROLES, true)) {
            return 'cliente';
        }

        return $role;
    }
}
