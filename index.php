<?php
require_once __DIR__ . '/vendor/autoload.php';

use Core\App;

$app = App::getInstance()
    ->builder(function (array &$services, App $app) {
        $services['env'] = $app->loadEnvFile();
        $services['db'] = $app->loadDatabaseInstance();
    })
    ->execute();

dump($app);
dump($app);