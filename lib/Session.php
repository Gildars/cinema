<?php

namespace App\Cinema\Lib;

class Session
{
    protected static $flash_massage;

    public static function setFlash($message)
    {
        self::$flash_massage = $message;
    }

    public static function hasFlash()
    {
        return !is_null(self::$flash_massage);
    }

    public static function flash()
    {
        echo self::$flash_massage;
        self::$flash_massage = null;
    }

    public function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

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
