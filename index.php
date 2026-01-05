<?php
require_once __DIR__ . '/vendor/autoload.php';

use Core\App;
use Core\Url;
use Core\View;
use Core\Executable;
use Core\Router;


App::getInstance()
    ->builder(function (array &$services, App $app) {
        $services['env'] = $app->loadEnvFile();
        $services['db'] = $app->loadDatabaseInstance();
        $services['url'] = Url::getInstance();
        $services['router'] = Router::getInstance();
    })
    ->beforeExecute(function (App $app) {
        $app->router->loadRoutesFromFile();
    })
    ->execute();
