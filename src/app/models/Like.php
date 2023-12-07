<?

namespace Models;

use PDO;

class Like extends Models
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "like";
    }

    public function getByUserId($userId): mixed
    {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM `{$this->table}` WHERE id_user = :userId");
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function add($userId, $housingId)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO `{$this->table}` (id_user, id_housing) VALUES (:id_user, :id_housing)");
            $stmt->bindParam(':id_user', $userId);
            $stmt->bindParam(':id_housing', $housingId);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
    }

    public function delete($userId, $housingId)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM `{$this->table}` WHERE id_user = :id_user AND id_housing = :id_housing");
            $stmt->bindParam(':id_user', $userId);
            $stmt->bindParam(':id_housing', $housingId);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
