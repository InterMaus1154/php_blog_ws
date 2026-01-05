<?php

use Core\App;
use Core\Url;
use JetBrains\PhpStorm\NoReturn;
use Core\View;

if (!function_exists('dd')) {
    #[NoReturn]
    function dd(mixed $value): void
    {
        dump($value);
        die;
    }
}

if (!function_exists('dump')) {
    function dump(): void
    {
        echo "<div style='background-color: #000; padding: 2rem; color: lightgreen; font-size: 1.15rem; margin-block: 1rem;'>";
        echo "<pre>";
        foreach (func_get_args() as $arg) {
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

if (!function_exists('uri')) {
    function uri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
}


if (!function_exists('url')) {
    function url(): Url
    {
        return Url::getInstance();
    }
}

if (!function_exists('app')) {
    function app()
    {
        $app = App::getInstance();
        if (func_num_args() === 1) {
            return $app->get(func_get_arg(0));
        } else if (func_num_args() === 2) {
            return $app->set(func_get_arg(0), func_get_arg(1));
        } else {
            return $app;
        }
    }
}

if (!function_exists('view')) {

    function view(string $view, array $data = []): View
    {
        return View::make($view, $data);
    }
}