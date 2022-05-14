<?php
namespace controller;
use model\User;
class Home
{
	public function Index($request)
	{
		echo json_encode(User::All());
	}
}