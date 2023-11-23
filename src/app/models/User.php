<?php
namespace Models;

use PDO;

class User extends Models
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getByUsername(string $username): mixed
    {
        $this->table = "user";
        try {
            return $this->pdo->query("SELECT * FROM {$this->table} WHERE username  = '$username'")->fetch(PDO::FETCH_ASSOC);

        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function getById(int $id): mixed
    {
        $this->table = "user";
        try {
            return $this->pdo->query("SELECT * FROM {$this->table} WHERE id  = '$id'")->fetch(PDO::FETCH_ASSOC);

        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function create(array $data): mixed
    {
        $this->table = "user";
        try {
            $password = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (username, name, surname, password, email, phone_number) VALUES (:username, :name, :surname, :password, :email, :phone_number)");
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':name', $data['firstname']);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':surname', $data['lastname']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone_number', $data['phone_number']);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
