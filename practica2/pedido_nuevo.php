<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();
$cart = pedido_cart_get();
$errores = [];

if (is_post()) {
    $accion = (string) ($_POST['accion'] ?? '');

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF invalido.';
    } else {
        if ($accion === 'set_tipo') {
            $tipo = (string) ($_POST['tipo'] ?? 'local');
            $cart['tipo'] = $tipo === 'llevar' ? 'llevar' : 'local';
            pedido_cart_save($cart);
            flash_set('ok', 'Tipo de pedido actualizado.');
            redirect_to('pedido_nuevo.php');
        }

        if ($accion === 'add_item') {
            $productoId = (int) ($_POST['producto_id'] ?? 0);
            $cantidad = (int) ($_POST['cantidad'] ?? 1);
            $cantidad = max(1, min(50, $cantidad));

            $producto = $productoId > 0 ? ProductoRepository::findById($productoId) : null;
            if (!$producto || (int) $producto['ofertado'] !== 1) {
                $errores[] = 'El producto no esta disponible.';
            } else {
                $key = (string) $productoId;
                $actual = (int) ($cart['items'][$key] ?? 0);
                $cart['items'][$key] = min(50, $actual + $cantidad);
                pedido_cart_save($cart);
                flash_set('ok', 'Producto anadido al carrito.');
                redirect_to('pedido_nuevo.php');
            }
        }

        if ($accion === 'update_cart') {
            $cantidades = $_POST['cantidades'] ?? [];
            if (!is_array($cantidades)) {
                $cantidades = [];
            }

            $nuevosItems = [];
            foreach ($cantidades as $productoId => $cantidad) {
                $id = (int) $productoId;
                $qty = (int) $cantidad;
                if ($id > 0 && $qty > 0) {
                    $nuevosItems[(string) $id] = min(50, $qty);
                }
            }

            $cart['items'] = $nuevosItems;
            pedido_cart_save($cart);
            flash_set('ok', 'Carrito actualizado.');
            redirect_to('pedido_nuevo.php');
        }

        if ($accion === 'clear_cart') {
            pedido_cart_clear();
            flash_set('ok', 'Pedido cancelado: carrito vaciado.');
            redirect_to('pedido_nuevo.php');
        }

        if ($accion === 'checkout') {
            if (empty($cart['items'])) {
                $errores[] = 'Debes anadir al menos un producto al carrito.';
            } else {
                redirect_to('pedido_pago.php');
            }
        }
    }
}

$productos = ProductoRepository::all(false);
$porCategoria = [];
foreach ($productos as $producto) {
    $categoria = (string) ($producto['categoria_nombre'] ?? 'Sin categoria');
    if (!isset($porCategoria[$categoria])) {
        $porCategoria[$categoria] = [];
    }
    $porCategoria[$categoria][] = $producto;
}

$lineasCarrito = [];
$totalCarrito = 0.0;
$idsInvalidos = [];

foreach ($cart['items'] as $productoId => $cantidad) {
    $id = (int) $productoId;
    $qty = (int) $cantidad;
    if ($id <= 0 || $qty <= 0) {
        continue;
    }

    $producto = ProductoRepository::findById($id);
    if (!$producto || (int) $producto['ofertado'] !== 1) {
        $idsInvalidos[] = (string) $id;
        continue;
    }

    $precioBase = (float) $producto['precio'];
    $iva = (float) $producto['iva'];
    $precioFinal = round($precioBase * (1 + ($iva / 100)), 2);
    $subtotal = round($precioFinal * $qty, 2);
    $totalCarrito += $subtotal;

    $lineasCarrito[] = [
        'id' => $id,
        'nombre' => (string) $producto['nombre'],
        'cantidad' => $qty,
        'precio_final' => $precioFinal,
        'subtotal' => $subtotal,
    ];
}

if ($idsInvalidos !== []) {
    foreach ($idsInvalidos as $idInvalido) {
        unset($cart['items'][$idInvalido]);
    }
    pedido_cart_save($cart);
    $errores[] = 'Se han retirado del carrito productos que ya no estan ofertados.';
}

$listaErrores = '';
if ($errores) {
    $items = '';
    foreach ($errores as $error) {
        $items .= '<li>' . h($error) . '</li>';
    }
    $listaErrores = '<ul>' . $items . '</ul>';
}

