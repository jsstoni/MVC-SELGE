<?php
session_start();
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: application/json");
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)).DS);
function autoloading()
{
    spl_autoload_register(function ($class) {
        $file = str_replace('\\', DS, $class).'.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    });
}
autoloading();

$selge = new src\Router();