<?php

use Core\Router;
use App\Controllers\ViewController;
use App\Controllers\PostController;

app('service.router')->buildRoutes(function (Router $router) {
    $router->get('/', [ViewController::class, 'index']);

    $router->get('/posts', [PostController::class, 'index']);
    $router->get('/posts/{id}', [PostController::class, 'show']);
});

