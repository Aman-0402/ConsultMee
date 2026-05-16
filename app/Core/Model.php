<?php
declare(strict_types=1);

namespace ConsultMee\Core;

abstract class Model
{
    private static ?\PDO $pdo = null;

    protected function db(): \PDO
    {
        if (self::$pdo === null) {
            $cfg = require APP_ROOT . '/config/database.php';
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $cfg['host'],
                $cfg['database'],
                $cfg['charset']
            );
            self::$pdo = new \PDO($dsn, $cfg['username'], $cfg['password'], $cfg['options']);
        }
        return self::$pdo;
    }

    protected function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $row = $this->query($sql, $params)->fetch();
        return $row ?: null;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    protected function execute(string $sql, array $params = []): int
    {
        return $this->query($sql, $params)->rowCount();
    }

    protected function lastInsertId(): string
    {
        return $this->db()->lastInsertId();
    }
}
