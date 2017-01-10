<?php


namespace core\Admin;


use core\Exception;

class Form
{

	protected static $table_scheme = [];
	private static $model;



	/**
	 * Data: result from DB for this item
	 * @param array $data
	 * @param array $widgets
	 * @param array $fields
	 * @return string
	 */
	public static function buildForm(array $data, array $widgets = [], array $fields = [])
	{

		self::$model = Admin::getModel($_SERVER['REQUEST_URI']);
		foreach (self::$model->getTableScheme() as $item => $value){
			self::$table_scheme[$value['Field']] = $value;
		}

		if (empty($fields)) {
			$fieldset_name = ucfirst(explode('/', $_SERVER['REQUEST_URI'])[2]);
			$fields[$fieldset_name] = [];
			foreach (self::$table_scheme as $item => $value) {
				if ($item != 'id'){
					$fields[$fieldset_name][ucwords(implode(' ', explode('_', $value['Field'])))] = $value['Field'];
				}
			}
		}
		$output = [];
		$count = 0;
		foreach ($fields as $key => $field) {
			$output[$key] = [];
			if ($count == 0){
				array_push($output[$key], [
					'field' => self::textInputField('csrf_token', $_SESSION['csrf_token'], 'hidden'),
					'type' => 'hidden',
					'field_name' => 'csrf_token',
					'field_empty' => ''
				]);
			}
			if (isset($data['id'])){
				array_push($output[$key], [
					'field' => self::textInputField('id', $data['id'], 'hidden'),
					'type' => 'hidden',
					'field_name' => 'id',
					'field_empty' => ''
				]);
			}
			$count++;
			foreach ($field as $name => $db_name) {
				$fld = '';
				$label = '';
				$widget = '';

				if (isset($widgets['number']) && in_array($db_name, $widgets['number'])) {
					$fld = self::textInputField($db_name, $data[$db_name], 'number');
					$label = self::label($db_name, $name, (!empty($data[$db_name]))? 'active' : null);
					$type = 'number';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				} elseif (isset($widgets['datetype']) && in_array($db_name, $widgets['datetype'])){
					$fld = self::textInputField($db_name, $data[$db_name], 'date', "datepicker");
					$label = self::label($db_name, $name, (!empty($data[$db_name]))? 'active' : null);
					$type = 'data';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				} elseif (isset($widgets['textarea']) && in_array($db_name, $widgets['textarea'])){
					$fld = self::textAreaField($db_name, $data[$db_name]);
					$label = self::label($db_name, '', (!empty($data[$db_name]))? 'active' : null);
					$type = 'textarea';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				} elseif (isset($widgets['radio']) && in_array($db_name, array_keys($widgets['radio']))){
					$widget = self::radioWidget($db_name, $widgets['radio'][$db_name], $data[$db_name]);
					$type = 'radio';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				} elseif (isset($widgets['select']) && in_array($db_name, array_keys($widgets['select']))){
					$widget = self::selectField($db_name, $widgets['select'][$db_name], $data[$db_name]);
					$type = 'select';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				} elseif (isset($widgets['select_mul']) && in_array($db_name, array_keys($widgets['select_mul']))){
					$val = (!is_array($data[$db_name])) ? [$data[$db_name]] : $data[$db_name];
					$widget = self::selectMultipleField($db_name, $widgets['select_mul'][$db_name], $val);
					$type = 'select_mul';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				} elseif (isset($widgets['checkbox']) && in_array($db_name, array_keys($widgets['checkbox']))){
					$fld = self::checkBoxWidget($db_name, $data[$db_name]);
					$label = self::label($db_name, $name);
					$type = 'checkbox';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				} elseif (isset($widgets['switch']) && in_array($db_name, $widgets['switch'])){
					$widget = self::switchWidget($data[$db_name], $db_name);
					$type = 'switch';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				} elseif (isset($widgets['file']) && in_array($db_name, $widgets['file'])){
					$widget = self::fileField($db_name, $data[$db_name]);
					$type = 'file';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				} elseif (isset($widgets['password']) && in_array($db_name, $widgets['password'])) {
					$fld = self::textInputField($db_name, $data[$db_name], 'password');
					$label = self::label($db_name, $name, (!empty($data[$db_name]))? 'active' : null);
					$type = 'password';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;

				} else {
					$fld = self::textInputField($db_name, $data[$db_name], 'text');
					$label = self::label($db_name, $name, (!empty($data[$db_name]))? 'active' : null);
					$type = 'text';
					$f_name = $db_name;
					$p_name = $name;
					$blank = (self::$table_scheme[$db_name]['Null'] == 'NO') ? false : true;
				}
				$dt = (!empty($widget)) ? $widget : $fld . $label;
				array_push($output[$key],[
					'field' => $dt,
					'type' => $type,
					'placeholder' => $p_name,
					'field_name' => $f_name,
					'field_empty' => $blank
				]);
			}
		}
		return $output;
	}

