<?php

	requires ('path', 'helpers', 'request');


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
			routes(compact('method', 'paths', 'conds', 'funcs'));
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


		if (isset($route['conds']['query']) and is_equal($route['conds']['query'], true) and isset($request['query']))
		{
			$query_matches = true;
		}
		else $query_matches = true;


		if ($method_matches and $path_matches and $action_matches and $query_matches)
		{//$rpath_matches['0'] should be equal to 'foo' for '/foo/bar' and $rpath_matches['1'] should be 'bar'
			$route['path_matches'] = $matches;

			return	$route;
		}

		return false;
	}

?>