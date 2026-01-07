<?php

namespace Core;

class Router
{

    private array $routes = [];

    private bool $routesLoaded = false;

    public function __construct()
    {
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
        foreach ($this->routes as $method => $path) {

        }
    }

    public function hasRoute(string $method, string $path): bool
    {
        return isset($this->routes[$method][$path]);
    }

    public function dispatch(Url $url)
    {
        if (!$this->hasRoute($url->method, $url->path)) {
            http_response_code(404);
            die("404: Page not exists!");
        }

        $routeAction = $this->routes[$url->method][$url->path];

        if (is_array($routeAction)) {
            list($class, $method) = $routeAction;
            $obj = new $class();
            $res = $obj->{$method}();
        } else if (is_callable($routeAction)) {
            $res = $routeAction();
        } else {
            die("Invalid route action method type.");
        }

        if ($res instanceof Executable) {
            $res->execute();
            exit(0);
        } else if (is_scalar($res)) {
            echo $res;
            exit(0);
        } else {
            echo "Route action can only return Executable or callable" . PHP_EOL;
            echo "Found: " . gettype($res) . PHP_EOL;
            die;
        }
    }

}