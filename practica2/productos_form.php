<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gestor = require_role('gerente');

$id = (int) ($_GET['id'] ?? 0);
$esEdicion = $id > 0;
$productoEditar = $esEdicion ? ProductoRepository::findById($id) : null;

if ($esEdicion && !$productoEditar) {
    flash_set('error', 'Producto no encontrado.');
    redirect_to('productos.php');
}

// conseguir las categorías para el desplegable
$categorias = CategoriaRepository::all();
if (empty($categorias)) {
    flash_set('error', 'Debes crear al menos una categoría antes de crear un producto.');
    redirect_to('categorias.php');
}

$errores = [];
$datos = [
    'categoria_id' => (string) ($productoEditar['categoria_id'] ?? $categorias[0]['id']),
    'nombre' => $productoEditar['nombre'] ?? '',
    'descripcion' => $productoEditar['descripcion'] ?? '',
    'precio' => (string) ($productoEditar['precio'] ?? '0.00'),
    'iva' => (string) ($productoEditar['iva'] ?? '21.00'),
    'ofertado' => (string) ($productoEditar['ofertado'] ?? '1'),
];

if (is_post()) {
    foreach ($datos as $campo => $_) {
        $datos[$campo] = trim((string) ($_POST[$campo] ?? ''));
    }

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF inválido.';
    }

    if ($datos['nombre'] === '') {
        $errores[] = 'El nombre del producto es obligatorio.';
    }
    
    if (!is_numeric($datos['precio']) || (float) $datos['precio'] < 0) {
        $errores[] = 'El precio debe ser un número válido.';
    }

    // subir imagen
    $fotoPath = null;
    $fotoUpload = $_FILES['foto'] ?? null;

    if (!$errores && is_array($fotoUpload) && ($fotoUpload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
        if (($fotoUpload['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $errores[] = 'Error al subir la foto.';
        } else {
            $tmp = (string) $fotoUpload['tmp_name'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmp);

            $ext = match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => '',
            };

            if ($ext === '') {
                $errores[] = 'Formato de foto no válido (solo JPG, PNG o WEBP).';
            } else {
                // Guardamos en la carpeta img/
                $fileName = 'p_' . time() . '_' . random_int(100, 999) . '.' . $ext;
                $destinoFisico = __DIR__ . '/img/' . $fileName; 
                
                // Asegurarse de que la carpeta img/ existe
                if (!is_dir(__DIR__ . '/img')) {
                    mkdir(__DIR__ . '/img', 0777, true);
                }

                if (!move_uploaded_file($tmp, $destinoFisico)) {
                    $errores[] = 'No se pudo guardar la foto subida.';
                } else {
                    $fotoPath = 'img/' . $fileName;
                }
            }
        }
    }

    if (!$errores) {
        if ($esEdicion) {
            ProductoRepository::update($id, $datos);
            if ($fotoPath !== null) {
                ProductoRepository::setFoto($id, $fotoPath);
            }
            flash_set('ok', 'Producto actualizado correctamente.');
        } else {
            $datos['foto'] = $fotoPath;
            ProductoRepository::create($datos);
            flash_set('ok', 'Producto creado correctamente.');
        }

        redirect_to('productos.php');
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

// opciones del select de categorías
$opcionesCategoria = '';
foreach ($categorias as $cat) {
    $selected = $datos['categoria_id'] === (string) $cat['id'] ? ' selected' : '';
    $opcionesCategoria .= '<option value="' . (int) $cat['id'] . '"' . $selected . '>' . h((string) $cat['nombre']) . '</option>';
}

// opciones de estado (ofertado o no ofertado)
$opcionesOfertado = '';
foreach (['1' => 'Sí, mostrar a clientes', '0' => 'No (oculto)'] as $valor => $texto) {
    $selected = $datos['ofertado'] === (string) $valor ? ' selected' : '';
    $opcionesOfertado .= '<option value="' . h((string) $valor) . '"' . $selected . '>' . h($texto) . '</option>';
}

// Si editamos y tiene foto, se muestra
$fotoHtml = '';
if ($esEdicion && !empty($productoEditar['foto'])) {
    $fotoHtml = '<p>Foto actual:<br><img src="' . h(base_url((string) $productoEditar['foto'])) . '" alt="Foto" width="120"></p>';
}

$titulo = $esEdicion ? 'Editar producto' : 'Crear producto';

$contenido = <<<HTML
<section>
  <h2>{$titulo}</h2>
  {$listaErrores}
  {$fotoHtml}
  <form method="post" action="{action}" enctype="multipart/form-data">
    {csrf}
    <p>
      <label for="categoria_id">Categoría:</label><br>
      <select id="categoria_id" name="categoria_id">{$opcionesCategoria}</select>
    </p>
    <p>
      <label for="nombre">Nombre del plato/producto:</label><br>
      <input type="text" id="nombre" name="nombre" value="{nombre}" required>
    </p>
    <p>
      <label for="descripcion">Descripción (ingredientes):</label><br>
      <textarea id="descripcion" name="descripcion" rows="3" cols="40">{descripcion}</textarea>
    </p>
    <p>
      <label for="precio">Precio (€):</label><br>
      <input type="number" step="0.01" id="precio" name="precio" value="{precio}" required>
    </p>
    <p>
      <label for="iva">IVA (%):</label><br>
      <input type="number" step="0.01" id="iva" name="iva" value="{iva}" required>
    </p>
    <p>
      <label for="ofertado">¿Está ofertado?</label><br>
      <select id="ofertado" name="ofertado">{$opcionesOfertado}</select>
    </p>
    <p>
      <label for="foto">Foto (JPG, PNG o WEBP):</label><br>
      <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
    </p>
    <p>
      <button type="submit">Guardar</button>
      <a href="{volver}">Volver</a>
    </p>
  </form>
</section>
HTML;

$contenido = str_replace(
    ['{action}', '{csrf}', '{nombre}', '{descripcion}', '{precio}', '{iva}', '{volver}'],
    [
        h(base_url('producto_form.php' . ($esEdicion ? '?id=' . $id : ''))),
        csrf_field(),
        h($datos['nombre']),
        h($datos['descripcion']),
        h($datos['precio']),
        h($datos['iva']),
        h(base_url('productos.php'))
    ],
    $contenido
);

render_page($titulo, $contenido);
