<?php

	requires ('path', 'helpers');


	function route_match($routes, $request)
	{
		foreach ($routes as $route)
		{
			//TODO: handle missing keys in route
			$request_query = !empty($request['query']);
			$route_query = isset($route['query']) ? $route['query'] : false;
			$query_matches = is_equal($request_query, $route_query);

			$method_matches = (is_equal($request['method'], $route['method']) or is_equal('', $route['method']));

			$route_matches = path_match($route['path'], $request['path']);
			$path_matches = !is_equal($route_matches, false);

			$action = isset($request['form_data']['action']) ? valid_action($request['form_data']['action']) : '';
			$route_action = isset($route['action']) ? $route['action'] : '';
			$action_matches = is_equal($action, $route_action);

			if ($method_matches and $path_matches and $query_matches and $action_matches)
			{
				$route_matches = empty($route_matches) ? array() : $route_matches;
				return array_merge($route_matches, array('handler'=>$route['handler'], 'func'=>$route['func']));
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

	function valid_action($action)
	{

		$action = str_underscorize($action);
		$valid_php_function_name = '/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

		if (preg_match($valid_php_function_name, $action))
		{
			return strtolower($action);
		}

		return 'post';
	}


	function get_route($path, $handler, $func)
	{
		return array
		(
			'method'=>'GET',
			'path'=>$path,
			'handler'=>$handler,
			'func'=>$func
		);
	}

	function post_route($path, $action, $handler, $func)
	{
		return array
		(
			'method'=>'POST',
			'path'=>$path,
			'action'=>$action,
			'handler'=>$handler,
			'func'=>$func
		);
	}

?>