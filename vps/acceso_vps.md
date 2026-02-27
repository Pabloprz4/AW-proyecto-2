Acceso y uso del VPS de prácticas

Cada grupo de prácticas dispone de un Virtual Private Server (VPS) donde poder alojar su práctica para su entrega. Igual que otros años el servicio se proporciona AS IS, es decir, no desarrolléis directamente sobre él o utilicéis el VPS como medio para compartir la práctica. Si hubiera algún problema con el VPS la única solución será reiniciar el VPS (borrando los datos existentes).

De manera similar a otros años, los servidores están alojados en la infraestructura de laboratorios y se deben solicitar como otros materiales de prácticas. Revisa el CV de tu grupo de AW / SW donde encontrarás unas instrucciones adicionales incluido el proceso de solicitud del material.

Puedes ver una pequeña demo uso del servidor en el siguiente vídeo: https://youtu.be/p4BS-E_m7wI

También se proporciona un servicio adicional en https://mail.containers.fdi.ucm.es. Este servicio simula una bandeja de entrada de correo electrónico de modo que si desde php se utiliza la función mail para enviar un correo aparecerá en esa bandeja de entrada.
Conexión al servidor de prácticas
Antes de poder acceder es necesario que tengáis conectividad UCM, es decir, tenéis que utilizar el servicio VPN de la UCM para poder acceder a ellos con objeto de evitar visitantes no deseados a la máquina.

A través de la web de laboratorios podéis acceder a 2 datos:
Identificador del VPS asignado: vm01, vm023, etc.
Una contraseña. Esta contraseña es la contraseña del usuario root del VPS y del servidor MariaDB que tenéis asignado. También sirve como contraseña para la webshell.

URLs de acceso a los diferentes servicios.
https://guacamole.containers.fdi.ucm.es. Instancia de Apache Guacamole que utilizamos como webshell para acceder a un terminal de VPS. El nombre de usuario es vmXXX y la contraseña es la que os han facilitado.
https://phpmyadmin.containers.fdi.ucm.es. Instancia de phpmyadmin compartida por todos los VPS. Debéis seleccionar vuestro servidor específico de BD vmXXX.db.swarm.test. El usuario administrativo es root y la contraseña es la que os han facilitado.
https://vmXXX.containers.fdi.ucm.es. Esta es la URL donde se encontrará disponible vuestra aplicación para entregar.
https://vmXXX-beta.containers.fdi.ucm.es. Esta es la URL donde se encontrará disponible vuestra aplicación para hacer pruebas mientras se está evaluando la práctica entregada.
Web Shell
La webshell está basada en Apache Guacamole, por lo que es interesante que reviséis su documentación para aprender los pasos básicos, en particular:
Cómo abrir el menú de opciones.
Cómo poder copiar y pegar texto.
Subir ficheros al servidor. 
NOTA1: El tamaño máximo de archivo a subir es de 200M. Os recomiendo que hagáis un .zip de vuestro proyecto y subáis este archivo en vez de subir los archivos individualmente para que sea más rápido.
NOTA2: En la documentación indican que es posible arrastrar un fichero a la shell y se sube al servidor, pero el directorio destino siempre es /root, por lo que tendréis que mover el archivo al destino que necesitéis.
NOTA 3: Os recuerdo que tenéis que dejar los ficheros de vuestra práctica en el directorio DocumentRoot de Apache. Como tenemos dos entornos, tenemos dos directorios:
El directorio /var/www/produccion es el correspondiente a https://vmXXX.containers.fdi.ucm.es
El directorio /var/www/beta es el correspondiente a https://vmXXX-beta.containers.fdi.ucm.es

Por otro lado, aunque Apache Guacamole permite realizar conexiones múltiples contra el mismo servidor, cada VPS tiene limitada sus conexiones a 1 conexión concurrente.

Una vez estáis conectados se trata de una shell linux donde podéis ejecutar los diferentes comandos que sean necesarios.

NOTA: La gestión de los servicios como Apache, SSH o MariaDB (iniciar, reiniciar, etc.) se realiza automáticamente. Sólo si cambiais la configuración proporcionada de PHP y/o Apache será necesario reiniciarlo (ver sección comandos útiles).
PHPMyAdmin
Esta instancia de phpMyAdmin es compartida por todos los VPS pero es similar al que utilizais en XAMPP.

Cada VPS tiene asignado un servidor de BD propio vmXXX.db.swarm.test. Este es el nombre de host que tenéis que utilizar en el DSN de PDO o en el campo “host” de mysqli.

