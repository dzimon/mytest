<?php




return [
	'global' => [
		'debug' => false,
		'fr_admin' => true, // Boolean true or false to use default admin or custom
		'admin_uri' => 'admin',
		'site_name' => 'Мой сайт'
	],
	'db'     => [
		'db_driver' => '',
		'db_server' => '',
		'db_name' => '',
		'db_password' => '',
		'db_user' => '',
		'db_port' => '',
		'db_prefix' => '',
		'db_charset' => ''
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