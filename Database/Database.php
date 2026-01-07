<?php

namespace Database;

class Database
{

    private \PDO $pdo;

    /**
     * @throws \Exception
     */
    public static function init(string $dsn, string $username, string $password, array $options = []): self
    {
        return new Database($dsn, $username, $password, $options);
    }

    public function __construct(string $dsn, string $username, string $password, array $options = [])
    {
        try {
            $this->pdo = new \PDO(dsn: $dsn, username: $username, password: $password, options: $options);
        } catch (\PDOException $e) {
            echo "Database connection failed" . PHP_EOL;
            echo $e->getMessage() . PHP_EOL;
            die;
        }
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

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollback(): void
    {
        $this->pdo->rollBack();
    }

    public function getPDO(): \PDO
    {
        return $this->pdo;
    }

    private function prepare($sql): false|\PDOStatement
    {
        return $this->pdo->prepare($sql);
    }


}