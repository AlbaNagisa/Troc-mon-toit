<?php

namespace Controllers\Admin;

use Controllers\AdminController;
use Models\City;
use Models\Equipments;
use Models\Housing;
use Models\HousingType;
use Models\Services;
use Twig\Environment;

class HousingController extends AdminController
{
    private Housing $housing;
    private HousingType $housingType;
    private City $city;
    private Services $services;
    private Equipments $equipments;

    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->housing = new Housing();
        $this->housingType = new HousingType();
        $this->city = new City();
        $this->equipments = new Equipments();
        $this->services = new Services();
    }

    public function index()
    {
        $housing = $this->housing->getAll();

        for ($i = 0; $i < count($housing); $i++) {
            if ($housing[$i]['equipments'] != null) {
                $housing[$i]['equipments'] = explode(',', $housing[$i]['equipments']);
            }
            if ($housing[$i]['services'] != null) {
                $housing[$i]['services'] = explode(',', $housing[$i]['services']);
            }
        }

        echo $this->twig->render("admin/housing.html.twig", ["housings" => $housing, "housingTypes" => $this->housingType->getAll(), "cities" => $this->city->getAll(), "equipments" => $this->equipments->getAll(), 'services' => $this->services->getAll()]);
    }

    public function create()
    {
        $this->housing->create($_POST);
        return header("Location: /admin/housing");
    }

    public function delete()
    {
        $this->housing->delete($_POST['id']);
        return header("Location: /admin/housing");
    }

    public function modify()
    {
        $this->housing->update($_POST['id'], $_POST);
        return header("Location: /admin/housing");
    }
}
