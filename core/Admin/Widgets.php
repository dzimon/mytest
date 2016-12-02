<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.11.2016
 * Time: 16:50
 */

namespace core\Admin;


class Widgets extends Fields
{



	public static function textField()
	{
		parent::inputField();

	}

	public static function textAreaField()
	{
		parent::textArea();
	}

	public static function multiSelectField()
	{
		return '';
	}

	public static function dateField()
	{
		self::$input_type = 'date';
		self::inputField(['class'=>'datepicker']);
	}

	public static function passField()
	{
		return '';
	}

	public static function emailField()
	{
		return '';
	}

	public static function urlField()
	{
		return '';
	}

	public static function selectField()
	{
		return '';
	}

	public static function checkboxField()
	{
		return '';
	}

	public static function radioField()
	{
		return '';
	}

	public static function numberField()
	{
		return '';
	}

	public static function fileField()
	{
		return '';
	}

	public static function hiddenField()
	{
		return '';
	}

}