<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');
$pedidoId = (int) ($_GET['id'] ?? 0);

if ($pedidoId <= 0) {
    flash_set('error', 'Pedido invalido.');
    redirect_to('cocina.php');
}

$pedido = PedidoRepository::findById($pedidoId);
if (!$pedido) {
    flash_set('error', 'Pedido no encontrado.');
    redirect_to('cocina.php');
}

$estado = (string) $pedido['estado'];
$permitidos = ['en_preparacion', 'cocinando', 'listo_cocina'];
if (!in_array($estado, $permitidos, true)) {
    flash_set('error', 'Este pedido ya no pertenece al flujo de cocina.');
    redirect_to('cocina.php');
}

$lineas = PedidoRepository::lineasByPedido((int) $pedido['id']);
$pedidoCocineroId = (int) ($pedido['cocinero_id'] ?? 0);
$esCocineroAsignado = $pedidoCocineroId > 0 && $pedidoCocineroId === (int) $cocinero['id'];

$filas = '';
$totalLineas = 0;
$lineasPreparadas = 0;

foreach ($lineas as $linea) {
    $totalLineas++;
    $preparado = (int) ($linea['preparado'] ?? 0) === 1;
    if ($preparado) {
        $lineasPreparadas++;
    }

    $filas .= '<tr>' .
        '<td>' . h((string) $linea['producto_nombre']) . '</td>' .
        '<td>' . (int) $linea['cantidad'] . '</td>' .
        '<td>' . h(money_eur((float) $linea['precio_final_unitario'])) . '</td>' .
        '<td>' . h(money_eur((float) $linea['subtotal'])) . '</td>' .
        '<td>' . ($preparado ? 'Si' : 'No') . '</td>' .
        '</tr>';
}

if ($filas === '') {
    $filas = '<tr><td colspan="5">No hay lineas en este pedido.</td></tr>';
}

$numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];
$resumenAsignacion = 'Sin cocinero asignado.';
if ($pedidoCocineroId > 0) {
    $resumenAsignacion = $esCocineroAsignado
        ? 'Asignado a ti.'
        : 'Asignado a otro cocinero (' . h((string) ($pedido['cocinero_usuario'] ?? 'desconocido')) . ').';
}

$acciones = '';
if ($estado === 'en_preparacion' && $pedidoCocineroId === 0) {
    $acciones =
        '<form method="post" action="' . h(base_url('cocina_tomar.php')) . '" class="inline">' .
        csrf_field() .
        '<input type="hidden" name="id" value="' . (int) $pedido['id'] . '">' .
        '<button type="submit">Tomar pedido</button>' .
        '</form>';
}

$contenido = <<<HTML
<section>
  <h2>Detalle de cocina</h2>
  <ul>
    <li><strong>ID:</strong> {id}</li>
    <li><strong>Numero del dia:</strong> {numero_visible}</li>
    <li><strong>Estado:</strong> {estado}</li>
    <li><strong>Tipo:</strong> {tipo}</li>
    <li><strong>Cliente:</strong> {cliente}</li>
    <li><strong>Total:</strong> {total}</li>
    <li><strong>Cocinero:</strong> {asignacion}</li>
    <li><strong>Progreso lineas:</strong> {lineas_preparadas}/{lineas_totales}</li>
  </ul>
  <p>{$acciones}</p>
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
        <th>Preparado</th>
      </tr>
    </thead>
    <tbody>
      {$filas}
    </tbody>
  </table>
  <p><a href="{volver}">Volver al panel de cocina</a></p>
</section>
HTML;

$contenido = str_replace(
    ['{id}', '{numero_visible}', '{estado}', '{tipo}', '{cliente}', '{total}', '{asignacion}', '{lineas_preparadas}', '{lineas_totales}', '{volver}'],
    [
        (string) (int) $pedido['id'],
        h($numeroVisible),
        h(PedidoRepository::estadoLabel($estado)),
        h(PedidoRepository::tipoLabel((string) $pedido['tipo'])),
        h((string) $pedido['cliente_usuario']),
        h(money_eur((float) $pedido['total'])),
        $resumenAsignacion,
        (string) $lineasPreparadas,
        (string) $totalLineas,
        h(base_url('cocina.php')),
    ],
    $contenido
);

render_page('Detalle de cocina', $contenido);

