<?php

use app\Shop\models\ShopModel;
use app\Users\models\UserModel;
use core\Admin\Admin;
$shop = new ShopModel;
$users = new UserModel;
Admin::register($shop, [
	'lists' => [
		'fields' => [
			'№'=>'id', 'Товар'=>'name', 'Цена' => 'price',
			'Новая цена'=>'new_price', 'Год'=>'year', 'Наличие' => 'in_stock'
		],
		'meta' => ['link'=>'name']
	],
	'fieldset' => [
		'data'=> [
			'Основное' => [
				'Название'=>'name',	'URL'=>'slug', 'Цена'=>'price',	'Новая цена'=>'new_price',
				'Год'=>'year', 'Описание'=> 'description', 'Наличие' => 'in_stock'
			],
			'SEO' => [
				'SEO Title' => 'seo_title',
				'SEO Описание' => 'seo_desc',
				'Ключевые слова' => 'seo_keys',
			],
		],
		'widgets' => [
			'number'=>['price'],
			'dataType'=>['year', 'date'],
			'textArea'=>['VEditor' =>'description'],
			'select'=>[$shop::CHOICE]
		],
	],
	'app_meta' => [
		'app_name' => 'Каталог',
	]
]);

Admin::register($users, [

]);