<?php

namespace App\Cinema\Lib;
use App\Cinema\Exceptions\NotFoundTemplateException;

/**
 * Class View
 * @package App\Cinema\Lib
 */
class View
{
    protected $data;

    protected $path;

    protected $siteName;

    protected $pageTitle;

    /**
     * View constructor.
     * @param string $pageTitle
     * @param array $data
     * @param null $path
     * @throws NotFoundTemplateException
     */
    public function __construct(string $pageTitle, $data = [], $path = null)
    {
        if (!$path) {
            $path = self::getDefaultViewPath();
        }
        if (!file_exists($path)) {
            throw new NotFoundTemplateException('Templete file is note found in path:' . $path);
        }
        $this->path = $path;
        $this->data = $data;
        $this->siteName = Config::get('site_name');
        $this->pageTitle = $pageTitle;
    }

    /**
     * @return false|string
     */
    protected static function getDefaultViewPath()
    {
        $router = Bootstrap::getRouter();
        if (!$router) {
            return false;
        }
        $controller_dir = $router->getController();
        $template_name = $router->getMethodPrefix() . $router->getAction() . '.html';

        return VIEWS_PATH . DS . $controller_dir . DS . $template_name;
    }

    /**
     * @return false|string
     */
    public function render()
    {
        $data = $this->data;

        ob_start();
        include($this->path);
        $content = ob_get_clean();

        return $content;
    }
}
