<?php

namespace Controllers;

use Twig\Environment;

class Page
{

    public function __construct(private Environment $twig, private array $params)
    {
    }

    public function index(): void
    {
        echo "je suis la page dynamique " . $this->params['id'];
    }
}
