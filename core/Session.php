<?php

namespace core;


class Session
{
	public static function start()
	{
		ini_set('session.use_only_cookies', 1);
		session_start();
		if (!isset($_SESSION['time'])) {
			$_SESSION['time'] = date("H:i:s");
		}
		echo $_SESSION['time'];
	}

	public static function destroy()
	{
		session_destroy();
	}
}