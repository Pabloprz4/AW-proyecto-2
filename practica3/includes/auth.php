<?php
declare(strict_types=1);

function auth_user(): ?array
{
    $app = \es\ucm\fdi\aw\Aplicacion::getInstance();

    if (!$app->usuarioLogueado()) {
        return null;
    }

    $id = (int) $app->idUsuario();
    $nombre = (string) $app->nombreUsuario();
    $rolPorDefecto = $app->esAdmin() ? 'gerente' : 'cliente';

    $usuarioRepo = null;
    if (class_exists('UsuarioRepository')) {
        try {
            $usuarioRepo = UsuarioRepository::findById($id);
        } catch (\Throwable $e) {
            $usuarioRepo = null;
        }
    }

    if (is_array($usuarioRepo)) {
        return [
            'id' => (int) $usuarioRepo['id'],
            'nombre_usuario' => (string) ($usuarioRepo['nombre_usuario'] ?? ('u' . $id)),
            'nombre' => (string) ($usuarioRepo['nombre'] ?? $nombre),
            'rol' => (string) ($usuarioRepo['rol'] ?? $rolPorDefecto),
            'avatar' => isset($usuarioRepo['avatar']) && $usuarioRepo['avatar'] !== null
                ? (string) $usuarioRepo['avatar']
                : null,
        ];
    }

    return [
        'id' => $id,
        'nombre_usuario' => 'u' . $id,
        'nombre' => $nombre,
        'rol' => $rolPorDefecto,
        'avatar' => null,
    ];
}

function auth_check(): bool
{
    return auth_user() !== null;
}

function auth_login(string $username, string $password): bool
{
    $user = UsuarioRepository::findByUsername($username, true);
    if (!$user) {
        return false;
    }

    if (!password_verify($password, (string) $user['password_hash'])) {
        return false;
    }

    session_regenerate_id(true);

    // Compatibilidad con la sesion del sistema base de practica3
    $_SESSION['login'] = true;
    $_SESSION['nombre'] = (string) $user['nombre'];
    $_SESSION['idUsuario'] = (int) $user['id'];

    $rol = (string) $user['rol'];
    $_SESSION['roles'] = $rol === 'gerente' ? [1, 2] : [2];

    return true;
}

function auth_logout(): void
{
    unset($_SESSION['login'], $_SESSION['nombre'], $_SESSION['idUsuario'], $_SESSION['roles']);
    session_regenerate_id(true);
}

function require_login(): array
{
    $user = auth_user();

    if ($user === null) {
        flash_set('error', 'Debes iniciar sesion para acceder.');
        redirect_to('login.php');
    }

    return $user;
}

function require_role(string $role): array
{
    $user = require_login();

    if (!has_role($role, (string) ($user['rol'] ?? ''))) {
        flash_set('error', 'No tienes permisos para acceder a esta pagina.');
        redirect_to('index.php');
    }

    return $user;
}
