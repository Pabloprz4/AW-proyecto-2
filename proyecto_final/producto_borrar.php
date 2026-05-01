<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gestor = require_role('gerente');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Petición inválida.');
    redirect_to('productos.php');
}

$id = post_positive_int('id');
if ($id === null) {
    flash_set('error', 'ID de producto inválido.');
    redirect_to('productos.php');
}

$producto = ProductoRepository::findById($id);
if (!$producto) {
    flash_set('error', 'Producto no encontrado.');
    redirect_to('productos.php');
}

if (ProductoRepository::setOfertado($id, false)) {
    flash_set('ok', 'Producto retirado de la oferta correctamente.');
} else {
    flash_set('error', 'No se pudo retirar el producto de la oferta.');
}

redirect_to('productos.php');
