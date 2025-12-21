<?php
require_once __DIR__ . '/vendor/autoload.php';

// load .env file
$loadEnv = new \Core\LoadEnv(__DIR__ . '/.en');
$loadEnv->load();

$host = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');
$db = getenv('DB_NAME');
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$db = \Database\Database::init(dsn: $dsn, username: $username, password: $password, options: [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
]);

