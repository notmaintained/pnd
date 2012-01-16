<?php

	require 'validators.php';


	function form_field($field_type,
	                    $field_name,
	                    $overridden_validators=array(),
	                    $additional_filters=array(),
	                    $overridden_error_msgs=array())
	{
		$field = field_types($field_type);

		$required = array('required'=>true);
		$field['validators'] = isset($field['validators']) ? array_merge($required, $field['validators']) : array();

		$field['validators'] = isset($field['validators']) ?
		                             array_merge($field['validators'], $overridden_validators) :
		                             $overridden_validators;

		$field['filters'] = isset($field['filters']) ?
		                          array_merge_recursive($field['filters'], $additional_filters) :
		                          $additional_filters;

		$field['error_msgs'] = isset($field['error_msgs']) ?
		                             array_merge($field['error_msgs'], $overridden_error_msgs) :
		                             $overridden_error_msgs;

		$field['name'] = $field_name;

		return $field;
	}

		function field_types($type)
		{
			static $field_types;

			if (!isset($field_types))
			{
				require 'field_types.config.php';
			}

			return isset($field_types[$type]) ? $field_types[$type] : array();
		}


	function form_validate($form_data, $form_fields)
	{
		$form = array();

		foreach ($form_fields as $field=>$field_info)
		{
			$value = form_array_value($field, $form_data);
			if (!is_equal(false, $value))
			{
				$value = trim($value);

				$value = form_field_apply_before_filters($value, $field_info);
				$is_invalid_field = form_validate_field($value, $field_info);
				$value = form_field_apply_after_filters($value, $field_info);

				$form['names'][$field] = $field_info['name'];
				$form['xss-safe'][$field] = htmlentities($value, ENT_QUOTES);

				if ($is_invalid_field)
				{
					$form['errors'][$field] = $is_invalid_field;
				}
				else
				{
					$form['valid'][$field] = $value;
				}
			}
		}

		return $form;
	}

		function form_array_value($index, $arr)
		{
			if (!$pos=strpos($index, '['))
			{
				if (array_key_exists($index, $arr))	return $arr[$index];
				else return false;
			}
			else
			{
				$key = substr($index, 0, $pos);
				$index = substr($index, $pos);
				$arr = $arr[$key];
				if (preg_match_all('/\[([^\]]*)\]/', $index, $matches))
				{
					$keys = $matches[1];
					foreach($keys as $key)
					{
						if (array_key_exists($key, $arr)) $arr = $arr[$key];
						else $arr = false;
					}

					return $arr;
				}
			}
		}

		function form_field_apply_before_filters($field_value, $field_info)
		{
			return form_field_filter('before', $field_value, $field_info);
		}

		function form_field_apply_after_filters($field_value, $field_info)
		{
			return form_field_filter('after', $field_value, $field_info);
		}

			function form_field_filter($filter_type, $field_value, $field_info)
			{
				if (isset($field_info['filters'][$filter_type]))
				{
					foreach ($field_info['filters'][$filter_type] as $filter)
					{
						if (is_array($filter))
						{
							$filter_func = array_shift($filter);
							$filter_params = array_merge(array($field_value), $filter);
							$field_value = call_user_func_array($filter_func, $filter_params);
						}
						else $field_value = $filter($field_value);
					}
				}

				return $field_value;
			}

		function form_validate_field($field_value, $field_info)
		{
			$field_errors = array();

			if (isset($field_info['validators']))
			{
				foreach ($field_info['validators'] as $validator=>$validator_param)
				{
					$validator_func = "validate_$validator";

					$error_msg = isset($field_info['error_msgs'][$validator]) ?
					             $field_info['error_msgs'][$validator] :
					             form_validation_error_msgs($validator);

					if($validator_func($field_value, $validator_param) !== true)
					{
						$field_errors[] = strtr($error_msg, array('%validator_param'=>$validator_param,
						                                          '%field_value'=>$field_value,
						                                          '%field_name'=>$field_info['name']));
					}
				}
			}

			return $field_errors;

		}


	function form_has_errors($form)
	{
		return isset($form['errors']);
	}

	function form_errors($form)
	{
		return isset($form['errors']) ? $form['errors'] : NULL;
	}

	function form_field_name($field, $form)
	{
		return isset($form['names'][$field]) ? $form['names'][$field] : NULL;
	}

	function form_field_values($form)
	{
		return isset($form['valid']) ? $form['valid'] : NULL;
	}

	function form_field_value($field, $form)
	{
		return isset($form['valid'][$field]) ? $form['valid'][$field] : NULL;
	}

	function form_xss_safe_field_value($field, $form)
	{
		return isset($form['xss-safe'][$field]) ? $form['xss-safe'][$field] : NULL;
	}

	//TODO: form_error_message_for($field, $form)

?>