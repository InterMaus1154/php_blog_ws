<?php

use JetBrains\PhpStorm\NoReturn;

if (!function_exists('dd')) {
    #[NoReturn]
    function dd(mixed $value): void
    {
        dump($value);
        die;
    }
}

if(!function_exists('dump')){
    function dump(): void
    {
        echo "<div style='background-color: #000; padding: 2rem; color: lightgreen; font-size: 1.15rem; margin-block: 1rem;'>";
        echo "<pre>";
        foreach (func_get_args() as $arg){
            var_dump($arg);
        }
        echo "</pre>";
        echo "</div>";
    }
}

if (!function_exists('urlIs')) {
    function urlIs(string $value): bool
    {
        return $_SERVER['REQUEST_URI'] === $value;
    }
}

if(!function_exists('uri')){
    function uri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
}