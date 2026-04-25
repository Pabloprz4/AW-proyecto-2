<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gestor = require_role('gerente');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Petición inválida.');
    redirect_to('productos.php');
}

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    flash_set('error', 'ID de producto inválido.');
    redirect_to('productos.php');
}

ProductoRepository::setOfertado($id, false);
flash_set('ok', 'Producto retirado de la oferta correctamente.');

redirect_to('productos.php');
