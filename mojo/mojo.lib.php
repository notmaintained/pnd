<?php

	//TODO: 'db', 'error'
	requires ('helpers', 'request', 'response', 'route', 'template', 'form', 'emailmodule');


	map_request_to_handler(request_(), app_or_default_routes(), php_self_dir());


		function map_request_to_handler($request, $routes, $app_dir)
		{
			if ($matches = route_match($routes, $request))
			{
				$handler = $matches['handler'];
				$func = $matches['func'];

				$response = array();

				$pipeline = handler_filters($handler, $func, $app_dir);
				$response = next_filter($pipeline, $matches, $request);

				mojo_flush_response($handler, $func, $request, $response);
			}

			exit_with(response_
			(
				STATUS_NOT_FOUND,
				array(),
				'Not Found')
			);
		}


			function app_or_default_routes()
			{
				$app_routes = app_routes(php_self_dir());
				return empty($app_routes) ? default_routes() : $app_routes;
			}
				function app_routes($app_dir)
				{
					if ($app_routes = file_exists_($app_dir.'conf'.DIRECTORY_SEPARATOR.'routes.conf.php')) include $app_routes;
					return isset($routes) ? $routes : array();
				}

			function handler_filters($handler, $func, $app_dir)
			{
				if ($handler_file = file_exists_(handler_file($handler, $app_dir))) require_once $handler_file;

				if ($handler_filters_func = function_exists_("{$handler}_filters")) $handler_filters = $handler_filters_func();
				if ((!empty($handler_filters)) and isset($handler_filters[$func])) $handler_func_filters = $handler_filters[$func];
				else $handler_func_filters = array();
				$handler_func_filters = array_map('filter_func', $handler_func_filters);
				if ($handler_func = handler_func_exists($handler, $func, $app_dir))
				{
					array_push($handler_func_filters, 'invoke_handler_filter');
					array_push($handler_func_filters, $handler_func);
				}
				return $handler_func_filters;

			}
				function filter_func($filter)
				{
					return "{$filter}_filter";
				}

				function invoke_handler_filter($pipeline, $route_matches, $request)
				{
					$handler_func = array_shift($pipeline);
					$func = $route_matches['func'];
					$id = isset($route_matches['id']) ? $route_matches['id'] : '';
					return call_user_func_array($handler_func, handler_args($func, $id, $request));
				}

			function next_filter($pipeline, $route_matches, $request)
			{
				if (!empty($pipeline))
				{
					$next_filter = array_shift($pipeline);
					if (function_exists($next_filter)) return $next_filter($pipeline, $route_matches, $request);
					else trigger_error("Required filter ($next_filter) not found.", E_USER_ERROR);
				}
			}

			function handler_func_exists($handler, $func, $app_dir)
			{
				$handler_func = "{$handler}_{$func}";
				if (function_exists($handler_func)) return $handler_func;

				if (!empty($handler))
				{
					$handler_file = handler_file($handler, $app_dir);
					if (file_exists($handler_file)) require_once $handler_file;
					if (function_exists($handler_func)) return $handler_func;
				}

				return false;
			}
				function handler_file($handler, $app_dir)
				{
					return $app_dir.'handlers'.DIRECTORY_SEPARATOR.$handler
					       .DIRECTORY_SEPARATOR."$handler.handler.php";
				}


			function handler_args($func, $id, $request)
			{
				$id = (empty($id) and isset($request['form_data']['id'])) ? $request['form_data']['id'] : $id;
				if (in_array($func, array('home'))) return array($request);
				elseif (in_array($func, array('show', 'save', 'delete', 'catchall'))) return array($id, $request);
				elseif (in_array($func, array('query'))) return array($request['query'], $request);
				elseif (!empty($id)) return array($id, $request);
				elseif (!empty($request['form_data'])) return array($request['form_data'], $request);
				return array($request);
			}


			function mojo_flush_response($handler, $func, $request, $response)
			{
				if (template_file_exists(handler_template($handler, $func)) and
					template_file_exists(handler_layout($handler)))
				{
					$request_vars = array('request'=>$request);
					$response = empty($response) ? array() : $response;
					$template_vars =  is_array($response) ? array_merge($response, $request_vars) : array_merge(array('content'=>$response), $request_vars);
					exit_with_ok_html
					(
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
					exit_with_ok_plain(print_r($response, true));
				}
			}


			function handler_template($handler, $func)
			{
				return empty($handler) ? "$func.html" : "$handler/$handler.$func.html";
			}

			function handler_layout($handler)
			{
				if (!empty($handler) and template_file_exists("$handler/$handler.layout.html"))
				{
					return "$handler/$handler.layout.html";
				}

				return "layout.html";
			}


	function mojo_sendmail($handler, $email, $resource)
	{
		require_once handler_email_file($handler, php_self_dir());
		$args = func_get_args();
		$handler_email_func_args = array_slice($args, 2);
		$handler_email_func = "{$handler}_{$email}_email";
		$params = call_user_func_array($handler_email_func, $handler_email_func_args);
		 // TODO: is this all I need to send to the email template?
		$template_vars = array('resource'=>$resource);
		if (isset($params['message'])) $message = $params['message'];
		else $message = template_compose(handler_email_template($handler, $email), $template_vars,
		                                 handler_email_layout($handler), $template_vars);

		return emailmodule_sendmail($params['from'], $params['to'], $params['subject'], $message);
	}

		function handler_email_file($handler, $app_dir)
		{
			return $app_dir.'handlers'.DIRECTORY_SEPARATOR.$handler.DIRECTORY_SEPARATOR."$handler.email.php";
		}

//TODO: The fact that for the app level ones you have to pass an empty string for the handler param in mojo_sendmail sucks
		function handler_email_template($handler, $email)
		{
			return empty($handler) ? "email/$email.txt" : "$handler/email/$email.txt";
		}

		function handler_email_layout($handler)
		{
			if (!empty($handler) and template_file_exists("$handler/email/layout.txt"))
			{
				return "$handler/email/layout.txt";
			}
//TODO: sucks that this cannot be email/layout cause then we cannot have a handler called email - maybe I could name it to _email
			return "email_layout.txt";
		}

?>