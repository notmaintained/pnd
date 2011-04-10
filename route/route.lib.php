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
				$route['conds'] = array();

				if (is_array($path))
				{
					foreach ($path as $key=>$val)
					{
						if (is_string($key))
						{
							$route['conds'][$key] = $val;
						}
						else
						{
							$route['paths'][$key] = $val;
						}
					}
				}
				else
				{
					$route['paths'] = array($path);
				}

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


			if (isset($route['conds']['action']) and isset($request['form']['action']))
			{
				$action_matches = is_equal($route['conds']['action'], valid_action($request['form']['action']));
			}
			elseif (isset($route['conds']['action']) and !isset($request['form']['action']))
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