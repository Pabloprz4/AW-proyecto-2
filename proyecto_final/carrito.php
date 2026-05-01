<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();
$cart = pedido_cart_get();
$errores = [];

if (is_post()) {
    $accion = post_enum('accion', ['set_tipo', 'update_cart', 'clear_cart', 'checkout']);

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF inválido.';
    } elseif ($accion === null) {
        $errores[] = 'Acción inválida.';
    } else {
        if ($accion === 'set_tipo') {
            $tipo = post_enum('tipo', ['local', 'llevar']);
            if ($tipo === null) {
                $errores[] = 'Tipo de pedido inválido.';
            } else {
                $cart['tipo'] = $tipo;
                pedido_cart_save($cart);
                flash_set('ok', 'Tipo de pedido actualizado.');
                redirect_to('carrito.php');
            }
        }

        if ($accion === 'update_cart') {
            $cantidades = $_POST['cantidades'] ?? [];
            $cantidadInvalida = false;
            if (!is_array($cantidades)) {
                $errores[] = 'Cantidades inválidas.';
                $cantidadInvalida = true;
                $cantidades = [];
            }

            $nuevosItems = [];
            foreach ($cantidades as $productoId => $cantidad) {
                $idStr = (string) $productoId;
                if (
                    is_array($cantidad)
                    || !preg_match('/^[1-9][0-9]*$/', $idStr)
                    || !array_key_exists($idStr, $cart['items'])
                ) {
                    $cantidadInvalida = true;
                    continue;
                }

                $qtyStr = trim((string) $cantidad);
                if (!preg_match('/^[0-9]+$/', $qtyStr)) {
                    $cantidadInvalida = true;
                    continue;
                }

                $id = (int) $idStr;
                $qty = (int) $qtyStr;
                if ($qty > 50) {
                    $cantidadInvalida = true;
                    continue;
                }

                if ($id > 0 && $qty > 0) {
                    $nuevosItems[(string) $id] = $qty;
                }
            }

            if ($cantidadInvalida) {
                $errores[] = 'El carrito contiene cantidades o productos inválidos.';
            } else {
                $cart['items'] = $nuevosItems;
                pedido_cart_save($cart);
                flash_set('ok', 'Carrito actualizado.');
                redirect_to('carrito.php');
            }
        }

        if ($accion === 'clear_cart') {
            pedido_cart_clear();
            flash_set('ok', 'Pedido cancelado: carrito vaciado.');
            redirect_to('carrito.php');
        }

        if ($accion === 'checkout') {
            $resumenValidacion = pedido_cart_resolve($cart);
            if ($resumenValidacion['lineas'] === [] || $resumenValidacion['ids_invalidos'] !== []) {
                $errores[] = 'Debes añadir al menos un producto válido al carrito.';
            } else {
                redirect_to('pedido_pago.php');
            }
        }
    }
}

$cart = pedido_cart_get();
$resumenCarrito = pedido_cart_resolve($cart);
if ($resumenCarrito['ids_invalidos'] !== []) {
    foreach ($resumenCarrito['ids_invalidos'] as $idInvalido) {
        unset($cart['items'][$idInvalido]);
    }
    pedido_cart_save($cart);
    $resumenCarrito = pedido_cart_resolve($cart);
    $errores[] = 'Se han retirado del carrito productos que ya no están ofertados o disponibles.';
}

$listaErrores = '';
if ($errores) {
    $items = '';
    foreach ($errores as $error) {
        $items .= '<li>' . h($error) . '</li>';
    }
    $listaErrores = '<div class="alert alert-error"><ul>' . $items . '</ul></div>';
}

