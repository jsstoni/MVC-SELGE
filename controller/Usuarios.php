<?php
namespace controller;
use model\User;
class Usuarios
{
	public function clientes($req)
	{
		$page = $req->getInput('page') ?? 1;
		var_dump(User::misClientes($page));
	}
}