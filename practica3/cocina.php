<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');
$pedidos = PedidoRepository::forCocineroPanel();

$filas = '';
foreach ($pedidos as $pedido) {
    $pedidoId = (int) $pedido['id'];
    $estado = (string) $pedido['estado'];
    $numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];
    $asignado = (int) ($pedido['cocinero_id'] ?? 0);

    $acciones = '<a href="' . h(base_url('cocina_detalle.php?id=' . $pedidoId)) . '">Detalle cocina</a>';

    if ($estado === 'en_preparacion') {
        $acciones .=
            '<form method="post" action="' . h(base_url('cocina_tomar.php')) . '" class="inline">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . $pedidoId . '">' .
            '<button type="submit">Tomar pedido</button>' .
            '</form>';
    }

    if ($estado === 'cocinando') {
        if ($asignado === (int) $cocinero['id']) {
            $acciones .= ' <span>(lo estas preparando)</span>';
        } elseif ($asignado > 0) {
            $acciones .= ' <span>(asignado a otro cocinero)</span>';
        }
    }

    $filas .= '<tr>' .
        '<td>' . $pedidoId . '</td>' .
        '<td>' . h($numeroVisible) . '</td>' .
        '<td>' . h((string) $pedido['cliente_usuario']) . '</td>' .
        '<td>' . h(PedidoRepository::tipoLabel((string) $pedido['tipo'])) . '</td>' .
        '<td>' . h(PedidoRepository::estadoLabel($estado)) . '</td>' .
        '<td>' . h(money_eur((float) $pedido['total'])) . '</td>' .
        '<td>' . $acciones . '</td>' .
        '</tr>';
}

if ($filas === '') {
    $filas = '<tr><td colspan="7">No hay pedidos pendientes de cocina.</td></tr>';
}

$avatarHtml = '<img src="' . h(avatar_web_url(isset($cocinero['avatar']) ? (string) $cocinero['avatar'] : null)) . '" alt="Avatar cocinero" width="80">';

$contenido = <<<HTML
<section>
  <h2>Panel de cocina</h2>
  <p>Usuario: <strong>{usuario}</strong></p>
  <p>{$avatarHtml}</p>
  <p>Pedidos visibles: En preparacion y Cocinando.</p>
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>ID</th>
        <th>Numero dia</th>
        <th>Cliente</th>
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
    [h((string) $cocinero['nombre_usuario'])],
    $contenido
);

render_page('Panel de cocina', $contenido);

