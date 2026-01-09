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

    private bool $debugFlag = false;

    private static ?App $instance = null;

    public const ENV_PATH = __DIR__ . "/../.env";

    private ?Closure $beforeExecutionActions = null;
    private ?Closure $debugActions = null;

    private function __construct()
    {

    }

    public function __get(string $name)
    {
    }

    public function __set(string $name, mixed $value)
    {
    }

    public static function getInstance(): App
    {
        if (!isset(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }


    public function builder(Closure $builder): App
    {
        $builder($this);
        return $this;
    }

    public function beforeExecute(Closure $actions): App
    {
        $this->beforeExecutionActions = $actions;
        return $this;
    }

    public function debugBeforeExecute(bool $debugFlag, Closure $actions): App
    {
        $this->debugFlag = $debugFlag;
        $this->debugActions = $actions;
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

        if ($this->debugFlag && is_callable($this->debugActions)) {
            $this->debugActions->call($this, $this);
            exit(0);
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

        if (is_callable($serviceValue)) {
            $serviceValue = $serviceValue();
        }

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
