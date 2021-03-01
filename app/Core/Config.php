<?php

namespace App\Cinema\Core;

/**
 * Class Config
 * @package App\Cinema\Core
 */
class Config
{
    protected static array $settings = [];

    /**
     * @param $key
     * @return mixed
     */
    public static function get($key): mixed
    {
        return self::$settings[$key] ?? null;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        self::$settings[$key] = $value;
    }
}
