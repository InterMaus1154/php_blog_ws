<?php

namespace Core;

class Router
{

    private static ?self $instance;

    private array $routes = [];

    private bool $routesLoaded = false;

    private function __construct()
    {
    }

    public static function getInstance(): Router
    {
        if (!isset(self::$instance)) {
            self::$instance = new Router();
        }

        return self::$instance;
    }

    public function add(string $method, string $path, callable|array $action)
    {
        $this->routes[$method][$path] = $action;
    }

    public function get(string $path, callable|array $action)
    {
        $this->add('GET', $path, $action);
    }

    public function post(string $path, callable|array $action)
    {
        $this->add('POST', $path, $action);
    }

    public function buildRoutes(\Closure $builder)
    {
        $builder($this);
    }

    public function loadRoutesFromFile()
    {
        $file = __DIR__ . '/../routes.php';
        if (!file_exists($file)) {
            http_response_code(404);
            throw new \Exception("Route file not found at specified path: $file");
        }

        require_once $file;
        $this->routesLoaded = true;

    }

    public function dumpRoutes()
    {
        dump($this->routes);
    }

    public function hasRoute(string $method, string $path): bool
    {
        return isset($this->routes[$method][$path]);
    }

}