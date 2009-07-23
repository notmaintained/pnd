<?php

	requires ('path', 'helpers');

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



	function route_match($routes, $request)
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

?>