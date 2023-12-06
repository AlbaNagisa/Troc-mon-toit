<?php

namespace Controllers;

class HomeController extends Controller
{

    public function showPosts()
    {
        echo $this->twig->render('home/index.html.twig', ["user" => $_SESSION["user"]]);
    }
}
