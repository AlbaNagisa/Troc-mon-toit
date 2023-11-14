<?php

require '../vendor/autoload.php';

use Router\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

define('BASE_VIEW_PATH', __DIR__ . '/../app/views/templates');

$loader = new FilesystemLoader(BASE_VIEW_PATH);
$twig = new Environment($loader, []);

$router = new Router($twig);
$router->init();
