<?php
use es\ucm\fdi\aw\Aplicacion;

$app = Aplicacion::getInstance();
$usuarioLogueado = $app->usuarioLogueado();
$usuario = null;
$rolTexto = 'Invitado';

if ($usuarioLogueado && function_exists('auth_user')) {
    $usuario = auth_user();
}

if (is_array($usuario)) {
    $rolTexto = ucfirst((string) ($usuario['rol'] ?? 'cliente'));
}
elseif ($usuarioLogueado) {
    $rolTexto = $app->esAdmin() ? 'Gerente' : 'Cliente';
}
?>
<aside id="sidebarDer">
	<h3>Sesion</h3>
	<p><strong>Rol:</strong> <?= htmlspecialchars($rolTexto, ENT_QUOTES, 'UTF-8') ?></p>
	<?php if (!$usuarioLogueado): ?>
		<p>Inicia sesion para acceder al panel segun tu rol.</p>
	<?php else: ?>
		<p>Navega usando los enlaces del menu izquierdo.</p>
	<?php endif; ?>
</aside>
