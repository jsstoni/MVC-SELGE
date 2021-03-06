<?php
session_start();
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
header("Content-Type: application/json");
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)).DS);
define('HOST', 'localhost');
define('DBNAME', 'selge');
define('USER', 'root');
define('PASS', '');

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

$secure = new src\Secure();
$selge = new src\Router();
$selge->_setMainFolder('mvc-selge');
$selge->setBasePath('routes.php');
$selge->run();