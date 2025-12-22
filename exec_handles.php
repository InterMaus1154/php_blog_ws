<?php

/**
 * Handle the "dev" command
 * @param int $argc
 * @param array $argv
 * @return void
 */
function handleDev(int $argc, array $argv): void
{
    $devPort = 8000;

    // parse port number
    // if argc doesn't have more than 3, then there is no way it will have port
    if ($argc > 2) {
        foreach ($argv as $arg) {
            if (str_starts_with($arg, '--port=')) {
                // split string, extract port number
                $devPort = (int)explode('=', $arg)[1];
                if (!is_int($devPort) || $devPort < 1 || $devPort > 65535) {
                    echo "Invalid port number\n";
                    echo "Port must be between 1 and 65,535\n";
                    die;
                }
                // exit out of loop, because port is found - not looking for other arguments for dev server
                break;
            }
        }
    }

    // run default php server
    passthru("php -S localhost:$devPort index.php");
}

/**
 * Display list of available commands
 * @return void
 */
function listCommands(): void
{
    echo "List of available commands:\n";
    echo "dev --> Runs dev server\n";
    echo "help --> Lists available commands\n";
}

/**
 * Run migration files
 * @return void
 */
function migrate(): void
{
    require_once __DIR__ . '/Database/Migration.php';
    require_once __DIR__ .'/Database/Migrator.php';
    require_once __DIR__ .'/Database/Database.php';
    require_once __DIR__ . '/Core/LoadEnv.php';

    $loadEnv = new \Core\LoadEnv(__DIR__ . '/.env');
    $loadEnv->load();

    $host = getenv('DB_HOST');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');
    $db = getenv('DB_NAME');
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    \Database\Database::init(dsn: $dsn, username: $username, password: $password, options: [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    $migrator = new \Database\Migrator();
    $migrator->run();
}