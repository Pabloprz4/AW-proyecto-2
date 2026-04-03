<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuario = auth_user();

if ($usuario === null) {
    $login = h(base_url('login.php'));
    $registro = h(base_url('registro.php'));

    $contenidoPrincipal = <<<HTML
<section>
  <h1>Panel principal</h1>
  <p>Bienvenido/a a BistrO FDI.</p>
  <p>Para acceder a las funcionalidades del prototipo inicia sesion o crea una cuenta.</p>
  <p>
    <a href="{$login}">Login</a> |
    <a href="{$registro}">Registro</a>
  </p>
</section>

<section>
  <h2>Estado del prototipo</h2>
  <ul>
    <li>Funcionalidad 0: gestion de usuarios y perfil.</li>
    <li>Funcionalidad 1: gestion de categorias y productos.</li>
    <li>Funcionalidad 2: gestion de pedidos (cliente, camarero y gerente).</li>
  </ul>
</section>
HTML;

    $params = [
        'tituloPagina' => 'Inicio',
        'cabecera' => 'Inicio',
        'contenidoPrincipal' => $contenidoPrincipal,
    ];

    $app->generaVista('/plantillas/plantilla.php', $params);
    exit;
}

$bloqueCliente = '<li><a href="' . h(base_url('pedido_nuevo.php')) . '">Crear pedido (funcionalidad 2)</a></li>';
$bloqueCliente .= '<li><a href="' . h(base_url('mis_pedidos.php')) . '">Consultar mis pedidos (funcionalidad 2)</a></li>';

$bloqueCamarero = '';
if (has_role('camarero', (string) $usuario['rol'])) {
    $bloqueCamarero .= '<li><a href="' . h(base_url('pedidos_camarero.php')) . '">Panel de camarero (funcionalidad 2)</a></li>';
}

$bloqueCocinero = '';
if (has_role('cocinero', (string) $usuario['rol'])) {
    $bloqueCocinero .= '<li><a href="' . h(base_url('cocina.php')) . '">Panel de cocina (funcionalidad 3)</a></li>';
}

$bloqueGerente = '';
if (has_role('gerente', (string) $usuario['rol'])) {
    $bloqueGerente .= '<li><a href="' . h(base_url('usuarios.php')) . '">Gestion de usuarios (funcionalidad 0)</a></li>';
    $bloqueGerente .= '<li><a href="' . h(base_url('categorias.php')) . '">Gestion de categorias (funcionalidad 1)</a></li>';
    $bloqueGerente .= '<li><a href="' . h(base_url('productos.php')) . '">Gestion de productos (funcionalidad 1)</a></li>';
    $bloqueGerente .= '<li><a href="' . h(base_url('pedidos.php')) . '">Consulta de pedidos (funcionalidad 2)</a></li>';
}

$nombre = h((string) ($usuario['nombre'] ?? $usuario['nombre_usuario'] ?? 'Usuario'));

$contenidoPrincipal = <<<HTML
<section>
  <h1>Panel principal</h1>
  <p>Bienvenido/a, {$nombre}.</p>
  <ul>
    <li><a href="{perfil}">Mi perfil</a></li>
    {$bloqueCliente}
    {$bloqueCamarero}
    {$bloqueCocinero}
    {$bloqueGerente}
  </ul>
</section>

<section>
  <h2>Estado del prototipo</h2>
  <ul>
    <li>Funcionalidad 0: gestion de usuarios y perfil.</li>
    <li>Funcionalidad 1: gestion de categorias y productos.</li>
    <li>Funcionalidad 2: gestion de pedidos (cliente, camarero y gerente).</li>
    <li>Funcionalidad 3: preparacion en cocina (cocinero).</li>
  </ul>
</section>
HTML;

$contenidoPrincipal = str_replace('{perfil}', h(base_url('perfil.php')), $contenidoPrincipal);

$params = [
    'tituloPagina' => 'Inicio',
    'cabecera' => 'Inicio',
    'contenidoPrincipal' => $contenidoPrincipal,
];

$app->generaVista('/plantillas/plantilla.php', $params);
