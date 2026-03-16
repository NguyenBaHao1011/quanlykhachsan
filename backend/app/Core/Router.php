<?php

namespace App\Core;

class Router {
    protected $routes = [];

    public function add($method, $uri, $controller) {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }

    public function handle($method, $uri) {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                list($controllerName, $methodName) = explode('@', $route['controller']);
                $controllerClass = "App\\Controllers\\" . $controllerName;
                $controller = new $controllerClass();
                return $controller->$methodName();
            }
        }
        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
    }
}
