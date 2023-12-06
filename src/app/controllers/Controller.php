<?php

namespace Controllers;

use Models\User;
use Twig\Environment;

class Controller
{
    protected User $user;

    public function __construct(protected Environment $twig)
    {
        $this->user = new User();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            if ($_SERVER['REQUEST_URI'] != "/register") {
                if ($_SERVER['REQUEST_URI'] == "/login") {
                    return;
                }
                header("Location: /register");
            }

            if ($_SERVER['REQUEST_URI'] != "/login") {
                if ($_SERVER['REQUEST_URI'] == "/register") {
                    return;
                }
                header("Location: /login");
            }

        }

        if (isset($_SESSION['user'])) {
            $userFromDb = $this->user->getById($_SESSION['user']['id']);
            if (!password_verify($_SESSION['user']['password'], $userFromDb['password'])) {
                $_SESSION['user'] = null;
            } else if (password_verify($_SESSION['user']['password'], $userFromDb['password'])) {
                $userFromDb['password'] = $_SESSION['user']['password'];
                $_SESSION['user'] = $userFromDb;
            }
            if ($_SERVER['REQUEST_URI'] == "/login") {
                header("Location: /");
            }
            if ($_SERVER['REQUEST_URI'] == "/register") {
                header("Location: /");
            }
        }

    }
}
