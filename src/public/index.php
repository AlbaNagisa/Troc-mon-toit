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

require '../app/core/db/createDB.php';
 */
$router->register(Method::GET->value, '/', 'Controllers\\HomeController', 'index');
$router->register(Method::POST->value, '/', 'Controllers\\HomeController', 'filter');

$router->register(Method::GET->value, '/login', 'Controllers\\UserController', 'login');
$router->register(Method::GET->value, '/register', 'Controllers\\UserController', 'register');
$router->register(Method::GET->value, '/admin', 'Controllers\\AdminController', 'showAdmin');
$router->register(Method::GET->value, '/admin/equipments', 'Controllers\\Admin\\EquipmentController', 'index');
$router->register(Method::GET->value, '/admin/city', 'Controllers\\Admin\\CityController', 'index');
$router->register(Method::GET->value, '/admin/housingType', 'Controllers\\Admin\\HousingTypeController', 'typeIndex');
$router->register(Method::GET->value, '/admin/service', 'Controllers\\Admin\\ServicesController', 'index');
$router->register(Method::GET->value, '/admin/service', 'Controllers\\Admin\\ServicesController', 'index');
$router->register(Method::GET->value, '/admin/housing', 'Controllers\\Admin\\HousingController', 'index');
$router->register(Method::GET->value, '/admin/user', 'Controllers\\Admin\\UserController', 'index');


$router->register(Method::GET->value, '/housing/:id', 'Controllers\\HousingController', 'index');
$router->register(Method::GET->value, '/profil', 'Controllers\\UserController', 'index');
$router->register(Method::GET->value, '/admin/review', 'Controllers\\Admin\\ReviewController', 'index');

$router->register(Method::POST->value, '/admin/review/delete', 'Controllers\\Admin\\ReviewController', 'delete');
$router->register(Method::POST->value, '/admin/review/modify', 'Controllers\\Admin\\ReviewController', 'modify');
$router->register(Method::POST->value, '/admin/review', 'Controllers\\Admin\\ReviewController', 'add');



$router->register(Method::POST->value, '/housing/booking', 'Controllers\\HousingController', 'booking');


$router->register(Method::POST->value, '/admin/user', 'Controllers\\Admin\\UserController', 'create');
$router->register(Method::POST->value, '/admin/user/delete', 'Controllers\\Admin\\UserController', 'delete');
$router->register(Method::POST->value, '/admin/user/modify', 'Controllers\\Admin\\UserController', 'modify');

$router->register(Method::POST->value, '/admin/equipments', 'Controllers\\Admin\\EquipmentController', 'create');
$router->register(Method::POST->value, '/admin/city', 'Controllers\\Admin\\CityController', 'create');
$router->register(Method::POST->value, '/admin/housingType', 'Controllers\\Admin\HousingTypeController', 'create');
$router->register(Method::POST->value, '/admin/service', 'Controllers\\Admin\\ServicesController', 'create');
$router->register(Method::POST->value, '/admin/housing/create', 'Controllers\\Admin\\HousingController', 'create');
$router->register(Method::POST->value, '/admin/housing', 'Controllers\\Admin\\HousingController', 'search');


$router->register(Method::POST->value, '/register', 'Controllers\\UserController', 'registerPost');
$router->register(Method::POST->value, '/login', 'Controllers\\UserController', 'loginPost');

$router->register(Method::POST->value, '/admin/equipments/delete', 'Controllers\\Admin\\EquipmentController', 'delete');
$router->register(Method::POST->value, '/admin/equipments/modify', 'Controllers\\Admin\\EquipmentController', 'modify');

$router->register(Method::POST->value, '/admin/city/delete', 'Controllers\\Admin\\CityController', 'delete');
$router->register(Method::POST->value, '/admin/city/modify', 'Controllers\\Admin\\CityController', 'modify');

$router->register(Method::POST->value, '/admin/housingType/modify', 'Controllers\\Admin\\HousingTypeController', 'modify');
$router->register(Method::POST->value, '/admin/housingType/delete', 'Controllers\\Admin\\HousingTypeController', 'delete');

$router->register(Method::POST->value, '/admin/service/delete', 'Controllers\\Admin\\ServicesController', 'delete');
$router->register(Method::POST->value, '/admin/service/modify', 'Controllers\\Admin\\ServicesController', 'modify');

$router->register(Method::POST->value, '/admin/housing/delete', 'Controllers\\Admin\\HousingController', 'delete');
$router->register(Method::POST->value, '/admin/housing/modify', 'Controllers\\Admin\\HousingController', 'modify');

$router->register(Method::POST->value, '/addFavorite', 'Controllers\\HousingController', 'addLike');
$router->register(Method::POST->value, '/deleteHousing', 'Controllers\\HousingController', 'deleteLike');



$router->register(Method::POST->value, '/review', 'Controllers\\ReviewController', 'review');






$router->dispatch($_SERVER['REQUEST_URI']);
