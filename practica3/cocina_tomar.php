<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Peticion invalida.');
    redirect_to('cocina.php');
}

$pedidoId = (int) ($_POST['id'] ?? 0);
if ($pedidoId <= 0) {
    flash_set('error', 'ID de pedido invalido.');
    redirect_to('cocina.php');
}

$ok = PedidoRepository::tomarParaCocinar($pedidoId, (int) $cocinero['id']);

if ($ok) {
    flash_set('ok', 'Pedido tomado para cocinar.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

flash_set('error', 'No se pudo tomar el pedido. Verifica su estado actual.');
redirect_to('cocina.php');

