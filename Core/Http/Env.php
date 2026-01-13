<?php

namespace Core\Http;

final readonly class Env
{
    public function __construct()
    {
    }

    public function fromFile(string $filePath): Env
    {
        if(!file_exists($filePath)){
            throw new \Exception(".env not found at $filePath");
        }

        // load file
        $lines = file($filePath, FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            // ignore lines without =
            if(!str_contains($line, '=')) continue;
            putenv($line);
            list($key, $value) = explode('=', $line);
            $_ENV[$key] = $value;
        }
        return $this;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        if(!getenv($key) && !isset($_ENV[$key])) return $default;
        return getenv($key) ?? $_ENV[$key];
    }

    public function __get(string $key): mixed
    {
        return self::get($key);
    }

    public function __set(string $key, mixed $value)
    {
        if(!is_scalar($value)){
            throw new \InvalidArgumentException("Env value can only be scalar");
        }
        putenv("$key=$value");
    }
}