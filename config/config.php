<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    define('SITE_NAME', 'EVENTZ');
    define('ROOT', __DIR__ . '/..');

    // ── Database ───────────────────────────────────────────────────────────────
    // Environment variables are set by docker-compose.yml when running in Docker.
    // The fallback values keep the app working on a plain XAMPP / local install.
    define('DB_HOST', getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost');
    define('DB_NAME', getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'eventx_db');
    define('DB_USER', getenv('DB_USER') !== false ? getenv('DB_USER') : 'root');
    define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');

    // ── Site URL ───────────────────────────────────────────────────────────────
    // In Docker: set SITE_URL=http://localhost:8080/Eventx/ via docker-compose.yml
    // On XAMPP:  falls back to the classic local path below.
    define('SITE_URL', getenv('SITE_URL') !== false ? getenv('SITE_URL') : 'http://localhost/Eventx/');

?>