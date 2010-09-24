<?php

	requires ('path', 'helpers');


	function route_match($routes, $request)
	{
		foreach ($routes as $route)
		{
			//TODO: handle missing keys in route
			$request_query = !empty($request['query']);
			$route_query = isset($route['query']) ? $route['query'] : false;
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

	function default_routes()
	{
		if ($file = file_exists_(dirname(__FILE__).DIRECTORY_SEPARATOR.'default_routes.conf.php'))
		{
			include $file;
		}

		return isset($routes) ? $routes : array();
	}

	function post_action()
	{
		if (isset($_POST['action']))
		{
			$action = str_underscorize($_POST['action']);
			$valid_php_function_name = '/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

			if (preg_match($valid_php_function_name, $action))
			{
				return strtolower($action);
			}
		}

		return 'post';
	}

?>