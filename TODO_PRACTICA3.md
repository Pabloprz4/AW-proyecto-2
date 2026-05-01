# To-Do Antes Del Proyecto Final: Corrección De `practica3`

## Resumen

- [ ] Empezar por la base común antes de F4/F5.
- [ ] Revisar primero `plantilla.php`, `sidebarIzq.php` y `estilo.css`.
- [ ] Corregir HTML, mensajes, menús por rol e interfaz base.

## Base común

- [x] Añadir `lang="es"`, meta viewport, título escapado y estructura HTML limpia en la plantilla.
- [x] Unificar mensajes flash para que `flash_set('ok'/'error')` se vea siempre en la plantilla.
- [x] Cambiar el menú lateral a roles exactos: cliente ve pedidos propios, camarero ve panel camarero, cocinero ve cocina, gerente ve gestión.
- [x] Mantener permisos backend coherentes: el cocinero no debe poder entrar al panel camarero.

## CSS real y HTML válido

- [x] Crear clases comunes: botones, tablas, alertas, formularios, badges de estado, acciones inline y grids/cards.
- [x] Quitar `border`, `cellpadding`, `style="display:inline"` y `onclick` de las páginas.
- [x] Dejar las tablas solo donde tengan sentido; para paneles operativos usar tarjetas/listas visuales.
- [x] Añadir responsive básico con Grid/Flexbox y media queries.

## Paneles de cocina y camarero

- [x] Rediseñar `cocina.php` como panel de tarjetas por pedido: estado claro, total, cliente, tipo y acción principal visible.
- [x] Rediseñar `cocina_detalle.php`: cabecera del pedido, progreso de líneas, avatar del cocinero y botones grandes para preparar/finalizar.
- [x] Rediseñar `pedidos_camarero.php`: agrupar por flujo real: cobrar, esperando cocina, preparar entrega, entregar.
- [x] Evitar textos largos explicativos en la pantalla; que el estado y la acción se entiendan visualmente.

## Pedido cliente, carrito y productos

- [x] Separar mejor el carrito de `pedido_nuevo.php`, idealmente con `carrito.php` o una sección muy clara y actualizable.
- [x] Mostrar imágenes en la carta del cliente.
- [x] Ampliar `seedProductos.sql` a 4-5 productos por categoría, con imágenes suficientes.

## Validación y limpieza de código

- [x] Revisar formularios POST: IDs positivos, enums válidos, cantidades, precios, IVA, roles, estados y uploads.
- [x] No confiar en hidden fields como `accion`, `id`, `cantidad` o `estado`; validarlos contra BD/estado actual.
- [x] Quitar comentarios informales y textos sin acentos.
- [x] Aislar o retirar el código legado con `mysqli` si no forma parte del flujo real.

## Arquitectura mínima defendible

- [ ] No reescribir todo a entidades todavía.
- [ ] Separar renderizado repetido con helpers/partials para tablas, cards, formularios y badges.
- [ ] Si queda tiempo, crear entidades simples para `Usuario`, `Producto`, `Pedido` y `Categoría`, o documentar que los repositorios PDO son la capa de datos real.

## Plan de pruebas

- [ ] Ejecutar sintaxis PHP en entorno con PHP instalado: `find practica3 -name "*.php" -print0 | xargs -0 -n1 php -l`.
- [ ] Validar HTML renderizado, no PHP crudo: `index`, `perfil`, `pedido_nuevo`, `pedidos_camarero`, `cocina`, `cocina_detalle`, `pedidos`.
- [ ] Probar menús y accesos con los 4 roles: cliente, camarero, cocinero, gerente.
- [ ] Probar flujo completo: cliente crea pedido, camarero cobra/entrega, cocinero toma/prepara/finaliza, gerente consulta.
- [ ] Revisar responsive en móvil y escritorio.
