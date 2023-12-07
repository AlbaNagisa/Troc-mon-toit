<?

namespace Models;

use PDO;

class Booking extends Models
{

    public function __construct()
    {
        parent::__construct();
        $this->table = "booking";
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

    public function booking($dateStart, $dateEnd, float $total, $housing_Id)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (id_housing, id_user, date_start, date_end, total_price) VALUES (:id_housing, :id_user, :date_start, :date_end, :total_price)");
            $stmt->bindParam(':id_housing', $housing_Id);
            $stmt->bindParam(':id_user', $_SESSION['user']['id']);
            $stmt->bindParam(':date_start', $dateStart);
            $stmt->bindParam(':date_end', $dateEnd);
            $stmt->bindParam(':total_price', $total);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }
}
