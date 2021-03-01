<?php

namespace App\Cinema\Requests;

/**
 * Class Request
 * @package App\Cinema\Requests
 */
abstract class Request
{
    protected array $params;

    public function __construct(array $getParams)
    {
        $this->params = $getParams;
    }
}