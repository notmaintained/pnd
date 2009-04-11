<?php

	if (!defined('INERTIA_INSTALL_DIR'))
	{
		function slashes_to_directory_separator_($path)
		{
			return preg_replace('/[\/\\\]/', DIRECTORY_SEPARATOR, $path);
		}


		function str_contains_($needle, $haystack)
		{
			return (strpos($haystack, $needle) !== false);
		}


		function str_underscorize_($str)
		{
			return preg_replace('/[^a-zA-Z0-9]/', '_', $str);
		}


		function str_sanitize_($str)
		{
			return htmlspecialchars($str, ENT_QUOTES);
		}


		function is_equal_($var1, $var2)
		{
			return ($var1 == $var2);
		}


		function server_var_($key)
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


		function array_keys_exists_($keys, $search_array)
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
	}

 ?>