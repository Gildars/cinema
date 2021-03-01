<?php

namespace App\Cinema\Requests;

use voku\helper\AntiXSS;

/**
 * Class Request
 * @package App\Cinema\Requests
 */
abstract class Request
{
    protected array $params;

    protected AntiXSS $antiXSS;

    public function __construct(array $getParams)
    {
        $this->params = $getParams;
        $this->antiXSS = new AntiXSS();
    }
}
