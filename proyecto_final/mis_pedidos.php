<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();

$pedidos = PedidoRepository::forCliente((int) $usuario['id']);
$pedidosActivos = [];
$pedidosHistorico = [];

foreach ($pedidos as $pedido) {
    $estado = (string) $pedido['estado'];
    if (in_array($estado, ['nuevo', 'recibido', 'en_preparacion', 'cocinando', 'listo_cocina', 'terminado'], true)) {
        $pedidosActivos[] = $pedido;
    } else {
        $pedidosHistorico[] = $pedido;
    }
}

$renderFilas = static function (array $lista): string {
    if ($lista === []) {
        return '<tr><td colspan="8">No hay pedidos.</td></tr>';
    }

    $filas = '';
    foreach ($lista as $pedido) {
        $estado = PedidoRepository::estadoLabel((string) $pedido['estado']);
        $tipo = PedidoRepository::tipoLabel((string) $pedido['tipo']);
        $numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];

        $acciones = '<a href="' . h(base_url('pedido_detalle.php?id=' . (int) $pedido['id'])) . '">Detalle</a>';
        if (in_array((string) $pedido['estado'], ['nuevo', 'recibido'], true)) {
            $acciones .=
                '<form method="post" action="' . h(base_url('pedido_cancelar.php')) . '" class="inline">' .
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
            '<td>' . (int) ($pedido['bistrocoins_usados'] ?? 0) . ' usados / ' . (int) ($pedido['bistrocoins_ganados'] ?? 0) . ' ganados</td>' .
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
  <p>Estados relevantes: Nuevo, Recibido, En preparación, Cocinando, Listo cocina, Terminado.</p>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Número día</th>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Total</th>
        <th>BistroCoins</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {$filasActivos}
    </tbody>
  </table>
</section>

<section>
  <h3>Histórico de pedidos</h3>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Número día</th>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Estado</th>
        <th>Total</th>
        <th>BistroCoins</th>
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
