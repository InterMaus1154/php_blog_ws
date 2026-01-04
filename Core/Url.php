<?php

namespace Core;

class Url
{

    private static ?self $instance = null;

    public readonly string $path;
    public readonly string $method;
    public readonly array $query;
    public readonly int $requestTimeUnix;

    public readonly \DateTime $requestDateTime;
    public readonly ?string $previous;

    public function __construct()
    {
        $this->path = parse_url($_SERVER['REQUEST_URI'])['path'];
        $this->query = explode('&', parse_url($_SERVER['REQUEST_URI'])['query']);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->requestTimeUnix = $_SERVER['REQUEST_TIME'];
        $this->requestDateTime = new \DateTime('@'.$_SERVER['REQUEST_TIME']);
        $this->previous = $_SERVER['HTTP_REFERER'] ?? null;
    }

    public function pathIs(string $value): bool
    {
        return $this->path === $value;
    }

    public static function getInstance(): Url
    {
        if(!isset(self::$instance)){
            self::$instance = new Url();
        }

        return self::$instance;
    }

}