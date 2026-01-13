<?php

namespace Core\Http;

readonly class Url
{

    public string $path;
    public string $method;
    public ?array $query;
    public int $requestTimeUnix;

    public \DateTime $requestDateTime;
    public ?string $previous;

    public function __construct()
    {
        $path = parse_url($_SERVER['REQUEST_URI'])['path'];
        $this->path = $path === '/' ? '/' : rtrim($path, '/');

        $this->query = isset(parse_url($_SERVER['REQUEST_URI'])['query']) ? explode('&', parse_url($_SERVER['REQUEST_URI'])['query']) : [];

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->requestTimeUnix = $_SERVER['REQUEST_TIME'];
        $this->requestDateTime = new \DateTime('@'.$_SERVER['REQUEST_TIME']);
        $this->previous = $_SERVER['HTTP_REFERER'] ?? null;
    }

    public function pathIs(string $value): bool
    {
        return $this->path === $value;
    }


}