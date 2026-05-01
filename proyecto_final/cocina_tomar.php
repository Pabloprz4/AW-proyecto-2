<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$cocinero = require_role('cocinero');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Petición inválida.');
    redirect_to('cocina.php');
}

$pedidoId = post_positive_int('id');
if ($pedidoId === null) {
    flash_set('error', 'ID de pedido inválido.');
    redirect_to('cocina.php');
}

$pedido = PedidoRepository::findById($pedidoId);
if (!$pedido) {
    flash_set('error', 'Pedido no encontrado.');
    redirect_to('cocina.php');
}

if ((string) $pedido['estado'] !== 'en_preparacion' || !empty($pedido['cocinero_id'])) {
    flash_set('error', 'El pedido no está disponible para cocina.');
    redirect_to('cocina.php');
}

$ok = PedidoRepository::tomarParaCocinar($pedidoId, (int) $cocinero['id']);

if ($ok) {
    flash_set('ok', 'Pedido tomado para cocinar.');
    redirect_to('cocina_detalle.php?id=' . $pedidoId);
}

flash_set('error', 'No se pudo tomar el pedido. Verifica su estado actual.');
redirect_to('cocina.php');
