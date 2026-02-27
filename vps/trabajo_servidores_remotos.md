X
Facultad de Informática
Universidad Complutense de Madrid
Material elaborado por Iván Martínez Ortiz, Pablo Moreno Ger.
Aunque XAMPP está muy bien para practicar, no es apto
para publicar nuestra web
 Nuestro ordenador no suele ser “visible” desde Internet
 Tiene limitaciones de seguridad
 No siempre tendremos el ordenador encendido
Es preferible publicar las webs en servidores dedicados
para ello
 Alta disponibilidad y siempre conectado
 Habitualmente, Linux (y sólo consola)
 IP fija y visible en Internet
Trabajo con servidores remotos 2
Trabajar en remoto es más complicado
 ¿Cómo accedemos al servidor?
 ¿Cómo configuramos el servidor?
 ¿Cómo publicamos la aplicación?
 ¿Cómo creamos la base de datos?
 ¿Cómo podemos consultar los logs?
Trabajo con servidores remotos 3
Trabajo con servidores remotos 4
Los servidores remotos pueden ofrecer:
 Paneles de control con interfaz gráfica (App Web)
 Opción habitual para la infraestructura de laboratorios
 Acceso directo a la consola (vía SSH)
Acceso vía SSH:
ssh [-p PUERTO] usuario@servidor
 Por defecto, el puerto es el 22
 Una vez conectados, tenemos todos los comandos de consola
Linux a nuestra disposición
Trabajo con servidores remotos 5
Lo habitual es subir los archivos mediante algún
protocolo usando herramientas
 FTP (poco seguro)
 SFTP (FTP sobre SSH, muy seguro)
Trabajo con servidores remotos 6
Depende del tipo de sistema operativo en el servidor
 Consultar la documentación para encontrarlo
Lo más frecuente
 /etc/apache2/…
 Acceso vía consola o FTP/SFTP
Trabajo con servidores remotos 7
OPCIÓN 1: Uso de MySQL desde la línea de comandos
 Conexión vía SSH y trabajo normal desde consola
OPCIÓN 2: Uso de herramientas de escritorio en local
 Conexión remota a la base de datos del servidor
 Puerto 3306
 Poco frecuente - Las BBDD suelen estar “capadas” para acceso local
exclusivo
OPCIÓN 3: Instalar PHPMyAdmin
 Aprovechamos que ya hay un Apache para instalarlo
 Acceso vía servidor web
Trabajo con servidores remotos 8
Depende del tipo de sistema operativo en el servidor
 Consultar la documentación para encontrarlo
Lo más frecuente
 /var/www
 /var/www/html es el DocumentRoot de Apache
 Acceso vía consola o FTP/SFTP
Trabajo con servidores remotos 9
Un servidor en producción nunca debe “mostrar” sus
errores
 Pueden dar pistas sobre problemas de seguridad
 Queda muy mal :)
Apache escribe todos los errores de PHP en un archivo de
registro
 /var/log/apache2/error.log
 Comando muy útil (vía SSH)
tail /var/log/apache2/error.log
Trabajo con servidores remotos 10
fix-www-acl
• Ajusta la propiedad y permisos del directorio
/var/www/html y subdirectorios para que Apache
pueda utilizarlos
Trabajo con servidores remotos 11
Trabajo con servidores remotos 12
Conexión SSH
 Windows – BitVise SSH Client
 OSX / Linux – Soporte nativo en la consola
Cliente SFTP
 Windows – BitVise SSH Client
 OSX – CyberDuck
 Linux - ??
Trabajo con servidores remotos 13
Trabajo con servidores remotos 14
En el laboratorio podéis solicitar un servidor dedicado
 LAMP puro (con PHPMyAdmin)
 Accesible desde Internet
 Uno por grupo
Web Facultad >>> Laboratorios FDI >>>
Préstamo de material
Trabajo con servidores remotos 15
Solicitud online
 Un miembro del grupo hace la solicitud
 Debe aportar el correo electrónico de todos los miembros
 Los miembros deben confirmar por correo
Declaración de uso responsable
 Un servidor dedicado y público es una cosa delicada
 Uso exclusivo para el proyecto
Trabajo con servidores remotos 16
Servicio no garantizado
 Borrado en caso de amenaza de seguridad o uso indebido
 ¡Debéis hacer backups frecuentes!
Trabajo con servidores remotos 17
Lo que recibís
 URL del panel de acceso (Guacamole)
 https://guacamole.containers.fdi.ucm.es
 URL del panel de administración de BD (phpMyAdmin)
 https://phpmyadmin.containers.fdi.ucm.es/
 Nombre del servidor de BD
 vm01.db.swarm.test en el ejemplo
 URL de acceso web a vuestro servicio
 https://vmXX.contaienrs.fdi.ucm.es
 https://vm01.containers.fdi.ucm.es en el ejemplo
 Usuario de guacamole: vmXXX
 Password Guacamole / phpMyAdmin
 ¡Cuidadlo bien!
Si te conectas desde casa debes utilizar la VPN de la
UCM https://ssii.ucm.es/vpn
Trabajo con servidores remotos 18
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
 Material elaborado por Pablo Moreno Ger.
Trabajo con servidores remotos
19
Trabajo con servidores remotos 20
 En un entorno más realista no tendríamos Guacamole sino que
utilizaríamos un jump-server o bastion-host server para
conectarnos a la máquina de producción por SSH / SFTP
 https://en.wikipedia.org/wiki/Bastion_host
 https://en.wikipedia.org/wiki/Jump_server
 En este caso tenemos que conectarnos a una máquina
intermedia antes de llegar a nuestro destino
Bastion-host VM
producción
Desarrollador
Firewall
Trabajo con servidores remotos 21
Opción 1: Usando el terminal + Filezilla / sftp / putty
1. 2. Crear el túnel entre el tu equipo de desarrollo y el VPS
 Línea de comandos
– ssh -L 2222:vmxx.swarm.test:22 -N hop@containers.fdi.ucm.es
– plink -L 2222:vmxx.swarm.test:22 -N hop@containers.fdi.ucm.es
 Password del usuario hop => hop2021
-L 2222:vmxx.swarm.test:22 => crea un túnel entre el puerto
2222 de la máquina donde se ejecuta el comando con el
puerto 22 la máquina vmxx.swarm.test
Establecer la conexión por SFTP a través del túnel
 Servidor: localhost
 Puerto: 2222
 Usuario: root
 Password: Aparece en el préstamo
Trabajo con servidores remotos 22
Filezilla
Trabajo con servidores remotos 23
Putty
Trabajo con servidores remotos 24
Opción 2: Usar WinSCP (todo en uno)
 Extra: Si haces doble clic sobre un fichero del servidor se abre
un editor local (asociado al tipo de archivo archivo). Cuando
guardas los cambios se envían directamente al servidor
vmxx.swarm.test
Trabajo con servidores remotos 25
Opción 2: Usar WinSCP (todo en uno)
containers.fdi.ucm.es
Trabajo con servidores remotos 26