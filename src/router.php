<?php
namespace src;
class Router
{
	private $url;
	private $main;

	public function __construct()
	{
		$this->url = $_SERVER['REQUEST_URI'];
	}

	public function _setMain($path)
	{
		$this->main = $path;
	}
}