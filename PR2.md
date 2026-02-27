Aplicaciones Web / Sistemas Web 2025/2026
Práctica 2: Arquitectura y prototipo
funcional del proyecto
Fecha de entrega: 6 de marzo de 2026
1. Descripción general
La práctica consiste en fijar la arquitectura del proyecto final. Deben definirse las vistas
que van a estar disponibles, la estructura de objetos y la estructura de la base de datos
(tablas y campos).
La entrega de esta práctica debe ser una memoria describiendo la estructura1, y un
prototipo funcional con el 50% de funcionalidades del proyecto final implementadas.
En un grupo de 4 personas, deberían estar completamente implementadas las
funcionalidades 0, 1 y 2. En grupo de 5 personas, deberían implementarse las
funcionalidades 0, 1, 2 y 3.
La memoria no tiene un formato fijo, pero debe incluir las siguientes secciones:
 Listado de scripts para las vistas.
 Listado de scripts adicionales.
 Estructura de la base de datos.
 Prototipo funcional del proyecto.
En la memoria se deberá incluir una sección del VPS, indicando el usuario de VPS (por
ejemplo, vmXXX) y la URL del VPS que dará acceso a la aplicación. También deberá
incluir una sección indicando la URL del repositorio de código fuente empleado (por
ejemplo, GitHub).
A continuación, se describen los contenidos de estas secciones.
El prototipo se debe publicar y debe estar operativo en producción en el VPS (Virtual
Private Server) asignado a cada grupo.
2. Listado de scripts para las vistas
En la práctica anterior se añadieron bocetos de las distintas pantallas que van viendo
los distintos usuarios. En esta práctica hay que implementar todos los scripts PHP que
serán necesarios para todas las vistas de todas las funcionalidades. Se puede construir
1 Partes de esta memoria se podrán aprovechar para la memoria final.
Práctica 2 Página 1
Aplicaciones Web / Sistemas Web 2025/2026
un script para cada pantalla, o un mismo script puede ser responsable de generar
varias pantallas. Todos los scripts PHP de vistas deben estar implementados.
En este capítulo de la memoria se deben enumerar y explicar todos los scripts de vistas
que se van a implementar, separados por funcionalidades, para dar soporte a la
interacción de la web. Para cada script hay que indicar brevemente para qué va a servir
y a qué funcionalidad pertenece.
Si procede, se pueden añadir diagramas que indiquen el flujo de la aplicación de un
script a otro.
3. Listado de scripts adicionales
Como hemos visto en clase, una aplicación puede contar con scripts adicionales que
contengan definiciones de clases, la lógica de la aplicación o que abstraigan el manejo
de la base de datos.
En este capítulo de la memoria se deben explicar todos los scripts adicionales (que no
se correspondan con vistas de la aplicación) que incluirá el proyecto.
4. Estructura de la base de datos
En este capítulo se debe detallar la estructura de la base de datos, indicando las tablas,
campos y relaciones. También se puede incluir un diagrama entidad-relación.
Para cada tabla se debe indicar su propósito, detallar los scripts que acceden a la tabla
(indicando si sólo consultan o también modifican) y explicar los campos que contiene.
Las explicaciones se pueden acompañar de diagramas en formatos adecuados para
representar la estructura (por ejemplo, utilizando diagramas de entidad-relación).
5. Prototipo funcional del proyecto
En base a la documentación generada en los apartados anteriores, se debe implementar
completamente (incluido el acceso a BD):
 El login de la aplicación.
 Al menos el 50% de funcionalidades completas de la aplicación
(funcionalidades 0, 1, y 2 (grupo de tamaño 4); funcionalidades 0, 1, 2 y 3 (grupo
de tamaño 5)).
 Todas las tablas de la base de datos relacionadas con las funcionalidades
implementadas y los scripts PHP responsables de su gestión deben ser
implementadas.
Práctica 2 Página 2
Aplicaciones Web / Sistemas Web 2025/2026
Se recuerda que todavía no es necesario usar formato CSS en las páginas. Incluso si no
se ven “bonitas”, lo importante en este punto es la estructura de la información y la
funcionalidad.
La memoria debe incluir los datos de acceso de los usuarios (nombres de usuario y
contraseñas) necesarios para poder probar las funcionalidades implementadas.
En esta práctica tendréis que utilizar un VPS para poner en producción la aplicación
web y en la memoria tendréis que incluir la URL del VPS y los datos de acceso.
6. Entrega
La práctica deberá entregarse antes de las 23:55 del 6 de marzo, a través del Campus
Virtual. Debe entregarse un único archivo .zip que debe contener:
 Un documento en formato PDF con la memoria tal cual se ha descrito en los
apartados anteriores. El nombre de este documento debe seguir la siguiente
notación “memoria_p2_g<numGrupo>.pdf”, donde “<numGrupo>” de debe
substituir por el número de grupo (el número de grupo se puede consultar en
el campus virtual).
 Un archivo leeme.txt que indique de forma clara qué funcionalidades de la
aplicación están implementadas en esta entrega indicando el grado de
completitud.
 Un fichero .zip con el prototipo funcional de la aplicación, incluyendo la
exportación de la base de datos. El nombre de este fichero debe seguir la
siguiente notación “prototipo_p2_g<numGrupo>.zip”.
La práctica debe ser entregada por uno de los miembros del grupo. El nombre del
archivo a entregar debe seguir la siguiente notación “p2_g<numGrupo>.zip”. Por
ejemplo, el grupo 3 entregará el siguiente fichero “p2_g3.zip”. Una vez entregada la
práctica no se podrán hacer cambios en el VPS producción. El proyecto se podrá
continuar en el VPS beta.
Se obtendrá una penalización si no se siguen las anteriores normas de entrega.
Práctica 2 Página 3