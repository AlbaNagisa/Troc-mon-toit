<?php
$host = "mysql";
$user = "root";
$password = "root";
$port = 3306;
$database = "database";

$dsn = "mysql:host=$host;port=$port;dbname=$database";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $password, $options);
    echo "database connexion etablished - ";

} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int) $e->getCode());
}
