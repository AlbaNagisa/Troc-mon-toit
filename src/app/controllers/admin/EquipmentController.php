<?php

namespace Controllers\Admin;

use Controllers\AdminController;
use Core\DB;
use Models\Equipments;
use Twig\Environment;

class EquipmentController extends AdminController
{
    private Equipments $equipment;
    private static array $success;
    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
        $this->equipment = new Equipments();
    }

    public function index()
    {
        echo $this->twig->render("admin/equipment.html.twig", ["equipments" => $this->equipment->getAll(), "success" => self::$success ?? null]);
    }

    public function create()
    {
        if (isset($_POST['name'])) {
            $name = $_POST['name'];
            $id = $this->equipment->create($name);
            if (DB::isValid($id)) {
                self::$success = ['true', "L'équipement a bien été créé"];
            } else {
                self::$success = ['false', "Une erreur est survenue"];
            }
        } else {
            self::$success = ['false', "Une erreur est survenue"];
        }
        echo $this->twig->render("admin/equipment.html.twig", ["equipments" => $this->equipment->getAll(), "success" => self::$success ?? null]);

        // return header("Location: /admin/equipements");
    }

    public function delete()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $this->equipment->delete($id);
            self::$success = ['true', "L'équipement a bien été supprimé"];
            header("Location: /admin/equipments");
        } else {
            self::$success = ['false', "Une erreur est survenue"];
            header("Location: /admin/equipments");
        }
    }
    public function modify()
    {
        if (isset($_POST['id']) && isset($_POST['name'])) {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $this->equipment->update($name, $id);
            self::$success = ['true', "L'équipement a bien été modifié"];
            header("Location: /admin/equipments");
        } else {
            self::$success = ['false', "Une erreur est survenue"];
            header("Location: /admin/equipments");
        }
    }
}
