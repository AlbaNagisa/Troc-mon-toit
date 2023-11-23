<?php

namespace Core;

use PDO;
use PDOException;

class DB
{
    private static $instance = null;
    private string $dsn;
    private PDO $pdo;
    private function __construct(private string $host, private string $user, private string $password, private int $port, private string $database)
    {
        $this->dsn = "mysql:host=$host;port=$port;dbname=$database";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($this->dsn, $user, $password, $options);
        } catch (PDOException $e) {
            echo "error";
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }

    public static function getInstance(string $host, string $user, string $password, int $port, string $database): self
    {
        if (self::$instance === null) {
            self::$instance = new self($host,
                $user,
                $password,
                $port,
                $database
            );
        }
        return self::$instance;
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
