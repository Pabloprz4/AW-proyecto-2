<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');
$pedidos = PedidoRepository::forCocineroPanel();

$tarjetas = '';
foreach ($pedidos as $pedido) {
    $pedidoId = (int) $pedido['id'];
    $estado = (string) $pedido['estado'];
    $numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];
    $asignado = (int) ($pedido['cocinero_id'] ?? 0);

    $badgeEstado = '<span class="badge badge-estado-' . h($estado) . '">' . h(PedidoRepository::estadoLabel($estado)) . '</span>';
    $acciones = '<div class="actions-inline">';
    $acciones .= '<a class="boton" href="' . h(base_url('cocina_detalle.php?id=' . $pedidoId)) . '">Detalle cocina</a>';

    if ($estado === 'en_preparacion') {
        $acciones .=
            '<form method="post" action="' . h(base_url('cocina_tomar.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . $pedidoId . '">' .
            '<button class="btn btn-primary" type="submit">Tomar pedido</button>' .
            '</form>';
    }

    if ($estado === 'cocinando') {
        if ($asignado === (int) $cocinero['id']) {
            $acciones .= '<span class="cocina-estado-note">(lo estas preparando)</span>';
        } elseif ($asignado > 0) {
            $acciones .= '<span class="cocina-estado-note">(asignado a otro cocinero)</span>';
        }
    }
    $acciones .= '</div>';

    $tarjetas .= '<article class="card">' .
        '<h3>Pedido #' . $pedidoId . ' · ' . h($numeroVisible) . '</h3>' .
        '<p><strong>Estado:</strong> ' . $badgeEstado . '</p>' .
        '<p><strong>Cliente:</strong> ' . h((string) $pedido['cliente_usuario']) . '</p>' .
        '<p><strong>Tipo:</strong> ' . h(PedidoRepository::tipoLabel((string) $pedido['tipo'])) . '</p>' .
        '<p><strong>Total:</strong> ' . h(money_eur((float) $pedido['total'])) . '</p>' .
        $acciones .
        '</article>';
}

if ($tarjetas === '') {
    $tarjetas = '<p>No hay pedidos pendientes de cocina.</p>';
}

$avatarHtml = '<img class="avatar-cocina" src="' . h(avatar_web_url(isset($cocinero['avatar']) ? (string) $cocinero['avatar'] : null)) . '" alt="Avatar cocinero" width="80">';

$contenido = <<<HTML
<section class="cocina-panel">
  <h2>Panel de cocina</h2>
  <p>Usuario: <strong>{usuario}</strong></p>
  <p>{$avatarHtml}</p>
  <p class="cocina-info">Pedidos visibles: En preparacion y Cocinando.</p>
  <div class="grid">{$tarjetas}</div>
</section>
HTML;

$contenido = str_replace(
    ['{usuario}'],
    [h((string) $cocinero['nombre_usuario'])],
    $contenido
);

render_page('Panel de cocina', $contenido);
