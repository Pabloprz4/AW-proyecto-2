<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gestor = require_role('gerente');

$id = (int) ($_GET['id'] ?? 0);
$esEdicion = $id > 0;
$usuarioEditar = $esEdicion ? UsuarioRepository::findById($id) : null;

if ($esEdicion && !$usuarioEditar) {
    flash_set('error', 'Usuario no encontrado.');
    redirect_to('usuarios.php');
}

$errores = [];
$datos = [
    'nombre_usuario' => $usuarioEditar['nombre_usuario'] ?? '',
    'email' => $usuarioEditar['email'] ?? '',
    'nombre' => $usuarioEditar['nombre'] ?? '',
    'apellidos' => $usuarioEditar['apellidos'] ?? '',
    'rol' => $usuarioEditar['rol'] ?? 'cliente',
    'activo' => (string) ($usuarioEditar['activo'] ?? 1),
];

if (is_post()) {
    foreach ($datos as $campo => $_) {
        $datos[$campo] = trim((string) ($_POST[$campo] ?? ''));
    }

    $password = (string) ($_POST['password'] ?? '');
    $password2 = (string) ($_POST['password2'] ?? '');

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF invalido.';
    }

    if (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $datos['nombre_usuario'])) {
        $errores[] = 'Nombre de usuario invalido.';
    }

    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Email invalido.';
    }

    if ($datos['nombre'] === '' || $datos['apellidos'] === '') {
        $errores[] = 'Nombre y apellidos son obligatorios.';
    }

    if (!in_array($datos['rol'], ['cliente', 'camarero', 'cocinero', 'gerente'], true)) {
        $errores[] = 'Rol invalido.';
    }

    if (!$esEdicion && strlen($password) < 6) {
        $errores[] = 'Para crear usuario, la contrasena debe tener al menos 6 caracteres.';
    }

    if ($password !== '' && strlen($password) < 6) {
        $errores[] = 'La contrasena debe tener al menos 6 caracteres.';
    }

    if ($password !== $password2) {
        $errores[] = 'Las contrasenas no coinciden.';
    }

    $excludeId = $esEdicion ? $id : null;
    if (UsuarioRepository::usernameExists($datos['nombre_usuario'], $excludeId)) {
        $errores[] = 'Ese nombre de usuario ya existe.';
    }

    if (UsuarioRepository::emailExists($datos['email'], $excludeId)) {
        $errores[] = 'Ese email ya existe.';
    }

    if ($esEdicion && $id === (int) $gestor['id'] && $datos['rol'] !== 'gerente') {
        $errores[] = 'No puedes quitarte a ti mismo el rol de gerente.';
    }

    if (!$errores) {
        if ($esEdicion) {
            UsuarioRepository::updateProfile($id, [
                'nombre_usuario' => $datos['nombre_usuario'],
                'email' => $datos['email'],
                'nombre' => $datos['nombre'],
                'apellidos' => $datos['apellidos'],
                'password' => $password,
            ]);
            UsuarioRepository::setRole($id, $datos['rol']);
            UsuarioRepository::setActivo($id, $datos['activo'] === '1');
            flash_set('ok', 'Usuario actualizado correctamente.');
        } else {
            UsuarioRepository::create([
                'nombre_usuario' => $datos['nombre_usuario'],
                'email' => $datos['email'],
                'nombre' => $datos['nombre'],
                'apellidos' => $datos['apellidos'],
                'password' => $password,
                'rol' => $datos['rol'],
                'avatar' => null,
                'activo' => 1,
            ]);
            flash_set('ok', 'Usuario creado correctamente.');
        }

        redirect_to('usuarios.php');
    }
}

$listaErrores = '';
if ($errores) {
    $items = '';
    foreach ($errores as $error) {
        $items .= '<li>' . h($error) . '</li>';
    }
    $listaErrores = '<ul>' . $items . '</ul>';
}

$opcionesRol = '';
foreach (['cliente', 'camarero', 'cocinero', 'gerente'] as $rol) {
    $selected = $datos['rol'] === $rol ? ' selected' : '';
    $opcionesRol .= '<option value="' . h($rol) . '"' . $selected . '>' . h($rol) . '</option>';
}

$opcionesActivo = '';
foreach (['1' => 'Activo', '0' => 'Inactivo'] as $valor => $texto) {
    $valorStr = (string) $valor;
    $selected = $datos['activo'] === $valorStr ? ' selected' : '';
    $opcionesActivo .= '<option value="' . h($valorStr) . '"' . $selected . '>' . h($texto) . '</option>';
}

$titulo = $esEdicion ? 'Editar usuario' : 'Crear usuario';

$contenido = <<<HTML
<section>
  <h2>{$titulo}</h2>
  {$listaErrores}
  <form method="post" action="{action}">
    {csrf}
    <p>
      <label for="nombre_usuario">Nombre de usuario:</label><br>
      <input type="text" id="nombre_usuario" name="nombre_usuario" value="{nombre_usuario}" required>
    </p>
    <p>
      <label for="email">Email:</label><br>
      <input type="email" id="email" name="email" value="{email}" required>
    </p>
    <p>
      <label for="nombre">Nombre:</label><br>
      <input type="text" id="nombre" name="nombre" value="{nombre}" required>
    </p>
    <p>
      <label for="apellidos">Apellidos:</label><br>
      <input type="text" id="apellidos" name="apellidos" value="{apellidos}" required>
    </p>
    <p>
      <label for="rol">Rol:</label><br>
      <select id="rol" name="rol">{$opcionesRol}</select>
    </p>
    <p>
      <label for="activo">Estado:</label><br>
      <select id="activo" name="activo">{$opcionesActivo}</select>
    </p>
    <p>
      <label for="password">Contrasena {pw_hint}:</label><br>
      <input type="password" id="password" name="password">
    </p>
    <p>
      <label for="password2">Repetir contrasena:</label><br>
      <input type="password" id="password2" name="password2">
    </p>
    <p>
      <button type="submit">Guardar</button>
      <a href="{volver}">Volver</a>
    </p>
  </form>
</section>
HTML;

$pwHint = $esEdicion ? '(dejar vacia para no cambiarla)' : '(obligatoria, minimo 6 caracteres)';

$contenido = str_replace(
    ['{action}', '{csrf}', '{nombre_usuario}', '{email}', '{nombre}', '{apellidos}', '{volver}', '{pw_hint}'],
    [
        h(base_url('usuario_form.php' . ($esEdicion ? '?id=' . $id : ''))),
        csrf_field(),
        h($datos['nombre_usuario']),
        h($datos['email']),
        h($datos['nombre']),
        h($datos['apellidos']),
        h(base_url('usuarios.php')),
        h($pwHint),
    ],
    $contenido
);

render_page($titulo, $contenido);
