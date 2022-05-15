<?php
namespace model;
use src\Model;
class User extends Model
{
	public static function misClientes($page = 1, $rows = 12)
	{
		return self::queryPaginate("SELECT * FROM clientes", $page, $rows);
	}
}