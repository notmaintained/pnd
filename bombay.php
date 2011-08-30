<?php

	define('BOMBAY_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);


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
					"Currently running PHP $php_version. Bombay requires at least PHP $min_php_version. Error triggered by Bombay",
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
		$libraries = func_get_args();

		foreach ($libraries as $library)
		{
			$library_file = BOMBAY_DIR.$library.DIRECTORY_SEPARATOR."$library.lib.php";
			if (!file_exists($library_file)) trigger_error_on_caller_("Requires non-existent library $library", E_USER_ERROR);
			require_once $library_file;
		}
	}
		function trigger_error_on_caller_($message, $level)
		{
			$stacktrace = debug_backtrace();
			$caller = $stacktrace[1];
			$triggered_by = $stacktrace[0];
			$me = __FUNCTION__;
			trigger_error("$message in {$stacktrace[1]['file']} on line {$stacktrace[1]['line']}. Error triggered by {$stacktrace[1]['function']}() in {$stacktrace[0]['file']} on line {$stacktrace[0]['line']} from {$me}()", $level);
		}

?>