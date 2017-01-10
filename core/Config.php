<?php


namespace core;


class Config
{
	public static $settings = [];  // Base settings
	public static $schema;         // URL schema
	public static $db = [];        // DB settings
	public static $urls = [];      // urls settings
	public static $admin_url = '';


	public static function init()
	{
		self::setSchema($_SERVER['HTTP_X_FORWARDED_PROTO']);
		if (!defined('DS')){define('DS', DIRECTORY_SEPARATOR);}
		if (!defined('BASE_DIR')){define('BASE_DIR', dirname(__DIR__));}
		if (!defined('APP_DIR')){define('APP_DIR', dirname(__DIR__) . DS . 'app');}
		if (!defined('PUBLIC_DIR')){define('PUBLIC_DIR', __DIR__);}
		if (!defined('CORE_DIR')){define('CORE_DIR', dirname(__DIR__) . DS . 'core');}
		if (!defined('TEMPLATE_DIR')){define('TEMPLATE_DIR',
			dirname(__DIR__) . DIRECTORY_SEPARATOR . '/app/templates');}
		require_once __DIR__ .'/helpers/helpers.php';
		if (is_file(dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'app/settings.php'))
		{
			self::$settings = array_merge(
				require dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'core/default.settings.php',
				require dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'app/settings.php'
			);
		} else {
			self::$settings = (require dirname(__DIR__)  . DIRECTORY_SEPARATOR . 'core/default.settings.php');
		}
		self::setSettings(self::$settings);

		if (file_exists(dirname(__DIR__) . 'vendor/autoload.php'))
		{
			require dirname(__DIR__) . 'vendor/autoload.php';
		} else {
			spl_autoload_register(function ($class){
				$file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php';
				if (is_file($file))
				{
					require_once $file;
				}
			});
		}
		self::$db = self::setDb();

		require APP_DIR . DS . 'Admin/admin.php';
		self::setUrls();

		if (self::$settings['global']['debug'] == true){
			error_reporting(-1);
			ini_set('display_errors', 1);
		}

	}

	/**
	 * @return array
	 */
	public static function getSettings(): array
	{
		return self::$settings;
	}

	/**
	 * @param array $settings
	 */
	public static function setSettings(array $settings)
	{
		self::$settings = $settings;
	}

	/**
	 * @return mixed
	 */
	public static function getSchema()
	{
		return self::$schema;
	}

	/**
	 * @param mixed $schema
	 */
	public static function setSchema($schema)
	{
		if ($schema === 'https'){
			$_SERVER['SERVER_PORT'] = '443';
			self::$schema = 'https';
		} else {
			self::$schema = 'http';
		}
	}

	/**
	 * @return array
	 */
	public static function getDb(): array
	{
		return self::$db;
	}

	/**
	 * @param array $db
	 * @return array|mixed|null
	 */
	public static function setDb(array $db = [])
	{
		$db = (isset(self::$settings['db'])) ? self::$settings['db'] : null;
		return $db;
	}

	/**
	 * @return array
	 */
	public static function getUrls()
	{
		return self::$urls;
	}


	public function setUrls()
	{
		self::$urls = (self::$settings['global']['fr_admin']) ? array_merge(
			                 require APP_DIR . DS . 'urls.php',
			                 require __DIR__ . '/Admin/urls.php'
		                 ) : require APP_DIR . DS . 'urls.php';

	}

}