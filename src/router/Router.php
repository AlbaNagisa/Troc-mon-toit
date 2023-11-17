<?php

namespace Router;

use Twig\Environment;

class Router
{

    private array $routes = [];
    private static $instance = null;

    private function __construct(private Environment $twig)
    {
    }

    public static function getInstance(Environment $twig): self
    {
        if (self::$instance == null) {
            self::$instance = new self($twig);
        }
        return self::$instance;
    }

    public function register(string $method, string $path, $controller, $action): void
    {
        if (class_exists($controller)) {
            if (method_exists($controller, $action)) {
                if (!isset($this->routes[$path])) {
                    $this->routes[$path][$method] = $controller . "@" . $action;
                }
            }
        }
    }

    public function dispatch()
    {
        $path = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        if (isset($this->routes[$path][$method])) {
            $controller = explode("@", $this->routes[$path][$method])[0];
            $action = explode("@", $this->routes[$path][$method])[1];
            $controller = new $controller();
            $controller->$action();
        } else {
            echo "404";
        }
    }

}
