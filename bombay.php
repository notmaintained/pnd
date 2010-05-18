<?php

/* bombay.php
 *
 *
 *
 * function email($str){return preg_replace('/^(.*)\/(.*)/', '$2@$1', $str);}
 * Authors: Sandeep Shetty email('gmail.com/sandeep.shetty')
 *
 * Copyright (C) 2005 - date('Y') Collaboration Science,
 * http://collaborationscience.com/
 *
 * This file is part of Bombay.
 *
 * Bombay is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * Bombay is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * To read the license please visit http://www.gnu.org/copyleft/gpl.html
 *
 *
 *-------10--------20--------30--------40--------50--------60---------72
 */


	define('BOMBAY_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);


	function requires()
	{
		$libraries = func_get_args();
		foreach ($libraries as $library)
		{
			$library_file = BOMBAY_DIR.$library.DIRECTORY_SEPARATOR."$library.lib.php";
			if (file_exists($library_file))
			{
				require_once $library_file;
			}
			else
			{
				trigger_error("Required library ($library) not found.", E_USER_ERROR);
			}
		}
	}


 	php_min_version_guard('4.3.0');
	fix_magic_quotes_gpc();


		function php_min_version_guard($min_php_version)
		{
			$is_min_php_version = (function_exists('version_compare')
								   and version_compare(PHP_VERSION,  $min_php_version, '>='));

			if (!$is_min_php_version)
			{
				echo sprintf("ERROR: Older version of PHP. Current version of PHP is %s Please upgrade to PHP %s.",
							 PHP_VERSION, $min_php_version);
				exit;
			}
		}


		function fix_magic_quotes_gpc()
		{
			$funcname = 'array_stripslashes';

			if (get_magic_quotes_gpc())
			{
				array_walk($_GET, $funcname);
				array_walk($_POST, $funcname);
				array_walk($_COOKIE, $funcname);
				array_walk($_REQUEST, $funcname);
			}
		}

			function array_stripslashes(&$value)
			{
				$value = is_array($value) ? array_walk($value, __FUNCTION__) : stripslashes($value);
			}

?>