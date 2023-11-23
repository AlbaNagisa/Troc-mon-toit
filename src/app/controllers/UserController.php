<?php

namespace Controllers;

use Models\User;

class UserController extends Controller
{

    public function login()
    {
        echo $this->twig->render('user/login.html.twig');
    }
    public function loginPost()
    {
        $user = (new User())->getByUsername($_POST['username']);

        if (!$user) {
            return header("Location: /login");
        }

        if (password_verify($_POST['password'], $user['password'])) {
            $user['password'] = $_POST['password'];
            $_SESSION['user'] = $user;
            return header("Location: /");
        }

        return header("Location: /login");
    }

    public function register()
    {
        echo $this->twig->render('user/register.html.twig');
    }

    public function registerPost()
    {
        $userId = (new User())->create($_POST);

        if (!is_int(intval($userId))) {
            echo $this->twig->render('user/register.html.twig', ["error" => $userId]);
            return;
        } else {
            $user = (new User())->getById(intval($userId));
            if (!$user) {
                echo $this->twig->render('user/register.html.twig', ["error" => "Le nom d'utilisateur/email ou numero de telephone est deja utilise."]);
                return;
            }
            $user['password'] = $_POST['password'];
            $_SESSION['user'] = $user;
            return header("Location: /");

        }

    }
}
