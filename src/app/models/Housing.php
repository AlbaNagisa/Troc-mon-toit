<?

namespace Models;

use PDO;

class Housing extends Models
{
    public function __construct()
    {
        parent::__construct();
        $this->table = "housing";
    }

    public function getAll()
    {
        try {
            $stmt = $this->pdo->prepare("SELECT *, type.name as type, city.name as city, GROUP_CONCAT(DISTINCT equipment.name) AS equipments, GROUP_CONCAT(DISTINCT service.name) AS services, housing.name as name, housing.id as id FROM housing LEFT JOIN housing_equipment ON housing.id = housing_equipment.id_housing LEFT JOIN equipment ON housing_equipment.id_equipment = equipment.id LEFT JOIN housing_service ON housing.id = housing_service.id_housing LEFT JOIN service ON housing_service.id_service = service.id INNER JOIN type ON housing.id_type = type.id INNER JOIN city ON housing.id_city = city.id INNER JOIN image ON housing.id_image = image.id GROUP BY housing.id;");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function create(array $data)
    {
        try {
            if (isset($_FILES['uploadFile'])) {
                $file = $_FILES['uploadFile'];
                if ($file['error'] === UPLOAD_ERR_OK) {

                    $stmt = $this->pdo->prepare("INSERT INTO image (image) VALUES (:image)");
                    $base64Image = base64_encode(file_get_contents($file['tmp_name']));
                    $stmt->bindParam(':image', $base64Image);
                    $stmt->execute();
                    $data['image'] = $this->pdo->lastInsertId();
                } else {
                    $stmt = $this->pdo->prepare("INSERT INTO image (image) VALUES (:image)");
                    $base64Image = "";
                    $stmt->bindParam(':image', $base64Image);
                    $stmt->execute();
                    $data['image'] = $this->pdo->lastInsertId();
                }
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO image (image) VALUES (:image)");
                $base64Image = "";
                $stmt->bindParam(':image', $base64Image);
                $stmt->execute();
                $data['image'] = $this->pdo->lastInsertId();
            }
            $stmt = $this->pdo->prepare("INSERT INTO {$this->table} (name, description, night_price, id_type, id_city, id_image) VALUES (:name, :description, :price, :type_id, :city_id, :image_id)");
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':type_id', $data['type']);
            $stmt->bindParam(':city_id', $data['city']);
            $stmt->bindParam(':image_id', $data['image']);
            $stmt->execute();
            $housing_id = $this->pdo->lastInsertId();
            $stmt = $this->pdo->prepare("INSERT INTO housing_equipment (id_housing, id_equipment) VALUES (:id_housing, :id_equipment)");
            $stmt->bindParam(':id_housing', $housing_id);
            if (isset($data['equipments'])) {
                for ($i = 0; $i < count($data['equipments']); $i++) {
                    $stmt->bindParam(':id_equipment', $data['equipments'][$i]);
                    $stmt->execute();
                }
            }
            $stmt = $this->pdo->prepare("INSERT INTO housing_service (id_housing, id_service) VALUES (:id_housing, :id_service)");
            $stmt->bindParam(':id_housing', $housing_id);
            if (isset($data['services'])) {
                for ($i = 0; $i < count($data['services']); $i++) {
                    $stmt->bindParam(':id_service', $data['services'][$i]);
                    $stmt->execute();
                }
            }

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
            return $stmt->rowCount();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
    public function getById($id)
    {
        $stmt = $this->pdo->prepare("SELECT *, type.name as type, city.name as city,
    GROUP_CONCAT(DISTINCT equipment.name) AS equipments,
    GROUP_CONCAT(DISTINCT service.name) AS services,
    housing.name as name, housing.id as id
    FROM housing
    LEFT JOIN housing_equipment ON housing.id = housing_equipment.id_housing
    LEFT JOIN equipment ON housing_equipment.id_equipment = equipment.id
    LEFT JOIN housing_service ON housing.id = housing_service.id_housing
    LEFT JOIN service ON housing_service.id_service = service.id
    INNER JOIN type ON housing.id_type = type.id
    INNER JOIN city ON housing.id_city = city.id
    INNER JOIN image ON housing.id_image = image.id
    WHERE housing.id = :housing_id
    GROUP BY housing.id;");
        $stmt->bindParam(':housing_id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, array $data)
    {
        try {
            $existingHousing = $this->getById($id);
            if (!$existingHousing) {
                return false;
            }

            if (isset($_POST['deleteImage'])) {
                $stmt = $this->pdo->prepare("UPDATE image SET image = :image WHERE id = :image_id");
                $base64Image = '';
                $stmt->bindParam(':image', $base64Image);
                $stmt->bindParam(':image_id', $existingHousing['id_image']);
                $stmt->execute();
            }
            if (isset($_FILES['uploadFile'])) {
                $file = $_FILES['uploadFile'];
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $stmt = $this->pdo->prepare("UPDATE image SET image = :image WHERE id = :image_id");
                    $base64Image = base64_encode(file_get_contents($file['tmp_name']));
                    $stmt->bindParam(':image', $base64Image);
                    $stmt->bindParam(':image_id', $existingHousing['id_image']);
                    $stmt->execute();
                }
            }

            $stmt = $this->pdo->prepare("UPDATE {$this->table} SET
            name = :name,
            description = :description,
            night_price = :price,
            id_type = :type_id,
            id_city = :city_id
            WHERE id = :housing_id");

            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':description', $data['description']);
            $stmt->bindParam(':price', $data['price']);
            $stmt->bindParam(':type_id', $data['type']);
            $stmt->bindParam(':city_id', $data['city']);
            $stmt->bindParam(':housing_id', $id);

            $stmt->execute();

            $this->deleteHousingEquipments($id);
            $this->deleteHousingServices($id);

            $this->addHousingEquipments($id, $data['equipments'] ?? []);

            $this->addHousingServices($id, $data['services'] ?? []);

            return true;
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return false;
        }
    }
    private function addHousingEquipments($housingId, array $equipmentIds)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO housing_equipment (id_housing, id_equipment) VALUES (:housing_id, :equipment_id)");

            foreach ($equipmentIds as $equipmentId) {
                $stmt->bindParam(':housing_id', $housingId);
                $stmt->bindParam(':equipment_id', $equipmentId);
                $stmt->execute();
            }

            return true;
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return false;
        }
    }

    private function addHousingServices($housingId, array $serviceIds)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO housing_service (id_housing, id_service) VALUES (:housing_id, :service_id)");

            foreach ($serviceIds as $serviceId) {
                $stmt->bindParam(':housing_id', $housingId);
                $stmt->bindParam(':service_id', $serviceId);
                $stmt->execute();
            }

            return true;
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return false;
        }
    }
    private function deleteHousingEquipments($housingId)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM housing_equipment WHERE id_housing = :housing_id");
            $stmt->bindParam(':housing_id', $housingId);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function searchByName($name)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT *, type.name as type, city.name as city,
            GROUP_CONCAT(DISTINCT equipment.name) AS equipments,
            GROUP_CONCAT(DISTINCT service.name) AS services,
            housing.name as name, housing.id as id
            FROM housing
            LEFT JOIN housing_equipment ON housing.id = housing_equipment.id_housing
            LEFT JOIN equipment ON housing_equipment.id_equipment = equipment.id
            LEFT JOIN housing_service ON housing.id = housing_service.id_housing
            LEFT JOIN service ON housing_service.id_service = service.id
            INNER JOIN type ON housing.id_type = type.id
            INNER JOIN city ON housing.id_city = city.id
            INNER JOIN image ON housing.id_image = image.id 
            WHERE housing.name LIKE :name
            GROUP BY housing.id;");
            $name = '%' . $name . '%';
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
    }
    private function deleteHousingServices($housingId)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM housing_service WHERE id_housing = :housing_id");
            $stmt->bindParam(':housing_id', $housingId);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function getByFilter($night_price, $type, $city, $equipments, $services)
    {
        try {

            $req = "SELECT *, type.name as type, city.name as city,
            GROUP_CONCAT(DISTINCT equipment.name) AS equipments,
            GROUP_CONCAT(DISTINCT service.name) AS services,
            housing.name as name, housing.id as id
            FROM housing
            LEFT JOIN housing_equipment ON housing.id = housing_equipment.id_housing
            LEFT JOIN equipment ON housing_equipment.id_equipment = equipment.id
            LEFT JOIN housing_service ON housing.id = housing_service.id_housing
            LEFT JOIN service ON housing_service.id_service = service.id
            INNER JOIN type ON housing.id_type = type.id
            INNER JOIN city ON housing.id_city = city.id
            INNER JOIN image ON housing.id_image = image.id 
            WHERE 1 = 1";
            $stmt = $this->pdo->prepare($req);
            $i = 0;
            if ($night_price != null) {
                $req .= ($i > 0 ? " OR" : " AND") . " housing.night_price <= :night_price";
                $i++;
            }
            if ($type != null) {
                $req .= ($i > 0 ? " OR" : " AND") . " housing.id_type = :type";
                $i++;
            }
            if ($city != null) {
                $req .= ($i > 0 ? " OR" : " AND") . " housing.id_city = :city";
                $i++;
            }
            if ($equipments != null) {
                $req .= ($i > 0 ? " OR" : " AND") . " housing.id IN (SELECT id_housing FROM housing_equipment WHERE id_equipment IN (:equipments))";
                $i++;
            }
            if ($services != null) {
                $req .= ($i > 0 ? " OR" : " AND") . " housing.id IN (SELECT id_housing FROM housing_service WHERE id_service IN (:services))";
                $i++;
            }
            $req .= " GROUP BY housing.id;";
            $stmt = $this->pdo->prepare($req);
            if ($night_price != null) {
                $stmt->bindParam(':night_price', $night_price);
            }
            if ($type != null) {
                $stmt->bindParam(':type', $type);
            }
            if ($city != null) {
                $stmt->bindParam(':city', $city);
            }
            if ($equipments != null) {
                $res = '';
                for ($i = 0; $i < count($equipments); $i++) {
                    $res .=  "$equipments[$i]";
                    if ($i != count($equipments) - 1) {
                        $res .= ', ';
                    }
                }
                $stmt->bindParam(':equipments', $res);
            }
            if ($services != null) {
                $res = '';
                for ($i = 0; $i < count($services); $i++) {
                    $res .=  "$services[$i]";
                    if ($i != count($services) - 1) {
                        $res .= ', ';
                    }
                }
                $stmt->bindParam(':services', $res);
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            echo $e->getMessage();
            return $e->getMessage();
        }
    }
}
