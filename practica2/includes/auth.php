<?php
declare(strict_types=1);

function auth_user(): ?array
{
    $user = $_SESSION['auth_user'] ?? null;
    return is_array($user) ? $user : null;
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

    $_SESSION['auth_user'] = [
        'id' => (int) $user['id'],
        'nombre_usuario' => (string) $user['nombre_usuario'],
        'nombre' => (string) $user['nombre'],
        'rol' => (string) $user['rol'],
        'avatar' => $user['avatar'] ? (string) $user['avatar'] : null,
    ];

    return true;
}

function auth_logout(): void
{
    unset($_SESSION['auth_user']);
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
