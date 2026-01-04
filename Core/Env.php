<?php

namespace Core;

final readonly class Env
{
    public function __construct(private string $filePath)
    {
    }

    public function loadFromFile(): void
    {
        if(!file_exists($this->filePath)){
            return;
        }

        // load file
        $lines = file($this->filePath, FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            // ignore lines without =
            if(!str_contains($line, '=')) continue;
            putenv($line);
            list($key, $value) = explode('=', $line);
            $_ENV[$key] = $value;
        }
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
}