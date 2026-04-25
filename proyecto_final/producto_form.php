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
    'disponible' => (string) ($productoEditar['disponible'] ?? '1'),
    'ofertado' => (string) ($productoEditar['ofertado'] ?? '1'),
];

if ($esEdicion && !empty($productoEditar['foto'])) {
    ProductoRepository::ensureMainImageInCollection($id, (string) $productoEditar['foto']);
}
$imagenesActuales = $esEdicion ? ProductoRepository::imagesByProducto($id) : [];

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

    if (!in_array($datos['disponible'], ['0', '1'], true)) {
        $errores[] = 'El estado de disponibilidad no es valido.';
    }

    if (!in_array($datos['ofertado'], ['0', '1'], true)) {
        $errores[] = 'El estado de oferta no es valido.';
    }

    if (!in_array($datos['iva'], ['4', '4.00', '10', '10.00', '21', '21.00'], true)) {
        $errores[] = 'El IVA debe ser 4, 10 o 21.';
    }

    $nuevasRutas = [];
    $fotosUpload = $_FILES['fotos'] ?? null;
    if (is_array($fotosUpload) && isset($fotosUpload['name']) && is_array($fotosUpload['name'])) {
        $total = count($fotosUpload['name']);
        for ($i = 0; $i < $total; $i++) {
            $error = (int) ($fotosUpload['error'][$i] ?? UPLOAD_ERR_NO_FILE);
            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($error !== UPLOAD_ERR_OK) {
                $errores[] = 'Error al subir una de las imagenes.';
                continue;
            }

            $tmp = (string) ($fotosUpload['tmp_name'][$i] ?? '');
            if ($tmp === '') {
                $errores[] = 'No se pudo procesar una imagen subida.';
                continue;
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmp);

            $ext = match ($mime) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => '',
            };

            if ($ext === '') {
                $errores[] = 'Formato de imagen no valido (solo JPG, PNG o WEBP).';
                continue;
            }

            if (!is_dir(__DIR__ . '/img')) {
                mkdir(__DIR__ . '/img', 0777, true);
            }

            $fileName = 'p_' . time() . '_' . random_int(100, 999) . '_' . $i . '.' . $ext;
            $destinoFisico = __DIR__ . '/img/' . $fileName;
            if (!move_uploaded_file($tmp, $destinoFisico)) {
                $errores[] = 'No se pudo guardar una de las imagenes.';
                continue;
            }

            $nuevasRutas[] = 'img/' . $fileName;
        }
    }

    $idsBorrar = $_POST['delete_images'] ?? [];
    if (!is_array($idsBorrar)) {
        $idsBorrar = [];
    }

    $idsActuales = [];
    foreach ($imagenesActuales as $img) {
        $idsActuales[] = (int) $img['id'];
    }

    $idsBorrarLimpios = [];
    foreach ($idsBorrar as $idBorrar) {
        $imgId = (int) $idBorrar;
        if ($imgId > 0 && in_array($imgId, $idsActuales, true)) {
            $idsBorrarLimpios[] = $imgId;
        }
    }
    $idsBorrarLimpios = array_values(array_unique($idsBorrarLimpios));

    if (!$esEdicion && count($nuevasRutas) === 0) {
        $errores[] = 'Debes subir al menos una imagen para el producto.';
    }

    if ($esEdicion) {
        $imagenesFinales = count($imagenesActuales) - count($idsBorrarLimpios) + count($nuevasRutas);
        if ($imagenesFinales <= 0) {
            $errores[] = 'El producto debe conservar al menos una imagen asociada.';
        }
    }

    if (!$errores) {
        if ($esEdicion) {
            ProductoRepository::update($id, $datos);
            if ($idsBorrarLimpios !== []) {
                ProductoRepository::deleteImages($id, $idsBorrarLimpios);
            }
            if ($nuevasRutas !== []) {
                ProductoRepository::addImages($id, $nuevasRutas);
            }
            ProductoRepository::syncFotoWithImages($id);
            flash_set('ok', 'Producto actualizado correctamente.');
        } else {
            $datos['foto'] = $nuevasRutas[0] ?? null;
            $productoCreadoId = ProductoRepository::create($datos);
            ProductoRepository::addImages($productoCreadoId, $nuevasRutas);
            ProductoRepository::syncFotoWithImages($productoCreadoId);
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

// opciones de disponibilidad
$opcionesDisponible = '';
foreach (['1' => 'Si (hay stock)', '0' => 'No disponible temporalmente'] as $valor => $texto) {
    $selected = $datos['disponible'] === (string) $valor ? ' selected' : '';
    $opcionesDisponible .= '<option value="' . h((string) $valor) . '"' . $selected . '>' . h($texto) . '</option>';
}

// opciones de estado (ofertado o no ofertado)
$opcionesOfertado = '';
foreach (['1' => 'Si, mostrar en carta', '0' => 'No (retirado de carta)'] as $valor => $texto) {
    $selected = $datos['ofertado'] === (string) $valor ? ' selected' : '';
    $opcionesOfertado .= '<option value="' . h((string) $valor) . '"' . $selected . '>' . h($texto) . '</option>';
}

// Si editamos y tiene foto principal, se muestra
$fotoHtml = '';
if ($esEdicion && !empty($productoEditar['foto'])) {
    $fotoHtml = '<p>Imagen principal:<br><img src="' . h(base_url((string) $productoEditar['foto'])) . '" alt="Imagen principal" width="120"></p>';
}

$galeriaHtml = '';
if ($esEdicion) {
    if ($imagenesActuales === []) {
        $galeriaHtml = '<p>No hay imagenes asociadas.</p>';
    } else {
        $items = '';
        foreach ($imagenesActuales as $img) {
            $imgId = (int) $img['id'];
            $ruta = (string) $img['ruta'];
            $items .= '<li>' .
                '<label>' .
                '<input type="checkbox" name="delete_images[]" value="' . $imgId . '"> Eliminar' .
                '</label> ' .
                '<img src="' . h(base_url($ruta)) . '" alt="Imagen producto" width="110">' .
                '</li>';
        }
        $galeriaHtml = '<h3>Imagenes asociadas</h3><ul>' . $items . '</ul>';
    }
}

$titulo = $esEdicion ? 'Editar producto' : 'Crear producto';

$contenido = <<<HTML
<section>
  <h2>{$titulo}</h2>
  {$listaErrores}
  {$fotoHtml}
  <p>Un producto debe tener una o mas imagenes asociadas.</p>
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
      <select id="iva" name="iva">
        <option value="4.00" {iva_4}>4%</option>
        <option value="10.00" {iva_10}>10%</option>
        <option value="21.00" {iva_21}>21%</option>
      </select>
    </p>
    <p>
      <label for="precio_final">Precio final (base + IVA):</label><br>
      <input type="text" id="precio_final" value="" readonly>
      <small>Se actualiza automaticamente al cambiar precio o IVA.</small>
    </p>
    <p>
      <label for="disponible">¿Esta disponible?</label><br>
      <select id="disponible" name="disponible">{$opcionesDisponible}</select>
    </p>
    <p>
      <label for="ofertado">¿Está ofertado?</label><br>
      <select id="ofertado" name="ofertado">{$opcionesOfertado}</select>
    </p>
    <p>
      <label for="fotos">Imagenes (JPG, PNG o WEBP):</label><br>
      <input type="file" id="fotos" name="fotos[]" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" multiple>
    </p>
    {$galeriaHtml}
    <p>
      <button type="submit">Guardar</button>
      <a href="{volver}">Volver</a>
    </p>
  </form>
</section>
<script>
(function () {
  var precioInput = document.getElementById('precio');
  var ivaSelect = document.getElementById('iva');
  var precioFinalInput = document.getElementById('precio_final');

  if (!precioInput || !ivaSelect || !precioFinalInput) {
    return;
  }

  function recalcularPrecioFinal() {
    var base = parseFloat((precioInput.value || '').replace(',', '.'));
    var iva = parseFloat(ivaSelect.value || '0');

    if (isNaN(base) || base < 0 || isNaN(iva)) {
      precioFinalInput.value = '';
      return;
    }

    var total = base * (1 + iva / 100);
    precioFinalInput.value = total.toFixed(2) + ' EUR';
  }

  precioInput.addEventListener('input', recalcularPrecioFinal);
  ivaSelect.addEventListener('change', recalcularPrecioFinal);
  recalcularPrecioFinal();
})();
</script>
HTML;

$contenido = str_replace(
    ['{action}', '{csrf}', '{nombre}', '{descripcion}', '{precio}', '{iva_4}', '{iva_10}', '{iva_21}', '{volver}'],
    [
        h(base_url('producto_form.php' . ($esEdicion ? '?id=' . $id : ''))),
        csrf_field(),
        h($datos['nombre']),
        h($datos['descripcion']),
        h($datos['precio']),
        $datos['iva'] === '4' || $datos['iva'] === '4.00' ? 'selected' : '',
        $datos['iva'] === '10' || $datos['iva'] === '10.00' ? 'selected' : '',
        $datos['iva'] === '21' || $datos['iva'] === '21.00' ? 'selected' : '',
        h(base_url('productos.php'))
    ],
    $contenido
);

render_page($titulo, $contenido);
