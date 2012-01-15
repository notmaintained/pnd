<?php

	require_once dirname(__FILE__).'/../bombay.php';
	requires ('path', 'helpers', 'request', 'response');


	function route_($method, $paths, $conds, $func)
	{
		return compact('method', 'paths', 'conds', 'func', 'path_matches');
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
			foreach ($paths as $key=>$val) if (!is_int($key)) named_paths_($key, $val);
			foreach($funcs as $func) routes_(route_($method, $paths, $conds, $func));
		}

			function named_paths_($name=NULL, $path=NULL, $reset=false)
			{
				static $named_routes = array();

				if ($reset) return $named_routes = array();
				if (is_null($name) and is_null($path)) return $named_routes;
				if (is_null($path)) return isset($named_routes[$name]) ? $named_routes[$name] : false;

				$named_routes[$name] = $path;
				return $named_routes;
			}

			function routes_($route=NULL, $reset=false)
			{
				static $routes = array();

				if ($reset) return $routes = array();
				if (is_null($route)) return $routes;

				$routes[] = $route;
				return $routes;
			}


	function next_func()
	{
		$args = func_get_args();
		$route = next_route_match_($args[0], $matches);

		if (!is_null($route))
		{
			if (is_callable($route['func']))
			{
				$response = call_user_func_array($route['func'], array_merge(array($args, $matches)));
			}
			else trigger_error_("Invalid func ({$route['func']}).", E_USER_ERROR);

			return $response;
		}

		//TODO: this should be overridable by the user
		exit_with_404_plain('Not Found');

	}

		function next_route_match_($req, &$matches)
		{
			static $routes; if (!isset($routes)) $routes = routes_();

			while ($route = array_shift($routes) and ($matched_route = route_match_($route, $req, $matches)))
			{
				return $matched_route;
			}
		}

			function route_match_($route, $req, &$matches=NULL)
			{
				$method_matched = (is_equal($req['method'], $route['method']) or is_equal('*', $route['method']));

				foreach ($route['paths'] as $path)
				{
					if ($path_matched = path_match($path, $req['path'], $matches)) break;
				}

				$action_cond_failed = (isset($route['conds']['action']) and
				                      (!isset($req['form']['action']) or
									   !is_equal ($route['conds']['action'], strtolower(str_underscorize($req['form']['action'])))));

				$query_cond_failed = (isset($route['conds']['query']) and
				                      is_equal(true, $route['conds']['query']) and
									  empty($req['query']));

				if ($method_matched and $path_matched and !$action_cond_failed and !$query_cond_failed)
				{
					return	$route;
				}
			}


	function path_match_include($path, $file)
	{
		$req = request_();
		if (path_match($path, $req['path'])) include $file;
	}

?>