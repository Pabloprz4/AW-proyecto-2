# Modificaciones del proyecto

## Pablo:

- Añadir lang="es", meta viewport, título escapado y estructura HTML limpia en la plantilla
- Unificar mensajes flash para que `flash_set('ok'/'error')` se vea siempre en la plantilla
- Cambiar el menú lateral a roles exactos
- Bloquear al cocinero el acceso backend al panel camarero
- Crear clases comunes: botones, tablas, alertas, formularios, badges de estado, acciones inline y grids/cards.
- Quitar `border`, `cellpadding`, `style="display:inline"` y `onclick` de las páginas.
- Dejar las tablas solo donde tengan sentido; para paneles operativos usar tarjetas/listas visuales.
- Añadir responsive básico con Grid/Flexbox y media queries.
- Rediseñar `cocina.php` como panel de tarjetas por pedido con estado, total, cliente, tipo y acción principal visible.
- Rediseñar `cocina_detalle.php` con cabecera del pedido, progreso de líneas, avatar del cocinero y botones grandes para preparar/finalizar.
- Rediseñar `pedidos_camarero.php` agrupando el flujo real: cobrar, esperando cocina, preparar entrega y entregar.
- Sustituir textos explicativos largos por estados visuales cortos y acciones principales claras en los paneles operativos.
- Ampliar `seedProductos.sql` a 4 productos por categoría y enlazar todas las imágenes disponibles.
- Separar el carrito en `carrito.php`, enlazarlo desde la navegación y dejar `pedido_nuevo.php` centrado en la carta.
- Mostrar imágenes de producto en la carta del cliente con tarjetas por categoría.
- Reforzar formularios POST con IDs positivos, enums válidos, cantidades acotadas, precio/IVA/roles/estados y uploads validados.
- Validar `accion`, `id`, `cantidad` y cambios de estado contra el carrito, la BD y el estado actual del pedido antes de ejecutar acciones.
- Limpiar comentarios informales y corregir textos visibles sin acentos.
- Retirar la conexión legacy `mysqli` y dejar la clase antigua de usuario apoyada en los repositorios PDO actuales.

### Verificación

- Pendiente.

## Daniel

- Implementar la funcionalidad 4: gestión de ofertas.
- Crear el repositorio `OfertaRepository.php` para listar, consultar, crear, actualizar, borrar y cargar ofertas con sus productos asociados.
- Añadir las pantallas de gerente `ofertas.php`, `oferta_form.php` y `oferta_borrar.php` para gestionar ofertas actuales y pasadas.
- Crear `FormularioOferta.php` para dar de alta y editar ofertas con nombre, descripción, descuento, fechas y productos/cantidades del pack.
- Añadir JavaScript en el formulario de ofertas para agregar y eliminar productos dinámicamente.
- Crear los scripts SQL `schemaOfertas.sql` y `seedOfertas.sql` para la estructura y datos de prueba de ofertas.
- Integrar las ofertas activas en `carrito.php`, mostrando si son aplicables al carrito actual.
- Permitir aplicar y quitar una oferta desde el carrito, validando productos y cantidades requeridas.
- Ajustar el cálculo del carrito y del pedido para reflejar subtotal, descuento aplicado y total final.
- Actualizar confirmación/pago del pedido para conservar y mostrar los importes derivados de las ofertas.
- Añadir el acceso a ofertas desde la navegación/panel principal del gerente.

### Verificación

- Pendiente.

## Ruben

- Pendiente.

### Verificación

- Pendiente.
