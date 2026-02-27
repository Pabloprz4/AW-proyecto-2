<?php
declare(strict_types=1);

if (!defined('BD_HOST')) {
    define('BD_HOST', getenv('BD_HOST') ?: 'localhost');
}

if (!defined('BD_PORT')) {
    define('BD_PORT', getenv('BD_PORT') ?: '3306');
}

if (!defined('BD_NAME')) {
    define('BD_NAME', getenv('BD_NAME') ?: 'awp2_beta');
}

if (!defined('BD_USER')) {
    define('BD_USER', getenv('BD_USER') ?: 'awp2_app');
}

if (!defined('BD_PASS')) {
    define('BD_PASS', getenv('BD_PASS') ?: '');
}

if (!defined('RUTA_APP')) {
    $basePath = getenv('APP_BASE_PATH');
    if ($basePath === false) {
        $basePath = '/practica2';
    }
    define('RUTA_APP', rtrim((string) $basePath, '/'));
}

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

if (!defined('UPLOAD_AVATARS_DIR')) {
    define('UPLOAD_AVATARS_DIR', APP_ROOT . '/uploads/avatars');
}

if (!defined('UPLOAD_AVATARS_WEB')) {
    $prefix = RUTA_APP === '' ? '' : RUTA_APP;
    define('UPLOAD_AVATARS_WEB', $prefix . '/uploads/avatars');
}

ini_set('default_charset', 'UTF-8');
setlocale(LC_ALL, 'es_ES.UTF.8');
date_default_timezone_set('Europe/Madrid');
