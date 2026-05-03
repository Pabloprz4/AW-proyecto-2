<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');
$pedidos = PedidoRepository::forCocineroPanel();

$tarjetas = '';
foreach ($pedidos as $pedido) {
    $pedidoId = (int) $pedido['id'];
    $estado = (string) $pedido['estado'];
    $numeroVisible = pedido_numero_visible($pedido);
    $asignado = (int) ($pedido['cocinero_id'] ?? 0);
    $estadoLabel = PedidoRepository::estadoLabel($estado);
    $tipoLabel = PedidoRepository::tipoLabel((string) $pedido['tipo']);
    $cliente = (string) $pedido['cliente_usuario'];
    $total = money_eur((float) $pedido['total']);

    $badgeEstado = '<span class="badge badge-estado-' . h($estado) . '">' . h($estadoLabel) . '</span>';
    $urlDetalle = h(base_url('cocina_detalle.php?id=' . $pedidoId));
    $accionPrincipal = '';
    $accionSecundaria = '<a class="btn" href="' . $urlDetalle . '">Ver detalle</a>';

    if ($estado === 'en_preparacion') {
        $accionPrincipal =
            '<form method="post" action="' . h(base_url('cocina_tomar.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . $pedidoId . '">' .
            '<button class="btn btn-primary" type="submit">Tomar pedido</button>' .
            '</form>';
    }
    elseif ($estado === 'cocinando') {
        if ($asignado === (int) $cocinero['id']) {
            $accionPrincipal = '<a class="btn btn-primary" href="' . $urlDetalle . '">Preparar líneas</a>';
            $accionSecundaria = '<span class="badge badge-accion-ok">Tuyo</span>';
        } elseif ($asignado > 0) {
            $accionPrincipal = '<span class="badge badge-accion-bloqueada">Otro cocinero</span>';
        } else {
            $accionPrincipal = '<a class="btn btn-primary" href="' . $urlDetalle . '">Revisar pedido</a>';
        }
    }

    $acciones = '<div class="cocina-card-actions">' . $accionPrincipal . $accionSecundaria . '</div>';

    $tarjetas .= '<article class="card cocina-card cocina-card-estado-' . h($estado) . '">' .
        '<div class="cocina-card-header">' .
        '<div>' .
        '<span class="cocina-card-label">Pedido</span>' .
        '<h3 class="cocina-card-title">#' . $pedidoId . '</h3>' .
        '<span class="cocina-pedido-numero">Número día ' . h($numeroVisible) . '</span>' .
        '</div>' .
        $badgeEstado .
        '</div>' .
        '<dl class="cocina-meta">' .
        '<div class="cocina-meta-item"><dt>Cliente</dt><dd>' . h($cliente) . '</dd></div>' .
        '<div class="cocina-meta-item"><dt>Tipo</dt><dd>' . h($tipoLabel) . '</dd></div>' .
        '<div class="cocina-meta-item cocina-total"><dt>Total</dt><dd>' . h($total) . '</dd></div>' .
        '</dl>' .
        $acciones .
        '</article>';
}

if ($tarjetas === '') {
    $tarjetas = '<div class="alert cocina-empty">No hay pedidos pendientes de cocina.</div>';
}

$avatarHtml = '<img class="avatar-cocina" src="' . h(avatar_web_url(isset($cocinero['avatar']) ? (string) $cocinero['avatar'] : null)) . '" alt="Avatar cocinero" width="80">';
$totalPedidos = count($pedidos);

$contenido = <<<HTML
<section class="cocina-panel">
  <div class="cocina-panel-header">
    {$avatarHtml}
    <div>
      <h2>Panel de cocina</h2>
      <p class="cocina-info">Usuario: <strong>{usuario}</strong></p>
      <p class="cocina-info">Pedidos activos: <strong>{total_pedidos}</strong></p>
    </div>
  </div>
  <div class="grid cocina-grid">{$tarjetas}</div>
</section>
HTML;

$contenido = str_replace(
    ['{usuario}', '{total_pedidos}'],
    [h((string) $cocinero['nombre_usuario']), (string) $totalPedidos],
    $contenido
);

render_page('Panel de cocina', $contenido);
