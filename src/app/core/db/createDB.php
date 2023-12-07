<?php

namespace Core;

require __DIR__ . '/../../../vendor/autoload.php';

$pdo = DB::getInstance("mysql", "root", "root", "3306", "database")->getPDO();

$pdo->exec("CREATE TABLE IF NOT EXISTS `image` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `image` LONGBLOB NOT NULL
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS `city` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `name` VARCHAR(255) NOT NULL
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS `type` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `name` VARCHAR(255) NOT NULL
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS `service` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `name` VARCHAR(255) NOT NULL
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS `equipment` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `name` VARCHAR(255) NOT NULL UNIQUE
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS `user` (
`id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
`username` VARCHAR(255) NOT NULL UNIQUE,
`name` VARCHAR(255) NOT NULL,
`surname` VARCHAR(255) NOT NULL,
`password` CHAR(255) NOT NULL,
`email` VARCHAR(255) NOT NULL UNIQUE,
`phone_number` VARCHAR(255) NOT NULL UNIQUE,
`id_image` INT UNSIGNED,
`role` enum('Admin','User') NOT NULL DEFAULT 'User'
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS housing (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `id_type` INT UNSIGNED NOT NULL,
    `id_image` INT UNSIGNED NOT NULL,
    `id_city` INT UNSIGNED NOT NULL,
    `night_price` FLOAT NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    FOREIGN KEY (`id_city`) REFERENCES `city` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`id_type`) REFERENCES `type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`id_image`) REFERENCES `image` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);");



$pdo->exec("CREATE TABLE IF NOT EXISTS `booking` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `id_user` INT UNSIGNED NOT NULL,
    `id_housing` INT UNSIGNED NOT NULL,
    `date_start` DATE NOT NULL,
    `date_end` DATE NOT NULL,
    `total_price` FLOAT NOT NULL,
    FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`id_housing`) REFERENCES `housing` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS `review` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `id_user` INT UNSIGNED NOT NULL,
    `id_housing` INT UNSIGNED NOT NULL,
    `date_start` DATE NOT NULL,
    `date_end` DATE NOT NULL,
    `comment` VARCHAR(255) NOT NULL,
    `stars` INT NOT NULL,
   
    FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`id_housing`) REFERENCES `housing` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS `like` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `id_user` INT UNSIGNED NOT NULL,
    `id_housing` INT UNSIGNED NOT NULL,
    FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`id_housing`) REFERENCES `housing` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS `housing_service` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `id_housing` INT UNSIGNED NOT NULL,
    `id_service` INT UNSIGNED NOT NULL,
    FOREIGN KEY (`id_housing`) REFERENCES `housing` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`id_service`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);");

$pdo->exec("CREATE TABLE IF NOT EXISTS `housing_equipment` (
    `id` INT  UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `id_housing` INT UNSIGNED NOT NULL,
    `id_equipment` INT UNSIGNED NOT NULL,
    FOREIGN KEY (`id_housing`) REFERENCES `housing` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`id_equipment`) REFERENCES `equipment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);");
echo "tables created";
