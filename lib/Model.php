<?php

namespace App\Cinema\Lib;

use PDO;

/**
 * Class Model
 * @package App\Cinema\Lib
 */
class Model
{
    protected ?PDO $db;

    public function __construct()
    {
        $this->db = Bootstrap::$db;
    }

    /**
     * @param $value
     * @param $min
     * @param $max
     * @return bool
     */
    public function checkLength($value, $min, $max): bool
    {
        $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
        return !$result;
    }
}
