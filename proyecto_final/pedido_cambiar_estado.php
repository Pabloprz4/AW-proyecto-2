<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$camarero = require_exact_role('camarero');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Petición inválida.');
    redirect_to('pedidos_camarero.php');
}

$pedidoId = post_positive_int('id');
$accion = post_enum('accion', ['cobrar', 'preparar_entrega', 'entregar']);

if ($pedidoId === null) {
    flash_set('error', 'ID de pedido inválido.');
    redirect_to('pedidos_camarero.php');
}

if ($accion === null) {
    flash_set('error', 'Acción inválida.');
    redirect_to('pedidos_camarero.php');
}

$pedido = PedidoRepository::findById($pedidoId);
if (!$pedido) {
    flash_set('error', 'Pedido no encontrado.');
    redirect_to('pedidos_camarero.php');
}

$estadoActual = (string) $pedido['estado'];
$acciones = [
    'cobrar' => [
        'estado' => 'recibido',
        'mensaje_ok' => 'Pedido cobrado. Estado actualizado a En preparación.',
        'handler' => static fn (): bool => PedidoRepository::marcarEnPreparacion($pedidoId, (int) $camarero['id']),
    ],
    'preparar_entrega' => [
        'estado' => 'listo_cocina',
        'mensaje_ok' => 'Pedido preparado para entregar. Estado actualizado a Terminado.',
        'handler' => static fn (): bool => PedidoRepository::marcarTerminado($pedidoId, (int) $camarero['id']),
    ],
    'entregar' => [
        'estado' => 'terminado',
        'mensaje_ok' => 'Pedido entregado correctamente.',
        'handler' => static fn (): bool => PedidoRepository::marcarEntregado($pedidoId, (int) $camarero['id']),
    ],
];

if ($estadoActual !== $acciones[$accion]['estado']) {
    flash_set('error', 'El pedido no está en el estado esperado para esa acción.');
    redirect_to('pedidos_camarero.php');
}

$ok = $acciones[$accion]['handler']();

if ($ok) {
    flash_set('ok', $acciones[$accion]['mensaje_ok']);
} else {
    flash_set('error', 'No se pudo cambiar el estado del pedido. Verifica el estado actual.');
}

redirect_to('pedidos_camarero.php');
