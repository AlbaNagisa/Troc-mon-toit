<?php

namespace Controllers;

use Models\Housing;
use Models\Like;

class HomeController extends Controller
{

    private Housing $housing;
    private Like $like;

    public function __construct(\Twig\Environment $twig)
    {
        parent::__construct($twig);
        $this->housing = new Housing();
        $this->like = new Like();
    }
    public function index()
    {
        echo $this->twig->render('home/index.html.twig', ['housings' => $this->housing->getAll(), "likes" => $this->like->getByUserId($_SESSION['user']['id'])]);
    }
}
