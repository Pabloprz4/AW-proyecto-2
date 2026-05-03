<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();
$cart = pedido_cart_get();
$errores = [];

if (is_post()) {
    $accion = post_enum('accion', ['set_tipo', 'update_cart', 'clear_cart', 'checkout', 'apply_offer', 'remove_offer', 'remove_reward']);

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF invalido.';
    } elseif ($accion === null) {
        $errores[] = 'Accion invalida.';
    } else {
        if ($accion === 'set_tipo') {
            $tipo = post_enum('tipo', ['local', 'llevar']);
            if ($tipo === null) {
                $errores[] = 'Tipo de pedido invalido.';
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
                $errores[] = 'Cantidades invalidas.';
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
                $errores[] = 'El carrito contiene cantidades o productos invalidos.';
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
            $sinElementos = $resumenValidacion['lineas'] === [] && $resumenValidacion['lineas_recompensa'] === [];
            if ($sinElementos || $resumenValidacion['ids_invalidos'] !== [] || $resumenValidacion['ids_recompensas_invalidas'] !== []) {
                $errores[] = 'Debes anadir al menos un elemento valido al carrito.';
            } else {
                redirect_to('pedido_pago.php');
            }
        }

        if ($accion === 'apply_offer') {
            $ofertaId = post_positive_int('oferta_id');
            if ($ofertaId === null) {
                $errores[] = 'Oferta invalida.';
            } else {
                $oferta = OfertaRepository::findByIdWithProducts($ofertaId);
                if (!$oferta) {
                    $errores[] = 'Oferta no encontrada.';
                } else {
                    $applicable = true;
                    foreach ($oferta['productos'] as $prod) {
                        $prodId = (string) $prod['producto_id'];
                        $required = (int) $prod['cantidad'];
                        $available = (int) ($cart['items'][$prodId] ?? 0);
                        if ($available < $required) {
                            $applicable = false;
                            break;
                        }
                    }
                    if (!$applicable) {
                        $errores[] = 'La oferta no es aplicable al carrito actual.';
                    } else {
                        $cart['oferta_aplicada'] = $ofertaId;
                        pedido_cart_save($cart);
                        flash_set('ok', 'Oferta aplicada.');
                        redirect_to('carrito.php');
                    }
                }
            }
        }

        if ($accion === 'remove_offer') {
            unset($cart['oferta_aplicada']);
            pedido_cart_save($cart);
            flash_set('ok', 'Oferta removida.');
            redirect_to('carrito.php');
        }

        if ($accion === 'remove_reward') {
            $recompensaId = post_positive_int('recompensa_id');
            if ($recompensaId === null) {
                $errores[] = 'Recompensa invalida.';
            } else {
                unset($cart['recompensas'][(string) $recompensaId]);
                pedido_cart_save($cart);
                flash_set('ok', 'Recompensa eliminada del carrito.');
                redirect_to('carrito.php');
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
    $errores[] = 'Se han retirado del carrito productos que ya no estan ofertados o disponibles.';
}
if ($resumenCarrito['ids_recompensas_invalidas'] !== []) {
    foreach ($resumenCarrito['ids_recompensas_invalidas'] as $idInvalido) {
        unset($cart['recompensas'][$idInvalido]);
    }
    $errores[] = 'Se han retirado recompensas que ya no estan disponibles.';
}
if ($resumenCarrito['ids_invalidos'] !== [] || $resumenCarrito['ids_recompensas_invalidas'] !== []) {
    pedido_cart_save($cart);
    $resumenCarrito = pedido_cart_resolve($cart);
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

$recompensasHtml = '';
if ($resumenCarrito['lineas_recompensa'] !== []) {
    $recompensasHtml .= '<h3>Recompensas en carrito</h3>';
    $recompensasHtml .= '<div class="grid rewards-grid" id="cart-rewards-grid">';
    foreach ($resumenCarrito['lineas_recompensa'] as $linea) {
        $recompensasHtml .= '<article class="card reward-in-cart">' .
            '<h4>' . h((string) $linea['nombre']) . '</h4>' .
            '<p>Cantidad: <strong>' . (int) $linea['cantidad'] . '</strong></p>' .
            '<p>Coste: <strong>' . (int) $linea['bistrocoins_total'] . ' BistroCoins</strong></p>' .
            '<form method="post" action="' . h(base_url('carrito.php')) . '">' .
            csrf_field() .
            '<input type="hidden" name="accion" value="remove_reward">' .
            '<input type="hidden" name="recompensa_id" value="' . (int) $linea['recompensa_id'] . '">' .
            '<button class="btn btn-danger" type="submit">Quitar recompensa</button>' .
            '</form>' .
            '</article>';
    }
    $recompensasHtml .= '</div>';
}

$tipoLabel = $cart['tipo'] === 'llevar' ? 'Llevar' : 'Local';

// Ofertas
$ofertas = OfertaRepository::getActiveOffers();
$ofertasHtml = '';
$ofertaAplicada = $cart['oferta_aplicada'] ?? null;
if ($ofertaAplicada) {
    $oferta = OfertaRepository::findById((int) $ofertaAplicada);
    if ($oferta) {
        $ofertasHtml .= '<p>Oferta aplicada: ' . h((string) $oferta['nombre']) . ' (' . h((string) $oferta['descuento']) . '% descuento)</p>';
    }
    $ofertasHtml .= '<form method="post" action="' . h(base_url('carrito.php')) . '" class="inline">';
    $ofertasHtml .= csrf_field();
    $ofertasHtml .= '<input type="hidden" name="accion" value="remove_offer">';
    $ofertasHtml .= '<button class="btn" type="submit">Quitar oferta</button>';
    $ofertasHtml .= '</form>';
} else {
    $ofertasHtml .= '<h3>Ofertas disponibles</h3>';
    if (empty($ofertas)) {
        $ofertasHtml .= '<p>No hay ofertas activas.</p>';
    } else {
        foreach ($ofertas as $o) {
            $applicable = true;
            foreach ($o['productos'] as $prod) {
                $prodId = (string) $prod['producto_id'];
                $required = (int) $prod['cantidad'];
                $available = (int) ($cart['items'][$prodId] ?? 0);
                if ($available < $required) {
                    $applicable = false;
                    break;
                }
            }
            $status = $applicable ? 'Aplicable' : 'No aplicable';
            $productosReq = '';
            foreach ($o['productos'] as $prod) {
                $productosReq .= (string) $prod['producto_nombre'] . ' x' . (int) $prod['cantidad'] . ', ';
            }
            $productosReq = rtrim($productosReq, ', ');
            $ofertasHtml .= '<div class="oferta-item">';
            $ofertasHtml .= '<h4>' . h((string) $o['nombre']) . '</h4>';
            $ofertasHtml .= '<p>' . h((string) $o['descripcion']) . '</p>';
            $ofertasHtml .= '<p>Requiere: ' . h($productosReq) . '</p>';
            $ofertasHtml .= '<p>Descuento: ' . h((string) $o['descuento']) . '%</p>';
            $ofertasHtml .= '<p>Estado: ' . $status . '</p>';
            if ($applicable) {
                $ofertasHtml .= '<form method="post" action="' . h(base_url('carrito.php')) . '" class="inline">';
                $ofertasHtml .= csrf_field();
                $ofertasHtml .= '<input type="hidden" name="accion" value="apply_offer">';
                $ofertasHtml .= '<input type="hidden" name="oferta_id" value="' . (int) $o['id'] . '">';
                $ofertasHtml .= '<button class="btn btn-primary" type="submit">Aplicar oferta</button>';
                $ofertasHtml .= '</form>';
            }
            $ofertasHtml .= '</div>';
        }
    }
}

$carritoHtml = '';
if ($lineasHtml === '') {
    if ($resumenCarrito['lineas_recompensa'] === []) {
        $carritoHtml = '<div class="alert">Tu carrito esta vacio.</div>' .
            '<p class="actions-inline">' .
            '<a class="btn btn-primary" href="' . h(base_url('pedido_nuevo.php')) . '">Ver carta</a>' .
            '<a class="btn" href="' . h(base_url('recompensas_cliente.php')) . '">Ver recompensas</a>' .
            '</p>';
    } else {
        $carritoHtml = '<div class="alert">No hay productos de pago en el carrito. Puedes confirmar un pedido solo con recompensas.</div>' .
            '<p><a class="btn" href="' . h(base_url('recompensas_cliente.php')) . '">Seguir canjeando recompensas</a></p>';
    }
} else {
    $carritoHtml = '<form method="post" action="' . h(base_url('carrito.php')) . '">' .
        csrf_field() .
        '<input type="hidden" name="accion" value="update_cart">' .
        '<table class="table carrito-table">' .
        '<thead><tr><th>Foto</th><th>Producto</th><th>Cantidad (0 elimina)</th><th>Precio unidad</th><th>Subtotal</th></tr></thead>' .
        '<tbody>' . $lineasHtml . '</tbody>' .
        '</table>' .
        '<p><strong>Subtotal: ' . h(money_eur(array_sum(array_column($resumenCarrito['lineas'], 'subtotal')))) . '</strong></p>' .
        '<p><strong>Descuento aplicado: -' . h(money_eur($resumenCarrito['descuento_aplicado'])) . '</strong></p>' .
        '<p><strong>BistroCoins usados en recompensas: ' . (int) $resumenCarrito['bistrocoins_usados'] . '</strong></p>' .
        '<p><strong>Total a pagar en euros: ' . h(money_eur((float) $resumenCarrito['total'])) . '</strong></p>' .
        '<div class="actions-inline">' .
        '<button class="btn btn-primary" type="submit">Actualizar carrito</button>' .
        '<a class="btn" href="' . h(base_url('recompensas_cliente.php')) . '">Ver recompensas</a>' .
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

if ($lineasHtml === '' && $resumenCarrito['lineas_recompensa'] !== []) {
    $carritoHtml .= '<p><strong>BistroCoins usados en recompensas: ' . (int) $resumenCarrito['bistrocoins_usados'] . '</strong></p>';
    $carritoHtml .= '<div class="carrito-actions">' .
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
</section>

<section>
  <h2>Ofertas</h2>
  {$ofertasHtml}
</section>

<section class="carrito-panel">
  {$recompensasHtml}
  {$carritoHtml}
</section>
<script>
(function () {
  const cards = document.querySelectorAll('#cart-rewards-grid .reward-in-cart');
  if (!cards.length) return;
  cards.forEach((card, idx) => {
    setTimeout(() => card.classList.add('reward-in-cart-visible'), (idx + 1) * 80);
  });
})();
</script>
HTML;

$contenido = str_replace(
    ['{usuario}', '{action}', '{csrf}', '{sel_local}', '{sel_llevar}'],
    [
        h((string) $usuario['nombre_usuario']),
        h(base_url('carrito.php')),
        csrf_field(),
        $cart['tipo'] === 'local' ? 'selected' : '',
        $cart['tipo'] === 'llevar' ? 'selected' : '',
    ],
    $contenido
);

render_page('Carrito', $contenido);
