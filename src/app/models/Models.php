<?php
namespace Models;

use Core\DB;
use PDO;

class Models
{
    protected PDO $pdo;
    protected string $table;
    public function __construct()
    {
        $this->pdo = DB::getInstance("mysql", "root", "root", "3306", "database")->getPDO();
    }
}
