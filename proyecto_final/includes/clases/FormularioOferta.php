<?php
namespace es\ucm\fdi\aw;

use es\ucm\fdi\aw\Formulario;

class FormularioOferta extends Formulario
{
    private $oferta;

    public function __construct($oferta = null) {
        $this->oferta = $oferta;
        $url = $oferta ? 'oferta_form.php?id=' . $oferta['id'] : 'oferta_form.php';
        parent::__construct('formOferta', ['urlRedireccion' => Aplicacion::getInstance()->resuelve($url)]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $nombre = $datos['nombre'] ?? ($this->oferta['nombre'] ?? '');
        $descripcion = $datos['descripcion'] ?? ($this->oferta['descripcion'] ?? '');
        $descuento = $datos['descuento'] ?? ($this->oferta['descuento'] ?? '');
        $fecha_inicio = $datos['fecha_inicio'] ?? ($this->oferta['fecha_inicio'] ?? '');
        $fecha_fin = $datos['fecha_fin'] ?? ($this->oferta['fecha_fin'] ?? '');
        $productos = $datos['productos'] ?? ($this->oferta['productos'] ?? []);

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'descripcion', 'descuento', 'fecha_inicio', 'fecha_fin'], $this->errores, 'span', array('class' => 'error'));

        // Generar filas de productos
        $productosHtml = '<div id="productos-container">';
        $productosHtml .= $this->generaProductoRows($productos);
        $productosHtml .= '</div>';
        $productosHtml .= '<button type="button" id="add-producto" onclick="addProductoRow()">Añadir Producto</button>';

        // Datos para JavaScript
        $productCount = count($productos);
        $productosDB = \ProductoRepository::all();
        $productosJsonOptions = json_encode(array_map(function($p) {
            return ['id' => (int) $p['id'], 'nombre' => (string) $p['nombre']];
        }, $productosDB));

        $html = <<<EOF
        $htmlErroresGlobales
        <fieldset>
            <legend>Datos de la oferta</legend>
            <div>
                <label for="nombre">Nombre:</label>
                <input id="nombre" type="text" name="nombre" value="$nombre" required />
                {$erroresCampos['nombre']}
            </div>
            <div>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" required>$descripcion</textarea>
                {$erroresCampos['descripcion']}
            </div>
            <div>
                <label for="fecha_inicio">Fecha inicio:</label>
                <input id="fecha_inicio" type="date" name="fecha_inicio" value="$fecha_inicio" required />
                {$erroresCampos['fecha_inicio']}
            </div>
            <div>
                <label for="fecha_fin">Fecha fin:</label>
                <input id="fecha_fin" type="date" name="fecha_fin" value="$fecha_fin" required />
                {$erroresCampos['fecha_fin']}
            </div>
            <div>
                <label>Productos:</label>
                $productosHtml
            </div>
            <div>
                <label for="descuento">Descuento (%):</label>
                <input id="descuento" type="number" step="0.01" name="descuento" value="$descuento" required />
                {$erroresCampos['descuento']}
            </div>
            <div>
                <button type="submit" name="guardar">Guardar</button>
            </div>
        </fieldset>
        
        <script>
        let productIndex = {$productCount};
        
        function addProductoRow() {
            const container = document.getElementById('productos-container');
            const newRow = document.createElement('div');
            newRow.className = 'producto-row';
            newRow.innerHTML = getProductoRowHTML(productIndex);
            container.appendChild(newRow);
            productIndex++;
        }
        
        function removeProductoRow(btn) {
            btn.parentElement.remove();
        }
        
        function getProductoRowHTML(index) {
            const productosOptions = {$productosJsonOptions};
            let options = '<option value="">Seleccionar producto</option>';
            productosOptions.forEach(p => {
                options += '<option value="' + p.id + '">' + p.nombre + '</option>';
            });
            
            return '<select name="productos[' + index + '][producto_id]" required>' + options + '</select>' +
                   '<input type="number" name="productos[' + index + '][cantidad]" value="1" min="1" required />' +
                   '<button type="button" class="btn-remove-producto" onclick="removeProductoRow(this)">Eliminar</button>';
        }
        </script>
        EOF;
        return $html;
    }

    private function generaProductoRows($productos) {
        $html = '';
        if (!empty($productos)) {
            foreach ($productos as $index => $prod) {
                $html .= $this->generaProductoRow($index, $prod);
            }
        }
        return $html;
    }

    private function generaProductoRow($index, $prod) {
        $productosDB = \ProductoRepository::all();
        $options = '<option value="">Seleccionar producto</option>';
        $prodId = isset($prod['producto_id']) ? (int) $prod['producto_id'] : 0;
        $cantidad = isset($prod['cantidad']) ? (int) $prod['cantidad'] : 1;
        
        foreach ($productosDB as $p) {
            $pId = (int) $p['id'];
            $selected = ($pId === $prodId) ? 'selected' : '';
            $options .= "<option value=\"{$pId}\" $selected>" . htmlspecialchars((string) $p['nombre']) . "</option>";
        }
        
        return <<<EOF
        <div class="producto-row">
            <select name="productos[$index][producto_id]" required>$options</select>
            <input type="number" name="productos[$index][cantidad]" value="$cantidad" min="1" required />
            <button type="button" class="btn-remove-producto" onclick="removeProductoRow(this)">Eliminar</button>
        </div>
        EOF;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $nombre = trim($datos['nombre'] ?? '');
        if (!$nombre) {
            $this->errores['nombre'] = 'El nombre es obligatorio.';
        }

        $descripcion = trim($datos['descripcion'] ?? '');
        if (!$descripcion) {
            $this->errores['descripcion'] = 'La descripción es obligatoria.';
        }

        $descuento = (float) ($datos['descuento'] ?? 0);
        if ($descuento < 0 || $descuento > 100) {
            $this->errores['descuento'] = 'El descuento debe estar entre 0 y 100.';
        }

        $fecha_inicio = $datos['fecha_inicio'] ?? '';
        if (!$fecha_inicio) {
            $this->errores['fecha_inicio'] = 'La fecha de inicio es obligatoria.';
        }

        $fecha_fin = $datos['fecha_fin'] ?? '';
        if (!$fecha_fin) {
            $this->errores['fecha_fin'] = 'La fecha de fin es obligatoria.';
        }

        // Procesar productos
        $productos = [];
        $productosInput = $datos['productos'] ?? [];
        if (is_array($productosInput)) {
            foreach ($productosInput as $prod) {
                if (isset($prod['producto_id']) && isset($prod['cantidad'])) {
                    $prodId = (int) $prod['producto_id'];
                    $cantidad = (int) $prod['cantidad'];
                    if ($prodId > 0 && $cantidad > 0) {
                        $productos[] = [
                            'producto_id' => $prodId,
                            'cantidad' => $cantidad,
                        ];
                    }
                }
            }
        }

        if (empty($productos)) {
            $this->errores[] = 'Debe añadir al menos un producto.';
        }

        if (count($this->errores) === 0) {
            $ofertaData = [
                'nombre' => $nombre,
                'descripcion' => $descripcion,
                'descuento' => $descuento,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'activo' => 1,
            ];

            if ($this->oferta) {
                \OfertaRepository::update($this->oferta['id'], $ofertaData);
                \OfertaRepository::setProducts($this->oferta['id'], $productos);
            } else {
                $id = \OfertaRepository::create($ofertaData);
                \OfertaRepository::setProducts($id, $productos);
            }
        }
    }
}