<?php

namespace Controllers;

use Models\User;
use Twig\Environment;

class Controller
{
    public function __construct(protected Environment $twig)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) && $_SERVER['REQUEST_URI'] != "/login") {
            header("Location: /login");
        }

        if (isset($_SESSION['user']) && $_SERVER['REQUEST_URI'] == "/login") {
            header("Location: /");
        }

        if (isset($_SESSION['user']) && $_SERVER['REQUEST_URI'] == "/register") {
            header("Location: /");
        }

        if (isset($_SESSION['user']) && !password_verify($_SESSION['user']['password'], ((new User())->getById($_SESSION['user']['id']))['password'])) {
            $_SESSION['user'] = null;
        }
    }
}
