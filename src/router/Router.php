<?php

namespace Router;

use Twig\Environment;

class Router
{

    public $url;
    private array $routes;
    private string $params;
    public function __construct(private Environment $twig)
    {
    }

    function dirToArray($dir)
    {
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                    $result[$value] = $this->dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                } else {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

    public function init(): void
    {
        $route = $this->dirToArray(__DIR__ . "/../app/controllers/");
        $this->url = explode("?", $_SERVER['REQUEST_URI'])[0];
        // $this->params = explode("?", $_SERVER['REQUEST_URI'])[1];

        $cutUrl = array_slice(explode("/", strtolower($this->url)), 1);

        if ($this->checkURL($cutUrl, $route)) {
            $this->render($cutUrl);
        } else {
            $this->render(["404"]);
        }
    }

    public function render(array $url): void
    {
        $lastElem = $url[count($url) - 1];
        $url = implode("/", $url);
        $url = $url ? $url . ".php" : "index.php";
        require __DIR__ . "/../app/controllers/" . $url;
        if ($lastElem == "404") {
            $lastElem = "PageNotFound";
        }
        if (class_exists("Controllers\\" . ucfirst($lastElem))) {
            $class = "Controllers\\" . ucfirst($lastElem);
            $class = new $class($this->twig);
            $class->index();
        } else {
            $this->render(["404"]);
        }
    }
    private function checkURL(array $url, mixed $route): bool
    {
        $res = false;
        $value = $url[0];
        if (array_key_exists($value, $route)) {
            $route = $route[$value];
            return $this->checkURL(array_slice($url, 1), $route);
        } else {
            if (in_array(str_contains(".php", $value) ? $value : $value . ".php", $route)) {
                if (count($url) <= 1) {
                    $res = true;
                }
            }
        }
        return $res;
    }
}
