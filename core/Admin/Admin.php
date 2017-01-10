<?php


namespace core\Admin;


//use app\Shop\models\ShopModel;
//use app\Users\models\UserModel;
use app\Users\models\UserModel;
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
	public static $model_scheme = [];


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
			'site_name' => Config::getSettings()['global']['site_name'],
		]);
	}

	public static function listView()
	{
		$model = self::getModel($_SERVER['REQUEST_URI']);
		$key = explode('/', array_keys($_REQUEST)[0]);
		self::$meta = self::$models[$key[1]]['meta'];
		$table = [];
		if (empty(self::$meta['lists']['fields'])) {
			foreach ($model->getTableScheme() as $item => $value) {
				$table[ucwords(implode(' ', explode('_', $value['Field'])))] = $value['Field'];
			}
		} else {
			$table = self::$meta['lists']['fields'];
		}
		$context = [
			'table'=> $table,
			'meta'=> (isset(self::$meta['lists']['meta']))? self::$meta['lists']['meta'] : [],
			'dataset'=> (isset($model->all()[0]))? $model->all() : [],
			'site_name' => Config::getSettings()['global']['site_name'],
			'models' => self::$models,
			'abs_url' => $model->getAbsUrl()
		];
		if ((new Request())->method == 'POST'){
			if (isset($_POST['_delete'])){
				$model->delete(['id' => $_POST['_delete']]);
				$redirect = $model->getAbsUrl();
				Response::redirect($redirect);
			}
		}
		$view = new View(__DIR__ . '/tmp');
		$view->render('templates/chunks/list_views.html.twig', $context);
	}

	public static function detailView($id = null)
	{
		$model = self::getModel($_SERVER['REQUEST_URI']);
		self::$meta = self::$models[explode('/', $_SERVER['REQUEST_URI'])[2]]['meta'];
		foreach (self::$model->getTableScheme() as $item => $value){
			self::$model_scheme[$value['Field']] = $value;
		}
		$request = new Request();

		if (strtoupper($request->method) === 'GET'){
			if ($request->GET('id')){
				$id = $request->GET('id');
			}
		}
		$id = (!$id) ? $request->GET('id') : $id;
		$form = Form::buildForm(
			$model->getOr404(['id'=>$id]),
			self::$meta['fieldset']['widgets'],
			self::$meta['fieldset']['data']
		);
		if (strtoupper($request->method) === 'POST'){
			Form::validate($_POST);
			if (is_array($model->getOr404(['id'=>$id]))){
				$up_data = [];
				foreach ($model->getOr404(['id'=>$id]) as $field => $val){
					$up_data[$field] = (isset($_POST[$field])) ? $_POST[$field] : $val;
				}

				$model->update($up_data)->filter(['id__exact'=>$id])->execute();
				Form::save();

			} else {
				throw new Exception('Запись с указанным идентификатором отсутствует в базе данных');die;
			}
		}
		$fieldset = [];
		foreach (array_keys($model->getOr404(['id'=>$id])) as $key){
			$fieldset[$key] = $key;
		}

		$context = [
			'widgets' => (isset(self::$meta['fieldset']['widgets']))? self::$meta['fieldset']['widgets']: [],
			'site_name' => Config::getSettings()['global']['site_name'],
			'models' => (isset(self::$models)) ? self::$models : [],
			'form' => $form,
//			'message' => 'Спасибо, все работает как нужно',
			'abs_url' => $model->getAbsUrl()
		];
		$view = new View(__DIR__ . '/tmp');
		$view->render('templates/chunks/detail_view.html.twig', $context);
	}

	public static function createItem()
	{
		$model = self::getModel($_SERVER['REQUEST_URI']);
		self::$meta = self::$models[explode('/', $_SERVER['REQUEST_URI'])[2]]['meta'];
		foreach (self::$model->getTableScheme() as $item => $value){
			self::$model_scheme[$value['Field']] = $value;
		}

		$request = new Request();
		$fields = [];
		foreach (self::$model_scheme as $field => $value){
			if ($field == 'id') {
				continue;
			}
			$fields[$field] = '';
		}
		if ($model instanceof UserModel){
			self::$meta['fieldset']['data']['Статус']['Пароль'] = 'password';
		}

		$form = Form::buildForm(
			$fields,
			self::$meta['fieldset']['widgets'],
			self::$meta['fieldset']['data']
			);
//		var_dump($form);die;
		if ($request->method == 'POST'){
			Form::validate($_POST);
			$model->insert($_POST)->execute();
		}
		$context = [
			'widgets' => (isset(self::$meta['fieldset']['widgets']))? self::$meta['fieldset']['widgets']: [],
			'site_name' => Config::getSettings()['global']['site_name'],
			'models' => (isset(self::$models)) ? self::$models : [],
			'form' => $form,
//			'message' => 'Спасибо, все работает как нужно',
			'abs_url' => $model->getAbsUrl()
		];
		$view = new View(__DIR__ . '/tmp');
		$view->render('templates/chunks/detail_view.html.twig', $context);

	}

	public static function getModel($request)
	{
		$model = (isset(self::$models[explode('/', $request)[2]]['model'])) ?
			self::$models[explode('/', $request)[2]]['model'] : (new Response())->return_404();
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
					'number' => [],     // HTML5 number field
					'datetype' => [],   // Date field
					'textarea' => [],   // Text Area
					'select' => [],     // Select field
					'select_mul' => [], // Select multiple widget
					'checkbox' => [],   // Checkbox field
					'radio' => [],      // Radio fields
					'email' => [],      // HTML5 Email field
					'password' => [],   // Password field (****)
					'url' => [],        // HTML5 URL field
					'file' => [],       // File field
					'hidden' => [],     // Hidden field
					'switch' => [],     // Switch widget
					'filter' => [],     // Widget for many-to-many models
					'autoslug' => [],   // Field auto generate slug, most two field, example: ['field_name' => 'field_slug']
				],
			],
			'app_meta' => [
				'app_name' => '',
			],
		];
		self::$meta = array_merge($default, $meta);
	}

}