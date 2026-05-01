<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuarioActual = require_role('gerente');

if (!is_post() || !verify_csrf()) {
    flash_set('error', 'Petición inválida.');
    redirect_to('usuarios.php');
}

$id = post_positive_int('id');
if ($id === null) {
    flash_set('error', 'ID de usuario inválido.');
    redirect_to('usuarios.php');
}

if ($id === (int) $usuarioActual['id']) {
    flash_set('error', 'No puedes desactivarte a ti mismo.');
    redirect_to('usuarios.php');
}

$usuario = UsuarioRepository::findById($id);
if (!$usuario) {
    flash_set('error', 'Usuario no encontrado.');
    redirect_to('usuarios.php');
}

if ((int) ($usuario['activo'] ?? 0) !== 1) {
    flash_set('ok', 'El usuario ya estaba inactivo.');
    redirect_to('usuarios.php');
}

if (UsuarioRepository::setActivo($id, false)) {
    flash_set('ok', 'Usuario desactivado correctamente.');
} else {
    flash_set('error', 'No se pudo desactivar el usuario.');
}
redirect_to('usuarios.php');
