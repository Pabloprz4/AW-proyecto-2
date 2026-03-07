<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gerente = require_role('gerente');

$estadoFiltro = trim((string) ($_GET['estado'] ?? ''));
$estadosValidos = [
    'recibido',
    'en_preparacion',
    'cocinando',
    'listo_cocina',
    'terminado',
    'entregado',
];

$pedidos = in_array($estadoFiltro, $estadosValidos, true)
    ? PedidoRepository::all([$estadoFiltro])
    : PedidoRepository::all();

$opcionesEstado = '<option value="">Todos</option>';
foreach ($estadosValidos as $estado) {
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

    $filas .= '<tr>' .
        '<td>' . (int) $pedido['id'] . '</td>' .
        '<td>' . h($numeroVisible) . '</td>' .
        '<td>' . h((string) $pedido['fecha_pedido']) . '</td>' .
        '<td>' . h((string) $pedido['cliente_usuario']) . '</td>' .
        '<td>' . h($camarero) . '</td>' .
        '<td>' . h(PedidoRepository::tipoLabel((string) $pedido['tipo'])) . '</td>' .
        '<td>' . h(PedidoRepository::estadoLabel((string) $pedido['estado'])) . '</td>' .
        '<td>' . h(money_eur((float) $pedido['total'])) . '</td>' .
        '<td><a href="' . h(base_url('pedido_detalle.php?id=' . (int) $pedido['id'])) . '">Detalle</a></td>' .
        '</tr>';
}

if ($filas === '') {
    $filas = '<tr><td colspan="9">No hay pedidos para el filtro indicado.</td></tr>';
}

$contenido = <<<HTML
<section>
  <h2>Pedidos (Gerente)</h2>
  <p>Usuario: <strong>{usuario}</strong></p>
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
