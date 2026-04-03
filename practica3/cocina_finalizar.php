<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Peticion invalida.');
    redirect_to('cocina.php');
}

$pedidoId = (int) ($_POST['pedido_id'] ?? 0);
if ($pedidoId <= 0) {
    flash_set('error', 'Pedido invalido.');
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
    flash_set('error', 'Solo el cocinero asignado puede finalizar este pedido.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

$lineas = PedidoRepository::lineasByPedido($pedidoId);
if ($lineas === []) {
    flash_set('error', 'El pedido no tiene lineas para finalizar.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

foreach ($lineas as $linea) {
    if ((int) ($linea['preparado'] ?? 0) !== 1) {
        flash_set('error', 'Debes preparar todas las lineas antes de finalizar.');
        redirect_to('cocina_detalle.php?id=' . $pedidoId);
    }
}

$ok = PedidoRepository::marcarListoCocina($pedidoId, (int) $cocinero['id']);
if ($ok) {
    flash_set('ok', 'Pedido enviado a Listo cocina.');
    redirect_to('cocina.php');
}

flash_set('error', 'No se pudo finalizar cocina. Verifica el estado actual.');
redirect_to('cocina_detalle.php?id=' . $pedidoId);

