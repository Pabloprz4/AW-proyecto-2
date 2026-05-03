<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_role('cliente');
$usuarioDb = UsuarioRepository::findById((int) $usuario['id']);
if (!$usuarioDb) {
    auth_logout();
    flash_set('error', 'Sesion invalida. Vuelve a iniciar sesion.');
    redirect_to('login.php');
}

$cart = pedido_cart_get();
$resumenCart = pedido_cart_resolve($cart);
$errores = [];

if ($resumenCart['ids_recompensas_invalidas'] !== []) {
    foreach ($resumenCart['ids_recompensas_invalidas'] as $idInvalido) {
        unset($cart['recompensas'][$idInvalido]);
    }
    pedido_cart_save($cart);
    $resumenCart = pedido_cart_resolve($cart);
    $errores[] = 'Se han retirado recompensas invalidas del carrito.';
}

$saldoActual = (int) ($usuarioDb['bistrocoins'] ?? 0);
$saldoReservado = PedidoRepository::pendingBistrocoinsByCliente((int) $usuarioDb['id']);
$saldoDisponible = max(0, $saldoActual - $saldoReservado);
$coinsCarrito = (int) $resumenCart['bistrocoins_usados'];
$coinsRestantesParaCarrito = max(0, $saldoDisponible - $coinsCarrito);

if (is_post()) {
    $accion = post_enum('accion', ['add_reward', 'remove_reward']);

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF invalido.';
    } elseif ($accion === null) {
        $errores[] = 'Accion invalida.';
    } elseif ($accion === 'add_reward') {
        $recompensaId = post_positive_int('recompensa_id');
        $cantidad = post_int_range('cantidad', 1, 20);

        if ($recompensaId === null || $cantidad === null) {
            $errores[] = 'Datos de recompensa invalidos.';
        } else {
            $recompensa = RecompensaRepository::findById($recompensaId);
            if (
                !$recompensa
                || (int) $recompensa['activo'] !== 1
                || (int) ($recompensa['ofertado'] ?? 0) !== 1
                || (int) ($recompensa['disponible'] ?? 0) !== 1
            ) {
                $errores[] = 'La recompensa seleccionada no esta disponible.';
            } else {
                $key = (string) $recompensaId;
                $actualQty = (int) ($cart['recompensas'][$key] ?? 0);
                $nuevoQty = min(20, $actualQty + $cantidad);
                $incrementoQty = $nuevoQty - $actualQty;

                if ($incrementoQty <= 0) {
                    $errores[] = 'Esa recompensa ya esta al maximo permitido en carrito.';
                } else {
                    $costeUnit = (int) $recompensa['bistrocoins'];
                    $costeExtra = $incrementoQty * $costeUnit;

                    if (($coinsCarrito + $costeExtra) > $saldoDisponible) {
                        $errores[] = 'No tienes BistroCoins suficientes para ese canje.';
                    } else {
                        $cart['recompensas'][$key] = $nuevoQty;
                        pedido_cart_save($cart);
                        flash_set('ok', 'Recompensa anadida al carrito.');
                        redirect_to('recompensas_cliente.php?added=' . $recompensaId);
                    }
                }
            }
        }
    } elseif ($accion === 'remove_reward') {
        $recompensaId = post_positive_int('recompensa_id');
        if ($recompensaId === null) {
            $errores[] = 'Recompensa invalida.';
        } else {
            unset($cart['recompensas'][(string) $recompensaId]);
            pedido_cart_save($cart);
            flash_set('ok', 'Recompensa eliminada del carrito.');
            redirect_to('recompensas_cliente.php');
        }
    }
}

$cart = pedido_cart_get();
$resumenCart = pedido_cart_resolve($cart);
$coinsCarrito = (int) $resumenCart['bistrocoins_usados'];
$coinsRestantesParaCarrito = max(0, $saldoDisponible - $coinsCarrito);
$recompensas = RecompensaRepository::all(false);

$totalRecompensasEnCarrito = 0;
foreach ($resumenCart['lineas_recompensa'] as $lineaRecompensa) {
    $totalRecompensasEnCarrito += (int) $lineaRecompensa['cantidad'];
}

$listaErrores = '';
if ($errores) {
    $items = '';
    foreach ($errores as $error) {
        $items .= '<li>' . h($error) . '</li>';
    }
    $listaErrores = '<div class="alert alert-error"><ul>' . $items . '</ul></div>';
}

$listaCarritoRewards = '';
if ($resumenCart['lineas_recompensa'] === []) {
    $listaCarritoRewards = '<p>No tienes recompensas en el carrito.</p>';
} else {
    $items = '';
    foreach ($resumenCart['lineas_recompensa'] as $lineaRecompensa) {
        $items .= '<li>' .
            h((string) $lineaRecompensa['nombre']) .
            ' x' . (int) $lineaRecompensa['cantidad'] .
            ' (' . (int) $lineaRecompensa['bistrocoins_total'] . ' BistroCoins)' .
            '</li>';
    }
    $listaCarritoRewards = '<ul class="reward-cart-list">' . $items . '</ul>';
}

