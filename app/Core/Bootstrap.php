<?php

namespace App\Cinema\Core;

use App\Cinema\Exceptions\BadMethodCallException;

/**
 * Class Bootstrap
 * @package App\Cinema\Lib
 */
class Bootstrap
{
    protected static $router;

    public static $db;

    public static function getRouter()
    {
        return self::$router;
    }

    /**
     * @param $uri
     * @throws \App\Cinema\Exceptions\BadMethodCallException
     * @throws \App\Cinema\Exceptions\NotFoundTemplateException
     */
    public static function run($uri)
    {
        self::$router = new Router($uri);

        self::$db = Database::getInstance();

        $controller_class =  'App\Cinema\Controllers\\' . ucfirst(self::$router->getController()) . 'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix() . self::$router->getAction());


        $layout = self::$router->getRoute();
        $controller_object = new $controller_class();
        if (method_exists($controller_object, $controller_method)) {
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getPageTitle(), $controller_object->getData(), $view_path);
            $content = $view_object->render();
        } else {
            throw new BadMethodCallException(
                'Method ' . $controller_method . ' of class ' . $controller_class . ' does not exist.'
            );
        }

        $layout_path = VIEWS_PATH . DS . $layout . '.html';
        $layout_view_object = new View($controller_object->getPageTitle(), compact('content'), $layout_path);
        echo $layout_view_object->render();
    }
}
