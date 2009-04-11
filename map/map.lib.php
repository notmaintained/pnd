<?php

	function swx_map($maps, $subject)
	{
		foreach ($maps as $map)
		{
			$pattern = swx_map_regex_pattern($map[0]);

			if (is_equal_(preg_match($pattern, $subject, $matches), 1))
			{
				return (isset($map[1])) ? array_merge($matches, $map[1]) : $matches;
			}
		}
	}
	
		//TODO: convert all \{ and \} to \x00<curllystart>, \x00<curllyend>
		function swx_map_regex_pattern($map_pattern)
		{
			$map_pattern = _swx_map_convert_optional_parts($map_pattern);
			$map_pattern = _swx_map_convert_names_parts($map_pattern);
			$map_pattern = strtr($map_pattern, array('/' => '\/'));
			return "/^$map_pattern\$/";
		}

			function _swx_map_convert_optional_parts($map_pattern)
			{
				$optional_parts_pattern = '/\[([^\]\[]*)\]/';
				$replacement = '(\1)?';

				while (true)
				{
					$pattern = preg_replace($optional_parts_pattern, $replacement, $map_pattern);
					if (!is_equal_($pattern, $map_pattern)) 
					{
						$map_pattern = $pattern;
					}
					else break;
				}
				
				return $pattern;
			}

			function _swx_map_convert_names_parts($map_pattern)
			{
				$named_parts = '/{([^}]*)}/';
				$pattern = preg_replace_callback($named_parts, '_swx_replacement_callback', $map_pattern);
				return $pattern;
			}
				function _swx_replacement_callback($matches)
				{
					return _swx_map_expand_named_part_filters($matches, _swx_map_named_part_filters());
				}
	
					function _swx_map_named_part_filters()
					{
						require dirname(__FILE__).DIRECTORY_SEPARATOR.'filters.config.php';
						return isset($filters) ? $filters : array();
					}

				function _swx_map_expand_named_part_filters($matches, $filters)
				{
					if (str_contains_(':', $matches[1]))
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

?>