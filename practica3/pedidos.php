<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gerente = require_role('gerente');

$estadoFiltro = trim((string) ($_GET['estado'] ?? ''));
$estadosPendientes = [
    'recibido',
    'en_preparacion',
    'cocinando',
    'listo_cocina',
    'terminado',
];

$pedidos = in_array($estadoFiltro, $estadosPendientes, true)
    ? PedidoRepository::all([$estadoFiltro])
    : PedidoRepository::all($estadosPendientes);

$opcionesEstado = '<option value="">Todos los pendientes</option>';
foreach ($estadosPendientes as $estado) {
    $selected = $estadoFiltro === $estado ? ' selected' : '';
    $opcionesEstado .= '<option value="' . h($estado) . '"' . $selected . '>' . h(PedidoRepository::estadoLabel($estado)) . '</option>';
}

$filas = '';
foreach ($pedidos as $pedido) {
    $numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];
    $camarero = (string) ($pedido['camarero_usuario'] ?? '');
    if ($camarero === '') {
        $camarero = '-';
    }

    $cocineroUsuario = trim((string) ($pedido['cocinero_usuario'] ?? ''));
    $cocineroHtml = 'Sin asignar';
    if ($cocineroUsuario !== '') {
        $avatar = avatar_web_url((string) ($pedido['cocinero_avatar'] ?? null));
        $cocineroHtml = '<div class="cocina-acciones">' .
            '<img class="avatar-cocina" src="' . h($avatar) . '" alt="Avatar cocinero" width="40" height="40">' .
            '<span>' . h($cocineroUsuario) . '</span>' .
            '</div>';
    }

    $filas .= '<tr>' .
        '<td>' . (int) $pedido['id'] . '</td>' .
        '<td>' . h($numeroVisible) . '</td>' .
        '<td>' . h((string) $pedido['fecha_pedido']) . '</td>' .
        '<td>' . h((string) $pedido['cliente_usuario']) . '</td>' .
        '<td>' . h($camarero) . '</td>' .
        '<td>' . $cocineroHtml . '</td>' .
        '<td>' . h(PedidoRepository::tipoLabel((string) $pedido['tipo'])) . '</td>' .
        '<td>' . h(PedidoRepository::estadoLabel((string) $pedido['estado'])) . '</td>' .
        '<td>' . h(money_eur((float) $pedido['total'])) . '</td>' .
        '<td><a href="' . h(base_url('pedido_detalle.php?id=' . (int) $pedido['id'])) . '">Detalle</a></td>' .
        '</tr>';
}

if ($filas === '') {
    $filas = '<tr><td colspan="10">No hay pedidos pendientes para el filtro indicado.</td></tr>';
}

$contenido = <<<HTML
<section>
  <h2>Pedidos pendientes (Gerente)</h2>
  <p>Usuario: <strong>{usuario}</strong></p>
  <p>Estados pendientes visibles: Recibido, En preparacion, Cocinando, Listo cocina y Terminado.</p>
  <form method="get" action="{action}">
    <label for="estado">Filtrar por estado:</label>
    <select id="estado" name="estado">{$opcionesEstado}</select>
    <button type="submit">Filtrar</button>
  </form>
</section>

<section>
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>ID</th>
        <th>Numero dia</th>
        <th>Fecha</th>
        <th>Cliente</th>
        <th>Camarero</th>
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
    ['{usuario}', '{action}'],
    [h((string) $gerente['nombre_usuario']), h(base_url('pedidos.php'))],
    $contenido
);

render_page('Pedidos (Gerente)', $contenido);
