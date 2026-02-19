<?php 

    class Router {
        private $routes = [];

        public function get($uri, $action){
            $this->routes['GET'][$uri] = $action;
        }

        public function post($uri, $action){
            $this->routes['POST'][$uri] = $action;
        }

        public function dispatch($uri, $method)
        {
            $uri = parse_url($uri, PHP_URL_PATH);

            $scriptName = dirname($_SERVER['SCRIPT_NAME']);

            if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
                $uri = substr($uri, strlen($scriptName));
            }

            $uri = rtrim($uri, '/') ?: '/';

            if (!isset($this->routes[$method])) {
                http_response_code(404);
                echo "404 Not Found";
                return;
            }

            foreach ($this->routes[$method] as $route => $action) {

                // Convert /editEvent/{id} → regex
                $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
                $pattern = "#^" . $pattern . "$#";

                if (preg_match($pattern, $uri, $matches)) {

                    array_shift($matches); // remove full match

                    list($controllerName, $methodName) = explode('@', $action);

                    $controller = new $controllerName();

                    call_user_func_array([$controller, $methodName], $matches);

                    return;
                }
            }

            http_response_code(404);
            echo "404 Not Found";
        }

    }

?>