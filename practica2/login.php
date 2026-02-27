<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (auth_check()) {
    redirect_to('index.php');
}

$errores = [];
$nombreUsuario = '';

if (is_post()) {
    $nombreUsuario = trim((string) ($_POST['nombre_usuario'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF invalido.';
    }

    if ($nombreUsuario === '' || $password === '') {
        $errores[] = 'Debes rellenar nombre de usuario y contrasena.';
    }

    if (!$errores && auth_login($nombreUsuario, $password)) {
        flash_set('ok', 'Login correcto.');
        redirect_to('index.php');
    }

    if (!$errores) {
        $errores[] = 'Credenciales incorrectas o usuario desactivado.';
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
  <h2>Login</h2>
  {$listaErrores}
  <form method="post" action="{action}">
    {csrf}
    <p>
      <label for="nombre_usuario">Nombre de usuario:</label><br>
      <input type="text" id="nombre_usuario" name="nombre_usuario" value="{usuario}" required>
    </p>
    <p>
      <label for="password">Contrasena:</label><br>
      <input type="password" id="password" name="password" required>
    </p>
    <p><button type="submit">Entrar</button></p>
  </form>
  <p><a href="{registro}">No tienes cuenta? Registrate</a></p>
</section>
HTML;

$contenido = str_replace(
    ['{action}', '{csrf}', '{usuario}', '{registro}'],
    [h(base_url('login.php')), csrf_field(), h($nombreUsuario), h(base_url('registro.php'))],
    $contenido
);

render_page('Login', $contenido);
