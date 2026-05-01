<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Petición inválida.');
    redirect_to('cocina.php');
}

$pedidoId = post_positive_int('pedido_id');
$lineaId = post_positive_int('linea_id');

if ($pedidoId === null || $lineaId === null) {
    flash_set('error', 'Datos de línea inválidos.');
    redirect_to('cocina.php');
}

$pedido = PedidoRepository::findById($pedidoId);
if (!$pedido) {
    flash_set('error', 'Pedido no encontrado.');
    redirect_to('cocina.php');
}

if ((string) $pedido['estado'] !== 'cocinando') {
    flash_set('error', 'El pedido no está en estado cocinando.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

if ((int) ($pedido['cocinero_id'] ?? 0) !== (int) $cocinero['id']) {
    flash_set('error', 'Solo el cocinero asignado puede preparar líneas.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

$lineas = PedidoRepository::lineasByPedido($pedidoId);
$lineaObjetivo = null;
foreach ($lineas as $linea) {
    if ((int) $linea['id'] === $lineaId) {
        $lineaObjetivo = $linea;
        break;
    }
}

if ($lineaObjetivo === null) {
    flash_set('error', 'Línea no encontrada para ese pedido.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

if ((int) ($lineaObjetivo['preparado'] ?? 0) === 1) {
    flash_set('ok', 'La línea ya estaba marcada como preparada.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

$ok = PedidoRepository::marcarLineaPreparada($lineaId, $pedidoId);
if ($ok) {
    flash_set('ok', 'Línea marcada como preparada.');
} else {
    flash_set('error', 'No se pudo actualizar la línea.');
}

redirect_to('cocina_detalle.php?id=' . $pedidoId);
