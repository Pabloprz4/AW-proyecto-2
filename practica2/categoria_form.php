<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gestor = require_role('gerente');

$id = (int) ($_GET['id'] ?? 0);
$esEdicion = $id > 0;
$categoriaEditar = $esEdicion ? CategoriaRepository::findById($id) : null;

if ($esEdicion && !$categoriaEditar) {
    flash_set('error', 'Categoría no encontrada.');
    redirect_to('categorias.php');
}

$errores = [];
$datos = [
    'nombre' => $categoriaEditar['nombre'] ?? '',
    'descripcion' => $categoriaEditar['descripcion'] ?? '',
];

if (is_post()) {
    foreach ($datos as $campo => $_) {
        $datos[$campo] = trim((string) ($_POST[$campo] ?? ''));
    }

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF inválido.';
    }

    if ($datos['nombre'] === '') {
        $errores[] = 'El nombre de la categoría es obligatorio.';
    }

    if (!$errores) {
        if ($esEdicion) {
            CategoriaRepository::update($id, [
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'],
            ]);
            flash_set('ok', 'Categoría actualizada correctamente.');
        } else {
            CategoriaRepository::create([
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'],
            ]);
            flash_set('ok', 'Categoría creada correctamente.');
        }

        redirect_to('categorias.php');
    }
}

$listaErrores = '';
if ($errores) {
    $items = '';
    foreach ($errores as $error) {
        $items .= '<li>' . h($error) . '</li>';
    }
    $listaErrores = '<ul>' . $items . '</ul>';
}

$titulo = $esEdicion ? 'Editar categoría' : 'Crear categoría';

$contenido = <<<HTML
<section>
  <h2>{$titulo}</h2>
  {$listaErrores}
  <form method="post" action="{action}">
    {csrf}
    <p>
      <label for="nombre">Nombre de la categoría:</label><br>
      <input type="text" id="nombre" name="nombre" value="{nombre}" required>
    </p>
    <p>
      <label for="descripcion">Descripción (opcional):</label><br>
      <textarea id="descripcion" name="descripcion" rows="4" cols="40">{descripcion}</textarea>
    </p>
    <p>
      <button type="submit">Guardar</button>
      <a href="{volver}">Volver</a>
    </p>
  </form>
</section>
HTML;

$contenido = str_replace(
    ['{action}', '{csrf}', '{nombre}', '{descripcion}', '{volver}'],
    [
        h(base_url('categoria_form.php' . ($esEdicion ? '?id=' . $id : ''))),
        csrf_field(),
        h($datos['nombre']),
        h($datos['descripcion']),
        h(base_url('categorias.php'))
    ],
    $contenido
);


render_page($titulo, $contenido);
