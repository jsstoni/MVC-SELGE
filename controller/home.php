<?php
namespace controller;
use model\User;
class Home
{
	public function Index()
	{
		var_dump(User::All());
	}
}