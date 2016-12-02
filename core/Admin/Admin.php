<?php


namespace core\Admin;


//use app\Shop\models\ShopModel;
//use app\Users\models\UserModel;
use core\Config;
use core\Exception;
use core\Model;
use core\Query;
use core\Request;
use core\Response;
use core\View;



class Admin extends Model
{
	public static $models = [];
	public static $model;

	public static $meta = [];


	public static function register(Model $model = null, array $meta = [])
	{
		if (!isset($meta['app_meta']['app_name']))
			$app_name = ucfirst(explode('\\', get_class($model))[1]);
		else {
			$app_name = $meta['app_meta']['app_name'];
		}

		$app_url = strtolower(explode('\\', get_class($model))[1]);
		self::setMeta($meta);
		self::$models[strtolower(explode('\\', get_class($model))[1])] = [
			'name' => $app_name,
			'url' => '/admin/' . $app_url . '/',
			'model' => get_class($model),
			'meta' => self::getMeta(),
		];

	}


	public static function dashboard()
	{
		$view = new View(__DIR__ . '/tmp');
		$view->render('templates/chunks/dashboard.twig', [
			'models' => self::$models,
			'form' => Form::outerHtml(),
		]);
	}

	public static function listView()
	{
		self::$model = self::getModel($_REQUEST);
		$key = explode('/', array_keys($_REQUEST)[0]);
		self::$meta = self::$models[$key[1]]['meta'];
		$view = new View(__DIR__ . '/tmp');
		$context = [
			'table'=>(isset(self::$meta['lists']))? self::$meta['lists']['fields']: [],
			'meta'=> (isset(self::$meta['lists']['meta']))? self::$meta['lists']['meta'] : [],
			'dataset'=>(isset(self::$model->all()[0]))? self::$model->all() : [],
			'site_name' => Config::getSettings()['global']['site_name'],
			'models' => self::$models
		];
		$view->render('templates/chunks/list_views.html.twig', $context);
	}

	public static function detailView($id = null)
	{
		$model = self::getModel($_SERVER['REQUEST_URI']);
		self::$meta = self::$models[explode('/', $_SERVER['REQUEST_URI'])[2]]['meta'];

		$request = new Request();
		if (strtoupper($request->method) === 'GET'){
			if ($request->GET('id')){
				$id = $request->GET('id');
			}
		}
//		$form = Form::renderForm(
//			$model->getOr404(['id'=>$id]),
//			self::$meta['fieldset']['widgets'],
//			self::$meta['fieldset']['data'],
//			['action'=>'/', 'method'=>'POST']
//			);
		$fieldset = [];
		foreach (array_keys(static::$model->getOr404(['id'=>$id])) as $key){
			$fieldset[$key] = $key;
		}
		$context = [
			'fieldset' => (isset(self::$meta['fieldset']))? self::$meta['fieldset']['data'] : $fieldset,
			'widgets' => (isset(self::$meta['fieldset']['widgets']))? : [],
			'dataset'=>$model->getOr404(['id'=>$id]),
			'site_name' => Config::getSettings()['global']['site_name'],
			'models' => (isset(self::$models)) ? self::$models : [],
			'filter' => Form::selectMultipleField('Категории', [1=>'Moda',2=>'News',3=>'Cat3',4=>'Cat4',5=>'Cat5'], [3=>'Cat3'])
		];
		$view = new View(__DIR__ . '/tmp');
		$view->render('templates/chunks/detail_view.html.twig', $context);
	}

	public static function getModel($request)
	{
		$model = self::$models[explode('/', $request)[2]]['model'];
		if (class_exists($model)){
			self::$model = new $model;
		} else {
			(new Response())->return_404();
		}
		return self::$model;
	}

	/**
	 * @return array
	 */
	public static function getMeta(): array
	{
		return self::$meta;
	}

	/**
	 * @param array $meta
	 */
	public static function setMeta(array $meta)
	{
		$default = [
			'lists' => [
				'fields' => [],
				'meta' => ['link'=>'']
			],
			'fieldset' => [
				'data' => [],
				'widgets' => [
					'numberFields' => [],
					'dateFields' => [],
					'textAreaFields' => [],
					'selectFields' => [],
					'checkboxFields' => [],
					'radioFields' => [],
					'emailFields' => [],
					'passwordFields' => [],
					'urlFields' => [],
					'fileFields' => [],
					'hiddenFields' => [],
				],
			],
			'app_meta' => [
				'app_name' => '',
			],
		];
		self::$meta = array_merge($default, $meta);
	}

}