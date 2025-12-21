<?php

namespace Database;

class DatabaseConnection
{
    private \PDO $pdo;
    public function __construct(array $config)
    {
        $this->pdo = new \PDO(
            dsn: $config['dsn'],
            username: $config['username'],
            password: $config['password'],
            options: $config['options'] ?? []
        );
    }

    public function pdo(): \PDO
    {
        return $this->pdo;
    }
}