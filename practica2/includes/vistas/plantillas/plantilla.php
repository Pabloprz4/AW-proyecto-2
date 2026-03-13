<?php
$usuarioSesion = auth_user();
$flashOk = flash_get('ok');
$flashError = flash_get('error');
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($tituloPagina) ?></title>
</head>
<body>
  <header>
    <h1>Bistro FDI - Practica 2</h1>
    <nav aria-label="Navegacion principal">
      <ul>
        <li><a href="<?= h(base_url('index.php')) ?>">Inicio</a></li>
        <?php if ($usuarioSesion): ?>
          <li><a href="<?= h(base_url('perfil.php')) ?>">Perfil</a></li>
          <li><a href="<?= h(base_url('pedido_nuevo.php')) ?>">Nuevo pedido</a></li>
          <li><a href="<?= h(base_url('mis_pedidos.php')) ?>">Mis pedidos</a></li>
          <?php if (has_role('camarero', (string) $usuarioSesion['rol'])): ?>
            <li><a href="<?= h(base_url('pedidos_camarero.php')) ?>">Panel camarero</a></li>
          <?php endif; ?>
          <?php if (has_role('gerente', (string) $usuarioSesion['rol'])): ?>
            <li><a href="<?= h(base_url('usuarios.php')) ?>">Usuarios</a></li>
            <li><a href="<?= h(base_url('categorias.php')) ?>">Categorias</a></li>
            <li><a href="<?= h(base_url('productos.php')) ?>">Productos</a></li>
            <li><a href="<?= h(base_url('pedidos.php')) ?>">Pedidos</a></li>
          <?php endif; ?>
          <li>
            <form method="post" action="<?= h(base_url('logout.php')) ?>">
              <?= csrf_field() ?>
              <button type="submit">Cerrar sesion</button>
            </form>
          </li>
        <?php else: ?>
          <li><a href="<?= h(base_url('login.php')) ?>">Login</a></li>
          <li><a href="<?= h(base_url('registro.php')) ?>">Registro</a></li>
        <?php endif; ?>
      </ul>
    </nav>
    <?php if ($usuarioSesion): ?>
      <p><img src="<?= h(avatar_web_url(isset($usuarioSesion['avatar']) ? (string) $usuarioSesion['avatar'] : null)) ?>" alt="Avatar usuario" width="50"></p>
      <p>Sesion iniciada como <strong><?= h((string) $usuarioSesion['nombre_usuario']) ?></strong> (rol: <?= h((string) $usuarioSesion['rol']) ?>)</p>
    <?php endif; ?>
  </header>

  <main>
    <?php if ($flashOk): ?>
      <p><strong><?= h($flashOk) ?></strong></p>
    <?php endif; ?>

    <?php if ($flashError): ?>
      <p><strong><?= h($flashError) ?></strong></p>
    <?php endif; ?>

    <?= $contenidoPrincipal ?>
  </main>
</body>
</html>
