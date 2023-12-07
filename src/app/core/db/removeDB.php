<?php

namespace Core;

require __DIR__ . '/../../../vendor/autoload.php';

$pdo = DB::getInstance("mysql", "root", "root", "3306", "database")->getPDO();

$pdo->exec("set foreign_key_checks=0;");
$pdo->exec("drop table if exists `image`;");
$pdo->exec("drop table if exists `city`;");
$pdo->exec("drop table if exists `type`;");
$pdo->exec("drop table if exists `service`;");
$pdo->exec("drop table if exists `equipment`;");
$pdo->exec("drop table if exists `user`;");
$pdo->exec("drop table if exists `housing`;");
$pdo->exec("drop table if exists `booking`;");
$pdo->exec("drop table if exists `review`;");
$pdo->exec("drop table if exists `like`;");
$pdo->exec("drop table if exists `housing_service`;");
$pdo->exec("drop table if exists `housing_equipment`;");
$pdo->exec("set foreign_key_checks=1;");
