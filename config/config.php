<?php

use App\Cinema\Lib\Config;

Config::set('site_name', 'Cinema');

//Список языков использумых в преокте
Config::set('languages', array('ru', 'ua')); // доступ к списку языков осуществляется по ключу languages

//роуты
Config::set('routes', array(
    'default' => '',
    'admin' => 'admin_',
));
//название контроллеров по умолчанию

Config::set('default_route', 'default');
Config::set('default_controller', 'films');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'cinema');

Config::set('salt', '23fd45g2f839');
