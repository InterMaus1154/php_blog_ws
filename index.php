<?php
require_once __DIR__ . '/vendor/autoload.php';

use Core\App;
use Core\Url;
use Core\Router;
use Core\Env;
use Database\Database;

App::getInstance()
    ->builder(function (App $app) {
        $app->set('service.env', (new Env())->fromFile(App::ENV_PATH));
        $app->set('service.db', function (): Database {

            $host = Env::get('DB_HOST');
            $username = Env::get('DB_USER');
            $password = Env::get('DB_PASSWORD');
            $port = Env::get('DB_PORT');
            $db = Env::get('DB_NAME');
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

            return Database::init(dsn: $dsn, username: $username, password: $password, options: [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        });

        $app->set('service.url', new Url());
        $app->set('service.router', new Router());

    })
    ->beforeExecute(function (App $app) {
        require_once __DIR__ . '/helpers.php';
        $app->get('service.router')->loadRoutesFromFile();
    })
    ->debugBeforeExecute(false, function (App $app) {
        dd(app('service.db'));
    })
    ->execute();