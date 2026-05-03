<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();
$cart = pedido_cart_get();
$errores = [];

if (is_post()) {
    $accion = post_enum('accion', ['set_tipo', 'add_item']);

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
                redirect_to('pedido_nuevo.php');
            }
        }

        if ($accion === 'add_item') {
            $productoId = post_positive_int('producto_id');
            $cantidad = post_int_range('cantidad', 1, 50);

            if ($productoId === null || $cantidad === null) {
                $errores[] = 'Producto o cantidad inválidos.';
            } else {
                $producto = ProductoRepository::findById($productoId);

                if (
                    !$producto
                    || (int) $producto['ofertado'] !== 1
                    || (int) ($producto['disponible'] ?? 0) !== 1
                ) {
                    $errores[] = 'El producto no está disponible.';
                } else {
                    $key = (string) $productoId;
                    $actual = (int) ($cart['items'][$key] ?? 0);
                    $cart['items'][$key] = min(50, $actual + $cantidad);
                    pedido_cart_save($cart);
                    flash_set('ok', 'Producto añadido al carrito.');
                    redirect_to('pedido_nuevo.php');
                }
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

$productos = ProductoRepository::all(false);
$porCategoria = [];
foreach ($productos as $producto) {
    $categoria = (string) ($producto['categoria_nombre'] ?? 'Sin categoría');
    if (!isset($porCategoria[$categoria])) {
        $porCategoria[$categoria] = [];
    }
    $porCategoria[$categoria][] = $producto;
}

$listaErrores = '';
if ($errores) {
    $items = '';
    foreach ($errores as $error) {
        $items .= '<li>' . h($error) . '</li>';
    }
    $listaErrores = '<div class="alert alert-error"><ul>' . $items . '</ul></div>';
}

$bloquesCategorias = '';
foreach ($porCategoria as $categoriaNombre => $productosCategoria) {
    $tarjetas = '';
    foreach ($productosCategoria as $producto) {
        $precioBase = (float) $producto['precio'];
        $iva = (float) $producto['iva'];
        $precioFinal = round($precioBase * (1 + ($iva / 100)), 2);
        $descripcion = trim((string) ($producto['descripcion'] ?? ''));
        $foto = trim((string) ($producto['foto'] ?? ''));
        $fotoHtml = $foto !== ''
            ? '<img class="producto-card-img" src="' . h(base_url($foto)) . '" alt="' . h((string) $producto['nombre']) . '">'
            : '<div class="producto-card-img producto-card-img-empty">Sin imagen</div>';

        $tarjetas .= '<article class="card producto-card">' .
            $fotoHtml .
            '<div class="producto-card-body">' .
            '<h4>' . h((string) $producto['nombre']) . '</h4>' .
            '<p>' . h($descripcion) . '</p>' .
            '<strong>' . h(money_eur($precioFinal)) . '</strong>' .
            '<form class="producto-add-form" method="post" action="' . h(base_url('pedido_nuevo.php')) . '">' .
            csrf_field() .
            '<input type="hidden" name="accion" value="add_item">' .
            '<input type="hidden" name="producto_id" value="' . (int) $producto['id'] . '">' .
            '<input class="form-control" type="number" name="cantidad" min="1" max="50" value="1" required>' .
            '<button class="btn btn-primary" type="submit">Añadir</button>' .
            '</form>' .
            '</div>' .
            '</article>';
    }

    $bloquesCategorias .= '<section class="catalogo-categoria">' .
        '<h3>' . h($categoriaNombre) . '</h3>' .
        '<div class="grid catalogo-grid">' . $tarjetas . '</div>' .
        '</section>';
}

$tipoLabel = $cart['tipo'] === 'llevar' ? 'Llevar' : 'Local';
$cantidadCarrito = (int) $resumenCarrito['cantidad_total'];
$totalCarrito = (float) $resumenCarrito['total'];
$carritoTexto = $cantidadCarrito === 1 ? '1 elemento' : $cantidadCarrito . ' elementos';

$contenido = <<<HTML
<section>
  <h2>Nuevo pedido</h2>
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

<section class="card carrito-resumen">
  <div>
    <span class="cocina-card-label">Carrito</span>
    <strong>{carrito_texto}</strong>
    <span>Total: {total_carrito}</span>
  </div>
  <div class="actions-inline">
    <span class="badge badge-accion-pendiente">{tipo_label}</span>
    <a class="btn btn-primary" href="{carrito_url}">Ver carrito</a>
    <a class="btn" href="{recompensas_url}">Ver recompensas</a>
  </div>
</section>

<section>
  <h2>Carta de productos ofertados</h2>
  {$bloquesCategorias}
</section>
HTML;

$contenido = str_replace(
    ['{usuario}', '{action}', '{csrf}', '{sel_local}', '{sel_llevar}', '{carrito_texto}', '{total_carrito}', '{tipo_label}', '{carrito_url}', '{recompensas_url}'],
    [
        h((string) $usuario['nombre_usuario']),
        h(base_url('pedido_nuevo.php')),
        csrf_field(),
        $cart['tipo'] === 'local' ? 'selected' : '',
        $cart['tipo'] === 'llevar' ? 'selected' : '',
        h($carritoTexto),
        h(money_eur($totalCarrito)),
        h($tipoLabel),
        h(base_url('carrito.php')),
        h(base_url('recompensas_cliente.php')),
    ],
    $contenido
);

render_page('Nuevo pedido', $contenido);
