<?php
namespace src;
use src\DB;
class Model
{
	public function getResult($sql)
	{
		try {
			$statement = DB::prepare($sql);
			$statement->execute();
			$result = array();
			while ($find = $statement->fetch()) {
				$result[] = $find;
			}
			$statement->closeCursor();
			return $result;
		} catch (\Exception $e) {
			return array();
		}
	}

	public function queryPaginate($query, $page, $quantity)
	{
		$q = $this->Query($query);
		if ($q) {
			$totalQuery = $q->rowCount();
			if ($totalQuery > 0 || $totalQuery >= $quantity) {
				$paginar = new Paginacion($page, $totalQuery, $quantity);
				$query .= " LIMIT {$paginar->start}, {$paginar->end}";
				$result = $this->getResult($query);
			} else {
				$result = $this->getResult($query);
			}
			$data = array(
				'result' => $result,
				'pages' => isset($paginar) ? $paginar->totalPage : 1
			);
			return $data;
		}
		return array('result' => array(), 'pages' => '');
	}

	public function Query($query)
	{
		try {
			$statement = DB::query($query);
			return $statement;
		} catch (\PDOException $e) {
			return false;
		}
	}

	public function Remove($table, $where)
	{
		try {
			$query = "DELETE FROM {$table} WHERE {$where}";
			$statement = DB::prepare($query);
			$result = $statement->execute();
			$statement->closeCursor();
			if ($result) {
				return true;
			}
			return false;
		} catch (\Exception $e) {
			return false;
		}
	}

	private function _rows($values = array())
	{
		$j = 0;
		foreach ($values as $value) {
			if (is_array($value)) {
				$rows = array_keys($value);
				$j++;
			} else {
				$rows = array_keys($values);
				$j = 1;
			}
		}
		$placeHolders = substr(str_repeat(',?', count($rows)), 1);
		$rows = "(".implode(", ", $rows).") VALUES (".implode('), (', array_fill(0, $j, $placeHolders)).")";
		return $rows;
	}

	private function _fields($fields = array())
	{
		$set = array();
		foreach ($fields as $name => $value) {
			$set[] = "{$name} = \"{$value}\"";
		}
		return implode(', ', $set);
	}

	private function _records(array $field)
	{
		$records = [];
		foreach ($field as $record) {
			if (is_array($record)) {
				array_push($records, ...array_values($record));
			} else {
				array_push($records, $record);
			}
		}
		return $records;
	}

	public function Insert($table, $values = array(), $wildcard = '')
	{
		switch ($wildcard) {
			case 'IGNORE':
				$q = "INSERT IGNORE INTO {$table} ";
				break;
			case 'REPLACE':
				$q = "REPLACE INTO {$table} ";
				break;
			default:
				$q = "INSERT INTO {$table} ";
				break;
		}
		$q = $q.$this->_rows($values);
		$records = $this->_records($values);
		try {
			$statement = DB::prepare($q);
			$result = is_array($values) ? $statement->execute($records) : $statement->execute();
			$statement->closeCursor();
			if ($result) {
				return true;
			}
			return false;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function InsertDuplicateKey($table, $data = array())
	{
		try {
			$sql = "INSERT INTO ".$table."";
			$values = array();
			$columns = array();
			foreach ($data as $key => $value) {
				if (is_array($value)) {
					if ($key < 1) {
						$columns = array_keys($value);
					}
					$values[] = " ('".implode("', '", $value)."') ";
				} else {
					$columns = array_keys($data);
					$values = array_values($data);
					break;
				}
			}
			$col = " (`".implode("`, `", array_values($columns))."`)";
			$dku = implode(", ", array_map(function ($v) {
				return $v." = values(".$v.")";
			}, $columns));
			$sql .= $col." VALUES ".implode(", ", $values)." ON DUPLICATE KEY UPDATE {$dku}";
			return $this->Query($sql);
		} catch (\Exception $e) {
			return false;
		}
	}

	private function _setvalues(array $v)
	{
		$keys = array_keys($v);
		$k = array();
		foreach ($keys as $value) {
			$k[] = $value."=?";
		}
		return implode(",", $k);
	}

	public function Update($table, $set = array(), $where)
	{
		$setvalues = $this->_setvalues($set);
		$records = $this->_records($set);
		$q = "UPDATE {$table} SET {$setvalues} WHERE {$where}";
		$statement = DB::prepare($q);
		$result = is_array($set) ? $statement->execute($records) : $statement->execute();
		$statement->closeCursor();
		if ($result) {
			return true;
		}
		return false;
	}
}