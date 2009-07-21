<?php

	requires ('helpers', 'webserver', 'path');

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
				//TODO handle missing keys in route
				$request_query = !empty($request['query']);
				$route_query = (isset($route['query']) and $route['query']);
				$method_matches = (is_equal($request['method'], $route['method']) or is_equal('', $route['method']));
				$defaults = isset($route['defaults']) ? $route['defaults'] : array();
				$path_matches = path_match($route['path'], $request['path'], $defaults);
				$query_matches = is_equal($request_query, $route_query);

				if ($method_matches and $path_matches and $query_matches)
				{
					return $path_matches;
				}
			}
			
			return false;
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