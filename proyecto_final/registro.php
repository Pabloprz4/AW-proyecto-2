<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (auth_check()) {
    redirect_to('index.php');
}

$errores = [];
$datos = [
    'nombre_usuario' => '',
    'email' => '',
    'nombre' => '',
    'apellidos' => '',
];

if (is_post()) {
    foreach ($datos as $campo => $_) {
        $datos[$campo] = post_trimmed_string($campo);
    }

    $password = post_string('password');
    $password2 = post_string('password2');

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF inválido.';
    }

    if (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $datos['nombre_usuario'])) {
        $errores[] = 'Nombre de usuario inválido (3-30 caracteres alfanuméricos, ., _, -).';
    }

    if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Email inválido.';
    }

    if ($datos['nombre'] === '' || $datos['apellidos'] === '') {
        $errores[] = 'Nombre y apellidos son obligatorios.';
    }

    if (strlen($password) < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres.';
    }

    if ($password !== $password2) {
        $errores[] = 'Las contraseñas no coinciden.';
    }

    if (UsuarioRepository::usernameExists($datos['nombre_usuario'])) {
        $errores[] = 'El nombre de usuario ya existe.';
    }

    if (UsuarioRepository::emailExists($datos['email'])) {
        $errores[] = 'El email ya existe.';
    }

    if (!$errores) {
        UsuarioRepository::create([
            'nombre_usuario' => $datos['nombre_usuario'],
            'email' => $datos['email'],
            'nombre' => $datos['nombre'],
            'apellidos' => $datos['apellidos'],
            'password' => $password,
            'rol' => 'cliente',
            'avatar' => null,
            'activo' => 1,
        ]);

        flash_set('ok', 'Usuario creado correctamente. Ya puedes iniciar sesión.');
        redirect_to('login.php');
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

$contenido = <<<HTML
<section>
  <h2>Registro de usuario</h2>
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
      <label for="password">Contraseña:</label><br>
      <input type="password" id="password" name="password" required>
    </p>
    <p>
      <label for="password2">Repite la contraseña:</label><br>
      <input type="password" id="password2" name="password2" required>
    </p>
    <p><button type="submit">Crear cuenta</button></p>
  </form>
</section>
HTML;

$contenido = str_replace(
    ['{action}', '{csrf}', '{nombre_usuario}', '{email}', '{nombre}', '{apellidos}'],
    [
        h(base_url('registro.php')),
        csrf_field(),
        h($datos['nombre_usuario']),
        h($datos['email']),
        h($datos['nombre']),
        h($datos['apellidos']),
    ],
    $contenido
);

render_page('Registro', $contenido);
