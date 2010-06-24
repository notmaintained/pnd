<?php

/* uri.core.php
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

	requires ('helpers', 'webserver');


	define('URI_SCHEME', uri_scheme(server_var('HTTPS')));
	define('URI_HOST', uri_host(server_var('HTTP_HOST')));
	define('URI_PORT', uri_port(server_var('HTTP_HOST')));
	define('URI_PATH', uri_path(server_var('PHP_SELF')));
	define('URI_ABSOLUTE_BASE', uri_absolute_base(URI_SCHEME, URI_HOST, URI_PORT, URI_PATH));
	define('URI_RELATIVE_BASE', uri_relative_base(URI_PATH));


		function uri_scheme($https)
		{
			$ssl = !is_null($https) and is_equal('on', $https);
			$scheme = $ssl ? 'https' : 'http';
			return $scheme;
		}


		function uri_host($http_host)
		{
			$pieces = explode(':', $http_host);
			if (str_contains(':', $http_host)) $host = array_shift($pieces);
			else $host = $http_host;
			return $host;
		}


		function uri_port($http_host)
		{
			$pieces = explode(':', $http_host);
			if (str_contains(':', $http_host)) $port = array_pop($pieces);
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

	function relative_uri($path=NULL)
	{
		return webserver_specific('uri', URI_RELATIVE_BASE, $path);
	}

?>