<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (!defined('BD_PORT')) {
    define('BD_PORT', '3306');
}

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

if (!defined('UPLOAD_AVATARS_DIR')) {
    define('UPLOAD_AVATARS_DIR', APP_ROOT . '/uploads/avatars');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!is_dir(UPLOAD_AVATARS_DIR)) {
    mkdir(UPLOAD_AVATARS_DIR, 0775, true);
}

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/repos/UsuarioRepository.php';
require_once __DIR__ . '/repos/CategoriaRepository.php';
require_once __DIR__ . '/repos/ProductoRepository.php';
require_once __DIR__ . '/repos/PedidoRepository.php';
require_once __DIR__ . '/auth.php';
