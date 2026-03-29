<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gestor = require_role('gerente');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Petición inválida.');
    redirect_to('categorias.php');
}

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    flash_set('error', 'ID de categoría inválido.');
    redirect_to('categorias.php');
}

if (CategoriaRepository::delete($id)) {
    flash_set('ok', 'Categoría borrada correctamente.');
} else {
    flash_set('error', 'No se puede borrar la categoría porque tiene productos asociados.');
}

redirect_to('categorias.php');
