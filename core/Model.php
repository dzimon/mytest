<?php


namespace core;


abstract class Model extends Query
{
	const TABLE = 'undefined';

	const CHOICE = [];

	public $db;

	public function __construct()
	{
		parent::__construct();
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
		return $result->fetch() ? : [];
	}

	/**
	 * @return array
	 */
	public function all()
	{
		$sql = "SELECT * FROM " . static::TABLE;
		$result = $this->db->query($sql);
		return $result->fetchAll() ? : [];
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
//		$res = $this->db->execute($sql);
		$result = $res->fetch();
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
		$sql = '';
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

	public function getAbsUrl($param = null)
	{
		$schema = Config::$schema;
		$get = $param;
		if (explode('/', $_SERVER['REQUEST_URI'])[1] == Router::$admin_url){
			$url = Router::$admin_url;
			$model = explode('/', $_SERVER['REQUEST_URI'])[2];
			$abs_url = $schema . '://' . $_SERVER['HTTP_HOST'] . '/' . $url . '/' . $model . '/';
			if ($get) {
				$abs_url .= trim($get, '/') . '/';
			}
		} else {
			$model = explode('/', $_SERVER['REQUEST_URI'])[1];
			$abs_url = $schema . '://' . $_SERVER['HTTP_HOST'] . '/' . $model . '/';
			if ($get) {
				$abs_url .= trim($get, '/') . '/';
			}
		}
		return $abs_url;
	}

}