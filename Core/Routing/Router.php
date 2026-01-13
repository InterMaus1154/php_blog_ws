<?php

namespace Core\Routing;

use Core\App\Executable;
use Core\Http\Url;

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

    public function dumpRoutes(): string
    {
        $routes = "";
        foreach ($this->routes as $method => $paths) {
            $routes .= sprintf("%s:\n\t", $method);
            foreach ($paths as $path => $action) {
                $routes .= sprintf("- %s -> [%s: %s]\n\t", $path, $action[0], $action[1]);
            }
            $routes .= "\n\t";
        }

        return $routes;
    }

    public function hasRoute(string $method, string $path): bool
    {
        return isset($this->routes[$method][$path]);
    }

    public function dispatch(Url $url)
    {

        if (!isset($this->routes[$url->method])) {
            http_response_code(404);
            die("Invalid method for " . $url->path);
        }

        foreach ($this->routes[$url->method] as $route => $routeAction) {
            $pattern = preg_replace("#\{\w+\}#", "([^\/]+)", $route);

            if (preg_match("#^$pattern$#", $url->path, $matches)) {

                array_shift($matches);

                if (is_array($routeAction)) {
                    list($class, $method) = $routeAction;
                    $obj = new $class();
                    $res = $obj->{$method}(...array_values($matches));
                } else if (is_callable($routeAction)) {
                    $res = $routeAction(...array_values($matches));
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

        http_response_code(404);
        die("Page not found");
    }

}