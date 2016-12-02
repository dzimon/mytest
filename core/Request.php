<?php


namespace core;


class Request
{
	private $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'UPDATE'];
	protected $url;
	public $args = [];
	public $method = null;

	public function __construct()
	{

		if (in_array($_SERVER['REQUEST_METHOD'], $this->allowedMethods)) {
			$this->method = isset($_SERVER['REQUEST_METHOD']) ?
				trim(strtoupper($_SERVER['REQUEST_METHOD'])) :
				'';
		} else {
			echo '<h3>This method not allowed!</h3>';
			exit;
		}
		$this->getUrl();
	}

	/**
	 * @param string $key
	 * @param null $default
	 * @return null
	 */
	public function GET($key = '', $default = null)
	{
		$result = null;
		if ($key && isset($_GET[$key])) {
			$result = $_GET[$key];
		} elseif ($key && !isset($_GET[$key])) {
			$result = $default;
		} else {
			$result = $_GET;
		}
		return $result;
	}

	/**
	 * @param string $key
	 * @param null $default
	 * @return null
	 */
	public function POST($key = '', $default = null)
	{
		$result = null;
		if ($key && isset($_POST[$key])) {
			$result = $_POST[$key];
		} elseif ($key && !isset($_POST[$key])) {
			$result = $default;
		} else {
			$result = $_POST;
		}
		return $result;
	}

	/**
	 * @return array
	 */
	public function getAllowedMethods(): array
	{
		return $this->allowedMethods;
	}

	/**
	 * @return bool
	 */
	public function is_ajax()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == strtolower('XMLHttpRequest')) ? true : false;
	}

	/**
	 * @return mixed
	 */
	public function getUrl(){
		$this->cleanUrl($_SERVER['REQUEST_URI']);
		return $this->url;
	}

	/**
	 * @param $query
	 */
	protected function cleanUrl($query)
	{
		$query = explode('&', trim($query, '/'));
		$this->args = array_slice($_REQUEST, 1);
		foreach ($this->args as $key => $value){
			$this->args[$key] = trim($value, ' /');
		}
		$this->url = trim($query[0], '/');;
	}
}