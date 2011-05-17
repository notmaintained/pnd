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


	function handle_head($path)
	{
		handle_('HEAD', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_get($path)
	{
		handle_('GET', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_query($path)
	{
		handle_('GET', $path, array('query'=>true), array_slice(func_get_args(), 1));
	}

	function handle_post($path)
	{
		handle_('POST', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_post_action($path, $action)
	{
		handle_('POST', $path, array('action'=>$action), array_slice(func_get_args(), 2));
	}


		function handle_($method, $paths, $conds, $funcs)
		{
			routes(compact('method', 'paths', 'conds', 'funcs'));
		}


	function route_match($routes, $request)
	{
		foreach ($routes as $route)
		{
			$method_matches = (is_equal($request['method'], $route['method']) or is_equal('', $route['method']));

			foreach ($route['paths'] as $path)
			{
				if ($path_matches = path_match($path, $request['path'], $matches)) break;
			}

			if (isset($route['conds']['action']) and !isset($request['form']['action']))
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
		}

		return false;
	}

		function valid_action($action)
		{

			$action = strtolower(str_underscorize($action));
			$valid_php_function_name = '/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/';

			if (preg_match($valid_php_function_name, $action))
			{
				return $action;
			}
		}

?>