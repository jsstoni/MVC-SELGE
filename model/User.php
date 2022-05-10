<?php
namespace model;
use src\DB;
class User
{
	public static function All()
	{
		return DB::query("SELECT * FROM usuarios");
	}
}