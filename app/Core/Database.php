<?php

namespace App\Cinema\Core;

use PDO;

/**
 * Class Database
 * @package App\Cinema\Lib
 */
class Database extends PDO
{
    private static $instance = null;

    protected function __construct()
    {

        self::$instance =  parent::__construct(
            'mysql:host=' . Config::get('db.host') . ';dbname=' . Config::get('db.db_name'),
            Config::get('db.user'),
            Config::get('db.password'),
            [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'", PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT]
        );
    }


    /**
     * @return \App\Cinema\Core\Database|\PDO|null
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    protected function __clone()
    {
    }

}
