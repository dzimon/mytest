<?php

namespace core;

use core\Admin\Admin;

class Router
{
	protected static $routes = [];
	protected static $route = [];
	protected static $abs_url;
	private static $admin = true;
	public static $admin_url;

	public static function setUrls(array $urls = null)
	{
		self::setAdmin();
		self::setAdminUrl();
		if ($urls) {
			foreach ($urls as $reg => $date) {
				self::add($reg, $date);
			}
		} else {
			throw new Exception('Для работы сайта нужно указать url адреса');
			exit;
		}
	}

	public static function add($regexp, array $route = [])
	{
		self::$routes[$regexp] = $route;
	}

	/**
	 * @return array
	 */
	public static function getRoutes(): array
	{
		return self::$routes;
	}

	/**
	 * @return array
	 */
	public static function getRoute(): array
	{
		return self::$route;
	}

	public static function match($url)
	{
		foreach (self::$routes as $pattern => $route) {
			if (preg_match("~$pattern~i", $url, $matches)) {
				foreach ($matches as $key => $value) {
					if (is_string($key)) {
						$route[$key] = $value;
					}
				}
				if (!isset($route['action'])) {
					$route['action'] = 'listView';
				}
				$route['controller'] = self::formatClassName($route['controller']);
				self::$route = $route;
				return true;
			}
		}
		return false;
	}

	/**
	 * @param $url
	 * @throws Exception
	 */
	public static function dispatch($url)
	{
		if (self::match($url)) {
			$app = explode('/', $url);
			if ($app[0] != self::$admin_url || self::$admin === false) {
				if (is_dir(APP_DIR . '\\' . ucfirst($app[0])) && !empty($app[0])) {
					$controller = 'app\\' . ucfirst($app[0]) . '\controllers\\' .
						self::formatClassName(self::$route['controller']);
				} else {
					$controller = 'app\\' . self::formatClassName(self::$route['controller']);
				}
				if (class_exists($controller)) {
					(new $controller)->{self::$route['action']}();
				}

			} elseif ($app[0] == self::$admin_url && self::$admin === true) {
				$controller = 'core\\Admin\\' . self::formatClassName(self::$route['controller']);
				if (count($app) > 2) {
					$_GET['id'] = $app[count($app) - 1];
				}
				$controller::{self::$route['action']}();
			}

		} else {
			(new Response())->return_404();
		}
	}

	public static function formatClassName($name)
	{
		$name = join('', array_map('ucwords', explode('-', $name)));
		return $name;
	}

	private static function setAdmin()
	{
		self::$admin = Config::getSettings()['global']['fr_admin'];
	}

	private static function setAdminUrl()
	{
		self::$admin_url = Config::getSettings()['global']['admin_uri'];
	}

	public static function getAbsoluteUrl()
	{
		return self::$abs_url;
	}
}
