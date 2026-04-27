0
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Pablo Moreno Ger, con modificaciones de
Raquel Hervás Ballesteros e Iván Martínez Ortiz.
Vamos a construir una micro-web, usando:
 HTML.
 Hojas de estilo (CSS).
 Código en el lado del servidor (PHP).
 Código en el lado del cliente (JavaScript).
AW: Mi primera Aplicación Web 2
AW: Mi primera Aplicación Web 3
Los documentos de la web se escriben en HTML
 HTML es un lenguaje de marcas.
 El texto se mezcla con “marcas” que indican cómo debe
mostrarse dentro del navegador.
AW: Mi primera Aplicación Web 4
AW: Mi primera Aplicación Web 5
index.html
<h1>¡Hola Mundo!</h1> Encabezado de primer nivel
<p>Esto es un párrafo escrito en HTML. También podemos
poner <em>parte del texto</em> con énfasis.</p> Párrafo
<h2>Se pueden hacer titulos de menor tamaño</h2>
<h3>Y más pequeños.</h3> Otros encabezados
<p>También se pueden poner imágenes (a menudo, de
gatos):</p>
<img src="gato.png"> Imagen para añadir al documento
<p>También podemos poner enlaces:
<a href="masEjemplos.html">Ver más ejemplos</a> Enlace
</p>
AW: Mi primera Aplicación Web 6
<!DOCTYPE html>Esto indica que es un documento HTML5
<html> Inicio del documento HTML
index.html
<head>Cabecera
<title>Mi primera web</title> Título (sale en la pestaña)
</head>Fin de la cabecera
<body>Empieza el cuerpo
<h1>¡Hola Mundo!</h1>
(…)
</body>Fin del cuerpo
</html> Fin del documento
AW: Mi primera Aplicación Web 7
Normalmente, en un servidor web.
Cliente
Servidor
Navegador
Petición HTTP
Servicio HTTP
Respuesta:
Texto, archivo, imagen…
HTML Imágenes
AW: Mi primera Aplicación Web 8
Cómo conectarse a un servidor web vía consola
 Putty: cliente SSH, Telnet y TCP
 Petición HTTP
AW: Mi primera Aplicación Web 9
AW: Mi primera Aplicación Web 10
Limitaciones del protocolo HTTP
 Comunicación unidireccional: El cliente solicita contenidos, el
servidor los devuelve, y el cliente los muestra.
 El contenido es estático.
 Cada comunicación se procesa sin tener en cuenta otras
posibles peticiones anteriores.
¡Así no podríamos construir
Amazon o Gmail!
AW: Mi primera Aplicación Web
11
El navegador solamente sabe:
 Solicitar un documento (o una imagen) a un servidor.
 Enviar datos básicos (texto) a un servidor.
 Interpretar y mostrar documentos HTML.
No podemos modificar el navegador, pero sí el servidor
 Bueno, un poco sí…
 Podemos modificar el comportamiento del navegador usando JavaScript y otras tecnologías de ejecución en el cliente.
 La funcionalidad está limitada por motivos de seguridad.
Solución
 El navegador solicita un documento aportando una serie de datos.
 El servidor interpreta los datos, actúa en consecuencia y genera al vuelo un
documento HTML para enviárselo al navegador.
 El navegador interpreta y muestra el documento recibido sin darse cuenta de que
no era un fichero estático.
AW: Mi primera Aplicación Web
12
Cliente
Navegador
Petición HTTP
Respuesta:
Código HTML
generado dinámicamente
AW: Mi primera Aplicación Web Servidor
Servicio HTTP
Procesador
(e.g. PHP)
Archivos
estáticos
Base de datos
13
AW: Mi primera Aplicación Web 14
En lugar de tener un documento HTML estático, tengo un
programa que “escribe” código HTML y se lo envía al cliente:
<?php
echo '<!DOCTYPE html>';“echo” envía HTML al navegador
echo '<html><head><title>PHP</title></head><body>;
echo '<h1>¿No te ha quedado claro el saludo?</h1>';
for ($i = 0; $i < 50; $i++) {Escribir 50 veces
echo '<p>' . $i . ' - ¡Hola Mundo!</p>';
}
echo '</body></html>';
?>
AW: Mi primera Aplicación Web 15
Esto es poco práctico…
 La mayor parte del código será casi siempre HTML.
 Las partes programadas suelen ser trozos dentro de una plantilla HTML.
¿Y si le damos la vuelta?
AW: Mi primera Aplicación Web 16
saludo.php
En realidad es más cómodo escribirlo al revés:
<html>
<head>
<title>PHP</title>
</head>
<body>
<h1>¿No te ha quedado claro el saludo?</h1>
<?php
for ($i = 0; $i < 50; $i++) {
echo '<p>' . $i . ' - ¡Hola Mundo!</p>';
}
?>
¡Pero este código hace siempre lo mismo!
</body>
</html>
AW: Mi primera Aplicación Web 17
saludos.php
Paso de parámetros en la URL:
http://servidor/saludos.php?num=100
<?php
$vueltas = $_GET['num'];
for ($i = 0; $i < $vueltas; $i++) {
echo '<p>' . $i . ' - ¡Hola Mundo!</p>';
}
?>
Cosas que veremos más adelante:
• Las variables llevan $ delante.
• Las variables no se declaran.
• $_GET['X'] recoge el dato enviado por el cliente.
AW: Mi primera Aplicación Web 18
Demo práctica:
 Pruebas con el navegador
AW: Mi primera Aplicación Web 19
¡Y veremos mucho más!
 Base de datos con los datos de la aplicación.
 Gestión de sesiones para asociar peticiones.
 Otras tecnologías de servidor
 PHP (que puede ser orientado a objetos).
 Java en el servidor.
AW: Mi primera Aplicación Web 20
AW: Mi primera Aplicación Web 21
Al principio, HTML tenía etiquetas para indicar el formato
de presentación…
<body bgcolor="#000000" text="green">
<center>
<h1>¡Hola Mundo!</h1>
</center>
<p align="justify">Esto es un párrafo escrito en HTML
con alineación justificada.</p>
…pero esto no era buena idea y ya no se usa.
AW: Mi primera Aplicación Web 22
Idea básica:
 Anotar la estructura del contenido en HTML, indicar los
formatos en CSS.
¿Dónde van los formatos CSS? Tres opciones:
 En una sección especial del archivo HTML.
 En un archivo aparte (.css).
 Opción preferida.
 Mezclados con el contenido.
 Esto es por inercia, y obviamente va contra el propósito de
usar CSS. Mejor no mezclarlo.
AW: Mi primera Aplicación Web 23
holaConEstilos.html
AW: Mi primera Aplicación Web 24
estilos.css
body {
background-color: #559955;Color de fondo
color: #ffffff; Color de la fuente
}
p {
}
h3 {
}
em {
}
font-family: sans-serif;Tipo de fuente (también vale “Arial”)
font-size: 54px; Agrandamos el tamaño de H3
font-weight: bold; Ponemos el énfasis en negrita
AW: Mi primera Aplicación Web 25
<!DOCTYPE html>
<html>
<head>
<title>Mi primera web</title>
<link href="estilos.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>¡Hola Mundo!</h1>
<p>Esto es un párrafo escrito en HTML.</p>
<p>Esto es otro párrafo, con <em>parte del texto</em>
enfatizado.</p>
<h2>También se pueden hacer títulos menores</h2>
<h3>Y más pequeños (o no...).</h3>
</body>
</html>
AW: Mi primera Aplicación Web 26
holaConEstilos.html
AW: Mi primera Aplicación Web 27
AW: Mi primera Aplicación Web 28
AW: Mi primera Aplicación Web 29
AW: Mi primera Aplicación Web 30
AW: Mi primera Aplicación Web 31
Por ahora tenemos:
 Lenguaje de marcas para estructurar el contenido.
 Lenguaje de estilos para mostrarlo de una forma u otra.
 Posibilidad de ejecutar código en el servidor para generar el
contenido.
Nos falta:
 Ejecutar código en el cliente (navegador) que pueda modificar el
contenido directamente.
AW: Mi primera Aplicación Web 32
JavaScript es un lenguaje que se ejecuta en un intérprete
incrustado en el navegador.
 Todos los navegadores soportan JavaScript…
 …pero no todos lo soportan igual.
Idea básica:
 Se puede incrustar código (incluso funciones y objetos) en el
documento HTML.
 Se definen triggers que activan las funciones para responder
ante ciertos eventos
 Página cargada.
 Activación de algún elemento de la página.
AW: Mi primera Aplicación Web 33
Partimos de una página sencilla:
<body>
<h1>Ejecución de código en el navegador</h1>
<button type="button">Incrementar</button> Un botón…
<p id="contador">0</p> La ID me ayudará a localizar el párrafo
</body>
AW: Mi primera Aplicación Web 34
Partimos de una página sencilla:
AW: Mi primera Aplicación Web 35
Escribimos una función para incrementar el contador:
<script>
var i=0;
function incrementar(){
i++;
document.getElementById('contador').innerHTML= i;
}
</script>
AW: Mi primera Aplicación Web 36
Vinculamos la función con el botón
<body>
<h1>Ejecución de código en el navegador</h1>
<button type="button" onclick="incrementar()">
Incrementar
</button>
<p id="contador">0</p>
</body>
AW: Mi primera Aplicación Web 37
contador.html
Todo junto:
<!DOCTYPE html>
<html>
<head>
<title>Javascript</title>
<script>
var i=0;
function incrementar() {
i++;
document.getElementById('contador').innerHTML= i;
}
</script>
En realidad esto no lo
haríamos así…
</head>
<body>
<h1>Ejecución de código en el navegador</h1>
<button type="button" onclick="incrementar()">
Incrementar
</button >
<p id="contador">0</p>
</body></html>
AW: Mi primera Aplicación Web 38
Igual que deben separarse los estilos del texto, también
debe separarse el código
 Un HTML con el texto.
 Una CSS con los estilos.
 Un JS con el código.
 Para que el HTML sepa dónde están las funciones definidas en
JavaScript hay que incluir en el documento HTML una
referencia a un archivo externo con extensión .js.
<script type="text/javascript" src="./js/miJavascript.js"></script>
AW: Mi primera Aplicación Web 39
AW: Mi primera Aplicación Web 40
En resumen
 Usamos documentos HTML para escribir el contenido.
 Usamos hojas de estilos CSS para describir el formato.
 Podemos ejecutar código en el servidor antes de servir la página
 El código HTML puede ser estático o dinámico.
 Podemos ejecutar código en el propio navegador.
 JavaScript.
AW: Mi primera Aplicación Web 41
Licencia Creative Commons
 Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
 Material elaborado por Pablo Moreno Ger, con
modificaciones de Raquel Hervás Ballesteros e Iván Martínez
Ortiz.
AW: Mi primera Aplicación Web 42




1
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Juan Pavón Mestras, con modificaciones de
Material elaborado por Juan Pavón, con modificaciones de Pablo
Pablo Moreno Ger, Manuel Freire Morán y Raquel Hervás Ballesteros
Moreno, Manuel Freire, Raquel Hervás y Javier Bravo
• Modelo de capas.
• Protocolos de Internet.
• Internet: DNS.
• Arquitectura cliente-servidor.
• URL.
• HTTP.
• Cookies.
• Tecnologías de la Web.
• Internacionalización
• Accesibilidad
2
AW: Internet, protocolos y tecnologías Web
La Web
está montada
sobre internet
SMTP FTP Telnet HTTP ...
TCP/UDP
IP
Protocolos de
Acceso a Red
3
AW: Internet, protocolos y tecnologías Web
Ofrece la capacidad de acceder a cualquier red física
• Brinda los recursos para transmitir datos a través de la red.
• Se encarga el sistema operativo y lo permiten los drivers de hardware
(como el de la tarjeta de red).
Múltiples tecnologías
• Ethernet
En esta asignatura no nos
ocuparemos de cuestiones
• DSL (Digital subscribe line)
relacionadas con la capa física.
• HFC (Hybrid Fiber Coaxial)
• FTTH (Fiber To The Home)
• Wi-Fi (wireless fidelity)
• WiMAX (Worldwide Interoperability for Microwave Access)
4
AW: Internet, protocolos y tecnologías Web
• Tiene como propósito seleccionar la mejor ruta para enviar
paquetes por la red (sea del tipo que sea).
• Encaminamiento en una red IP.
Paquete IP
Paquete IP
a.b.c.d w.x.y.z
Paquete IP
5
AW: Internet, protocolos y tecnologías Web
IPv4: 32 bits con 8 bits en cada una de sus 4 partes
• 4 números enteros (bytes) entre 0 y 255 separados por ‘.’
• xxx.xxx.xxx.xxx
• Eso da 28 * 28 * 28 * 28 = 4.294.967.296
• Muchas son privadas o están reservadas.
• Resultan escasas.
IPv6: 128 bits con 16 bits en cada una de sus 8 partes
• 8 números enteros entre 0 y 65535 separados por ‘.’
• Eso da 216 * … * 216 =
340.282.366.920.938.000.000.000.000.000.000.000.000
6
AW: Internet, protocolos y tecnologías Web
• Establece una conexión extremo a extremo entre el
punto de origen y destino.
• También asegura que llegan todos los paquetes
correctamente y los re-ensambla.
Paquete TCP
Conexión extremo a extremo
Servidor Web
Navegador
puerto +
dirección IP
Paquete IP
Paquete IP
Paquete IP
puerto +
dirección IP
7
AW: Internet, protocolos y tecnologías Web
Aportaciones importantes:
• El cliente “ve” una conexión punto a punto con una máquina de
destino.
• El sistema operativo se encarga de organizar los paquetes,
reclamar los que no hayan llegado, etc.
… pero esto todavía no es suficiente:
• ¿Qué datos pedimos?
• ¿Cómo recibimos la respuesta?
8
AW: Internet, protocolos y tecnologías Web
Combina los aspectos relacionados con las aplicaciones
• Maneja protocolos de alto nivel, aspectos de representación,
codificación, etc.
Servicios de aplicación más comunes:
• DNS: Domain Name System.
• Traducción de nombres en direcciones (www.google.es  x.y.z.w)
• FTP: File Transport Protocol (sobre TCP).
• SMTP: Simple Mail Transfer Protocol.
• POP: Post Office Protocol (email).
• IMAP: Internet Message Access Protocol (email).
• HTTP: HyperText Transfer Protocol (Páginas Web).
9
AW: Internet, protocolos y tecnologías Web
Otros que os podrían sonar:
• BitTorrent – eD2k - Kademlia: Compartición de archivos p2p.
• IRC: Internet Relay Chat.
• NFS: Network File System.
• LDAP: Lightweight Directory Access Protocol.
• RPC: Remote Procedure Call.
• SSH: Secure Shell.
10
AW: Internet, protocolos y tecnologías Web
DNS (Domain Name System)
• Resolución de nombres.
Servidores DNS
• Dado el nombre de un host (www.ucm.es), obtener su IP (147.96.1.15).
Raíz
Top level domains
int us
com ar
edu
gov
mil
org
net
jp
fr
es …
amazon
ooc uba
sun omg
acm rediris
ieee
ucm
java
Genéricos Países
11
AW: Internet, protocolos y tecnologías Web
Servidores DNS:
• Servidores raíz: sólo 13 en todo el mundo
• Algunos están replicados.
12
AW: Internet, protocolos y tecnologías Web
13
Fuente: https://root-servers.org/
AW: Internet, protocolos y tecnologías Web
• En Windows (CMD):
• ipconfig
• ipconfig /all
• ipconfig /flushdns
• ping
• ping –n 10 8.8.8.8
• tracert
• tracert –h 7 www.ucm.es
• getmac
• nslookup
• nslookup www.ucm.es
• hostname
14
AW: Internet, protocolos y tecnologías Web
Modelo de funcionamiento básico de la Web
El cliente puede
ser un proceso
ejecutándose en
un dispositivo
cualquiera
El servidor puede
ser un proceso
ejecutándose en
un equipo
(normalmente de
altas prestaciones)
• El cliente realiza una petición a un servidor:
• Origina el tráfico web vía Internet usando un protocolo
(generalmente HTTP) y suele hacerse mediante un navegador.
• Si el servidor es capaz de responder a dicha petición, entonces
enviará una respuesta con el recurso o archivo que, generalmente,
el cliente es capaz de mostrar.
15
AW: Internet, protocolos y tecnologías Web
• La respuesta del servidor puede ser de dos posibles tipos:
• Estática: un archivo que está almacenado a priori en el
servidor.
• Dinámica: consiste en crear dinámicamente un documento, a
partir de información extra que se pasa al servidor al hacerle
la petición, y devolver dicho archivo creado ad-hoc.
16
AW: Internet, protocolos y tecnologías Web
URL (Uniform Resource Locator)
• Descriptor del acceso (cómo encontrar) a un recurso.
• Estructura
• protocolo://servidor[:puerto]/ruta/archivo[?datos]
• Por partes
• Protocolo: http, https, ftp, file, …
• Puerto: no suele ponerse. Si no está, se usa el por defecto de
cada protocolo (80, 443, 22, …).
• Datos adicionales: parámetros para el servidor.
http://kamino.fdi.ucm.es/saludos.php?num=50
17
AW: Internet, protocolos y tecnologías Web
El método habitual para obtener información empleando
un navegador web es el protocolo HTTP.
• El navegador suele recibir un documento HTML.
HTTP GET www.ucm.es
index.html
+
otros
Navegador Servidor Web
18 AW: Internet, protocolos y tecnologías Web
HTTP es un protocolo de tipo petición/respuesta sin estado cuya
operación básica es la siguiente:
1. 2. 3. 4. 5. 6. Una aplicación ejecutada por un cliente (habitualmente un navegador web)
se conecta al servidor Web.
• Pero para conectarse necesita saber su IP, no su nombre.
A través de la conexión el cliente envía la petición codificada como texto
ASCII.
El servidor Web analiza la petición y localiza el recurso especificado.
El servidor envía una copia del recurso al cliente a través de la conexión.
El servidor cierra la conexión.
El navegador interpreta los datos recibidos del servidor y muestra al cliente
el documento solicitado.
• A veces el documento pide descargar datos adicionales (e.g. fotos).
• Para estos archivos, se abren nuevas conexiones HTTP independientes.
19 AW: Internet, protocolos y tecnologías Web
Una petición HTTP consta de:
• Método de petición (GET, POST, etc.).
• URL (http://...).
• Datos adicionales.
Métodos habituales de petición:
• GET
• Solicita el recurso identificado por la URL.
• Es el mecanismo más habitual.
• POST
• Junto con la petición se envían datos al servidor Web
(archivos adjuntos, datos de un formulario,…).
20 AW: Internet, protocolos y tecnologías Web
Mensajes HTTP:
• Línea inicial.
• 0..n líneas de cabecera.
• Línea en blanco.
• Cuerpo de mensaje opcional (un fichero, solicitud de datos,
datos resultado de una solicitud).
<línea inicial, diferente para solicitud o respuesta>
Cabecera1: valor1
Cabecera2: valor2
Cabecera3: valor3
<opcional – cuerpo de mensaje, contenido de fichero o datos de query;
puede tener cualquier cantidad de líneas, incluso datos binarios>
21
AW: Internet, protocolos y tecnologías Web
Tipos de mensajes:
• HTTP/1.0 (1996, RFC1945)
• GET.
• POST.
• HEAD: Como GET pero pide al servidor que solamente envíe
la cabecera de la respuesta.
• HTTP/1.1 (1996, RFC2616)
• GET, POST, HEAD.
• PUT: Sube archivos en el cuerpo de la solicitud.
• DELETE: Borra el archivo especificado en el campo URL (si el
servidor le deja).
22
AW: Internet, protocolos y tecnologías Web
• RFC7540.
• Mantener la compatibilidad / coexistencia con HTTP/1.1.
• Disminuir la latencia para mejorar los tiempos de carga (y la
percepción del usuario).
• Protocolo binario.
• Compresión de cabeceras.
• Multiplexación de peticiones.
• Una conexión por origen.
• Recomendado / Forzado el uso de TLS >= 1.2
Fuente: https://kinsta.com/learn/what-is-http2
23
AW: Internet, protocolos y tecnologías Web
Líneas de cabecera
• Proporcionan información de la solicitud o respuesta
• Estructura:
• Nombre-cabecera “:” valor
• Nombre de cabecera:
• HTTP 1.0 define 16 cabeceras (ninguna obligatoria).
• HTTP 1.1 define 46 cabeceras, y requiere al menos una (e.g.
Host:).
24
AW: Internet, protocolos y tecnologías Web
El cliente suele poner las siguientes cabeceras en la
petición:
• Host (obligatoria en HTTP 1.1): El servidor al que me estoy
conectando (por si la máquina tiene varios).
• From: dirección email o programa solicitante.
• User-Agent: identifica el programa que hace la petición con la
forma: “nombre-programa/x.xx“
• Host:www.ucm.es
• User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64;
rv:11.0) Gecko/20120313 Chrome/56.0.2924.87
25
AW: Internet, protocolos y tecnologías Web
Una respuesta HTTP contiene:
• Código de respuesta.
• Datos de Cabecera.
• Cuerpo.
Respuestas más habituales:
• 200 – Éxito. En el cuerpo del mensaje se incluye el recurso solicitado.
• 404 – Recurso no encontrado.
• 5xx – indica un error en el servidor que impide dar respuesta a la petición.
26 AW: Internet, protocolos y tecnologías Web
El servidor suele poner las siguientes cabeceras:
• Date: fecha y hora actual en el servidor (en GMT).
• Server: identifica el software del servidor "Program-name/x.xx".
• Last-Modified: última modificación del recurso (para gestionar
cachés).
• Content-Length: número de bytes que va a contener la
respuesta.
• Content-Type: tipo MIME del contenido.
Date: Fri, 14 Feb 2014 10:17:22 GMT
Server: Apache/2.4.7 (Win32) OpenSSL/1.0.1e PHP/5.5.6
Last-Modified: Wed, 12 Feb 2014 15:16:55 GMT
Content-Length: 507
Content-Type: text/html
27
AW: Internet, protocolos y tecnologías Web
HTTP es un protocolo SIN ESTADO
• No se guarda información de la sesión/historia pasada
• (Esto simplifica el protocolo)
Una posible solución a este problema son las cookies
28
AW: Internet, protocolos y tecnologías Web
Uso de “cookies”
• Una cookie es un string que se pasa en una cabecera HTTP y que el
navegador puede guardar en un pequeño fichero de texto. (Set-Cookie:)
• La cookie se reenvía luego al servidor HTTP con cada petición del cliente a
ese servidor.
• Las cookies no pueden capturar información del cliente.
crea cookie respuesta HTTP
navegador
petición HTTP
servidor Memoria/
Disco
29
AW: Internet, protocolos y tecnologías Web
Campos ocultos de los formularios
• Obliga a procesar cada petición de página con el mecanismo de
enviar formulario.
• Anticuado, poco seguro.
Añadir información de estado al final del URL mediante
query string del URL
• http://maquina/pagina.html?session=2323412312
• Poco seguro
30
AW: Internet, protocolos y tecnologías Web
HTTPS (Hypertext Transfer Protocol Secure)
• Permite que la información sensible (datos de usuario,
passwords, pagos, etc.) no pueda ser interceptada sin cifrar
durante la transferencia de datos.
• La información viaja por un canal cifrado sobre SSL/TLS
• https://www.youtube.com/watch?v=ZghMPWGXexs&index=
6&list=PLzdnOPI1iJNfMRZm5DDxco3UdsFegvuB7
• Para utilizar HTTPS el servidor recibe las conexiones en el
puerto 443 (por defecto).
• La URL indica el uso de este protocolo: https://...
31
AW: Internet, protocolos y tecnologías Web
En el cliente:
• Navegadores (front-end).
• Lenguajes de programación.
En el servidor:
• Servidores (back-end).
• Lenguajes de programación.
• Gestores de contenidos.
Estándares en la web:
• Formatos.
• Accesibilidad.
32
AW: Internet, protocolos y tecnologías Web
Permiten acceder a la web y visualizar en modo gráfico
documentos HTML.
• Procesan también otros tipos de objetos: imágenes, sonidos,
videos, scripts, etc.
• Pueden arrancar aplicaciones que traten los ficheros recibidos.
Aceptan la instalación de plugins (módulos con
funcionalidad extra).
• Para procesar ciertos tipos de documentos (p.ej, PDF).
• Presentaciones Flash (deprecated).
33
AW: Internet, protocolos y tecnologías Web
Más populares:
• WorldWideWeb [Nexus] (Tim Berners-Lee, 1991).
• Mosaic (NCSA, 1993).
• Netscape Navigator: introduce JavaScript en la v2 (1995).
• El primer dominante.
• Nombre interno: Mozilla.
• Microsoft Internet Explorer (1995): sólo en Windows.
• Mozilla Suite (1995 – 2005 -> SeaMonkey).
• Opera (1996): el que más tecnologías soportaba.
• Mozilla Firefox (2002).
• Safari (2003): en Mac OS X [Apple llegó muy tarde a la Web…].
• Chrome (2008).
34
AW: Internet, protocolos y tecnologías Web
35
Fuente: https://gs.statcounter.com
AW: Internet, protocolos y tecnologías Web
En una página HTML se pueden incrustar elementos
computacionales y scripts:
• JavaScript.
• Flash (deprecated, RIP 2020).
• Applets de Java (deprecated, sin soporte desde Java 8).
• ActiveX (deprecated).
• WebAssembly
36
AW: Internet, protocolos y tecnologías Web
Fuente: https://w3techs.com/technologies/overview/client_side_language
37
AW: Internet, protocolos y tecnologías Web
Ejemplos de servidores Web
• Apache (apache.org).
• El más estándar en Linux (también funciona en Windows).
• Internet Information Server (IIS).
• Sólo para Windows, basado en la tecnología .NET.
• Nginx (nginx.org).
• Muy ligero y escalable, aunque menos versátil que Apache.
38
AW: Internet, protocolos y tecnologías Web
Fuente: https://w3techs.com/technologies/overview/web_server
39
AW: Internet, protocolos y tecnologías Web
La Web funciona en todos los países y todos los idiomas
• Los sitios Web deben diseñarse para adaptarse
automáticamente a cualquier idioma y región sin necesidad de
cambiar el código.
Unicode/ISO 10646
• Estándar universal para codificar texto multi-lenguaje.
• Mantenido por UTC (Unicode Technical Committee).
• Define tres formas de codificación: UTF-8, UTF-16 y UTF-32.
40
AW: Internet, protocolos y tecnologías Web
Para indicar el conjunto de caracteres que se utiliza
• En HTML5, dentro de <HEAD>, con una etiqueta <META>:
<head>
<meta charset= "utf-8">
</head>
Para indicar el idioma en una parte del contenido de una
página
• En HTML: lang="es"
• Se recomienda indicar el idioma del documento antes del
<HEAD>
<html lang="es">
• Y cada vez que se cambie de idioma a lo largo del texto de la
página
<p>Usted diría eso en chino como <span lang="zh-Hans">信息学</span>.</p>
41
AW: Internet, protocolos y tecnologías Web
Enero 2023 Enero 2026
Fuente: https://w3techs.com/technologies/overview/content_language
42
AW: Internet, protocolos y tecnologías Web
¿Todas las personas tienen derecho a utilizar Internet y
poder acceder de forma libre a todo su contenido?
• ¿Todas las personas tienen derecho a circular por las aceras,
montar en transporte público, etcétera?
Como en la vida misma, los sitios web que no tienen en
cuenta la accesibilidad, suponen una barrera para un
sector de la población.
• Conseguir que la web sea un lugar verdaderamente universal,
sin fronteras ni barreras.
43
AW: Internet, protocolos y tecnologías Web
• Garantizar que personas con algún tipo de
discapacidad van a poder hacer uso de la Web.
• Hacer diseño Web que permita que estas personas
puedan percibir, entender, navegar e interactuar con
la Web, aportando a su vez contenidos.
• La accesibilidad Web también beneficia a otras
personas, incluyendo personas de edad avanzada que
han visto mermadas sus habilidades a consecuencia
de la edad.
44
AW: Internet, protocolos y tecnologías Web
Existen unas pautas que deben ser seguidas si se desea
que un sitio web sea completamente accesible.
• Estas pautas han sido elaboradas por la Iniciativa de
Accesibilidad Web (WAI) del Consorcio World Wide Web (W3C).
Web Accessibility Initiative (WAI) del W3C
• http://www.w3.org/WAI/
• Guías y herramientas para facilitar la accesibilidad de los sitios
Web.
45
AW: Internet, protocolos y tecnologías Web
WCAG: Web Content Accessibility Guidelines 2.0 (W3C, 2008)
• 14 pautas de diseño accesible
• Prioridad 1: puntos que si no se cumplen, ciertos grupos de usuarios no
podrían acceder a la información del sitio Web.
• Indicar siempre el cambio de idioma (lang=
"en"
, lang=
"fr"
, lang=
"es")
• Utilizar el atributo alt para incorporar texto equivalente a una imagen
• Prioridad 2: puntos que si no se cumplen, sería muy difícil acceder a la
información para ciertos grupos de usuarios.
• Prioridad 3: puntos que si no se cumplen, algunos usuarios
experimentarían ciertas dificultades para acceder a la información.
• Niveles de conformidad:
• A: Satisface todos los puntos de verificación de prioridad 1.
• AA: ídem prioridad 1 y 2.
• AAA: ídem prioridad 1, 2 y 3.
46
AW: Internet, protocolos y tecnologías Web
• Imágenes y animaciones
• Usar el atributo alt para describir la función de cada
elemento visual.
• Mapas de imagen
• Usar el elemento map y texto para las zonas activas.
• Multimedia
• Proporcionar subtítulos y transcripción del sonido, así como
descripción del vídeo.
• Enlaces de hipertexto
• Usar texto que tenga sentido leído fuera de contexto. Por
ejemplo, evite "pincha aquí”.
47
AW: Internet, protocolos y tecnologías Web
• Organización de las páginas
• Usar encabezados, listas y estructura consistente.
• Usar CSS para la maquetación donde sea posible.
• Figuras y diagramas
• Describir brevemente en la página o usar el atributo
longdesc.
• Scripts, applets y plug-ins
• Ofrezcer contenido alternativo si las funciones nuevas no son
accesibles.
• Tablas
• Facilitar la lectura línea a línea. Resumir.
48
AW: Internet, protocolos y tecnologías Web
Licencia Creative Commons
 Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
 Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo
Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros y Javier
Bravo Agapito
49 AW: Internet, protocolos y tecnologías Web



2
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Juan Pavón Mestras, con modificaciones de
Material elaborado por Juan Pavón Mestras, con modificaciones de
Pablo Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros,
Pablo Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros
Iván Martínez Ortiz y Javier Bravo Agapitoe Iván Martínez Ortiz
• HTML, XHTML, HTML5
• Estructura de un documento HTML
• HTML: Etiquetas, atributos, validación
• HTML: Cabecera
• <title>, <link>, <meta>, <script>, <noscript>, <style>
• HTML: Texto
• Etiquetas de bloque, inline, de contenedores
• HTML: Apariencia y navegación
• Imágenes, enlaces, listas, tablas, codificación de caracteres,
comentarios
• HTML: Formularios
2 Tema 2. HTML5
HTML (HyperText Markup Language): Lenguaje con el que se
definen páginas Web
• Permite describir el contenido de una página, incluyendo texto y otros
elementos.
• Una página HTML consta de texto y marcas especiales que permiten
indicar algún tratamiento especial.
• Las marcas/etiquetas se indican en formato <marca>…</marca>
<html>
<head>
<title>Título de la página</title>
</head>
<body>
<h1>Título de sección</h1>
<p>Texto...</p>
<p>Texto <b>en negrita</b></p>
</body>
</html>
3 Tema 2. HTML5
HTML: Lenguaje original
• Basado en SGML (Standard Generalized Markup Language).
• Tiene elementos no compatibles con XML.
4 Tema 2. HTML5
5 Tema 2. HTML5
Estructura general
• Todo documento HTML comienza por la etiqueta <html> y acaba con
</html>
• Todo documento HTML tiene dos partes: Cabecera y Cuerpo
<html>
Cabecera
<head>
</head>
<title>Título de la página</title>
Cuerpo
<body>
<h1>Título de sección</h1>
<p>Texto...</p>
<p>Texto <b>en negrita</b></p>
</body>
</html>
6 Tema 2. HTML5
El elemento <html>
• Puede servir para indicar el idioma del texto
• <html lang="en">
• <html lang="en-GB">
• <html lang="es">
• Esto es importante para mejorar la accesibilidad del documento
• Si en alguna parte del documento se utiliza otro idioma se
puede indicar en el elemento contenedor correspondiente
• <p lang="fr">Ceci est un paragraphe.</p>
• <p>Buenos días en francés: <span lang="fr"> Bonjour
</span></p>
7 Tema 2. HTML5
Declaración que indica que el
documento es HTML5
<!DOCTYPE html>
<html>
<head>
</head>
<title>Título del documento</title>
<body>
</body>
<p>Ejemplo de documento HTML5</p>
</html>
HTML 4.01: <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML
4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
8
Tema 2. HTML5
Etiquetas emparejadas y sin emparejar:
<h1>Introducción</h1>
<p>Esto es un párrafo</p>
<hr />
<link href="estilos.css" rel="stylesheet" type="text/css" />
HTML permite que las etiquetas sin emparejar no lleven
cierre (/)
<hr>
<link href="estilos.css" rel="stylesheet" type="text/css">
En HTML las etiquetas se pueden escribir en mayúsculas o
minúsculas, indistintamente
• Aunque se recomienda escribir las etiquetas en minúsculas
<h1>Título <Em>1</eM></H1>
9
Tema 2. HTML5
Un elemento puede tener una etiqueta con atributos,
separados por espacios, y que se especifican como pares
atributo="valor“
<etiqueta atributo="valor"> texto </etiqueta>
• En XHTML el valor siempre tiene que ir entre comillas.
• En HTML no es obligatorio, pero muy recomendado.
Los atributos permiten añadir información adicional
 Por ejemplo, un hiper-enlace:
