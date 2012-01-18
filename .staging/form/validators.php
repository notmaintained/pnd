<?php

	//TODO: min_length, max_length, max_digits, max_decimal_places, max_whole_digits,

	function form_validation_error_msgs($validator)
	{
		$error_msgs = array
		(
			'except'=>'cannot be "%validator_param"',
			'matches'=>'is invalid',
			'required'=>'is required'
		);

		return isset($error_msgs[$validator]) ? $error_msgs[$validator] : $validator;
	}


	function validate_matches($field_value, $pattern)
	{
		return (preg_match("/^$pattern$/", $field_value) === 1) ? true : false;
	}

	function validate_except($field_value, $exception_str)
	{
		return ($exception_str != $field_value);
	}

	function validate_required($field_value, $is_required)
	{
		return $is_required ? !is_equal('', trim($field_value)) : true;
	}

	function validate_email($address, $level)
	{
		if ('basic' == $level)
		{
			$pos = strpos($address, '@');
			return ($pos > 0) and  (strpos(substr($address, $pos), '.') > 0) and (strlen($address) - $pos > 4);
		}

		return false;
	}

?>