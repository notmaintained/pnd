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
		$str = preg_replace('/^[^a-zA-Z0-9]+/', '', trim($str));
		return preg_replace('/[^a-zA-Z0-9]/', '_', trim($str));
	}


	function str_hyphenate($str)
	{
		$str = preg_replace('/^[^a-zA-Z0-9]+/', '', trim($str));
		return preg_replace('/[^a-zA-Z0-9]/', '-', trim($str));
	}


	function str_humanize($str)
	{
		return strtr(trim($str), array('-'=>' ', '_'=>' '));
	}

	//TODO: This is a hack. Make it more comprehensive.
	function str_singularize($str)
	{
		if (is_equal('s', substr($str, -1))) return substr($str, 0, -1);
	}


//TODO: this is more of a XSS sanitizer so shud this be renamed?
	function str_sanitize($str, $translate_quotes=true)
	{
		return $translate_quotes ? htmlspecialchars($str, ENT_QUOTES) :
		                           htmlspecialchars($str, ENT_NOQUOTES);
	}


	function filerialize_var($var_name, $var)
	{
		$var_str = var_export($var, true);
		return "<?php\n\n\$$var_name = $var_str;\n\n?>";
	}


	function is_equal($var1, $var2)
	{
		return ($var1 === $var2);
	}


	function server_var($key, $sanitize=true)
	{
		$val = NULL;

		if (isset($_SERVER[$key]))
		{
			$val = $_SERVER[$key];
		}
		elseif (isset($_ENV[$key]))
		{
			$val = $_ENV[$key];
		}
		elseif ($env_val = getenv($key))
		{
			$val = $env_val;
		}

		if (is_null($val)) return $val;
		return $sanitize ? str_sanitize($val) : $val;
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
		$backtrace = debug_backtrace();
		$php_self = array_pop($backtrace);
		$php_self_dir = dirname($php_self['file']).DIRECTORY_SEPARATOR;
		return $php_self_dir;
	}


	function str_random_alphanum($length=10)
	{
		$aZ09_without_similar_chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$len = strlen($aZ09_without_similar_chars) - 1;
		$random_alphanum = '';

		for($i=0; $i < $length; $i++)
		{
				$random_alphanum .= $aZ09_without_similar_chars[mt_rand(0, $len)];
		}

		return $random_alphanum;
	}


 ?>