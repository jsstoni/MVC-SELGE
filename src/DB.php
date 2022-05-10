<?php
namespace src;
use PDO;
trait DB
{
	protected static $instance = null;
	protected function __construct() {}
	protected function __clone() {}

	public static function instance()
	{
		if (self::$instance === null) {
			$drive = sprintf("mysql:host=%s;dbname=%s", HOST, DBNAME);
			self::$instance = new PDO($drive, USER, PASS, array(
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			));
			self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$instance;
	}

	public static function __callStatic($method, $args)
	{
		return call_user_func_array(array(self::instance(), $method), $args);
	}
}