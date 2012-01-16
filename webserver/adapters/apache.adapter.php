<?php

	requires ('uri');

	function apache_specific_is_rewrite_engine_on()
	{
		return _apache_specific_is_rewrite_engine_on(server_var('REWRITE_ENGINE'));
	}
		function _apache_specific_is_rewrite_engine_on($rewrite_engine)
		{
			return is_equal('on', strtolower($rewrite_engine));
		}


	function apache_specific_request_path()
	{
		if (!apache_specific_is_rewrite_engine_on()) return default_request_path();
		return _apache_specific_request_path(server_var('REQUEST_URI'), uri_path(server_var('PHP_SELF')));
	}
		function _apache_specific_request_path($request_uri, $path_to_index_dot_php)
		{
			$path = substr($request_uri, strlen($path_to_index_dot_php));
			list($path, ) = (str_contains('?', $path)) ? explode('?', $path, 2) : array($path, '');
			return $path;
		}


	function apache_specific_request_headers()
	{	//TODO: Do I need to strtolower() the apache_request_headers() keys cause default_request_headers() keys are lowercased?
		if (!function_exists('apache_request_headers')) return default_request_headers();
		return apache_request_headers();
	}


	function apache_specific_uri($base_uri, $path)
	{
		if (!apache_specific_is_rewrite_engine_on()) return default_uri($base_uri, $path);
		return _apache_specific_uri($base_uri, $path);

	}
		function _apache_specific_uri($base_uri, $path)
		{
			assert(substr($base_uri, -1) == '/');
			return $base_uri.ltrim($path, '/');
		}

?>