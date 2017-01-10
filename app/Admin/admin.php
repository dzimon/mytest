<?php

use app\Blog\models\BlogModel;
use app\Category\models\CategoryModel;
use app\Shop\models\ShopModel;
use app\Users\models\UserModel;
use core\Admin\Admin;

$shop = new ShopModel;
Admin::register($shop, [
	'lists' => [
		'fields' => [
			'№' => 'id', 'Товар' => 'name', 'Цена' => 'price',
			'Новая цена' => 'new_price', 'Год' => 'year', 'Наличие' => 'in_stock'
		],
		'meta' => ['link' => 'name']
	],
	'fieldset' => [
		'data' => [
			'Основное' => [
				'Название' => 'name', 'URL' => 'slug', 'Наличие' => 'in_stock', 'Цена' => 'price',
				'Новая цена' => 'new_price', 'Изображение' => 'image', 'Год' => 'year', 'Описание' => 'description'
			],
			'SEO' => [
				'SEO Title' => 'seo_title',
				'SEO Описание' => 'seo_desc',
				'Ключевые слова' => 'seo_keys',
			],
		],
		'widgets' => [
			'number' => ['price', 'new_price'],
			'datetype' => ['year'],
			'textarea' => ['description'],
			'file' => ['image'],
			'radio' => ['in_stock' => $shop::CHOICE],
			'autoslug' => ['name' => 'slug']
		],
	],
	'app_meta' => [
		'app_name' => 'Каталог',
	]
]);

$users = new UserModel;
Admin::register($users, [
	'lists' => [
		'fields' => [
			'ID' => 'id', 'Имя' => 'first_name', 'Фамилия' => 'last_name',
			'Логин' => 'nik_name', 'Email' => 'email', 'Админ' => 'is_super',
		],
		'meta' => ['link' => 'nik_name']
	],
	'fieldset' => [
		'data' => [
			'Личные Данные' => [
				'Имя' => 'first_name', 'Фамилия' => 'last_name',
				'Логин' => 'nik_name', 'Email' => 'email',
				'Аватар' => 'avatar', 'День рождения' => 'birthday_dt'
			],
			'Статус' => [
				'Админ доступ' => 'is_super'
			],
			'Дополнительные данные' => [
				'Дата регистрации' => 'add_date',
				'Дата последнего изменения' => 'edit_date',
				'Дата последнего визита' => 'last_visit',
				'Рейтинг' => 'rating',
			],
		],
		'widgets' => [
			'number' => ['rating'],
			'datetype' => ['add_date', 'edit_date', 'last_visit'],
			'file' => ['avatar'],
			'switch' => ['is_super'],
			'password' => ['password']
		],
	],
	'app_meta' => [
		'app_name' => 'Пользователи',
	]
]);

$blog = new BlogModel;
Admin::register($blog, [

	'app_meta' => [
		'app_name' => 'Блог',
	]
]);

$category = new CategoryModel;
Admin::register($category, [
	'lists' => [
		'fields' => [
			'№' => 'id', 'Название' => 'name', 'Порядок сортировки' => 'order',
			'Отображать в меню' => 'display_in_menu', 'Родительский ID' => 'parent_id'
		],
//		'meta' => ['link' => 'name']
	],
	'fieldset' => [
		'data' => [
			'Добавить категорию' => [
				'Название' => 'name', 'URL' => 'slug', 'Отображать в меню' => 'display_in_menu',
				'Порядок сортировки' => 'order', 'Описание' => 'description'
			],
		],
		'widgets' => [
			'switch' => ['display_in_menu'],
			'autoslug' => ['name' => 'slug'],
			'number' => ['order'],
			'textarea' => ['description'],
		],
	],
	'app_meta' => [
		'app_name' => 'Категории',
	]
]);