$bloquesCategorias = '';
foreach ($porCategoria as $categoriaNombre => $productosCategoria) {
    $filas = '';
    foreach ($productosCategoria as $producto) {
        $precioBase = (float) $producto['precio'];
        $iva = (float) $producto['iva'];
        $precioFinal = round($precioBase * (1 + ($iva / 100)), 2);
        $descripcion = trim((string) ($producto['descripcion'] ?? ''));

        $filas .= '<tr>' .
            '<td>' . h((string) $producto['nombre']) . '</td>' .
            '<td>' . h($descripcion) . '</td>' .
            '<td>' . h(money_eur($precioFinal)) . '</td>' .
            '<td>' .
            '<form method="post" action="' . h(base_url('pedido_nuevo.php')) . '">' .
            csrf_field() .
            '<input type="hidden" name="accion" value="add_item">' .
            '<input type="hidden" name="producto_id" value="' . (int) $producto['id'] . '">' .
            '<input type="number" name="cantidad" min="1" max="50" value="1" required>' .
            '<button type="submit">Anadir</button>' .
            '</form>' .
            '</td>' .
            '</tr>';
    }

    $bloquesCategorias .= '<section>' .
        '<h3>' . h($categoriaNombre) . '</h3>' .
        '<table border="1" cellpadding="6">' .
        '<thead><tr><th>Producto</th><th>Descripcion</th><th>Precio final</th><th>Accion</th></tr></thead>' .
        '<tbody>' . $filas . '</tbody>' .
        '</table>' .
        '</section>';
}

$lineasHtml = '';
foreach ($lineasCarrito as $linea) {
    $lineasHtml .= '<tr>' .
        '<td>' . h($linea['nombre']) . '</td>' .
        '<td><input type="number" name="cantidades[' . (int) $linea['id'] . ']" min="0" max="50" value="' . (int) $linea['cantidad'] . '" required></td>' .
        '<td>' . h(money_eur((float) $linea['precio_final'])) . '</td>' .
        '<td>' . h(money_eur((float) $linea['subtotal'])) . '</td>' .
        '</tr>';
}

$carritoHtml = '';
if ($lineasCarrito === []) {
    $carritoHtml = '<p>Tu carrito esta vacio.</p>';
} else {
    $carritoHtml = '<form method="post" action="' . h(base_url('pedido_nuevo.php')) . '">' .
        csrf_field() .
        '<input type="hidden" name="accion" value="update_cart">' .
        '<table border="1" cellpadding="6">' .
        '<thead><tr><th>Producto</th><th>Cantidad (0 elimina)</th><th>Precio unidad</th><th>Subtotal</th></tr></thead>' .
        '<tbody>' . $lineasHtml . '</tbody>' .
        '</table>' .
        '<p><strong>Total: ' . h(money_eur($totalCarrito)) . '</strong></p>' .
        '<p><button type="submit">Actualizar carrito</button></p>' .
        '</form>' .
        '<form method="post" action="' . h(base_url('pedido_nuevo.php')) . '">' .
        csrf_field() .
        '<input type="hidden" name="accion" value="clear_cart">' .
        '<button type="submit">Cancelar pedido (vaciar carrito)</button>' .
        '</form>' .
        '<form method="post" action="' . h(base_url('pedido_nuevo.php')) . '">' .
        csrf_field() .
        '<input type="hidden" name="accion" value="checkout">' .
        '<button type="submit">Ir al pago</button>' .
        '</form>';
}

$tipoLabel = $cart['tipo'] === 'llevar' ? 'Llevar' : 'Local';

$contenido = <<<HTML
<section>
  <h2>Nuevo pedido</h2>
  <p>Usuario: {$usuario['nombre_usuario']}</p>
  {$listaErrores}
  <form method="post" action="{action}">
    {csrf}
    <input type="hidden" name="accion" value="set_tipo">
    <label for="tipo">Tipo de pedido:</label>
    <select id="tipo" name="tipo">
      <option value="local" {sel_local}>Local</option>
      <option value="llevar" {sel_llevar}>Llevar</option>
    </select>
    <button type="submit">Guardar tipo</button>
  </form>
  <p>Tipo actual: <strong>{$tipoLabel}</strong></p>
</section>

<section>
  <h2>Carta de productos ofertados</h2>
  {$bloquesCategorias}
</section>

<section>
  <h2>Carrito</h2>
  {$carritoHtml}
</section>
HTML;

$contenido = str_replace(
    ['{action}', '{csrf}', '{sel_local}', '{sel_llevar}'],
    [
        h(base_url('pedido_nuevo.php')),
        csrf_field(),
        $cart['tipo'] === 'local' ? 'selected' : '',
        $cart['tipo'] === 'llevar' ? 'selected' : '',
    ],
    $contenido
);

render_page('Nuevo pedido', $contenido);
