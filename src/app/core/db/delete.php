<?php

require 'connDB.php';
$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("drop table user");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

echo "table users deleted";