	/**
	 * @param null $name
	 * @param null $value
	 * @param string $class
	 * @param string $type
	 * @return string
	 */
	public static function textInputField($name = null, $value = null, $type = 'text',
	                                      $class = null, $length = null)
	{
		$value_field = ($value) ? ' value="' . $value . '"' : '';
		$id_field = ($name) ? ' id="' . $name . '"' : '';
		$name_field = ($name) ? ' name="' . $name . '"' : '';
		$class_field = ($class) ? ' class="' . $class . '"' : ' class="validate"';
		$length_field = ((int)$length) ? ' length="' . $length . '"' : '';
		$step = ($type == 'number') ? ' step="0.01"' : '';
		$input = '<input ' . $id_field . ' type="' . $type . '"' .
			$name_field . $value_field . $class_field . $length_field . $step . '>';
		return $input;
	}

	/**
	 * @param null $name
	 * @param string $class
	 * @return string
	 */
	public static function textAreaField($name = null, $values = null, $class = null, $length = null)
	{
		$id_field = ($name) ? ' id="' . $name . '"' : '';
		$val = ($values) ? $values : '';
		$class_field = ($class) ? ' class="' . $class . '"' : ' class="materialize-textarea"';
		$length_field = ((int)$length) ? ' length="' . $length . '"' : '';
		$textarea = '<textarea name="' . $name . '"' . $id_field . $class_field . $length_field . '>' . $val . '</textarea>';
		return $textarea;
	}

	/**
	 * @param null $name
	 * @param array $choice
	 * @param string $value
	 * @param string $class
	 * @return string
	 */
	public static function selectField($name = null, array $choice = [], $value = 'Выберите значение', $class = "")
	{
		$id_field = ($name) ? ' id="' . $name . '"' : '';
		$name_field = ($name) ? ' name="' . $name . '"' : '';
		$class_field = ($class) ? ' class="' . $class . '"' : '';
		$select = '<select ' . $id_field . $name_field . $class_field . '>';
		if (!in_array($value, array_keys($choice))) {
			$select .= '<option value="" disabled selected>Выберите значение</option>';
		} else {
			$select .= '<option value="' . $value . '" selected>' . $choice[$value] . '</option>';
		}
		foreach ($choice as $key => $item) {
			$select .= ($key != $value) ? '<option value="' . $key . '">' . $item . '</option>' : '';
		}
		return $select .= '</select>';
	}

	/**
	 * @param null $name
	 * @param array $values
	 * @param array $choices
	 * @param string $class
	 * @return string
	 */
	public static function selectMultipleField($name = null, array $choices = [], array $values = [], $class = "")
	{
		$id_field = ($name) ? ' id="' . $name . '"' : '';
		$name_field = ($name) ? ' name="' . $name . '"' : '';
		$class_field = ($class) ? ' class="' . $class . '"' : '';
		$select = '<select ' . $id_field . $name_field . $class_field . ' multiple>';
		if (array_keys($choices) == array_diff(array_keys($choices), $values)) {
			$select .= '<option value="" disabled selected>Выберите значение</option>';
		}
		foreach ($choices as $key => $value) {
			$select .= (array_search($key ,$values) === false ) ? '<option value="' . $key . '" >' . $value . '</option>' :
				'<option value="' . $key . '" selected>' . $choices[$key] . '</option>';
		}
		return $select .= '</select>';
	}

	/**
	 * @param null $name
	 * @param array $values
	 * @param array $choices
	 * @return string
	 */
	public static function selectMultipleWidget($name = null, array $values = [], array $choices = [])
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

