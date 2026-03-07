<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();
$pedidoId = (int) ($_GET['id'] ?? 0);

if ($pedidoId <= 0) {
    flash_set('error', 'Pedido invalido.');
    redirect_to('mis_pedidos.php');
}

$pedido = PedidoRepository::findById($pedidoId);
if (!$pedido) {
    flash_set('error', 'Pedido no encontrado.');
    redirect_to('mis_pedidos.php');
}

$esPropietario = (int) $pedido['cliente_id'] === (int) $usuario['id'];
$esCamarero = has_role('camarero', (string) $usuario['rol']);
$esGerente = has_role('gerente', (string) $usuario['rol']);

if (!$esPropietario && !$esCamarero && !$esGerente) {
    flash_set('error', 'No tienes permisos para ver este pedido.');
    redirect_to('index.php');
}

$lineas = PedidoRepository::lineasByPedido((int) $pedido['id']);

$filas = '';
foreach ($lineas as $linea) {
    $filas .= '<tr>' .
        '<td>' . h((string) $linea['producto_nombre']) . '</td>' .
        '<td>' . (int) $linea['cantidad'] . '</td>' .
        '<td>' . h(money_eur((float) $linea['precio_final_unitario'])) . '</td>' .
        '<td>' . h(money_eur((float) $linea['subtotal'])) . '</td>' .
        '</tr>';
}

if ($filas === '') {
    $filas = '<tr><td colspan="4">No hay lineas en este pedido.</td></tr>';
}

$numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];
$estadoLabel = PedidoRepository::estadoLabel((string) $pedido['estado']);
$tipoLabel = PedidoRepository::tipoLabel((string) $pedido['tipo']);
$metodoPagoLabel = PedidoRepository::metodoPagoLabel((string) $pedido['metodo_pago']);

$volver = has_role('gerente', (string) $usuario['rol'])
    ? base_url('pedidos.php')
    : (has_role('camarero', (string) $usuario['rol']) ? base_url('pedidos_camarero.php') : base_url('mis_pedidos.php'));

$contenido = <<<HTML
<section>
  <h2>Detalle de pedido</h2>
  <ul>
    <li><strong>ID:</strong> {id}</li>
    <li><strong>Numero del dia:</strong> {numero_visible}</li>
    <li><strong>Fecha/hora:</strong> {fecha_pedido}</li>
    <li><strong>Cliente:</strong> {cliente}</li>
    <li><strong>Estado:</strong> {estado}</li>
    <li><strong>Tipo:</strong> {tipo}</li>
    <li><strong>Metodo de pago:</strong> {metodo_pago}</li>
    <li><strong>Total:</strong> {total}</li>
  </ul>
</section>

<section>
  <h3>Lineas del pedido</h3>
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Precio unidad</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      {$filas}
    </tbody>
  </table>
  <p><a href="{volver}">Volver</a></p>
</section>
HTML;

$contenido = str_replace(
    ['{id}', '{numero_visible}', '{fecha_pedido}', '{cliente}', '{estado}', '{tipo}', '{metodo_pago}', '{total}', '{volver}'],
    [
        (string) (int) $pedido['id'],
        h($numeroVisible),
        h((string) $pedido['fecha_pedido']),
        h((string) $pedido['cliente_usuario']),
        h($estadoLabel),
        h($tipoLabel),
        h($metodoPagoLabel),
        h(money_eur((float) $pedido['total'])),
        h($volver),
    ],
    $contenido
);

render_page('Detalle de pedido', $contenido);
