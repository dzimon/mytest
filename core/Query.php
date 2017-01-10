<?php


namespace core;


class Query
{

	protected $sql = '';
	protected $table;
	protected $db;
	private $operators = [
		'exact' => '=',
		'iexact' => 'LIKE',
		'in' => 'IN',
		'contains' => 'LIKE',
		'gt' => '>',
		'gte' => '>=',
		'lt' => '<',
		'lte' => '<=',
		'starts' => 'LIKE',
		'ends' => 'LIKE',
		'range' => 'BETWEEN',
		'isnull' => 'IS NULL',
		'!isnull' => 'IS NOT NULL',
		'or' => 'OR',
		'and' => 'AND',
		'not' => 'NOT'
	];

	public function __construct()
	{
		$this->db = DB::connect();
	}

	/**
	 * @param null $args
	 * @return $this
	 */
	public function select($args = null)
	{
		$this->sql = "SELECT ";
		if ($args === null) {
			$args = '*';
		}
		$this->sql .= is_array($args) ? implode(',', $args) : $args;
		return $this;
	}

	/**
	 * @param string $table
	 * @return $this|bool
	 */
	public function from($table = '')
	{
		if (is_string($table)) {
			$this->sql .= ' FROM ' . $table;
		} else {
			return false;
		}
		return $this;
	}

	public function where($args = null, $op = '=')
	{
		$this->sql .= " WHERE ";
		if ($args && !empty($args)){
			foreach ($args as $arg => $param){
				$this->sql .= $arg . " " . $op . " " . ":" . $arg;
			}
		}
		return $this;
	}

	public function limit($offset = null, $count = null)
	{
		$sql = " LIMIT ";
		if ($offset && is_numeric($offset)) {
			$sql .= (int)$offset . ", ";
		}
		if ($count && is_numeric($offset)) {
			$sql .= (int)$count;
		}
		$this->sql .= $sql;
		return $this;
	}

	public function insert(array $fields)
	{
		$this->sql = "INSERT INTO " . static::TABLE;
		$count = 0;
		$fds_str = '';
		$val_str = '';
		foreach ($fields as $field => $value){
			$count++;
			if ($field == 'csrf_token' || $field == '_save' || $field == '_save_exit'){
				continue;
			}
			if (!empty($value)){
				$fds_str .= $field ;
				$val_str .= "'" . $value . "'";
			}
			if ($count < count($fields)){
				$fds_str .= (!empty($value)) ? "," : null;
				$val_str .= (!empty($value)) ? "," : null;
			}
		}
		$fds_str = rtrim($fds_str, ',');
		$val_str = rtrim($val_str, ',');
		$this->sql .= " (" . $fds_str . ")" . " VALUES (" . $val_str . ")";
		return $this;
	}

	public function execute(array $args = null)
	{
		try {
			$stmt = $this->db->prepare($this->sql);
			$stmt->execute($args);
			return true;
		} catch (\PDOException $e) {
			echo 'Ошибка выполнения запроса. Причина: ' . $e->getMessage();
			exit;
		}
	}


	public function update(array $params = [])
	{
		$this->sql = "UPDATE " . static::TABLE . " SET ";
		$count = 1;
		foreach ($params as $field => $val) {
			$value = (is_string($val)) ? "'" . $val . "'" : $val;
			$this->sql .= $field . "=" . $value;
			if ($count < count($params)) {
				$this->sql .= " , ";
			}
			$count++;
		}
		return $this;
	}

	public function delete(array $params = [])
	{
		$this->sql .= "DELETE FROM " . static::TABLE . " WHERE ";
		foreach ($params as $k => $v) {
			$this->sql .= $k . '=' . $v;
		}
		return $this;
	}

	public function query($sql = '')
	{
		if (empty($sql)) {
			$sql = $this->sql;
		}
		$stmt = $this->db->query($sql);
		$result = $stmt->fetchAll();
		return $result;
	}

	public function filter(array $args = [])
	{
		$this->sql .= " WHERE";
		$i = 0;
		foreach ($args as $k => $v) {
			$i++;
			if (!empty($k)) {
				$data = (count(explode('__', $k)) > 1) ? array_merge(explode('__', $k),
					['value' => $v]) : [$k, 'exact', 'value' => $v];
				if (isset($this->operators[$data[1]])) {
					$this->sql .= ' ' . $data[0] . ' ' . $this->operators[$data[1]] . ' ';
					switch ($data[1]) {
						case 'contains':
							$this->sql .= "'%" . $v . "%'";
							break;
						case 'starts':
							$this->sql .= "'" . $v . "%'";
							break;
						case 'ends':
							$this->sql .= "'%" . $v . "'";
							break;
						case 'in':
							$this->sql .= (is_array($v)) ? "(" . implode(',', $v) . ")" : '()';
							break;
						case 'range':
							$c = 0;
							if (is_array($v)) {
								foreach ($v as $item) {
									$c++;
									$this->sql .= ($c <= 1) ? "'" . $item . "' AND " : "'" . $item . "'";
								}
							}
							break;
						default:
							$this->sql .= (is_string($v)) ? "'" . $v . "'" : $v;
					}
					if (count($args) > 1 && count($args) !== $i) {
						$this->sql .= ' AND';
					}
				}
			}
		}
		$this->sql = (substr($this->sql, -3) === 'AND') ? rtrim(substr($this->sql, 0, -3)) : $this->sql;
		return $this;
	}

}