<?php
namespace model;
use src\Model;
use src\Auth;
class User extends Model
{
	public static function misClientes($page = 1, $rows = 12)
	{
		$token = Auth::getAuthorization();
		return self::queryPaginate("SELECT * FROM clientes WHERE usuario = '{$token}'", $page, $rows);
	}
}