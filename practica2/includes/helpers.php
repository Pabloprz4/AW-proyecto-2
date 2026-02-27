<?php
declare(strict_types=1);

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function base_url(string $path = ''): string
{
    $base = defined('RUTA_APP') ? (string) RUTA_APP : '';
    $base = rtrim($base, '/');
    $path = ltrim($path, '/');

    if ($base === '') {
        return $path === '' ? '/' : '/' . $path;
    }

    return $path === '' ? $base . '/' : $base . '/' . $path;
}

function redirect_to(string $path): void
{
    header('Location: ' . base_url($path));
    exit;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'][$type] = $message;
}

function flash_get(string $type): ?string
{
    if (!isset($_SESSION['flash'][$type])) {
        return null;
    }

    $message = (string) $_SESSION['flash'][$type];
    unset($_SESSION['flash'][$type]);
    return $message;
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

function verify_csrf(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals((string) $_SESSION['csrf_token'], $token);
}

function role_level(string $role): int
{
    return match ($role) {
        'cliente' => 1,
        'camarero' => 2,
        'cocinero' => 3,
        'gerente' => 4,
        default => 0,
    };
}

function has_role(string $requiredRole, ?string $currentRole = null): bool
{
    $role = $currentRole;
    if ($role === null) {
        $user = auth_user();
        $role = $user['rol'] ?? '';
    }

    return role_level((string) $role) >= role_level($requiredRole);
}

function render_page(string $title, string $content): void
{
    $tituloPagina = $title;
    $contenidoPrincipal = $content;
    require APP_ROOT . '/includes/vistas/plantillas/plantilla.php';
}
