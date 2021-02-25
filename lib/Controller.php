<?php

namespace App\Cinema\Lib;

class Controller
{
    protected $data;

    protected $model;

    protected $params;

    protected $siteName;

    protected $pageName;

    public function getData()
    {
        return $this->data;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function __construct($pageName, $data = array())
    {
        $this->siteName = Config::get('site_name');
        $this->pageName = $pageName;
        $this->data = $data;
        $this->params = Bootstrap::getRouter()->getParams();
    }
}
