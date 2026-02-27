<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();

$bloqueGerente = '';
if (has_role('gerente', (string) $usuario['rol'])) {
    $bloqueGerente = '<li><a href="' . h(base_url('usuarios.php')) . '">Gestion de usuarios (funcionalidad 0)</a></li>';
}

$contenido = <<<HTML
<section>
  <h2>Panel principal</h2>
  <p>Bienvenido/a, {$usuario['nombre']}.</p>
  <p>Esta entrega implementa la estructura base y la funcionalidad 0 (usuarios).</p>
  <ul>
    <li><a href="{perfil}">Mi perfil</a></li>
    {$bloqueGerente}
  </ul>
</section>

<section>
  <h2>Estado del prototipo</h2>
  <ul>
    <li>Funcionalidad 0: implementada en esta rama.</li>
    <li>Login, logout, registro, perfil y gestion de usuarios por gerente: disponible.</li>
  </ul>
</section>
HTML;

$contenido = str_replace('{perfil}', h(base_url('perfil.php')), $contenido);

render_page('Inicio', $contenido);
