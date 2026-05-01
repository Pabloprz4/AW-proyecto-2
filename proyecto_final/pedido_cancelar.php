<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Petición inválida.');
    redirect_to('mis_pedidos.php');
}

$id = post_positive_int('id');
if ($id === null) {
    flash_set('error', 'ID de pedido inválido.');
    redirect_to('mis_pedidos.php');
}

$pedido = PedidoRepository::findById($id);
if (
    !$pedido
    || (int) ($pedido['cliente_id'] ?? 0) !== (int) $usuario['id']
    || !in_array((string) $pedido['estado'], ['nuevo', 'recibido'], true)
) {
    flash_set('error', 'No se pudo cancelar el pedido.');
    redirect_to('mis_pedidos.php');
}

$ok = PedidoRepository::cancelarAbierto($id, (int) $usuario['id']);
if ($ok) {
    flash_set('ok', 'Pedido cancelado correctamente.');
} else {
    flash_set('error', 'No se pudo cancelar el pedido (solo se pueden cancelar pedidos en estado Nuevo o Recibido).');
}

redirect_to('mis_pedidos.php');