$lineasHtml = '';
foreach ($resumenCarrito['lineas'] as $linea) {
    $foto = trim((string) ($linea['foto'] ?? ''));
    $fotoHtml = $foto !== ''
        ? '<img class="carrito-img" src="' . h(base_url($foto)) . '" alt="' . h((string) $linea['nombre']) . '">'
        : '<div class="carrito-img carrito-img-empty">Sin imagen</div>';

    $lineasHtml .= '<tr>' .
        '<td>' . $fotoHtml . '</td>' .
        '<td>' . h((string) $linea['nombre']) . '</td>' .
        '<td><input class="form-control" type="number" name="cantidades[' . (int) $linea['id'] . ']" min="0" max="50" value="' . (int) $linea['cantidad'] . '" required></td>' .
        '<td>' . h(money_eur((float) $linea['precio_final'])) . '</td>' .
        '<td>' . h(money_eur((float) $linea['subtotal'])) . '</td>' .
        '</tr>';
}

$tipoLabel = $cart['tipo'] === 'llevar' ? 'Llevar' : 'Local';
$carritoHtml = '';
if ($lineasHtml === '') {
    $carritoHtml = '<div class="alert">Tu carrito está vacío.</div>' .
        '<p><a class="btn btn-primary" href="' . h(base_url('pedido_nuevo.php')) . '">Ver carta</a></p>';
} else {
    $carritoHtml = '<form method="post" action="' . h(base_url('carrito.php')) . '">' .
        csrf_field() .
        '<input type="hidden" name="accion" value="update_cart">' .
        '<table class="table carrito-table">' .
        '<thead><tr><th>Foto</th><th>Producto</th><th>Cantidad (0 elimina)</th><th>Precio unidad</th><th>Subtotal</th></tr></thead>' .
        '<tbody>' . $lineasHtml . '</tbody>' .
        '</table>' .
        '<p><strong>Total: ' . h(money_eur((float) $resumenCarrito['total'])) . '</strong></p>' .
        '<div class="actions-inline">' .
        '<button class="btn btn-primary" type="submit">Actualizar carrito</button>' .
        '<a class="btn" href="' . h(base_url('pedido_nuevo.php')) . '">Seguir comprando</a>' .
        '</div>' .
        '</form>' .
        '<div class="carrito-actions">' .
        '<form method="post" action="' . h(base_url('carrito.php')) . '" class="inline">' .
        csrf_field() .
        '<input type="hidden" name="accion" value="clear_cart">' .
        '<button class="btn btn-danger" type="submit">Vaciar carrito</button>' .
        '</form>' .
        '<form method="post" action="' . h(base_url('carrito.php')) . '" class="inline">' .
        csrf_field() .
        '<input type="hidden" name="accion" value="checkout">' .
        '<button class="btn btn-primary" type="submit">Ir al pago</button>' .
        '</form>' .
        '</div>';
}

$contenido = <<<HTML
<section>
  <h2>Carrito</h2>
  <p>Usuario: {usuario}</p>
  {$listaErrores}
  <form class="form-row" method="post" action="{action}">
    {csrf}
    <input type="hidden" name="accion" value="set_tipo">
    <div>
      <label for="tipo">Tipo de pedido:</label>
      <select class="form-control" id="tipo" name="tipo">
        <option value="local" {sel_local}>Local</option>
        <option value="llevar" {sel_llevar}>Llevar</option>
      </select>
    </div>
    <button class="btn" type="submit">Guardar tipo</button>
  </form>
  <p>Tipo actual: <strong>{tipo_label}</strong></p>
</section>

<section class="carrito-panel">
  {$carritoHtml}
</section>
HTML;

$contenido = str_replace(
    ['{usuario}', '{action}', '{csrf}', '{sel_local}', '{sel_llevar}', '{tipo_label}'],
    [
        h((string) $usuario['nombre_usuario']),
        h(base_url('carrito.php')),
        csrf_field(),
        $cart['tipo'] === 'local' ? 'selected' : '',
        $cart['tipo'] === 'llevar' ? 'selected' : '',
        h($tipoLabel),
    ],
    $contenido
);

render_page('Carrito', $contenido);
