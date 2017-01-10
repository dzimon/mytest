<?php

namespace core;


class Session
{
	public function start()
	{
		ini_set('session.use_only_cookies', 1);
		session_start();
		if (!isset($_SESSION['time'])) {
			$_SESSION['time'] = date("H:i:s");
		}
		if (!isset($_SESSION['csrf_token'])){
			$_SESSION['csrf_token'] = $this->token_gen();
			setcookie('csrf_token', $_SESSION['csrf_token']);
		}
	}

	public function destroy()
	{
		session_destroy();
	}

	public function token_gen()
	{
		$token = bin2hex(random_bytes(16));
		return $token;
	}

}