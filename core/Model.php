<?php


namespace core;


abstract class Model
{
	const TABLE = 'undefined';

	const CHOICE = [];

	public $db;

	public function __construct()
	{
		$this->db = DB::connect();
	}

	/**
	 * @param array $args
	 * @return array
	 */
	public function get(array $args = []){
		foreach ($args as $k => $v) {
			$sql = "SELECT * FROM " . static::TABLE . " WHERE $k = $v";
		}

		$result = $this->db->query($sql);
		return $result->fetch(\PDO::FETCH_ASSOC) ? : [];
	}

	/**
	 * @return array
	 */
	public function all()
	{
		$sql = "SELECT * FROM " . static::TABLE;
		$result = $this->db->query($sql);
		return $result->fetchAll(\PDO::FETCH_ASSOC) ? : [];
	}

	/**
	 * @param array $args
	 * @return mixed
	 */
	public function getOr404(array $args = [])
	{
		foreach ($args as $k => $v) {
			$sql = "SELECT * FROM " . static::TABLE . " WHERE $k = $v";
		}
		$res = $this->db->query($sql);
		$result = $res->fetch(\PDO::FETCH_ASSOC);
		if ($result) {
			return $result;
		} else {
			$response = new Response();
			$response->return_404();
		}
	}

	/**
	 * @param array $args
	 * @return bool
	 */
	public function delete(array $args = [])
	{
		foreach ($args as $k => $v) {
			$sql = "DELETE FROM ". static::TABLE ." WHERE $k = $v";
		}
		$res = $this->db->query($sql);
		return $res->rowCount() ? : false;
	}

	public function getTableScheme($table_name = '')
	{
		if (!$table_name){
			$table_name = static::TABLE;
		}
		$sql = "SHOW COLUMNS FROM " . $table_name;
		$res = $this->db->prepare($sql);
		$res->execute();
		return $res->fetchAll() ? : false;
	}



}