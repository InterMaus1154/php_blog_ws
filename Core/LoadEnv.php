<?php

namespace Core;

final readonly class LoadEnv
{
    public function __construct(private string $filePath)
    {
    }

    public function load(): void
    {
        if(!file_exists($this->filePath)){
            throw new \Exception(".env file not found at specified path: $this->filePath");
        }

        // load file
        $lines = file($this->filePath, FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            // ignore lines without =
            if(!str_contains($line, '=')) continue;
            putenv($line);
        }
    }
}