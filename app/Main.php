<?php

namespace app;

use app\Blog\models\BlogModel;
use app\Category\models\CategoryModel;
use app\Shop\models\ShopModel;
use core\Controller;
use core\View;

class Main extends Controller
{
	public function index(){
		$goods = (new ShopModel())->all();
		$news = (new BlogModel())->all();
		$cat = (new CategoryModel())->all();
		$context = [
			'goods' => $goods,
			'news' => $news
		];
		$view = new View();
		$view->render('catalog/catalog.twig', $context);
	}
}