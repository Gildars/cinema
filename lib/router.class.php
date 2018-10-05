<?php

class Router {

protected $uri;

protected $controller;

protected $action;

protected $params;

protected $route;

protected $method_prefix;


public function getUri()
{
	return $this->uri;
}
public function getController(){
	return $this->controller;
}
public function getAction() {
	return $this->action;
}
public function getParams() {
	return $this->params;
}

public function getRoute()
{
	return $this->route;
}
public function getMethodPrefix()
{
	return $this->method_prefix;
}

//парсинг запроса
public function __construct($uri) {
	$this->uri = urldecode(trim($uri, '/'));// очищаем от слешей и правильно обрабатываем закодированые символы из урл
	
	$routes = Config::get('routes'); // получаем список роутеров
	$this->route = Config::get('default_route');
	$this->method_prefix = isset($routes[$this->route]) ? $routes[$this->route] : ''; // получаем методы префиксов по умолчанию
	$this->controller = Config::get ('default_controller');
	$this->action = Config::get ('default_action');
	//разбор ури
	$uri_parts = explode('?',  $this->uri); //разделяем ури
	$path = $uri_parts[0];// тепеь строка дял парсинга из которой мы будем получать параметри будет находится $uri_parts[0]
    $path_parts = explode('/', $path);
	/*if (strlen($path) > 0)  {
        $path_parts = explode('/', $path);
    }else{
        $path_parts = null;
    }*/
	//echo "<pre>"; print_r($path_parts); echo "</pre>";

if (count($path_parts)) {
    
    if (in_array(strtolower(current($path_parts)), array_keys($routes))){
        $this->route = strtolower(current($path_parts));
        $this->method_prefix = isset($routes[$this->route]) ? $routes[$this->route] : '';
        array_shift($path_parts);
    }
    if (current($path_parts)) {
        $this->controller = strtolower(current($path_parts));
        array_shift($path_parts);
    }
    if (current($path_parts)) {
        $this->action = strtolower(current($path_parts));
        array_shift($path_parts);
    }
    $this->params = $path_parts;	
}
	}
	public static function redirect($location) {
		header("Location:$location");
	}
}
