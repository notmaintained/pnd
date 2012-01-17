<?php

	define('PATH_IN_QUERY_HACK', '_path');

	function default_is_rewrite_engine_on()
	{
		return false;
	}

	function default_request_path()
	{
		return _default_request_path($_GET);
	}
		function _default_request_path($get)
		{
			$path = isset($get[PATH_IN_QUERY_HACK]) ? $get[PATH_IN_QUERY_HACK] : '/';
			return str_xss_sanitize($path); //TODO: if $_GET is sanitized we cud remove this!
		}


	function default_request_headers()
	{
		_extract_request_headers($_SERVER);
	}
		function _extract_request_headers($server_vars)
		{
			$headers = array();
			foreach ($server_vars as $key=>$value)
			{
				if (preg_match('/^HTTP_(.*)/', $key, $matches))
				{
					$header = strtolower(strtr($matches[1], '_', '-'));
					//TODO: Need to sanitize but can't use str_sanitize cause it encodes ' and " which might be common in headers
					$headers[$header] = $value;
				}
			}

			return $headers;
		}


	function default_uri($base_uri, $path)
	{
		assert(substr($base_uri, -1) == '/');
		//$path = strtr($path, array('?'=>'&amp;'));
		$path = strtr($path, array('?'=>'&'));
		$path = '/'.ltrim($path, '/');
		return $base_uri.'index.php?'.PATH_IN_QUERY_HACK."=$path";
	}

?>