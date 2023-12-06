<?php

namespace Controllers;

class UserController extends Controller
{

    public function login()
    {
        echo $this->twig->render('user/login.html.twig');
    }
    public function loginPost()
    {
        $user = $this->user->getByUsername($_POST['username']);

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
        $userId = $this->user->create($_POST);

        if (!is_int(intval($userId))) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $referringPage = $_SERVER['HTTP_REFERER'];
                if (str_contains($referringPage, "admin")) {
                    return header("Location: /admin?userSuccess=false");
                }
            }
            echo $this->twig->render('user/register.html.twig', ["error" => $userId]);
            return;
        } else {
            $user = $this->user->getById(intval($userId));
            if (isset($_SERVER['HTTP_REFERER'])) {
                $referringPage = $_SERVER['HTTP_REFERER'];
                if (str_contains($referringPage, "admin") && $user) {
                    return header("Location: /admin?userSuccess=true");
                } else if (str_contains($referringPage, "admin") && !$user) {
                    return header("Location: /admin?userSuccess=false");

                }
            }
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
