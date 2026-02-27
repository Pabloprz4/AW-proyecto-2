<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if (!is_post() || !verify_csrf()) {
    redirect_to('index.php');
}

auth_logout();
flash_set('ok', 'Sesion cerrada.');
redirect_to('login.php');
