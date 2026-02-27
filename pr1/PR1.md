Aplicaciones Web 2025/2026
Práctica 1: Creación de una página Web
Fecha de entrega: 13 de febrero de 2026
1- Descripción general
La práctica consiste en crear una página web, formada por varios documentos,
incluyendo imágenes, tablas y formularios. La web no hará uso de ejecución de código
en el lado del servidor ni en el lado del cliente.
El contenido de la página deberá consistir en una descripción detallada del proyecto a
realizar para la evaluación, siguiendo las secciones y restricciones indicadas en las
próximas secciones.
2- Estructura de la página
La página consiste en una presentación detallada del proyecto a desarrollar. Como
mínimo, deberá incluir las siguientes secciones:
 index.html: presentación inicial del proyecto.
 detalles.html: descripción extendida.
 bocetos.html: bocetos de la página web dibujados en papel y explicaciones.
 miembros.html: datos de los miembros del grupo.
 planificacion.html: plan detallado para el desarrollo del proyecto.
 contacto.html: formulario de contacto para pedir más información.
A continuación, se dan más detalles sobre el contenido de cada una de estas páginas.
2.1- Página index.html
Esta página contiene el título del proyecto, una descripción breve del mismo (menos
de 150 palabras) y un logotipo o imagen de presentación en grande. En el proyecto os
hemos dado un logotipo, pero podéis crear uno vosotros más personalizado.
2.2- Página detalles.html
En este documento, de mayor longitud, se describe el proyecto y las funcionalidades
ofrecidas por la web con mucho más detalle. Orientativamente, se proponen las
siguientes subsecciones dentro de esta página, aunque se aceptará cualquier forma de
estructurar la información:
Práctica 1 Página 1
Aplicaciones Web 2025/2026
 Introducción: qué hace la aplicación, para qué sirve, etc. Es una versión
extendida de la descripción breve.
 Tipos de usuarios: explica los tipos de usuarios que se contemplan y las “cosas”
que podrán hacer.
 Funcionalidades: enumerar cada una de las funcionalidades con una breve
explicación e indicar qué tipos de usuarios las realizan.
2.3- Página bocetos.html
En este documento se describe la apariencia de la web, aportando bocetos en papel
para las distintas páginas intermedias que van a ver los distintos usuarios.
Deben ser bocetos de baja tecnología, se recomienda dibujarlos en papel y después
escanearlos o fotografiarlos. Alternativamente, se pueden usar herramientas de
wireframing, como Balsamiq1, Lucidchart2, Figma3, Sketch4
, o Wireframe.cc5
, Penpot6 y
exportar los resultados como imágenes.
Debe incluir también una descripción textual de lo que se puede ver en cada una de
las pantallas y describir cómo sería la navegación entre pantallas. En particular, para
las funcionalidades principales de la página, el documento debe aclarar cómo se harían
paso a paso desde la página principal. Es importante que en cada boceto esté indicado
a qué funcionalidad corresponde.
Es aceptable y razonable que estos diseños evolucionen y cambien más adelante.
2.4- Página miembros.html
En esta página se deben incluir los detalles de todos los miembros del grupo. Para cada
miembro se debe incluir el nombre completo, una dirección de correo de contacto (por
ejemplo, la misma que tengáis en el campus), una foto y un breve párrafo describiendo
vuestras aficiones o intereses (pueden ser inventados). Menos de 50 palabras por
miembro.
Al principio de esta página debe aparecer un listado con los nombres de todos los
miembros, y cada nombre debe ser un enlace al apartado (dentro de la misma página)
donde aparecen los datos detallados de la persona correspondiente.
1 https://balsamiq.com/
2 https://www.lucidchart.com/pages/examples/wireframe_software
3 https://www.figma.com/templates/wireframe-kits/
4 https://www.sketch.com
5 https://wireframe.cc/
6 https://penpot.app/
Práctica 1 Página 2
Aplicaciones Web 2025/2026
2.5- Página planificación.html
En esta página se debe describir la planificación del proyecto (tareas a realizar, ideas
sobre cómo repartir el trabajo, plazos, etc.). El formato es libre, pero debe terminar con
una tabla que resuma los hitos y sus fechas de terminación. Adicionalmente se puede
incluir un diagrama de Gantt.
Las fechas de los distintos hitos del proyecto están detalladas en el enunciado general
del proyecto final.
2.6- Página contacto.html
Es una página que contiene un formulario de contacto. El formulario se implementará
para que la información se envíe en forma de correo electrónico, usando la función
mailto.
El formulario debe pedir al usuario los siguientes datos:
 Nombre
 Dirección de email de contacto
 Motivo de la consulta, con radio buttons:
o Evaluación
o Sugerencias
o Críticas
 Un checkbox para marcar junto a la frase: “Marque esta casilla para verificar que
ha leído nuestros términos y condiciones del servicio”7
.
 Un cuadro de texto para que el usuario escriba la consulta.
3- Requisitos para la implementación
Aunque el formato es libre, la actividad se debe realizar siguiendo una serie de
criterios y restricciones a la hora de trabajar.
3.1- Escritura del código
Los documentos se pueden escribir usando cualquier editor de textos, algunos de ellos
especializados en la edición de archivos orientados a la web. Existen editores visuales
también, aunque no los recomendamos para esta primera práctica para que os
familiaricéis mejor con la sintaxis de HTML.
7 No hace falta que redactéis unos términos y condiciones del servicio, nadie los lee nunca.
Práctica 1 Página 3
Aplicaciones Web 2025/2026
Se valorará la limpieza del código (indentación, algunos comentarios, estructura
uniforme, etc.). También se valorará que se utilicen las etiquetas HTML correctas y que
cada fichero HTML pase correctamente por el validador HTML sin errores ni warnings.
3.2- Imágenes
Todas las imágenes de la web deberán estar contenidas en una carpeta img, ubicada al
mismo nivel que los archivos HTML. Esta carpeta se debe entregar también junto a la
página web.
Todas las imágenes deben estar en formato PNG o JPG.
3.3- Etiquetas, formato y estilos
Se plantean varios requisitos y restricciones:
 Todas las páginas deben ser documentos HTML5 válidos. Podéis probar el
validador que hemos visto en clase.
 No se debe usar ningún tipo de etiqueta de formato en desuso (e.g. <b>, <i>,
<center>).
 No es necesario aplicar estilos a la página, los estilos por defecto son aceptables,
aunque queden “feos”
.
 En caso de querer añadir elementos de estilo, deberán añadirse empleando CSS
(no se puntuará extra por ello, pero sí se penalizará el mal uso de etiquetas de
formato).
3.4- Navegación
Todas las páginas deben tener al principio de la página una lista de enlaces a todas las
páginas de la web. Por el momento, podéis copiar y pegar la lista de enlaces de una
página a otra, aunque pronto veremos cómo evitar tener que hacer esto.
4- Entrega
La práctica deberá entregarse antes de las 23:55 del viernes 13 de febrero, a través del
Campus Virtual. Debe entregarse un único archivo comprimido con todos los
elementos de la web (incluyendo imágenes). Es suficiente con que la entregue uno de
los miembros del equipo de trabajo.
El nombre del archivo comprimido deberá seguir la siguiente notación:
p1_g<numGrupo>.zip. Donde <numGrupo> debe ser substituido por el número de
grupo. Por ejemplo, el grupo 1 entregará un fichero con nombre p1_g1.zip.
Práctica 1 Página 4
