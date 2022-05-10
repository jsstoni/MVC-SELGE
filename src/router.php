<?php
namespace src;
class Router
{
	const	DEFAULT_REGEX = '/:([^\/]+)/',
			REPLACE_REGEX = '([^/]+)';
	private $url;
	private $main;
	private $method;
	private $GET = array();
	private $POST = array();
	private $PUT = array();
	private $DELETE = array();

	public function __construct()
	{
		$this->url = $_SERVER['REQUEST_URI'];
		$this->method = $_SERVER['REQUEST_METHOD'];
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

	public function get($path, $cb)
	{
		$path = $this->main.$path;
		$this->GET[$path] = $cb;
	}

	public function post($path, $cb)
	{
		$path = $this->main.$path;
		$this->POST[$path] = $cb;
	}

	public function put($path, $cb)
	{
		$path = $this->main.$path;
		$this->PUT[$path] = $cb;
	}

	public function delete($path, $cb)
	{
		$path = $this->main.$path;
		$this->DELETE[$path] = $cb;
	}

	private function _checkURL()
	{
		$list = array();
		switch ($this->method) {
			case 'GET':
				$list = $this->GET;
				break;
			case 'POST':
				$list = $this->POST;
				break;
			case 'PUT':
				$list = $this->PUT;
				break;
			case 'DELETE':
				$list = $this->DELETE;
				break;
		}
		return array_filter($list, function($path) {
			return preg_match($this->_regex($path), $this->url);
		}, ARRAY_FILTER_USE_KEY);
	}

	private function _convertRequest($path)
	{
		$route = explode('/', $path[0]);
		$url_route = explode('/', rtrim($this->url, '/'));
		$route_union = array_combine($route, $url_route);
		$args = array();
		foreach ($route_union as $key => $value) {
			if (preg_match(self::DEFAULT_REGEX, $key)) {
				$key = str_replace(':', '', $key);
				if (! array_key_exists($key, $args)) {
					$args[$key] = $value;
				}
			}
		}
		return $args;
	}

	public function run()
	{
		if ($check = $this->_checkURL()) {
			$path = array_keys($check);
			$cb = array_values($check);
			return call_user_func($cb[0], $this->_convertRequest($path));
		}
		echo "Error 404";
	}
}