<?php

	require_once dirname(__FILE__).'/../bombay.php';
	requires ('str');

	function varialize($var_name, $var)
	{
		$var_str = var_export($var, true);
		return "<?php\n\n\$$var_name = $var_str;\n\n?>";
	}

	function unvarialize_file($file, $var_name, $default=NULL)
	{
		include $file;
		return isset($$var_name) ? $$var_name : $default;
	}

//remove this?
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

		if (!is_null($val)) return $sanitize ? str_xss_sanitize($val) : $val;
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

	function func_returning()
	{
		$arg = func_get_arg(0);
		return function () use ($arg) { return $arg; };
	}

 ?>