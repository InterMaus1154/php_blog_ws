<?php
use Core\Router;
use App\Controllers\ViewController;

app('service.router')->buildRoutes(function(Router $router){
    $router->get('/', [ViewController::class, 'index']);

    $router->get('/about', function(){
       return view('about', ['heading' => 'test']);
    });

});

