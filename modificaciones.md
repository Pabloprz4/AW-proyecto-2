# Modificaciones del proyecto

## Pablo:

- Anadir `lang="es"`, meta viewport, titulo escapado y estructura HTML limpia en la plantilla.
- Unificar mensajes flash para que `flash_set('ok'/'error')` se vea siempre en la plantilla.
- Cambiar el menu lateral a roles exactos.
- Bloquear al cocinero el acceso backend al panel camarero.
- Crear clases comunes: botones, tablas, alertas, formularios, badges de estado, acciones inline y grids/cards.
- Quitar `border`, `cellpadding`, `style="display:inline"` y `onclick` de las paginas.
- Dejar tablas solo donde tiene sentido; para paneles operativos usar tarjetas/listas visuales.
- Anadir responsive basico con Grid/Flexbox y media queries.
- Redisenar `cocina.php` como panel de tarjetas por pedido con estado, total, cliente, tipo y accion principal visible.
- Redisenar `cocina_detalle.php` con cabecera del pedido, progreso de lineas, avatar del cocinero y botones grandes para preparar/finalizar.
- Redisenar `pedidos_camarero.php` agrupando el flujo real: cobrar, esperando cocina, preparar entrega y entregar.
- Sustituir textos explicativos largos por estados visuales cortos y acciones principales claras en paneles operativos.
- Ampliar `seedProductos.sql` a 4 productos por categoria y enlazar todas las imagenes disponibles.
- Separar el carrito en `carrito.php`, enlazarlo desde la navegacion y dejar `pedido_nuevo.php` centrado en la carta.
- Mostrar imagenes de producto en la carta del cliente con tarjetas por categoria.
- Reforzar formularios POST con IDs positivos, enums validos, cantidades acotadas, precio/IVA/roles/estados y uploads validados.
- Validar `accion`, `id`, `cantidad` y cambios de estado contra carrito, BD y estado actual del pedido antes de ejecutar acciones.
- Limpiar comentarios informales y corregir textos visibles sin acentos.
- Retirar la conexion legacy `mysqli` y dejar la clase antigua de usuario apoyada en repositorios PDO actuales.

### Verificacion

- Pendiente.

## Daniel

- Implementar la funcionalidad 4: gestion de ofertas.
- Crear `OfertaRepository.php` para listar, consultar, crear, actualizar, borrar y cargar ofertas con sus productos asociados.
- Anadir pantallas de gerente `ofertas.php`, `oferta_form.php` y `oferta_borrar.php` para gestionar ofertas actuales y pasadas.
- Crear `FormularioOferta.php` para alta/edicion de ofertas con nombre, descripcion, descuento, fechas y productos/cantidades del pack.
- Anadir JavaScript en el formulario de ofertas para agregar y eliminar productos dinamicamente.
- Crear scripts SQL `schemaOfertas.sql` y `seedOfertas.sql` para estructura y datos de prueba de ofertas.
- Integrar ofertas activas en `carrito.php`, mostrando si son aplicables al carrito actual.
- Permitir aplicar y quitar una oferta desde carrito, validando productos y cantidades requeridas.
- Ajustar calculo del carrito y del pedido para reflejar subtotal, descuento aplicado y total final.
- Actualizar confirmacion/pago del pedido para conservar y mostrar importes derivados de las ofertas.
- Anadir acceso a ofertas desde navegacion/panel principal del gerente.

### Verificacion

- Pendiente.

## Ruben

- Implementar la estructura de F5 en base de datos:
- anadir `usuarios.bistrocoins`.
- crear tabla `recompensas`.
- anadir campos de recompensas/BistroCoins en `pedidos` y `pedido_lineas`.
- Crear y poblar scripts SQL de recompensas (`schemaRecompensas.sql`, `seedRecompensas.sql`) y ajustar seeds de usuarios/pedidos para contemplar BistroCoins y lineas de recompensa.
- Crear `RecompensaRepository.php` e integrarlo en `bootstrap.php`.
- Extender `helpers.php` para soportar recompensas en carrito (`recompensas`, `lineas_recompensa`, `ids_recompensas_invalidas`, `bistrocoins_usados`).
- Extender `PedidoRepository.php` para crear pedidos con lineas normales + lineas recompensa, calcular BistroCoins usados/ganados, reservar/liquidar BistroCoins segun metodo de pago y aplicar deduccion final al pagar con camarero.
- Integrar F5 en flujo cliente:
- `carrito.php` para mostrar/quitar recompensas y total en BistroCoins.
- `pedido_pago.php` para resumen mixto de productos/recompensas.
- `pedido_confirmacion.php` para mostrar BistroCoins usados y ganados.
- `pedido_detalle.php` para mostrar detalle de lineas recompensa y BistroCoins del pedido.
- `mis_pedidos.php` para mostrar columna de BistroCoins por pedido.
- `pedido_nuevo.php` con acceso directo a recompensas.
- `perfil.php` con saldo BistroCoins, reservados y disponibles.
- Crear gestion de recompensas para gerente:
- `recompensas.php` para listar.
- `recompensa_form.php` para crear/editar.
- `recompensa_borrar.php` para borrar.
- Crear vista cliente `recompensas_cliente.php` con resaltado de recompensas canjeables segun saldo, confirmacion antes de canjear, validaciones JS y actualizacion visual del bloque de carrito/recompensas.
- Actualizar navegacion (`index.php`, `sidebarIzq.php`) para enlazar vistas F5 de cliente y gerente.
- Ampliar `css/estilo.css` con componentes visuales de recompensas y animaciones de feedback.

### Verificacion

- Verificacion funcional manual pendiente en entorno local/VPS.
