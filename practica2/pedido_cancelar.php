<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Peticion invalida.');
    redirect_to('mis_pedidos.php');
}

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    flash_set('error', 'ID de pedido invalido.');
    redirect_to('mis_pedidos.php');
}

$ok = PedidoRepository::cancelarRecibido($id, (int) $usuario['id']);
if ($ok) {
    flash_set('ok', 'Pedido cancelado correctamente.');
} else {
    flash_set('error', 'No se pudo cancelar el pedido (solo se pueden cancelar pedidos en estado Recibido).');
}

redirect_to('mis_pedidos.php');
