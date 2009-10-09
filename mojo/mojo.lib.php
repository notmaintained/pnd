<?php

	//TODO: error_handler, restbase, 'emailmodule'
	requires ('helpers', 'request', 'response', 'route', 'template', 'form');


	map_request_to_handler(request_(), default_routes(), php_self_dir());


		function map_request_to_handler($request, $routes, $app_dir)
		{
			//TODO: request filters
			if ($matches = route_match($routes, $request)
				and ($handler_func = handler_func_exists($matches['handler'], $matches['func'], $app_dir)))
			{
				if (is_equal('home', $matches['func']))
				{
					$args = array($request);
				}
				elseif (in_array($matches['func'], array('show', 'save', 'delete')))
				{
					$args = array($matches['id'], $request);
				}
				elseif (is_equal('query', $matches['func']))
				{
					$args = array($request['query'], $request);
				}
				else
				{
					$args = array($request['form_data'], $request);
				}

				$response = call_user_func_array($handler_func, $args);
				//TODO: response filters
				flush_response($response);
			}
			//TODO: instead trigger http error and send to custom error handler?
			else flush_response(response_
			(
			    STATUS_NOT_FOUND,
			    array(),
			    'Not Found')
			); 
		}

			function handler_func_exists($handler, $func, $app_dir)
			{
				$handler_func = "{$handler}_{$func}";
				$handler_file = handler_file($handler, $app_dir);
				$non_handler_func = empty($handler);
				if (!$non_handler_func) require $handler_file;
				if (function_exists($handler_func)) return $handler_func;

				return false;
			}
			
				function handler_file($handler, $app_dir)
				{
					return $app_dir.'handlers'.DIRECTORY_SEPARATOR.$handler
					       .DIRECTORY_SEPARATOR."$handler.handler.php";
				}

?>