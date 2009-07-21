<?php

/* uri.core.php
 *
 * function email($str){return preg_replace('/^(.*)\/(.*)/', '$2@$1', $str);}
 * Authors: Sandeep Shetty email('gmail.com/sandeep.shetty')
 *
 * Copyright (C) 2005 - date('Y') Collaboration Science,
 * http://collaborationscience.com/
 *
 * This file is part of Swx.
 *
 * Swx is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * Swx is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 * 
 * To read the license please visit http://www.gnu.org/copyleft/gpl.html
 *
 *
 *-------10--------20--------30--------40--------50--------60---------72
 */


	define('SCHEME_', uri_scheme(server_var('HTTPS')));
	define('HOST_', uri_host(server_var('HTTP_HOST')));
	define('PORT_', uri_port(server_var('HTTP_HOST')));
	define('PATH_', uri_path(server_var('PHP_SELF')));
	define('ABSOLUTE_BASE_URI_', uri_absolute_base(SCHEME_, HOST_, PORT_, PATH_));
	define('RELATIVE_BASE_URI_', uri_relative_base(PATH_));


		function uri_scheme($https)
		{
			$ssl = !is_null($https) and is_equal('on', $https);
			$scheme = $ssl ? 'https' : 'http';
			return $scheme;
		}


		function uri_host($http_host)
		{
			if (str_contains(':', $http_host)) $host = array_shift(explode(':', $http_host));
			else $host = $http_host;
			return str_sanitize($host);
		}


		function uri_port($http_host)
		{
			if (!str_contains(':', $http_host)) return '';
			else $port = array_pop(explode(':', $http_host));
			return str_sanitize($port);
		}


		function uri_path($index_dot_php_path)
		{
			$base_path = dirname($index_dot_php_path);
			$base_path_equals_directory_separator = (is_equal(strlen($base_path), 1) and is_equal(DIRECTORY_SEPARATOR, $base_path));

			return $base_path_equals_directory_separator ? '' : str_sanitize($base_path);
		}


		function uri_absolute_base($inertia_scheme, $inertia_host, $inertia_port, $inertia_path)
		{
			$port = empty($inertia_port) ? '' : ":$inertia_port";
			$base_uri = "$inertia_scheme://$inertia_host$port$inertia_path/";
			return str_sanitize($base_uri);
		}

		
		function uri_relative_base($inertia_path)
		{
			return "$inertia_path/";
		}



	//TODO: shud take $query, $fragment - for all *uri_ functions below
	function inertia_absolute_uri($path=NULL)
	{
		$base_uri = uri_absolute_base(SCHEME_, HOST_, PORT_, PATH_);
		return webserver_specific('uri_', $base_uri, $path);
	}

	function inertia_relative_uri($path=NULL)
	{
		$base_uri = uri_relative_base(PATH_);
		return webserver_specific('uri_', $base_uri, $path);
	}

?>