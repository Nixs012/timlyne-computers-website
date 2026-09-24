<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function get($route, $action, $middleware = []) {
        $this->addRoute('GET', $route, $action, $middleware);
    }

    public function post($route, $action, $middleware = []) {
        $this->addRoute('POST', $route, $action, $middleware);
    }

    private function addRoute($method, $route, $action, $middleware) {
        // Convert route params like [slug] to regex
        $routeRegex = preg_replace('/\[([a-zA-Z0-9_]+)\]/', '(?P<\1>[a-zA-Z0-9_-]+)', $route);
        $routeRegex = '#^' . $routeRegex . '/?$#';
        
        $this->routes[] = [
            'method' => $method,
            'pattern' => $routeRegex,
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function dispatch($uri, $method) {
        $uri = parse_url($uri, PHP_URL_PATH);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                // Run middleware
                foreach ($route['middleware'] as $mw) {
                    $mwInstance = new $mw();
                    if (!$mwInstance->handle()) {
                        return; // Middleware blocked request
                    }
                }

                // Clean matches to only include named params
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                list($controller, $methodName) = explode('@', $route['action']);
                $controllerClass = "\\App\\Controllers\\" . $controller;
                
                $instance = new $controllerClass();
                return call_user_func_array([$instance, $methodName], $params);
            }
        }
        
        // 404
        http_response_code(404);
        echo "404 Not Found";
    }
}
