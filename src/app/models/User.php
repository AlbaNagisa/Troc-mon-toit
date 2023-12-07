<?php

namespace Models;

use PDO;

class User extends Models
{


    public function getAll(): mixed
    {
        $this->table = "user";
        try {
            return $this->pdo->query("SELECT * FROM {$this->table}")->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
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

    public function update(array $data): mixed
    {
        $this->table = "user";
        try {
            if (isset($data['password']) && $data['password'] != "") {
                $password = password_hash($data['password'], PASSWORD_DEFAULT);
            } else {
                $password = ($this->getById($data['id']))['password'];
            }
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET username = :username, password = :password, name = :name, surname = :surname, email = :email, phone_number = :phone_number WHERE id = :id");
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':username', $data['username']);
            $stmt->bindParam(':name', $data['firstname']);
            $stmt->bindParam(':surname', $data['lastname']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone_number', $data['phone_number']);
            $stmt->bindParam(':id', $data['id']);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
    }

    public function delete(int $id): mixed
    {
        $this->table = "user";
        try {
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
