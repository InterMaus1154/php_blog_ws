<?php
require_once __DIR__ . '/vendor/autoload.php';

use Core\App;
use Core\Url;
use Core\Router;

App::getInstance()
    ->builder(function (App $app) {
        $app->set('service.env', $app->loadEnvFile());
        $app->set('service.db', $app->loadDatabaseInstance());
        $app->set('service.url', new Url());
        $app->set('service.router', new Router());

    })
    ->beforeExecute(function (App $app) {
        require_once __DIR__ . '/helpers.php';
        $app->get('service.router')->loadRoutesFromFile();
    })
    ->execute();
