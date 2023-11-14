<?php

namespace Controllers;

use Twig\Environment;

class Page
{

    public function __construct(private Environment $twig, array $params)
    {
    }

    public function index(): void
    {
        echo "je suis index de test";
    }
}
