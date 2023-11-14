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
        echo basename(__DIR__);
    }
}
