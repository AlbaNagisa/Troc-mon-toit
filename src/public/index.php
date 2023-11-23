<?php

require '../vendor/autoload.php';

//use Core\DB;
use Core\Method as Method;
use Router\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

define('BASE_VIEW_PATH', __DIR__ . '/../app/views/');

$loader = new FilesystemLoader(BASE_VIEW_PATH);
$twig = new Environment($loader, []);

$router = Router::getInstance($twig);
/*
$pdo = DB::getInstance("mysql", "root", "root", "3306", "database")->getPDO();
require '../app/core/db/fillDB.php';

require '../app/core/db/createDB.php';  */

$router->register(Method::GET->value, '/', 'Controllers\\HomeController', 'showPosts');
$router->register(Method::GET->value, '/login', 'Controllers\\UserController', 'login');
$router->register(Method::GET->value, '/register', 'Controllers\\UserController', 'register');

$router->register(Method::POST->value, '/register', 'Controllers\\UserController', 'registerPost');
$router->register(Method::POST->value, '/login', 'Controllers\\UserController', 'loginPost');

$router->dispatch();
