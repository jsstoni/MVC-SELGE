<?php
namespace src;
class Request
{
	public $data;

	public function __construct()
	{
		$this->data = file_get_contents("php://input");
	}
}