<?php


namespace core;


class DB
{

	/**
	 * @return \PDO
	 */
	public static function connect()
	{
		try {
			return new \PDO(Config::$db['db_driver'] . ":host=" . Config::$db['db_server'] . ";" .
				"dbname=" . Config::$db['db_name'], Config::$db['db_user'], Config::$db['db_password'],
				[
					\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
					\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
				]);
		} catch (\PDOException $e) {
			die("Подключение не произошло. Причина: - " . $e->getMessage());
		}
	}

}