<?php


namespace app;
use core\Controller;

abstract class BaseController extends Controller
{
	public function __construct()
	{
		echo 'Im a class: ' . static::class;
	}
}