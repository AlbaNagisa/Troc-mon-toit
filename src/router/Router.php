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
                $this->routes[$method][$path] = $controller . "@" . $action;
            }
        }
    }
    public function dispatch(string $path)
    {

        $method = $_SERVER['REQUEST_METHOD'];
        $path = explode("?", $path)[0];
        $registeredPath = $this->routes[$method];
        $checkedURL = self::checkURL(explode("/", $path), $registeredPath, $method);
        if ($checkedURL[0]) {
            if ($checkedURL[2] != []) {
                $key = array_keys($checkedURL[2]);
                $path = explode("/", $path);
                $path[count($path) - 1] = ":" . $key[0];
                $path = implode("/", $path);
            }
            $controller = explode("@", $this->routes[$method][$path])[0];
            $action = explode("@", $this->routes[$method][$path])[1];
            $controller = new $controller($this->twig, $checkedURL[2]);
            $controller->$action();
        } else {
            echo $this->twig->render("404.html.twig");
        }
    }

    private function checkURL(array $url, array | string $registeredPath, string $method, $indexInUrl = 0, $params = []): array
    {
        $value = $url[0];
        $res = [false, "", $params];

        if (isset($this->routes[$method][implode("/", $url)])) {
            $res[0] = true;
            $res[1] = $this->routes[$method][implode("/", $url)];
            return $res;
        }

        if (gettype($registeredPath) == "string") {
            $res[0] = true;
            $res[1] = $registeredPath;
            return $res;
        }

        $arrayKey = array_keys($registeredPath);

        for ($i = 0; $i < count($arrayKey); $i++) {
            $pathOfArrayKey = explode("/", $arrayKey[$i]);
            for ($j = 0; $j < count($pathOfArrayKey); $j++) {
                if ($url[$j] != $pathOfArrayKey[$j] && substr($pathOfArrayKey[$j], 0, 1) != ":") {
                    break;
                }
                if (substr($pathOfArrayKey[$j], 0, 1) != ":") {
                    continue;
                }
                $params[substr($pathOfArrayKey[$j], 1)] = $url[count($url) - 1];
                $route = $registeredPath[$arrayKey[$i]];
                return $this->checkURL(array_slice($url, 1), $route, $method, $indexInUrl + 1, $params);
            }
        }
        return $res;
    }

}
