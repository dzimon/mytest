<?php


namespace core;


class App
{


	public static function init()
	{
		Config::init();
		(new Session())->start();
	}

	public static function run()
	{
		Router::setUrls(Config::getUrls());
		$request = new Request();
		Router::dispatch($request->getUrl());
	}
}