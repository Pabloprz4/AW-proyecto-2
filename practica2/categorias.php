<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// solo el gerente puede gestionar categorías
$usuarioActual = require_role('gerente');
$categorias = CategoriaRepository::all();

$filas = '';
foreach ($categorias as $categoria) {
    $acciones = '<a href="' . h(base_url('categoria_form.php?id=' . (int) $categoria['id'])) . '">Editar</a> ';

    $acciones .=
        '<form method="post" action="' . h(base_url('categoria_borrar.php')) . '" style="display:inline;">' .
        csrf_field() .
        '<input type="hidden" name="id" value="' . (int) $categoria['id'] . '">' .
        '<button type="submit" onclick="return confirm(\'¿Seguro que quieres borrar esta categoría?\');">Borrar</button>' .
        '</form>';

    $filas .= '<tr>' .
        '<td>' . (int) $categoria['id'] . '</td>' .
        '<td>' . h((string) $categoria['nombre']) . '</td>' .
        '<td>' . h((string) ($categoria['descripcion'] ?? '')) . '</td>' .
        '<td>' . $acciones . '</td>' .
        '</tr>';
}

$contenido = <<<HTML
<section>
  <h2>Gestión de Categorías</h2>
  <p><a href="{nuevo}">Crear nueva categoría</a></p>
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {$filas}
    </tbody>
  </table>
</section>
HTML;

$contenido = str_replace('{nuevo}', h(base_url('categoria_form.php')), $contenido);

render_page('Gestión de Categorías', $contenido);

