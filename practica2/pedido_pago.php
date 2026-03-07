<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();
$cart = pedido_cart_get();

if (empty($cart['items'])) {
    flash_set('error', 'Tu carrito esta vacio. Debes crear un pedido primero.');
    redirect_to('pedido_nuevo.php');
}

$lineasValidas = [];
$total = 0.0;
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
    $total += $subtotal;

    $lineasValidas[] = [
        'producto_id' => $id,
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
}

if ($lineasValidas === []) {
    flash_set('error', 'No hay productos validos para pagar.');
    redirect_to('pedido_nuevo.php');
}

$errores = [];
$metodoPago = (string) ($_POST['metodo_pago'] ?? 'camarero');
$tarjeta = [
    'numero' => trim((string) ($_POST['tarjeta_numero'] ?? '')),
    'titular' => trim((string) ($_POST['tarjeta_titular'] ?? '')),
    'caducidad' => trim((string) ($_POST['tarjeta_caducidad'] ?? '')),
    'cvv' => trim((string) ($_POST['tarjeta_cvv'] ?? '')),
];

if (is_post()) {
    if (!verify_csrf()) {
        $errores[] = 'Token CSRF invalido.';
    }

    if (!in_array($metodoPago, ['tarjeta', 'camarero'], true)) {
        $errores[] = 'Metodo de pago invalido.';
    }

    if ($metodoPago === 'tarjeta') {
        $numero = preg_replace('/\s+/', '', $tarjeta['numero']);
        if (!is_string($numero) || !preg_match('/^\d{13,19}$/', $numero)) {
            $errores[] = 'Numero de tarjeta invalido.';
        }

        if (strlen($tarjeta['titular']) < 3) {
            $errores[] = 'Titular de tarjeta invalido.';
        }

        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $tarjeta['caducidad'])) {
            $errores[] = 'Caducidad invalida (MM/AA).';
        }

        if (!preg_match('/^\d{3,4}$/', $tarjeta['cvv'])) {
            $errores[] = 'CVV invalido.';
        }
    }

    if (!$errores) {
        try {
            $pedidoId = PedidoRepository::createFromCart(
                (int) $usuario['id'],
                (string) $cart['tipo'],
                $metodoPago,
                $lineasValidas
            );

            pedido_cart_clear();
            flash_set('ok', 'Pedido creado correctamente.');
            redirect_to('pedido_confirmacion.php?id=' . $pedidoId);
        } catch (Throwable $e) {
            $errores[] = 'No se pudo registrar el pedido: ' . $e->getMessage();
        }
    }
}

$listaErrores = '';
if ($errores) {
    $items = '';
    foreach ($errores as $error) {
        $items .= '<li>' . h($error) . '</li>';
    }
    $listaErrores = '<ul>' . $items . '</ul>';
}

$filasResumen = '';
foreach ($lineasValidas as $linea) {
    $filasResumen .= '<tr>' .
        '<td>' . h($linea['nombre']) . '</td>' .
        '<td>' . (int) $linea['cantidad'] . '</td>' .
        '<td>' . h(money_eur((float) $linea['precio_final'])) . '</td>' .
        '<td>' . h(money_eur((float) $linea['subtotal'])) . '</td>' .
        '</tr>';
}

$tipoLabel = PedidoRepository::tipoLabel((string) $cart['tipo']);

$contenido = <<<HTML
<section>
  <h2>Pago del pedido</h2>
  {$listaErrores}
  <p>Tipo de pedido: <strong>{$tipoLabel}</strong></p>
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio unidad</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      {$filasResumen}
    </tbody>
  </table>
  <p><strong>Total: {total}</strong></p>
</section>

<section>
  <h2>Metodo de pago</h2>
  <form method="post" action="{action}">
    {csrf}
    <p>
      <label>
        <input type="radio" name="metodo_pago" value="tarjeta" {pago_tarjeta}>
        Tarjeta (simulada)
      </label><br>
      <label>
        <input type="radio" name="metodo_pago" value="camarero" {pago_camarero}>
        Pagar al camarero
      </label>
    </p>
    <fieldset>
      <legend>Datos de tarjeta (solo si eliges tarjeta)</legend>
      <p>
        <label for="tarjeta_numero">Numero:</label><br>
        <input type="text" id="tarjeta_numero" name="tarjeta_numero" value="{tarjeta_numero}">
      </p>
      <p>
        <label for="tarjeta_titular">Titular:</label><br>
        <input type="text" id="tarjeta_titular" name="tarjeta_titular" value="{tarjeta_titular}">
      </p>
      <p>
        <label for="tarjeta_caducidad">Caducidad (MM/AA):</label><br>
        <input type="text" id="tarjeta_caducidad" name="tarjeta_caducidad" value="{tarjeta_caducidad}">
      </p>
      <p>
        <label for="tarjeta_cvv">CVV:</label><br>
        <input type="text" id="tarjeta_cvv" name="tarjeta_cvv" value="{tarjeta_cvv}">
      </p>
    </fieldset>
    <p>
      <button type="submit">Confirmar y pagar</button>
      <a href="{volver}">Volver al carrito</a>
    </p>
  </form>
</section>
HTML;

$contenido = str_replace(
    [
        '{total}',
        '{action}',
        '{csrf}',
        '{pago_tarjeta}',
        '{pago_camarero}',
        '{tarjeta_numero}',
        '{tarjeta_titular}',
        '{tarjeta_caducidad}',
        '{tarjeta_cvv}',
        '{volver}',
    ],
    [
        h(money_eur($total)),
        h(base_url('pedido_pago.php')),
        csrf_field(),
        $metodoPago === 'tarjeta' ? 'checked' : '',
        $metodoPago === 'camarero' ? 'checked' : '',
        h($tarjeta['numero']),
        h($tarjeta['titular']),
        h($tarjeta['caducidad']),
        h($tarjeta['cvv']),
        h(base_url('pedido_nuevo.php')),
    ],
    $contenido
);

render_page('Pago del pedido', $contenido);
