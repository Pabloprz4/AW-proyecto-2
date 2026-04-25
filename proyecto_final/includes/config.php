<?php

/**
 * Parámetros de conexión a la BD
 */
define('BD_HOST', getenv('BD_HOST') ?: 'vm009.db.swarm.test');
define('BD_PORT', getenv('BD_PORT') ?: '3306');
define('BD_NAME', getenv('BD_NAME') ?: 'awp3_prod');
define('BD_USER', getenv('BD_USER') ?: 'awp3_app');
define('BD_PASS', getenv('BD_PASS') ?: 'Awp3App!123');

/**
 * Parámetros de configuración utilizados para generar las URLs y las rutas a ficheros en la aplicación
 */
define('RAIZ_APP', __DIR__);
define('RUTA_APP', getenv('APP_BASE_PATH') ?: '/practica3');
define('RUTA_IMGS', RUTA_APP . '/img/');
define('RUTA_CSS', RUTA_APP . '/css/');
define('RUTA_JS', RUTA_APP . '/js/');

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

if (!defined('UPLOAD_AVATARS_DIR')) {
    define('UPLOAD_AVATARS_DIR', APP_ROOT . '/uploads/avatars');
}

if (!defined('PREDEFINED_AVATARS')) {
    define('PREDEFINED_AVATARS', [
        'uploads/avatars/predef_default.svg',
        'uploads/avatars/predef_blue.svg',
        'uploads/avatars/predef_green.svg',
        'uploads/avatars/predef_orange.svg',
    ]);
}

if (!defined('DEFAULT_AVATAR')) {
    define('DEFAULT_AVATAR', 'uploads/avatars/predef_default.svg');
}

/**
 * Configuración del soporte de UTF-8, localización (idioma y país) y zona horaria
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF.8');
date_default_timezone_set('Europe/Madrid');

/**
 * Función para autocargar clases PHP.
 *
 * @see http://www.php-fig.org/psr/psr-4/
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'es\\ucm\\fdi\\aw\\';

    // base directory for the namespace prefix
    $base_dir = implode(DIRECTORY_SEPARATOR, [__DIR__, 'clases', '']);

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

/* */
/* Inicialización de la aplicación */
/* */

define('INSTALADA', true);

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$app->init(array('host'=>BD_HOST, 'bd'=>BD_NAME, 'user'=>BD_USER, 'pass'=>BD_PASS), RUTA_APP, RAIZ_APP);

if (! INSTALADA) {
	$app->paginaError(502, 'Error', 'Oops', 'La aplicación no está configurada. Tienes que modificar el fichero config.php');
}

/**
 * @see http://php.net/manual/en/function.register-shutdown-function.php
 * @see http://php.net/manual/en/language.types.callable.php
 */
register_shutdown_function(array($app, 'shutdown'));

// Incluimos funciones de utiliría básicas que se utilizan en la mayoría de páginas
require_once __DIR__ . '/vistas/helpers/utils.php';
