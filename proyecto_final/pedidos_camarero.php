<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$camarero = require_exact_role('camarero');
$estadosPanel = [
    'recibido',
    'en_preparacion',
    'cocinando',
    'listo_cocina',
    'terminado',
];
$pedidos = PedidoRepository::all($estadosPanel);

$avatarHtml = '<img class="avatar-cocina" src="' . h(avatar_web_url(isset($camarero['avatar']) ? (string) $camarero['avatar'] : null)) . '" alt="Avatar camarero" width="80">';

$flujos = [
    'cobrar' => [
        'titulo' => 'Cobrar',
        'items' => '',
        'total' => 0,
    ],
    'esperando_cocina' => [
        'titulo' => 'Esperando cocina',
        'items' => '',
        'total' => 0,
    ],
    'preparar_entrega' => [
        'titulo' => 'Preparar entrega',
        'items' => '',
        'total' => 0,
    ],
    'entregar' => [
        'titulo' => 'Entregar',
        'items' => '',
        'total' => 0,
    ],
];

foreach ($pedidos as $pedido) {
    $pedidoId = (int) $pedido['id'];
    $estado = (string) $pedido['estado'];
    $numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];
    $estadoLabel = PedidoRepository::estadoLabel($estado);
    $tipoLabel = PedidoRepository::tipoLabel((string) $pedido['tipo']);
    $total = money_eur((float) $pedido['total']);
    $cocineroUsuario = trim((string) ($pedido['cocinero_usuario'] ?? ''));
    $cocineroHtml = 'Sin asignar';
    if ($cocineroUsuario !== '') {
        $avatar = avatar_web_url((string) ($pedido['cocinero_avatar'] ?? null));
        $cocineroHtml = '<div class="cocina-acciones">' .
            '<img class="avatar-cocina" src="' . h($avatar) . '" alt="Avatar cocinero" width="40" height="40">' .
            '<span>' . h($cocineroUsuario) . '</span>' .
            '</div>';
    }

    $flujo = match ($estado) {
        'recibido' => 'cobrar',
        'en_preparacion', 'cocinando' => 'esperando_cocina',
        'listo_cocina' => 'preparar_entrega',
        'terminado' => 'entregar',
        default => 'esperando_cocina',
    };

    $badgeEstado = '<span class="badge badge-estado-' . h($estado) . '">' . h($estadoLabel) . '</span>';
    $detalle = '<a class="btn" href="' . h(base_url('pedido_detalle.php?id=' . $pedidoId)) . '">Detalle</a>';
    $accionPrincipal = '';
    if ($estado === 'recibido') {
        $accionPrincipal =
            '<form method="post" action="' . h(base_url('pedido_cambiar_estado.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . $pedidoId . '">' .
            '<input type="hidden" name="accion" value="cobrar">' .
            '<button class="btn btn-primary btn-lg" type="submit">Cobrar pedido</button>' .
            '</form>';
    }
    elseif ($estado === 'en_preparacion') {
        $accionPrincipal = '<span class="badge badge-accion-pendiente">En espera</span>';
    }
    elseif ($estado === 'cocinando') {
        $accionPrincipal = '<span class="badge badge-accion-progreso">Cocinando</span>';
    }
    elseif ($estado === 'listo_cocina') {
        $accionPrincipal =
            '<form method="post" action="' . h(base_url('pedido_cambiar_estado.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . $pedidoId . '">' .
            '<input type="hidden" name="accion" value="preparar_entrega">' .
            '<button class="btn btn-primary btn-lg" type="submit">Preparar entrega</button>' .
            '</form>';
    }
    elseif ($estado === 'terminado') {
        $accionPrincipal =
            '<form method="post" action="' . h(base_url('pedido_cambiar_estado.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . $pedidoId . '">' .
            '<input type="hidden" name="accion" value="entregar">' .
            '<button class="btn btn-primary btn-lg" type="submit">Entregar pedido</button>' .
            '</form>';
    }

    $acciones = '<div class="cocina-card-actions cocina-card-actions-lg">' . $accionPrincipal . $detalle . '</div>';

    $flujos[$flujo]['items'] .= '<article class="card camarero-card camarero-card-estado-' . h($estado) . '">' .
        '<div class="cocina-card-header">' .
        '<div>' .
        '<span class="cocina-card-label">Pedido</span>' .
        '<h3 class="cocina-card-title">#' . $pedidoId . '</h3>' .
        '<span class="cocina-pedido-numero">Numero dia ' . h($numeroVisible) . '</span>' .
        '</div>' .
        $badgeEstado .
        '</div>' .
        '<dl class="cocina-meta camarero-meta">' .
        '<div class="cocina-meta-item"><dt>Cliente</dt><dd>' . h((string) $pedido['cliente_usuario']) . '</dd></div>' .
        '<div class="cocina-meta-item"><dt>Cocinero</dt><dd>' . $cocineroHtml . '</dd></div>' .
        '<div class="cocina-meta-item"><dt>Tipo</dt><dd>' . h($tipoLabel) . '</dd></div>' .
        '<div class="cocina-meta-item cocina-total"><dt>Total</dt><dd>' . h($total) . '</dd></div>' .
        '</dl>' .
        $acciones .
        '</article>';
    $flujos[$flujo]['total']++;
}

$secciones = '';
foreach ($flujos as $clave => $flujo) {
    $items = $flujo['items'];
    if ($items === '') {
        $items = '<div class="alert camarero-empty">Sin pedidos.</div>';
    }

    $secciones .= '<section class="camarero-flow camarero-flow-' . h($clave) . '">' .
        '<div class="camarero-flow-header">' .
        '<div>' .
        '<span class="cocina-card-label">Flujo</span>' .
        '<h3>' . h($flujo['titulo']) . '</h3>' .
        '</div>' .
        '<span class="badge">' . (int) $flujo['total'] . '</span>' .
        '</div>' .
        '<div class="camarero-flow-list">' . $items . '</div>' .
        '</section>';
}

$totalPedidos = count($pedidos);

$contenido = <<<HTML
<section class="camarero-panel">
  <div class="cocina-panel-header">
    {$avatarHtml}
    <div>
      <h2>Panel de camarero</h2>
      <p class="cocina-info">Usuario: <strong>{usuario}</strong></p>
      <p class="cocina-info">Pedidos activos: <strong>{total_pedidos}</strong></p>
    </div>
  </div>
  <div class="camarero-board">{$secciones}</div>
</section>
HTML;

$contenido = str_replace(
    ['{usuario}', '{total_pedidos}'],
    [h((string) $camarero['nombre_usuario']), (string) $totalPedidos],
    $contenido
);

render_page('Panel de camarero', $contenido);
