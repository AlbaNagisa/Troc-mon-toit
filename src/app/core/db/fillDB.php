<?php

require_once dirname(__dir__) . '../../../vendor/autoload.php';

$faker = Faker\Factory::create('fr_FR');

require 'connDB.php';

$hashPassword = null;
$users = [];
$housing = [];
$ypes = [];
$services = [];
$equipements = [];

for ($i = 0; $i < 10; $i++) {
    $hashPassword = password_hash($faker->password(), PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO `user` (`username`, `name`, `surname`, `password`, `email`, `phone_number`, `role`) VALUES ('{$faker->userName}', '{$faker->firstName}', '{$faker->lastName}', 'ratio', '{$faker->email}', '{$faker->phoneNumber}', 'Admin');");
    $users = $pdo->lastInsertId();
}
