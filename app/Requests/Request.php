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

    public function __construct(array $params)
    {
        $this->params = $params;
        $this->trimAll($this->params);
        $this->antiXSS = new AntiXSS();
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param $array
     */
    protected function trimAll(&$array): void
    {
        foreach ($array as &$value) {
            if (!is_array($value)) {
                $value = trim($value);
            } else {
                $this->trimAll($value);
            }
        }
    }
}
