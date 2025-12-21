<?php

namespace Database;

class Database
{

    private static ?self $instance = null;
    private \PDO $pdo;

    /**
     * @throws \Exception
     */
    public static function init(string $dsn, string $username, string $password, array $options = []): self
    {
        self::$instance = new Database($dsn, $username, $password, $options);
        return self::getDBInstance();
    }

    /**
     * @throws \Exception
     */
    public static function getDBInstance(): self
    {
        if (is_null(self::$instance)) {
            throw new \Exception('Database has not been initialised!');
        }
        return self::$instance;
    }

    private function __construct(string $dsn, string $username, string $password, array $options = [])
    {
        try {
            $this->pdo = new \PDO(dsn: $dsn, username: $username, password: $password, options: $options);
        } catch (\PDOException $e) {
            echo "Database connection failed" . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
            die;
        }
    }

    private function __clone()
    {
    }

    public function all(string $sql, array $params = []): array
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function one(string $sql, array $params = [])
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    private function prepare($sql): false|\PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

}