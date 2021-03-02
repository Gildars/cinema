<?php

namespace App\Cinema\Core;

use App\Cinema\Core\Config;

/**
 * Class Router
 * @package App\Cinema\Core
 */
class Router
{
    protected string $uri;

    protected string $controller;

    protected string $action;

    protected array $params;

    protected string $route;

    protected mixed $method_prefix;


    public function getUri(): string
    {
        return $this->uri;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getMethodPrefix(): string
    {
        return $this->method_prefix;
    }

    /**
     * Router constructor.
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->uri = urldecode(trim($uri, '/'));

        $routes = Config::get('routes');
        $this->route = Config::get('default_route');
        $this->method_prefix = $routes[$this->route] ?? '';
        $this->controller = Config::get('default_controller');
        $this->action = Config::get('default_action');
        // pars uri
        $uri_parts = explode('?', $this->uri);
        $path = $uri_parts[0];
        $path_parts = explode('/', $path);

        if (count($path_parts)) {
            if (in_array(strtolower(current($path_parts)), array_keys($routes))) {
                $this->route = strtolower(current($path_parts));
                $this->method_prefix = $routes[$this->route] ?? '';
                array_shift($path_parts);
            }
            if (current($path_parts)) {
                $this->controller = strtolower(current($path_parts));
                array_shift($path_parts);
            }
            if (current($path_parts)) {
                $this->action = strtolower(current($path_parts));
                array_shift($path_parts);
            }
            $this->params = $path_parts;
        }
    }


    /**
     * @param string $location
     */
    public static function redirect(string $location): void
    {
        header("Location:$location");
    }
}
