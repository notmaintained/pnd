<?php

/* apache.adapter.php
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
	{
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