		foreach ($values as $key => $value) {
			if (!in_array($key, array_keys($choices))) {
				$select_left .= '<option value="' . $key . '">' . $value . '</option>';

			} else {
				$select_right .= '<option value="' . $key . '">' . $choices[$key] . '</option>';

			}
		}
		$filter = $start . $select_left . $end . $buttons . $select_right . $end;
		return $filter . '</div>';
	}

	/**
	 * @param string $btn_name
	 * @param null $value
	 * @return string
	 */
	public static function fileField($name = null, $value = null, $btn_name = 'Добавить')
	{
		$file = '<div class="btn">
        				<span>%s</span>
				        <input type="file">
				    </div>
	                <div class="file-path-wrapper">
	                    <input %s class="file-path validate %s type="text">
				 </div>';
		$value_field = ($value) ? 'valid" value="' . $value . '"' : '"';
		$name_field = ($name) ? '" name="' . $name . '"' : '"';
		$file = (sprintf($file, $btn_name, $name_field, $value_field));
		return $file;
	}

	/**
	 * @param null $name
	 * @param string $btn_name
	 * @param array|null $values
	 * @return string
	 */
	public static function fileMultipleField($name = null, $btn_name = 'Добавить', array $values = null)
	{
		$name_field = ($name) ? ' name="' . $name . '[]"' : '';
		$file = '<div class="file-field input-field">
      				<div class="btn">
        				<span>%s</span>
				        <input type="file" multiple>
				    </div>
	                <div class="file-path-wrapper">
	                    <input %s class="file-path validate %s type="text">
				    </div>
			    </div>';
		$value = ($values) ? 'valid" value="' . implode(', ', $values) . '"' : '"';
		$file = (sprintf($file, $btn_name, $name_field, $value));
		return $file;
	}

	/**
	 * @param null $name
	 * @param null $value
	 * @param string $class
	 * @return string
	 */
	public static function checkBoxWidget($name = null, $value = null, $class = "filled-in")
	{
		$id_field = ($name) ? ' id="' . $name . '"' : '';
		$name_field = ($name) ? ' name="' . $name . '"' : '';
		$class_field = ' class="' . $class . '"';
		$value_field = ($value) ? ' value="' . $value . '" checked="checked"' : '';
		$checkbox = '<input type="checkbox"' . $class_field . $id_field . $name_field . $value_field . '" />';
		return $checkbox;
	}

	/**
	 * @param null $name
	 * @param array $choice
	 * @param null $value
	 * @param string $class
	 * @return string
	 */
	public static function radioWidget($name = null, array $choice = [], $value = null, $class = "with-gap")
	{
		$class_field = ' class="' . $class . '"';
		$radio = '';
		foreach ($choice as $key => $item) {
			$radio .= '<div style="display: inline-block; padding-left: 5px">';
			if ($value == $item) {
				$value_field = ' value="' . $item . '" checked="checked"';
			} elseif ($value == $key) {
				$value_field = ' value="' . $key . '" checked="checked"';
			} else {
				$value_field = ' value="' . $key . '"';
			}
			$radio .= '<input name="' . $name . '"' . $class_field . $value_field . ' type="radio" id="' . $key . '" />';
			$radio .= '<label for="' . $key . '">' . $item . '</label>';
			$radio .= '</div>';
		}
		return $radio;
	}

	/**
	 * @param null $value
	 * @param null $name
	 * @param string $false
	 * @param string $true
	 * @return string
	 */
	public static function switchWidget($value = null, $name = null, $false = 'Нет', $true = 'Да')
	{
		$name = ($name) ? ' name="' . $name . '" ' : '';
		$value = ($value && $value == true || $value == 1) ? ' value="' . $value . '" checked ' : ' value="1" ';
		$switch = '
		    <div class="switch">
		    	<label>' .
			mb_strtoupper($false) . '
					<input type="hidden" ' . $name . ' value="0">
			        <input' . $value . ' type="checkbox"' . $name . '>
			        <span class="lever"></span>' .
			mb_strtoupper($true) . '
		        </label>
		    </div>';
		return $switch;
	}

	/**
	 * @param string $for
	 * @param string $name
	 * @return string
	 */
	public static function label($for = '', $name = '', $class = '')
	{
		$class = ($class) ? ' class="' . $class . '"' : '';
		return '<label for="' . $for . '"' . $class . '>' . $name . '</label>';
	}

	public static function validate(array $fields)
	{
		foreach ($fields as $field => $value){

			if ($field == 'csrf_token'){
				$token = $value;
				if ($token != $_SESSION[$field] && $token != $_COOKIE[$field]){
					throw new Exception('Sorry, you form token not valid');
					exit;
				}
				continue;
			}
			if ($field[0] == '_' || $field == 'id'){
				continue;
			}

			$type = explode('(', self::$table_scheme[$field]['Type'])[0];
			if ($type == 'date'){
				$arr_date = explode(' ', $value);
				if (count($arr_date) > 1){
					$month = rtrim($arr_date[1], ',');
					$true_date = $arr_date[2] . '-';
					switch ($month){
						case 'January':
							$true_date .= '1-';break;
						case 'February':
							$true_date .= '2-';break;
						case 'March':
							$true_date .= '3-';break;
						case 'April':
							$true_date .= '4-';break;
						case 'May':
							$true_date .= '5-';break;
						case 'June':
							$true_date .= '6-';break;
						case 'July':
							$true_date .= '7-';break;
						case 'August':
							$true_date .= '8-';break;
						case 'September':
							$true_date .= '9-';break;
						case 'October':
							$true_date .= '10-';break;
						case 'November':
							$true_date .= '11-';break;
						case 'December':
							$true_date .= '12-';break;
					}
					$true_date .= $arr_date[0];
					$_POST[$field] = $true_date;
				}

			} elseif ($type == 'tinyint' && isset($_POST[$field])){
				$_POST[$field] = (int)$_POST[$field];
			} elseif ($type == 'int') {
				$_POST[$field] = (int)$_POST[$field];
			} elseif ($type == 'varchar' || $type == 'longtext'){
				$_POST[$field] = (string)$_POST[$field];
			} elseif($type == 'double' || $type == 'decimal'){
				$_POST[$field] = (float)$_POST[$field];
			} elseif ($type == 'enum'){
				$_POST[$field] = (string)$_POST[$field];
			}
		}
	}

	public static function save()
	{
		self::$model = Admin::getModel($_SERVER['REQUEST_URI']);
		if (isset($_POST['_save'])){
			$redirect = self::$model->getAbsUrl() . $_POST['id'] . '/';
			Response::redirect($redirect);
		} elseif (isset($_POST['_save_exit'])){
			$redirect = self::$model->getAbsUrl();
			Response::redirect($redirect);
		}
	}
}