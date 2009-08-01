<?php

	function slashes_to_directory_separator($path)
	{
		return preg_replace('/[\/\\\]/', DIRECTORY_SEPARATOR, $path);
	}


	function str_contains($needle, $haystack)
	{
		return (strpos($haystack, $needle) !== false);
	}


	function str_underscorize($str)
	{
		return preg_replace('/[^a-zA-Z0-9]/', '_', $str);
	}


	function str_sanitize($str)
	{
		return htmlspecialchars($str, ENT_QUOTES);
	}


	function is_equal($var1, $var2)
	{
		return ($var1 == $var2);
	}


	function server_var($key)
	{
		if (isset($_SERVER[$key]))
		{
			return $_SERVER[$key];
		}
		elseif (isset($_ENV[$key]))
		{
			return $_ENV[$key];
		}
		elseif ($val = getenv($key))
		{
			return $val;
		}

		return NULL;
	}


	function file_exists_($file_path)
	{
		return file_exists($file_path) ? $file_path : false;
	}


	function function_exists_($func_name)
	{
		return function_exists($func_name) ? $func_name : false;
	}


	function php_self_dir()
	{
		static $php_self_dir;
		if (isset($php_self_dir)) return $php_self_dir;
		$php_self = array_pop(debug_backtrace());
		$php_self_dir = dirname($php_self['file']).DIRECTORY_SEPARATOR;
		return $php_self_dir;
	}


	function array_keys_exist($keys, $search_array)
	{
		foreach ($keys as $key)
		{
			if (!array_key_exists($key, $search_array))
			{
				return false;
			}
		}

		return true;
	}

 ?>