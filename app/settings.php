<?php


return [
	'global' => [
		'debug' => true,
		'fr_admin' => true, // Boolean true or false to use default admin or custom
		'admin_uri' => 'admin',
		'site_name' => 'Мой сайт'
	],
	'db'     => [
		'db_driver' => 'mysql',
		'db_server' => 'localhost',
		'db_name' => 'books',
		'db_password' => '',
		'db_user' => 'root',
		'db_port' => '',
		'db_prefix' => '',
		'db_charset' => 'utf8'
	],
	'auth'   => [
		'login_page' => 'login',
		'logout_page' => 'logout'
	],
	'session' => [
		'session_name'    => 'SID',
		'cookie_life'     => 60 * 60 * 24 * 7 * 2,
		'cookie_path'     => '/',
		'cookie_httponly' => true,
	],
];
