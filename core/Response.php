<?php


namespace core;


class Response
{
	public $status_code = 200;


	public function return_404($template = '')
	{
		$this->status_code = 404;
		if (!$template){
			$template = '/errors/404.twig';
		}
		http_response_code($this->status_code);
		$view = new View();
		$view->render($template);
		exit;
	}
}