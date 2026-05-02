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

function request_positive_int(array $source, string $key): ?int
{
    if (!array_key_exists($key, $source) || is_array($source[$key])) {
        return null;
    }

    $value = trim((string) $source[$key]);
    if (!preg_match('/^[1-9][0-9]*$/', $value)) {
        return null;
    }

    return (int) $value;
}

function post_positive_int(string $key): ?int
{
    return request_positive_int($_POST, $key);
}

function get_positive_int(string $key): ?int
{
    return request_positive_int($_GET, $key);
}

function request_int_range(array $source, string $key, int $min, int $max): ?int
{
    if (!array_key_exists($key, $source) || is_array($source[$key])) {
        return null;
    }

    $value = trim((string) $source[$key]);
    if (!preg_match('/^-?[0-9]+$/', $value)) {
        return null;
    }

    $intValue = (int) $value;
    if ($intValue < $min || $intValue > $max) {
        return null;
    }

    return $intValue;
}

function post_int_range(string $key, int $min, int $max): ?int
{
    return request_int_range($_POST, $key, $min, $max);
}

function request_enum(array $source, string $key, array $allowed): ?string
{
    if (!array_key_exists($key, $source) || is_array($source[$key])) {
        return null;
    }

    $value = trim((string) $source[$key]);
    return in_array($value, $allowed, true) ? $value : null;
}

function post_enum(string $key, array $allowed): ?string
{
    return request_enum($_POST, $key, $allowed);
}

function request_trimmed_string(array $source, string $key): string
{
    if (!array_key_exists($key, $source) || is_array($source[$key])) {
        return '';
    }

    return trim((string) $source[$key]);
}

function post_trimmed_string(string $key): string
{
    return request_trimmed_string($_POST, $key);
}

