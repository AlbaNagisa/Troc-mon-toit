<?php

namespace Router;

use Twig\Environment;

class Router
{

    public $url;
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
        if (substr($this->url, -1) != "/") {
            $this->url = $this->url . "/";
        }
        // $this->params = explode("?", $_SERVER['REQUEST_URI'])[1];

        $cutUrl = array_slice(explode("/", strtolower($this->url)), 1);
        $checkedURL = $this->checkURL($cutUrl, $route);
        if ($checkedURL[0]) {
            if (count($checkedURL[1]) != 0) {
                foreach ($checkedURL[1] as $key => $value) {
                    $cutUrl[$value[1]] = ":" . $key;
                    $checkedURL[1][$key] = $value[0];
                }
            }
            $this->render($cutUrl, $checkedURL[1] ?? []);

        } else {
            $this->render(["404"], $checkedURL[1] ?? []);
        }
    }

    public function render(array $url, array $params): void
    {

        $lastElem = $url[count($url) - 1];
        if ($url[count($url) - 1] == "") {
            $url[count($url) - 1] = "page";
        }

        $url = implode("/", $url);
        $url = str_contains($url, ".php") ? $url : $url . ".php";
        require __DIR__ . "/../app/controllers/" . $url;
        if ($lastElem == "404") {
            $lastElem = "PageNotFound";
        } else if ($lastElem == "") {
            $lastElem = "page";
        }

        if (class_exists("Controllers\\" . ucfirst($lastElem))) {
            $class = "Controllers\\" . ucfirst($lastElem);
            $class = new $class($this->twig, $params);
            $class->index();

        } else {
            $this->render(["404"], $params);
        }
    }
    private function checkURL(array $url, mixed $route, $indexInUrl = 0, $params = []): array
    {
        $res = [false, $params];
        $value = $url[0];
        if (array_key_exists($value, $route)) {
            $route = $route[$value];
            return $this->checkURL(array_slice($url, 1), $route, $indexInUrl + 1, $params);
        }

        if ($value == "") {
            $value = "page";
        }
        if (in_array($value . ".php", $route)) {
            if (count($url) <= 1) {
                $res[0] = true;
            }
            return $res;
        }
        $arrayKey = array_keys($route);

        for ($i = 0; $i < count($arrayKey); $i++) {
            if (substr($arrayKey[$i], 0, 1) != ":") {
                continue;
            }
            $params[substr($arrayKey[$i], 1)] = [$value, $indexInUrl];
            $route = $route[$arrayKey[$i]];
            return $this->checkURL(array_slice($url, 1), $route, $indexInUrl + 1, $params);
        }

        return $res;

    }
}
