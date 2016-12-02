<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.11.2016
 * Time: 11:14
 */

namespace core\Admin;


class Fields
{
	protected static $input_type = 'text';
	protected static $input_id = '';
	protected static $input_value = '';
	protected static $input_attrs = [];
	protected static $input = '<input ';
	protected static $label = '<label ';
	protected static $select = '<select ';

	public static function inputField(array $attrs = [])
	{
		$input = self::$input . 'id="' . self::$input_id .'"'. 'type=' . self::$input_type;
		if ($attrs){
			foreach ($attrs as $name => $attr){
				$input .= ' ' . $name . '="' . $attr . '" ';
			}
		}
		$input .= '>';
		return $input;
	}

	public static function textArea(array $attrs = [])
	{
		self::$input = '<textarea ';
		$input = self::$input . 'id="' . self::$input_id .'">';
		if ($attrs){
			foreach ($attrs as $name => $attr){
				$input .= ' ' . $name . '="' . $attr . '" ';
			}
		}
		$input .= '</textarea>';
		echo $input;
	}
}