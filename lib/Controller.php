<?php

namespace App\Cinema\Lib;

/**
 * Class Controller
 * @package App\Cinema\Lib
 */
class Controller
{
    protected $data;

    protected $model;

    protected $params;

    protected $pageTitle;

    /**
     * @return array|mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getParams()
    {
        return $this->params;
    }

    /**
     * Controller constructor.
     * @param $pageName
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->params = Bootstrap::getRouter()->getParams();
    }
}
