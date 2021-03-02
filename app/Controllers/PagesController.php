<?php

namespace App\Cinema\Controllers;

use App\Cinema\Core\Controller;

/**
 * Class PagesController
 * @package App\Cinema\Controller
 */
class PagesController extends Controller
{
    /**
     * Controller constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        parent::__construct($data);
    }

    public function notFound()
    {
        $this->pageTitle = 'Страница не найдена';
    }
}
