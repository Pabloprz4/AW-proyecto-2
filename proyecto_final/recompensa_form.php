<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$gestor = require_role('gerente');

$id = isset($_GET['id']) ? get_positive_int('id') : null;
if (isset($_GET['id']) && $id === null) {
    flash_set('error', 'ID de recompensa invalido.');
    redirect_to('recompensas.php');
}

$id = $id ?? 0;
$esEdicion = $id > 0;
$recompensaEditar = $esEdicion ? RecompensaRepository::findById($id) : null;

if ($esEdicion && !$recompensaEditar) {
    flash_set('error', 'Recompensa no encontrada.');
    redirect_to('recompensas.php');
}

$productos = ProductoRepository::all(true);
if ($productos === []) {
    flash_set('error', 'No hay productos disponibles para crear recompensas.');
    redirect_to('productos.php');
}

$productosPorId = [];
foreach ($productos as $producto) {
    $productosPorId[(int) $producto['id']] = $producto;
}

$errores = [];
$datos = [
    'producto_id' => (string) ($recompensaEditar['producto_id'] ?? ''),
    'bistrocoins' => (string) ($recompensaEditar['bistrocoins'] ?? '10'),
    'activo' => (string) ($recompensaEditar['activo'] ?? '1'),
];

if (is_post()) {
    $datos['producto_id'] = post_trimmed_string('producto_id');
    $datos['bistrocoins'] = post_trimmed_string('bistrocoins');
    $datos['activo'] = post_enum('activo', ['0', '1']) ?? '';

    if (!verify_csrf()) {
        $errores[] = 'Token CSRF invalido.';
    }

    $productoId = request_positive_int($datos, 'producto_id');
    if ($productoId === null || !isset($productosPorId[$productoId])) {
        $errores[] = 'Debes seleccionar un producto valido.';
    }

    if (!preg_match('/^[1-9][0-9]{0,5}$/', $datos['bistrocoins'])) {
        $errores[] = 'Las BistroCoins deben ser un entero positivo (max 6 digitos).';
    }
    $bistrocoins = (int) $datos['bistrocoins'];
    if ($bistrocoins <= 0) {
        $errores[] = 'Las BistroCoins deben ser mayores que cero.';
    }

    if (!in_array($datos['activo'], ['0', '1'], true)) {
        $errores[] = 'El estado de la recompensa no es valido.';
    }

    if (!$errores && $productoId !== null) {
        $existente = RecompensaRepository::findByProductId($productoId);
        if ($existente && (!$esEdicion || (int) $existente['id'] !== $id)) {
            $errores[] = 'Ese producto ya tiene una recompensa asociada.';
        }
    }

    if (!$errores) {
        try {
            $payload = [
                'producto_id' => $productoId,
                'bistrocoins' => $bistrocoins,
                'activo' => (int) $datos['activo'],
            ];

            if ($esEdicion) {
                RecompensaRepository::update($id, $payload);
                flash_set('ok', 'Recompensa actualizada correctamente.');
            } else {
                RecompensaRepository::create($payload);
                flash_set('ok', 'Recompensa creada correctamente.');
            }

            redirect_to('recompensas.php');
        } catch (Throwable $e) {
            $errores[] = 'No se pudo guardar la recompensa.';
        }
    }
}

$listaErrores = '';
if ($errores) {
    $items = '';
    foreach ($errores as $error) {
        $items .= '<li>' . h($error) . '</li>';
    }
    $listaErrores = '<div class="alert alert-error"><ul>' . $items . '</ul></div>';
}

$opcionesProducto = '<option value="">Selecciona un producto</option>';
foreach ($productos as $producto) {
    $productoId = (int) $producto['id'];
    $selected = $datos['producto_id'] === (string) $productoId ? ' selected' : '';
    $precioFinal = round((float) $producto['precio'] * (1 + ((float) $producto['iva'] / 100)), 2);
    $opcionesProducto .= '<option value="' . $productoId . '" data-precio-final="' . h((string) $precioFinal) . '" data-ofertado="' . (int) $producto['ofertado'] . '" data-disponible="' . (int) $producto['disponible'] . '"' . $selected . '>' .
        h((string) $producto['nombre']) .
        '</option>';
}

