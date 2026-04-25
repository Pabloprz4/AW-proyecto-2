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

$filas = '';
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

    $acciones = '<a href="' . h(base_url('pedido_detalle.php?id=' . (int) $pedido['id'])) . '">Detalle</a> ';
    if ($estado === 'recibido') {
        $acciones .=
            '<form method="post" action="' . h(base_url('pedido_cambiar_estado.php')) . '" style="display:inline;">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . (int) $pedido['id'] . '">' .
            '<input type="hidden" name="accion" value="cobrar">' .
            '<button type="submit">Cobrar -> En preparacion</button>' .
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
            '<form method="post" action="' . h(base_url('pedido_cambiar_estado.php')) . '" style="display:inline;">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . (int) $pedido['id'] . '">' .
            '<input type="hidden" name="accion" value="preparar_entrega">' .
            '<button type="submit">Preparar entrega -> Terminado</button>' .
            '</form>';
    }
    elseif ($estado === 'terminado') {
        $acciones .=
            '<form method="post" action="' . h(base_url('pedido_cambiar_estado.php')) . '" style="display:inline;">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . (int) $pedido['id'] . '">' .
            '<input type="hidden" name="accion" value="entregar">' .
            '<button type="submit">Entregar</button>' .
            '</form>';
    }

    $filas .= '<tr>' .
        '<td>' . (int) $pedido['id'] . '</td>' .
        '<td>' . h($numeroVisible) . '</td>' .
        '<td>' . h((string) $pedido['cliente_usuario']) . '</td>' .
        '<td>' . $cocineroHtml . '</td>' .
        '<td>' . h(PedidoRepository::tipoLabel((string) $pedido['tipo'])) . '</td>' .
        '<td>' . h(PedidoRepository::estadoLabel($estado)) . '</td>' .
        '<td>' . h(money_eur((float) $pedido['total'])) . '</td>' .
        '<td>' . $acciones . '</td>' .
        '</tr>';
}

if ($filas === '') {
    $filas = '<tr><td colspan="8">No hay pedidos pendientes para camarero.</td></tr>';
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
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>ID</th>
        <th>Numero dia</th>
        <th>Cliente</th>
        <th>Cocinero</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Total</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {$filas}
    </tbody>
  </table>
</section>
HTML;

$contenido = str_replace(
    ['{usuario}'],
    [h((string) $camarero['nombre_usuario'])],
    $contenido
);

render_page('Panel de camarero', $contenido);
