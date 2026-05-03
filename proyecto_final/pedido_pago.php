<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();
$cart = pedido_cart_get();

if (($cart['items'] ?? []) === [] && ($cart['recompensas'] ?? []) === []) {
    flash_set('error', 'Tu carrito esta vacio. Debes crear un pedido primero.');
    redirect_to('carrito.php');
}

$resumenCarrito = pedido_cart_resolve($cart);
if ($resumenCarrito['ids_invalidos'] !== [] || $resumenCarrito['ids_recompensas_invalidas'] !== []) {
    foreach ($resumenCarrito['ids_invalidos'] as $idInvalido) {
        unset($cart['items'][$idInvalido]);
    }
    foreach ($resumenCarrito['ids_recompensas_invalidas'] as $idInvalido) {
        unset($cart['recompensas'][$idInvalido]);
    }
    pedido_cart_save($cart);
    flash_set('error', 'Se han retirado elementos invalidos del carrito. Revisa productos y recompensas.');
    redirect_to('carrito.php');
}

$lineasPago = $resumenCarrito['lineas'];
$lineasRecompensa = $resumenCarrito['lineas_recompensa'];
$total = (float) $resumenCarrito['total'];
$bistrocoinsUsados = (int) $resumenCarrito['bistrocoins_usados'];

if ($lineasPago === [] && $lineasRecompensa === []) {
    flash_set('error', 'No hay elementos validos para pagar.');
    redirect_to('carrito.php');
}

$errores = [];
$metodoPago = is_post() ? (post_enum('metodo_pago', ['tarjeta', 'camarero']) ?? '') : 'camarero';
$tarjeta = [
    'numero' => post_trimmed_string('tarjeta_numero'),
    'titular' => post_trimmed_string('tarjeta_titular'),
    'caducidad' => post_trimmed_string('tarjeta_caducidad'),
    'cvv' => post_trimmed_string('tarjeta_cvv'),
];

if (is_post()) {
    if (!verify_csrf()) {
        $errores[] = 'Token CSRF inválido.';
    }

    if ($metodoPago === '') {
        $errores[] = 'Método de pago inválido.';
    }

    if ($metodoPago === 'tarjeta') {
        $numero = preg_replace('/\s+/', '', $tarjeta['numero']);
        if (!is_string($numero) || !preg_match('/^\d{13,19}$/', $numero)) {
            $errores[] = 'Número de tarjeta inválido.';
        }

        if (strlen($tarjeta['titular']) < 3) {
            $errores[] = 'Titular de tarjeta inválido.';
        }

        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $tarjeta['caducidad'])) {
            $errores[] = 'Caducidad inválida (MM/AA).';
        }

        if (!preg_match('/^\d{3,4}$/', $tarjeta['cvv'])) {
            $errores[] = 'CVV inválido.';
        }
    }

    if (!$errores) {
        try {
            $pedidoId = PedidoRepository::createFromCart(
                $cart,
                (int) $usuario['id'],
                $metodoPago
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
        $items .= '<p class="payment-error-item">' . h($error) . '</p>';
    }
    $listaErrores = '<div class="alert alert-error payment-error-summary" role="alert">' .
        '<strong>No se pudo confirmar el pago.</strong>' .
        '<p>Revisa los datos marcados antes de continuar.</p>' .
        '<div class="payment-error-list">' . $items . '</div>' .
        '</div>';
}

$filasResumen = '';
foreach ($lineasPago as $linea) {
    $filasResumen .= '<tr>' .
        '<td>' . h((string) $linea['nombre']) . '</td>' .
        '<td>' . (int) $linea['cantidad'] . '</td>' .
        '<td>' . h(money_eur((float) $linea['precio_final'])) . '</td>' .
        '<td>' . h(money_eur((float) $linea['subtotal'])) . '</td>' .
        '</tr>';
}
foreach ($lineasRecompensa as $linea) {
    $filasResumen .= '<tr>' .
        '<td>' . h((string) $linea['nombre']) . ' (Recompensa)</td>' .
        '<td>' . (int) $linea['cantidad'] . '</td>' .
        '<td>0.00 EUR</td>' .
        '<td>0.00 EUR</td>' .
        '</tr>';
}

$tipoLabel = PedidoRepository::tipoLabel((string) $cart['tipo']);

$contenido = <<<HTML
<section>
  <h2>Pago del pedido</h2>
  {$listaErrores}
  <p>Tipo de pedido: <strong>{$tipoLabel}</strong></p>
  <table class="table">
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
  <p><strong>BistroCoins usados: {coins_usados}</strong></p>
  <p><strong>Total a pagar: {total}</strong></p>
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
        '{coins_usados}',
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
        (string) $bistrocoinsUsados,
        h(money_eur($total)),
        h(base_url('pedido_pago.php')),
        csrf_field(),
        $metodoPago === 'tarjeta' ? 'checked' : '',
        $metodoPago === 'camarero' ? 'checked' : '',
        h($tarjeta['numero']),
        h($tarjeta['titular']),
        h($tarjeta['caducidad']),
        h($tarjeta['cvv']),
        h(base_url('carrito.php')),
    ],
    $contenido
);

render_page('Pago del pedido', $contenido);
