<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();

$bloqueGerente = '';
if (has_role('gerente', (string) $usuario['rol'])) {
    $bloqueGerente .= '<li><a href="' . h(base_url('usuarios.php')) . '">Gestión de usuarios (funcionalidad 0)</a></li>';
    $bloqueGerente .= '<li><a href="' . h(base_url('categorias.php')) . '">Gestión de categorías (funcionalidad 1)</a></li>';
    $bloqueGerente .= '<li><a href="' . h(base_url('productos.php')) . '">Gestión de productos (funcionalidad 1)</a></li>';
}

$contenido = <<<HTML
<section>
  <h2>Panel principal</h2>
  <p>Bienvenido/a, {$usuario['nombre']}.</p>
  <p>Esta entrega implementa la estructura base y las funcionalidades 0 (usuarios) y 1 (productos y categorías).</p>
  <ul>
    <li><a href="{perfil}">Mi perfil</a></li>
    {$bloqueGerente}
  </ul>
</section>

<section>
  <h2>Estado del prototipo</h2>
  <ul>
    <li>Funcionalidades 0 y 1: implementadas en esta rama.</li>
    <li>Login, logout, registro, perfil, gestión de usuarios, categorías y productos: disponible.</li>
  </ul>
</section>
HTML;

$contenido = str_replace('{perfil}', h(base_url('perfil.php')), $contenido);

render_page('Inicio', $contenido);
