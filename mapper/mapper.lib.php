<?php

	function unsafe_swx_mapper_match()
	{
		$maps = func_get_args();
		$subject = array_shift($maps);

		foreach ($maps as $map)
		{
			$pattern = _unsafe_swx_mapper_pattern($map[0]);

			if (is_equal_(preg_match($pattern, $subject, $matches), 1))
			{
				return (isset($map[1])) ? array_merge($matches, $map[1]) : $matches;
			}
		}

		return array();
	}
	
		//TODO: convert all \{ and \} to \x00<curllystart>, \x00<curllyend>
		function _unsafe_swx_mapper_pattern($pattern)
		{
			$pattern = _swx_mapper_convert_optional_parts_to_regex($pattern);
			$pattern = _unsafe_swx_mapper_convert_named_parts_to_regex($pattern);
			$pattern = strtr($pattern, array('/' => '\/'));
			return "/^$pattern\$/";
		}

			function _swx_mapper_convert_optional_parts_to_regex($pattern)
			{
				$optional_parts_pattern = '/\[([^\]\[]*)\]/';
				$replacement = '(\1)?';

				while (true)
				{
					$regex_pattern = preg_replace($optional_parts_pattern, $replacement, $pattern);
					if (!is_equal_($regex_pattern, $pattern)) 
					{
						$pattern = $regex_pattern;
					}
					else break;
				}
				
				return $pattern;
			}

			function _unsafe_swx_mapper_convert_named_parts_to_regex($pattern)
			{
				$named_parts = '/{([^}]*)}/';
				$pattern = preg_replace_callback($named_parts, '_unsafe_swx_mapper_named_part_replacement_callback', $pattern);
				return $pattern;
			}

				function _unsafe_swx_mapper_named_part_replacement_callback($matches)
				{
					return _swx_mapper_convert_named_part_filters_to_regex($matches, _unsafe_swx_mapper_named_part_filters());
				}

					function _swx_mapper_convert_named_part_filters_to_regex($matches, $filters)
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
	
					function _unsafe_swx_mapper_named_part_filters()
					{
						require dirname(__FILE__).DIRECTORY_SEPARATOR.'filters.config.php';
						return isset($filters) ? $filters : array();
					}

?>