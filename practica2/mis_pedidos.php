<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();

$pedidos = PedidoRepository::forCliente((int) $usuario['id']);
$pedidosActivos = [];
$pedidosHistorico = [];

foreach ($pedidos as $pedido) {
    $estado = (string) $pedido['estado'];
    if (in_array($estado, ['en_preparacion', 'cocinando', 'listo_cocina', 'terminado'], true)) {
        $pedidosActivos[] = $pedido;
    } else {
        $pedidosHistorico[] = $pedido;
    }
}

$renderFilas = static function (array $lista): string {
    if ($lista === []) {
        return '<tr><td colspan="7">No hay pedidos.</td></tr>';
    }

    $filas = '';
    foreach ($lista as $pedido) {
        $estado = PedidoRepository::estadoLabel((string) $pedido['estado']);
        $tipo = PedidoRepository::tipoLabel((string) $pedido['tipo']);
        $numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];

        $acciones = '<a href="' . h(base_url('pedido_detalle.php?id=' . (int) $pedido['id'])) . '">Detalle</a>';
        if ((string) $pedido['estado'] === 'recibido') {
            $acciones .=
                '<form method="post" action="' . h(base_url('pedido_cancelar.php')) . '" style="display:inline;">' .
                csrf_field() .
                '<input type="hidden" name="id" value="' . (int) $pedido['id'] . '">' .
                '<button type="submit">Cancelar</button>' .
                '</form>';
        }

        $filas .= '<tr>' .
            '<td>' . (int) $pedido['id'] . '</td>' .
            '<td>' . h($numeroVisible) . '</td>' .
            '<td>' . h((string) $pedido['fecha_pedido']) . '</td>' .
            '<td>' . h($tipo) . '</td>' .
            '<td>' . h($estado) . '</td>' .
            '<td>' . h(money_eur((float) $pedido['total'])) . '</td>' .
            '<td>' . $acciones . '</td>' .
            '</tr>';
    }

    return $filas;
};

$filasActivos = $renderFilas($pedidosActivos);
$filasHistorico = $renderFilas($pedidosHistorico);

$contenido = <<<HTML
<section>
  <h2>Mis pedidos</h2>
  <p><a href="{nuevo}">Crear nuevo pedido</a></p>
</section>

<section>
  <h3>Pedidos en seguimiento</h3>
  <p>Estados relevantes: En preparacion, Cocinando, Listo cocina, Terminado.</p>
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>ID</th>
        <th>Numero dia</th>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Total</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {$filasActivos}
    </tbody>
  </table>
</section>

<section>
  <h3>Historico de pedidos</h3>
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>ID</th>
        <th>Numero dia</th>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Total</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {$filasHistorico}
    </tbody>
  </table>
</section>
HTML;

$contenido = str_replace(
    ['{nuevo}'],
    [h(base_url('pedido_nuevo.php'))],
    $contenido
);

render_page('Mis pedidos', $contenido);
