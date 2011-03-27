<?php

	//TODO: 'error'
	requires ('helpers', 'request', 'response', 'route', 'template', 'form', 'emailmodule', 'mojo_sendmail', 'db', 'handler');

	map_request_to_handler(request_(), app_or_default_routes(), php_self_dir());


	function map_request_to_handler($request, $routes, $app_dir)
	{
		if ($route_matches = route_match($routes, $request))
		{
			$request['path'] = $route_matches;
			$handler = $route_matches['handler'];
			$func = $route_matches['func'];
			if ($handler_file = file_exists_(handler_file($handler))) require_once $handler_file;
			$response = array();

			$pipeline = mojo_filters($handler, $func, $routes, $app_dir);
			$response = next_filter($request, $pipeline);

			mojo_flush_response($handler, $func, $request, $route_matches, $response, $app_dir);
		}

		exit_with_404_plain('Not Found');
	}


		function app_or_default_routes()
		{
			$app_routes = app_routes(php_self_dir());
			return empty($app_routes) ? default_routes() : $app_routes;
		}
			function app_routes($app_dir)
			{
				if ($app_routes = file_exists_($app_dir.'routes.conf.php')) include $app_routes;
				return isset($routes) ? $routes : array();
			}

		function mojo_filters($handler, $func, $routes, $app_dir)
		{
			$handler_func_filters = route_filters($handler, $func, $routes);
			if ($handler_func = handler_func_exists($handler, $func))
			{
				array_push($handler_func_filters, $handler_func);
			}
			return $handler_func_filters;

		}

		function next_filter($request, $pipeline)
		{
			if (!empty($pipeline))
			{
				$next_filter = array_shift($pipeline);
				if (function_exists($next_filter))
				{
					return empty($pipeline) ? $next_filter($request) : $next_filter($request, $pipeline);
				}
				else trigger_error("Required filter ($next_filter) not found.", E_USER_ERROR);
			}

		}

		function mojo_flush_response($handler, $func, $request, $matches, $response, $app_dir)
		{
			if (is_valid_response($response)) exit_with($response);

			if (template_file_exists(handler_template($handler, $func)) and template_file_exists(handler_layout($handler)))
			{
				$request_vars = array('request'=>$request);
				$response = empty($response) ? array() : $response;
				$template_vars =  is_array($response) ? array_merge($response, $request_vars, $matches) : array_merge(array('content'=>$response), $request_vars, $matches);

				exit_with
				(
					response_status_code($response),
					array_merge(array('content-type'=>'text/html'), response_headers($response)),
					template_compose
					(
						handler_template($handler, $func),
						$template_vars,
						handler_layout($handler),
						$template_vars
					)
				);
			}
			elseif (!empty($response))
			{
				exit_with_200_plain(print_r($response, true));
			}
		}

?>