$cardsHtml = '';
foreach ($recompensas as $recompensa) {
    $rewardId = (int) $recompensa['id'];
    $cartQty = (int) ($cart['recompensas'][(string) $rewardId] ?? 0);
    $costeUnitario = (int) $recompensa['bistrocoins'];
    $precioFinalProducto = round(
        (float) $recompensa['precio'] * (1 + ((float) $recompensa['iva'] / 100)),
        2
    );
    $productoDisponible = (int) ($recompensa['ofertado'] ?? 0) === 1 && (int) ($recompensa['disponible'] ?? 0) === 1;
    $canjeableSaldo = $productoDisponible && $saldoDisponible >= $costeUnitario;
    $canjeableAhora = $productoDisponible && $coinsRestantesParaCarrito >= $costeUnitario;
    $clases = 'card reward-card ';
    $clases .= $canjeableAhora ? 'reward-card-available ' : 'reward-card-unavailable ';
    if ($cartQty > 0) {
        $clases .= 'reward-card-in-cart ';
    }

    $estado = $productoDisponible
        ? ($canjeableSaldo
            ? 'Tienes saldo suficiente para esta recompensa.'
            : 'No tienes saldo suficiente para esta recompensa.')
        : 'Producto temporalmente no disponible para canje.';

    $estadoCarrito = !$productoDisponible
        ? 'No puedes anadirla mientras el producto no este disponible.'
        : ($canjeableAhora
            ? 'Puedes anadirla ahora.'
            : 'No puedes anadir mas ahora con el saldo restante.');

    $maxAnadible = max(1, 20 - $cartQty);
    $disableForm = (!$productoDisponible || $maxAnadible <= 0) ? ' disabled' : '';

    $cartBadge = $cartQty > 0
        ? '<span class="badge badge-accion-progreso">En carrito: ' . $cartQty . '</span>'
        : '<span class="badge">No anadida</span>';

    $removeForm = '';
    if ($cartQty > 0) {
        $removeForm = '<form method="post" action="' . h(base_url('recompensas_cliente.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="accion" value="remove_reward">' .
            '<input type="hidden" name="recompensa_id" value="' . $rewardId . '">' .
            '<button class="btn btn-danger" type="submit">Quitar del carrito</button>' .
            '</form>';
    }

    $cardsHtml .= '<article id="reward-card-' . $rewardId . '" class="' . trim($clases) . '" data-reward-id="' . $rewardId . '" data-coins-unit="' . $costeUnitario . '">' .
        '<div class="reward-card-head">' .
        '<h3>' . h((string) $recompensa['producto_nombre']) . '</h3>' .
        $cartBadge .
        '</div>' .
        '<p><strong>Coste:</strong> ' . $costeUnitario . ' BistroCoins</p>' .
        '<p><strong>Precio normal:</strong> ' . h(money_eur($precioFinalProducto)) . '</p>' .
        '<p class="reward-status-text">' . h($estado) . '</p>' .
        '<p class="reward-status-text">' . h($estadoCarrito) . '</p>' .
        '<form class="reward-add-form" method="post" action="' . h(base_url('recompensas_cliente.php')) . '" data-reward-name="' . h((string) $recompensa['producto_nombre']) . '" data-coins-unit="' . $costeUnitario . '" data-maxed="' . ($maxAnadible <= 0 ? '1' : '0') . '" data-disabled-product="' . ($productoDisponible ? '0' : '1') . '">' .
        csrf_field() .
        '<input type="hidden" name="accion" value="add_reward">' .
        '<input type="hidden" name="recompensa_id" value="' . $rewardId . '">' .
        '<label for="cantidad_reward_' . $rewardId . '">Cantidad:</label>' .
        '<input class="form-control reward-qty-input" id="cantidad_reward_' . $rewardId . '" type="number" name="cantidad" min="1" max="' . $maxAnadible . '" value="1"' . $disableForm . ' required>' .
        '<p class="reward-cost-preview" aria-live="polite"></p>' .
        '<button class="btn btn-primary reward-add-button" type="submit"' . $disableForm . '>Anadir recompensa</button>' .
        '</form>' .
        $removeForm .
        '</article>';
}

if ($cardsHtml === '') {
    $cardsHtml = '<div class="alert">No hay recompensas activas en este momento.</div>';
}

$addedRewardId = get_positive_int('added');
$addedRewardData = $addedRewardId !== null ? (string) $addedRewardId : '';

$contenido = <<<HTML
<section id="rewards-page" data-coins-remaining="{coins_remaining}" data-added-reward="{added_reward}">
  <h2>Recompensas BistroCoins</h2>
  {$listaErrores}
  <p>Usuario: <strong>{usuario}</strong></p>
  <div class="card reward-summary-card" id="reward-summary-card">
    <p><strong>BistroCoins actuales:</strong> {coins_actuales}</p>
    <p><strong>BistroCoins reservados (pedidos pendientes de pago):</strong> {coins_reservados}</p>
    <p><strong>BistroCoins disponibles:</strong> {coins_disponibles}</p>
    <p><strong>BistroCoins usados en carrito:</strong> <span id="coins-cart">{coins_carrito}</span></p>
    <p><strong>BistroCoins restantes para nuevos canjes:</strong> <span id="coins-remaining">{coins_remaining}</span></p>
    <p><strong>Recompensas en carrito:</strong> {rewards_count}</p>
    <p><a class="btn btn-primary" href="{carrito_url}">Ir al carrito</a></p>
    {$listaCarritoRewards}
  </div>
</section>

<section>
  <h3>Recompensas disponibles</h3>
  <div class="grid rewards-grid">
    {$cardsHtml}
  </div>
</section>

<script>
(function () {
  var page = document.getElementById('rewards-page');
  if (!page) return;

  var remainingCoins = Number(page.getAttribute('data-coins-remaining') || '0');
  var addedReward = page.getAttribute('data-added-reward');
  var summaryCard = document.getElementById('reward-summary-card');

  function updateCardPreview(form) {
    var input = form.querySelector('.reward-qty-input');
    var preview = form.querySelector('.reward-cost-preview');
    var button = form.querySelector('.reward-add-button');
    var card = form.closest('.reward-card');
    if (!input || !preview || !button || !card) return;

    var isMaxed = form.getAttribute('data-maxed') === '1';
    var productDisabled = form.getAttribute('data-disabled-product') === '1';
    if (productDisabled) {
      preview.textContent = 'Esta recompensa no esta disponible ahora mismo.';
      button.disabled = true;
      return;
    }

    if (isMaxed) {
      preview.textContent = 'Cantidad maxima alcanzada en carrito para esta recompensa.';
      button.disabled = true;
      return;
    }

    var unit = Number(form.getAttribute('data-coins-unit') || '0');
    var qty = Number(input.value || '0');
    if (!Number.isInteger(qty) || qty < 1) {
      preview.textContent = 'Cantidad invalida.';
      button.disabled = true;
      card.classList.add('reward-card-unavailable');
      card.classList.remove('reward-card-available');
      return;
    }

    var total = unit * qty;
    var affordable = total <= remainingCoins;
    preview.textContent = 'Coste del canje: ' + total + ' BistroCoins.';
    button.disabled = !affordable;
    card.classList.toggle('reward-card-available', affordable);
    card.classList.toggle('reward-card-unavailable', !affordable);
  }

  var forms = document.querySelectorAll('.reward-add-form');
  forms.forEach(function (form) {
    var input = form.querySelector('.reward-qty-input');
    if (input) {
      input.addEventListener('input', function () {
        updateCardPreview(form);
      });
    }

    form.addEventListener('submit', function (event) {
      if (form.getAttribute('data-disabled-product') === '1') {
        event.preventDefault();
        return;
      }

      if (form.getAttribute('data-maxed') === '1') {
        event.preventDefault();
        return;
      }

      var unit = Number(form.getAttribute('data-coins-unit') || '0');
      var qty = Number((form.querySelector('.reward-qty-input') || {}).value || '0');
      if (!Number.isInteger(qty) || qty < 1) {
        event.preventDefault();
        return;
      }

      var total = unit * qty;
      if (total > remainingCoins) {
        event.preventDefault();
        return;
      }

      var rewardName = form.getAttribute('data-reward-name') || 'esta recompensa';
      var message = 'Vas a canjear ' + qty + ' unidad(es) de ' + rewardName + ' por ' + total + ' BistroCoins. Continuar?';
      if (!window.confirm(message)) {
        event.preventDefault();
      }
    });

    updateCardPreview(form);
  });

  if (addedReward) {
    var card = document.getElementById('reward-card-' + addedReward);
    if (card) {
      card.classList.add('reward-card-added');
      card.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    if (summaryCard) {
      summaryCard.classList.add('reward-summary-updated');
      setTimeout(function () {
        summaryCard.classList.remove('reward-summary-updated');
      }, 1100);
    }
  }
})();
</script>
HTML;

$contenido = str_replace(
    ['{usuario}', '{coins_actuales}', '{coins_reservados}', '{coins_disponibles}', '{coins_carrito}', '{coins_remaining}', '{rewards_count}', '{carrito_url}', '{added_reward}'],
    [
        h((string) $usuarioDb['nombre_usuario']),
        (string) $saldoActual,
        (string) $saldoReservado,
        (string) $saldoDisponible,
        (string) $coinsCarrito,
        (string) $coinsRestantesParaCarrito,
        (string) $totalRecompensasEnCarrito,
        h(base_url('carrito.php')),
        h($addedRewardData),
    ],
    $contenido
);

render_page('Recompensas', $contenido);
