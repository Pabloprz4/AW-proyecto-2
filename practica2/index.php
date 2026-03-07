<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = require_login();

$bloqueCliente = '<li><a href="' . h(base_url('pedido_nuevo.php')) . '">Crear pedido (funcionalidad 2)</a></li>';
$bloqueCliente .= '<li><a href="' . h(base_url('mis_pedidos.php')) . '">Consultar mis pedidos (funcionalidad 2)</a></li>';

$bloqueCamarero = '';
if (has_role('camarero', (string) $usuario['rol'])) {
    $bloqueCamarero .= '<li><a href="' . h(base_url('pedidos_camarero.php')) . '">Panel de camarero (funcionalidad 2)</a></li>';
}

$bloqueGerente = '';
if (has_role('gerente', (string) $usuario['rol'])) {
    $bloqueGerente .= '<li><a href="' . h(base_url('usuarios.php')) . '">Gestion de usuarios (funcionalidad 0)</a></li>';
    $bloqueGerente .= '<li><a href="' . h(base_url('categorias.php')) . '">Gestion de categorias (funcionalidad 1)</a></li>';
    $bloqueGerente .= '<li><a href="' . h(base_url('productos.php')) . '">Gestion de productos (funcionalidad 1)</a></li>';
    $bloqueGerente .= '<li><a href="' . h(base_url('pedidos.php')) . '">Consulta de pedidos (funcionalidad 2)</a></li>';
}

$contenido = <<<HTML
<section>
  <h2>Panel principal</h2>
  <p>Bienvenido/a, {$usuario['nombre']}.</p>
  <ul>
    <li><a href="{perfil}">Mi perfil</a></li>
    {$bloqueCliente}
    {$bloqueCamarero}
    {$bloqueGerente}
  </ul>
</section>

<section>
  <h2>Estado del prototipo</h2>
  <ul>
    <li>Funcionalidad 0: gestion de usuarios.</li>
    <li>Funcionalidad 1: gestion de categorias y productos.</li>
    <li>Funcionalidad 2: gestion de pedidos (cliente, camarero y consulta de gerente).</li>
  </ul>
</section>
HTML;

$contenido = str_replace('{perfil}', h(base_url('perfil.php')), $contenido);

render_page('Inicio', $contenido);
