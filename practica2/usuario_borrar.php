<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuarioActual = require_role('gerente');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Peticion invalida.');
    redirect_to('usuarios.php');
}

$id = (int) ($_POST['id'] ?? 0);
if ($id <= 0) {
    flash_set('error', 'ID de usuario invalido.');
    redirect_to('usuarios.php');
}

if ($id === (int) $usuarioActual['id']) {
    flash_set('error', 'No puedes desactivarte a ti mismo.');
    redirect_to('usuarios.php');
}

UsuarioRepository::setActivo($id, false);
flash_set('ok', 'Usuario desactivado correctamente.');
redirect_to('usuarios.php');
