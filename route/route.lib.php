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


	function handle_head()
	{
		handle_('HEAD', func_get_args());
	}

	function handle_get()
	{
		handle_('GET', func_get_args());
	}

	function handle_post()
	{
		handle_('POST', func_get_args());
	}


		function handle_($method, $args)
		{
			array_unshift($args, $method);
			$route = parse_route_params($args);
			route_($route['method'], $route['paths'], $route['funcs'], $route['conds']);
		}

			function parse_route_params($args)
			{
				if (count($args) < 3) return false;

				$route = array();
				$route['method'] = array_shift($args);

				$path = array_shift($args);
				$route['paths'] = (is_array($path)) ? $path : array($path);

				$conds = array();
				if (is_array(end($args))) $conds = array_pop($args);

				$route['funcs'] = array();
				foreach ($args as $arg)
				{
					if (is_array($arg))
					{
						foreach ($arg as $val)
						{
							$route['funcs'][] = $val;
						}
					}
					else $route['funcs'][] = $arg;
				}

				$route['conds'] = $conds;

				return $route;
			}

			function route_($method, $paths, $funcs, $conditions)
			{
				routes
				(
					array
					(
						'method'=>$method,
						'paths'=>$paths,
						'funcs'=>$funcs,
						'conds'=>$conditions
					)
				);
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

			if (isset($matches['conds']['action']) and isset($request['form']['action']))
			{
				$action_matches = is_equal($matches['conds']['action'], $request['form']['action']);
			}
			elseif (isset($matches['conds']['action']) and !isset($request['form']['action']))
			{
				$action_matches = false;
			}
			else $action_matches = true;

			if ($method_matches and $path_matches and $action_matches)
			{//$rpath_matches['0'] should be equal to 'foo' for '/foo/bar' and $rpath_matches['1'] should be 'bar'
				$route['path_matches'] = $matches;
				return	$route;
			}
		}

		return false;
	}


?>