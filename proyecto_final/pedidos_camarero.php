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

$avatarHtml = '<img src="' . h(avatar_web_url(isset($camarero['avatar']) ? (string) $camarero['avatar'] : null)) . '" alt="Avatar camarero" width="80">';

$tarjetas = '';
foreach ($pedidos as $pedido) {
    $estado = (string) $pedido['estado'];
    $numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];
    $cocineroUsuario = trim((string) ($pedido['cocinero_usuario'] ?? ''));
    $cocineroHtml = 'Sin asignar';
    if ($cocineroUsuario !== '') {
        $avatar = avatar_web_url((string) ($pedido['cocinero_avatar'] ?? null));
        $cocineroHtml = '<div class="cocina-acciones">' .
            '<img class="avatar-cocina" src="' . h($avatar) . '" alt="Avatar cocinero" width="40" height="40">' .
            '<span>' . h($cocineroUsuario) . '</span>' .
            '</div>';
    }

    $badgeEstado = '<span class="badge badge-estado-' . h($estado) . '">' . h(PedidoRepository::estadoLabel($estado)) . '</span>';
    $acciones = '<div class="actions-inline"><a class="btn" href="' . h(base_url('pedido_detalle.php?id=' . (int) $pedido['id'])) . '">Detalle</a>';
    if ($estado === 'recibido') {
        $acciones .=
            '<form method="post" action="' . h(base_url('pedido_cambiar_estado.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . (int) $pedido['id'] . '">' .
            '<input type="hidden" name="accion" value="cobrar">' .
            '<button class="btn btn-primary" type="submit">Cobrar -> En preparacion</button>' .
            '</form>';
    }
    elseif ($estado === 'en_preparacion') {
        $acciones .= '<span class="cocina-estado-note">Esperando a que cocina tome el pedido.</span>';
    }
    elseif ($estado === 'cocinando') {
        $acciones .= '<span class="cocina-estado-note">Pedido en cocina.</span>';
    }
    elseif ($estado === 'listo_cocina') {
        $acciones .=
            '<form method="post" action="' . h(base_url('pedido_cambiar_estado.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . (int) $pedido['id'] . '">' .
            '<input type="hidden" name="accion" value="preparar_entrega">' .
            '<button class="btn btn-primary" type="submit">Preparar entrega -> Terminado</button>' .
            '</form>';
    }
    elseif ($estado === 'terminado') {
        $acciones .=
            '<form method="post" action="' . h(base_url('pedido_cambiar_estado.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . (int) $pedido['id'] . '">' .
            '<input type="hidden" name="accion" value="entregar">' .
            '<button class="btn btn-primary" type="submit">Entregar</button>' .
            '</form>';
    }
    $acciones .= '</div>';

    $tarjetas .= '<article class="card">' .
        '<h3>Pedido #' . (int) $pedido['id'] . ' · ' . h($numeroVisible) . '</h3>' .
        '<p><strong>Estado:</strong> ' . $badgeEstado . '</p>' .
        '<p><strong>Cliente:</strong> ' . h((string) $pedido['cliente_usuario']) . '</p>' .
        '<p><strong>Cocinero:</strong> ' . $cocineroHtml . '</p>' .
        '<p><strong>Tipo:</strong> ' . h(PedidoRepository::tipoLabel((string) $pedido['tipo'])) . '</p>' .
        '<p><strong>Total:</strong> ' . h(money_eur((float) $pedido['total'])) . '</p>' .
        $acciones .
        '</article>';
}

if ($tarjetas === '') {
    $tarjetas = '<p>No hay pedidos pendientes para camarero.</p>';
}

$contenido = <<<HTML
<section>
  <h2>Panel de camarero</h2>
  <p>Usuario: <strong>{usuario}</strong></p>
  <p>{$avatarHtml}</p>
  <p>
    Acciones disponibles:
    <br>1) Cobrar pedidos en estado Recibido (pasan a En preparacion)
    <br>2) Seguimiento de estados de cocina: En preparacion y Cocinando
    <br>3) Preparar entrega de pedidos en Listo cocina (pasan a Terminado)
    <br>4) Entregar pedidos en estado Terminado
  </p>
  <div class="grid">{$tarjetas}</div>
</section>
HTML;

$contenido = str_replace(
    ['{usuario}'],
    [h((string) $camarero['nombre_usuario'])],
    $contenido
);

render_page('Panel de camarero', $contenido);
