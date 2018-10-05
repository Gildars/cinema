<?php

/**
 *Класс отвечает за хранение настроек сайта, таких как параметры соиденения с бд.
 */
class Config
{
    protected static $settings = array(); //настройки сайта представляются  в виде масива

    public static function get($key)
    {
        return isset (self::$settings[$key]) ? self::$settings[$key] : null;// функция возвращает значение из масива $setings если оно существует в противном случае будет возвращатся null
    }

    public static function set($key, $value)
    {  //В теле функции выполнятся присвоение значение елементу масива $setings с указаным ключом (таким образом можна устанавливать и поулчать настройки сайта)
        self::$settings[$key] = $value;
    }

}
