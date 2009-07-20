<?php

/* default.adapter.php
 *
 * function email($str){return preg_replace('/^(.*)\/(.*)/', '$2@$1', $str);}
 * Authors: Sandeep Shetty email('gmail.com/sandeep.shetty')
 *
 * Copyright (C) 2005 - date('Y') Collaboration Science,
 * http://collaborationscience.com/
 *
 * This file is part of Inertia.
 *
 * Inertia is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * Inertia is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 * 
 * To read the license please visit http://www.gnu.org/copyleft/gpl.html
 *
 *
 *-------10--------20--------30--------40--------50--------60---------72
 */


	function default_is_rewrite_engine_on_()
	{
		return false;
	}
	
	function default_request_path_()
	{
		return default_request_path_helper_($_GET);
	}
		function default_request_path_helper_($get)
		{
			$path = isset($get['path_']) ? $get['path_'] : '/';
			return $path;
		}


	function default_request_headers_()
	{
		extract_request_headers_($_SERVER);
	}
		function extract_request_headers_($server_vars)
		{
			$headers = array();
			foreach ($server_vars as $key=>$value)
			{
				if (preg_match('/^HTTP_(.*)/', $key, $matches))
				{
					$header = strtolower(strtr($matches[1], '_', '-'));
					$headers[$header] = $value;
				}
			}

			return $headers;
		}


	function default_uri_($base_uri, $path)
	{
		assert(substr($base_uri, -1) == '/');
		//$path = strtr($path, array('?'=>'&amp;'));
		$path = strtr($path, array('?'=>'&'));
		$path = '/'.ltrim($path, '/');
		return $base_uri."index.php?path_=$path";
	}

?>