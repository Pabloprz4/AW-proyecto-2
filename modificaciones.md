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

- Pendiente.

### Verificación

- Pendiente.

## Ruben

- Pendiente.

### Verificación

- Pendiente.
