<?php

use App\Cinema\Core\Config;

Config::set('site_name', 'Cinema');

Config::set('routes', [
    'default' => '',
    'admin' => 'admin_',
]);

Config::set('default_route', 'default');
Config::set('default_controller', 'films');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'cinema');

Config::set('salt', '23fd45g2f839');
