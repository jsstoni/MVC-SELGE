<?php
namespace model;
use src\Model;
class User extends Model
{
	public static function All()
	{
		return self::getResult("SELECT * FROM usuarios");
	}
}