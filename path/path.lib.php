<?php

/* path.lib.php
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
 * Bombay is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * Bombay is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * To read the license please visit http://www.gnu.org/copyleft/gpl.html
 *
 *
 *----------------------------------------------------------------------------
 */


	function segment_count($path)
	{
		return ($sub_segments = _sub_segments($path, 0)) ? count($sub_segments) : 0;
	}


	function sub_path($path, $start, $length=NULL)
	{
		$slash = (is_equal(substr($path, 0, 1), '/')) ? '/' : '';
		return ($sub_segments = _sub_segments($path, $start, $length)) ? $slash.implode("/", $sub_segments) : false;
	}


	function sub_segment($path, $index=0)
	{
		if ($sub_segments = _sub_segments($path, $index, 1)) return $sub_segments[0];
		return false;
	}

		function _sub_segments($path, $start, $length=NULL)
		{
			$segments = explode('/', $path);
			if (is_equal(substr($path, 0, 1), '/')) array_shift($segments);
			$sub_segments = (is_null($length)) ? array_slice($segments, $start) : array_slice($segments, $start, $length);
			if (empty($sub_segments)) return false;
			return $sub_segments;
		}


	function path_match($path_pattern, $path, $defaults=array())
	{
		$pattern = path_pattern_to_pattern($path_pattern);
		if (is_equal(preg_match($pattern, $path, $matches), 1))
		{
			foreach ($matches as $key=>$val) { if (is_int($key)) { unset($matches[$key]); }}
			return array_merge($defaults, $matches);
		}
		else return false;
	}

		//TODO: convert all \{ and \} to \x00<curllystart>, \x00<curllyend>
		function path_pattern_to_pattern($pattern)
		{
			$pattern = convert_optional_parts_to_regex($pattern);
			$pattern = convert_named_parts_to_regex($pattern);
			$pattern = strtr($pattern, array('/' => '\/'));
			return "/^$pattern\$/";
		}

			function convert_optional_parts_to_regex($pattern)
			{
				$optional_parts_pattern = '/\[([^\]\[]*)\]/';
				$replacement = '(\1)?';

				while (true)
				{
					$regex_pattern = preg_replace($optional_parts_pattern, $replacement, $pattern);
					if (!is_equal($regex_pattern, $pattern)) 
					{
						$pattern = $regex_pattern;
					}
					else break;
				}
				
				return $pattern;
			}

			function convert_named_parts_to_regex($pattern)
			{
				$named_parts = '/{([^}]*)}/';
				$pattern = preg_replace_callback($named_parts, 'named_part_replacement_callback', $pattern);
				return $pattern;
			}

				function named_part_replacement_callback($matches)
				{
					return convert_named_part_filters_to_regex($matches, named_part_filters());
				}

					function convert_named_part_filters_to_regex($matches, $filters)
					{
						if (str_contains(':', $matches[1]))
						{
							list($subpattern_name, $pattern) = explode(':', $matches[1], 2);
							$pattern = isset($filters[$pattern]) ? $filters[$pattern] : $pattern;
							return "(?P<$subpattern_name>$pattern)";
						}
						else
						{
							return "(?P<{$matches[1]}>{$filters['segment']})";
						}
					}
	
					function named_part_filters()
					{
						require dirname(__FILE__).DIRECTORY_SEPARATOR.'filters.config.php';
						return isset($filters) ? $filters : array();
					}

?>