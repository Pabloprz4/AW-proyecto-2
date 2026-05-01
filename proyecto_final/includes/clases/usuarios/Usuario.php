<?php
namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\MagicProperties;

class Usuario
{
    use MagicProperties;

    public const ADMIN_ROLE = 1;

    public const USER_ROLE = 2;

    public static function login($nombreUsuario, $password)
    {
        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->compruebaPassword($password)) {
            return $usuario;
        }

        return false;
    }

    public static function crea($nombreUsuario, $password, $nombre, $rol)
    {
        self::ensureReposLoaded();

        if (\UsuarioRepository::usernameExists((string) $nombreUsuario)) {
            return false;
        }

        $id = \UsuarioRepository::create([
            'nombre_usuario' => (string) $nombreUsuario,
            'email' => (string) $nombreUsuario . '@legacy.local',
            'nombre' => (string) $nombre,
            'apellidos' => '',
            'password' => (string) $password,
            'rol' => ((int) $rol === self::ADMIN_ROLE) ? 'gerente' : 'cliente',
            'avatar' => null,
            'activo' => 1,
        ]);

        return self::buscaPorId($id);
    }

    public static function buscaUsuario($nombreUsuario)
    {
        self::ensureReposLoaded();
        $row = \UsuarioRepository::findByUsername((string) $nombreUsuario);

        return is_array($row) ? self::fromRow($row) : false;
    }

    public static function buscaPorId($idUsuario)
    {
        self::ensureReposLoaded();
        $id = (int) $idUsuario;
        if ($id <= 0) {
            return false;
        }

        $row = \UsuarioRepository::findById($id);
        return is_array($row) ? self::fromRow($row) : false;
    }

    private static function ensureReposLoaded(): void
    {
        $includesDir = dirname(__DIR__, 2);

        if (!function_exists('db')) {
            require_once $includesDir . '/db.php';
        }

        if (!class_exists('\\UsuarioRepository')) {
            require_once $includesDir . '/repos/UsuarioRepository.php';
        }
    }

    private static function fromRow(array $row): self
    {
        return new self(
            (string) $row['nombre_usuario'],
            (string) $row['password_hash'],
            (string) $row['nombre'],
            (int) $row['id'],
            self::rolesFromRole((string) $row['rol'])
        );
    }

    private static function rolesFromRole(string $role): array
    {
        return $role === 'gerente'
            ? [self::ADMIN_ROLE, self::USER_ROLE]
            : [self::USER_ROLE];
    }

    private static function hashPassword($password): string
    {
        return password_hash((string) $password, PASSWORD_DEFAULT);
    }

    private $id;

    private $nombreUsuario;

    private $password;

    private $nombre;

    private $roles;

    private function __construct($nombreUsuario, $password, $nombre, $id = null, $roles = [])
    {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->roles = $roles;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function añadeRol($role)
    {
        $role = (int) $role;
        if (in_array($role, [self::ADMIN_ROLE, self::USER_ROLE], true) && !in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function tieneRol($role)
    {
        return in_array((int) $role, $this->roles, true);
    }

    public function compruebaPassword($password)
    {
        return password_verify((string) $password, $this->password);
    }

    public function cambiaPassword($nuevoPassword)
    {
        $this->password = self::hashPassword($nuevoPassword);
    }

    public function guarda()
    {
        throw new \RuntimeException('La persistencia legacy de usuarios no forma parte del flujo actual.');
    }

    public function borrate()
    {
        return false;
    }
}
