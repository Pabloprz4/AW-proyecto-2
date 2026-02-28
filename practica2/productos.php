<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

// solo gereente
$usuarioActual = require_role('gerente');
$productos = ProductoRepository::all(true);

$filas = '';
foreach ($productos as $producto) {
    $acciones = '<a href="' . h(base_url('producto_form.php?id=' . (int) $producto['id'])) . '">Editar</a>';

    // boton para dejar de ofertar 
    if ((int) $producto['ofertado'] === 1) {
        $acciones .= 
            '<form method="post" action="' . h(base_url('producto_borrar.php')) . '" style="display:inline;">' .
            csrf_field() .
            '<input type="hidden" name="id" value="' . (int) $producto['id'] . '">' .
            '<button type="submit">Quitar oferta</button>' .
            '</form>';
    }

    $precioConFormato = number_format((float) $producto['precio'], 2) . ' €';

    $filas .= '<tr>' .
        '<td>' . (int) $producto['id'] . '</td>' .
        '<td><img src="' . h(base_url((string) $producto['foto'])) . '" alt="Foto" width="50"></td>' .
        '<td>' . h((string) $producto['nombre']) . '</td>' .
        '<td>' . h((string) $producto['categoria_nombre']) . '</td>' .
        '<td>' . h($precioConFormato) . '</td>' .
        '<td>' . ((int) $producto['ofertado'] === 1 ? 'Sí' : 'No') . '</td>' .
        '<td>' . $acciones . '</td>' .
        '</tr>';
}

$contenido = <<<HTML
<section>
  <h2>Gestión de Productos</h2>
  <p><a href="{nuevo}">Crear nuevo producto</a></p>
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>ID</th>
        <th>Foto</th>
        <th>Nombre</th>
        <th>Categoría</th>
        <th>Precio</th>
        <th>Ofertado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      {$filas}
    </tbody>
  </table>
</section>
HTML;

$contenido = str_replace('{nuevo}', h(base_url('producto_form.php')), $contenido);

render_page('Gestión de Productos', $contenido);
