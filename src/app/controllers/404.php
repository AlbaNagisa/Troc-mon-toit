<?php

namespace Controllers;

use Twig\Environment;

class PageNotFound
{

    public function __construct(private Environment $twig, array $params)
    {
    }

    public function index(): void
    {
        $counter = isset($_SESSION['counter']) ? $_SESSION['counter'] : 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $counter++;
            $_SESSION['counter'] = $counter;
        }

        echo $this->twig->render('404/404.html.twig', ['counter' => $counter]);
    }
}
