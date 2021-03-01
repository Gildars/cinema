<?php

namespace App\Cinema\Core;

/**
 * Class Session
 * @package App\Cinema\Lib
 */
class Session
{
    protected static $flash_massage;

    /**
     * @param $message
     */
    public static function setFlash($message)
    {
        self::$flash_massage = $message;
    }

    /**
     * @return bool
     */
    public static function hasFlash(): bool
    {
        return !is_null(self::$flash_massage);
    }

    public static function flash()
    {
        echo self::$flash_massage;
        self::$flash_massage = null;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key): mixed
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     */
    public static function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function destroy()
    {
        session_destroy();
    }
}
