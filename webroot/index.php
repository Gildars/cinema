<?php

require_once '../vendor/autoload.php';
use App\Cinema\Core\Bootstrap;

const DS = DIRECTORY_SEPARATOR;
define('ROOT', dirname(dirname(__FILE__)));
const VIEWS_PATH = ROOT . DS . 'views';
require_once ROOT . DS . 'config' . DS . 'config.php';
session_start();
Bootstrap::run($_SERVER['REQUEST_URI']);