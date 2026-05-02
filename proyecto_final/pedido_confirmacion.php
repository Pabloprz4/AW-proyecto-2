<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();
$pedidoId = get_positive_int('id');

if ($pedidoId === null) {
    flash_set('error', 'Pedido inválido.');
    redirect_to('index.php');
}

$pedido = PedidoRepository::findById($pedidoId);
if (!$pedido) {
    flash_set('error', 'Pedido no encontrado.');
    redirect_to('index.php');
}

$esPropietario = (int) $pedido['cliente_id'] === (int) $usuario['id'];
$esGerente = has_role('gerente', (string) $usuario['rol']);

if (!$esPropietario && !$esGerente) {
    flash_set('error', 'No tienes permisos para ver este pedido.');
    redirect_to('index.php');
}

$numeroVisible = (int) $pedido['numero_dia'] . '/' . h((string) $pedido['fecha_dia']);
$estadoLabel = PedidoRepository::estadoLabel((string) $pedido['estado']);
$tipoLabel = PedidoRepository::tipoLabel((string) $pedido['tipo']);
$metodoPagoLabel = PedidoRepository::metodoPagoLabel((string) $pedido['metodo_pago']);
$totalSinDescuento = $pedido['total_sin_descuento'] ? money_eur((float) $pedido['total_sin_descuento']) : null;
$descuentoAplicado = $pedido['descuento_aplicado'] ? money_eur((float) $pedido['descuento_aplicado']) : null;

$contenido = <<<HTML
<section>
  <h2>Confirmación del pedido</h2>
  <p>Tu pedido se ha registrado correctamente.</p>
  <ul>
    <li><strong>ID interno:</strong> {id}</li>
    <li><strong>Número de pedido (día actual):</strong> {numero_visible}</li>
    <li><strong>Estado:</strong> {estado}</li>
    <li><strong>Tipo:</strong> {tipo}</li>
    <li><strong>Método de pago:</strong> {metodo_pago}</li>
    {total_sin_descuento}
    {descuento_aplicado}
    <li><strong>Total:</strong> {total}</li>
  </ul>
  <p>
    <a href="{detalle}">Ver detalle del pedido</a><br>
    <a href="{nuevo}">Crear otro pedido</a><br>
    <a href="{inicio}">Volver al inicio</a>
  </p>
</section>
HTML;

$contenido = str_replace(
    ['{id}', '{numero_visible}', '{estado}', '{tipo}', '{metodo_pago}', '{total_sin_descuento}', '{descuento_aplicado}', '{total}', '{detalle}', '{nuevo}', '{inicio}'],
    [
        (string) (int) $pedido['id'],
        $numeroVisible,
        h($estadoLabel),
        h($tipoLabel),
        h($metodoPagoLabel),
        $totalSinDescuento ? '<li><strong>Total sin descuento:</strong> ' . $totalSinDescuento . '</li>' : '',
        $descuentoAplicado ? '<li><strong>Descuento aplicado:</strong> ' . $descuentoAplicado . '</li>' : '',
        h(money_eur((float) $pedido['total'])),
        h(base_url('pedido_detalle.php?id=' . (int) $pedido['id'])),
        h(base_url('pedido_nuevo.php')),
        h(base_url('index.php')),
    ],
    $contenido
);

render_page('Confirmación del pedido', $contenido);
