<?php


namespace core;


class Query
{

	protected $sql = '';
	protected $table;
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

	public function __toString()
	{
		return $this->query();
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
		$this->sql .= is_array($args)? implode(',', $args): $args;
		return $this;
	}

	/**
	 * @param string $table
	 * @return $this|bool
	 */
	public function from($table = '')
	{
		if (is_string($table)) {
			$this->table = $table;
			$this->sql .= ' FROM ' . $this->table;
		} else {
			return false;
		}
		return $this;
	}

	public function where()
	{
		return $this;
	}

	public function insert()
	{
		$this->sql = "INSERT INTO ";
		return $this;
	}

	public function update()
	{
		$this->sql = "UPDATE ";
		return $this;
	}

	public function delete(array $params=[])
	{
		$this->sql .= "DELETE " . $this->table . " WHERE ";
		foreach ($params as $k => $v){
			$this->sql .= $k . '=' .$v;
		}
		return $this;
	}

	public function query($fetch = 'fetchAll', $result_arr = 'FETCH_ASSOC')
	{
		var_dump($this->sql);
		$res = $this->db->query($this->sql);
		$result = $res->fetchAll(\PDO::FETCH_ASSOC);
		var_dump($result);
		return $this->sql;
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
					switch ($data[1]){
						case 'contains':
							$this->sql .= "'%" . $v ."%'";
							break;
						case 'starts':
							$this->sql .= "'" . $v ."%'";
							break;
						case 'ends':
							$this->sql .= "'%" . $v ."'";
							break;
						case 'in':
							$this->sql .= (is_array($v)) ? "(" . implode(',', $v) .")": '()';
							break;
						case 'range':
							$c = 0;
							if (is_array($v)) {
								foreach ($v as $item) {
									$c++;
									$this->sql .= ($c <=1 )? "'" . $item . "' AND " : "'" . $item . "'";
								}
							}
							break;
						default:
							$this->sql .= (is_string($v)) ? "'" . $v ."'": $v;
					}
					if (count($args) > 1 && count($args) !== $i){
						$this->sql .= ' AND';
					}
				}
			}
		}
		$this->sql = (substr($this->sql, -3) === 'AND')? rtrim(substr($this->sql,0, -3)): $this->sql;
		return $this;
	}


	public function raw($sql)
	{
		if (is_string($sql)) {
			$res = $this->stmt->query($sql);
			$result = $res->fetchAll(\PDO::FETCH_ASSOC);
			if ($result) {
				return $result;
			}
		}
		return true;
	}


}