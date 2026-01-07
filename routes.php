<?php
use Core\Router;

app('service.router')->buildRoutes(function(Router $router){
    $router->get('/', function(){
        echo "hello world";
    });

    $router->post('/login', function(){

    });

});

