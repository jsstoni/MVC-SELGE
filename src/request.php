<?php
namespace src;
class Request
{
	private $input = array();
	private $post = array();
	private $get = array();

	public function __construct()
	{
		$this->input = json_decode(file_get_contents("php://input"), true);
		array_walk($_POST, array(&$this, '_setPost'));
		array_walk($_GET, array(&$this, '_setGet'));
	}

	private function _setPost($str, $key)
	{
		$this->post[$key] = $str;
	}

	private function _setGet($str, $key)
	{
		$this->get[$key] = $str;
	}

	public function setInputPath($args)
	{
		$this->input = array_merge($this->input ?? array(), $args, $this->post, $this->get);
	}

	public function getInput($key)
	{
		return $this->input[$key] ?? null;
	}

	public function getPost($key)
	{
		return $this->post[$key] ?? null;
	}
}