<?php
class Router {
    protected $routes = [];

    public function add($method, $route, $controller, $action) {
        $route = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $route);
        $route = '#^' . $route . '$#';
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'controller' => $controller,
            'action' => $action
        ];
    }

    public function get($route, $controller, $action) {
        $this->add('GET', $route, $controller, $action);
    }

    public function post($route, $controller, $action) {
        $this->add('POST', $route, $controller, $action);
    }

    public function dispatch($url) {
        $url = parse_url($url, PHP_URL_PATH);
        $basePath = '/discover_rangsit/public';
        if (strpos($url, $basePath) === 0) {
            $url = substr($url, strlen($basePath));
        }
        if ($url == '') $url = '/';

        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] == $method && preg_match($route['route'], $url, $matches)) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];

                require_once '../app/controllers/' . $controllerName . '.php';
                $controller = new $controllerName();

                $params = [];
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                call_user_func_array([$controller, $actionName], $params);
                return;
            }
        }

        // 404 handler
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found - Route not defined.";
    }
}