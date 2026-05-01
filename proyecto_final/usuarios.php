<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$usuarioActual = require_role('gerente');
$usuarios = UsuarioRepository::all(true);

$filas = '';
foreach ($usuarios as $usuario) {
    $acciones = '<a href="' . h(base_url('usuario_form.php?id=' . (int) $usuario['id'])) . '">Editar</a>';

    if ((int) $usuario['id'] !== (int) $usuarioActual['id'] && (int) $usuario['activo'] === 1) {
        $acciones .=
            '<form method="post" action="' . h(base_url('usuario_borrar.php')) . '">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . (int) $usuario['id'] . '">' .
            '<button type="submit">Desactivar</button>' .
            '</form>';
    }

    $filas .= '<tr>' .
        '<td>' . (int) $usuario['id'] . '</td>' .
        '<td>' . h((string) $usuario['nombre_usuario']) . '</td>' .
        '<td>' . h((string) $usuario['email']) . '</td>' .
        '<td>' . h((string) $usuario['nombre']) . '</td>' .
        '<td>' . h((string) $usuario['apellidos']) . '</td>' .
        '<td>' . h((string) $usuario['rol']) . '</td>' .
        '<td>' . ((int) $usuario['activo'] === 1 ? 'Activo' : 'Inactivo') . '</td>' .
        '<td>' . $acciones . '</td>' .
        '</tr>';
}

$contenido = <<<HTML
<section>
  <h2>Gestión de usuarios</h2>
  <p><a href="{nuevo}">Crear nuevo usuario</a></p>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Email</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {$filas}
    </tbody>
  </table>
</section>
HTML;

$contenido = str_replace('{nuevo}', h(base_url('usuario_form.php')), $contenido);

render_page('Gestión de usuarios', $contenido);
