<?php

namespace Core;

use Core\Env;
use Database\Database;
use PDO;

class App implements Executable
{

    private array $configs = [];
    private array $services = [];

    private static ?App $instance = null;

    private const ENV_PATH = __DIR__ . "/../.env";

    private function __construct()
    {
        $this->loadEnvFile();
    }

    public static function getInstance(): App
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function loadEnvFile(): Env
    {
        $env = new Env(self::ENV_PATH);
        $env->loadFromFile();
        return $env;
    }

    public function __get(string $name): mixed
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }
        return null;
    }

    public function __set(string $name, mixed $value)
    {
        if (isset($this->services[$name])) {
            throw new \Exception(sprintf("Duplicate key for %s", $name));
        }

        $this->services[$name] = $value;
    }

    public function loadDatabaseInstance(): Database
    {
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
    }

    /**
     * Creates an app instance with env variables loaded and with database
     * @return App
     */
    public static function loadMinimalApp(): App
    {
        require_once __DIR__ . '/Env.php';
        require_once __DIR__ . '/../Database/Database.php';

        $app = new self;
        $app->env = $app->loadEnvFile();
        $app->db = $app->loadDatabaseInstance();

        return $app;
    }

    public function builder(\Closure $builder): App
    {
        $builder($this->services, $this);
        return $this;
    }

    #[\Override]
    public function execute(): App
    {
//        if(!isset($this->services['router'])){
//            echo "No router found. Exiting...";
//            exit(1);
//        }

        return $this;
    }

    public function get(string $serviceKey): mixed
    {
        return $this->services[$serviceKey];
    }

    public function set(string $serviceKey, mixed $serviceValue): mixed
    {
        $this->services[$serviceKey] = $serviceValue;
        return $this->services[$serviceKey];
    }

}
