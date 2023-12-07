<?

namespace Models;

use PDO;

class Review extends Models
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "review";
    }

    public function getAll(): mixed
    {
        try {
            return $this->pdo->query("SELECT * FROM {$this->table}")->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
    }
    public function getByHousing($housingId): mixed
    {
        try {
            return $this->pdo->query("SELECT * FROM {$this->table} WHERE id_housing = $housingId")->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
    }

    public function getByUserId($userId): mixed
    {
        try {
            return $this->pdo->query("SELECT * FROM {$this->table} WHERE id_user = $userId")->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
    }

    public function getByUserIdAndHousingId($userId, $housingId): mixed
    {
        try {
            return $this->pdo->query("SELECT * FROM {$this->table} WHERE id_user = $userId AND id_housing = $housingId")->fetch(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
    }

    public function add($dateStart, $dateEnd,  $housing_Id, $comment, $stars, $userId = null)
    {
        try {
            if ($userId == null) {
                $userId =  $_SESSION['user']['id'];
            }
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_housing, id_user, date_start, date_end, comment, stars) VALUES (:id_housing, :id_user, :date_start, :date_end, :comment, :stars)");
            $stmt->bindParam(':id_housing', $housing_Id);
            $stmt->bindParam(':id_user', $userId);
            $stmt->bindParam(':date_start', $dateStart);
            $stmt->bindParam(':date_end', $dateEnd);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':stars', $stars);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function modify($id, $dateStart, $dateEnd, $comment, $stars)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET date_start = :date_start, date_end = :date_end, comment = :comment, stars = :stars WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':date_start', $dateStart);
            $stmt->bindParam(':date_end', $dateEnd);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':stars', $stars);
            $stmt->execute();
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
}
