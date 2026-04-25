# To-Do Antes Del Proyecto Final: Correccion De `practica3`

## Resumen

- [ ] Empezar por la base comun antes de F4/F5.
- [ ] Revisar primero `plantilla.php`, `sidebarIzq.php` y `estilo.css`.
- [ ] Corregir HTML, mensajes, menus por rol e interfaz base.

## Base comun

- [x] Anadir `lang="es"`, meta viewport, titulo escapado y estructura HTML limpia en la plantilla.
- [x] Unificar mensajes flash para que `flash_set('ok'/'error')` se vea siempre en la plantilla.
- [x] Cambiar el menu lateral a roles exactos: cliente ve pedidos propios, camarero ve panel camarero, cocinero ve cocina, gerente ve gestion.
- [x] Mantener permisos backend coherentes: el cocinero no debe poder entrar al panel camarero.

## CSS real y HTML valido

- [ ] Crear clases comunes: botones, tablas, alertas, formularios, badges de estado, acciones inline y grids/cards.
- [ ] Quitar `border`, `cellpadding`, `style="display:inline"` y `onclick` de las paginas.
- [ ] Dejar las tablas solo donde tengan sentido; para paneles operativos usar tarjetas/listas visuales.
- [ ] Anadir responsive basico con Grid/Flexbox y media queries.

## Paneles de cocina y camarero

- [ ] Redisenar `cocina.php` como panel de tarjetas por pedido: estado claro, total, cliente, tipo y accion principal visible.
- [ ] Redisenar `cocina_detalle.php`: cabecera del pedido, progreso de lineas, avatar del cocinero y botones grandes para preparar/finalizar.
- [ ] Redisenar `pedidos_camarero.php`: agrupar por flujo real: cobrar, esperando cocina, preparar entrega, entregar.
- [ ] Evitar textos largos explicativos en la pantalla; que el estado y la accion se entiendan visualmente.

## Pedido cliente, carrito y productos

- [ ] Separar mejor el carrito de `pedido_nuevo.php`, idealmente con `carrito.php` o una seccion muy clara y actualizable.
- [ ] Mostrar imagenes en la carta del cliente.
- [ ] Ampliar `seedProductos.sql` a 4-5 productos por categoria, con imagenes suficientes.

## Validacion y limpieza de codigo

- [ ] Revisar formularios POST: IDs positivos, enums validos, cantidades, precios, IVA, roles, estados y uploads.
- [ ] No confiar en hidden fields como `accion`, `id`, `cantidad` o `estado`; validarlos contra BD/estado actual.
- [ ] Quitar comentarios informales y textos sin acentos.
- [ ] Aislar o retirar el codigo legado con `mysqli` si no forma parte del flujo real.

## Arquitectura minima defendible

- [ ] No reescribir todo a entidades todavia.
- [ ] Separar renderizado repetido con helpers/partials para tablas, cards, formularios y badges.
- [ ] Si queda tiempo, crear entidades simples para `Usuario`, `Producto`, `Pedido` y `Categoria`, o documentar que los repositorios PDO son la capa de datos real.

## Despliegue

- [ ] Dejar `RUTA_APP` configurable por entorno.
- [ ] Hacer que la app funcione en raiz del VPS o con `APP_BASE_PATH` claramente definido.
- [ ] No tocar ni subir `.teoria_completa.md`.

## Plan de pruebas

- [ ] Ejecutar sintaxis PHP en entorno con PHP instalado: `find practica3 -name "*.php" -print0 | xargs -0 -n1 php -l`.
- [ ] Validar HTML renderizado, no PHP crudo: `index`, `perfil`, `pedido_nuevo`, `pedidos_camarero`, `cocina`, `cocina_detalle`, `pedidos`.
- [ ] Probar menus y accesos con los 4 roles: cliente, camarero, cocinero, gerente.
- [ ] Probar flujo completo: cliente crea pedido, camarero cobra/entrega, cocinero toma/prepara/finaliza, gerente consulta.
- [ ] Revisar responsive en movil y escritorio.

## Supuestos

- [ ] Trabajar primero sobre `practica3`, sin implementar funcionalidades 4 y 5.
- [ ] Priorizar lo que penalizo el profesor: UI, HTML, CSS, roles, paneles y separacion.
- [ ] Dejar entidades/objetos como mejora posterior si bloquea la limpieza visible y funcional.
