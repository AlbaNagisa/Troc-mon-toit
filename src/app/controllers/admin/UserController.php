<?php

namespace Controllers\Admin;

use Controllers\AdminController;
use Twig\Environment;

class UserController extends AdminController
{
    public function __construct(Environment $twig)
    {
        parent::__construct($twig);
    }
    public function index()
    {
        echo $this->twig->render("admin/user.html.twig", ["users" => $this->user->getAll()]);
    }

    public function create()
    {
        $this->user->create($_POST);
        header("Location: /admin/user");
    }

    public function delete()
    {
        $this->user->delete($_POST["id"]);
        header("Location: /admin/user");
    }

    public function modify()
    {
        $this->user->update($_POST);
        header("Location: /admin/user");
    }
}
