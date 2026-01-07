<?php

namespace Core;

use Closure;
use Core\Env;
use Database\Database;
use mysql_xdevapi\Exception;
use PDO;

class App implements Executable
{

    private array $configs = [];
    private array $services = [];
    private array $misc = [];

    private static ?App $instance = null;

    private const ENV_PATH = __DIR__ . "/../.env";

    private ?Closure $beforeExecutionActions = null;

    private function __construct()
    {
        $this->loadEnvFile();
    }

    public function __get(string $name){}
    public function __set(string $name, mixed $value){}

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
        $app->set('service.env', $app->loadEnvFile());
        $app->set('service.db', $app->loadDatabaseInstance());

        return $app;
    }

    public function builder(\Closure $builder): App
    {
        $builder($this);
        return $this;
    }

    public function beforeExecute(\Closure $actions): App
    {
        $this->beforeExecutionActions = $actions;
        return $this;
    }

    #[\Override]
    public function execute(): void
    {
        if (isset($this->beforeExecutionActions) && is_callable($this->beforeExecutionActions)) {
            $this->beforeExecutionActions->call($this, $this);
        }

        if (!isset($this->services['router'])) {
            echo "No router found. Exiting...";
            exit(1);
        }

        $this->services['router']->dispatch($this->services['url']);
    }

    public function get(string $serviceKey): mixed
    {
        if (str_contains($serviceKey, '.')) {
            list($identifier, $key) = explode('.', $serviceKey);

            return match ($identifier) {
                'service' => $this->services[$key],
                'config' => $this->configs[$key],
                default => $this->misc[$key],
            };
        } else {
            return $this->misc[$serviceKey];
        }
    }

    public function set(string $serviceKey, mixed $serviceValue)
    {
        if (str_contains($serviceKey, '.')) {
            list($identifier, $key) = explode('.', $serviceKey);

            switch ($identifier) {
                case 'service':
                    $this->throwIfKeyExists($key, $this->services);
                    $this->services[$key] = $serviceValue;
                    break;
                case 'config':
                    $this->throwIfKeyExists($key, $this->configs);
                    $this->configs[$key] = $serviceValue;
                    break;
                default:
                    $this->throwIfKeyExists($key, $this->misc);
                    $this->misc[$key] = $serviceValue;
                    break;
            }

        } else {
            $this->throwIfKeyExists($serviceKey, $this->misc);
            $this->misc[$serviceKey] = $serviceValue;
        }
    }

    private function throwIfKeyExists(string $key, array &$array): bool
    {
        if (array_key_exists($key, $array)) {
            throw new Exception("{$key} has already been set.");
        }

        return false;

    }


}
