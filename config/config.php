<?php 

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    define('SITE_NAME', 'EVENTZ');
    define('SITE_URL', 'http://localhost/Eventx/');
    define('ROOT', __DIR__ . '/..');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'eventx_db');
    define('DB_USER', 'root');
    define('DB_PASS', '');

?>