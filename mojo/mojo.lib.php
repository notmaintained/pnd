<?php

	//TODO: 'error_handler', 'restbase', 'emailmodule'
	requires ('helpers', 'request', 'response', 'route', 'template', 'form');


	map_request_to_handler(request_(), default_routes(), php_self_dir());

		//TODO: request (before route match) and response filters (before flush_response)
		function map_request_to_handler($request, $routes, $app_dir)
		{
			if ($matches = route_match($routes, $request))
			{
				$response = array();

				if ($handler_func = handler_func_exists($matches['handler'], $matches['func'], $app_dir))
				{
					$response = call_user_func_array($handler_func, handler_args($matches, $request));
				}

				if (template_file_exists(handler_template($matches)) )
				{
					$request_vars = array('request'=>$request);
					$template_vars = array_merge($response, $request_vars);
					flush_response(response_
					(
						STATUS_OK,
						array('content-type'=>'text/html'),
						template_compose(handler_template($matches), $template_vars, 'layout.html', $request_vars)
					)); 

				}
				else return;
			}


			flush_response(response_
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

			function handler_args($matches, $request)
			{
				if (is_equal('home', $matches['func'])) return array($request);
				elseif (in_array($matches['func'], array('show', 'save', 'delete'))) return array($matches['id'], $request);
				elseif (is_equal('query', $matches['func'])) return array($request['query'], $request);				
				else return array($request['form_data'], $request);
			}

			function handler_template($matches)
			{
				return empty($matches['handler']) ? "{$matches['func']}.html" :
				                                    "/{$matches['handler']}/{$matches['func']}.html";
			}

?>