<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
$usuarioLogueado = $app->usuarioLogueado();
$usuario = null;
$rolActual = 'anonimo';

if ($usuarioLogueado && function_exists('auth_user')) {
    $usuario = auth_user();
}

if (is_array($usuario)) {
    $rolActual = (string) ($usuario['rol'] ?? 'cliente');
}
elseif ($usuarioLogueado) {
    $rolActual = $app->esAdmin() ? 'gerente' : 'cliente';
}
?>
<nav id="sidebarIzq">
	<h3>Navegación</h3>
	<ul>
		<li><a href="<?= $app->resuelve('/index.php')?>">Inicio</a></li>
		<?php if (!$usuarioLogueado): ?>
			<li><a href="<?= $app->resuelve('/login.php')?>">Login</a></li>
			<li><a href="<?= $app->resuelve('/registro.php')?>">Registro</a></li>
		<?php else: ?>
			<li><a href="<?= $app->resuelve('/perfil.php')?>">Mi perfil</a></li>

			<?php if ($rolActual === 'cliente'): ?>
				<li><a href="<?= $app->resuelve('/pedido_nuevo.php')?>">Crear pedido</a></li>
				<li><a href="<?= $app->resuelve('/mis_pedidos.php')?>">Mis pedidos</a></li>
			<?php endif; ?>

			<?php if ($rolActual === 'camarero'): ?>
				<li><a href="<?= $app->resuelve('/pedidos_camarero.php')?>">Panel camarero</a></li>
			<?php endif; ?>

			<?php if ($rolActual === 'cocinero'): ?>
				<li><a href="<?= $app->resuelve('/cocina.php')?>">Panel cocina</a></li>
			<?php endif; ?>

			<?php if ($rolActual === 'gerente'): ?>
				<li><a href="<?= $app->resuelve('/usuarios.php')?>">Gestion usuarios</a></li>
				<li><a href="<?= $app->resuelve('/categorias.php')?>">Gestion categorias</a></li>
				<li><a href="<?= $app->resuelve('/productos.php')?>">Gestion productos</a></li>
				<li><a href="<?= $app->resuelve('/pedidos.php')?>">Pedidos gerente</a></li>
			<?php endif; ?>
		<?php endif; ?>
	</ul>
</nav>