NOTA: Es altamente recomendable que creéis un usuario específico para vuestra aplicación y sólo usar root para tareas administrativas.
Acceso a vuestra aplicación
Cada VPS tiene asignado un dominio propio vmXXX.containers.fdi.ucm.es y tiene asignado un certificado TLS válido por lo que es posible utilizar APIs Javascript que requieren conexiones HTTPS en dominios diferentes a localhost.
Errores habituales
Habitualmente vais a encontrar errores debido a que el VPS se encuentra configurado de un modo diferente a vuestro XAMPP y a diferencia respecto a la plataforma donde se ejecuta, normalmente vosotros utilizáis Windows y el VPS está en Linux.
Os recuerdo que en caso de fallo, debéis consultar el archivo de logs de error de PHP para averiguar si se debe a algún error en vuestro código PHP (ver siguiente sección de comandos útiles).
Los errores más habituales con el VPS son:
“Warning Cannot modify header information - headers already sent”. Este error es debido habitualmente a que estáis invocando session_start() después de haber empezado a enviar datos al usuario (NOTA: <!DOCTYPE html> cuenta como caracteres enviados, o si tenéis la codificación UTF8-BOM en los ficheros se está enviando un carácter oculto). La manera de resolver el problema es hacer el session_start() y, en general, procesar toda la petición antes de enviar nada al usuario. Al utilizar la plantilla del ejercicio 3 no deberíais tener este problema.
Fallan las consultas SQL. Internamente MySQL y MariaDB guardan las tablas en ficheros de datos independientes. En el caso de Windows los nombres de ficheros no son sensibles a mayúsculas y minúsculas, pero en el caso de Linux / MacOS sí lo son. Si creáis una tabla (revisad el DDL) con CREATE TABLE `Usuarios`, en las instrucciones SQL necesariamente tenéis que usar el nombre ‘Usuarios’ como nombre de tabla. En el caso de Windows suele funcionar sin problemas ‘usuarios’, ‘USUARIOS’ pero en Linux tiene que coincidir exactamente.
Problemas con la subida de archivos. Cuando subís los archivos y directorios al servidor lo hacéis como usuario root, pero Apache (y PHP) se ejecutan como otro usuario. Como se indica en este documento es recomendable ejecutar fix-www-acl para ajustar los permisos y la propiedad de archivos y directorios en el directorio htdocs de apache y que no haya problemas y PHP pueda escribir (más bien mover) los ficheros asociados a un formulario.
No se cargan los recursos (css, imágenes, js, etc.). Dependiendo de la organización de los ficheros de vistas dentro de vuestro proyecto, puede ser más o menos compleja la generación de URLs para los recursos, en algunos casos es necesario empezar a añadir ../ para que se carguen los recursos. No obstante, esto es un problema ya que en cuanto mueves el archivo a otra carpeta o tienes una plantilla común para todas las páginas, estas rutas dejan de funcionar. Si revisas la solución del ejercicio 3 (anexo 3) o la solución avanzada del ejercicio 2 verás que en el config.php se definen varias constantes RUTA_APP, RUTA_IMG, RUTA_CSS, etc. Estas constantes se usan como puntos de partida para generar las URLs que apuntan a los recursos como en cabecera.php. De este modo, ya puedes colocar las plantillas y las vistas en cualquier carpeta o subcarpeta sin tener que preocuparse respecto a las rutas.
Otra información de interés
Recordatorio de comandos útiles
fix-www-acl
Ajusta la propiedad y permisos del directorio /var/www/html y subdirectorios para que Apache pueda utilizarlos. Es necesario que cada vez que modifiquéis o subáis una versión de la práctica lo apliquéis para que todo funcione.
rm -fr /var/www/produccion/*; unzip -d /var/www/produccion /root/proyecto.zip; fix-www-acl
Se trata de 3 comandos que: 1) borran el contenido que sirve Apache; descomprime un zip que habéis subido arrastrando el archivo a la webshell y 3) arregla los permisos de los ficheros descomprimidos.
tail -f /var/log/php/errors.log. Os permite ver los mensajes de error de PHP. Este comando no termina hasta que pulsáis Ctrl+C.
tail -f /var/log/apache2/error.log. Os permite ver los mensajes de error de Apache. Este comando no termina hasta que pulsáis Ctrl+C.
apache2ctl configtest: Verifica que las modificaciones que habéis hecho en un archivo en /etc/apache2 no tienen ningún problema “sintáctico”. Es recomendable verificarlo antes de reiniciar el servidor.
apache2ctl graceful: reinicia el servidor de Apache. Necesario si cambiais algún archivo en /etc/apache2 o /usr/local/etc/php.
Características del VPS
Generales:
CPU: 0.125 - 0.5 vCPU
disco: 1GB de datos
RAM: 256 MB (para Apache+PHP) + 256MB (para MariaDB)
Software instalado:
Apache 2.4
PHP 8.2.12
MariaDB 10.4.32
Herramientas básicas de consola linux: vim, nano, tmux, less, grep, etc.
Directorios donde se puede escribir:
/root. HOME de vuestro usuario (root).
/var/www. El directorio /var/www/produccion y /var/www/beta son los DocumentRoot de Apache.
/var/log. El directorio /var/log/apache2 contiene los archivos de log de Apache y /var/log/php contiene los logs de PHP.
/etc/apache2. Contiene los archivos de configuración de Apache2.
/var/lib. El directorio /var/lib/mysql contiene los archivos de vuestra BD.
/usr/local/etc/php. El directorio contiene los archivos de configuración (.ini) de PHP.
/tmp. Archivos temporales 