<a href="http://www.ucm.es/">Universidad Complutense de
Madrid</a>
10
Tema 2. HTML5
Se pueden asociar a cualquier etiqueta:
• id = "texto"
• Asigna un identificador único al elemento dentro de la página.
• class = "texto"
• Asocia una clase CSS que define un conjunto de estilos de formato
para el elemento.
• style = "texto"
• Establece de forma directa los estilos CSS del elemento.
• title = "texto"
• Asigna un título a un elemento.
• Es útil para mejorar la accesibilidad ya que los navegadores lo
muestran cuando el usuario pasa el ratón por encima del
elemento.
• contenteditable ="true | false"
• Indica si el contenido de un elemento puede ser editado.
11 Tema 2. HTML5
Conviene validar el código HTML5
• Elementos obligatorios (como <title>).
• Etiquetas abiertas que no se cierran.
• Etiquetas mal anidadas.
• Atributos necesarios para elementos (como un <img> sin src).
• Elementos en lugares incorrectos (como texto en la cabecera).
Validador oficial de W3C
• http://validator.w3.org
• https://chrispederick.com/work/web-developer/
• https://addons.mozilla.org/en-US/firefox/addon/web-
developer/
También se debe comprobar qué características
funcionan en los distintos navegadores
• http://caniuse.com
12 Tema 2. HTML5
13 Tema 2. HTML5
<head>
• Delimita la cabecera del documento.
• En la cabecera se describe información del documento (título,
meta-información, scripts, estilos).
<title>
• Indica el título del documento.
• Es “obligatoria” y tiene que aparecer una y sólo una vez en el
documento.
• El navegador lo visualiza en la barra de título de su ventana (o
en la pestaña).
14 Tema 2. HTML5
<link />, <link>
• Enlace a otros archivos (generalmente hojas de estilo)
<link rel="stylesheet" type="text/css" href="estilo.css" />
<link rel="stylesheet" type="text/css" href="estilo.css">
• Separación del contenido y el estilo
• Se puede reutilizar el fichero de estilo para todas las páginas
de un sitio.
• Código HTML más claro.
• Se pueden definir distintos estilos según el propósito
• Un estilo para visualizar en el navegador y otro para imprimir
la página.
<link rel="stylesheet" media="screen" href="estilo.css">
<link rel="stylesheet" media="print" href="impresora.css">
15
Tema 2. HTML5
Metadatos (<meta />, <meta>)
• Meta-información sobre el documento (información procesable
automáticamente por programas que analicen la página).
• Normalmente se usan los atributos name (para definir un tipo
de metadato) y content (para definir el valor).
• Atributos habituales:
<meta name=“author” content=“Javier”>
<meta name=“robots” content=“index, follow”>
<meta name=“keywords” content=“aplicaciones web, html”>
<meta name=“description” content=“Curso sobre aplicaciones web”>
<meta charset=“utf-8”>
16
Tema 2. HTML5
<script>
• Permite incluir código de script.
• Aunque por defecto se espera JavaScript, conviene especificarlo.
<noscript>
• Indica un mensaje a mostrar en navegadores que no pueden procesar
<script>
<script type="text/javascript">
//Código JavaScript
</script>
<noscript>
<p>Esta página requiere el uso de JavaScript. Por favor
compruebe la configuración de su navegador.</p>
</noscript>
17 Tema 2. HTML5
<script>
• También puede referirse a un fichero externo
<script type="text/javascript"
src="http://www.ejemplo.com/js/miscript.js">
</script>
18 Tema 2. HTML5
<style>
• Permite definir propiedades de estilos que se aplicarán a lo largo del
documento.
• En lugar de esto generalmente se indica la hoja de estilo que se va a
aplicar (con <link>).
• Veremos la sintaxis detallada más adelante.
<style type="text/css">
p {
font-family: Arial;
font-size: 10pt;
color: rgb(0,0,128);
text-indent: 15px;
text-align: justify;
margin-left: 10px;
}
</style>
19 Tema 2. HTML5
20 Tema 2. HTML5
El texto de un documento debe estar contenido por alguna de las
etiquetas de una de las tres categorías siguientes:
• Etiquetas de bloque:
• Pueden aparecer directamente dentro de <body>.
• No se deben anidar.
• <p> <pre> <h1> <h2> <h3> <h4> <h5> <h6> <address>.
• Etiquetas inline:
• Se usan dentro de los bloques.
• No deben aparecer fuera de los bloques.
• Afectan a una parte de texto dentro de un bloque.
• <br /><abbr> <cite> <code> <em> <kbd> <strong> <sub> <sup>.
• Etiquetas de contenedores de bloques:
• Sirven para estructurar el texto y definir agrupaciones de bloques.
• Pueden contener etiquetas de bloque u otros contenedores anidados.
• <body>.
• <blockquote>, <div>, <article>, <header>, <section>, <aside>, <nav>,
<footer>.
21 Tema 2. HTML5
22 Tema 2. HTML5
<p>
• Contiene el texto de un párrafo.
• El navegador no muestra los espacios en blanco ni los saltos de
línea dentro de un párrafo.
<p>Esto es un párrafo. El contenido aparecerá partido en varias
líneas según el tamaño de la ventana, pero es independiente de
los
saltos de línea o del uso de espacios adicionales</p>
<pre>
• Texto preformateado: igual que <p> pero sí se tienen en cuenta
espacios en blanco y líneas en blanco.
• Se usa un tipo de letra de ancho fijo.
23
Tema 2. HTML5
<h1> <h2> <h3> <h4> <h5> <h6>
• Encabezado (de nivel 1 a 6)
<h1>Sección 1</h1>
<h2>Sección 1.1</h2>
<p>Texto normal.</p>
<h3>Sección 1.1.1</h3>
<p>Texto normal.</p>
<address>
• Información de contacto del autor de la página
<address>Autor: Javier Bravo (UCM) <br />
Última modificación: 28 de enero de 2026
</address>
24
Tema 2. HTML5
25 Tema 2. HTML5
<br />
• Salto de línea.
&nbsp;
• Espacio en blanco. (non breaking space)
• Para forzar espacios que si no ignoraría el navegador.
• Poco aconsejable, mejor usar <pre>.
• Para poner un blanco en el que el navegador no rompa las líneas.
<hr />
• Línea horizontal (horizontal rule).
• Se usa cada vez menos ya que con CSS se pueden crear mejor los
bordes.
26 Tema 2. HTML5
Importancia del texto
• <em>
• Para resaltar una porción de texto dándole énfasis (por
defecto aparecerá en cursiva).
• <strong>
negrita).
• Mayor énfasis que con <em> (por defecto aparecerá en
• En desuso:
• Antes se usaba <i> para cursiva y <b> para negrita.
• Ahora tienen un nuevo significado (voz alternativa o atención
especial).
27 Tema 2. HTML5
Tipo de información (relativamente poco comunes):
• <abbr>
• Abreviatura.
<abbr title="etcétera">etc.</abbr>
<abbr title="Universidad Complutense de Madrid">UCM</abbr>
• <cite>
cursiva).
• Para incluir una referencia o cita (por defecto aparecerá en
<p>Como dice el refrán, <cite>a la tercera va la
vencida</cite>. </p>
• <dfn>
• Definición de un término (por defecto aparecerá en cursiva).
28
Tema 2. HTML5
Tipo de información:
• <code>
• Parecido a <pre>, pero para usar en mitad de un bloque.
• Fragmento de código de programa (por defecto aparecerá en
letra monospace).
• Los saltos de línea y espacios dentro de <code> se ignoran.
<p>La función se escribe poniendo <code>main() { printf
("Hola, mundo"); }</code> en el archivo fuente.</p>
29
Tema 2. HTML5
Para indicar edición del texto:
• <del>
tachado).
• Para mostrar que se elimina un texto (por defecto aparecerá
• <ins>
• Para mostrar que se ha insertado un texto (por defecto
aparecerá subrayado).
<p>La nota final es <del>suspenso</del>
<ins datetime="20240524">aprobado</ins>.</p>
• La nota final es suspenso aprobado.
30
Tema 2. HTML5
Modificación de aspecto:
• Las siguientes etiquetas existían en HTML, pero han sido
eliminadas de HTML5:
• <big> para etiquetar texto de mayor tamaño.
• <tt> para etiquetar texto de fuente de espaciado fijo.
• Las siguientes etiquetas se usan habitualmente
• <sub> para identificar texto en subíndice: H<sub>2</sub>O
 H2O
• <sup> para identificar texto en superíndice: x<sup>n</sup>
 xn
31 Tema 2. HTML5
<span>
• Se usa para dar formato con la hoja de estilo a un grupo de elementos
en línea seguidos dentro de un mismo bloque (por ejemplo, varias
palabras seguidas dentro de un párrafo).
• Se suele asociar a clases definidas en un fichero de estilos (.css).
Fichero .css
Fichero .html (incluye al .css)
span.feroz {
font-style: italic;
text-decoration: underline;
}
span.rojo {
color: red;
}
<p>Andaba sola
<span class="rojo">Caperucita
Roja</span>
y se encontró
<span class="feroz">al lobo
feroz</span></p>
Andaba sola Caperucita Roja y se encontró al lobo feroz
32 Tema 2. HTML5
33 Tema 2. HTML5
<blockquote>
• Para incluir una cita larga, que puede contener varios párrafos u
otras etiquetas.
• Se suele mostrar el texto dentro de esta etiqueta con márgenes
a izquierda y derecha.
• Lo mejor es definir el formato deseado en la hoja de estilo.
• Se puede indicar el origen de la cita con el atributo cite.
<p>El candidato a la presidencia fue locuaz en su discurso:</p>
<blockquote cite="http://www.buenasnoticias.org/entrevista12.html">
<p>Prometo que voy a respetar los servicios sociales, naturalmente.
Miente quien infunda alguna sospecha al respecto. </p>
<p>Y bla bla bla...</p>
</blockquote>
34 Tema 2. HTML5
<div>
• División: mecanismo más importante para agrupar diversos
elementos de bloque (párrafos, encabezados, listas, tablas,
divisiones, etc.).
• El formato hay que definirlo en una hoja de estilo.
• Una división no puede insertarse dentro de una etiqueta inline
(<strong>,<em>, etc.) o de un bloque de texto (<p>, <h1>, etc.)
• Pero sí puede insertarse dentro de otra división <div>.
Con CSS se puede luego definir la posición de los distintos
elementos.
35 Tema 2. HTML5
Tradicionalmente se hacía con elementos <div>
<body>
<div class=”header”>
<div
class=
”menu”>
<div class= ”contenido”>
<div class= ”apartado”>
<div class= ”apartado”>
...
<div class=”footer”>
36
Tema 2. HTML5
Estructuras semánticas (sólo HTML5)
• Etiquetas similares a <div> pero con significados más
específicos:
• <header>, <nav>, <section>, <article>, <aside>, <footer>,
<details>, …
• Facilitan el procesamiento semántico (búsquedas,
procesamiento del contenido).
• Clarifica la estructura del documento con elementos estándar
comunes.
• Se pueden anidar dentro de otros elementos contenedores.
37 Tema 2. HTML5
<body>
<header>
<nav>
<footer>
38
<section>
<article>
<article>
...
Tema 2. HTML5
39 Tema 2. HTML5
<img src="url-de-imagen" alt="descripcion textual" />
• Descripción textual para navegadores ‘modo texto’, o mientras se
carga la imagen.
Formatos recomendables:
• .png : para imágenes con pocos colores donde los detalles pequeños
son importantes; ej.: capturas de pantalla.
•
.jpg: para imágenes con muchos colores donde los detalles finos son
menos salientes; ej.: fotos.
.png – 6kb .jpg – 12kb
(calidad al 50%)
40 Tema 2. HTML5
.jpg – 32kb
(calidad al 50%)
.png – 428kb
GIF (Graphics Interchange Format):
• Patentado y necesita licencia.
JPEG (Joint Photographic Experts Group)
• Ratios de compresión muy altos.
PNG (Portable Network Graphics)
• Estandarizado por W3C (1996) y por ISO (ISO/IEC 15948:2003).
• Los archivos gráficos en formato PNG pueden ser indexados por los motores
de búsqueda, debido a la inclusión de meta-información.
• PNG ofrece un modo de compresión progresivo (entrelazado de dos
dimensiones) que facilita el reconocimiento de la imagen en el inicio de su
descarga.
• No permite imágenes animadas (GIF sí)
• Pero hay una variante animada MNG (Multiple-image Network Graphics).
41 Tema 2. HTML5
SVG (Scalable Vector Graphics)
• Descripción de gráficos vectoriales en dos dimensiones.
• Con formato XML.
• Recomendación del W3C (2001).
• Implementado en casi todos los navegadores actuales.
• Define tres tipos de objetos gráficos:
• Formas gráficas vectoriales (líneas, curvas, áreas).
• Texto.
• Imágenes de mapa de bits/digitales.
42 Tema 2. HTML5
Fuente: https://w3techs.com/technologies/history_overview/image_format
43
Tema 2. HTML5
Fuente: https://w3techs.com/technologies/overview/image_format
44
Tema 2. HTML5
<a href="destino">texto del enlace</a>
• “a” de anchor (ancla).
Campo “destino”:
• Dirección absoluta: "http://www.ucm.es"
• Página local absoluta: "file:///c:/mifichero.txt"
• Dirección relativa: "foros/general.php"
• Correo electrónico: "mailto:javier.bravo@ucm.es"
45 Tema 2. HTML5
Contenido
• Generalmente texto que aparece subrayado en azul.
• Puede ser una imagen.
<a href="http://www.ucm.es/">
<img src="logo.png" />
</a>
Dónde se abre el enlace:
• En la misma ventana: target="_self" (opción por defecto)
• En otra ventana (o pestaña): target="_blank"
46
Tema 2. HTML5
En lugar de a una página, los enlaces pueden apuntar a
un elemento concreto.
• Primero, definimos un identificador
<h1 id="apartado2">…</h1>
• Enlace para ir al apartado desde el mismo documento
<a href="#apartado2">Ir al apartado 2</a>
• Enlace desde otro documento distinto
<a href="pagina.html#apartado2">Ir al apartado 2</a>
47
Tema 2. HTML5
Tres tipos de listas:
• Listas numeradas (<ol> ... </ol>)
• <li> ... </li> delimitan cada elemento de la lista
• Listas no ordenadas (<ul> ... </ul>)
• <li> ... </li> delimitan cada elemento de la lista
• Listas de definición (<dl> ... </dl>)
• <dt> ... </dt> delimitan los términos
• <dd> ... </dd> delimitan las definiciones
48 Tema 2. HTML5
Las listas se pueden anidar
<ol>
<li>Primer elemento </li>
<li>Segundo elemento </li>
<ul>
<li>Elemento de lista desordenada anidada</li>
</ul>
<li>Definiciones: </li>
<dl>
<dt>Término</dt>
<dd>Definición del término 1</dd>
</dl>
</ol>
49 Tema 2. HTML5
Escribe el código necesario para generar las siguientes
listas anidadas:
50 Tema 2. HTML5
Permiten presentar información tabular, en filas y
columnas, con cabeceras
• Cada elemento de la tabla puede ser simple o a su vez ser otra
agrupación de filas y de columnas, cabeceras y pies de tabla,
subdivisiones, cabeceras múltiples y otros elementos complejos.
Como permiten un control muy detallado, a veces se usan
para organizar la estructura general de una página web.
Esto es algo que no se recomienda en absoluto
51 Tema 2. HTML5
Leyenda de la tabla
<caption>
Cabecera
de la tabla
<thead>
Cuerpo de
la tabla
<tbody>
Pie de
la tabla
<tfoot>
Calificaciones
Cabecera de columna
<th>
Alumno Práctica Trabajo Final
Álvarez Gómez, Javier 8 8 NT (8)
Gutiérrez Rodríguez, Clara 8 10 SB (9)
Fila
<tr>
Rodríguez Hernández, Pedro 8 6 NT (7)
Revisión de exámenes: martes 18 a las 12h
Cabecera de fila
<th>
celda
<td>
52 Tema 2. HTML5
<table>
• Define una tabla
• Anteriormente, se configuraba con atributos:
• Border, width, cellspacing, cellpading, …
• Todos los atributos de las tablas desaparecen en HTML5.
• Mejor usar CSS
<caption>
• Leyenda de la tabla: texto opcional que se muestra fuera de la
tabla (por defecto, arriba).
• Se suele poner justo después de <table>.
• No puede incluir párrafos ni otros elementos de bloque, aunque
sí etiquetas inline (<strong>, <em>, etc.).
53 Tema 2. HTML5
Una tabla sencilla se define con:
• La etiqueta <table>.
• A continuación se definen las filas, con <tr> (table row).
• Y para cada fila, los elementos con <td> (table data cell).
• Algunas celdas se usan como cabeceras de fila o columna:
<th> (table header)
Se pueden agrupar celdas:
• En una fila con el atributo colspan.
• En una columna con el atributo rowspan.
54 Tema 2. HTML5
<table>
<caption>Fusión de filas y columnas</caption>
<tr>
<th colspan=3>Números</th>
</tr>
<tr>
<th>Nombre</th>
<th>Valor</th>
<th>Idioma</th>
</tr>
<tr>
<td>Uno</td>
<td>1</td>
<td rowspan="2">Español</td>
</tr>
<tr>
<td>Dos</td>
<td>2</td>
</tr>
</table>
55 Tema 2. HTML5
Acentos y letras especiales:
• Lo mejor es declarar el uso de caracteres UTF-8 para que se vean bien
los acentos y letras como la ñ.
• Hoy día la mayoría de los navegadores interpretan bien UTF-8.
• Algunas herramientas no lo interpretan bien, y convierten el texto a
ISO-8859.
• Se pueden sustituir por símbolos universales (cualquier navegador
sabrá interpretarlos).
&ntilde; ñ &Ntilde; Ñ Ñ
&aacute; á &Aacute; Á Á
&eacute; é &Eacute; É É
&iacute; í &Iacute; Í Í
&oacute; ó &Oacute; Ó Ó
&uacute; ú &Uacute; Ú Ú
&uuml; ü &Uuml; Ü Ü
&euro; €
56 Tema 2. HTML5
Caracteres especiales
• Hay muchos símbolos que no se pueden poner “tal cual” en un
documento HTML:
• &lt; <
• &gt; >
• &amp; &
• &quot; "
• &nbsp; (espacio en blanco)
• &apos; '
• &ndash; –
Lista de todos los 256 caracteres especiales en HTML:
• http://en.wikipedia.org/wiki/List_of_XML_and_HTML_character
_entity_references
57 Tema 2. HTML5
Texto que ignora el navegador
<!-- texto del comentario (una o más líneas) -->
Los comentarios pueden estar insertados en cualquier lugar
de la página web. Normalmente se usan para:
• Marcar el comienzo y el final de las secciones de las páginas
<!-- Inicio de las noticias -->
<div id="noticias"> ... </div>
<!-- Fin de las noticias -->
• Incluir notas para otros diseñadores
<!-- Esto se puede mejorar -->
• Incluir explicaciones sobre el código de la página
<!--
Script para identificar las preferencias del usuario
-->
58 Tema 2. HTML5
59 Tema 2. HTML5
<form>
Conjunto de controles que permiten al usuario interactuar
• Generalmente para introducir datos y enviarlos al servidor web.
• El navegador envía únicamente los datos de los controles contenidos
en el formulario.
• En una misma página puede haber varios formularios que envíen
datos al mismo o a diferentes servidores.
60 Tema 2. HTML5
Ejemplo:
<form action="http://www.miweb.com/procesaform.php" method="post">
Escribe tu nombre:
<input type="text" name="nombre" value="" />
<br/>
<input type="submit" value="Enviar" />
</form>
61 Tema 2. HTML5
Atributos de <form>
• action="URL": aplicación del servidor que procesará los datos
remitidos (por ejemplo, un script de PHP).
• method: método HTTP para enviar los datos al servidor.
• GET: como añadido a la dirección indicada en el atributo
action.
• Limitado a 500 bytes.
• Los datos enviados se añaden al final de la URL con un separador “?”.
• POST: en forma separada
• Puede enviar más información.
• Permite enviar ficheros adjuntos.
• Los datos enviados no se ven en la barra del navegador.
• Se suele usar cuando se envía información que puede modificar el
servidor.
62 Tema 2. HTML5
• POST vs GET: ¿estás cambiando algo de forma irreversible?
Un botón de 'refrescar listado' puede enviar 'GET';
Uno de 'borrar archivo' (irreversible) sólo debe enviar 'POST'.
POST /pagina-destino HTTP/1.1
… resto de cabeceras …
Connection: keep-alive
Content-Type: application/x-www-form-
urlencoded
Content-Length: 56
nombre=escribe+tu+nombre&apellidos=escribe+t
us+apellidos
GET /pagina-destino?nombre=escribe+tu+nombre&apellidos=escribe+tus+apellidos HTTP/1.1
… resto de cabeceras …
63 Tema 2. HTML5
Dentro de un formulario puede haber:
• Cualquier elemento típico de una página web
• Párrafos, imágenes, divisiones, listas, tablas, etc.
• Controles de formularios
• <input />, <button>, <select> y <option>, <optgroup>,
<textarea>
• Estructura de formularios
• <fieldset> y <legend>
• Información para accesibilidad
• <label> permite mejorar la accesibilidad de los controles
• Controles avanzados (sólo HTML5)
• <datalist>, <keygen>, …
64 Tema 2. HTML5
<input />
• type = "text | password | checkbox | radio | submit | reset | file | hidden |
image | button" - Indica el tipo de control que se incluye en el formulario.
• name = "texto" - Nombre del control (para que el servidor pueda procesar el
formulario).
• value = "texto" - Valor inicial del control.
• size - Tamaño inicial del control (en píxeles, salvo para campos de texto y de
password que se refiere al número de caracteres).
• disabled - El control aparece deshabilitado y su valor no se envía al servidor
junto con el resto de datos.
• readonly - El contenido del control no se puede modificar.
• alt = "texto" - Descripción del control.
Y muchos más…
65 Tema 2. HTML5
Cuadro de texto
Nombre <br/>
<input type="text" name="nombre" value="" />
Se enviará al servidor cuando se pulse un botón de enviar
• El nombre asignado en nametiene que concordar con el que se use en la
aplicación en el servidor.
• valuepermite establecer un valor inicial en el cuadro de texto.
Contraseñas
Contraseña <br/>
<input type="password" name="contrasena" value="" />
• Igual que el cuadro de texto, pero el valor introducido no se ve.
66 Tema 2. HTML5
Cuadro de texto de varias líneas
Nombre <br/>
<textarea name="nombre" rows="4" cols="50">
Contenido inicial del cuadro de texto
</textarea>
• Atributos:
• rows: número de filas visibles (sale una barra de
desplazamiento).
• cols: anchura en caracteres.
67 Tema 2. HTML5
Botón de envío de formulario
<input type="submit" name="enviar" value="Enviar" />
• El navegador se encarga de enviar automáticamente los datos
cuando el usuario pincha el botón.
Botón de reseteo de formulario
<input type="reset" name="borrar" value="Borrar
formulario" />
• El navegador borra toda la información introducida y muestra el
formulario en su estado original.
68 Tema 2. HTML5
Botones en general: <button>
<button type="submit">Enviar</button>
<button type="reset">Borrar formulario</button>
<button type="button">Botón</button>
69 Tema 2. HTML5
Ambos tienen tres tipos:
• submit: botón de envío; envía el formulario a su destino.
• reset: pone todos los campos a sus valores iniciales.
• button: no hace nada; útil en combinación con JavaScript.
Mejor Button:
• Puede contener HTML dentro.
• Se puede personalizar su estilo más fácilmente.
• https://caniuse.com/?search=button
70 Tema 2. HTML5
<form action="http://example.com/prueba.php" method="POST">
<input type="text" name="nombre" value="escribe tu nombre"/>
<input type="text" name="apellidos" value="escribe tus
apellidos"/>
<input type="submit" name="submit1" value="Enviar
form.">hola!!</input>
<input type="reset" name=“resetForm" value="Limpiar
form.">adios!!</input>
<button type="submit" name="submit2" value="Enviar form.">
<img src="img/check.png" /> Enviar
</button>
</form>
71 Tema 2. HTML5
Casillas de verificación (checkbox)
Lenguajes de programación: <br/>
<input name="java" type="checkbox" value="Java" checked/> Java
<input name="cplusplus" type="checkbox" value="Cplusplus"/> C++
<input name="csharp" type="checkbox" value="Csharp"/> C#
<input name="otros" type="checkbox" value="Otros"/> Otros
checked indica si la casilla está activada por defecto
72
Tema 2. HTML5
Radiobutton
Sexo <br/>
<input type="radio" name="sexo" value="hombre" checked/> Hombre
<input type="radio" name="sexo" value="mujer" /> Mujer
Hay que usar el mismo “name” para
formar un grupo de selección exclusiva
73
Tema 2. HTML5
Listas de selección
<select name="lenguajes">
<option value="c">C</option>
<option value="cplusplus">C++</option>
<option value="java" selected>Java</option>
<option value="php">PHP</option>
<option value="python">Python</option>
</select>
• Atributos de option:
• value determina el valor que se envía al servidor.
• selected permite definir la opción por defecto.
74
Tema 2. HTML5
Incluir un fichero
• El atributo enctypeen la etiqueta <form> del formulario tiene
que ser multipart/form-data.
• Sólo vale si el método es post.
<form name="fichero" action="procesa_fichero.php"
method="post" enctype="multipart/form-data">
Fichero: <input type="file" name="archivo" />
<input type="submit" value="Enviar">
</form>
75
Tema 2. HTML5
Agrupación de elementos
• Permite ver mejor las partes de un formulario agrupando
elementos relacionados.
• <legend> es el título que se visualiza con el grupo.
<fieldset>
<legend>Información personal:</legend>
Nombre: <input type="text" size="50"><br>
E-mail: <input type="text" size="50"><br>
Ciudad: <input type="text" size="20">
</fieldset>
76
Tema 2. HTML5
Licencia Creative Commons
• Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo
Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros, Iván
Martínez Ortiz y Javier Bravo Agapito
77 Tema 2. HTML5



3
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Juan Pavón Mestras, con modificaciones de
Pablo Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros y
Material elaborado por Juan Pavón Mestras, con modificaciones de
Javier Bravo AgapitoPablo Moreno Ger, Manuel Freire Morán y Raquel Hervás Ballesteros.
• Servidor web
• El lenguaje PHP
• PHP: echo, print, printf, sprintf
• PHP: sintaxis, tipos básicos, arrays
• PHP: estructuras de control
• if/else/elseif, switch
• while, for, foreach
• PHP: funciones, variables, constantes
Tema 3.1. PHP 2
Accede a la encuesta:
https://www.menti.com/alitvq2fos35
Código: 8569 3830
Tema 3.1. PHP 3
Tema 3.1. PHP 4
Apache
• Servidor web: recibe y responde peticiones HTTP.
• Muy flexible, docenas de extensiones y módulos.
• Puede delegar peticiones a otros servidores (Tomcat, ...), o
responderlas usando distintos procesadores (php, cgi, ...).
PHP
• Lenguaje de script embebido en HTML.
• PHP: Hypertext Preprocessor.
• Desde la v5, buen soporte para POO.
MySQL/MariaDB
• Base de datos SQL rápida y potente.
• phpMyAdmin permite administrarla de forma gráfica vía web.
Tema 3.1. PHP
5
Incluye Apache, MySQL/MariaDB y PHP (+Perl)
• http://www.apachefriends.org/en/xampp-windows.html
Muy similar a lo que ofrecen los proveedores de hosting
típicos
• Útil para hacer pruebas de desarrollo en local.
• Técnicamente, permite tener un servidor completamente
funcional en cualquier ordenador.
• ¡Pero no se recomienda!
Tema 3.1. PHP
6
XAMPP está orientado a desarrollo y no tanto a
producción.
Riesgos de seguridad con XAMPP:
• El administrador de MySQL no tiene password.
• El demonio de MySQL es accesible desde internet.
• Los usuarios de Mercury y FileZilla (Windows) y ProFTPD (Linux)
usan passwords por defecto que son conocidas.
• PhpMyAdmin es accesible desde internet.
• En Linux, MySQL y Apache se ejecutan bajo el mismo usuario
(root).
Tema 3.1. PHP 7
¿Y entonces?
• Desarrollo local en XAMPP
• Servidores de producción separados (no XAMPP)
• Linux
• Apache
• MySQL
• PHP
Y lo primero es revisar la configuración de seguridad
básica
• Hay ayudas para Linux y Windows (ver la documentación).
Tema 3.1. PHP 8
Tema 3.1. PHP 9
Evolución
• 1994: Rasmus Lerfdorf (Personal Home Page); construido sobre PERL.
• 1998: PHP3; reescritura del intérprete (Andi Gutmans, Zeev Suraski);
cambian acrónimo a PHP: Hypertext Processor (~70k dominios)
• 2000: PHP4; Zend Engine
• 2004: PHP5; Zend Engine 2.0
• OO muy mejorado, excepciones, mejor manejo de memoria
• (~100M dominios)
• 2009: PHP5.3
• Espacios de nombres, clausuras, recolección basura ref. circulares
• 2012: PHP5.4
• 'rasgos' (traits)
Tema 3.1. PHP 10
El servidor procesa código PHP en ficheros con extensión
.php
• Un fichero .php puede contener texto de varios tipos:
• Código PHP
• Código HTML
• Código JavaScript
El código PHP normalmente está embebido en texto
HTML
• <?php ... ?>
• Sintaxis recomendada
• <? ... ?>
• Short tags: Requiere que esté habilitada con la propiedad
short_open_tag on en el fichero de configuración php.ini
Tema 3.1. PHP 11
holamundo.php
<html lang=“es”>
<head>
<meta charset=“utf-8”>
<title>Hola Mundo PHP</title>
</head>
<body>
<?php echo '<h1>Hola Mundo PHP</h1>'; ?>
</body>
</html>
intérprete PHP
GET holamundo.php
<html lang=“es”>
<head>
<meta charset=“utf-8”>
<title>Hola Mundo PHP</title>
</head>
<body>
<h1>Hola Mundo PHP</h1>
</body>
</html>
Tema 3.1. PHP
12
Indistintamente con echo o print
• void echo(string argument1 [, ...string argumentN])
• int print(argument)
• Son similares salvo que print…
• Sólo tiene un argumento (echo puede tener varios)
• print devuelve 1 (significa que ha generado la salida)
$usuario = "Juan";
echo "<p>Bienvenido $usuario</p>";
print "<p>Bienvenido $usuario</p>";
print("<p>Bienvenido $usuario</p>");
<p>Bienvenido Juan</p><p>Bienvenido Juan</p><p>Bienvenido Juan</p>
Tema 3.1. PHP
13
• Permite escribir varias líneas con saltos de línea e
inclusión de variables (heredoc).
• Evita tener que utilizar echo.
• Ejemplo:
$contenidoPrincipal = <<<EOS
<h1>Ejemplo de página</h1>
<p>Esto es un párrafo</p>
EOS;
$contenidoPrincipal = <<<‘EOS’
<h1>Ejemplo de página</h1>
<p>Esto es un párrafo</p>
EOS;
Tema 3.1. PHP 14
Cuando hay varios argumentos a los que se les quiere aplicar un formato:
printf
• integer printf(string format [, mixed args])
• Tipos:
• %b número binario
• %c carácter ASCII
• %d número entero
• %f número en coma flotante
• %o número en octal
• %s string
• %u número decimal sin signo
• %x hexadecimal
printf("%d kilos de caramelos cuestan %.2f euros", 3, 27.90);
3 kilos de caramelos cuestan 27.90 euros
sprintf() hace lo mismo pero genera un string que se puede asignar a una
variable.
Tema 3.1. PHP
15
Igual que C / C++ / Java
• Comentarios:
• // /* */ # (nuevo; equivale a //)
• Bloques y fin de línea:
• { ... }
• ;
• Operadores:
• + - * / . % && || ! ^ =
• ‘.’ es nuevo, significa “concatenar”
• Control:
• for, while, do/while , if/then/else,
• switch/case/default
• PHP distingue mayúsculas y minúsculas
Tema 3.1. PHP 16
Tema 3.1. PHP 17
Tipos de datos (el tipo sólo se usa en 'casts')
• int/integer
• float/real/double
Tipos escalares
• bool/boolean
• string (usando ' ' ó " " )
• array
• object
• null
Tema 3.1. PHP 18
Tipado dinámico
• No hace falta decir de qué tipo son las variables.
• Es posible cambiar tipos sobre la marcha.
Conversiones automáticas
• cadena -> número (si se la trata como número).
• número -> cadena (si se le concatena con '.' a una cadena).
• cualquier valor -> booleano (si se usa en una expresión booleana).
$total = "1"; $total = $total + 1; $total = $total . "uno"; $total = “2”;
$total = $total / 2; // (una cadena)
// total = 2 (un número)
// total = "2uno" (una cadena)
// total = 1 (un número)
Conversiones explícitas tipo C / Java:
$total = "1";
$entero = (int) total;
$flotante = (float) total;
Tema 3.1. PHP 19
== y != aplican conversiones
$a = 1
$b = "1"
a == b; // true
a != b; // false
=== y !== no aplican conversiones
$a = 1
$b = "1"
a === b; // false
a !== b; // true
Tema 3.1. PHP 20
bool/boolean
• FALSE, false: 0, 0.0, "", "0", array de 0 elementos, NULL y variables sin inicializar.
• TRUE, true: cualquier otro valor.
integer
• Representados en base 10 (decimal), 8 (octal) o 16 (hexadecimal).
• En las versiones recientes se guardan con 64 bits.
• Si al evaluar una expresión sobrepasa el valor máximo (PHP_INT_MAX), se
convierte a float.
float
• Números reales (no hay diferencia entre float y double).
$usuario = "Juan";
$activo = true;
$activo = 1; // true
$octal = 0623;
$hexadecimal = 0xF4;
$cuenta = 33;
$saldo = 4534.32;
$saldo = 4.53432e3
Tema 3.1. PHP
21
String
• Cadenas de caracteres ASCII, entre comillas simples o dobles.
• Para trabajar con Unicode se usan las funciones
• string utf8_encode(string $data)
• string utf8_decode(string $data)
• Se puede acceder a los caracteres de un String como en un array
• Con {} en lugar de con [].
• El primer carácter es el del índice 0.
• string substr ( string $string , int $start [, int $length ] )
• Devuelve un string desde la posición $start y longitud $length.
• (ver documentación para más detalles)
$texto = "Bienvenido";
echo $texto{3};
echo $texto{0};
echo substr($texto, 0, 4);
// n
// B
// Bien
Tema 3.1. PHP 22
String (con comillas dobles)
• Sustitución de variables en strings
• Al encontrar una variable (un $) se sustituye por su valor.
• Se puede encerrar la variable o su nombre con {}.
• Interpretación de secuencias de escape
• \n Salto de línea \t Tabulador horizontal
• \\ Barra \$ Signo de dólar
• \" Comillas dobles \' Comilla simple
$texto = "Bienvenido";
$nombre = "Juan";
echo "$texto, ${nombre}."; // Bienvenido, Juan.
Tema 3.1. PHP
23
String (con comillas simples)
• No sustituyen las variables por su valor.
• Ni siguen las secuencias de escape
• Sólo valen \' y \\
echo 'String de comillas simples'; // String de comillas simples
echo 'Se puede
poner con
varias líneas'; // En el HTML los saltos de línea no se interpretarán, pero están
echo 'Juan said: "I\'ll be back"'; // Juan said: "I'll be back"
echo 'Ficheros C:\\xampp\\htdocs\\*.html '; // Ficheros C:\xampp\htdocs\*.html
echo 'Ficheros C:\xampp\htdocs\*.html'; // Ficheros C:\xampp\htdocs\*.html
echo 'No salta l&iacute;nea con \n. Se imprime igual que \\n';
// No salta línea con \n. Se imprime igual que \n
echo 'Las $variables no se $interpretan.'; // Las $variables no se $interpretan.
Tema 3.1. PHP
24
String
• Operador de concatenación . (punto)
$texto = "Bienvenido";
$nombre = "Juan";
echo $texto . ", " . $nombre . " "; // Bienvenido, Juan.
• Asignación y concatenación .=
$texto = "Bienvenido";
$texto .= ", Juan.";
echo $texto; // Bienvenido, Juan.
• Más funciones sobre strings:
• http://www.php.net/manual/en/ref.strings.php
Tema 3.1. PHP
25
Tema 3.1. PHP 26
Arrays asociativos: secuencias de pares (clave, valor)
• Clave: entero o string
• Valor: cualquier cosa
Se crean con la función array y una secuencia de pares
clave=>valor separados por comas
• array( clave1=> valor, clave2 => valor2, clave3 => valor3, ... )
• Desde PHP 5.4 se pueden usar corchetes [] en vez del nombre de
la función y paréntesis ()
$array = array(
"uno" => 1,
"dos" => 2,
$array = array(
1 => "a",
"1" => "b",
1.5 => "c",
true => "dos",
);
// desde PHP 5.4
$array = [
"uno" => 1,
"dos" => 2,
);
];
Tema 3.1. PHP
27
• La clave puede ser un integer o un string. Si no lo es, se
transforma:
• string a integer (si contiene un integer válido)
• La clave "8" en realidad será almacenada como 8.
• La clave "08" no será convertida, ya que no es un número integer decimal válido.
• float a integer (la parte decimal se elimina)
• La clave 8.7 en realidad será almacenada como 8.
• booleano a integer (true será 1 y false será 0)
• null a string vacío (la clave null será almacenada como "").
• Los arrays y los objetos no pueden utilizarse como claves
• Si se hace, dará lugar a una advertencia: Illegal offset type
• Si varios elementos en la declaración del array usan la misma clave,
sólo se utilizará la última. Los demás se sobreescriben.
• El valor puede ser de cualquier tipo.
Tema 3.1. PHP 28
$array = array(
"uno" => 1,
"dos" => 2,
);
// desde PHP 5.4
$array = [
"uno" => 1,
"dos" => 2,
];
var_dump($array);
$array = array(
1 => "a",
"1" => "b",
1.5 => "c",
true => "dos",
);
var_dump($array);
array(2) { ["uno"]=> int(1), ["dos"]=> int(2) }
var_dump: muestra información sobre una variable.
array(1) { [1]=> string(3) "dos" }
Tema 3.1. PHP
29
Acceso a los elementos: array[clave] o array{clave}
$array = array(
"uno" => "hola",
42 => 24
);
var_dump($array["uno"]);
var_dump($array[42]);
string(4) "hola"
int(24)
Tema 3.1. PHP
30
Flexibilidad extrema
• Que se pueda hacer cualquier cosa con un array, no significa
que debamos.
$array = array(
"uno" => "hola",
42 => 24,
"multi" => array(
"dimensional" => array(
)
"array" => "bravo"
)
);
Tema 3.1. PHP
31
Modificación de los elementos de un array
• $arr[clave] = valor; //Si clave no existe, la añade al final
• $arr[] = valor; //Coloca valor en la “siguiente” posición libre
Si $arr no existe, se creará.
Para eliminar un par (clave, valor), utilizar la función unset().
$arr = array(1 => "uno", 4=> "cuatro");
$arr[] = 22; // $arr[5] = 22;
$arr["x"] = 33; // Nuevo elemento con clave "x"
unset($arr[5]); // Elimina el elemento del array
unset($arr); // Borra todo el array
Tema 3.1. PHP
32
Arrays multidimensionales: arrays de arrays
$miArray = array ( "frutas" => array ("a" => "albaricoque",
"c" => "coco",
"m" => "manzana",
"n" => "naranja" ),
"numeros" => array ( 1, 2, 3, 4, 5),
);
// Ejemplos de uso:
echo $miArray["frutas"]["c"];
echo $miArray["numeros"][3];
unset($miArray["frutas"]["a"]);
echo $miArray["frutas"]["a"];
// "coco"
// 4
// elimina "albaricoque"
// Notice: Undefined index
Ejemplo adaptado del Manual de PHP: http://www.php.net/manual/es/language.types.array.php
Tema 3.1. PHP
33
Tema 3.1. PHP 34
Instrucciones condicionales
if (condición) {
// Instrucciones
}
elseif (condición) {
// Instrucciones
}
// otros elseif ...
else {
// Instrucciones
}
Tema 3.1. PHP 35
• Operador ternario: ?
<?php
if (isset($_POST[‘nombre’])){
$nombre = $_POST[‘nombre’];
} else {
$nombre = ‘ninguno’;
}
// Es equivalente a
$nombre = isset($_POST[‘nombre’]) ? $_POST[‘nombre’] :
‘ninguno’;
Tema 3.1. PHP 36
• Operador null coalescing: ??
<?php
if (isset($_POST[‘nombre’])){
$nombre = $_POST[‘nombre’];
} else {
$nombre = ‘ninguno’;
}
// Es equivalente a
$nombre = $_POST[‘nombre’] ?? ‘ninguno’;
Tema 3.1. PHP 37
• Operador Elvis: ?:
<?php
if (!empty($_POST[‘nombre’])){
$nombre = $_POST[‘nombre’];
} else {
$nombre = ‘ninguno’;
}
// Es equivalente a
$nombre = $_POST[‘nombre’] ?: ‘ninguno’;
Tema 3.1. PHP 38
Instrucciones condicionales
switch (expresión) { // debe ser un tipo escalar (int,float,bool,string)
case valor1:
// Instrucciones caso 1
break; // para acabar el switch
case valor2:
// Instrucciones caso 2
break;
// otros case ...
default: // opcional
// Instrucciones si no se diera ningún caso
}
Tema 3.1. PHP 39
Bucles
• While
while( condición ){
// Instrucciones
}
• For
for ( inicialización; condición; actualización) {
// Instrucciones
}
Tema 3.1. PHP 40
Excepciones: try..catch
• Similar a Java pero sin finally
try {
// Código a ejecutar
}
catch(Exception $e) {
// Gestión de la excepción $e, por ejemplo:
echo($e->getMessage());
}
• Se puede lanzar una excepción con throw.
Tema 3.1. PHP 41
Recorrer un array: foreach
• En cada iteración, el valor del elemento actual se asigna a $valor
y el puntero interno del array avanza una posición
foreach ($array as $valor)
sentencias
• Lo mismo, pero recorriendo también las claves:
foreach ($array as $clave => $valor)
sentencias
<?php
$arr= array("uno", "dos", "tres");
foreach ($arr as $valor)
echo "$valor <br />";
foreach ($arr as $clave => $valor) {
echo "$clave => $valor <br />";
}
?>
Tema 3.1. PHP
42
Tema 3.1. PHP 43
Se definen por su nombre y parámetros
function nombre($par1 [=valor1], ..., $parN [=valorN]) {
// cuerpo de la función
}
La función se invoca con su nombre y argumentos
nombre($arg1, ..., $argn);
Más detalles
• El nombre de la función no es sensible a mayúsculas/minúsculas.
• Las funciones no se pueden sobrecargar.
• La función puede devolver un valor con return.
• Las funciones se pueden anidar.
• Pueden realizarse llamadas recursivas.
Tema 3.1. PHP 44
Los parámetros se pueden pasar
• Por valor
• Se hace una copia del argumento que se pasa a la función
• Los cambios realizados no son visibles fuera de la función
• Por referencia (indicando & delante del parámetro)
• En este caso los cambios al parámetro sí afectan a la variable que
se pasa por referencia
<?php
{
function aniadir_algo(&$cadena)
$cadena .= 'y algo más.';
}
$cad = 'Esto es una cadena, ';
aniadir_algo($cad);
echo $cad; // imprime 'Esto es una cadena, y algo más.'
?>
Tema 3.1. PHP 45
Lista de argumentos variable
• func_num_args(): número de argumentos que se han pasado.
• func_get_arg(n): n-ésimo argumento que se ha pasado a la
función.
• Si n>func_num_args(), devuelve false
• func_get_args(): array de parámetros que se han pasado a la
función.
Tema 3.1. PHP 46
¿Número variable de argumentos?
<?php
{
function foo()
$numargs = func_num_args();
echo "Numero de argumentos: $numargs\n";
}
foo(1, 2, 3);
?>
Ejemplo adaptado de http://www.php.net/manual/en/function.func-num-args.php
En resumen:
Es muy fácil programar mal en PHP
Tema 3.1. PHP
47
Ámbito de variables
• Local
• Una variable definida en una función está limitada a dicha
función.
• Se elimina al acabar la ejecución de la función.
• Global
• Una variable definida fuera de una función.
• Se pueden definir en una parte y usarse en otra.
• Existen durante todo el tiempo de proceso del fichero
• Al acabar de procesar la página se eliminan las variables globales.
• Nuevas ejecuciones definen nuevas variables globales.
Tema 3.1. PHP 48
Uso de variables globales
• Requiere reactivar las variables dentro de las funciones
• Si no, no se consideran declaradas dentro de la función
<?php
$a = 1;
$b = 2;
function suma()
{
global $a, $b; //OJO! Importante!
$b = $a + $b;
}
suma();
echo $b;
?>
Tema 3.1. PHP
49
Variables globales que se pueden acceder, desde
cualquier parte, funciones inclusive, sin usar 'global'.
nombre uso
$GLOBALS Acceso a todas las globales definidas
$_SERVER Entorno de llamada (arg, argc, REMOTE_ADDR, ...)
$_GET Parámetros pasados vía GET
$_POST Parámetros pasados vía POST
$_FILES Ficheros que te han subido vía POST
$_COOKIE Cookies devueltas por el navegador
$_SESSION Variables de sesión
$_REQUEST $_POST + $_GET + $_COOKIE
$_ENV Acceso a variables de entorno
Tema 3.1. PHP 50
Valores de tipos escalares
Se declaran con la función define
• define ('CONSTANTE', 'valor');
• El nombre de una constante no puede empezar por $
• Normalmente se escriben con mayúsculas
El ámbito de una constante es el script en el que está definida
• Si se declara en una primera sección de código se puede usar luego.
<?php
// Constantes
define('AUTOR', 'Juan');
?>
<html>
<head>
</head>
<body>
</body>
</html>
Ojo con el uso de
las comillas
<title>Ejemplo de uso de constantes</title>
<p>Hola <?php echo AUTOR; ?></p>
Tema 3.1. PHP
51
"Magical" PHP constants
• __LINE__
• Número de la línea de la instrucción que se está ejecutando.
• __FILE__
• Ruta y nombre del fichero.
• Si se usa en un include, se devuelve el nombre del fichero incluido.
• __DIR__
• Directorio del fichero.
• __FUNCTION__
• Nombre de la función.
• __CLASS__
• Nombre de la clase, incluye el namespace en el que está declarada.
• __TRAIT__
• Nombre de un trait (similar a una clase, se verá más adelante).
• __METHOD__
• Nombre del método.
• __NAMESPACE__
• Espacio de nombres.
Tema 3.1. PHP 52
Tema 3.1. PHP 53
void var_dump(mixed $variable)
• Muestra información (tipo, valor) de la variable
print_r(mixed $variable)
• Imprime información legible sobre una variable
bool empty (mixed $var)
• FALSE si la variable existe y tiene un valor no vacío (0, “”, etc.)
• TRUE si en caso contrario
bool isset (mixed $var [, mixed $... ])
• Determina si la variable tiene un valor y no es NULL
string strval (mixed $var)
• Obtiene el valor de la variable como string
Tema 3.1. PHP 54
Serialización
• string serialize (mixed $value)
• Genera una representación almacenable de un valor
• unserialize ( string $str )
• Operación contraria
Tema 3.1. PHP 55
Se pueden incluir otros ficheros con funciones de
inclusión PHP:
• require('/directorio/fichero');
• include('/directorio/fichero');
• require_once('/directorio/fichero');
• include_once('/directorio/fichero');
<html>
<?php
?>
<body>
require ('cabecera.php');
</body>
</html>
<p>La cabecera de este documento la ha generado un programa PHP. </p>
Tema 3.1. PHP 56
Diferencias:
• require si se produce un error no se sigue ejecutando el script.
• include solo produce un warning.
• include_once y require_once para incluir sólo una vez.
El directorio desde donde se buscan los includes se
define en la directiva include_path del fichero php.ini
Tema 3.1. PHP 57
Averiguar el tipo de una variable
• is_string(), is_object(), is_float(), is_null(), ...
Mostrar el contenido de la variable, de forma recursiva
• var_dump(…) / var_export(…)
Ficheros de log
• /var/log/apache2/error.log: errores
• /var/log/apache2/access.log: accesos
En XAMPP
• xampp/apache/logs
En un servidor “en producción” PHP no debería mostrar los errores de
ejecución. Siempre se deben mirar en el registro de errores.
Tema 3.1. PHP 58
http://www.php.net/manual/
Tema 3.1. PHP 59
Licencia Creative Commons
• Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo
Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros y Javier
Bravo Agapito
Tema 3.1. PHP
60


3
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Juan Pavón Mestras, con modificaciones
Material elaborado por Juan Pavón Mestras, con modificaciones de
de Pablo Moreno Ger, Manuel Freire Morán, Raquel Hervás
Pablo Moreno Ger, Manuel Freire Morán y Raquel Hervás Ballesteros.
Ballesteros y Javier Bravo Agapito
• Recordatorio: protocolo HTTP, formularios.
• Tratamiento de parámetros en PHP.
• $_REQUEST, $_GET, $_POST
• Seguridad en las entradas.
• Strip_tags, htmlspecialchars, trim
• Ficheros en formularios.
• Uso de cookies en PHP.
• $_COOKIE, setcookie
• Manejo de sesiones.
• $_SESSION, session_start(), session_destroy()
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 2
El navegador (cliente, user agent) solicita un recurso
(página HTML, imagen, video, etc.) a un servidor
• Solicitud: método que se utiliza GET, POST, PUT, etc.
• Campos de cabecera.
• Línea en blanco.
• Cuerpo del mensaje (texto): puede llevar parámetros del
formulario.
Cliente
(Navegador)
y
conexión
1
solicitud (GET/POST, ...)
2
respuesta
cierre
3
80
Servidor
x
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
3
Atributos de <form>
• action="URL": aplicación del servidor que procesará los datos
remitidos (por ejemplo, un script de PHP).
• method: método HTTP para enviar los datos al servidor.
• GET: como añadido a la dirección indicada en el atributo
action
• Limitado a 500 bytes.
• Los datos enviados se añaden al final de la URL de la página y por tanto se
ven en la barra del navegador.
• POST: en forma separada
• Puede enviar más información.
• Permite enviar ficheros adjuntos.
• Los datos enviados no se ven en la barra del navegador.
• Se suele usar cuando se envía información que puede modificar el
servidor.
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 4
• POST vs GET: ¿estás cambiando algo de forma irreversible?
Un botón de 'refrescar listado' puede enviar 'GET';
uno de 'borrar archivo' (irreversible) sólo debe enviar 'POST'.
POST /pagina-destino HTTP/1.1
… resto de cabeceras …
Connection: keep-alive
Content-Type: application/x-www-form-
urlencoded
Content-Length: 56
nombre=escribe+tu+nombre&apellidos=escribe+t
us+apellidos
GET /pagina-destino?nombre=escribe+tu+nombre&apellidos=escribe+tus+apellidos HTTP/1.1
… resto de cabeceras …
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 5
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 6
El valor de los parámetros se recibe en $_REQUEST
• $_REQUEST ["nombre"]
• nombrees el que en el formulario se indica con el atributo
name.
Se pueden usar igualmente las siguientes variables
superglobales
• $_GET ["nombre"]
• $_POST ["nombre"]
MEJOR UTILIZAR _GET, _POST ó _COOKIE PARA SABER
EXACTAMENTE QUÉ TIPO DE PETICIÓN NOS ESTÁ LLEGANDO Y
EVITAR PROBLEMAS DE SEGURIDAD
ó tienes que asegurarte del método de la petición utilizado
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 7
http://localhost/CursoPHP/hola.html
hola.html
<p>Por favor, indique su nombre:
<form method="get" action="procesaform.php">
Nombre:
<input type="text" name="cliente" />
<input type="submit" value="Enviar">
</form>
</p>
procesaform.php
<?php
$cliente = $_GET["cliente"];
echo "Hola $cliente";
?>
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
8
http://localhost/CursoPHP/hola.html
hola.html
<p>Por favor, indique su nombre:
<form method="post" action="procesaform.php">
Nombre:
<input type="text" name="cliente" />
<input type="submit" value="Enviar">
</form>
</p>
procesaform.php
<?php
$cliente = $_POST["cliente"];
echo "Hola $cliente";
?>
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
9
Crear una página HTML que genere el siguiente
formulario y un script PHP que lo procese:
• Un campo de texto para preguntar el nombre.
• Un campo radio button para seleccionar el sexo.
• Un campo checkbox para seleccionar lenguajes de
programación.
• Al hacer submit se envían los datos al servidor con POST y el
servidor devuelve una página con un texto que muestra los
datos recopilados.
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 10
Conviene comprobar que no llegue código con < y >
Podría ocasionar efectos inesperados
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 11
Para evitarlo se usa una función que elimine < y >
• strip_tags(string)
• Retira las etiquetas HTML y PHP de un string
• htmlspecialchars(string)
• Convierte caracteres especiales en entidades HTML
• &  &amp;
• " (comillas dobles)  &quot;
• ' (comilla simple)  &#039;
• <  '&lt;'
• >  '&gt;'
• También conviene quitar los espacios al principio
• trim(string)
• Elimina los espacios en blanco iniciales y finales del string
$cliente=htmlspecialchars(trim(strip_tags($_REQUEST["cliente"])));
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
12
Incluir un fichero
• El atributo enctype en la etiqueta <form> del formulario tiene que ser
multipart/form-data
<form name="fichero" action="procesa_fichero.php" method="post"
enctype="multipart/form-data">
<input type=“hidden” name=“MAX_FILE_SIZE” value=“30000” />
Fichero: <input type="file" name="archivo" />
<input type="submit" value="Enviar">
</form>
Los ficheros recibidos se pueden gestionar con $_FILE[]
• $_FILES['archivo']['name'] Nombre del fichero en el cliente
• $_FILES['archivo']['type'] Tipo MIME del fichero
• $_FILES['archivo']['size'] Tamaño, en bytes, del fichero
• $_FILES['archivo']['tmp_name'] Ubicación del archivo en el servidor
• move_uploaded_file(nombreArchivo, destino) Copia el archivo a destino
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
13
¿Y por qué tan complicado?
• Los nombres de archivo pueden ser un riesgo.
• Nunca permitimos al cliente decidir dónde guardar el archivo.
Pasos:
1. 2. 3. 4. 5. PHP crea un archivo temporal en una ubicación segura.
Comprobamos que todo esté en orden.
Decidimos qué nombre poner al archivo (por ejemplo, un id).
Copiamos el archivo al directorio que queramos, con el
nombre que queramos.
PHP descarta el archivo temporal una vez terminado el script.
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 14
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 15
HTTP es un protocolo SIN ESTADO
• No se guarda información de la sesión/historia pasada.
• Cada petición se trata de forma separada.
• Esto simplifica el protocolo
• Pero dificulta programar aplicaciones
Es necesario gestionar sesiones para recordar datos entre
dos peticiones HTTP del mismo usuario
• Pero no tenemos forma de saber si es el mismo usuario.
• Usuarios que comparten IP
• Usuarios que cambian de IP
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 16
Funcionamiento del mecanismo de cookies
crea cookie respuesta HTTP
navegador
petición HTTP
servidor Tema 3.2. Tratamiento de parámetros, cookies, sesiones
Memoria/
Disco
17
Atributos
• Par (Clave, Valor).
• Comentario (se puede presentar al usuario).
• Interesante para explicar para qué se usa el cookie (política
del sitio web)
• Especificación de las páginas y dominios a los que se puede
enviar la cookie.
• Fecha y hora de expiración.
• Permite controlar por ejemplo el tiempo máximo de una
sesión antes de volver a pedir login.
• Requiere o no una página segura.
• Versión.
AW: Internet, protocolos y tecnologías Web 18
Tamaño máximo:
• 4 Kbytes.
• Normalmente ocupan alrededor de 100 bytes
Seguridad
• Los navegadores sólo las envían al dominio especificado.
• No conviene poner información sensible en la cookie, mejor
utilizar un identificador en la cookie que sirva de clave de
acceso en la base de datos del servidor.
AW: Internet, protocolos y tecnologías Web 19
Uso de las cookies
• Recordar al usuario entre dos peticiones.
• Por ejemplo, durante una sesión de navegación.
• Guardar las preferencias del usuario.
• Reconocimiento de usuarios antiguos.
• Ayuda a recoger datos usados por aplicaciones de compra electrónica.
Cuidado con la seguridad y la privacidad
• Nuevo atributo SameSite (Febrero 2020 en Chrome) para permitir
tener mayor control con las cookies y los sitios de terceros.
• https://webmasters.googleblog.com/2020/01/get-ready-for-new-
samesitenone-secure.html
• https://web.dev/samesite-cookies-explained/
AW: Internet, protocolos y tecnologías Web 20
setcookie($nombre, $valor, $tvida, $ruta, $dominio, $seguridad)
• Las cookies tienen un $nombre y un $valor
• El nombre no debe coincidir con el de un control de formulario porque en
$_REQUEST se guardaría solo el valor del cookie, no el del control.
• Se puede indicar un tiempo de vida de la cookie
• Si no se indica, la cookie se elimina al cerrar el navegador.
• El tiempo se indica como tiempo Unix, esto es, el número de segundos
desde el 1 de Enero de 1970.
• $ruta y $dominio determinan páginas y dominios a los que se puede enviar
la cookie.
• $seguridad indica si se mandará la cookie únicamente en conexiones seguras
https (TRUE) o indistintamente (FALSE).
$cookie1="nombre";
$valor1="Juan";
$tvida=time()+3600*24; // expira en 24 horas
setcookie($cookie1, $valor1, $tvida, ".dominio.com");
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
21
setcookie($nombre, $valor, $tvida, $ruta, $dominio, $seguridad)
• El método se tiene que llamar antes de cualquier sentencia echo o print
• A partir del primer echo, el servidor cierra la cabecera y empieza a enviar
código HTML.
¡Atención!
Es un fallo de programación en
PHP muy habitual
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 22
Consulta de cookies
• El navegador reenviará las cookies en peticiones posteriores
• Uso de $_COOKIE[] para recuperar los datos
<html>
<head>
</head>
<body>
<?php
<title>Hola Cookie</title>
?>
</body>
</html>
echo "<h1>Hola " . $_COOKIE[“nombre”] . "</h1>";
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
23
Modificación del valor de una cookie
• Basta con crear nuevamente la cookie con otro valor.
Borrado de una cookie
• Se consigue creando la cookie con un tiempo de expiración del
pasado:
• setcookie("nombre", "valor", time()-60);
Uso de una cookie
• Consultando su existencia en la superglobal $_COOKIE.
• Conviene comprobar antes que se haya recibido.
if (isset($_COOKIE["nombre"])) {
echo "<p>El valor de la cookie es ".$_COOKIE["nombre"]."</p>";
} else {
echo "<p>No se ha recibido la cookie nombre.</p>";
}
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
24
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 25
Una sesión determina un contexto que relaciona las acciones
del cliente sobre un sitio web
• Normalmente las variables son destruidas cuando acaba la ejecución
de una página PHP.
• A veces es necesario guardar cierta información entre una página y
otra durante la navegación de un cliente.
Las sesiones tienen un ciclo de vida:
• Inicio de sesión
• Login de usuario.
• Actividad del usuario
• Flujo lógico de operaciones de consulta/modificación de
información.
• Cierre de sesión
• Explícito por el usuario.
• Por expiración de un tiempo de inactividad.
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 26
Para gestionar las sesiones sobre HTTP (protocolo sin estado) se
podrían usar varios mecanismos
• Una cookie: PHPSESSID
• Cuando se inicia una sesión en una página, el intérprete PHP comprueba
la presencia de este cookie y la establece si no existe.
• El identificador de sesión en la cookie PHPSESSID permite identificar
unívocamente ese cliente en el servidor.
• Variables de identificación de sesión
• Normalmente el usuario navega de una página a otra del mismo sitio.
• Se podría crear un identificador único al visitar la primera página si no
existiera y pasarlo en las siguientes páginas.
<a href="siguiente.php?sesion=<?php echo $_GET['id_sesion'];?>">Siguiente página</a>
<form action=“siguiente.php” method=“post”>
Campos del formulario
<input type=“hidden” name=“sesion” value="<?php echo $_GET['id_sesion'];?>" />
</form>
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
27
PHP ofrece un mecanismo de gestión de sesiones que
abstrae al programador de cuál de estos mecanismos se
utilice
• Normalmente usa cookies si el navegador lo permite, y si no el
identificador de sesión en GET y POST.
• Las variables de la sesión se guardan en un fichero temporal en
el servidor con el nombre del identificador.
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 28
Gestión de sesiones en PHP
• Iniciar una nueva sesión: session_start();
• Se tiene que invocar antes de escribir cualquier cosa con
echo o print.
• Uso de la variable superglobal $_SESSION
• Todas las variables de la sesión se incluirán y se pueden
acceder, entre página y página de una misma sesión, en el
array $_SESSION.
• Siempre se tiene que haber invocado antes session_start() al
principio de la página (así PHP prepara las variables
correspondientes a la sesión).
• Cerrar sesión: session_destroy();
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 29
<?php session_start(); ?>
<html>
<body>
<head><title>Ejemplo de sesiones PHP</title></head>
<h1>Ejemplo de sesiones con PHP</h1>
<?php
if (!isset($_SESSION['contador'])) {
$_SESSION['contador']=1;
echo "<p>Bienvenido por primera vez</p>";
}
else {
$_SESSION['contador']++;
echo "<p>Ya nos has visitado ".$_SESSION['contador']." veces.</p>";
}
?>
</body>
</html>
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
30
Probar a crear dos páginas distintas para una misma sesión
• En la primera crear la sesión
primera.php
<?php
session_start();
$_SESSION[‘nombre’] = "Javier";
print "<p>Se ha guardado tu nombre.</p>";
?>
• En la segunda usar alguna variable de la sesión creada
segunda.php
<?php
session_start();
print "<p>Hola $_SESSION[‘nombre’], vemos que sigues por aquí</p>";
?>
Tema 3.2. Tratamiento de parámetros, cookies, sesiones
31
Una sesión se puede destruir con la función
session_destroy()
• Si no se destruyen, los datos de una sesión en $_SESSION se
guardan durante un tiempo predeterminado de 24 minutos.
• ini_set(string varConfig, valor) permite modificar ese valor
(session.gc_maxlifetime)
• Tiene que invocarse antes de session_start()
• Cambia el php.ini
• Los valores de $_SESSION se pueden borrar también como en
cualquier otro array mediante la función unset().
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 32
Añadir una variable a la sesión:
$_SESSION["nombre"] = “Javier";
• Registra la variable en la sesión.
• Las asignaciones a esa variable se mantendrán en futuras
invocaciones dentro de la sesión.
• Se puede comprobar si la variable existe con isset().
• Se puede eliminar la variable con unset().
session_id() / session_id(string $id)
• Devuelve el identificador de la sesión.
• Establece el id de sesión (necesita ser llamado antes de
session_start().
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 33
Crea una secuencia de páginas que soliciten información
sobre un usuario.
• En la primera página su nombre, en la segunda su número de
teléfono y en la tercera su email. En la cuarta se mostrarán
todos los datos recibidos.
• Prueba a acceder a la vez desde dos navegadores distintos para
comprobar que se pueden gestionar dos sesiones diferentes a la
vez.
Este ejercicio es muy
importante, no dejes de hacerlo
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 34
Guardar datos sobre el usuario
• Tipos de datos
• Valores temporales que no se vayan a usar después de la sesión.
• Valores no sensibles (e.g. no tarjetas de crédito, contraseñas, etc.).
• Valores muy complejos que se puedan serializar (i.e. guardar como
strings).
• Ejemplos:
• Carro de la compra (asumiendo que no queramos mantenerlo
entre sesiones).
• Valores intermedios de un formulario en varias páginas.
• Traza de las páginas visitadas.
Para todo lo demás:
• Es preferible guardar los datos grandes e importantes en la capa de
persistencia
• Habitualmente, en una base de datos.
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 35
Licencia Creative Commons
• Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo
Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros y Javier
Bravo Agapito
Tema 3.2. Tratamiento de parámetros, cookies, sesiones 36


3
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Juan Pavón Mestras, con modificaciones de
Material elaborado por Juan Pavón Mestras, con modificaciones de
Pablo Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros
Pablo Moreno Ger, Manuel Freire Morán y Raquel Hervás Ballesteros
y Javier Bravo Agapito
• POO.
• Objetos en PHP.
• Clases en PHP, constructores.
• Herencia.
• Interfaces.
• Clases abstractas.
• Traits.
• Métodos mágicos y constructores.
Tema 3.3. Objetos en PHP 2
Tema 3.3. Objetos en PHP 3
Seguimos el modelo de objetos de Java o C++
• Un objeto combina una estructura de datos junto con las
operaciones que se pueden realizar sobre ella.
• Soporta ocultación de detalles de implementación vía 'public',
'private', etcétera.
• Usaremos los conceptos habituales:
• Clases e instancias
• Interfaces
• Polimorfismo
• Abstracción
• Vinculación dinámica
Tema 3.3. Objetos en PHP
4
Desde PHP 5 se ha mejorado el modelo de objetos
• Clases
• Traits (¡esto es nuevo!)
• Interfaces
Los objetos se crean con el operador new
• Las variables de objetos son referencias a los objetos (no copias
de los objetos).
• Las asignaciones de variables de referencia a objetos copian
referencias, no los objetos.
• Si se quiere una copia del objeto hay que clonar el objeto:
• $copia_de_objeto = clone $objeto;
Tema 3.3. Objetos en PHP 5
Se definen con class seguida del nombre de clase
• Un nombre válido PHP que no sea una palabra reservada
Propiedades
• Constantes
• Variables
Métodos
• Funciones
• Constructor
• Destructor
__
__
construct()
destruct()
Tema 3.3. Objetos en PHP 6
<?php
class Saludo {
// atributos
private $bienvenida = 'hola';
// constantes
const UNACONSTANTE = 'un valor constante';
// métodos
public function muestraBienvenida() {
echo $this->bienvenida;
}
}
$saludo = new Saludo(); // crea uno nuevo
$otro = $saludo; // ambos se refieren al mismo
$saludo->muestraBienvenida(); // funciona
$saludo->bienvenida; // error -- es privado
?>
Fuente: https://www.php.net/manual/es/language.oop5.basic.php
Tema 3.3. Objetos en PHP
7
<?php
class Fruit {
// Propiedades
public $name;
//Metodos
public function set_name($name) {
$this->name = $name;
}
public function get_name() {
return $this->name;
}
}
$apple = new Fruit();
$banana = new Fruit();
$apple->set_name('Apple');
$banana->set_name('Banana');
echo '<div>'. $apple->get_name() . '</div>';
echo '<div>' . $banana->get_name() . '<div>’;¡
?>
Fuente: https://www.w3schools.com/php/
Tema 3.3. Objetos en PHP 8
<?php
class Fruit {
// Propiedades
public $name;
public $color;
//Constructor
function __construct($name, $color) {
$this->name = $name;
$this->color = $color;
}
//Metodos
function get_name() {
return $this->name;
}
function get_color() {
return $this->color;
}
}
$apple = new Fruit("Red Chief", "red");
echo '<div>'. $apple->get_name() . '</div>';
echo '<div>' . $apple->get_color() . '<div>';
?>
Fuente: https://www.w3schools.com/php/
Tema 3.3. Objetos en PHP 9
Una clase puede tener métodos o atributos estáticos
• Declarados con un 'static' delante.
• Sin crear una instancia de la clase.
• Los objetos estáticos no pueden acceder a $this.
Llamando a métodos estáticos
• miObjeto->metodoEstatico() funciona con warning (desde PHP5.3)
• MiClase::metodoEstatico() preferido (al igual que en C++/Java)
Tema 3.3. Objetos en PHP
10
Sólo para atributos y métodos (las constantes son siempre
públicas)
Similar a C++ / Java:
• public siempre accesible, desde cualquier contexto
(se asume 'public' si no se especifica nada).
• protected sólo desde la clase o subclases de la misma.
• private sólo desde la clase.
El ‘estilo’ PHP
• Mucho más frecuente dejar cosas públicas que en otros lenguajes.
• Aun así, se recomienda:
• Especificar siempre la visibilidad para cada método o función.
• Usar siempre la más restrictiva que funcione.
Tema 3.3. Objetos en PHP
11
Tema 3.3. Objetos en PHP 12
extends
• Herencia simple (una clase solo puede heredar de otra).
• Se pueden sobreescribir los métodos y propiedades que no
estén declarados como final en la superclase
• Como no hay sobrecarga de métodos, en PHP tampoco se
puede sobreescribir un método con distintos parámetros que
la superclase.
• Se puede acceder a los métodos de la superclase con parent::
• parent::metodo();
Tema 3.3. Objetos en PHP 13
class Foo {
private $v = 'foo';
public function value() {
return $this->v;
}
}
class Bar extends Foo {
public function value() {
return parent::value() . '2'; // ojo: parent::$v falla
}
}
Tema 3.3. Objetos en PHP
14
Definen conjuntos de métodos públicos.
Tienen que ser implementados por clases.
• Se declaran las interfaces que implementa con la palabra
implements
• Una clase puede implementar más de una interfaz
interface Bolsa {
public function compra();
public function venta();
class BolsaDeMadrid implements Bolsa {
// implementación de los métodos compra() y venta()
}
}
Tema 3.3. Objetos en PHP 15
Clases que no se pueden instanciar, solo heredar.
• Pueden tener métodos sin implementar (abstractos).
abstract class ClaseAbstracta {
public function metodo() {
// implementación del método
}
public abstract function abstracta();
class ClaseConcreta extends ClaseAbstracta {
// implementación de la función abstracta()
}
}
Tema 3.3. Objetos en PHP 16
Tema 3.3. Objetos en PHP 17
Un trait permite agrupar funcionalidades muy específicas
• Mecanismo para hacer una especie de herencia múltiple.
• No se puede instanciar directamente un trait.
• Poco recomendable, hay alternativas que permiten mejores diseños.
trait A {
trait B {
}
}
class AB {
use A, B;
}
$o = new AB();
$o->printa();
$o->printb();
public function printa() { echo 'a'; }
public function printb() { echo 'b'; }
Tema 3.3. Objetos en PHP 18
En general, los nombres que empiezan por __ están reservados. Los más
importantes son:
•
__
construct() -- constructor
•
__
destruct() -- destructor
•
__toString() -- convierte tu objeto a cadena
El constructor también se puede declarar siguiendo la convención C++ /
Java: método con el nombre de la clase
class AutoCart extends Cart {
function AutoCart() {
$this->addItem("10", 1);
}
...
}
Ojo: si hay superclase, hay que llamar al constructor de la superclase de
forma explícita -- parent::__construct()
Tema 3.3. Objetos en PHP
19
Iteración tipo array asociativo
• Sólo muestra las variables que estén “visibles”
foreach ($objeto as $propiedad => $valor) {
echo "$propiedad vale $valor";
}
Clonado sencillo, con copia campo-a-campo
(si quieres que sea más profundo, sobreescribe __clone())
$objetoCopia = clone $objetoOriginal;
Serialización JSON
$json = json_encode($objeto) // devuelve "{ ... }"
Serialización estándar
$copia = unserialize(serialize($objetoOriginal))
Tema 3.3. Objetos en PHP
20
No es más complicado usar algo de objetos que no
usarlos
• Como poco, organizan el código en espacios de nombres.
Clase::metodo() vs. tipo_funcion()
• Las constantes quedan bien agrupadas.
• Puedes marcar cosas internas como privadas, para que no se
usen por accidente desde fuera.
• Podemos usar patrones de diseño:
• Data Access Object (DAO) -> persistencia de datos.
• Transfer Object (TO) -> atributos, getters y setters.
• Posibilidad de pasar de/a JSON de forma fácil.
Tema 3.3. Objetos en PHP
21
Muy cómodo usar objetos para las entidades del dominio
• Una clase para los elementos de cada tabla; asumamos una
tabla 'anuncios’
• Clase Anuncio.
• Métodos para:
• crear un nuevo anuncio (= constructor).
• insertar (devuelve ID asignado) ó sobreescribir un anuncio en
la BD.
• borrar un anuncio de la BD por ID.
• leer un anuncio de la BD por ID.
• leer de la BD todos los anuncios de un tipo dado.
Tema 3.3. Objetos en PHP
22
Licencia Creative Commons
• Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo
Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros y Javier
Bravo Agapito
Tema 3.3. Objetos en PHP
23


3
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Juan Pavón Mestras, con modificaciones de
Material elaborado por Juan Pavón Mestras, con modificaciones de
Pablo Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros,
Pablo Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros y
Carlos Cervigón y Javier Bravo AgapitoCarlos Cervigón
• Repaso de MySQL y BBDD. SQL.
• SELECT, INSERT, UPDATE, DELETE.
• phpMyAdmin.
• Ejemplo práctico con phpMyAdmin.
• PHP y MySQL.
• MySQLi, PDO.
• Conexión, desconexión, query.
• Operaciones con los resultados.
• Backup, peligros, escapar argumentos.
• Prepared statements, cifrado.
Tema 3.4: PHP y MySQL/MariaDB 2
Introducción sencilla al uso de phpMyAdmin y php con
MySQL
• https://www.yourwebskills.com/page.php?page=136
• https://www.yourwebskills.com/page.php?page=397
Libros
• Suehring, S., Converse, T. & Park, J. (2009). PHP6 and MySQL
Bible. Wiley Pub.
• Heurtel, O. (2009). PHP y MySQL. Domine el desarrollo de un
sitio Web dinámico e interactivo. Ediciones ENI.
• Delisle, M. (2007). Dominar phpMyAdmin para una
administración efectiva de MySQL. Packt Publishing.
Tema 3.4: PHP y MySQL/MariaDB 3
Tema 3.4: PHP y MySQL/MariaDB 4
La información se guarda para volver a utilizarla
• Ficheros
• Almacenamiento básico
• Acceso secuencial o aleatorio
• Bases de datos relacional
• Información estructurada
• Relaciones
• Búsquedas
• Acceso concurrente
• Control de acceso a la información
• Integridad
Tema 3.4: PHP y MySQL/MariaDB 5
Sistema de Gestión de Base de Datos Relacional
• La información se guarda en tablas
• Una tabla es una colección de datos relacionados.
• Una tabla consta de columnas (campos) y filas (registros).
• Las tablas se enlazan por relaciones entre columnas.
• Implementa casi todo el estándar SQL (Structured Query Language).
• Código abierto
• Actualmente de Oracle, que adquirió Sun (2010), a la que
pertenecía MySQL AB (2008).
• Escalable
• Aplicaciones pequeñas y grandes (millones de registros).
• Transacciones, Multiusuario
• Eficiente: Multihilo, varias técnicas de hash, b-tree, etc.
Tema 3.4: PHP y MySQL/MariaDB 6
Clientes
Pedidos
Productos
nif *
numero *
id *
nombre
fecha
nombre
direccion
cliente
descripcion
email
producto
precio
telefono
cantidad
Tema 3.4: PHP y MySQL/MariaDB
7
La base de datos consta de tablas
• Cada una con una serie de columnas (campos)
• Cada campo tendrá asociado un tipo:
• Enteros: TINYINT, SMALLINT, MEDIUMINT, INT, BIGINT
• Números reales: DECIMAL, DOUBLE, FLOAT, REAL
• Booleanos: BOOLEAN
• Fecha: DATE, TIME, YEAR
• Strings: VARCHAR (hasta 256 caracteres), TEXT
• Como Storage Engine conviene usar InnoDB para poder
gestionar relaciones entre tablas
• Como Collation conviene usar utf8[mb4]_general_ci
• Charset es la codificación en la que la base de datos guarda internamente los datos.
• Collation es el conjunto de reglas que se aplican para comparar caracteres en un
charset (como comparar los datos en la BD).
Tema 3.4: PHP y MySQL/MariaDB
8
SELECT
• Recupera elementos de una tabla o conjunto de tablas (con JOIN)
• SELECT campos FROM tabla WHERE campo = valor
• Si se quieren todos los campos, usar *
• Si se omite la cláusula WHERE se tienen todos los campos de la tabla
• Para la condición WHERE se pueden usar varios operadores:
• = <> != < <= > >= AND OR NOT
• Se pueden recuperar campos de varias tablas
• SELECT tabla1.campo1 tabla2.campo2 FROM tabla1, tabla2
WHERE campo3=valor3 AND tabla1.campo1 = tabla2.campo2
• También se pueden usar patrones para las condiciones
• % indica cualquier subcadena
• SELECT campos FROM tablas WHERE campo3 LIKE patron
• Ejemplo: SELECT nombre FROM clientes WHERE nombre LIKE Juan%
• Ordenar: ORDER BY
• Para no tener registros duplicados: DISTINCT
• SELECT DISTINCT campos FROM tablas WHERE ...
Tema 3.4: PHP y MySQL/MariaDB 9
INSERT
• Inserta nuevos elementos en una tabla
• Crea un nuevo cliente
• INSERT INTO clientes (nif, nombre, direccion, email, telefono)
VALUES ("M3885337J", "Empresa Uno", "Calle Uno, Madrid",
"jefe@empresauno.com", "91 2347898")
UPDATE
• Actualiza campos de una tabla
• Modifica el importe del producto "Producto1"
• UPDATE productos SET precio = 399.99 WHERE
nombre="Producto1“
DELETE
• Elimina registros de una tabla
• Elimina pedidos con más de 30 días de antigüedad
• DELETE FROM pedidos WHERE fecha < CURDATE()-30
Tema 3.4: PHP y MySQL/MariaDB 10
Tema 3.4: PHP y MySQL/MariaDB 11
Herramienta que ofrece una interfaz gráfica para la
administración del servidor MySQL
• Configuración del servidor y las bases de datos.
• Gestionar (crear, modificar, borrar) las bases de datos, tablas, campos,
relaciones, índices, etc.
• Consultas con SQL, y mediante ejemplos (query by example).
• Definir usuarios y asignar permisos.
• Realizar copias de seguridad.
• Crear gráficos (PDF) del esquema de la base de datos.
• Exportar a muchos formatos (documentos de texto, hojas de cálculo).
En XAMPP se puede invocar en http://localhost/phpmyadmin/
Tema 3.4: PHP y MySQL/MariaDB 12
Tema 3.4: PHP y MySQL/MariaDB 13
Conviene crear un nuevo usuario para cada sitio web
• Cada sitio web tendrá sus propias bases de datos.
• El usuario root sólo se debe usar para administración.
Entrar en phpMyAdmin como usuario root
• A continuación podemos crear nuevos usuarios
• Pestaña Cuentas de usuarios -> Agregar cuenta de usuario
• En la ventana que aparece indicar los datos correspondientes
• Salir de la sesión como root
Entrar con el nuevo usuario
• Se puede trabajar con la nueva base de datos
Tema 3.4: PHP y MySQL/MariaDB 14
Al seleccionar la base de datos creada aparecen las
operaciones que se pueden realizar con ella
• Se pueden añadir permisos (pestaña Privilegios) para que otros
usuarios puedan usar la base de datos
En Estructura se pueden crear las tablas que definen el
esquema de la base de datos
Tema 3.4: PHP y MySQL/MariaDB 15
1. Creamos la base de
datos vacía: por
ejemplo, para la web de
compra de cervezas:
‘beer’ con cotejamiento:
‘utf8_general_ci’.
2. Primero seleccionamos
la Base de datos. Luego
podemos crear las tablas
desde PHPMyadmin o
podemos importar las
tablas desde un script
(que contendrá las
sentencias CREATE
TABLE…)
Tema 3.4: PHP y MySQL/MariaDB 16
Creamos un usuario y le damos permisos sobre la base de datos
creada: Cuentas de usuarios ->Agregar cuenta de usuario
En este ejemplo el nombre de prueba es ‘beeruser’ y la contraseña
‘beerpass’.
Nombre del host: Local.
ALL PRIVILEGES.
$this->mysqli = new mysqli('127.0.0.1', 'beeruser', ‘beerpass', 'beer');
Tema 3.4: PHP y MySQL/MariaDB
17
Tema 3.4: PHP y MySQL/MariaDB 18
Clientes
Pedidos
Productos
nif *
numero *
id *
nombre
fecha
nombre
direccion
cliente
precio
email
producto
descripcion
telefono
cantidad
*Clave primaria (los objetos en esta columna son únicos y no nulos). Por defecto será indexada.
**Se pueden definir también índices para mejorar la eficiencia de las búsquedas
***Las claves foráneas (foreign keys) identifican una columna (o grupo de columnas) en una tabla que se
refiere a otra columna (o grupo de columnas) en otra tabla, generalmente la clave primaria en la tabla
referenciada.
Contribuyen a gestionar la integridad de la base de datos: no se puede crear un pedido de un cliente o un
producto que no existan.
Las claves foráneas deberían indexarse porque se usarán para seleccionar registros con frecuencia.
Tema 3.4: PHP y MySQL/MariaDB
19
La primera tabla es la de clientes, con cinco campos
• nif: servirá como primary key (el nif es único).
• nombre: de empresa o de persona (sería nombre + apellidos)
• Se puede indexar para hacer búsquedas por este campo.
• direccion
• email
• telefono: como string para permitir uso de caracteres no numéricos
Tema 3.4: PHP y MySQL/MariaDB 20
Crear dos tablas más:
• Productos y pedidos
 pedidos
autoindex
Tema 3.4: PHP y MySQL/MariaDB
21
Usar la pestaña Diseñador para ver gráficamente las
tablas
• Para añadir una relación seleccionar el botón Crear relación
• Seleccionar la clave primaria de la tabla clientes: nif
• Seleccionar la foreign key en la tabla pedidos: cliente
• Aparece una ventana para seleccionar qué hacer para
preservar la integridad de las referencias, con las siguientes
operaciones:
• DELETE: seleccionar RESTRICT
• UPDATE: seleccionar CASCADE
» La restricción más adecuada en la mayoría de
los casos es evitar realizar borrados en cascada
y actualizar en cascada
 La relación queda establecida y aparece en el gráfico
 Para salvar el diagrama, usar el botón Save
Tema 3.4: PHP y MySQL/MariaDB 22
Export
• Conveniente de forma regular
• Especialmente si se hacen
muchos cambios.
• Opciones para exportar
• El servidor completo.
• Una base de datos entera.
• Una tabla.
• Estructura o datos, o ambos.
• Compresión: ninguna, zipped,
gzipped, bzipped.
• Formato
• SQL, CSV, Word, Latex, Excel, OpenDoc,
PDF, XML, JSON, etc.
El proceso inverso es posible
con Import
Tema 3.4: PHP y MySQL/MariaDB 23
Pruebas en casa con phpMyAdmin
• Crear la base de datos tienda con las tablas clientes, productos y
pedidos tal como se han definido previamente.
• Insertar elementos en las tres tablas, primero en clientes y
productos y luego en pedidos
• Comprueba qué ocurre si se intenta introducir un pedido
para un cliente que no existe.
• Intenta eliminar un cliente que tiene algún pedido
• Observa el efecto de haber definido la política DELETE:
RESTRICT cuando se estableció la FOREIGN KEY.
• Intenta cambiar el id de un producto que tiene algún pedido
• Observa el efecto de haber definido la política UPDATE:
CASCADE cuando se estableció la FOREIGN KEY.
Tema 3.4: PHP y MySQL/MariaDB 24
Tema 3.4: PHP y MySQL/MariaDB 25
Desde PHP5 se recomienda utilizar la extensión MySQLi
(Mysql improved) en vez de la tradicional Mysql
• Permite utilizar las mejoras de las últimas versiones del servidor
MySQL.
• Interfaz orientada a objetos.
Alternativa: PHP Data Objects (PDO)
• Interfaz ligera para acceso a bases de datos, con soporte para
MySQL y otros sistemas de gestión de bases de datos.
• Un driver específico para cada SGBD.
• Proporciona una capa de abstracción para el acceso a datos.
• Independiente del tipo de SGBD.
• Orientado a objetos.
Tema 3.4: PHP y MySQL/MariaDB 26
Normalmente comprende los siguientes pasos:
1. Conexión con el servidor de bases de datos y selección de una
base de datos:
• Se obtiene un objeto para operar con la base de datos.
2. Uso de la base de datos
• Envío de operación SQL a la base de datos.
• Recepción y tratamiento de los resultados.
• Liberar memoria de resultados.
3. Desconexión
Tema 3.4: PHP y MySQL/MariaDB 27
Para utilizar una base de datos hay que indicar:
servidor, usuario, password, base de datos
$conn = new mysqli($hostname, $usuario, $password, $basededatos);
if ( mysqli_connect_errno() )
{
echo "Error de conexión a la BD: ". mysqli_connect_error();
exit();
}
• Devuelve un objeto sobre el que operar con la base de datos.
• Si hubiera un error se comprueba con el método:
mysqli_connect_errno()
• Cuando se deja de utilizar la base de datos conviene cerrar la
conexión al servidor para liberar recursos ordenadamente:
$conn->close();
Tema 3.4: PHP y MySQL/MariaDB
28
<?php
$servername = "localhost";
$username =
"username";
$password =
"password";
$db = "tienda";
// Crear conexion
$conn = new mysqli($servername, $username, $password, $db);
// Comprobar conexion
if (mysqli_connect_error()) {
die("Database connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
…
?>
$conn->close();
Fuente: https://www.w3schools.com/php/
Tema 3.4: PHP y MySQL/MariaDB
29
<?php
$servername = "localhost";
$username = "username";
$password = "password";
$db = "tienda";
// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $db);
// Comprobar conexion
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}
echo "Connected successfully";
…
?>
mysqli_close($conn);
Fuente: https://www.w3schools.com/php/
Tema 3.4: PHP y MySQL/MariaDB
30
<?php
$servername = "localhost";
$username = "username";
$password = "password";
try {
$conn = new PDO("mysql:host=$servername;dbname=myDB",
$username, $password);
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
echo "Connected successfully";
}
catch(PDOException $e) {
echo "Connection failed: " . $e->getMessage();
}
. . .
$conn = null;
?>
Fuente: https://www.w3schools.com/php/
Tema 3.4: PHP y MySQL/MariaDB
31
<?php
$servername = "localhost";
$username = "username";
$password = "password";
$db = "myDB";
Las queries SQL se pasan con el método query
$conn->query("SQL query");
• Devuelve un objeto que permite
tratar los resultados
• Devuelve FALSE si hay algún error
// Crear conexion
$conn = new mysqli($servername, $username, $password, $db);
// Comprobar conexion
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
// Create database
$sql = "CREATE DATABASE “ . $db;
if ($conn->query($sql) === TRUE) {
echo "Database created successfully";
} else
{
echo "Error creating database: " . $conn->error; }
$conn->close();
?>
Fuente: https://www.w3schools.com/php/
Tema 3.4: PHP y MySQL/MariaDB
32
<?php
$servername = "localhost";
$username = "username";
$password = "password";
$db = "myDB";
// Crear conexion
$conn = new mysqli($servername, $username, $password, $db);
// Comprobar conexion
if ($conn->connect_error) {
die(“error de conexión: " . $conn->connect_error);
}
// sql de creación de tabla
$sql = "CREATE TABLE MyGuests (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
firstname VARCHAR(30) NOT NULL,
lastname VARCHAR(30) NOT NULL,
email VARCHAR(50),
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
if ($conn->query($sql) === TRUE) {
echo "Tabla creada correctamente";
} else {
echo "Error creando tabla: " . $conn->error;
}
$conn->close();
?>
Fuente: https://www.w3schools.com/php/
Tema 3.4: PHP y MySQL/MariaDB
33
<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";
// Crear conexion
$conn = new mysqli($servername, $username, $password, $dbname);
// Comprobar conexion
if ($conn->connect_error) {
die(“fallo de conexion: " . $conn->connect_error);
}
$sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('John', 'Doe', 'john@example.com')";
if ($conn->query($sql) === TRUE) {
echo "Nuevo registro creado";
} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
Fuente: https://www.w3schools.com/php/
Tema 3.4: PHP y MySQL/MariaDB
34
$conn->query("SQL query");
• Devuelve un objeto que permite tratar los resultados.
• Devuelve FALSE si hay algún error.
• Si se ponen variables PHP en la query, se ponen entre
comillas simples para que la función mysql_query las
reemplace por su valor.
$empresa="Empresa%";
$query="SELECT * FROM clientes WHERE nombre LIKE '$empresa'";
$resultado=$conn->query($query)
or die ($mysqli->error. " en la línea ".(__LINE__-1));
$numregistros=$resultado->num_rows;
echo "<p>El número de clientes con nombre Empresa* es:
",$numregistros,".</p>";
Tema 3.4: PHP y MySQL/MariaDB
35
Varios atributos y métodos de la clase mysqli_result
facilitan el tratamiento de los registros obtenidos
• num_rows: Número de registros (filas)
• $numfilas = $resultado->num_rows;
• fetch_all([modo]): Devuelve todas las filas en un array
asociativo, numérico, o en ambos
• $registro = $resultado->fetch_all([modo])
• Modo: argumento opcional para indicar cómo se accede a los
registros
• Usando el nombre del campo como índice: MYSQL_ASSOC
• Usando la posición como índice: MYSQL_NUM
• Usando tanto el nombre de campo como la posición: MYSQL_BOTH
Tema 3.4: PHP y MySQL/MariaDB 36
• fetch_array([modo]): Lo mismo que fetch_all pero los devuelve
de uno en uno (en cada llamada).
• fetch_assoc(): Lo mismo que el anterior pero como array
asociativo.
• fetch_object(): Devuelve la fila actual de un conjunto de
resultados como un objeto.
• free(): Libera la memoria asociada al resultado
• $resultado->free();
Tema 3.4: PHP y MySQL/MariaDB 37
<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";
// Crear conexion
// Comprobar conexion
. . .
$sql = "SELECT id, firstname, lastname FROM MyGuests";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// mostramos los datos de cada fila
while($row = $result->fetch_assoc()) {
echo "id: " . $row["id"]. " - Name: " . $row["firstname"].
" " . $row["lastname"]. "<br>";
}
} else {
echo "0 resultados";
}
?>
$conn->close();
Fuente: https://www.w3schools.com/php/
Tema 3.4: PHP y MySQL/MariaDB
38
$query="SELECT * FROM clientes";
$resultado=$mysqli->query($query)
or die ($mysqli->error . " en la línea ".(__LINE__-1));
$numregistros=$resultado->num_rows;
echo "<p>El número de clientes es:",$numregistros,".</p>";
echo "<table border=2><tr><th>NIF</th> <th>Nombre</th> <th>Dirección</th>
<th>Email</th> <th>Teléfono</th></tr>";
while ($registro = $resultado->fetch_assoc())
{
echo "<tr>";
foreach ($registro as $campo)
echo "<td>",$campo, "</td>";
echo "</tr>";
}
echo "</table>";
$resultado->free();
Tema 3.4: PHP y MySQL/MariaDB
39
<!DOCTYPE html>
<html lang="es">
<head>
<meta http-equiv="Content-Type" content=
<title><?= $tituloPagina ?></title>
</head>
<body>
<div id="contenedor">
<main>
<article>
<?= $contenidoPrincipal ?>
</article>
</main>
<footer>
<p>[c]::<?= $contenidoPie ?>
</footer>
</div>
</body>
</html>
"text/html; charset=utf-8">
Tema 3.4: PHP y MySQL/MariaDB 40
<?php
//Definicion de constantes
//Parametros de acceso de la base de datos
define('BD_HOST', 'localhost');
define('BD_USER',
'tiendauser');
define('BD_PASS',
'userpass');
define('BD_NAME',
'tiendaaw');
$tituloPagina = 'Datos';
$contenidoPie = 'No hay errores';
$contenidoPrincipal = '<p>**Conectando con la base de datos**</p>';
$dni = '45678903';
//Conexion con base de datos
$conn = new mysqli(BD_HOST, BD_USER, BD_PASS, BD_NAME);
if ($conn->connect_error){
die("La conexión ha fallado" . $conn->connect_error);
}
$contenidoPrincipal .= '<p>***La conexión es correcta***</p>';
$conn->close();
$contenidoPrincipal .= '<p>**Cerrando la conexión**</p>';
include __DIR__.'/includes/plantillas/plantilla.php';
Tema 3.4: PHP y MySQL/MariaDB 41
//Definicion de constantes
//Parametros de acceso de la base de datos
//Conexion con base de datos
$conn = new mysqli(BD_HOST, BD_USER, BD_PASS, BD_NAME);
$contenidoPrincipal .= '<p>**Insertando un cliente**</p>';
$contenidoPrincipal .= '<p>**Comprobando si existe el cliente**<p>';
$query =
"SELECT * FROM Clientes WHERE dni = $dni";
$result = $conn->query($query);
if ($result->num_rows > 0){
$contenidoPrincipal .= '<p>***El cliente existe. Sus datos son:***</p>';
$reg = $result->fetch_assoc();
$contenidoPrincipal .= <<<EOF
<p><strong>{$reg['dni']}</strong></p>
<p><strong>{$reg['nombre']}</strong></p>
<p><strong>{$reg['apellidos']}</strong></p>
EOF;
$result->free();
}
$conn->close();
$contenidoPrincipal .= '<p>**Cerrando la conexión**</p>';
include __DIR__.'/includes/plantillas/plantilla.php';
Tema 3.4: PHP y MySQL/MariaDB 42
//Definicion de constantes
//Parametros de acceso de la base de datos
//Conexion con base de datos
$conn = new mysqli(BD_HOST, BD_USER, BD_PASS, BD_NAME);
$contenidoPrincipal .= '<p>**Insertando un cliente**</p>';
$contenidoPrincipal .= '<p>**Comprobando si existe el cliente**<p>';
$query =
"SELECT * FROM Clientes WHERE dni = $dni";
$result = $conn->query($query);
if ($result->num_rows > 0){
//El cliente existe, se muestran sus datos y se libera la memoria
$result->free();
}
else{
$sql =
"INSERT INTO Clientes (dni, nombre, apellidos, direccion, email) VALUES ($dni,
'Javier', 'Bravo Agapito', 'Calle José García Santesmases', ‘javier.bravo@ucm.es')";
if ($conn->query($sql) === TRUE){
$contenidoPrincipal .= "<p>***No existe. Nuevo registro creado con éxito***</p>";
} else {
$contenidoPie = "Error en : " . $sql . "<br>" . $conn->error;
}
}
$conn->close();
$contenidoPrincipal .= '<p>**Cerrando la conexión**</p>';
include __DIR__.'/includes/plantillas/plantilla.php';
Tema 3.4: PHP y MySQL/MariaDB 43
Tema 3.4: PHP y MySQL/MariaDB 44
Muy cómodo usar objetos para las entidades del dominio
• Una clase para los elementos de cada tabla; asumamos una
tabla 'anuncios’.
• Objeto 'anuncio'. Métodos para:
• crear un nuevo anuncio (= constructor)
• insertar (devuelve ID asignado) ó sobreescribir un anuncio en
la BD
• borrar un anuncio de la BD por ID
• leer un anuncio de la BD por ID
• leer de la BD todos los anuncios de un tipo dado
• "Data Access Object", DAO: patrón de diseño especializado en
persistencia de los datos
• Código de vistas muy legible; posibilidad de pasar de/a JSON
fácilmente
Tema 3.4: PHP y MySQL/MariaDB 45
Consejos importantes para el proyecto
• Crear una buena base de datos con datos de prueba detallados.
• Tener siempre a mano un backup de la misma
• Es muy común estropear el contenido de la BBDD durante el
desarrollo.
• Cada vez que se añade nueva funcionalidad, hacemos una
nueva versión de los datos de prueba
• Y su backup, claro.
Tema 3.4: PHP y MySQL/MariaDB 46
Peligros
• Inyección SQL: código que se comporta como datos
$login= "' or '1'='1'";
$sql = "SELECT * FROM users WHERE name = '" + $login + "';"
$sql = "SELECT * FROM users WHERE name = '' or '1'='1';"
Tema 3.4: PHP y MySQL/MariaDB 47
$nombre = 'nombre'; $pass = '123"fd';
$sql = 'select * from usuario where nombre = "$nombre“
and pass = "$pass"';
select * from usuario where nombre = "nombre" and pass = "123"fd";
$conn = mysqli_connect($hostname, $user, $pass, $bd);
if (!$conn) {
echo “Fallo de conexión “ . mysqli_connect_error();
exit;}
$sql = 'select * from usuario where nombre = "'.
mysql_real_escape_string($conn, $nombre).’" and pass = "'.
mysql_real_escape_string($conn, $pass).'"';
select* from usuario where nombre = "nombre" and pass = "123\"fd";
Tema 3.4: PHP y MySQL/MariaDB
48
<?php
$mysqli= new mysqli("localhost","my_user"
,"my_password"
,
"my_db");
if ($mysqli -> connect_errno) {
echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
exit();
}
// Escape special characters, if any
$firstname = $mysqli -> real_escape_string($_POST['firstname']);
$lastname = $mysqli -> real_escape_string($_POST['lastname']);
$age = $mysqli -> real_escape_string($_POST['age']);
$sql="INSERT INTO Persons (FirstName, LastName, Age) VALUES
('$firstname', '$lastname', '$age')";
if (!$mysqli -> query($sql)) {
printf("%d Row inserted.\n", $mysqli->affected_rows);
}
$mysqli -> close();
?>
Fuente: https://www.w3schools.com/php/
Tema 3.4: PHP y MySQL/MariaDB
49
<?php
$lastname =
"D'Ore";
$sql="INSERT INTO Persons (LastName) VALUES ('$lastname')";
// Esta querie fallará por no escapar campo $lastname
if (!$mysqli -> query($sql)) {
printf("%d Row inserted.\n", $mysqli->affected_rows);
}
?>
Fuente: https://www.w3schools.com/php/
Tema 3.4: PHP y MySQL/MariaDB
50
Opciones:
• Usa PDO con prepared statements
• Usa mysqli_*
y no te olvides, nunca, nunca, de escapar argumentos con
mysql_real_escape_string()
$nombre = $conn->real_escape_string($nombre);
$firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
Tema 3.4: PHP y MySQL/MariaDB
51
<?php
$servername =
$password =
"localhost"; $username =
"password"; $dbname =
"myDB";
"username";
// Create connection // Check connection
// prepare and bind
$stmt = $conn-> prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES
(?, ?, ?)");
$stmt->bind_param("sss", $firstname, $lastname, $email);
// set parameters and execute
$firstname = "John";
$lastname =
"Doe";
$email = "john@example.com";
$stmt->execute();
. . .
$firstname = "Julie";
$lastname = "Dooley";
$email = "julie@example.com";
$stmt->execute();
echo "New records created successfully";
$stmt->close(); $conn->close();
Fuente: https://www.w3schools.com/php/
?>
Tema 3.4: PHP y MySQL/MariaDB
52
<?php
// Al elegir BCRYPT la salida del algoritmo siempre serán 60 caracteres
echo password_hash("rasmuslerdorf", PASSWORD_BCRYPT)."\n";
// Genera $2y$10$.vGA1O9wmRjrwAVXD98HNOgsNpDczlqm3Jq7KnEd1rVAGv3Fykk1a
?>
// inserta un nuevo usuario en la BD
function add_user($db, $login_, $pass_, $role) {
$hashed = password_hash($pass_);
inserta ($login_, $hashed, $role) en BD
}
// verifica que una contraseña es correcta
function auth_check_login($db, $login_, $pass_) {
//consulta ($hashed) en BD, buscando por $login_
return password_verify($pass_, $hashed);
}
Tema 3.4: PHP y MySQL/MariaDB 53
...
public function insertarUsuario(tUsuario $usuario..)
. . .
if(empty($errores))
{
// encriptamos la contraseña del usuario
$usuario->setPassword(password_hash($usuario->getPassword(),
PASSWORD_DEFAULT));
// insertamos el usuario.
$idUsuario = $this->insert($usuario);
. . .
}
Tema 3.4: PHP y MySQL/MariaDB 54
public function validaLogin($username, $password)
. . .
$errores = array();
$usuario;
if(empty($username)) {
$errores[] = "El nombre de usuario no puede estar vacio";
}
if(empty($password)) {
$errores[] = "La contraseña no puede estar vacia";
}
if (mb_strlen($password) < 4) {
. . .
if(empty($errores)) {
$usuario = $this->buscarUsuarioPorNombre($username);
if($usuario) {
if(password_verify($password, $usuario->getPassword()))
{
$_SESSION['sesion'] = true;
$_SESSION['userID'] = $usuario->getId();
$_SESSION['esAdmin']=($usuario->getNivel_Acceso() == 9) ? true :false;
. . .
Tema 3.4: PHP y MySQL/MariaDB 55
PHP Extension and Application Repository
• Extensiones a PHP (módulos) para automatizar tareas frecuentes.
• Configurable vía dir-xampp/php/pear.bat(desde consola).
Extensiones interesantes
• DB: Manejo de conexiones a bases de datos, MySQL inclusive
• Permite cambiar el motor de BD sin tocar código (abstracción).
• Sustitución de parámetros inteligente, evitando "inyección SQL“.
Extensiones recomendadas
• Auth: Manejo de autenticación
• Mail: Envío de correos
• HTML_QuickForm2: generación / validación de formularios
• Text_CAPTCHA: generación / validación de CAPTCHAS
Tema 3.4: PHP y MySQL/MariaDB 56
Licencia Creative Commons
• Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo
Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros, Carlos
Cervigón y Javier Bravo Agapito
Tema 3.4: PHP y MySQL/MariaDB 57


4
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Juan Pavón Mestras y Manuel Freire Morán, con
Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo Moreno
Ger, Manuel Freire Morán, Raquel Hervás Ballesteros, Carlos Cervigón, Iván
Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo Moreno Ger,
modificaciones de Pablo Moreno Ger, Raquel Hervás Ballesteros y Javier
Martínez y Javier Bravo.
Manuel Freire Morán, Raquel Hervás Ballesteros, Carlos Cervigón e Iván Martínez.
Bravo Agapito
• CSS: ideas básicas, versiones, taxonomía.
• DOM.
• Reset de CSS.
• Anatomía y sintaxis de CSS.
• Selectores: etiquetas, clases, identificadores, pseudo
clases, pseudo elementos.
• Propiedades y valores.
• Modelo de cajas.
• Etiquetas HTML y tipos de cajas.
• Herencia y cascada.
Tema 4.1: CSS 2
Tema 4.1: CSS
3
Hojas de estilo en cascada (Cascading Style Sheets)
• Ficheros de texto con la extensión .css
• Definen la apariencia de las páginas web
• Facilitan la gestión de sitios web grandes y sofisticados:
• Las hojas CSS se crean una vez y se pueden compartir entre
varios desarrolladores web.
• Para realizar un cambio de estilo en todo el sitio solo hay que
hacerlo en un lugar: la hoja CSS correspondiente.
• Podemos tener múltiples estilos para una misma página (p.e.
pantalla grande, móvil, estilo para imprimir…).
Tema 4.1: CSS
4
Versiones:
• CSS 1 (1996, revisado en 2008)
• http://www.w3.org/TR/CSS1
• CSS 2 (1998), actual: 2.1 (revisado en 2021)
• http://www.w3.org/TR/CSS21
• https://www.w3.org/TR/CSS22/
• CSS 2.1 Soportado por todos los navegadores habituales
• CSS 3 (2011 - 2025)
• http://www.w3.org/Style/CSS/current-work
• Se divide en módulos, que están en distintos estados de
desarrollo.
• Algunas cosas ya aceptadas se van incorporando a los navegadores.
Tema 4.1: CSS
5
● Recommendation
● Candidate Recommendation
● Last Call
● Working Draft
CSS3 taxonomy and status
By Krauss - Own work, CC BY-SA 4.0,
https://commons.wikimedia.org/w/index.php?cu
rid=44954967
Tema 4.1: CSS 6
CSS embebido, como parte de una etiqueta HTML
<!DOCTYPE html>
<html>
<body>
</body>
</html>
<p style="color: red;">Texto en rojo</p>
CSS interno, como un elemento HTML
<!DOCTYPE html>
<html>
<head>
<style type="text/css">
p { color: red; }
</style>
</head>
<body>
<p>Texto en rojo</p>
</body>
</html>
Tema 4.1: CSS
7
CSS externo, como un fichero aparte
<!DOCTYPE html>
<html>
<head>
</head>
<body>
</body>
</html>
<p>Texto en rojo</p>
<link rel="stylesheet" type="text/css" href="ej.css" />
 ej.css
p {
}
color: red;
Tema 4.1: CSS
8
Modelo de Objetos del Documento (Document Object
Model)
• DOM define objetos y propiedades de los elementos HTML y
XML, y los métodos para acceder a ellos
• Representación de documentos HTML y XML.
• API para consultar y manipular los documentos (contenido,
estructura, estilo).
Los elementos de un documento se organizan en una
jerarquía (árbol): jerarquía DOM
• Los elementos del documento son los nodos del árbol.
• Las relaciones entre los nodos representan las interconexiones
de los elementos.
Tema 4.1: CSS 9
El navegador transforma el código del documento en un
árbol DOM
<html>
<head>
<title>My title</title>
</head>
<body>
<a href="...">My Link</a>
<h1>My header</h1>
</body>
</html>
Tema 4.1: CSS 10
Este árbol define distintas relaciones entre los elementos
• Descendientes: son todos los elementos contenidos (directa o
indirectamente) por un elemento. Por ejemplo, head, body, a y h1 son
descendientes de html.
• Hijos directos: son los descendientes de primer nivel. Por
ejemplo, head es hijo de html y h1 es hijo de body.
• Hermanos o siblings: Descendientes que tienen un padre común. Por
ejemplo, h1 y a son hermanos entre sí.
Tema 4.1: CSS 11
¿Qué ocurre cuando hay dos declaraciones contradictorias?
body { background-color: black;
color:red; }
p { color: blue; }
“Todos los elementos tendrán fondo negro y texto rojo, a excepción
de los párrafos que tendrán texto azul”
En general, la regla básica es:
• Cuanto más específico sea un selector, más importancia tiene su
regla asociada.
• A igual especificidad, se considera la última regla indicada
• De ficheros externos a estilos dentro del propio elemento.
• En una lista de reglas en el mismo ámbito, se elige la última.
• El orden en que se incluyen las CSS es muy importante.
Tema 4.1: CSS 12
Para que la hoja predeterminada permita que se vea igual en cada
navegador
<html>
<head> <link rel="stylesheet" type="text/css" href=“.css/reset.css" /> </head>
<body> . . . </body>
</html>
/* http://meyerweb.com/eric/tools/css/reset/
v2.0 | 20110126
License: none (public domain)
/* HTML5 display-role reset for older browsers */
article, aside, details, figcaption, figure,
footer, header, hgroup, menu, nav, section {
display: block;
*/
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed,
figure, figcaption, footer, header, hgroup,
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
margin: 0;
padding: 0;
border: 0;
font-size: 100%;
font: inherit;
vertical-align: baseline;
}
body {
line-height: 1;
}
ol, ul {
list-style: none;
}
blockquote, q {
quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
content: '';
content: none;
}
table {
border-collapse: collapse;
border-spacing: 0;
}
}
Tema 4.1: CSS 13
Una hoja se compone de una o más reglas.
Una regla especifica:
• Un selector, que indica a qué elementos se aplica.
• Una serie de propiedades y valores que indican qué estilos
aplicar a los elementos seleccionados.
body {
font-family: Verdana;
font-size: 1em;
text-align: justify
}
h1 {
font-family: Verdana, sans-serif;
font-size: 1.3em
}
code {
font-family: Courier, sans-serif;
font-size: 1em
}
Tema 4.1: CSS
14
Reglas estándar
h1 {
font-family: arial, helvetica;
font-weight: bold;
}
Comentarios (pueden ir en cualquier sitio)
/* una hoja de estilos */
at-rules, directivas para el parser
@import url(general.css)
@media print {
font-size: 12pt;
}
Tema 4.1: CSS
15
Tema 4.1: CSS
16
Etiquetas HTML: etiqueta
a <a ...> ... </a>
p <p> ... </p>
Clases: .clase
a.externo <a class="externo"...> ... </a>
p.verso <p class="verso"> ... </p>
Identificadores: #identificador
a#w3c <a id="w3c"...> ... </a>
p#neruda <p id="neruda"> ... </p>
Pseudo-clases y pseudo-elementos: :pseudo
a:hover <a ...> ... </a> (pero sólo si el puntero está encima)
p:first-letter <p> ... </p> (pero sólo afecta a la 1ª letra)
Tema 4.1: CSS
17
Etiqueta
• Indica el estilo aplicable a una etiqueta (se especifica sin < >)
p {text-align: center; color: red}
• Se pueden agrupar varias etiquetas para aplicarles el mismo
estilo
• Separadas por coma “ , “.
• Se pueden definir después otras reglas más particulares.
h1, h2, h3, h4, h5, h6 {
font-family: Verdana;
}
h1 { font-size: 2em; }
h2 { font-size: 1.5em; }
Tema 4.1: CSS
18
Selector universal
• Todos los elementos de la página: *
* {
}
margin: 0; padding: 0;
Tema 4.1: CSS
19
Selector de clase
• A una etiqueta html se le puede asociar una clase (o varias).
Indica la clase a la que pertenece el elemento
<h2 class="cabecera2">
<p class="clase1 clase2">
<h1 class= “destacado”>
• Esto permite aplicar estilos de la(s) clase(s) a la etiqueta. El
nombre de la etiqueta se separa del nombre de la clase con un
punto, sin espacios entre los nombres y el punto.
h2.cabecera2 {
text-align: center;
}
.destacado {
text-align: center;
}
Se aplica a todos los elementos h2 que
sean de clase cabecera2
Se aplica a todos los elementos que
sean de clase destacado
Tema 4.1: CSS
20
Clase
• Se puede definir una clase sin especificar tipo de etiqueta
• Esto equivale a *.clase
.cabeceracentrada {
text-align: center;
}
<h1 class="cabeceracentrada">Título
centrado</h2>
...
<h2 class="cabeceracentrada">Subtítulo
centrado</h2>
Tema 4.1: CSS
21
Identificador
• Permite aplicar un estilo a un único elemento de una página.
• El elemento se identifica con un atributo id, que es único.
<p id="destacado">Segundo párrafo</p>
• El estilo para el ID se especifica precedido con el carácter #
#destacado { color: red; }
Se aplica color rojo al único elemento
de la página con id destacado
Recomendaciones:
• Usar selector de clase, que se aplica a varios elementos, para
dar una apariencia homogénea al documento.
• Usar selector de identificador con mesura.
Tema 4.1: CSS
22
Ejemplos con identificador:
• Todos los párrafos <p> que tengan id="destacado“
p#destacado { ... }
• Puede parecer absurdo porque sólo habrá un elemento en la
página con ese id, independientemente del tipo.
• Podría ser útil si se aplicara el estilo a varias páginas.
• Elemento con id="destacado" que esté dentro de un párrafo <p>
p #destacado { ... }
• Todos los párrafos <p> y al elemento de cualquier tipo que tenga
id="destacado"
p, #destacado { ... }
Tema 4.1: CSS
23
Selectores descendentes:
• Separados por espacio: permite seleccionar los elementos
especificados por un selector dentro del ámbito de otro.
• Ejemplo: Todos los elementos con atributo class="destacado"
que estén dentro de un párrafo <p>.
p .destacado {
...
}
Tema 4.1: CSS
24
Selectores descendentes:
ATENCIÓN: Los espacios y la puntuación son importantes:
• Todos los párrafos <p> que estén declarados con atributo
class="destacado“
p.destacado {
...
}
• Todos los párrafos <p> y todos los elementos con atributo
class="destacado"
p, .destacado {
...
}
Tema 4.1: CSS
25
Ejemplos de selectores descendentes:
• Todos los elementos <span> con atributo class="especial" que
estén dentro de un elemento <div> con atributo
class="principal"
div.principal span.especial { ... }
• Todos los elementos <em> dentro de un <span> dentro de un
<p>
p span em { ... }
Tema 4.1: CSS
26
Ejemplos de selectores descendentes:
• Todos los elementos <a> dentro de algún elemento dentro de
un <p>
p * a { ... }
• Se aplica a:
<p><span><a href="#">Enlace</a></span></p>
• No se aplica a:
<p><a href="#">Enlace</a></p>
• ¿Qué se aplicaría a los dos anteriores?
p a { ... }
Tema 4.1: CSS
27
Pseudo clases dinámicas:
• Clases “virtuales” que no se insertan de manera explícita en las
páginas (están predefinidas)
• :hover – Cuando se coloca el ratón sobre el elemento.
• :active – Cuando se pincha sobre el elemento y se mantiene
el botón del ratón pulsado.
• :focus – Cuando el elemento tiene foco.
• a:link – Para un enlace no visitado.
• a:visited – Para un enlace ya visitado.
• En el caso de los enlaces:
• a:hover DEBE aparecer después de a:link y a:visited.
• a:active DEBE aparecer después de a:hover.
Tema 4.1: CSS
28
a:link{
color:green;
text-decoration:none;
}
a:visited{
color:red
}
a:hover{
color:blue;
}
p:hover{
color:brown;
}
Tema 4.1: CSS 29
Pseudo elementos: similar a las pseudo clases.
Algunos típicos:
• :first-letter – Primera letra en un elemento de bloque.
h1:first-letter { font-size: 400%; }
• :first-line – Primera línea en un elemento de bloque.
p:first-line { text-transform: uppercase; }
• :before y :after – Para añadir contenido antes o después de un
elemento.
p.cuidado:before { content: "Aviso: "; font-weight: bold;
text-decoration: underline; }
<p class="cuidado">Esto es un mensaje de atención.</p>
Aviso: Esto es un mensaje de atención.
Tema 4.1: CSS
30
Raíz
hermanos
sucesor
descendientes
ascendientes
Nodo
hijo
padre
Hoja
Nodo
Nodo
predecesor
Nodo
Tema 4.1: CSS
Selector e > f e f e + f e ~ f Descripción
f es hijo de e
f es descendiente de e
f es sucesor de e
f y e son hermanos
31
Selector Significado
* Todos los elementos
E Elemento HTML 'E'
E F Cualquier F que desciende de E
E > F Cualquier F que sea hijo directo de E (ejemplo: div > p { ... })
E:first-child E sólo si es el primer hijo de su padre
E:link / E:visited E hiperenlace no visitado (:link) o visitado (:visited)
E:active / E:hover /
E:focus E si está activo (pulsado), con el puntero encima, o enfocado
E:lang(c) E si está dentro de una declaración de idioma 'c'
E + F E[foo] F precedido inmediatamente por E (ambos con mismo padre)
E con atributo 'foo' (da igual su valor)
E[foo="warning"] E[foo~="warning"] E[lang|="en"] DIV.warning E#myid E con atributo 'foo="warning"'
E con atributo 'foo=" ... warning ... "' (al menos uno de los valores)
E con atributo 'lang="en-..."' (ignorando todo a partir del '-')
equivale a DIV[class~="warning"]
E con 'id="myid"'
Tema 4.1: CSS
32
¿A qué elementos se aplican las siguientes reglas?
• a:link img { border: solid blue }
• a.external:visited { color: blue }
• h2 + p:first-letter { font-size: 200%;}
• p.inicial:first-letter { color: red }
• h1 em { color: red }
Tema 4.1: CSS
33
Tema 4.1: CSS
34
Propiedad Valores Notas
font-family arial, verdana,
"Times New Roman"
Si se especifican varias, el navegador
parará en la primera que exista
font-size 120% Cualquier medida; mejor si relativa
font-style italic
normal Cursiva o no
font-weight bold
normal Negrita o no
text-decoration
overline
line-through
underline
none
Subrayados y tachados; el subrayado
mejor reservarlo para enlaces (es la
tradición en web)
line-height 150%
normal
Interlineado. Cualquier medida vale
en lugar de ese 150%
text-align
left
right
center
justify
Alineamiento del texto
Tema 4.1: CSS
35
Unidades de medida
• font-size: 80% Porcentaje (sobre el valor heredado)
• font-size: 2em 2x tamaño de la fuente actual (heredado)
• font-size: 12pt Puntos
• font-size: 12px Píxeles
• font-size: 12mm Valen también cm, in, etc. (no recomendado)
Colores
• color: red Rojo; hay otros 17 predefinidos
• color: #ff0000 Rojo en hexadecimal (rr, gg, bb)
• color: rgb(255, 0, 0) Rojo en decimal (valores del 0 al 255)
• color: rgb(100%,0%, 0%) Rojo en porcentajes
• color: transparent Transparente; cuenta como color
Tema 4.1: CSS
36
Muchas propiedades CSS tienen una 'versión condensada'
• Declara todos (o casi todos) los valores de una vez.
• Los no-declarados se ponen a sus valores por defecto.
p { font: bold 9px Charcoal }
condensada
p {
font-family: Charcoal;
font-style: normal;
font-variant: normal;
font-weight: bold;
font-size: 9px;
line-height: normal;
}
expandida
Tema 4.1: CSS
37
Propiedad Valores Notas
list-style-type
circle
square
decimal
lower-alpha
..
Tipo de marcador a usar en la
lista
list-style-position inside
outside
Si el marcador se incluye
dentro del texto o fuera (lo
habitual)
list-style-image url("cuadrado.png")
none Imagen para los marcadores
Tema 4.1: CSS
38
• Modelo de bloques o cajas.
• Modelo flexible: flexbox.
• Modelo de rejilla.
Los dos últimos se verán en el siguiente
tema.
Tema 4.1: CSS 39
Tema 4.1: CSS
40
Para cada etiqueta HTML se crea una caja rectangular
que encierra los contenidos de ese elemento
• El tamaño de cada área (margin, border, padding) se define con
propiedades relativas a las cuatro direcciones
margin (márgen)
border (borde)
padding (relleno)
Contenido
color background
incoloro, transparente
Tema 4.1: CSS
41
El texto de un documento debe estar contenido por alguna de las
etiquetas de una de las tres categorías siguientes:
• Etiquetas de bloque
• Pueden aparecer directamente dentro de <body>
• No se deben anidar
• <p> <pre> <h1> <h2> <h3> <h4> <h5> <h6> <address>
• Etiquetas inline:
• Se usan dentro de los bloques
• No deben aparecer fuera de los bloques
• Afectan a una parte de texto dentro de un bloque
• <br /><abbr> <cite> <code> <em> <kbd> <strong> <sub> <sup>
• Etiquetas de contenedores de bloques:
• Sirven para estructurar el texto y definir agrupaciones de bloques
• Pueden contener etiquetas de bloque u otros contenedores anidados
• <body>
• <blockquote>, <div>, <article>, <header>, <section>, <aside>, <nav>,
<footer>
Tema 4.1: CSS
42
En cada caja se pueden definir atributos para:
• El contenido (width y height).
• El relleno:
• padding-top, padding-right, padding-bottom y padding-left.
• El borde:
• border-top, border-right, border-bottom y border-left.
• Tiene normalmente su propio color y otras propiedades (tipo de
línea, etc.).
• El margen externo:
• margin-top, margin-right, margin-bottom y margin-left.
• Normalmente hereda el color del elemento padre.
• El fondo (background) del contenido y relleno.
Las medidas en modo resumido se indican en el orden
• Top, Right, Bottom, Left (TRouBLe).
• Si no se indican las cuatro, las que faltan se consideran por defecto.
Tema 4.1: CSS
43
Propiedad Valores Notas
margin top right bottom left Todos los márgenes a la vez
margin-left auto
2em
Auto = por defecto;
Cualquier medida vale; típico usar
'em' o porcentajes
padding-left auto
10% Similar a margin-left
width auto
100%
Ancho del contenido (no tiene en
cuenta padding, margin o border)
height auto
100%
Alto (mismas salvedades que
width)
NOTA: los porcentajes son sobre el tamaño total de su contenedor
Tema 4.1: CSS
44
Propiedad Valores Notas
border-width
thin
medium
thick
1px
Ancho de un borde; lo típico es usar píxeles
border-color red Cualquier color vale
border-style
none
dotted
dashed
solid
double
ridge
...
Tipo de borde; sin borde, a puntitos, con
guiones, sólido, doble-línea, etcétera
border-top-width (ver border-width)
Es posible cambiar independientemente los
anchos, tamaños y colores de los bordes,
usando top, bottom, left, right
border ancho estilo color Versión condensada: todo a la vez
Tema 4.1: CSS
45
Fuente: https://www.w3.org/TR/css-backgrounds-3/#the-border-style
Tema 4.1: CSS 46
Propiedad Valores Notas
background-color
color (e.g. ‘red’)
transparent
inherit
Por defecto todos los fondos son
transparentes
background-image
url
none
inherit
url puede ser cualquier dirección
relativa y absoluta.
background-repeat
repeat
repeat-x
repeat-y
no-repeat
Sólo si el fondo es una imagen.
Controla si se repite la imagen en caso
de ser más grande.
background-
attachment
scroll
fixed
¿Qué ocurre con el fondo cuando se
hace scroll en la página?
Tema 4.1: CSS
47
¿Cuánto ocupará el siguiente bloque?
div {
width: 400px;
padding-left: 60px;
padding-right: 60px;
margin-left: 40px;
margin-right: 40px;
border: 10px solid black;
}
margin (márgen)
border (borde)
padding (relleno)
Contenido
color background
incoloro, transparente
Tema 4.1: CSS
48
● Cada elemento tiene predefinido un tipo de caja.
● Puede modificarse con la propiedad display
○ block: caja rectangular que forma un bloque (p.ej. div).
○ inline: caja en-línea que puede ocupar varias líneas y está incluida en una
caja de tipo block (p.ej. span).
○ inline-block: se formatea como una caja de tipo block pero se coloca en
línea.
○ run-in: se añade al principio del elemento siguiente como in-line.
○ list-item: caja de tipo block pero que también incluye un marcador.
○ Cajas que forman parte de una tabla:
■ table, table-caption, table-cell, table-column, table-column-group,
table-footer-group, table-header-group, table-row, table-row-group,
inline-table
○ none: hace que no se genere caja ⇒ la caja desaparece.
Tema 4.1: CSS
49
Todo elemento HTML se representa según un tipo de caja
• block (bloque): forma un bloque independiente.
• p / div usad 'div' para estilar bloques estándar.
• inline (en línea): puede convivir en una línea con sus vecinos.
• a / span usad 'span' para estilar bloques inline.
• inline-block: como un bloque en un entorno inline.
• none (nada): no genera caja alguna; invisible.
Tema 4.1: CSS
50
display permite definir el tipo de caja de un elemento:
• block
• Siempre empiezan en una nueva línea y ocupan todo el espacio disponible hasta el
final de la línea.
• div, p, h1 - h6, form, header, footer, section.
• inline
• Adyacente a otros elementos, sólo ocupa el espacio necesario para mostrar sus
contenidos.
• No se pueden definir sus propiedades height o width (aunque sí line-height).
• span, a, cite, code, dfn, em, input, q.
• inline-block
• Para img.
• Igual que inline pero se pueden definir height y width.
• none
• Hace que el elemento sea invisible y no ocupe espacio.
• Con el atributo visibility: hidden el elemento se puede hacer invisible pero sigue
ocupando su espacio (que queda vacío).
• Se usa en JavaScript para ocultar o mostrar elementos sin eliminarlos ni recrearlos.
Tema 4.1: CSS 51
Se puede cambiar el tipo usando la propiedad display
• Bloques como inline
... <body>
... <body>
uno
uno
<ul><li>dos </li>
<ul><li>dos </li>
<li>tres </li></ul>
<li>tres </li></ul>
cuatro
cuatro
</body> ...
</body> ...
ul, li { display: inline; }
ul, li { display: inline; }
ul, li { display: inline; }
ul, li { display: inline; }
ul { padding-left: 0; }
ul { padding-left: 0; }
Tema 4.1: CSS
52
• Inlines como bloques
... <body>
... <body>
<p>Lorem ipsum dolor sit amet,</br>
<p>Lorem ipsum dolor sit amet,</br>
consectetur adipiscing elit,</br>
consectetur adipiscing elit,</br>
sed do <span>eiusmod</span>
sed do <span>eiusmod</span>
tempor</br> incididunt ut labore et
tempor</br> incididunt ut labore et
</br>dolore magna aliqua.</br> </p>
</br>dolore magna aliqua.</br> </p>
</body>...
</body>...
span {border: 2px solid red;} span {border: 2px solid red;}
span {border: 2px solid red;
span {border: 2px solid red;
display:block;}
display:block;}
span {border: 2px solid red;
span {border: 2px solid red;
display:none;}
display:none;}
Tema 4.1: CSS
53
• Inline-block
• Se comportan como elementos inline pero pueden tener
width y height.
• inline: margin-left, margin-right, padding-left, padding-right
• inline-block: margin, padding, height, width
... <body>
... <body>
<p>Lorem ipsum dolor sit amet,</br>
<p>Lorem ipsum dolor sit amet,</br>
consectetur adipiscing elit,</br>
consectetur adipiscing elit,</br>
sed do <span>eiusmod</span>
sed do <span>eiusmod</span>
tempor</br> incididunt ut labore et
tempor</br> incididunt ut labore et
</br>dolore magna aliqua.</br> </p>
</br>dolore magna aliqua.</br> </p>
</body>...
</body>...
span {border: 2px solid red;
span {border: 2px solid red;
display:inline-block;
display:inline-block;
height: 50px; width: 90px;}
height: 50px; width: 90px;}
Tema 4.1: CSS
54
Observa con el navegador Chrome las cajas de la página
http://informatica.ucm.es/
• Al seleccionar cada elemento aparece en la derecha los
atributos de la caja correspondiente.
• Se pueden editar los estilos y ver qué ocurre en la página.
Tema 4.1: CSS
55
Tema 4.1: CSS
56
Los elementos anidados heredan las propiedades de sus
padres salvo que otro elemento tenga una propiedad
contraria.
body {
color: red;
}
p {
color: blue;
}
<body>
<h1>Sección 1</h1>
<p>Párrafo</p>
<pre>Otro texto</pre>
</body>
• Resultado
• “Sección 1" aparecerá en rojo.
• “Párrafo” aparecerá en azul.
• “Otro texto” aparecerá en rojo.
Tema 4.1: CSS
57
¿Qué ocurre cuando hay dos declaraciones
contradictorias?
p { color: red; }
p { color: blue; }
En general, la regla básica es:
• Cuanto más específico sea un selector, más importancia tiene su
regla asociada.
• A igual especificidad, se considera la última regla indicada.
• De ficheros externos a estilos dentro del propio elemento.
• En una lista de reglas en el mismo ámbito, se elige la última.
Tema 4.1: CSS
58
El proceso para resolver la regla que se aplica es el siguiente:
1. 2. 3. Buscar todas las declaraciones aplicables a un elemento.
Si no existen reglas, se usa el valor heredado.
• Si no hubiera valor heredado, el navegador usa el valor por
defecto.
Si existen reglas, se aplica la de mayor peso de acuerdo a los
siguientes criterios:
• Se asigna un peso según su origen.
• Si un atributo tiene la palabra clave !important se le da mayor
prioridad.
• selector { atributo:valor !important ; atributo: valor ; }
• Se da mayor prioridad a los selectores más específicos.
• etiqueta << clase << ID
• Si después de aplicar las normas anteriores existen dos o más
reglas con la misma prioridad, se aplica la que se indicó en último
lugar.
Tema 4.1: CSS
59
Licencia Creative Commons
 Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Juan Pavón Mestras y Manuel Freire Morán, con
modificaciones de Pablo Moreno Ger, Raquel Hervás Ballesteros y Javier
Bravo Agapito.
Tema 4.1: CSS
60


4
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Juan Pavón Mestras y Manuel Freire Morán, con
Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo Moreno
Material elaborado por Juan Pavón Mestras y Manuel Freire Morán,
Ger, Manuel Freire Morán, Raquel Hervás Ballesteros, Carlos Cervigón, Iván
Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo Moreno Ger,
modificaciones de Pablo Moreno Ger, Raquel Hervás Ballesteros y Javier
con modificaciones de Pablo Moreno Ger, Raquel Hervás Ballesteros y
Martínez y Javier Bravo.
Manuel Freire Morán, Raquel Hervás Ballesteros, Carlos Cervigón e Iván Martínez.
Bravo Agapito
Javier Bravo Agapito
• Posicionamiento: static, relative, absolute, fixed, float.
• Organización de la página.
• Trucos de posicionamiento.
• Flexbox.
• Responsive design y media queries.
• Validación de CSS.
• Grid.
• Bootstrap.
• HTML: elementos incrustados.
Tema 4.2. Posicionamiento y contenido incrustado 2
Tema 4.2. Posicionamiento y contenido incrustado
3
Tema 4.2. Posicionamiento y contenido incrustado 4
Lienzo: rectángulo en el que se dibuja la página.
(puede ser mayor que la ventana)
Ventana: rectángulo a través del cual se mira al lienzo.
lienzo lienzo
lienzo lienzo
ventana ventana
ventana ventana
La 'caja base' (body) de CSS adopta (si puede) el ancho de
la ventana actual (si no puede, saca barras de scroll)
Tema 4.2. Posicionamiento y contenido incrustado
5
Los elementos pueden posicionarse de distintas formas,
según se especifique con la propiedad position:
• Estática (position: static) - posicionamiento por defecto; la
posición en la que quedaría en un "flujo normal“.
• Relativa (position: relative) - desplazado de su posición estática
estándar usando propiedades top, right, bottom y left.
• Absoluta (position: absolute)- eliminado del flujo normal, y
posicionado en una posición fija con respecto al lienzo; puede
superponerse a otros elementos si no se le reserva espacio.
• Fija (position: fixed)- igual que el posicionamiento absoluto, pero
especificando la posición con respecto a la ventana.
• Flotante- llevado a uno de los extremos de la caja contenedora, pero
afectando al flujo normal de otros elementos, que le reservan
espacio.
Tema 4.2. Posicionamiento y contenido incrustado
6
La mejor forma de comprender el funcionamiento del
posicionamiento es probándolo
 Se puede usar Codepen para hacer pruebas rápidamente
<body>
<div id="a"></div>
<div id="b">
<div id="c"></div>
</div>
<p>Lorem ipsum dolor
sit amet, consectetur
adipisicing elit.
Maiores est ... </p>
<p>Lorem ipsum dolor
sit amet, consectetur
adipisicing elit.
Doloremque ... </p>
</body>
div#a {
background: blue;
width: 8em;
height: 16em; }
div#b {
background: red;
width: 16em;
height: 8em; }
div#c {
background: pink;
width: 4em;
height: 4em; }
p {
posicionamiento.html
posicionamiento.css
font-size: 80px;
background: yellow;
margin: 0; padding: 0; }
Tema 4.2. Posicionamiento y contenido incrustado 7
• Posicionamiento por defecto (no hace falta
especificarlo)
• Las cajas se colocan seguidas verticalmente de arriba
abajo, en el orden en el que aparecen los elementos
correspondientes en el documento
• Sólo se tiene en cuenta si el elemento es de bloque o en
línea, sus propiedades width y height y su contenido.
Elemento de bloque
bloques
Elemento de bloque
Elemento en línea Elemento en línea
Elemento de bloque
Elemento de bloque
líneas
Tema 4.2. Posicionamiento y contenido incrustado
8
La caja se desplaza respecto de su posición original
• El desplazamiento de la caja se controla con las propiedades top, right,
bottom y left
• Por defecto top, right, bottom y left tienen el valor auto.
• El valor positivo de alguno de estos valores implica un desplazamiento:
• top hacia abajo desde el borde superior, bottom hacia arriba.
• left hacia la derecha desde el borde izquierdo, right hacia la izquierda.
• El resto de las cajas no se ven afectadas (no ocupan el espacio que ha dejado
la anterior)
Figura de: http://librosweb.es/css/capitulo_5/posicionamiento_relativo.html
Tema 4.2. Posicionamiento y contenido incrustado
9
Cambia el posicionamiento del bloque ”a” a relative y
con un espaciado superior de 4em e izquierdo de 8em.
• Verás que el bloque azul se superpone al rojo pero se reserva el
espacio que debería haber ocupado el bloque azul.
Tema 4.2. Posicionamiento y contenido incrustado 10
Cambia el posicionamiento del bloque ”c” a relative y con
un espaciado superior de 4em e izquierdo de 8em.
• Verás que, en este caso, el bloque rosa cambia su posición con
respecto a su contenedor (es decir, el bloque rojo)
Tema 4.2. Posicionamiento y contenido incrustado 11
img.movida {
position: relative;
top: 8em;
}
<img class="movida" src=“gato.png" alt="Imagen genérica" />
<img src=“gato.png" alt="Imagen genérica" />
<img src="igato.png" alt="Imagen genérica" />
Tema 4.2. Posicionamiento y contenido incrustado 12
La posición de la caja se determina con las propiedades top,
right, bottom y left respecto al contenedor padre (si no
hubiera, entonces se usa el <body>)
El resto de las cajas sí pueden verse afectadas.
• Pueden ocupar la posición que ha dejado la anterior.
• Se pueden producir solapamientos.
• Sirve cuando quieres que haya un elemento siempre en el mismo sitio
de la página.
Figura de: http://librosweb.es/css/capitulo_5/posicionamiento_absoluto.html
Tema 4.2. Posicionamiento y contenido incrustado
13
Cambia el posicionamiento del bloque ”a” a absolutey
con un espaciado superior de 4em e izquierdo de 8em.
• Verás que el bloque azul se superpone al rojo pero que este
último no ha reservado el espacio que debería haber ocupado el
bloque azul, por lo que el rojo se coloca en la esquina superior
izquierda de la pantalla. Verás que si haces scroll los bloques
dejan de verse.
Tema 4.2. Posicionamiento y contenido incrustado
14
Igual que el anterior pero las cajas correspondientes
están fijas respecto a la ventana
Ejercicio para practicar:
• Cambia el posicionamiento del bloque ”a” a fixed y con un
espaciado superior de 4em e izquierdo de 8em.
• Como antes, el bloque rojo no ha reservado el espacio del
bloque azul. En cambio, si hacemos scroll veremos que el
bloque azul sigue en una posición fija con respecto a la
ventana.
Tema 4.2. Posicionamiento y contenido incrustado 15
Una caja flotante:
• Se declara con float:right o float:left (no hace falta poner
position).
• Abandona su posición y se desplaza horizontalmente hasta la
zona más a la izquierda o más a la derecha posible.
Las cajas flotantes dejan de ser parte del flujo normal de
la página:
• El resto de cajas rellenan el hueco dejado.
• Dicho de otro modo: las cajas flotantes tapan al resto.
Tema 4.2. Posicionamiento y contenido incrustado
16
La caja 1 tapa los contenidos de la caja 2
Figuras de:
https://uniwebsidad.com/libros/css/capitulo-5/
Tema 4.2. Posicionamiento y contenido incrustado
17
Las cajas flotantes tapan al contenido no flotante, y se
“apilan” con el contenido flotante.
• Si hay otras cajas flotantes, se van “apilando” o alineando (a
izquierda o derecha).
• Si no existe sitio en la línea actual, la caja flotante baja a la línea
inferior hasta encontrar hueco.
Figuras de:
https://uniwebsidad.com/libros/css/capitulo-5
Tema 4.2. Posicionamiento y contenido incrustado
18
Cierre de flotación con la propiedad clear
• Fuerza a que el elemento se muestre debajo de cualquier caja
flotante
• Si clear: left/right, el elemento se desplaza de forma
descendente hasta que pueda colocarse en una línea en la
que no haya ninguna caja flotante en el lado
izquierdo/derecho.
• Si clear: both, el elemento se colocará debajo de
cualquier caja flotante.
Tema 4.2. Posicionamiento y contenido incrustado
19
div#paginacion {
border: 1px solid #CCC;
background-color: #E0E0E0;
padding: .5em;
}
.derecha {
float: right;
}
.izquierda {
float: left;
}
div.clear {
clear: both;
}
<body>
<div id="paginacion">
<span class="izquierda">&laquo;
Anterior</span>
<span class="derecha">Siguiente &raquo;</span>
<div class="clear"></div>
</div>
</body>
Tema 4.2. Posicionamiento y contenido incrustado 20
Cambia el posicionamiento del
bloque ”a” a float añadiendo la propiedad float:right
• Verás que el bloque azul se ha colocado junto al borde derecho
de la ventana y que no se le ha reservado espacio (por lo que se
superpone al resto de los elementos).
Tema 4.2. Posicionamiento y contenido incrustado 21
Cambia el posicionamiento del bloque c a floatañadiendo la propiedad float:right
• Verás que el bloque rosa se ha pegado al lado derecho de su
contenedor, el bloque rojo.
Cambia el posicionamiento del bloque b a floatañadiendo la
propiedad float:right
• Verás que el bloque rojo se ha pegado al lado derecho hasta que
ha chocado con el elemento flotante anterior, el bloque azul
Tema 4.2. Posicionamiento y contenido incrustado 22
Cambia el posicionamiento del bloque ”a” a float:left.
Añade a ”p” la propiedad clear: right
• Verás que el párrafo se coloca debajo de la caja roja pero se
superpone a la caja azul.
Cambia en ”p” la propiedad clear: both
• Verás que ahora el párrafo se ha colocado justo debajo de la
caja azul.
Tema 4.2. Posicionamiento y contenido incrustado 23
Tema 4.2. Posicionamiento y contenido incrustado
24
Para estructurar una página
• Definir bloques con <div> y su composición
• Cuando sea apropiado, usar <header>, <main>, <article>,
<section>, etc.
• A cada bloque asignarle una clase o un id.
• Cada bloque <div> puede constar de otros bloques <div>.
• A nivel de línea se pueden definir cajas con <span>.
• Asociar propiedades de posicionamiento y visualización a cada
una de las cajas definidas con clase o id.
Consultar ejemplos y buenas prácticas en
https://uniwebsidad.com/libros/css/capitulo-12
Tema 4.2. Posicionamiento y contenido incrustado
25
Diseño a 2 columnas con cabecera y pie de página
• De: http://librosweb.es/css/capitulo_12/estructura_o_layout.html
• Uso de float y clear
#contenedor {
width: 700px;
<body>
<div id="contenedor">
<div id="cabecera">
</div>
}
#cabecera {
}
#menu {
float: left;
width: 150px;
<div id="menu">
</div>
}
#contenido {
float: left;
width: 550px;
<div
id="contenido">
</div>
}
#pie {
clear: both;
}
<div id="pie">
</div>
</div>
</body>
Tema 4.2. Posicionamiento y contenido incrustado
26
Diseño a 2 columnas con cabecera y pie de página
• Con anchura variable
#contenedor {
}
#cabecera {
}
#menu {
float: left;
width: 15%;
<body>
<div id="contenedor">
<div id="cabecera">
</div>
<div id="menu">
</div>
}
#contenido {
float: left;
width: 85%;
<div
id="contenido">
</div>
}
#pie {
clear: both;
}
<div id="pie">
</div>
</div>
</body>
Tema 4.2. Posicionamiento y contenido incrustado
27
Centrar una página horizontalmente con ancho determinado
• Agrupar todos los contenidos de la página en un elemento <div>.
• Asignarle a ese <div> unos márgenes laterales automáticos con la
propiedad margin de CSS.
#contenedor {
width: 800px;
margin: 0 auto;
}
<body>
<div id="contenedor">
<h1>Sección 1</h1>
<p> bla bla bla </p>
...
</div>
</body>
Tema 4.2. Posicionamiento y contenido incrustado
28
Centrar una imagen horizontalmente
• Poner la imagen en un elemento <div>.
• Asignarle a ese <div> una alineación de texto centrada.
#contenedor {
text-align:center;
}
<body>
<div id="contenedor">
<img src="gato.png"/>
</div>
</body>
Tema 4.2. Posicionamiento y contenido incrustado
29
Propiedades para limitar la anchura y altura mínima y máxima de
cualquier elemento de la página
• min-width: anchura mínima de un elemento.
• Por defecto: 0
• max-width: anchura máxima de un elemento.
• Por defecto: none
• min-height: altura mínima de un elemento.
• max-height: altura máxima de un elemento.
• Se pueden dar como un valor numérico o un porcentaje.
#contenedor {
min-width: 300px;
max-width: 800px;
}
Tema 4.2. Posicionamiento y contenido incrustado
30
Tema 4.2. Posicionamiento y contenido incrustado
31
El posicionamiento flexible o flexbox permite un control
más fino del posicionamiento de los elementos en la
página
• Facilita acomodar los elementos de una página según cambien
las dimensiones y orientación de la página.
• Es apropiado para colocar pequeños componentes de una
página, pero menos para organizar la estructura general de la
página.
• Para aprender: https://flexboxfroggy.com/#es
Tema 4.2. Posicionamiento y contenido incrustado 32
Flexbox se basa en la creación de contenedores flex que
contienen elementos.
• Los contenedores expanden o comprimen sus elementos para
rellenar el espacio libre o ajustarse al área disponible.
Tendremos dos tipos de elementos:
• El contenedor flex (flex container).
• Los elementos dentro del contenedor flex (flex items).
El contenedor flex se define con display:flex
• Todos los elementos dentro de ese contenedor se tratarán como
elementos flex.
Tema 4.2. Posicionamiento y contenido incrustado 33
No se habla de orientación horizontal o vertical, sino que
se definen dos ejes que se pueden cambiar:
• Eje principal (main axis).
• Eje secundario (cross axis).
El diseño se puede adaptar fácilmente al cambio de
orientación de los dispositivos.
cross start
flex container
main axis
flex item flex item
cross end
cross axis
main start main end
Tema 4.2. Posicionamiento y contenido incrustado 34
Vamos a seguir las distintas características del modelo con un
ejemplo
.contenedor{
width: 100%;
border-style: solid;
border-color: #3F51B5;
border-width: 4px;
margin-top: 15px;
display: flex;
flexible.html
flexible.css
}
.contenedor>div{
background-color: #FF4081;
color: white;
text-align: center;
width: 50px;
font-size: 3em;
margin: 4px;
}
<body>
<div class="contenedor">
<div>1</div>
<div>2</div>
<div>3</div>
<div>4</div>
<div>5</div>
</div>
<div class="contenedor">
. . .
</div>
</body>
Ejemplo de J.A. Recio García: HTML5, CSS3 y JQuery. Curso práctico. Ra-ma 2016
Tema 4.2. Posicionamiento y contenido incrustado 35
flex-direction: indica cuál es el eje principal en el que se
organizan los elementos.
• row (fila), row-reverse (fila invertida), column (columna),
column-reverse (columna invertida)
flex-direction: row
flex-direction: row-reverse
flex-direction:
column
flex-direction:
column-reverse
Tema 4.2. Posicionamiento y contenido incrustado 36
.contenedor{
flexible.css
flexible.html
width: 100%;
border-style: solid;
border-color: #3F51B5;
border-width: 4px;
margin-top: 15px;
display: flex;
}
<body>
<div id="cont01" class="contenedor">
<div>1</div>
<div>2</div>
<div>3</div>
<div>4</div>
<div>5</div>
</div>
</body>
.contenedor>div{
background-color:
#FF4081;
color: white;
text-align: center;
width: 50px;
font-size: 3em;
margin: 4px;
}
#cont01{
flex-direction: row;
}
Ejemplo de J.A. Recio García: HTML5, CSS3 y JQuery. Curso práctico. Ra-ma 2016
Tema 4.2. Posicionamiento y contenido incrustado 37
flex-wrap: Indica si los elementos se fuerzan a que
ocupen una sola línea o si pueden ocupar varias.
• nowrap (ajusta los elementos a una línea).
• wrap (los distribuye en líneas sin cambiar de tamaño).
• wrap-reverse (como el anterior, pero en orden inverso de filas).
flex-wrap: nowrap
flex-wrap: wrap
flex-wrap: wrap-reverse
Tema 4.2. Posicionamiento y contenido incrustado 38
justify-content: indica cómo se organiza el espacio
sobrante de los elementos con respecto al eje en el que
se organizan.
• flex-start (junta los elementos al principio del eje).
• flex-end (junta los elementos al final del eje).
• center (centra los elementos en el eje).
• space-between (reparte el espacio entre los elementos dejando
los extremos pegados al borde).
• space-around (todo el espacio restante se reparte alrededor de
cada elemento).
Tema 4.2. Posicionamiento y contenido incrustado 39
justify-content: flex-start
justify-content: flex-end
justify-content: center
justify-content: space-between
justify-content: space-around
Tema 4.2. Posicionamiento y contenido incrustado 40
align-items: indica la organización de una línea de
elementos con respecto al eje secundario (que es el
perpendicular al que se organizan)
• stretch (para ocupar todo el espacio).
• flex-start (para alinear al principio del eje secundario).
• flex-end (para alinear al final del eje secundario).
• center (para centrar en el eje secundario).
Tema 4.2. Posicionamiento y contenido incrustado 41
align-items: stretch
align-items: flex-start
align-items: flex-end
align-items: center
Tema 4.2. Posicionamiento y contenido incrustado 42
Hasta ahora hemos visto propiedades para configurar el
contenedor principal, pero los elementos también tienen
algunas opciones de configuración:
• order: por defecto; todos los elementos se organizan según
están en el HTML, pero podemos cambiar la posición original
utilizando esta propiedad.
• flex-basis: define el tamaño en el eje principal del elemento
antes de que el espacio disponible se distribuya.
• Acepta una unidad de medida (como width o height).
Tema 4.2. Posicionamiento y contenido incrustado 43
• flex-grow: define la forma en la que el elemento va a crecer
relativo al resto de los items (si queda espacio disponible)
• Se usa un número que representa una proporción (por
ejemplo, un 2 indica que podrá alcanzar el doble de tamaño
que el resto de los elementos).
• flex-shrink: define la forma en la que el elemento va a encoger si
se va a superar el tamaño del contenedor.
• Se usa un número que representa una proporción (por
ejemplo, un 2 indica que se encogerá a la mitad).
Tema 4.2. Posicionamiento y contenido incrustado 44
#item1{
order: 3;
}
#item2{
<body>
<div class="contenedor">
<div id="item1">1</div>
<div id="item2">2</div>
<div id="item3">3</div>
<div id="item4">4</div>
<div id="item5">5</div>
</div>
</body>
order: 1;
flex-grow: 2;
}
#item3{
order: 2;
}
#item4{
order: 5;
}
#item5{
order: 4;
}
Tema 4.2. Posicionamiento y contenido incrustado 45
flex-flow: Es una forma abreviada de unir flex-direction y
flex-wrap
• flex-flow: row nowrap
flex: Es una forma abreviada de unir flex-grow, flex-shrink
y flex-basis
• flex: 2 1 auto
Tema 4.2. Posicionamiento y contenido incrustado 46
Tema 4.2. Posicionamiento y contenido incrustado
47
Diseño web adaptativo (Responsive web design)
• Diseño de páginas web para que el usuario las visualice
perfectamente en un amplio rango de dispositivos.
Mobile first
móvil.
• Hacer los diseños pensando primero en cómo se verán en el
• Y evitar que el usuario tenga que andar haciendo zoom...
Móvil
Desktop Tablet
Tema 4.2. Posicionamiento y contenido incrustado
48
Visualiza una web cualquiera con Chrome y prueba
cómo se adapta a distintos tamaños de pantalla
• Developer tools
• Botón Toggle device toolbar
En Firefox
• Desarrollador → Vista de diseño adaptable
• Permite usar varios tamaños predefinidos
• Hacer fotos de cómo queda
Tema 4.2. Posicionamiento y contenido incrustado
49
Tipo especial de reglas CSS para indicar el medio o medios en los
que se aplicarán los estilos incluidos en la regla
• El medio en el que se aplican los estilos (opcional), se indica después de
@media.
• Si los estilos se aplican a varios medios, se incluyen los nombres de todos los
medios separados por comas.
• Además de los medios se pueden usar características del medio (entre
paréntesis).
@media print {
body { font-size: 10pt }
}
@media screen, print {
body { line-height: 1.2 }
}
@media screen and (min-width: 700px) {
body { font-size: 44px }
}
Tema 4.2. Posicionamiento y contenido incrustado
50
Las características se expresan de forma similar a
propiedades CSS
• width y height: anchura y altura del área del display (viewport)
@media screen and (min-width: 400px) and (max-width: 700px) { … }
• orientation: portrait | landscape
@media all and (orientation:portrait) { … }
• aspect-ratio (width/height)
@media screen and (aspect-ratio: 16/9) { … } /* pantalla 16:9 */
• color: para indicar si el dispositivo tiene colores (y cuántos) o es
monocromo
@media screen and (min-color: 16) { … } /* 16-bit color */
Tema 4.2. Posicionamiento y contenido incrustado 51
@media [mediatype and] (media feature){
CSS-Code;
}
• width y height: anchura y altura del área del display (viewport)
@media screen and (min-width: 400px) and (max-width: 700px) { … }
• orientation: portrait | landscape
@media all and (orientation:portrait) { … }
• aspect-ratio (width/height)
@media screen and (aspect-ratio: 16/9) { … } /* pantalla 16:9 */
• color: para indicar si el dispositivo tiene colores (y cuántos) o es
monocromo
@media screen and (min-color: 16) { … } /* 16-bit color */
Tema 4.2. Posicionamiento y contenido incrustado 52
Pueden ser más complejas
• Se pueden definir varias restricciones que deben cumplirse con and
• Separadas por comas serían varias opciones (la coma equivale a OR)
• Se puede negar el resultado de una query con not
• Para cualquier medio se puede especificar all
• Si se aplica un parámetro que no corresponde al dispositivo, el
resultado de la media query será false
Ejemplos:
@media screen and (color), speech and (color) { ... }
@media not screen and (orientation: landscape) {...}
<link rel="stylesheet" media="screen and (color)" href="color.css" />
Tema 4.2. Posicionamiento y contenido incrustado
53
• Normalmente se declaran primero los estilos aplicables a todos
los casos.
• Y posteriormente los casos particulares para tipos de
dispositivos específicos.
• Ejemplo:
• Declarar los enlaces (links) como enlaces de texto normales.
• Para el caso de smartphones, asignarles un tamaño mayor
que facilite el seleccionarlos con los dedos.
Tema 4.2. Posicionamiento y contenido incrustado 54
Hay que distinguir dos conceptos
• Terminal del dispositivo físico.
• Viewport: el área visible al usuario de una página web.
El viewport varía según el dispositivo (será más pequeño
en un móvil que en una pantalla)
• En los inicios las páginas web se diseñaban sólo para pantallas
(tamaño fijo).
• Al navegar con los móviles y tablets, los navegadores “encogen”
la página completa para que quepa en el viewport.
Tema 4.2. Posicionamiento y contenido incrustado 55
En HTML5 se da control al desarrollador sobre el uso del viewport
<meta name="viewport" content="width=device-width, initial-scale=1.0">
• width=device-widthhace que la página tenga el ancho de la pantalla del
dispositivo.
• initial-scale=1.0indica el nivel de zoom inicial cuando la página es
cargada por el navegador.
Tema 4.2. Posicionamiento y contenido incrustado 56
Los usuarios suelen hacer scroll vertical, no horizontal
• Ajustar el viewport al ancho del dispositivo y poner el zoom
inicial a 1.
• Comprobar que los elementos se adaptan y pueden verse bien
con distintas anchuras de viewport.
• Usar media queries para aplicar distintos estilos según el tipo de
pantalla.
• Utilizar valores relativos/proporcionales y no absolutos para los
valores de anchura (p.e. width: 100%).
Tema 4.2. Posicionamiento y contenido incrustado 57
header {
height: 10%;
}
#principal {
display: flex;
flex-flow: row;
align-items: stretch;
min-height: 80%;
}
nav {
flex-basis: 20%;
}
<body>
<header>Esta es la cabecera</header>
<div id="principal">
<nav>Barra de navegación</nav>
<article>Contenido</article>
<aside>Barra lateral</aside>
</div>
<footer>Este es el pie de página</footer>
</body>
article {
flex-basis: 60%;
}
aside {
flex-basis: 20%;
}
footer{
height: 5%;
}
Ejemplo de J.A. Recio García: HTML5, CSS3 y JQuery. Curso práctico. Ra-ma 2016
Tema 4.2. Posicionamiento y contenido incrustado 58
¿Cómo queremos que se organicen los bloques por
debajo de 640px?
@media all and (max-width: 640px) {
#principal {
flex-flow: column;
}
article{
order: 1;
}
aside{
order: 2;
}
nav{
order: 3;
}
}
Tema 4.2. Posicionamiento y contenido incrustado 59
Estas características ya las aportan varios frameworks
que combinan CSS y JavaScript
• Bootstrap, de Twitter - http://getbootstrap.com/
• Foundation, de Zurb - http://foundation.zurb.com/
• Skeleton - http://getskeleton.com/
• HTML5 Boilerplate - https://html5boilerplate.com/
• Materialize - http://materializecss.com/
• Basado en Material Design (Google)
Recopilaciones y comparativas:
• http://designinstruct.com/roundups/html5-frameworks/
• https://colorlib.com/wp/html5-frameworks/
Tema 4.2. Posicionamiento y contenido incrustado 60
Al igual que con las páginas HTML, merece la pena validar
las hojas de estilo
http://jigsaw.w3.org/css-validator
Tema 4.2. Posicionamiento y contenido incrustado 61
Tema 4.2. Posicionamiento y contenido incrustado
62
Muchas mejoras frente a flexbox.
Trabajamos en una rejilla de dos dimensiones (grid
container)
El elemento principal es un container con una serie de
elementos sobre los que se aplican las propiedades.
• Contenedor principal
• Elementos del contenedor
.container {
display: grid;
}
Ejemplo obtenidos de
https://css-tricks.com/snippets/css/complete-guide-grid/
Tema 4.2. Posicionamiento y contenido incrustado 63
Propiedades del grid container:
Display, grid-template-columns, grid-template-rows, grid-template-areas, grid-
template-columns, grid-template-rows, . . .
Display: define el contenedor y un contexto de formato
de rejilla.
Valores:
• grid- genera una rejilla de bloque
• inline-grid– genera una rejilla de nivel interno
.container {
display: grid | inline-grid;
}
Tema 4.2. Posicionamiento y contenido incrustado 64
Propiedades del grid container:
grid-template-columns
grid-template-rows
Define las columnas y filas de la rejilla con una lista de
valores separados. Los valores representan el tamaño y el
espacio entre ellos representan la línea de la rejilla.
Valores:
• <track-size> - longitud, porcentaje o una fracción del
espacio libre del grid
• <line-name> - un nombre cualquiera
Tema 4.2. Posicionamiento y contenido incrustado 65
.container {
grid-template-columns: 40px 50px auto 50px 40px;
grid-template-rows: 25% 100px auto;
}
.container { grid-template-columns: [first] 40px [line2] 50px [line3] auto [col4-start] 50px [five] 40px [end];
grid-template-rows: [row1-start] 25% [row1-end] 100px [third-line] auto [last-line]; }
Tema 4.2. Posicionamiento y contenido incrustado 66
grid-template-areas
Define una plantilla de rejilla haciendo referencia a los nombres de las
áreas de cuadrícula que se especifican con la propiedad grid-area. Un
punto significa una celda vacía. La sintaxis en sí misma proporciona una
visualización de la estructura de la rejilla
Values:
<grid-area-name> - el nombre de un área de rejilla especificada
con grid-area
. Un punto significa celda vacía
none – sin áreas de rejilla definidas
Tema 4.2. Posicionamiento y contenido incrustado 67
.item-a {
grid-area: header;
}
.item-b {
grid-area: main;
}
.item-c {
grid-area: sidebar;
}
.item-d {
grid-area: footer;
}
Creará una rejilla de 4 columnas y 3 filas. La fila superior será
header, la fila central será 2 áreas de main, una celda vacía y
un sidebar área. La última fila es el footer.
.container {
display: grid;
grid-template-columns: 50px 50px 50px 50px;
grid-template-rows: auto;
grid-template-areas:
"header header header header"
"main main . sidebar"
"footer footer footer footer";
}
Tema 4.2. Posicionamiento y contenido incrustado 68
grid-column-gap
grid-row-gap
Especifica el tamaño de las líneas de la rejilla y el espacio
entre filas y columnas.
.container {
grid-template-columns: 100px 50px 100px;
grid-template-rows: 80px auto 80px;
grid-column-gap: 10px;
grid-row-gap: 15px;
}
Tema 4.2. Posicionamiento y contenido incrustado 69
Propiedades de los elementos del grid:
grid-column-start
grid-column-end
grid-row-start
grid-row-end
. .
• Determina la ubicación de un elemento de la rejilla haciendo referencia a líneas de
cuadrícula específicas.
• grid-column-start/grid-row-startes la línea donde comienza el elemento.
• grid-column-end/grid-row-end es la línea donde termina el elemento.
Valores:
• <line> puede ser un número para referirse a una línea de cuadrícula numerada, o un
nombre para referirse a una línea de cuadrícula con nombre.
• span <number>el elemento se extenderá a través del número proporcionado de pistas
de cuadrícula .
• span <name> el ítem se extenderá hasta que llegue a la siguiente línea con el nombre
proporcionado.
• auto indica auto-colocación, un span automático o un span por defecto de uno.
Tema 4.2. Posicionamiento y contenido incrustado 70
.container { grid-template-columns: [first] 40px [line2] 50px [line3] auto [col4-start] 50px [five] 40px [end];
grid-template-rows: [row1-start] 25% [row1-end] 100px [third-line] auto [last-line]; }
.item-a {
grid-column-start: 2;
grid-column-end: five;
grid-row-start: row1-start
grid-row-end: 3;
}
Mucho más en
https://css-tricks.com/snippets/css/complete-guide-grid/
Tema 4.2. Posicionamiento y contenido incrustado 71
Tema 4.2. Posicionamiento y contenido incrustado 72
Framework de Twitter para desarrollo de aplicaciones
web
• Sencillo y ligero
• Puede bastar con un fichero CSS y uno JavaScript
• Basado en los últimos estándares de desarrollo de Web
• HTML5, CSS3 y JavaScript/JQuery
• Facilita el Responsive Design
• Plugins de jQuery para validar entrada de datos, visualización
tablas, grafos, etc.
• Curva de aprendizaje baja.
• Compatible con todos los navegadores habituales.
• Código abierto. Publicado en 2011 con licencia Apache
Tema 4.2. Posicionamiento y contenido incrustado 73
Menú
1 2 Cabecera
Columna 1 3 4 5 6 7 8 9 10 11 12
Contenido principal Columna 2
Tema 4.2. Posicionamiento y contenido incrustado 74
Grid de 960px (basado en http://960.gs)
• Por defecto columnas de 60px y offset de 20px.
• Se adapta dependiendo del viewport.
• Por debajo de 768px (tabletas, smartphones) las columnas
pasan a fluid y se apilan verticalmente.
Tema 4.2. Posicionamiento y contenido incrustado 75
La página se estructura en bloques del grid.
Clases definidas en el grid de Bootstrap.
• container (y container-fluid)
• Se encarga de alinear y ajustar los márgenes adecuadamente
• No se pueden anidar
• row
• Grupos horizontales de columnas
• Columnas:
• Dentro de las filas (rows)
• col-md-1, col-md-2, col-md-3, … col-md-12
Tema 4.2. Posicionamiento y contenido incrustado 76
Clases para definir columnas
• col-md-*  La más habitual, para Desktop
• col-sm-*  Para tablets
• col-xs-*  Para móviles
• col-lg-*  Para pantallas muy grandes
Tema 4.2. Posicionamiento y contenido incrustado 77
Columna estrecha y contenido principal
Tema 4.2. Posicionamiento y contenido incrustado 78
Columna estrecha y contenido principal
<div class="row">
<div class="col-md-3">
<h2>Menú principal</h2>
<ul>
<li>Opción 1</li>
<li>Opción 2</li>
<li>Opción 3</li>
<li>Opción 4</li>
</ul>
</div>
<div class="col-md-9">
<h2>Contenido principal</h2>
<p>Lorem ipsum dolor ...</p>
</div>
</div>
Tema 4.2. Posicionamiento y contenido incrustado 79
Tres columnas
<div class="row">
<div class="col-md-4">
<p>Ésta es la columna 1.</p>
</div>
<div class="col-md-4">
<p>Ésta es la columna 2.</p>
</div>
<div class="col-md-4">
<p>Ésta es la columna 3.</p>
</div>
</div>
Tema 4.2. Posicionamiento y contenido incrustado 80
Cuatro columnas (adaptables a 2)
<div class="row">
<div class="col-md-3 col-xs-6">
<p>Ésta es la columna 1.</p>
</div>
<div class="col-md-3 col-xs-6">
<p>Ésta es la columna 2.</p>
</div>
<div class="col-md-3 col-xs-6">
<p>Ésta es la columna 3.</p>
</div>
<div class="col-md-3 col-xs-6">
<p>Ésta es la columna 4.</p>
</div>
</div>
Tema 4.2. Posicionamiento y contenido incrustado 81
Hay mucho más Bootstrap
• Menús de navegación
• Efectos de cajas
• Botones y formularios
• Contenedores con estilos
• Carrusel, jumbotron,…
•
…
Tema 4.2. Posicionamiento y contenido incrustado 82
Tema 4.2. Posicionamiento y contenido incrustado
83
Tema 4.2. Posicionamiento y contenido incrustado
84
• Aplica Responsive Web Design.
• Versión intermedia entre los modelos de posicionamiento
básicos y Bootstrap.
• Está pensado para aprender cómo crear un framework de CSS.
Tema 4.2. Posicionamiento y contenido incrustado 85
<object>
• Es una forma genérica de incluir elementos que son
interpretados por algún plugin.
• Atributos:
• data="URL" - Los datos que utiliza el objeto
• type="tipo-mime" - Tipo de contenido de los datos
• height="alto" y width="ancho"
• El navegador decidirá el plugin o acción que corresponda en
función del tipo
Ejemplos:
• <object data="video.swf" type="application/x-shockwave-
flash"> </object>
• <object data="pelicula.mpeg" type="application/mpeg" />
Tema 4.2. Posicionamiento y contenido incrustado
86
Recursos que podemos añadir mediante <object>
• Imágenes
• Vídeos
• Aunque es preferible usar <img>
• Archivos de sonido
• Applets de Java
• En HTML se usaba la etiqueta <applet>, que desaparece en HTML5
• Archivos PDF
• Controles ActiveX
•
…
Si el navegador no sabe abrir el contenido, solicitará al
usuario que descargue un plugin para ejecutarlo.
Tema 4.2. Posicionamiento y contenido incrustado
87
Para incluir vídeos en HTML5
<video width="320" height="240" controls>
<source src="movie.mp4" type="video/mp4">
<source src="movie.ogg" type="video/ogg">
Tu navegador no puede mostrar el vídeo.
</video>
Cuestiones importantes:
• El navegador reproducirá el primer vídeo compatible.
• Si no puede reproducir ninguno, mostrará el texto que no esté
incluido en ninguna etiqueta.
Tema 4.2. Posicionamiento y contenido incrustado
88
Para incluir sonidos en HTML5
<audio controls>
<source src="horse.ogg" type="audio/ogg">
<source src="horse.mp3" type="audio/mpeg">
Tu navegador no puede reproducir el sonido.
</audio>
Tema 4.2. Posicionamiento y contenido incrustado
89
No todos los navegadores soportan los mismos formatos
• Chrome
• Sonido: MP3, WAV, OGG
• Video: H.264+AAC, VP8+Vorbis, OGG
• Firefox
• Sonido: WAV, OGG (MP3 sólo en Windows)
• Video: VP8+Vorbis, OGG (H.264 sólo en Windows)
• Internet Explorer
• Sonido: MP3
• Video: H.264+AAC
Internet Explorer y Firefox en Linux son
mutuamente excluyentes, por lo que
siempre será necesario ofrecer más
• Safari
de una alternativa.
• Sonido: MP3
• Video: H.264+AAC
Tema 4.2. Posicionamiento y contenido incrustado
90
<track>
• Para incluir archivos de texto en vídeos o sonidos
• Subtítulos, metadatos, descripciones, anotaciones, etc.
• <video width="320" height="240" controls>
<source src="forrest_gump.mp4" type="video/mp4">
<source src="forrest_gump.ogg" type="video/ogg">
<track src="subtitles_en.vtt" kind="subtitles"
srclang="en" label="English">
<track src="subtitles_no.vtt" kind="subtitles"
srclang="no" label="Norwegian">
</video>
• Compatible con Internet Explorer, Chrom, Firefox, Safari.
Tema 4.2. Posicionamiento y contenido incrustado
91
La gran revolución de HTML5: <canvas>
• Superficie en blanco sobre la que podemos dibujar cualquier
cosa.
• Sólo en HTML5.
• Dibujamos en el canvas usando JavaScript.
• A día de hoy, sólo podemos dibujar en 2D
• Juegos en HTML5
• El futuro:
• 3D incrustado en el navegador.
• No es parte del estándar todavía.
Tema 4.2. Posicionamiento y contenido incrustado
92
Ejemplo de pintado en Canvas
<canvas id="miCanvas" width="800" height="600">
</canvas>
<script>
var elementoCanvas = document.getElementById("miCanvas");
var contexto = c.getContext("2d"); Siempre “2D”
contexto.fillStyle = "#FF0000"; Color de fondo
contexto.fillRect(0,0,150,75); Dibujar rectángulo
</script>
Se pueden dibujar muchas formas,
incluyendo imágenes en PNG.
Tema 4.2. Posicionamiento y contenido incrustado
93
Licencia Creative Commons
 Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Juan Pavón Mestras y Manuel Freire Morán, con
modificaciones de Pablo Moreno Ger, Raquel Hervás Ballesteros y Javier
Bravo Agapito
Tema 4.2. Posicionamiento y contenido incrustado
94



5
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Manuel Freire Morán y Pablo Moreno Ger. Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo Moreno
Ger, Manuel Freire Morán, Raquel Hervás Ballesteros, Carlos Cervigón, Iván
Martínez y Javier Bravo.
• Introducción: JavaScript, historia.
• Sintaxis de JavaScript: identificadores, variables, operadores,
condicionales, bucles, excepciones.
• Funciones.
• Variables, callbacks, objetos.
• Constructor, prototype, Boolean, Number, Math, String, Date, Global.
• Arrays.
Tema 5.1. Javascript 2
Tema 5.1. Javascript 3
Lenguaje de script para la creación de páginas web dinámicas.
• Ejecución de código en el lado del cliente.
• Creación de visualizaciones más atractivas y mayor interactividad.
• Podemos cambiar el aspecto de una página sin recargarla.
Lenguaje interpretado (no se compila).
• El navegador se encarga de interpretar y ejecutar el código JavaScript.
JavaScript NO es Java
• Ni está relacionado.
• ¿Mercadotecnia?
• Múltiples frameworks y tecnologías.
• JSON (JavaScript Object Notation) para transmisión de datos.
• AJAX (Asynchronous JavaScript And XML).
• JavaScript en el servidor: Node.js
Tema 5.1. Javascript 4
Primeros pasos:
• LiveScript (Brendan Eich, 1995)
• Lenguaje de script para Netscape Navigator 2.0.
• JavaScript: acuerdo entre Netscape y Sun.
Estandarización
• ECMA-262 – ECMAScript Language Specification (1997).
• Adoptado por ISO como ISO/IEC 16262 (ECMAScript 5.1).
• Actualmente (desde 2017), ECMAScript 8 (ES8).
Variantes
• Microsoft: JScript
• Firefox: JavaScript
• Chrome: JavaScript
• Opera: ECMAScript
Tema 5.1. Javascript 5
Por seguridad, los scripts sólo se pueden ejecutar dentro del navegador y con
ciertas limitaciones:
• No pueden comunicarse con recursos que no pertenezcan al mismo “origen” desde el
que se descargó el script (política same-origin).
• Da origen a los problemas de cross-domain.
• No pueden cerrar ventanas que no hayan abierto esos mismos scripts.
• No pueden acceder al sistema de ficheros, ni para leer ni para escribir.
• No pueden acceder a las preferencias del navegador.
• Si la ejecución de un script dura demasiado tiempo, el navegador informa al usuario de
que el script está consumiendo demasiados recursos y le da la posibilidad de detener
su ejecución.
Tema 5.1. Javascript 6
Código JavaScript en el documento HTML
• Con etiquetas <script> en cualquier parte del documento
• ¡Pero dentro del <head> o del <body>!
<head>
<script type="text/javascript">
alert("Un mensaje de prueba");
</script>
</head>
• Poco recomendado
• Función “alert”: muestra un mensaje de error.
Tema 5.1. Javascript 7
Código JavaScript en el documento HTML
• En un archivo externo (extensión .js). Recomendado
• Más fácil para compartir código en varios documentos
<script type="text/javascript" src="/js/codigo.js">
</script>
• Dentro de los elementos (generalmente para manejar eventos o para escribir código
dentro de la página). Es menos mantenible.
<input type="button" value="Pulse este botón"
onclick="alert('¡Has pulsado el botón!');">
Tema 5.1. Javascript 8
¿Dónde incluir los ficheros JavaScript?
• Si se ponen en el <head>:
• Se carga el código, se parsea y se interpreta antes de que la página se empiece a
renderizar (la renderización empieza con el tag <body>)
⇒ Mientras tanto la página se ve en blanco
• Si la carga y proceso de los ficheros JavaScript es considerable se puede tardar en
renderizar y por eso se ponen muchas veces estas sentencias al final del <body>
• Por ejemplo, al incluir el JavaScript de Bootstrap.
Tema 5.1. Javascript 9
Gestión de navegadores sin JavaScript
• La etiqueta <noscript> permite definir qué texto proporcionar al usuario cuando el
navegador no soporta o no tiene activado JavaScript.
• Se debe usar donde se hayan puesto los elementos <script>
<noscript>
<p> Esta página requiere JavaScript para su correcto
funcionamiento. Compruebe si JavaScript está
deshabilitado en el navegador. </p>
</noscript>
Tema 5.1. Javascript 10
<html>
<head>
<title>Mi primera web con JavaScript</title>
<script>
var clicks=0;
function incrementar(){
clicks = clicks+1;
document.getElementById('contador').innerHTML= clicks;
}
</script>
</head>
<body>
<h4>Ejecución de código JavaScript en el navegador</h4>
<button type="button" onclick="incrementar()">Incrementar</button>
<p id="contador">0</p>
</body>
</html>
Tema 5.1. Javascript 11
Para hacer pruebas podemos usar las siguientes instrucciones:
• Para escribir en la consola de JavaScript
• Se muestran mensajes, contenidos de variables, resultados de expresiones…
console.log("Mensaje");
• Para mostrar una ventana emergente con el mensaje indicado
• Se pueden mostrar como antes distintos tipos de cosas
alert("Mensaje");
• Para mostrar una ventana en la que el usuario puede escribir un texto
• Tras pulsar en Aceptar la cadena escrita se guarda en la variable
var x = prompt("Texto a escribir");
NO abusar en las aplicaciones finales
• Muy pesado para el usuario
Tema 5.1. Javascript 12
holaMundo.html
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ejecutando JavaScript</title>
</head>
<body>
<h1>Hola Mundo HTML</h1>
<script type="text/javascript" src="main.js"></script>
</body>
</html>
main.js
console.log("Hola Mundo JavaScript");
Tema 5.1. Javascript 13
Tema 5.1. Javascript 14
Escritura de código:
• Distingue entre mayúsculas y minúsculas
• while (correcto).
• While, WHILE (incorrectos).
• Ignora espacios en blanco, tabuladores y saltos de línea.
• El uso de ; al final de cada instrucción es opcional.
• Pero recomendable (si no están los pone el intérprete y puede equivocarse…).
Comentarios
• Comentario de varias líneas entre /* y */
• Comentario hasta el final de la línea con //
Tema 5.1. Javascript 15
Similar a C/Java:
• Deben comenzar por una letra o por '_'
• Pueden contener letras, dígitos y '_'
• No pueden coincidir con las palabras reservadas.
Palabras reservadas de JavaScript
• break
• case, catch, continue
• default, delete, do
• else
• finally, for, function
• if, in, instanceof
• new
• return
• switch
• this, throw, try, typeof
• var, void
• while, with
Tema 5.1. Javascript 16
Números
• Internamente las operaciones se realizan en punto flotante.
• Representación:
• Enteros: 0, -1, 44, ...
• Decimales (float): 0.20, 3.1415, -3.23e+6
• Hexadecimal, empiezan por 0x: 0xFF, 0x1A
Valores lógicos (Booleanos)
• true y false
Strings
• Secuencia de caracteres entre comillas dobles " o simples '
• Secuencias de escape, para representar caracteres especiales:
• \' Comilla simple \" Comilla doble
• \f Salto de página \n Salto de línea
• \t Tabulación \\ Barra inclinada \
Tema 5.1. Javascript 17
JavaScript es un lenguaje débilmente tipado.
• No se especifica el tipo de las variables.
• Se deduce por el contenido de la variable y el contexto.
• Las variables se declaran con var (recomendado) o implícitamente la
primera vez que se les asigna un valor.
• Las variables sin inicializar tienen como valor undefined (que es
distinto de null).
• Pueden almacenar tipos simples, arrays, objetos e incluso funciones.
var x, y; //x e y valen undefined
var total = 1;
var falso = false;
var texto = "lorem ipsum";
var array = [1, false, "lorem ipsum"];
var objeto = {total: 1, falso: false, texto: "lorem"};
var suma = function(a, b) { return a + b; };
Tema 5.1. Javascript 18
Asignación
• Guarda un valor específico en una variable
• var x = 0;
Expresiones numéricas
• Operadores aritméticos:
• + , ++, -
,
--, *, /, % (módulo), ^ (exponenciación)
• +=, -=, *=, /=, ^=, %=
Expresiones lógicas
• Operadores lógicos: && (and), || (or), ! (not)
Tema 5.1. Javascript 19
== y != aplican conversiones
var a = 1
var b = "1"
a == b; // true
a != b; // false
=== y !== no aplican conversiones
var a = 1
var b = "1"
a === b; // false
a !== b; // true
Booleanos
"" == false 0 == false // true, pero false con ===
// true, pero false con ===
Tema 5.1. Javascript
20
• var x = prompt("Texto a escribir"): Muestra una ventana en la que
el usuario puede escribir un texto. Tras pulsar en Aceptar la cadena
escrita se guarda en x.
• console.log("Mensaje"): Escribe en la consola de JavaScript el mensaje
indicado. También puede ser el contenido de una variable o el resultado
de una expresión.
• alert("Mensaje"): Muestra una ventana emergente con el mensaje
indicado. Puede ser el contenido de una variable o el resultado de una
expresión.
• confirm(“Mensaje”): Muestra una ventana emergente con el mensaje
indicado y da la posibilidad de aceptar o cancelar.
Tema 5.1. Javascript 21
Instrucciones condicionales
• if
if ( condición ) { // Instrucciones
}
else { //También else if
// Instrucciones
}
// 0, "" y null equivalen a false
• switch
switch ( expresión ) {
case valor1:
// Instrucciones caso 1
break; // para acabar el switch
case valor2:
// Instrucciones caso 2
break;
default: // opcional
// Instrucciones si no se diera ningún caso
}
Tema 5.1. Javascript 22
Bucles
• for
• for … in
for (var i=0; i<array.length; i++) {
procesa(array[i]);
}
var i;
var n = 10;
alert("Contemos hasta "+n);
for (i=1; i<=n; i++) {
alert(i);
};
for (i in array) {
procesa(array[i]);
}
Tema 5.1. Javascript 23
Bucles
• while
while( condicion ) {
//Cuerpo del bucle
}
• do ... while
do {
//Cuerpo del bucle
} while( condición )
Sentencias para control de bucles
• Salir del bucle: break;
• Saltar a la siguiente iteración: continue;
Tema 5.1. Javascript 24
var adivinar = 50;
var numeroUsuario = prompt("Adivina qué número estoy pensando");
while (numeroUsuario != adivinar) {
if (adivinar < numeroUsuario) {
alert("Lo siento mi número era menor");
}
else {
alert("Lo siento mi número era mayor");
};
numeroUsuario = prompt("Adivina qué número estoy pensando");
}
// Si estoy fuera del bucle es porque el usuario ha adivinado el número
alert("Has acertado");
Tema 5.1. Javascript 25
Excepciones: try..catch
try {
// Código a ejecutar
}
catch(err) {
// Gestión de errores
}
• Se puede lanzar una excepción con throw
Tema 5.1. Javascript 26
Tema 5.1. Javascript 27
function ()
• Entre paréntesis la lista de parámetros, sin tipo, separados por
comas
• Los parámetros siempre se pasan por valor
• El tipo de resultado no se declara, y se devuelve con return
function nombre_funcion ( arg1, arg2, ...){
// instrucciones
return resultado; //o return;
}
Se pueden definir funciones anidadas
function hipotenusa(a, b) {
function cuadrado(x) { return x*x; }
return Math.sqrt(cuadrado(a) + cuadrado(b));
}
Tema 5.1. Javascript 28
<html>
<body>
<h2>JavaScript Functions</h2>
<p>Ejemplo funciones</p>
<p id="demo"></p>
<script>
var x = myFunction(4, 3);
document.getElementById("demo").innerHTML = x;
function myFunction(a, b) {
return a * b;
}
</script>
</body>
</html>
Elemento HTML
que cambiamos
con Javascript
Tema 5.1. Javascript 29
function intercambiar (a, b) {
var temp = b;
b = a;
a = temp;
};
var x = 10;
var y = 20;
intercambiar (x,y);
console.log(x);
console.log(y);
Los parámetros se pasan
siempre por valor, es decir, se
hace una copia de ellos antes de
ejecutar el bloque de la función
Tema 5.1. Javascript 30
arguments
• El objeto arguments permite acceder a los argumentos de una función
como un array
• Los argumentos se acceden con arguments[i]
• El número de argumentos se accede con la propiedad length
function max( ){
var m = Number.NEGATIVE_INFINITY;
for(var i = 0; i < arguments.length; i++)
if (arguments[i] > m)
m = arguments[i];
return m;
}
...
max(10,30,42,21,19);
Tema 5.1. Javascript 31
En JavaScript las funciones son ciudadanos de primer orden.
Se puede hacer lo que se conoce como programación de orden superior: tratar las
funciones como datos
• Podemos asignar funciones a variables.
• Pasar funciones como parámetros.
• Podemos crear funciones que devuelvan funciones.
Tema 5.1. Javascript 32
Las funciones también se pueden pasar como parámetros de otra función
var operar = function(a, b, operacion) {
var resultado = operacion(a,b);
return resultado;
};
var multiplicar = function(a, b) {
return a*b;
};
var res = operar(3,2,multiplicar);
console.log(res); //muestra 6
// Podemos crear funciones anónimas "al vuelo"
res = operar(4,5, function(a,b) { return a+b; });
console.log(res); //muestra 9
Tema 5.1. Javascript 33
Las funciones pueden incluso devolver otras funciones
function ascendente() {
return function (a, b) {
return a <=b;
};
}
// f es la función resultado de ejecutar ascendente
var f = ascendente();
var x = 10;
var y = 20;
var siguiente = f(x,y) ? x : y;
console.log(siguiente); //muestra 10
Tema 5.1. Javascript 34
Tema 5.1. Javascript 35
Locales
• Se definen dentro de una función con var, let o const.
• let y const tienen ámbito de bloque (código delimitado por {}).
• https://www.freecodecamp.org/espanol/news/var-let-y-const-cual-es-la-diferencia/
Globales
• Se definen fuera de cualquier función con var.
• Dentro de una función una variable local prevalece sobre la global.
var m = "exterior";
function muestraMensaje() {
m = "interior";
console.log(m);
}
console.log(m);
muestraMensaje();
console.log(m);
Tema 5.1. Javascript 36
Argumentos de una función
• Son sólo visibles dentro de la función.
• Caso especial: las funciones anidadas tienen acceso a todas las variables y argumentos de
la función que la contiene.
function multiplicar(a, b) {
var prod = 0;
var i=1;
function sumar() { prod+=a; }
function incrementar() {i++; }
while (i<=b) {
sumar();
incrementar();
}
return prod;
};
Tema 5.1. Javascript 37
• Es una función que se pasa como parámetro a otra función que la
ejecutará cuando ocurra un determinado evento.
• Es un mecanismo básico para coordinar llamadas entre funciones de
manera asíncrona.
• Cuando asignamos un callback estaremos indicando qué función
queremos que se ejecute cuando ocurra un determinado evento.
• Los callbacks son fundamentales para ejecutar acciones en respuesta a los
eventos que el usuario genera al interactuar con el navegador.
Tema 5.1. Javascript 38
• En JavaScript hay dos métodos globales que permiten asociar la ejecución de
una función a un evento de tiempo:
• setTimeoutejecuta la función dada una vez transcurrido el tiempo
especificado (en milisegundos):
avisar = function() {
console.log("Por fin aparezco!");
}
setTimeout(avisar, 2000);
console.log("El callback no detiene la ejecución");
Tema 5.1. Javascript 39
• setInterval ejecuta la función dada con el intervalo de tiempo
especificado (en milisegundos), indefinidamente o hasta que se invoque a
la función clearInterval
function darLaPlasta() {
var count = 0;
var funcionPlasta = function() {
count++;
console.log("He aparecido " + count + " veces!");
if (count >= 5)
clearInterval(intervalId);
}
var intervalId = setInterval(funcionPlasta, 1000);
}
darLaPlasta();
Tema 5.1. Javascript 40
Se dice que JavaScript es un lenguaje basado en objetos
• En JavaScript se definen clases y también objetos.
• Es un lenguaje basado en prototipos (similar a las clases).
• Se pueden crear objetos copiando prototipos de otros objetos.
Un objeto en JavaScript es un conjunto de variables con un nombre
• Las variables del objeto se denominan propiedades.
• Las propiedades pueden ser valores de cualquier tipo de datos: arrays, funciones y
otros objetos.
• Las propiedades que son funciones se llaman métodos.
Clases en JavaScript:
https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Classes
Tema 5.1. Javascript 41
• Se utiliza la palabra reservada class nombre_clase{ … }
• Se define el constructor con constructor( … )
• Ejemplo de clase:
class Rectangulo {
constructor(alto, ancho) {
this.alto = alto;
this.ancho = ancho; }}
• La clase debe ser declarada antes de su uso.
• Se puede llamar a la superclase con super.
• Se realiza herencia con extends.
Tema 5.1. Javascript 42
Se puede crear un objeto directamente indicando sus
propiedades
persona=new Object();
persona.nombre="Juan";
persona.id= 12893;
• O en una sola instrucción, indicando las propiedades entre llaves:
var persona = { nombre: "Juan", id: 12893 }
• También se pueden definir funciones
var persona = { nombre: "Juan", apellido: "Nadie",
nombreCompleto: function() {
return this.nombre + " " + this.apellido;
}
};
Tema 5.1. Javascript 43
Acceso a sus propiedades:
name = persona.nombre; name = persona["nombre"]; x = "nombre"; name = persona[x]; // tres formas de acceder a
// una propiedad del
// objeto
Para recorrer las propiedades de una persona
for (x in persona) {
console.log(x + " :" + persona[x]);
}
Tema 5.1. Javascript 44
Se pueden añadir nuevas propiedades a un objeto
persona.nacionalidad = "española";
También nuevos métodos
persona.apellidoNombre = function () {
return this.apellido + ", " + this.nombre;
};
Tema 5.1. Javascript 45
Definir un constructor
• Como función independiente
function Persona(nombre, id) {
this.nombre=nombre;
this.id=id;
}
Crear objetos invocando al constructor
var gonzalo = new Persona(“Gonzalo", 12893);
var almudena = new Persona("Almudena", 23782);
Tema 5.1. Javascript 46
Se pueden definir métodos para un objeto dentro del constructor
function Persona(nombre, id) {
this.nombre=nombre;
this.id=id;
this.renombra=function renombra(nombre) {
this.nombre=nombre;
}
}
Y se invocan sobre el objeto:
var juan = new Persona("Juan", 12893);
juan.renombra("Juanjo");
Tema 5.1. Javascript 47
En muchas ocasiones los objetos nos van a servir como espacios de nombres, es decir, como
contenedores de valores y funciones que queremos que estén juntas y que no se utilicen de
manera global:
MisFunciones = {
pi: 3.14159;
sumar : function(a, b) {
var suma = 0;
while (a <= b) {
suma += a;
a++;
}
return suma;
},
pintarSuma : function(a, b) {
console.log(MisFunciones.sumar(a, b);
}
};
MisFunciones.pintarSuma(3,4);
Tema 5.1. Javascript 48
De hecho, JavaScript ya nos proporciona algunos de estos espacios de
nombres, como:
• Number: Tiene propiedades que representan valores especiales (máximo
valor representable, infinito…) y otros métodos.
• Math: Proporciona constantes matemáticas como Math.E o Math.PI y
algunos métodos como el de redondeo, raíz cuadrada o generador de
números aleatorios.
• Date: Proporciona métodos para conseguir la fecha actual del sistema y
formatearla de distintas maneras.
• RegExp: Nos permite trabajar con expresiones regulares, una forma de
buscar patrones y correspondencias en una cadena.
• Objetos del navegador que veremos más adelante.
Tema 5.1. Javascript 49
Tema 5.1. Javascript 50
Todos los objetos del lenguaje tienen estas propiedades:
• constructor
• Devuelve la función que crea el objeto.
• prototype
• Es una propiedad que permite añadir propiedades y métodos al objeto.
• Se aplica a todos los objetos de ese tipo.
Persona.prototype.nuevaFuncion=function() {
// código
Persona.prototype.nacionalidad = "española";
}
Y los métodos:
• toString(): Devuelve una representación como string del objeto.
• valueOf(): Devuelve el valor primitivo (true/false, un número, etc.) del objeto.
Tema 5.1. Javascript 51
Permite convertir objetos no booleanos a booleanos
• Creación de un objeto booleano:
• var unBooleano=new Boolean(otro);
• El valor será false si se crea con uno de los siguientes valores:
• 0
•-0
• null
""
•
• false
• undefined
• NaN
• En el resto de los casos el valor será true.
Tema 5.1. Javascript 52
Sólo hay un tipo de números, que se puede escribir con o sin decimales.
• Todos los números se almacenan con 64 bits.
• Creación de un objeto Number:
• var num = new Number(valor);
• Propiedades:
• MAX_VALUE: mayor número posible (1.7976931348623157e+308)
• MIN_VALUE: menor número posible (5e-324)
• NEGATIVE_INFINITY: -∞
• POSITIVE_INFINITY : ∞
• NaN: para indicar que el valor no es un número
• Métodos:
• toExponential(x): pone el número en notación científica (1.23e+3).
• toFixed(x): formatea el número con x decimales.
• toPrecision(x): formatea el número con longitud x.
Tema 5.1. Javascript 53
Ofrece varias operaciones matemáticas.
• Constantes matemáticas
• Math.E
• Math.PI
• Math.SQRT2: raíz cuadrada de 2
• Math.SQRT1_2: raíz cuadrada de 1/2 (inverso de la raíz cuadrada de 2)
• Math.LN2
• Math.LN10
• Math.LOG2E
• Math.LOG10E
• Métodos
• round(decimal): redondeo
• random(): devuelve un número aleatorio entre 0 y 1
• max(x, y)
• min(x, y)
Tema 5.1. Javascript 54
Métodos sobre strings
• length: número de caracteres de un string: s.length
• Concatenación de strings: operador +
• Al igual que en Java, si el primer operando es un string, los demás operandos se convertirán a strings para
concatenarse
• var cad = "2"+2+2; <--"222"
• toUpperCase(), toLowerCase()
• var m = "Juan";
• var m2= m.toUpperCase(); // m2 = "JUAN"
• charAt(posicion)– equivalente a cad[posicion]
• indexOf(caracter), lastIndexOf(caracter)
• Cuenta desde 0. Si no estuviera el carácter devuelven -1
• var posicion = m.indexOf('a'); // posicion = 2
• substring(inicio, final)
• var resto = m.substring(1); // resto = "uan"
• split(separador)
• var letras = m.split(""); // letras = ["J", "u", "a", "n"]
• m="Hola Juan"; palabras=m.split(" ") // palabras=["Hola","Juan"]
Tema 5.1. Javascript 55
Proporciona la fecha y hora
• new Date() // fecha y hora actual
• new Date(milisegundos) //milisegundos desde 1 de enero 1970
• new Date(string)
• new Date(anno, mes, dia, horas, minutos, segundos, milisegundos)
Métodos:
• getTime(): devuelve el número de milisegundos desde 01.01.1970 (UNIX).
• getFullYear(): devuelve el año (cuatro dígitos).
• getDate(): devuelve el día del mes (1..31).
• getDay(): devuelve el día de la semana (0..6).
• getHours(): devuelve la hora (0..23).
• getMinutes(): devuelve los minutos (0..59).
• Los equivalentes setDate, setHours, etc.
• toUTCString(): convierte la fecha a un string con formato de fecha de tiempo universal (Wed, 30 Jan 2013
07:03:25 GMT)
Tema 5.1. Javascript 56
Métodos globales para todos los objetos
• eval(string)– Evalúa una cadena de texto como si fuera un programa JavaScript
• parseInt(string, base)– Convierte una cadena de texto a un número entero
• base indica el sistema de numeración (2..36) (si no se indica se puede derivar del
inicio del string ("0x" hex, "0" octal, o decimal)
• Si no puede hacer la conversión devuelve Number.NaN
• parseFloat(string)– Convierte una cadena de texto a un float
• isNan(valor)– Devuelve true si valor no es un número, false si lo es
• isFinite(valor)– Devuelve true si su argumento no es NaN o Infinity
• encodeURI(uri)– Codifica los caracteres especiales de una URI excepto , / ? : @ & = +
$ #
• Para codificar también estos se usa encodeURIComponent()
• decodeURI(uri_codificada) – Descodifica una URI codificada
Tema 5.1. Javascript 57
Tema 5.1. Javascript 58
Objetos para guardar una colección de variables.
• Pueden ser todas del mismo tipo o cada una de un tipo diferente.
var nombre_array = [valor1, valor2, ..., valorN];
var lo_mismo = new Array(valor1, valor2, ..., valorN);
var sin_inicializar = new Array(5);
• Se accede a los elementos con nombre_array[índice]
• índice es un valor entre 0 y N-1.
Tema 5.1. Javascript 59
Es un tipo especial de objetos porque usa números para acceder a sus
miembros (los objetos usan nombres)
var persona = ["Ana", 24]; // array
persona[0] // "Ana"
persona[2] // undefined
var persona = {nombre:"Ana", edad:24}; // objeto
persona["nombre"] // "Ana"
persona.nombre // "Ana"
Tema 5.1. Javascript 60
Propiedades y métodos
• length: número de elementos de un array
• Permite añadir un elemento nuevo al array
• colores[colores.length] = "Morado";
• concat(): concatenar los elementos de varios arrays
• a1 = [1, 2, 3];
• a2 = a1.concat(4, 5, 6); // a2 = [1, 2, 3, 4, 5, 6]
• a3 = a1.concat([4, 5, 6]); // a3 = [1, 2, 3, 4, 5, 6]
• pop(): elimina y devuelve el último elemento del array.
• push(elemento): añade un elemento al final del array.
• shift(): elimina y devuelve el primer elemento del array.
• unshift(elemento): añade un elemento al principio del array.
Tema 5.1. Javascript 61
Clasificación en arrays
• sort(): clasifica los elementos del array alfabéticamente.
• reverse(): clasifica los elementos del array en orden inverso.
• Por defecto trabajan con los datos como strings.
• ¿Cómo clasificar valores numéricos?
• Definiendo la función de comparación que devuelve un valor positivo, negativo o
cero
var numeros = [12, 323, -1, 9, 0, 12];
numeros.sort(); // -1,0,12,12,323,9
numeros.sort(function(a, b){return a-b}); // -1,0,9,12,12,323
Tema 5.1. Javascript 62
var lote = [12,10,4,35];
var i;
console.log(lote.length) // 4
lote = lote.concat([0,23]);
console.log(lote.length) // 6
lote.sort();
// Veremos que no se muestra lo esperado
lote.sort(function(a, b) {
return a-b;
});
// Ahora deberíamos ver el orden esperado
for (i=0; i < lote.length; i++) {
console.log(lote[i]+ " ");
}
lote.splice(1,2, "otro", "número");
// El resultado final es un array que mezcla números y cadenas
Tema 5.1. Javascript 63
Las cadenas o string de JavaScript permiten realizar algunas de las
operaciones que usamos sobre los arrays. En particular, el operador + es
equivalente a concat():
var cadena = "hola" + " mundo";
console.log(cadena[0]); // Muestra la "h"
• toUpperCase(), toLowerCase(): Devuelve la cadena convertida en
mayúsculas/minúsculas.
• charAt(posicion): similar a cadena[posicion].
• indexOf(caracter), lastIndexOf(caracter): devuelve la posición de la
primera/última aparición de un determinado carácter o cadena. Devuelve -
1 si no lo encuentra.
• slice(inicio, final): devuelve la cadena contenida entre las posiciones
inicial y final.
• split(separador): Devuelve un array con las cadenas formadas partiendo
la cadena original en los lugares en los que aparece separador.
Tema 5.1. Javascript 64
var mail = "minombre@dominio.es";
var i = mail.indexOf("@");
if (i===-1) {
alert("Dirección de correo no válida");
}
var frase = "Una frase separada por espacios en blanco";
var palabras = frase.split(" ");
console.log("La frase tiene "+palabras.length+" palabras");
for (i in palabras) {
console.log("Palabra "+i+" "+palabras[i]);
}
La frase tiene 7 palabras
Palabra 0 Una
Palabra 1 frase
Palabra 2 separada
Palabra 3 por
Palabra 4 espacios
Palabra 5 en
Palabra 6 blanco
Tema 5.1. Javascript 65
Cambiar el html
<h2>What Can JavaScript Do?</h2>
<p id="demo">JavaScript can change HTML content.</p>
<button type="button" onclick='document.getElementById("demo").innerHTML = "Hello
JavaScript!"'>Click Me!</button>
Origen: https://www.w3schools.com
Tema 5.1. Javascript 66
Cambiar atributos
<p>In this case JavaScript changes the value of the src (source) attribute of an
image.</p>
<button onclick="document.getElementById('myImage').src='pic_bulbon.gif'">Turn on the
light</button>
<img id="myImage" src="pic_bulboff.gif" style="width:100px">
<button onclick="document.getElementById('myImage').src='pic_bulboff.gif'">Turn off the
light</button>
Origen: https://www.w3schools.com
Tema 5.1. Javascript 67
Funciones
<html>
<body>
<p>Click "Try it" to call a function with arguments</p>
<button onclick="myFunction('Harry Potter','Wizard')">Try it</button>
<p id="demo"></p>
<script>
function myFunction(name,job) {
document.getElementById("demo").innerHTML = "Welcome " + name + ", the " + job + ".";
}
</script>
</body>
</html>
Origen: https://www.w3schools.com
Tema 5.1. Javascript 68
Licencia Creative Commons
 Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo
Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros, Carlos
Cervigón, Iván Martínez y Javier Bravo.
Tema 5.1. Javascript
69


5
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Manuel Freire Morán y Pablo Moreno Ger. Material elaborado por Juan Pavón Mestras, con modificaciones de
Pablo Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros
y Javier Bravo Agapito.
• Objetos del navegador
• DOM
• Eventos en JavaScript
• Formularios
Tema 5.2. Objetos en el navegador y formularios 2
Tema 5.2. Objetos en el navegador y formularios 3
Browser Object Model (BOM)
• JavaScript proporciona objetos que permiten interactuar con el navegador
• No son parte del estándar pero casi todos los navegadores los implementan
Document Object Model (DOM)
• JavaScript también proporciona objetos para interactuar con la página que se
está mostrando en el navegador.
Jerarquía de objetos:
• Jerarquía de agregación, no de herencia
window navigator
screen
forms[]
objetos BOM
history
anchors[]
objetos DOM
location
links[]
document
images[]
Tema 5.2. Objetos en el navegador y formularios
4
Objeto que contiene todos los demás
• Por ejemplo, el más usado: window.document
• Aunque la mayoría de navegadores permiten omitir la referencia a window
Propiedades
• innerHeight – altura interior de la ventana del navegador en píxeles
• innerWidth – anchura interior de la ventana del navegador en píxeles
Métodos
• resizeTo(ancho, alto), resizeBy(ancho, alto) – cambia el tamaño de la ventana a
un tamaño fijo (To) o en la proporción indicada (By)
• moveTo(x, y), moveBy(x,y) – mueve la ventana actual a una posición fija (To) o en
una cantidad dada (By)
• setInterval(funcion, ms) – llama a la función cada ms milisegundos
Lista de propiedades y métodos:
https://www.w3schools.com/jsref/obj_window.asp
Tema 5.2. Objetos en el navegador y formularios
5
Ventanas de diálogo
• alert(mensaje) – Muestra una ventana de alerta con un mensaje.
• confirm(mensaje) – Muestra una ventana de confirmación con los
botones Aceptar y Cancelar y devuelve true o false.
• prompt(mensaje, valorPredeterminado) – Muestra una ventana de
diálogo para solicitar una información:
• Se indica un mensaje.
• Se puede indicar un valor por defecto para el área de la respuesta.
• Como resultado se espera recibir un string.
Estos métodos se pueden invocar sobre el objeto window o
directamente:
• var valor = window.prompt("Introduzca el valor: ", "");
• var valor = prompt("Introduzca el valor: ", "");
Tema 5.2. Objetos en el navegador y formularios 6
Para proteger la privacidad, este objeto tiene una
funcionalidad bastante limitada, básicamente para
avanzar o retroceder páginas
• history.length – indica cuántas páginas están registradas
(realmente sirve para saber si hay alguna anterior)
• history.back() – carga la página precedente (si la hubiera)
• history.forward() – carga la página siguiente (si la hubiera)
• history.go(número) – carga la página de la lista hacia delante o
atrás indicada por el número, según sea positivo o negativo
<script>
function atras(){
history.back();
}
</script>
Tema 5.2. Objetos en el navegador y formularios 7
Contiene información sobre el navegador
• No es muy útil porque se implementa de maneras bastante
diferentes
<script>
document.write("<h3>Propiedades del navegador:</h3>");
document.write("<p>CodeName: " + navigator.appCodeName + "</p>");
document.write("<p>Name: " + navigator.appName + "</p>");
document.write("<p>Versión: " + navigator.appVersion + "</p>");
document.write("<p>Cookies permitidos: " + navigator.cookieEnabled + "</p>");
document.write("<p>Plataforma: " + navigator.platform + "</p>");
document.write("<p>User-agent header: " + navigator.userAgent + "</p>");
document.write("<p>Lenguaje del sistema: " + navigator.language + "</p>");
</script>
Tema 5.2. Objetos en el navegador y formularios 8
Location
• Facilita la manipulación del URL actual y la posibilidad de
recargar la página o redireccionar a otra página
• Propiedades
• location.href – devuelve el URL
• location.hostname – devuelve el dominio del host web
• location.pathname – devuelve el camino del fichero de la
página actual
• location.port – devuelve el puerto (p.ej., 80 o 443)
• location.protocol – devuelve el protocolo usado (http:// o
https://)
• location.search – devuelve la parte del URL tras ? (incluido)
• location.hash – devuelve el anchor (la parte del URL tras #,
incluido)
Tema 5.2. Objetos en el navegador y formularios 9
Location
• Métodos
• location.assign(url) – carga la página indicada
function redirigir() {
window.location.assign("http://www.ucm.es");
}
• reload() – recarga la página (desde la caché)
• reload(true) – recarga la página (fuerza la recarga)
Tema 5.2. Objetos en el navegador y formularios 10
Tema 5.2. Objetos en el navegador y formularios 11
El navegador transforma el código del documento en un
árbol DOM
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type"
content="text/html; charset=iso-8859-
1" />
<title>Ejemplo</title>
</head>
<body>
<p>Ejemplo de página
<em>sencilla</em></p>
</body>
</html>
Documento
HTML
Elemento
HEAD
Elemento
BODY
Elemento
META
Elemento
TITLE
Elemento
P
Texto
Ejemplo
Texto
Ejemplo de página
Elemento
EM
Texto
sencilla
Tema 5.2. Objetos en el navegador y formularios 12
Acceso directo
• Acceso por ID: document.getElementById(id)
• Devuelve el objeto correspondiente al elemento que tenga el
id especificado (solo puede haber uno) o null si no lo hubiera
• Es un método del objeto documenten JavaScript
// Devuelve el elemento con id="principal"
// y lo guarda en la variable x
var x = document.getElementById("principal");
Tema 5.2. Objetos en el navegador y formularios 13
Acceso directo
• Acceso por etiqueta: getElementsByTagName(etiqueta)
• Devuelve un array de nodos que tienen la etiqueta
especificada
• Este método se puede aplicar a cualquier nodo
// Devuelve un array con todos los <p>
// del documento
var parrafos=document.getElementsByTagName("p");
// Devuelve un array con todos los enlaces <a>
// dentro del elemento x (id="principal")
var enlaces=x.getElementsByTagName("a");
Tema 5.2. Objetos en el navegador y formularios 14
Acceso directo
• Acceso por clase: getElementsByClassName(clase)
• Devuelve un array de nodos que pertenecen a la clase
especificada
• Este método se puede aplicar a cualquier nodo
// Devuelve un array con todos los elementos de
// cualquier tipo de clase "importante"
var imp=document.getElementsByClassName("importante");
• Acceso por selector CSS: querySelector[All](selector)
Tema 5.2. Objetos en el navegador y formularios 15
Desde el nodo padre
• Acceder al nodo raíz (document), y navegar por los hijos hasta el
nodo deseado
• Propiedades
• childNodes – NodeList (array) que contiene todos los hijos
del nodo
• firstChild y lastChild –primer y último hijo de un nodo
• parentNode – el padre del nodo
• nextSibling y previousSibling –nodos siguiente y anterior en
el mismo nivel
• Métodos
• hasChildNodes()
Tema 5.2. Objetos en el navegador y formularios 16
Todos los nodos tienen un conjunto de métodos para
acceder a los atributos del nodo
• hasAttribute("atributo") - devuelve true si el elemento tiene un
atributo con ese nombre
• getAttribute("atributo") – devuelve el valor del atributo
• setAttribute("atributo", "valor") – permite modificar el valor del
atributo, o añadir un nuevo atributo
Tema 5.2. Objetos en el navegador y formularios 17
Todos los nodos tienen un conjunto de métodos para
manipular los hijos:
• appendChild(nuevoNodo)
• insertBefore(nuevoNodo, viejoNodo)
• isSameNode(otroNodo)
• removeChild(nodoEliminado)
• replaceChild(nuevoNodo, viejoNodo)
Tema 5.2. Objetos en el navegador y formularios 18
Editar el contenido de un elemento
• Propiedad innerHTML
document.getElementById(id).innerHTML="nuevo código HTML"
Tema 5.2. Objetos en el navegador y formularios 19
Otros cambios
• Cambio de un atributo de un elemento.
document.getElementById(id).atributo="nuevo valor"
• Cambio del estilo de un elemento
• Puede ser muy útil para cambiar momentáneamente una
propiedad de estilo de un elemento.
• Es más común cambiar la clase de un elemento para que
muestre un aspecto distinto.
document.getElementById(id).style.property="nuevo valor"
Tema 5.2. Objetos en el navegador y formularios 20
var compactarCarrito = function(idCarrito) {
var panelCarrito = document.getElementById(idCarrito);
var unProducto = panelCarrito.firstChild;
while (unProducto!==null) {
if (unProducto.nodeType === Node.ELEMENT_NODE) {
unProducto.style.display = "none";
}
unProducto = unProducto.nextSibling;
};
};
compactarCarrito("carrito");
Tema 5.2. Objetos en el navegador y formularios 21
Para crear un nuevo elemento hay que:
• Crear el elemento
• createElement(etiqueta)
• createTextNode(string)
• Añadirlo a un elemento existente (padre) con la operación
appendChild(hijo)
Tema 5.2. Objetos en el navegador y formularios 22
<div id="seccion">
<p id="p1">Primer párrafo.</p>
<p id="p2">Segundo párrafo.</p>
</div>
<script>
/* Se crea el nuevo párrafo, incluyendo un nodo para el texto */
var nuevop=document.createElement("p");
var nodo=document.createTextNode("Un nuevo párrafo.");
nuevop.appendChild(nodo); // el nuevo párrafo con su texto
/* Se añade el párrafo al final de la sección correspondiente */
var elemento=document.getElementById("seccion");
elemento.appendChild(nuevop);
</script>
Tema 5.2. Objetos en el navegador y formularios 23
Para eliminar un nuevo elemento hay que:
• Localizar el padre del elemento
• Eliminar el nodo hijo que corresponde al elemento con la
operación removeChild(hijo)
Tema 5.2. Objetos en el navegador y formularios 24
<div id="seccion">
<p id="p1">Primer párrafo.</p>
<p id="p2">Segundo párrafo.</p>
</div>
<script>
var padre=document.getElementById("seccion");
var hijo=document.getElementById("p1");
padre.removeChild(hijo);
</script>
// Atajo a través de la propiedad parentNode:
var hijo=document.getElementById("p1");
hijo.parentNode.removeChild(hijo);
Tema 5.2. Objetos en el navegador y formularios 25
Tema 5.2. Objetos en el navegador y formularios 26
HTML DOM permite asociar código JavaScript a los
eventos
• Cada evento tiene una propiedad (event handler) a la que se
puede asignar la función que se invocará cuando se produzca el
evento.
• Esta propiedad suele llevar el prefijo on seguido del nombre de
evento.
Tema 5.2. Objetos en el navegador y formularios 27
<html>
<head><title>Gestión de eventos</title>
<script type="text/javascript">
function paginaCargada(){
alert("La pagina ha sido cargada");
}
window.onload=paginaCargada;
</script>
</head>
<body>
<form>
</form>
</body>
</html>
<input type="button" value="Hola" onclick="alert('Hola')" />
Tema 5.2. Objetos en el navegador y formularios 28
El código a ejecutar por un evento se puede declarar en
varios lugares
• Fuera del elemento, dentro del resto de código JavaScript.
Recomendado
• Con la propiedad asociada al evento
document.getElementById("primero").onclick=function(){
cambiaTexto(this)
};
// Con la función anónima fuera
var cambiar = function(){
cambiaTexto(this)
};
document.getElementById("primero").onclick = cambiar;
Tema 5.2. Objetos en el navegador y formularios 29
El código a ejecutar por un evento se puede declarar en
varios lugares
• Fuera del elemento, dentro del resto de código JavaScript.
Recomendado
• Usando el método addEventListener. Permite además
añadir varias funciones que serán avisadas en caso de que se
produzca el evento.
• Con el mismo nombre que el anterior pero sin el prefijo on
document.getElementById("primero").addEventListener('click',
function() {
cambiaTexto(this) }
);
document.getElementById("primero").addEventListener('click',
cambiar);
Tema 5.2. Objetos en el navegador y formularios 30
Problema: Si se invoca getElementById en un script
dentro del <head>, normalmente devolverá null porque
no se habrá construido aún el árbol DOM
• La solución consiste en incluir ese código dentro de onload (ver
ejemplo en la siguiente página).
• Otra opción es poner el código al final del body.
Tema 5.2. Objetos en el navegador y formularios 31
<!DOCTYPE html>
<html>
<head>
<title>Gestión de eventos</title>
<script type="text/javascript“>
window.onload = function() {
document.getElementById("primero").onclick=function(){
cambiaTexto(this)
};
}
function cambiaTexto(id) { id.innerHTML="¡Ole!"; }
</script>
</head>
<body>
</body>
</html>
<h1 id="primero">Haz click en este texto</h1>
Tema 5.2. Objetos en el navegador y formularios 32
Sobre la página
• onload – Al cargar una página (también vale para imágenes)
• onunload – Cuando se abandona la página
• onresize – Al modificar el tamaño de la ventana del navegador
Sobre elementos
• onfocus – Cuando el foco se pone en un objeto
• onblur – Cuando se cambia el foco a otro objeto
• onclick – Cuando se hace click sobre un objeto
• ondblclick – Cuando se hace doble click sobre un objeto
Sobre formularios
• onsubmit – Al pulsar el botón de envío de un formulario
• onchange – Al cambiar el valor de un campo de un formulario
• onreset – Al inicializar el formulario
Tema 5.2. Objetos en el navegador y formularios 33
Teclado
• onkeydown – Cuando se pulsa una tecla
• onkeyup – Cuando se suelta una tecla
• onkeypress – Cuando se pulsa y suelta una tecla
Ratón
• onmousedown – Cuando se pulsa un botón del ratón
• onmouseup – Cuando se suelta el botón del ratón
• onmousemove – Al mover el ratón
• onmouseover – Cuando se mueve el puntero del ratón sobre un
elemento (cuando entra al elemento)
• onmouseout – Cuando el puntero del ratón abandona un elemento
Tema 5.2. Objetos en el navegador y formularios 34
Algunos eventos (onclick, onkeydown, onkeypress,
onreset, onsubmit) ya tienen una acción por defecto
• Se puede modificar al definir un manejador de evento.
Algunas acciones pueden dar lugar a una sucesión de
eventos
• Por ejemplo, al pulsar sobre un botón de tipo "submit" se
desencadenan los eventos onmousedown, onclick, onmouseup
y onsubmit de forma consecutiva.
Tema 5.2. Objetos en el navegador y formularios 35
Tema 5.2. Objetos en el navegador y formularios 36
Propiedades de los elementos de un formulario
• name: valor del atributo name de HTML (no se puede modificar)
• value: valor del atributo value de HTML
• Para los campos de texto (<input type="text"> y <textarea>)
proporciona el texto que ha escrito el usuario
<input type="text" id="linea" />
...
var valor = document.getElementById("linea").value;
Tema 5.2. Objetos en el navegador y formularios 37
Eventos más utilizados en el manejo de los formularios
• onclick – cuando se pincha con el ratón sobre un elemento
• Generalmente con los botones
• onchange – cuando el usuario cambia el valor de un elemento
de texto
• Generalmente con entrada de tipo text o textarea.
• También se produce cuando el usuario selecciona una opción
en una lista desplegable (<select>), o al pasar el usuario al
siguiente campo del formulario.
• onfocus – cuando el usuario selecciona un elemento del
formulario.
• onblur – cuando el usuario pasa a otro elemento del formulario.
Tema 5.2. Objetos en el navegador y formularios 38
Caso de uso habitual
• Validar los valores de los formularios antes de enviarlos
• Detectar el intento de envío
• Validar los campos (todos los obligatorios completados, correo,
teléfono bien formateado)
• Si algún valor no es válido cancelar el envío y mostrar un mensaje
<form action="..." onsubmit="return validar(this);">
...
function validar(formulario) {
if (formulario.email.value == "") {
alert("No has puesto el correo." );
formulario.email.focus();
return false; // Cancela el envío
}
. . .
formulario.submit(); // Para enviar el formulario
}
Tema 5.2. Objetos en el navegador y formularios 39
Licencia Creative Commons
 Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo
Moreno Ger, Manuel Freire Morán, Raquel Hervás Ballesteros y Javier
Bravo Agapito.
Tema 5.2. Objetos en el navegador y formularios
40


5
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Manuel Freire Morán y Pablo Moreno Ger. Material elaborado por Juan Pavón Mestras, con modificaciones de Pablo Moreno
Material elaborado por Pablo Moreno Ger y Manuel Freire
Ger, Manuel Freire Morán, Raquel Hervás Ballesteros, Carlos Cervigón, Iván
Morán, con modificaciones de Javier Bravo Agapito.Martínez y Javier Bravo.
• JQuery
• Incluyendo JQuery.
• Sintaxis.
• Selectores y eventos.
• Modificación del árbol DOM.
• JQuery y CSS.
• Ajax
• Definición.
• Ajax desde JQuery.
Tema 5.3. jQuery y AJAX 2
Tema 5.3. jQuery y AJAX 3
function registerEventHandler(node, event, handler) {
if (typeof node.addEventListener == "function")
node.addEventListener(event, handler,
false);
else // Internet Explorer < 7
node.attachEvent("on" + event, handler);
}
var elemento = document.getElementById("miBoton");
registerEventHandler(elemento, "click", miManejador);
Sin JQuery
$("#miBoton").click(miManejador)
Con JQuery
Tema 5.3. jQuery y AJAX 4
Base ocupa sólo < 100 KB, minimizado (jquery-3.7.1.min.js)
Sin minimizar: aproximadamente 290 KB.
• Inclusión en local
<script type="text/javascript" src="js/jquery-3.7.1.min.js">
</script>
• Inclusión en remoto
<script type="text/javascript"
src="http://code.jquery.com/jquery-3.7.1.min.js"></script>
Nuestro código deberá estar en un archivo .js separado
<script type="text/javascript" src="my_codigo_jquery.js"></script>
Tema 5.3. jQuery y AJAX 5
Todas las llamadas se componen de un selector y una
acción:
$(selector).accion();
• $  Identifica una llamada de jQuery
• selector  Indica los elementos HTML objetivo
• accion()  Qué hacer con los elementos indicados
$(this).hide() $("p").hide() $(".test").hide() $("#test").hide()  oculta el elemento actual
 oculta todos los elementos <p>
 oculta los elementos con clase "test"
 oculta el elemento con id "test"
Tema 5.3. jQuery y AJAX
6
Empezamos a hacer llamadas después de cargar el árbol
DOM:
$(document).ready(function(){
// Código jQuery
});
Versión reducida:
$(function(){
// Código jQuery
});
Tema 5.3. jQuery y AJAX
7
Ocultar todos los párrafos al pulsar un botón
<script>
$(document).ready(function(){
$("#ocultar").click(function(){
$("p").hide();
});
});
</script>
<body>
<h2>Cabecera</h2>
<p>Primer párrafo.</p>
<p>Segundo párrafo.</p>
<button id="ocultar"></button>
</body>
Tema 5.3. jQuery y AJAX
8
Tema 5.3. jQuery y AJAX 9
Similares a CSS
• Selector por elemento: $("elemento")
• Selector por identificador: $("#id")
• Selector por clase: $(".clase")
• Combinaciones: $("p.intro") // Párrafos de clase intro
Otros
• Todos los elementos: $("*")
• Elemento actual: $(this)
• Botones (input + button): $(":button")
• Contiene texto: $(":contains(texto)")
• Negación: :not(selector)  Párrafos SIN clase ejemplo: p:not(.example)
• :odd/:even - los elementos pares / impares (respecto del padre)
Tema 5.3. jQuery y AJAX 10
Eventos típicos de javaScript:
• Eventos de ratón: click, dblclick, mouseenter, mouseleave
• Eventos de teclado: keypress, keydown, keyup
• Eventos de formulario: submit, change, focus, blur
• Eventos de documento: load, resize, scroll, unload
$(selector).evento(funcion(){
... código a ejecutar ...
});
Tema 5.3. jQuery y AJAX
11
Tema 5.3. jQuery y AJAX 12
jQuery facilita también la modificación del árbol DOM
• Obtener un fragmento de HTML
• Establecer el contenido de un elemento HTML
• Añadir nuevos elementos HTML
• Eliminar elementos HTML
Tema 5.3. jQuery y AJAX 13
Métodos para acceder a los nodos del árbol
• Nodo de texto
• Función text()
• Nodo HTML (elemento)
• Función html()
• Valor de un campo en un formulario
• Función val()
• Atributo de un elemento
• Función attr()
Tema 5.3. jQuery y AJAX 14
Obtener nodos HTML y nodos de texto (¡son distintos!)
<script>
$(document).ready(function(){
$("#btn1").click(function(){
alert("Texto: " + $("#test").text());
});
$("#btn2").click(function(){
alert("HTML: " + $("#test").html());
});
});
</script>
...
<p id="test">Este texto está en <b>negrita</b> y usa B...</p>
<button id="btn1">Ver texto</button>
<button id="btn2">Ver HTML</button>
http://www.w3schools.com/Jquery/tryit.asp?filename=tryjquery_dom_html_get
Tema 5.3. jQuery y AJAX
15
Los métodos anteriores pueden recibir parámetros
• Sin parámetros: devuelven el valor
• Con parámetros: establecen el valor
$("#btn1").click(function(){
$("#p1").text("Hola Mundo!");
});
$("#btn2").click(function(){
$("#p2").html("<em>Hola mundo!</em>");
});
$("#btn3").click(function(){
$("#nombre").val("Juan");
});
Tema 5.3. jQuery y AJAX
16
Funciones para añadir nodos
• Insertar al final de los elementos seleccionados (hijo)
• Función append()
• Insertar al principio de los elementos seleccionados (hijo)
• Función prepend()
• Insertar después de los elementos seleccionados (hermano)
• Función after()
• Insertar antes de los elementos seleccionados (hermano)
• Función before()
Tema 5.3. jQuery y AJAX 17
Dos funciones de borrado
• Borrar los elementos seleccionados y todos sus hijos
• Función remove()
• Borrar los hijos de los elementos seleccionados
• Función empty()
Tema 5.3. jQuery y AJAX 18
Tema 5.3. jQuery y AJAX 19
jQuery permite manipular CSS fácilmente
• Añadir o eliminar clases
• addClass()
• removeClass()
• Consultar y manipular propiedades
• css()
Tema 5.3. jQuery y AJAX 20
La forma más “correcta” de cambiar el aspecto de un
elemento es cambiando su clase
• La nueva clase debería existir y contemplar el nuevo aspecto
$("h1,h2,p").addClass("blue");
$("div").addClass("important");
$("h1,h2,p").removeClass("blue");
Tema 5.3. jQuery y AJAX
21
También podemos cambiar propiedades más específicas
• Una única función: css()
• Con un parámetro para consultar
• Con dos parámetros para modificar
// Consultar el color de fondo
$("p").css("background-color"); //Devuelve “red”
// Cambiar el color de fondo
$("p").css("background-color","yellow"); //Cambia el color
Tema 5.3. jQuery y AJAX
22
Múltiples efectos sobre elementos:
• Mostrar / Ocultar
• Funciones hide() y show()
• Reciben como argumento opcional un número en ms.
• Aparecer / desaparecer alternativamente
• Función toggle()
• Cambia al estado contrario
• Efecto fading
• Funciones fadeIn(), fadeOut(), fadeToggle()
• Reciben como parámetro la duración en ms o “fast” o “slow”
• Deslizamiento
• Funciones slideDown(), slideUp(), slideToggle()
• Animaciones
• Función animate()
• Transición de cualquier propiedad a cualquier valor
Tema 5.3. jQuery y AJAX 23
Tema 5.3. jQuery y AJAX 24
AJAX: Asynchronous JavaScript And XML
• Comunicación basada en XMLHttpRequest (XHR).
• XHR permite enviar una petición GET ó POST desde JavaScript,
sin recargar la página.
• El resultado devuelto por el servidor típicamente es:
• XML (originalmente).
• JSON.
• Desde JavaScript recibimos el nuevo fragmento de código y lo
usamos para modificar la web (¡sin tener que recargar! F5)
• Carga dinámica de contenido.
Tema 5.3. jQuery y AJAX 25
JSON: JavaScript Object Notation
• Sintaxis de JS para intercambiar datos.
• Sólo datos. Sin funciones ni operaciones.
• Con todos los nombres de campo entre comillas dobles.
• Fácil de usar desde JS: es JS.
• Fácil de usar desde PHP: json_encode() y json_decode().
{ "nombre": "John", "apellidos": [ "Smith", "Doe"] }
Tema 5.3. jQuery y AJAX 26
Cliente
Servidor
Cliente
Servidor
petición
inicial
recursos y
php para
toda la página
cliente redibuja
toda la página JS actualiza
sólo lo necesario
JS lanza
solicitud AJAX
JSON ó tradicional
JSON ó
fragmento HTML
PHP responde
sólo a la petición
Sin AJAX
Con AJAX
(actualización en 2º plano)
Tema 5.3. jQuery y AJAX 27
Peticiones (potencialmente) más ligeras
• El servidor (PHP) recibe peticiones que se contestan con algo de
JSON, y evita enviar el resto de la página (que puede ser cara de
construir).
• El cliente (JS) recibe sólo lo que ha cambiado, y puede
actualizarlo sin tener que volver a montar la página desde cero.
Asíncrono
• Es posible lanzar muchas peticiones AJAX sin esperar a sus
resultados. Por ejemplo, podemos ir guardando nuestros
cambios con AJAX en el servidor, sin tener que recargar la
página tras cada cambio.
• Si en el cliente llegan varias respuestas mientras todavía no nos
ha dado tiempo a procesar las viejas, podemos ir directamente
a procesar las nuevas...
Tema 5.3. jQuery y AJAX 28
Pasos en una invocación AJAX
 Crear el objeto XMLHTTPREQUEST (XHR).
 Preparar la petición.
 Enviar la petición asíncrona.
 URL.
 Tipo.
 Función para procesar la respuesta receptora asíncrona.
 En la función receptora (callback), procesar el texto recibido y
modificar el aspecto de la página.
Tema 5.3. jQuery y AJAX 29
Invocación AJAX (el estilo complicado)
XHR = new XMLHttpRequest();
XHR.open("GET", "procesador.php", true);
XHR.onreadystatechange = procesarRespuesta;
XHR.send(null);
function procesarRespuesta() {
if (XHR == 4) {
if (XHR.status == 200) {
alert(XHR.responseText);
}
}
}
Tema 5.3. jQuery y AJAX
30
Función .load(URL)
• Ejecuta una petición XHR a URL y coloca el resultado en el elemento
seleccionado (innerHTML).
<script>
$(document).ready(function(){
$("button").click(function(){
$("#div1").load("texto.txt");
});
});
</script>
...
<div id="div1"><h2>Texto original</h2></div>
<button>Cambiar texto</button>
Tema 5.3. jQuery y AJAX 31
No siempre querremos recibir un texto fijo
• Función $.get(URL, [datos], callback)
<script>
$(document).ready(function(){
$("button").click(function(){
$.get("procesador.php?atr=valor",function(data,status){
alert("Data: " + data + "\nStatus: " + status);
});
});
});
</script>
...
<button>Enviar petición con parámetros</button>
Tema 5.3. jQuery y AJAX 32
No siempre querremos recibir un texto fijo
• Función $.post(URL, datos, callback)
<script>
$(document).ready(function(){
$("button").click(function(){
$.post("procesar.php",
{ nombre: "Pablo", ciudad: "Madrid" },
function(data,status) {
alert("Data: " + data + "\nStatus: " + status);
}
});
});
});
</script>
...
<button>Enviar post con datos JSON</button>
Tema 5.3. jQuery y AJAX 33
<h1>Registro de nuevo usuario</h1>
<form action="index.php" method="POST">
<fieldset>
<legend>Datos del usuario</legend>
<p><label>Correo:</label> <input type="text" name="email" id="campoEmail" />
<img id="correoOK" src="ok.png" />
<img id="correoMal" src="no.png" /></p>
<p><label>User:</label> <input type="text" name="username" id="campoUser" />
<img id="userOK" src="ok.png" />
<img id="userMal" src="no.png" /></p>
<button type="submit">Entrar</button>
</fieldset>
</form>
Tema 5.3. jQuery y AJAX 34
<script>
$(document).ready(function() {
$("#correoOK").hide();
$("#userOK").hide();
$("#campoEmail").change(function(){
if (correoValido($("#campoEmail").val() ) ) {
$("#correoMal").hide();
$("#correoOK").show();
} else {
$("#correoMal").show();
$("#correoOK").hide();
Validamos el
campo de e-mail
}
});
function correoValido(correo) {
var arroba = correo.indexOf("@");
correo = correo.substring(arroba,correo.length);
var punto = correo.indexOf(".");
correo = correo.substring(punto + 1,correo.length);
return ( arroba > 0 && punto > 1 && correo.length > 0);
}
. . .
Tema 5.3. jQuery y AJAX 35
. . .
$("#campoUser").change(function(){
var url = "comprobarUsuario.php?user=" + $("#campoUser").val();
$.get(url,usuarioExiste);
});
Validamos el
campo usuario
con Ajax
function usuarioExiste(data,status) {
if (data == “duplicado”) {
$("#userMal").show();
$("#userOK").hide();
$("#campoUser").focus(); //Devuelvo el foco
alert("El usuario ya existe, escoge otro");
}
else if (data == "disponible") {
$("#userOK").show();
$("#userMal").hide();
}
}
})
</script>
<?php
?>
if ($_REQUEST["user"] === “carlos") { echo “duplicado";
} else { echo “disponible"; }
“comprobarUsuario.php”
Tema 5.3. jQuery y AJAX 36
JQuery
https://api.jquery.com/
Referencia DOM de Mozilla
https://developer.mozilla.org/en-
US/docs/Web/API/HTML_DOM_API
Referencia de JS de Mozilla
https://developer.mozilla.org/en/JavaScript (Inglés)
Referencias canónicas de la w3c (bastante legibles)
http://www.w3.org/TR/DOM-Level-2-Events/
http://www.w3.org/TR/DOM-Level-3-Events/
Tema 5.3. jQuery y AJAX 37
Licencia Creative Commons
• Este documento tiene establecidas las siguientes condiciones:
Reconocimiento (Attribution):
En cualquier explotación de la obra autorizada por la licencia
hará falta reconocer la autoría.
No comercial (Non commercial):
La explotación de la obra queda limitada a usos no comerciales.
Compartir igual (Share alike):
La explotación autorizada incluye la creación de obras derivadas
siempre que mantengan la misma licencia al ser divulgadas.
• Material elaborado por Pablo Moreno Ger y Manuel Freire Morán, con
modificaciones de Javier Bravo Agapito.
Tema 5.3. jQuery y AJAX 38