<?php

namespace Core;

use Faker\Factory;

require_once dirname(__dir__) . '../../../vendor/autoload.php';

$faker = Factory::create('fr_FR');

$city = [
    "New York",
    "Paris",
    "London",
    "Tokyo",
    "Los Angeles",
    "Chicago",
    "Moscow",
    "Shanghai",
    "Madrid",
    "Toronto"
];

$type = [
    "Appartements",
    "Maisons",
    "Chalets",
    "Villas",
    "Péniches",
    "Yourtes",
    "Cabanes",
    "Igloos",
    "Tentes",
    "Cars",
];

$equipment = [
    "Connexion Wi-Fi",
    "Climatiseur",
    "Chauffage",
    "Machine à laver",
    "Sèche-linge",
    "Télévision",
    "Fer à repasser / Planche à repasser",
    "Nintendo Switch",
    "PS5",
    "Terrasse",
    "Balcon",
    "Piscine",
    "Jardin",
];

$service = [
    "Transferts aéroport",
    "Petit-déjeuner",
    "Service de ménage",
    "Location de voiture",
    "Visites guidées",
    "Cours de cuisine",
    "Loisirs",
];


$pdo = DB::getInstance("mysql", "root", "root", "3306", "database")->getPDO();
// Insert data into the `city` table
foreach ($city as $cityName) {
    $pdo->exec("INSERT INTO `city` (`name`) VALUES ('$cityName');");
}
foreach ($type as $typeName) {
    $pdo->exec("INSERT INTO `type` (`name`) VALUES ('$typeName');");
}
foreach ($service as $serviceName) {
    $pdo->exec("INSERT INTO `service` (`name`) VALUES ('$serviceName');");
}
foreach ($equipment as $equipmentName) {
    $pdo->exec("INSERT INTO `equipment` (`name`) VALUES ('$equipmentName');");
}
$pdo->exec("INSERT INTO `image` (`image`) VALUES ('" . base64_encode('') . "');");
$id_image = $pdo->lastInsertId();
for ($i = 0; $i < 10; $i++) {
    $username = $faker->userName;
    $name = $faker->firstName;
    $surname = $faker->lastName;
    $password = password_hash($faker->password, PASSWORD_DEFAULT);
    $email = $faker->email;
    $phone_number = $faker->phoneNumber;
    $pdo->exec("INSERT INTO `user` (`username`, `name`, `surname`, `password`, `email`, `phone_number`)
                VALUES ('$username', '$name', '$surname', '$password', '$email', '$phone_number');");
}

for ($i = 0; $i < 10; $i++) {
    $name = $faker->word;
    $id_type = $faker->numberBetween(1, count($type)); // Assuming 3 types in the `type` table
    $id_city = $faker->numberBetween(1, count($city)); // Assuming 3 cities in the `city` table

    $night_price = $faker->randomFloat(2, 50, 200);
    $description = $faker->sentence;

    $pdo->exec("INSERT INTO `housing` (`name`, `id_type`, `id_image`, `id_city`, `night_price`, `description`)
                VALUES ('$name', '$id_type', '$id_image', '$id_city', '$night_price', '$description');");
}

// Insert data into the `booking` table with Faker
for ($i = 0; $i < 10; $i++) {
    $id_user = $faker->numberBetween(1, 10);
    $id_housing = $faker->numberBetween(1, 10);
    $date_start = $faker->date;
    $date_end = $faker->date;
    $total_price = $faker->randomFloat(2, 100, 500);

    $pdo->exec("INSERT INTO `booking` (`id_user`, `id_housing`, `date_start`, `date_end`, `total_price`)
                VALUES ('$id_user', '$id_housing', '$date_start', '$date_end', '$total_price');");
}

// Insert data into the `review` table with Faker
for ($i = 0; $i < 10; $i++) {
    $id_user = $faker->numberBetween(1, 10);
    $id_housing = $faker->numberBetween(1, 10);
    $date_start = $faker->date;
    $date_end = $faker->date;
    $comment = $faker->sentence;
    $stars = $faker->numberBetween(1, 5);

    $pdo->exec("INSERT INTO `review` (`id_user`, `id_housing`, `date_start`, `date_end`, `comment`, `stars`)
                VALUES ('$id_user', '$id_housing', '$date_start', '$date_end', '$comment', '$stars');");
}

// Insert data into the `like` table with Faker
for ($i = 0; $i < 10; $i++) {
    $id_user = $faker->numberBetween(1, 10);
    $id_housing = $faker->numberBetween(1, 10);

    $pdo->exec("INSERT INTO `like` (`id_user`, `id_housing`)
                VALUES ('$id_user', '$id_housing');");
}

// Insert data into the `housing_service` table with Faker
for ($i = 0; $i < 10; $i++) {
    $id_housing = $faker->numberBetween(1, 10);
    $id_service = $faker->numberBetween(1, count($service)); // Assuming 5 services in the `service` table

    $pdo->exec("INSERT INTO `housing_service` (`id_housing`, `id_service`)
                VALUES ('$id_housing', '$id_service');");
}

// Insert data into the `housing_equipment` table with Faker
for ($i = 0; $i < 10; $i++) {
    $id_housing = $faker->numberBetween(1, 10);
    $id_equipment = $faker->numberBetween(1, count($equipment)); // Assuming 5 equipments in the `equipment` table

    $pdo->exec("INSERT INTO `housing_equipment` (`id_housing`, `id_equipment`)
                VALUES ('$id_housing', '$id_equipment');");
}
$password = password_hash("admin", PASSWORD_DEFAULT);
$pdo->exec("INSERT INTO `user` (`username`, `name`, `surname`, `password`, `email`, `phone_number`, `role`)
                VALUES ('Admin', 'Admin', 'Admin', '$password', 'admin@admin.fr', '0658338134', 'Admin');");
echo "Tout est bon !";
echo "l'administrateur est : Admin , password : admin";