function post_string(string $key): string
{
    if (!array_key_exists($key, $_POST) || is_array($_POST[$key])) {
        return '';
    }

    return (string) $_POST[$key];
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

function money_eur(float $amount): string
{
    return number_format($amount, 2, '.', '') . ' EUR';
}

function predefined_avatars(): array
{
    if (!defined('PREDEFINED_AVATARS') || !is_array(PREDEFINED_AVATARS)) {
        return [];
    }

    $clean = [];
    foreach (PREDEFINED_AVATARS as $avatar) {
        $path = trim((string) $avatar);
        if ($path !== '') {
            $clean[] = $path;
        }
    }

    return array_values(array_unique($clean));
}

function default_avatar_path(): string
{
    $configured = defined('DEFAULT_AVATAR') ? trim((string) DEFAULT_AVATAR) : '';
    if ($configured !== '') {
        return $configured;
    }

    $predefined = predefined_avatars();
    if ($predefined !== []) {
        return (string) $predefined[0];
    }

    return 'uploads/avatars/predef_default.svg';
}

function is_predefined_avatar(string $avatarPath): bool
{
    return in_array($avatarPath, predefined_avatars(), true);
}

function is_upload_avatar(string $avatarPath): bool
{
    return str_starts_with($avatarPath, 'uploads/avatars/');
}

function resolve_avatar_path(?string $avatarPath): string
{
    $avatar = trim((string) $avatarPath);
    if ($avatar === '') {
        return default_avatar_path();
    }

    if (is_predefined_avatar($avatar) || is_upload_avatar($avatar)) {
        return $avatar;
    }

    return default_avatar_path();
}

function avatar_web_url(?string $avatarPath): string
{
    return base_url(resolve_avatar_path($avatarPath));
}

function pedido_cart_get(): array
{
    $cart = $_SESSION['pedido_cart'] ?? null;
    if (!is_array($cart)) {
        return [
            'tipo' => 'local',
            'items' => [],
            'oferta_aplicada' => null,
        ];
    }

    $tipo = ((string) ($cart['tipo'] ?? 'local')) === 'llevar' ? 'llevar' : 'local';
    $items = [];

    if (isset($cart['items']) && is_array($cart['items'])) {
        foreach ($cart['items'] as $productoId => $cantidad) {
            $id = (int) $productoId;
            $qty = (int) $cantidad;
            if ($id > 0 && $qty > 0) {
                $items[(string) $id] = $qty;
            }
        }
    }

    return [
        'tipo' => $tipo,
        'items' => $items,
        'oferta_aplicada' => $cart['oferta_aplicada'] ?? null,
    ];
}

function pedido_cart_save(array $cart): void
{
    $tipo = ((string) ($cart['tipo'] ?? 'local')) === 'llevar' ? 'llevar' : 'local';
    $items = [];

    if (isset($cart['items']) && is_array($cart['items'])) {
        foreach ($cart['items'] as $productoId => $cantidad) {
            $id = (int) $productoId;
            $qty = (int) $cantidad;
            if ($id > 0 && $qty > 0) {
                $items[(string) $id] = min($qty, 50);
            }
        }
    }

    $_SESSION['pedido_cart'] = [
        'tipo' => $tipo,
        'items' => $items,
        'oferta_aplicada' => $cart['oferta_aplicada'] ?? null,
    ];
}

function pedido_cart_clear(): void
{
    unset($_SESSION['pedido_cart']);
}

function pedido_cart_resolve(array $cart): array
{
    $lineas = [];
    $total = 0.0;
    $cantidadTotal = 0;
    $idsInvalidos = [];
    $descuentoAplicado = 0.0;

    $items = isset($cart['items']) && is_array($cart['items']) ? $cart['items'] : [];

    foreach ($items as $productoId => $cantidad) {
        $id = (int) $productoId;
        $qty = (int) $cantidad;
        if ($id <= 0 || $qty <= 0) {
            continue;
        }

        $producto = ProductoRepository::findById($id);
        if (
            !$producto
            || (int) $producto['ofertado'] !== 1
            || (int) ($producto['disponible'] ?? 0) !== 1
        ) {
            $idsInvalidos[] = (string) $id;
            continue;
        }

        $precioBase = (float) $producto['precio'];
        $iva = (float) $producto['iva'];
        $precioFinal = round($precioBase * (1 + ($iva / 100)), 2);
        $subtotal = round($precioFinal * $qty, 2);
        $total += $subtotal;
        $cantidadTotal += $qty;

        $lineas[] = [
            'id' => $id,
            'producto_id' => $id,
            'nombre' => (string) $producto['nombre'],
            'foto' => trim((string) ($producto['foto'] ?? '')),
            'cantidad' => $qty,
            'precio_final' => $precioFinal,
            'subtotal' => $subtotal,
        ];
    }

    // Aplicar oferta si existe
    $ofertaAplicada = $cart['oferta_aplicada'] ?? null;
    if ($ofertaAplicada) {
        $oferta = \OfertaRepository::findByIdWithProducts($ofertaAplicada);
        if ($oferta) {
            
            $vecesAplicables = PHP_INT_MAX;
            foreach ($oferta['productos'] as $prod) {
                $prodId = (string) $prod['producto_id'];
                $requerido = (int) $prod['cantidad'];
                $disponible = (int) ($cart['items'][$prodId] ?? 0);
                $veces = floor($disponible / $requerido);
                $vecesAplicables = min($vecesAplicables, $veces);
            }
            
            if ($vecesAplicables > 0) {
                $precioPack = \OfertaRepository::calculatePackPrice($oferta['productos']);
                $descuento = (float) $oferta['descuento'];
                $descuentoAplicado = round($precioPack * ($descuento / 100), 2) * $vecesAplicables;
                $total -= $descuentoAplicado;
                if ($total < 0) $total = 0;
            }
        }
    }

    return [
        'lineas' => $lineas,
        'total' => $total,
        'cantidad_total' => $cantidadTotal,
        'ids_invalidos' => $idsInvalidos,
        'descuento_aplicado' => $descuentoAplicado,
    ];
}

function render_page(string $title, string $content): void
{
    $app = \es\ucm\fdi\aw\Aplicacion::getInstance();
    $params = [
        'tituloPagina' => $title,
        'cabecera' => $title,
        'contenidoPrincipal' => $content,
    ];

    $app->generaVista('/plantillas/plantilla.php', $params);
}
