<?php

	requires ('path', 'helpers', 'request');

	//TODO: route_run($route_name, $request=array(), ...)
	// intercept_[all|get|post|head|...]

	function routes($route=NULL, $reset=false)
	{
		static $routes = array();

		if ($reset) return $routes = array();
		if (is_null($route)) return $routes;

		$routes[] = $route;
		return $routes;
	}


	function named_routes($name=NULL, $path=NULL)
	{
		static $named_routes = array();

		if (is_null($name) and is_null($path)) return $named_routes;
		if (is_null($path)) return isset($named_routes[$name]) ? $named_routes[$name] : false;

		$named_routes[$name] = $path;
		return $named_routes;
	}


	function route_pipeline($route=NULL)
	{
		static $routes = array();
		if (is_null($route)) return array_shift($routes);
		$routes[] = $route;
		return $routes;
	}


	function handle_all($path)
	{
		handle_route('*', $path, array(), array_slice(func_get_args(), 1));
	}


	function handle_head($path)
	{
		handle_route('HEAD', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_get($path)
	{
		handle_route('GET', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_query($path)
	{
		handle_route('GET', $path, array('query'=>true), array_slice(func_get_args(), 1));
	}

	function handle_post($path)
	{
		handle_route('POST', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_post_action($path, $action)
	{
		handle_route('POST', $path, array('action'=>$action), array_slice(func_get_args(), 2));
	}

		function handle_route($method, $paths, $conds, $funcs)
		{
			if (!is_array($paths)) $paths = array($paths);
			foreach ($paths as $key=>$val) if (!is_int($key)) named_routes($key, $val);
			$route = compact('method', 'paths', 'conds', 'funcs');
			routes($route);
			if ($matched_route = route_match($route, request_()))
			{
				foreach($funcs as $func) route_pipeline(array($func, $matched_route['path_matches']));
			}
		}




	function route_match($route, $request)
	{
		$method_matches = (is_equal($request['method'], $route['method']) or is_equal('*', $route['method']));

		foreach ($route['paths'] as $path)
		{
			if ($path_matches = path_match($path, $request['path'], $matches)) break;
		}


		if (isset($route['conds']['action']) and
		   (!isset($request['form']['action']) or
		   !is_equal ($route['conds']['action'], strtolower(str_underscorize($request['form']['action'])))))
		{
			$action_matches = false;
		}
		else $action_matches = true;


		if (isset($route['conds']['query']) and is_equal(true, $route['conds']['query']) and empty($request['query']))
		{
			$query_matches = false;
		}
		else $query_matches = true;


		if ($method_matches and $path_matches and $action_matches and $query_matches)
		{
			$route['path_matches'] = $matches;

			return	$route;
		}
	}

?>