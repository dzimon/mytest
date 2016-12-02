<?php


namespace app\Shop\models;


use core\Admin\Form;
use core\Model;

class ShopModel extends Model
{
	const TABLE = 'books_books';
	const CHOICE = [
		'in' => 'В наличии',
		'not' => 'Нет в наличии',
		'exp' => 'Ожидается'
	];

}