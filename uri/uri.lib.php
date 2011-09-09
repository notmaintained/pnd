<?php

	requires ('helpers', 'webserver');


	define('URI_SCHEME', uri_scheme(server_var('HTTPS')));
	define('URI_HOST', uri_host(server_var('HTTP_HOST')));
	define('URI_PORT', uri_port(server_var('HTTP_HOST')));
	define('URI_PATH', uri_path(server_var('PHP_SELF')));
	define('URI_ABSOLUTE_BASE', uri_absolute_base(URI_SCHEME, URI_HOST, URI_PORT, URI_PATH));
	define('URI_RELATIVE_BASE', uri_relative_base(URI_PATH));
	define('URI_SECURE_ABSOLUTE_BASE', uri_absolute_base('https', URI_HOST, URI_PORT, URI_PATH));


		function uri_scheme($https)
		{
			$ssl = !is_null($https) and is_equal('on', $https);
			$scheme = $ssl ? 'https' : 'http';
			return $scheme;
		}


		function uri_host($http_host)
		{
			list($host, ) = explode(':', $http_host, 2);
//TODO: This feels half baked. Need to put some more thought into this...
			if(!preg_match('@^([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9]+$@', $host))
				die(trigger_error("Invalid or malicious host detected in HTTP_HOST: ".str_sanitize($http_host), E_USER_ERROR));

			return $host;
		}


		function uri_port($http_host)
		{
			if (str_contains(':', $http_host))
			{
				list(, $port) = explode(':', $http_host, 2);
				if(!preg_match('/^[0-9]+$/', $port))
					die(trigger_error("Invalid or malicious port detected in HTTP_HOST: ".str_sanitize($http_host), E_USER_ERROR));
			}
			else $port = '';

			return $port;
		}


		function uri_path($path_to_index_dot_php)
		{
			$base_path = dirname($path_to_index_dot_php);
			$base_path_equals_directory_separator = (is_equal(strlen($base_path), 1) and is_equal(DIRECTORY_SEPARATOR, $base_path));

			return $base_path_equals_directory_separator ? '' : $base_path;
		}


		function uri_absolute_base($scheme, $host, $port, $path)
		{
			$port = empty($port) ? '' : ":$port";
			$base_uri = "$scheme://$host$port$path/";
			return $base_uri;
		}


		function uri_relative_base($path)
		{
			return "$path/";
		}



	//TODO: shud take $query, $fragment - for all *_uri functions below
	function absolute_uri($path=NULL)
	{
		return webserver_specific('uri', URI_ABSOLUTE_BASE, $path);
	}

	function secure_absolute_uri($path=NULL)
	{
		return webserver_specific('uri', URI_SECURE_ABSOLUTE_BASE, $path);
	}

	function relative_uri($path=NULL)
	{
		return webserver_specific('uri', URI_RELATIVE_BASE, $path);
	}

?>