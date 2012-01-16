<?php

	define('PND_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);


 	php_min_version_guard_('5.3.0');
	fix_magic_quotes_gpc_();


		function php_min_version_guard_($min_php_version)
		{
			$is_min_php_version = (function_exists('version_compare') and version_compare(PHP_VERSION,  $min_php_version, '>='));

			if (!$is_min_php_version)
			{
				list($php_version, ) = explode('-', PHP_VERSION, 2);
				trigger_error
				(
					"Currently running PHP $php_version. Pnd requires at least PHP $min_php_version. Error triggered by Pnd",
					E_USER_ERROR
				);
			}
		}

		function fix_magic_quotes_gpc_()
		{
			if (get_magic_quotes_gpc())
			{
				list($_GET, $_POST, $_COOKIE, $_REQUEST) = array_stripslashes_(array($_GET, $_POST, $_COOKIE, $_REQUEST));
			}
		}

			function array_stripslashes_($value)
			{
				if (is_array($value)) return array_map(__FUNCTION__, $value);
				return stripslashes($value);
			}



	function requires()
	{
		$libs = func_get_args();

		foreach ($libs as $lib)
		{
			$lib_file = PND_DIR.$lib.DIRECTORY_SEPARATOR."$lib.php";
			if (!file_exists($lib_file)) trigger_error_("Requires non-existent lib $lib", E_USER_ERROR);
			require_once $lib_file;
		}
	}


	function trigger_error_($error, $level)
	{
		$stacktrace = debug_backtrace();
		$caller = $stacktrace[1];
		$triggered_by = $stacktrace[0];
		$me = __FUNCTION__;
		trigger_error("$error in {$caller['file']} on line {$caller['line']}. Error triggered by {$caller['function']}() in {$triggered_by['file']} on line {$triggered_by['line']} from {$me}()", $level);
	}

?>