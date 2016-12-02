<?php


namespace core\Admin;


class Form
{
	private static $form = '';
	private static $action = '';
	private static $method = 'post';
	private static $button_name = 'Action';


	/**
	 * It's data result from DB for this item
	 * @param array $data
	 * @param array $widgets
	 * @param array $fields
	 * @param array ...$kwargs
	 * @return string
	 */
	public static function renderForm(array $data, array $widgets, array $fields, ...$kwargs)
	{
		self::$form = '<form class="col s12" ';
		self::$form .= (isset($kwargs[0]['action']))? 'action="' . $kwargs[0]['action'] . '"':
			self::$action;
		self::$form .= (isset($kwargs[0]['method']))? ' method="' . $kwargs[0]['method'] . '">':
			' method="' .self::$method  . '">';

//		var_dump(self::selectMultipleField('Категории', [1=>'Moda', 2=>'News', 3=>'Cat3'], [3=>'Cat3']));die;
		$field_row = '<div class="row">%s</div>';
		$input_cls = 's12';
		$count = 0;
		foreach (Admin::getModel($_SERVER['REQUEST_URI'])->getTableScheme() as $key => $field){
			echo $field['Field'] . '<br>';
			echo $field['Type'] . '<br>';
			echo $field['Null'] . '<br>';

		}

		foreach ($fields as $key => $field){
			if (is_array($field)){
				foreach ($field as $name => $item){


				}
			}
		}

		foreach ($widgets as $key => $value){
			if (array_map('is_array', $value)){
				foreach ($value as $item => $val){
					var_dump($key);
					var_dump($val);
					echo '--------------';
				}
			}
		}


		return self::$form;
	}

	public static function textInputField($name=null, $value = null, $class = 'validate',
	                                      $type = 'text')
	{
		$value = ($value)? ' value="' . $value . '"': '';
		$id = ($name)? ' id="' . $name . '"': '';
		$name = ($name)? ' name="' . $name . '"': '';
		$class = ($class)? ' class="' . $class . '"': ' class="' . $class . '"';
		$input = '<input ' . $id . ' type="' . $type . '"' . $name . $value . $class . '>';
		return $input;
	}

	public static function textAreaField($name=null, $class="materialize-textarea")
	{
		$id = ($name)? ' id="' . $name . '"': '';
		$class = ($class)? ' class="' . $class . '"': ' class="' . $class . '"';
		$textarea = '<textarea '. $id . $class . '></textarea>';
		return $textarea;
	}

	public static function selectField($name=null, array $choice = [], $value = 'Выберите значение', $class="")
	{
		$id = ($name)? ' id="' . $name . '"': '';
		$name = ($name)? ' name="' . $name . '"': '';
		$class = ($class)? ' class="' . $class . '"': '';
		$select = '<select '. $id . $name . $class . '>';
		if (!in_array($value, array_keys($choice))){
			$select .= '<option value="" disabled selected>Выберите значение</option>';
		} else {
			$select .= '<option value="' . $value .'" selected>' . $choice[$value] . '<option>';
		}
		foreach ($choice as $key => $item){
			$select .= ($key != $value)? '<option value="'. $key .'">' . $item . '<option>': '';
		}
//		var_dump($select .= '</select>');die;
		return $select .= '</select>';
	}

	public static function selectMultipleField($name=null, array $values = [], array $choices = [])
	{
		$start = "<div class='row card-panel'>
				      <div><h5>$name</h5></div>";
		$buttons = '<div class="col s2 center choice-group">
                    <button type="button" id="search_rightSelected" class="waves-effect waves-light btn">
                        <i class="material-icons">skip_next</i>
                    </button>
                    <button type="button" id="search_leftSelected" class="waves-effect waves-light btn">
                        <i class="material-icons">skip_previous</i>
                    </button>
                    <button type="button" id="search_rightAll" class="waves-effect waves-light btn">
                        <i class="material-icons">fast_forward</i>
                    </button>
                    <button type="button" id="search_leftAll" class="waves-effect waves-light btn">
                        <i class="material-icons">fast_rewind</i>
                    </button>
                </div>';
		$end = '</select>
                </div>';
		$select_left = '<div class="col s5">
                    <select name="from[]" id="search" class="select-filter browser-default" multiple>';
		$select_right = '<div class="col s5">
                    <select name="to[]" id="search_to" class="select-filter browser-default" multiple>';

		foreach ($values as $key => $value){
			if (!in_array($key, array_keys($choices))){
				$select_left .= '<option value="'. $key .'">' . $value . '<option>';

			} else {
				$select_right .= '<option value="'. $key .'">' . $choices[$key] . '<option>';

			}
		}
//		var_dump($select_left . $end);
//		echo '---------------------------';
//		var_dump($select_right . $end);
		$filter = $start . $select_left . $end . $buttons . $select_right . $end;
		return $filter . '</div>';
	}

}