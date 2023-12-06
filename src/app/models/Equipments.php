<?

namespace Models;

use PDO;

class Equipments extends Models {

        public function getAll(): mixed
        {
            $this->table = "equipment";
            try {
                return $this->pdo->query("SELECT * FROM {$this->table} WHERE name <> ''")->fetchAll(PDO::FETCH_ASSOC);

            } catch (\Throwable $e) {
                echo $e->getMessage();
                return $e->getMessage();
            }
        }

        public function getById(int $id): mixed
        {
            $this->table = "equipment";
            try {
                return $this->pdo->query("SELECT * FROM {$this->table} WHERE id  = '$id'")->fetch(PDO::FETCH_ASSOC);

            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }
        public function create(string $name): mixed {
            $this->table = "equipment";
            try {
                $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (name) VALUES (:name)");
                $stmt->bindParam(':name', $name);
                $stmt->execute();
                return $this->pdo->lastInsertId();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function update(string $name, string $id): mixed {
            $this->table = "equipment";
            try {
                $stmt = $this->pdo->prepare("UPDATE {$this->table} SET name = :name WHERE id = :id");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                return $this->pdo->lastInsertId();
            } catch (\Throwable $e) {
                return $e->getMessage();
            }
        }

        public function delete(string $id): mixed {
            $this->table = "equipment";
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
