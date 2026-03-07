<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$camarero = require_role('camarero');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Peticion invalida.');
    redirect_to('pedidos_camarero.php');
}

$pedidoId = (int) ($_POST['id'] ?? 0);
$accion = (string) ($_POST['accion'] ?? '');

if ($pedidoId <= 0) {
    flash_set('error', 'ID de pedido invalido.');
    redirect_to('pedidos_camarero.php');
}

$ok = false;
if ($accion === 'cobrar') {
    $ok = PedidoRepository::marcarEnPreparacion($pedidoId, (int) $camarero['id']);
    $mensajeOk = 'Pedido cobrado. Estado actualizado a En preparacion.';
}
elseif ($accion === 'preparar_entrega') {
    $ok = PedidoRepository::marcarTerminado($pedidoId, (int) $camarero['id']);
    $mensajeOk = 'Pedido preparado para entregar. Estado actualizado a Terminado.';
}
elseif ($accion === 'entregar') {
    $ok = PedidoRepository::marcarEntregado($pedidoId, (int) $camarero['id']);
    $mensajeOk = 'Pedido entregado correctamente.';
}
else {
    flash_set('error', 'Accion invalida.');
    redirect_to('pedidos_camarero.php');
}

if ($ok) {
    flash_set('ok', $mensajeOk);
} else {
    flash_set('error', 'No se pudo cambiar el estado del pedido. Verifica el estado actual.');
}

redirect_to('pedidos_camarero.php');
