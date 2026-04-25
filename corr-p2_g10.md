# Feedback de la P2 (Grupo 10)

## Número de miembros: 3

## Funcionalidades a implementar en el proyecto

F0- Gestión de usuarios.
F1- Gestión de productos: categorías y productos.
F2- Gestión de pedidos.
F3- Gestión de preparación de pedidos.
F4- Gestión de ofertas.
F5- Gestión de recompensas. (Para grupos de tamaño 5)

## Funcionalidades implementadas en P2 (50% del total)

1. F0. Gestión de usuarios.
2. F1. Gestión de productos: categorías y productos.
3. F2. Gestión de pedidos.
4. F3. Gestión de preparación de pedidos. (Sólo para grupos de tamaño 5)

## Calificación: 6 / 10

## Memoria (1.25 / 2)

- [X] La memoria tiene al menos las secciones solicitadas (0.5 puntos)

- [X] Los listados de scripts se limitan a las funcionalidades implementadas (0.5 puntos)
- [ ] Los listados de scripts parece que cubren todas las funcionalidades de la aplicación (1 punto)

- [X] El diagrama de base de datos cubre las funcionalidades implementadas (0.25 puntos)
- [ ] El diagrama de base de datos parece cubrir todas las funcionalidades de la aplicación (0.5 puntos)

Contenido:

- [X] Listado de scripts para las vistas
- [X] Listado de scripts adicionales
- [X] Estructura de la base de datos
- [X] Prototipo funcional del proyecto: funcionalidades implementadas y usuarios y passwords para probar la aplicación.

### Comentarios sobre la memoria

- La memoria es correcta, pero no incluye todos los scripts de las funcionalidades del proyecto.

## HTML (0.75 / 1)

- [ ] Hay errores graves en el HTML (0 puntos)
- [ ] Hay bastantes errores en el HTML (0.5 puntos)
- [X] Hay algunos errores en el HTML (0.75 puntos) (pedido_nuevo.php)
- [ ] Se hace un uso adecuado de las etiquetas (1 punto)

### Comentarios

## Evaluación de funcionalidades y código (4 / 7)

- Calificación de las funcionalidades implementadas (0-4 puntos)
- Puntos por la calidad del código PHP (0-3 puntos)
- Regla de calificación: (F1 + F2 + F3 (si se ha implementado)) / 4*3 (o 4*2 si no se ha implementado F3)
- Calificación de funcionalidades: (7/8)*4 = 3.5

### F1. Gestión de productos: categorías y productos (4 / 4)

#### Puebas de la funcionalidad F1

- [ ] Al probar la funcionalidad implementada no funciona o tiene bastantes errores (0 puntos)
- [ ] Al probar la funcionalidad implementada falla en algunos casos (1 punto)
- [X] Al probar la funcionalidad implementada funciona correctamente (2 puntos)

#### Grado de madurez de la funcionalidad F1

- [X] La funcionalidad incluye la visualización, creación, actualización y borrado (2 puntos)
- [ ] La funcionalidad no incluye la actualización o el borrado (1 punto)
- [ ] La funcionalidad no incluye la actualización ni el borrado (0 puntos)

#### Comentarios de la funcionalidad F1 y F0

- El registro de usuarios funciona de forma correcta.
- La edición del perfil de usuario es correcta.
- La funcionalidad de gestión de categorías y productos es correcta. Se han implementado las funcionalidades de edición y eliminación de categorías y productos.

### F2. Gestión de pedidos (3 / 4)

#### Puebas de la funcionalidad F2

- [ ] Al probar la funcionalidad implementada no funciona o tiene bastantes errores (0 puntos)
- [ ] Al probar la funcionalidad implementada falla en algunos casos (1 punto)
- [X] Al probar la funcionalidad implementada funciona correctamente (2 puntos)

#### Grado de madurez de la funcionalidad F2

- [ ] La funcionalidad incluye la visualización, creación, actualización y borrado (2 puntos)
- [X] La funcionalidad no incluye la actualización o el borrado (1 punto)
- [ ] La funcionalidad no incluye la actualización ni el borrado (0 puntos)

#### Comentarios de la funcionalidad F2

- La funcionalidad de gestión de pedidos es correcta, pero el carrito debería estar en una página aparte y poderse actualizar desde esta página. Faltan imágenes de los productos.

### F3. Gestión de preparación de pedidos ( / 4) [No se evalúa en esta práctica]

#### Puebas de la funcionalidad F3

- [ ] Al probar la funcionalidad implementada no funciona o tiene bastantes errores (0 puntos)
- [ ] Al probar la funcionalidad implementada falla en algunos casos (1 punto)
- [ ] Al probar la funcionalidad implementada funciona correctamente (2 puntos)

#### Grado de madurez de la funcionalidad F3

- [ ] La funcionalidad incluye la visualización, creación, actualización y borrado (2 puntos)
- [ ] La funcionalidad no incluye la actualización o el borrado (1 punto)
- [ ] La funcionalidad no incluye la actualización ni el borrado (0 puntos)

#### Comentarios de la funcionalidad F3

- Esta funcionalidad es sólo obligatoria en esta práctica para los grupos de tamaño 5.

### Evaluación de código PHP (0.5 / 3)

- [ ] No existe una separación clara entre scripts de vista y scripts de lógica (0 puntos)
- [X] Existe una separación clara entre scripts de vista y scripts de lógica (0.5 puntos)
- [ ] Existe una separación clara entre scripts de vista y scripts de lógica. Además, la lógica en los scripts de vista es concentrada al comienzo del script y se utilizan funciones de apoyo para simplificar la generación y el mantenimiento del HTML de las páginas. (1 punto)

- [X] El código contiene bastantes errores comunes o de otro tipo(0 puntos)
- [ ] El código contiene algunos errores comunes o de otro tipo (0.5 puntos)
- [ ] El código no contiene errores apreciables (1 punto)

- [ ] Sigue la estructura del ejercicio 2 o la estructura-proyecto. Las clases de entidad se encargan de la gestión de acceso a la base de datos (0.5 puntos)

- [ ] La solución utiliza orientación a objetos al menos para las clases de entidad de la aplicación (0.5 puntos)

## Errores comunes encontrados y errores de despliegue

- [X] No se liberan recursos $rs->free() cuando se lanza una consulta SELECT.
- [ ] Las operaciones de base de datos no escapan ($conn->real_escape_string()) los parámetros del usuario.
- [ ] No se utiliza HTTP POST cuando la operación modifica el estado del servidor.
- [X] Los datos que provienen del usuario no se validan adecuadamente.
- [ ] Las clases de entidad (e.g. Usuario, Mensaje, etc.) generan HTML. Las clases de entidad no deben de tener esa responsabilidad.
- [X] Las operaciones de BD devuelven arrays cuyo contenido son directamente las filas que se obtienen de la base de datos y no instancias de la clase correspondiente.
- [ ] Uso de style en HTML, en lugar de un fichero JavaScript.
- [X] La aplicación no está desplegada en la raíz del servidor.
