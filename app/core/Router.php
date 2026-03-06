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
        
        // ดึงเอาเฉพาะส่วนที่เป็น Path จาก BASE_URL ใน config
        // เช่น ถ้า BASE_URL คือ https://domain.com/public ค่า $basePath จะเป็น /public
        $basePath = parse_url(BASE_URL, PHP_URL_PATH) ?: '';
        
        // ตัดส่วนที่เป็น basePath ออกจาก URL ที่รับเข้ามา
        if ($basePath !== '' && $basePath !== '/' && strpos($url, $basePath) === 0) {
            $url = substr($url, strlen($basePath));
        }
        
        if ($url == '' || $url == '/') $url = '/';

        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] == $method && preg_match($route['route'], $url, $matches)) {
                $controllerName = $route['controller'];
                $actionName = $route['action'];

                require_once '../app/controllers/' . $controllerName . '.php';
                $controller = new $controllerName();

                // Page Access Logging (Safe Mode)
                try {
                    $logFile = APP_ROOT . '/app/models/ActivityLog.php';
                    if (file_exists($logFile)) {
                        require_once $logFile;
                        $logModel = new ActivityLog();
                        $logModel->log([
                            'user_id' => $_SESSION['user_id'] ?? null,
                            'action' => 'PAGE_ACCESS',
                            'description' => "Accessed " . $url
                        ]);
                    }
                } catch (Exception $e) {
                    // Fail silently to keep site alive
                }

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