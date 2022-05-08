<?php
namespace src;
class Router
{
	const	DEFAULT_REGEX = '/:([^\/]+)/',
			REPLACE_REGEX = '([^/]+)';
	private $url;
	private $main;

	public function __construct()
	{
		$this->url = $_SERVER['REQUEST_URI'];
	}

	public function _setMain($path)
	{
		$this->main = '/'.ltrim($path, '/');
	}

	private function _regex($path)
	{
		$regex = preg_replace(self::DEFAULT_REGEX, self::REPLACE_REGEX, $path);
		$regex = '/^' . str_replace('/', '\/', $regex) . '\/*$/s';
		return $regex;
	}

	private function checkURL()
	{
		return preg_match($this->_regex($this->main), $this->url);
	}

	public function run()
	{
		if ($check = $this->checkURL()) {
			echo "Hola mundo";
		}
	}
}