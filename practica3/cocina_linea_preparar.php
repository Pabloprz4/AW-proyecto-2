<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Peticion invalida.');
    redirect_to('cocina.php');
}

$pedidoId = (int) ($_POST['pedido_id'] ?? 0);
$lineaId = (int) ($_POST['linea_id'] ?? 0);

if ($pedidoId <= 0 || $lineaId <= 0) {
    flash_set('error', 'Datos de linea invalidos.');
    redirect_to('cocina.php');
}

$pedido = PedidoRepository::findById($pedidoId);
if (!$pedido) {
    flash_set('error', 'Pedido no encontrado.');
    redirect_to('cocina.php');
}

if ((string) $pedido['estado'] !== 'cocinando') {
    flash_set('error', 'El pedido no esta en estado cocinando.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

if ((int) ($pedido['cocinero_id'] ?? 0) !== (int) $cocinero['id']) {
    flash_set('error', 'Solo el cocinero asignado puede preparar lineas.');
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
    flash_set('error', 'Linea no encontrada para ese pedido.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

if ((int) ($lineaObjetivo['preparado'] ?? 0) === 1) {
    flash_set('ok', 'La linea ya estaba marcada como preparada.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

$ok = PedidoRepository::marcarLineaPreparada($lineaId, $pedidoId);
if ($ok) {
    flash_set('ok', 'Linea marcada como preparada.');
} else {
    flash_set('error', 'No se pudo actualizar la linea.');
}

redirect_to('cocina_detalle.php?id=' . $pedidoId);

