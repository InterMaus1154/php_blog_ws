<?php
require_once __DIR__ . '/vendor/autoload.php';

use Core\App;
use Core\Url;

$app = App::getInstance()
    ->builder(function (array &$services, App $app) {
        $services['env'] = $app->loadEnvFile();
        $services['db'] = $app->loadDatabaseInstance();
        $services['url'] = Url::getInstance();
    })
    ->execute();

dd($app);