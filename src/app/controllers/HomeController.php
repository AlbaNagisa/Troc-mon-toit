<?php

namespace Controllers;

use Models\City;
use Models\Equipments;
use Models\Housing;
use Models\HousingType;
use Models\Like;
use Models\Services;

class HomeController extends Controller
{

    private Housing $housing;
    private Like $like;
    private City $city;
    private HousingType $housingType;
    private Equipments $equipment;
    private Services    $services;

    public function __construct(\Twig\Environment $twig)
    {
        parent::__construct($twig);
        $this->housing = new Housing();
        $this->like = new Like();
        $this->city = new City();
        $this->housingType = new HousingType();
        $this->equipment = new Equipments();
        $this->services = new Services();
    }
    public function index()
    {
        $housing = $this->housing->getAll();

        echo $this->twig->render('home/index.html.twig', ['housings' => $housing, "likes" => $this->like->getByUserId($_SESSION['user']['id']), "cities" => $this->city->getAll(), "types" => $this->housingType->getAll(), "equipments" => $this->equipment->getAll(), 'services' => $this->services->getAll()]);
    }

    public function filter()
    {
        $housing = $this->housing->getByFilter($_POST['price'] ?? null, $_POST['type'] ?? null, $_POST['city'] ?? null, $_POST['equipments'] ?? null, $_POST['services'] ?? null);

        echo $this->twig->render('home/index.html.twig', ['housings' => $housing, "likes" => $this->like->getByUserId($_SESSION['user']['id']), "cities" => $this->city->getAll(), "types" => $this->housingType->getAll(), "equipments" => $this->equipment->getAll(), 'services' => $this->services->getAll()]);
    }
}
