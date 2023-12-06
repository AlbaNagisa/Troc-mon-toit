<?php

namespace Controllers\Admin;

use Controllers\AdminController;
use Models\City;
use Twig\Environment;

class CityController extends AdminController
{
    private City $city;
    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->city = new City();
    }
    public function index()
    {
        echo $this->twig->render("admin/city.html.twig", ["city" => $this->city->getAll()]);
    }

    public function create()
    {
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            $id = $this->city->create($name);
        }
        echo $this->twig->render("admin/city.html.twig", ["city" => $this->city->getAll()]);

    }

    public function delete()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->city->delete($id);
            header("Location: /admin/city");
        } else {
            header("Location: /admin/city");
        }
    }
    public function modify()
    {
        if (isset($_POST['id']) && isset($_POST['name'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $this->city->update($name, $id);
            header("Location: /admin/city");
        } else {
            header("Location: /admin/city");
        }
    }
}
