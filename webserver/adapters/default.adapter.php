<?php

/* default.adapter.php
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
			return str_sanitize($path); //TODO: if $_GET is sanitized we cud remove this!
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
					$headers[$header] = $value; //TODO: Need to sanitize but can't use str_sanitize cause it encodes ' and " which might be common in headers
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