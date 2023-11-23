<?php

require_once dirname(__dir__) . '../../../vendor/autoload.php';

$faker = Faker\Factory::create('fr_FR');

$hashPassword = null;
$users = [];
$housing = [];
$ypes = [];
$services = [];
$equipements = [];

for ($i = 0; $i < 10; $i++) {
    $hashPassword = password_hash("ratio", PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO `user` (`username`, `name`, `surname`, `password`, `email`, `phone_number`, `role`) VALUES ('{$faker->userName}', '{$faker->firstName}', '{$faker->lastName}', '{$hashPassword}', '{$faker->email}', '{$faker->phoneNumber}', 'Admin');");
    $users = $pdo->lastInsertId();
}