$titulo = $esEdicion ? 'Editar recompensa' : 'Crear recompensa';

$contenido = <<<HTML
<section>
  <h2>{$titulo}</h2>
  {$listaErrores}
  <form id="recompensa-form" method="post" action="{action}">
    {csrf}
    <p>
      <label for="producto_id">Producto de la carta:</label><br>
      <select id="producto_id" name="producto_id" required>
        {$opcionesProducto}
      </select>
    </p>
    <p id="reward-product-info" class="reward-help"></p>
    <p>
      <label for="bistrocoins">BistroCoins necesarias:</label><br>
      <input type="number" id="bistrocoins" name="bistrocoins" min="1" max="999999" step="1" value="{bistrocoins}" required>
    </p>
    <p>
      <label for="activo">Estado:</label><br>
      <select id="activo" name="activo">
        <option value="1" {activo_si}>Activa</option>
        <option value="0" {activo_no}>Inactiva</option>
      </select>
    </p>
    <p id="reward-form-error" class="alert alert-error reward-form-error" hidden></p>
    <p>
      <button class="btn btn-primary" type="submit">Guardar</button>
      <a class="btn" href="{volver}">Volver</a>
    </p>
  </form>
</section>
<script>
(function () {
  var form = document.getElementById('recompensa-form');
  var productoSelect = document.getElementById('producto_id');
  var coinsInput = document.getElementById('bistrocoins');
  var info = document.getElementById('reward-product-info');
  var errorBox = document.getElementById('reward-form-error');

  if (!form || !productoSelect || !coinsInput || !info || !errorBox) {
    return;
  }

  function setError(message) {
    if (!message) {
      errorBox.hidden = true;
      errorBox.textContent = '';
      return;
    }
    errorBox.hidden = false;
    errorBox.textContent = message;
  }

  function updateProductInfo() {
    var option = productoSelect.options[productoSelect.selectedIndex];
    if (!option || !option.value) {
      info.textContent = 'Selecciona un producto para ver su estado.';
      info.classList.remove('reward-help-ok');
      info.classList.add('reward-help-warn');
      return;
    }

    var precio = option.getAttribute('data-precio-final') || '0.00';
    var ofertado = option.getAttribute('data-ofertado') === '1';
    var disponible = option.getAttribute('data-disponible') === '1';
    var estado = (ofertado && disponible) ? 'Producto disponible en carta' : 'Producto no disponible en carta';

    info.textContent = 'Precio final actual: ' + precio + ' EUR. ' + estado + '.';
    info.classList.toggle('reward-help-ok', ofertado && disponible);
    info.classList.toggle('reward-help-warn', !(ofertado && disponible));
  }

  function validate() {
    var productoId = productoSelect.value;
    var coinsRaw = coinsInput.value.trim();
    var coins = Number(coinsRaw);

    if (!productoId) {
      setError('Debes seleccionar un producto.');
      return false;
    }

    if (!/^[1-9][0-9]*$/.test(coinsRaw) || !Number.isInteger(coins) || coins <= 0) {
      setError('Las BistroCoins deben ser un entero positivo.');
      return false;
    }

    if (coins > 999999) {
      setError('El valor maximo permitido es 999999 BistroCoins.');
      return false;
    }

    setError('');
    return true;
  }

  productoSelect.addEventListener('change', function () {
    updateProductInfo();
    validate();
  });

  coinsInput.addEventListener('input', validate);

  form.addEventListener('submit', function (event) {
    if (!validate()) {
      event.preventDefault();
    }
  });

  updateProductInfo();
})();
</script>
HTML;

$contenido = str_replace(
    ['{action}', '{csrf}', '{bistrocoins}', '{activo_si}', '{activo_no}', '{volver}'],
    [
        h(base_url('recompensa_form.php' . ($esEdicion ? '?id=' . $id : ''))),
        csrf_field(),
        h($datos['bistrocoins']),
        $datos['activo'] === '1' ? 'selected' : '',
        $datos['activo'] === '0' ? 'selected' : '',
        h(base_url('recompensas.php')),
    ],
    $contenido
);

render_page($titulo, $contenido);
