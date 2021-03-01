<?php

namespace App\Cinema\Core;

/**
 * Class Controller
 * @package App\Cinema\Lib
 */
class Controller
{
    protected array $data;

    protected $model;

    protected $params;

    protected string $pageTitle;

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getParams(): mixed
    {
        return $this->params;
    }

    /**
     * Controller constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
        $this->params = Bootstrap::getRouter()->getParams();
    }
}
