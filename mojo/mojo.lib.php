<?php

	//TODO: db, error_handler
	requires ('helpers', 'request', 'response', 'route', 'template', 'form');


	map_request_to_handler(request_(), default_routes(), php_self_dir());


		function map_request_to_handler($request, $routes, $app_dir)
		{//TODO: _method hack
			if ($matches = route_match($routes, $request)
				and ($handler_func = handler_func_exists($matches['handler'], $matches['func'], $app_dir)))
			{
				$params = request_params($matches, $request);
				forward($handler_func, $params, $request);
			}
			//TODO: instead trigger http error and send to custom error handler?
			else flush_response(response_(STATUS_NOT_FOUND,
			                              array(),
			                              'Not Found')); 
		}

			function handler_func_exists($handler, $func, $app_dir)
			{
				$handler_func = "{$handler}_{$func}";
				$handler_catchall = "{$handler}_catchall";
				$global_catchall = '_catchall';
				$handler_file = handler_file($handler, $app_dir);

				if (!empty($handler)) require $handler_file;

				if (function_exists($handler_func)) return $handler_func;
				if (function_exists($handler_catchall)) return $handler_catchall;
				if (function_exists($global_catchall)) return $global_catchall;

				return false;
			}
			
				function handler_file($handler, $app_dir)
				{
					return $app_dir.'handlers'.DIRECTORY_SEPARATOR.$handler
					       .DIRECTORY_SEPARATOR."$handler.handler.php";
				}

			function request_params($matches, $request)
			{
				unset($matches['handler']);
				unset($matches['func']);
				if (is_equal('GET', $request['method']))
				{
					return array_merge($matches, $request['query']);
				}
				elseif (is_equal('POST', $request['method']))
				{
					return array_merge($matches, $request['form_data']);
				}
				
				return $matches;
			}

			function forward($handler_func, $params, $request)
			{
				$app_dir = php_self_dir(); //TODO: separate query from command
				$referer = calling_function(debug_backtrace());
				$wrappers = wrappers($app_dir);
				foreach ($wrappers as $wrapper)
				{
					$wrapper_name = array_shift($wrapper);
					$wrapper_func = "{$wrapper_name}_wrapper";
					$wrapper_around = $wrapper['around'];
					$is_self = is_equal($wrapper_func, $referer);
					$wraps_handler_func = (is_equal($handler_func, $wrapper_around)
					                      or is_equal('*', $wrapper_around));
					if ($wraps_handler_func and !$is_self)
					{
						require wrapper_file($wrapper_name, $app_dir);
						return $wrapper_func($handler_func, $params, $request);
					}
				}
				
				return $handler_func($params, $request);
			}

				function calling_function($debug_backtrace)
				{
					return $debug_backtrace[1]['function'];
				}

				function wrappers($app_dir='')
				{
					static $wrappers_conf;

					if (isset($wrappers_conf)) return $wrappers_conf;

					if ($wrapper_conf_file = file_exists_(wrappers_conf_file($app_dir)))
					{
						require $wrapper_conf_file;
						if (isset($wrappers))
						{
							return $wrappers_conf = $wrappers;
						}
					}
					
					return array();
				}

				function wrappers_conf_file($app_dir)
				{
					return $app_dir.'wrappers'.DIRECTORY_SEPARATOR."wrappers.config.php";
				}

				function wrapper_file($wrapper, $app_dir)
				{
					return $app_dir.'wrappers'.DIRECTORY_SEPARATOR."$wrapper.wrapper.php";
				}

?>