<?php
namespace controller;
use model\User;
class Home
{
	public function Index($request)
	{
		var_dump(User::All());
	}
}