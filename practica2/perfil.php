<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuarioSesion = require_login();
$usuario = UsuarioRepository::findById((int) $usuarioSesion['id']);

if (!$usuario) {
    auth_logout();
    flash_set('error', 'No se encontro tu usuario.');
    redirect_to('login.php');
}

$errores = [];

if (is_post()) {
    $accion = (string) ($_POST['accion'] ?? 'guardar');

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF invalido.';
    }

    if (!$errores && $accion === 'quitar_avatar') {
        UsuarioRepository::setAvatar((int) $usuario['id'], null);
        $_SESSION['auth_user']['avatar'] = null;
        flash_set('ok', 'Avatar eliminado.');
        redirect_to('perfil.php');
    }

    if (!$errores && $accion === 'guardar') {
        $nombreUsuario = trim((string) ($_POST['nombre_usuario'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $nombre = trim((string) ($_POST['nombre'] ?? ''));
        $apellidos = trim((string) ($_POST['apellidos'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $password2 = (string) ($_POST['password2'] ?? '');

        if (!preg_match('/^[a-zA-Z0-9._-]{3,30}$/', $nombreUsuario)) {
            $errores[] = 'Nombre de usuario invalido.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Email invalido.';
        }

        if ($nombre === '' || $apellidos === '') {
            $errores[] = 'Nombre y apellidos son obligatorios.';
        }

        if ($password !== '' && strlen($password) < 6) {
            $errores[] = 'La contrasena nueva debe tener al menos 6 caracteres.';
        }

        if ($password !== $password2) {
            $errores[] = 'Las contrasenas no coinciden.';
        }

        if (UsuarioRepository::usernameExists($nombreUsuario, (int) $usuario['id'])) {
            $errores[] = 'Ese nombre de usuario ya esta en uso.';
        }

        if (UsuarioRepository::emailExists($email, (int) $usuario['id'])) {
            $errores[] = 'Ese email ya esta en uso.';
        }

        $avatarPath = null;
        $avatarUpload = $_FILES['avatar'] ?? null;

        if (!$errores && is_array($avatarUpload) && ($avatarUpload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            if (($avatarUpload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                $errores[] = 'Error al subir el avatar.';
            } else {
                $tmp = (string) $avatarUpload['tmp_name'];
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($tmp);

                $ext = match ($mime) {
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                    default => '',
                };

                if ($ext === '') {
                    $errores[] = 'Formato de avatar no valido (solo JPG, PNG o WEBP).';
                } else {
                    $fileName = 'u' . (int) $usuario['id'] . '_' . time() . '.' . $ext;
                    $destinoFisico = UPLOAD_AVATARS_DIR . '/' . $fileName;
                    if (!move_uploaded_file($tmp, $destinoFisico)) {
                        $errores[] = 'No se pudo guardar el avatar subido.';
                    } else {
                        $avatarPath = 'uploads/avatars/' . $fileName;
                    }
                }
            }
        }

        if (!$errores) {
            UsuarioRepository::updateProfile((int) $usuario['id'], [
                'nombre_usuario' => $nombreUsuario,
                'email' => $email,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'password' => $password,
            ]);

            if ($avatarPath !== null) {
                UsuarioRepository::setAvatar((int) $usuario['id'], $avatarPath);
            }

            $usuarioActualizado = UsuarioRepository::findById((int) $usuario['id']);
            if ($usuarioActualizado) {
                $_SESSION['auth_user'] = [
                    'id' => (int) $usuarioActualizado['id'],
                    'nombre_usuario' => (string) $usuarioActualizado['nombre_usuario'],
                    'nombre' => (string) $usuarioActualizado['nombre'],
                    'rol' => (string) $usuarioActualizado['rol'],
                    'avatar' => $usuarioActualizado['avatar'] ? (string) $usuarioActualizado['avatar'] : null,
                ];
            }

            flash_set('ok', 'Perfil actualizado correctamente.');
            redirect_to('perfil.php');
        }
    }
}

$usuario = UsuarioRepository::findById((int) $usuarioSesion['id']) ?: $usuario;
$avatarHtml = '';
if (!empty($usuario['avatar'])) {
    $avatarHtml = '<p><img src="' . h(base_url((string) $usuario['avatar'])) . '" alt="Avatar" width="120"></p>';
}

$listaErrores = '';
if ($errores) {
    $items = '';
    foreach ($errores as $error) {
        $items .= '<li>' . h($error) . '</li>';
    }
    $listaErrores = '<ul>' . $items . '</ul>';
}

$pedidosActivosPerfil = PedidoRepository::forCliente((int) $usuario['id'], ['en_preparacion', 'cocinando', 'listo_cocina', 'terminado']);
$filasPedidosPerfil = '';
foreach ($pedidosActivosPerfil as $pedido) {
    $numeroVisible = (int) $pedido['numero_dia'] . '/' . (string) $pedido['fecha_dia'];
    $filasPedidosPerfil .= '<tr>' .
        '<td>' . (int) $pedido['id'] . '</td>' .
        '<td>' . h($numeroVisible) . '</td>' .
        '<td>' . h(PedidoRepository::estadoLabel((string) $pedido['estado'])) . '</td>' .
        '<td>' . h(money_eur((float) $pedido['total'])) . '</td>' .
        '<td><a href="' . h(base_url('pedido_detalle.php?id=' . (int) $pedido['id'])) . '">Detalle</a></td>' .
        '</tr>';
}

if ($filasPedidosPerfil === '') {
    $filasPedidosPerfil = '<tr><td colspan="5">No tienes pedidos en seguimiento.</td></tr>';
}

$bloquePedidosPerfil = <<<HTML
<section>
  <h2>Pedidos en seguimiento</h2>
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>ID</th>
        <th>Numero dia</th>
        <th>Estado</th>
        <th>Total</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {$filasPedidosPerfil}
    </tbody>
  </table>
  <p><a href="{mis_pedidos}">Ver historico completo de pedidos</a></p>
</section>
HTML;

$bloquePedidosPerfil = str_replace('{mis_pedidos}', h(base_url('mis_pedidos.php')), $bloquePedidosPerfil);

$contenido = <<<HTML
<section>
  <h2>Mi perfil</h2>
  {$listaErrores}
  {$avatarHtml}
  <form method="post" action="{action}" enctype="multipart/form-data">
    {csrf}
    <input type="hidden" name="accion" value="guardar">
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
      <label for="avatar">Avatar (JPG, PNG o WEBP):</label><br>
      <input type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
    </p>
    <p>
      <label for="password">Nueva contrasena (opcional):</label><br>
      <input type="password" id="password" name="password">
    </p>
    <p>
      <label for="password2">Repetir contrasena:</label><br>
      <input type="password" id="password2" name="password2">
    </p>
    <p><button type="submit">Guardar cambios</button></p>
  </form>

  <form method="post" action="{action}">
    {csrf}
    <input type="hidden" name="accion" value="quitar_avatar">
    <button type="submit">Quitar avatar</button>
  </form>
</section>

{$bloquePedidosPerfil}
HTML;

$contenido = str_replace(
    ['{action}', '{csrf}', '{nombre_usuario}', '{email}', '{nombre}', '{apellidos}'],
    [
        h(base_url('perfil.php')),
        csrf_field(),
        h((string) $usuario['nombre_usuario']),
        h((string) $usuario['email']),
        h((string) $usuario['nombre']),
        h((string) $usuario['apellidos']),
    ],
    $contenido
);

render_page('Perfil', $contenido);
