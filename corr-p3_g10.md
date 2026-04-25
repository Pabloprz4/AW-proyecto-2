# Feedback de la P3 (Grupo 10) (v009 produccion en la carpeta practica3)

## Número de miembros: 3

## Funcionalidades a implementar en el proyecto

F0- Gestión de usuarios.
F1- Gestión de productos: categorías y productos.
F2- Gestión de pedidos.
F3- Gestión de preparación de pedidos.
F4- Gestión de ofertas.
F5- Gestión de recompensas. (Para grupos de tamaño 5)

## Funcionalidades implementadas en P3 (75% del total)

1. F0. Gestión de usuarios.
2. F1. Gestión de productos: categorías y productos.
3. F2. Gestión de pedidos.
4. F3. Gestión de preparación de pedidos.
5. F4- Gestión de ofertas. (Para grupos de tamaño 5)

## Calificación: 5 / 10

## Memoria (1.5 / 1.5)

- [ ] Los listados de los scripts NO han sido actualizados respecto a los de la P2 (0 puntos)
- [X] Los listados de los scripts han sido actualizados respecto a los de la P2 (0,5 puntos)

- [ ] El diagrama de base de datos NO ha sido actualizado respecto al de la P2 (0 puntos)
- [X] El diagrama de base de datos ha sido actualizado respecto al de la P2 (0.5 puntos)

- [X] La memoria incluye el parte de actividades detallado por cada integrante del grupo de prácticas (0.5 puntos)

Contenido:

- [X] Listado de scripts para las vistas
- [X] Listado de scripts adicionales
- [X] Estructura de la base de datos
- [X] Listado del juego de usuarios de pruebas.
- [X] Parte de actividades.

### Comentarios sobre la memoria

- La memoria es correcta, pero tenéis que mejorar la interfaz gráfica de la aplicación. También tenéis que añadir más variedad de productos, deberíais tener al menos 4 o 5 productos de cada categoría.
- En la implementación hay muchas partes del código que no se entienden bien y son excesivamente complejas.

## HTML (0.5 / 1)

- [ ] Hay errores graves en el HTML (0 puntos)
- [X] Hay bastantes errores en el HTML (0.5 puntos) (index.php, perfil.php, cocina_detalle.php, etc.)
- [ ] Hay algunos errores en el HTML (0.75 puntos) 
- [ ] Se hace un uso adecuado de las etiquetas (1 punto)

## CSS ( 0.25 / 1.5 )

- [ ] No se incluyen CSS o son las mismas que se proporcionan en el ejercicio 2. (0 puntos)
- [X] Estilos mínimos o modificaciones mínimas sobre las CSS proporcionadas en el ejercicio 2 (0.25 puntos)
- [ ] Las CSS añaden nuevas reglas tanto para modificar el aspecto de elementos de las páginas como para organizar la aplicación, pero no se incluyen los comentarios necesarios (0.5 puntos)
- [ ] Las CSS añaden nuevas reglas tanto para modificar el aspecto de elementos de las páginas como para organizar la aplicación (0.75 puntos)
- [ ] Se hace un uso intensivo de CSS, en particular se usan CSS Flexbox y/o CSS Grid para organizar las páginas, pero no se incluyen los comentarios necesarios para entender el diseño de las CSS (1 punto)
- [ ] Se hace un uso intensivo de CSS, en particular se usan CSS Flexbox y/o CSS Grid para organizar las páginas y se incluyen los comentarios necesarios para entender el diseño de las CSS (1.5 puntos)

## Evaluación de funcionalidades y código (2.75 / 6)

- Calificación de la funcionalidad implementada (F3 o F4) (0-3 puntos)
- Puntos por la calidad del código PHP (0-3 puntos)

### F3. Gestión de preparación de pedidos (2.25 / 3)

#### Puebas de la funcionalidad F3

- [ ] Al probar la funcionalidad implementada no funciona o tiene bastantes errores (0 puntos)
- [ ] Al probar la funcionalidad implementada falla en algunos casos (0.75 puntos)
- [X] Al probar la funcionalidad implementada funciona correctamente (1.5 puntos)

#### Grado de madurez de la funcionalidad F3

- [ ] La funcionalidad está completada por debajo del 50% (0 puntos)
- [X] La funcionalidad está completada entre el 50% y el 75% (0.75 puntos)
- [ ] La funcionalidad está completada entre el 75% y el 100% (1.5 puntos)

#### Comentarios de la funcionalidad F3

- La funcionalidad de gestión de preparación de pedidos es correcta. Se pueden visualizar los pedidos, marcar un pedido como preparado y seguir el flujo de preparación de los pedidos.
- Funciona de forma correcta la gestión de los estados de los pedidos. El usuario puede ver el estado de sus pedidos.
- Los paneles del cocinero y camarero no son intuitivos, deberían ser más visuales y fáciles de utilizar.
- El panel del cocinero permite marcar cada plato como preparado por separado y el gerente puede ver el estado de cada plato y también los avatares de los cocineros, pero la interfaz gráfica es muy mejorable.
- Tanto a cocinero como a camarero les aparece el menú Mis pedidos y no les debería aparecer. El cocinero tampoco debería ver el panel del camarero.

### Evaluación de código PHP (0.5 / 3)

- [X] No existe una separación clara entre scripts de vista y scripts de lógica (0 puntos)
- [ ] Existe una separación clara entre scripts de vista y scripts de lógica (0.5 puntos)
- [ ] Existe una separación clara entre scripts de vista y scripts de lógica. Además, la lógica en los scripts de vista es concentrada al comienzo del script y se utilizan funciones de apoyo para simplificar la generación y el mantenimiento del HTML de las páginas. (1 punto)

- [ ] El código contiene bastantes errores comunes o de otro tipo(0 puntos)
- [X] El código contiene algunos errores comunes o de otro tipo (0.5 puntos)
- [ ] El código no contiene errores apreciables (1 punto)

- [ ] Sigue la estructura del ejercicio 2 o la estructura-proyecto. (0.5 puntos)

- [ ] La solución utiliza orientación a objetos al menos para las clases de entidad de la aplicación (0.5 puntos)

## Errores comunes encontrados y errores de despliegue

- [X] No se liberan recursos $rs->free() cuando se lanza una consulta SELECT. (CategoriaRepository.php, PedidoRepository.php, etc.)
- [ ] Las operaciones de base de datos no escapan ($conn->real_escape_string()) los parámetros del usuario.
- [ ] No se utiliza HTTP POST cuando la operación modifica el estado del servidor.
- [X] Los datos que provienen del usuario no se validan adecuadamente.
- [ ] Las clases de entidad (e.g. Usuario, Mensaje, etc.) generan HTML. Las clases de entidad no deben de tener esa responsabilidad.
- [X] Las operaciones de BD devuelven arrays cuyo contenido son directamente las filas que se obtienen de la base de datos y no instancias de la clase correspondiente. (PedidoRepository.php, CategoriaRepository.php, etc.)
- [ ] Uso de style en HTML, en lugar de un fichero CSS.
- [X] La aplicación no está desplegada en la raíz del servidor.
