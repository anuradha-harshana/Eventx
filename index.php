<?php

require_once __DIR__ . '/config/config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


spl_autoload_register(function ($class) {

    $paths = [
        ROOT . '/app/core/',
        ROOT . '/app/controllers/',
        ROOT . '/app/models/',
    ];

    foreach ($paths as $path) {

        $file = $path . $class . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});


$router = new Router();

$routes = require ROOT . '/config/routes.php';

foreach ($routes as $method => $routeList) {

    foreach ($routeList as $uri => $action) {

        $router->$method($uri, $action);
    }
}


$router->dispatch(
    $_SERVER['REQUEST_URI'],
    $_SERVER['REQUEST_METHOD']
);
