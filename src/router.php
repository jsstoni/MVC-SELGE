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

	public function post()
	{
		$path = $this->main.$path;
		$this->POST[$path] = $cb;
	}

	public function put()
	{
		$path = $this->main.$path;
		$this->PUT[$path] = $cb;
	}

	public function delete()
	{
		$path = $this->main.$path;
		$this->DELETE[$path] = $cb;
	}

	private function checkURL()
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

	public function run()
	{
		if ($check = $this->checkURL()) {
			$path = array_keys($check);
			$cb = array_values($check);
			call_user_func_array($cb[0], array());
		}
	}
}