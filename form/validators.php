<?php

	//TODO: min_length, max_length, max_digits, max_decimal_places, max_whole_digits,

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
		return $is_required ? !empty($field_value) : true;
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