<?php

	requires ('helpers', 'webserver');

	function map_requests_to_handlers($custom_routes=array())
	{
		$request = request_();
		$routes = routes_($custom_routes);
		if ($matches = matching_route_found($request, $routes)
		    and ($handler = handler_func_exists($matches, $request)))
		{
			$handler();
		}
		else send_404_response();
	}

		function request_()
		{
			return array('method' => request_method_(server_var('REQUEST_METHOD')),
			             'path'   => request_path_(webserver_specific('request_path')),
			             'query'  => $_GET,
			             'headers'=> webserver_specific('request_headers'),
			             'body'   => request_body_(file_get_contents('php://input')));
		}

			function request_method_($method)
			{
				return strtoupper($method);
			}

			function request_path_($path)
			{
				return str_sanitize(rawurldecode('/'.ltrim($path, '/')));
			}

			function request_body_($body)
			{
				return empty($body) ? NULL : $body;
			}


		function routes_($custom_routes=array())
		{
			return array_merge($custom_routes, default_routes());
		}
		
			function default_routes()
			{
				if ($file = file_exists_(dirname(__FILE__).DIRECTORY_SEPARATOR.'routes.config.php'))
				{
					include $file;
				}

				return isset($routes) ? $routes : array();
			}



		function matching_route_found($request, $routes)
		{
			foreach ($routes as $route)
			{
				$path_pattern = path_pattern($route['path']);
				$request_query = !empty($request['query']);
				$route_query = (isset($route['query']) and $route['query']);
				//TODO handle missing keys in route

				if ((is_equal($request['method'], $route['method']) or is_equal('', $route['method']))
				    and is_equal(preg_match($path_pattern, $request['path'], $matches), 1)
				    and is_equal($request_query, $route_query))
				{
					return isset($route['defaults'])
					       ? array_merge($route['defaults'], $matches)
					       : $matches;
				}
			}
			
			return false;
		}
			//TODO: convert all \{ and \} to \x00<curllystart>, \x00<curllyend>
			function path_pattern($pattern)
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

		function handler_func_exists($matches)
		{
			$handler_func = $matches['handler'].'_'.$matches['func'];
			$handler_catchall = $matches['handler'].'_catchall_';
			$catchall = '_catchall_';
			//if (file_exists(handler_file())) include handler_file();
			if (function_exists($handler_func)) return $handler_func;
			if (function_exists($handler_catchall)) return $handler_catchall;
			if (function_exists($catchall)) return $catchall;
			return false;
		}


	function send_404_response()
	{
		echo 'Not Found';
	}


?>