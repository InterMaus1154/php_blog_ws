<?php

use App\Controllers\PostController;
use App\Controllers\ViewController;
use Core\Routing\Router;

app('service.router')->buildRoutes(function (Router $router) {
    $router->get('/', [ViewController::class, 'index']);

    $router->get('/posts', [PostController::class, 'index']);
    $router->get('/posts/{id}', [PostController::class, 'show']);
});

