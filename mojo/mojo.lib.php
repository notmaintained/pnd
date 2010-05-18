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
				$id = isset($matches['id']) ? $matches['id'] : '';

				$response = array();

				if ($handler_func = handler_func_exists($handler, $func, $app_dir))
				{
					$response = call_user_func_array($handler_func, handler_args($func, $id, $request));
					if (headers_sent()) exit;
				}

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


			function handler_func_exists($handler, $func, $app_dir)
			{
				$handler_func = strtolower("{$handler}_{$func}");
				if (function_exists($handler_func)) return $handler_func;

				if (!empty($handler))
				{
					$handler_file = handler_file($handler, $app_dir);
					if (file_exists($handler_file)) require $handler_file;
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
				if (in_array($func, array('home', 'catchall'))) return array($request);
				elseif (in_array($func, array('show', 'save', 'delete'))) return array($id, $request);
				elseif (is_equal('query', $func)) return array($request['query'], $request);
				else return array($request['form_data'], $request);
			}


			function mojo_flush_response($handler, $func, $request, $response)
			{
				if (template_file_exists(handler_template($handler, $func)) and
					template_file_exists(handler_layout($handler)))
				{
					$request_vars = array('request'=>$request);
					$response = empty($response) ? array() : $response;
					$template_vars = array_merge($response, $request_vars);
					exit_with_ok_html(template_compose(handler_template($handler, $func), $template_vars,
													   handler_layout($handler), $template_vars));

				}
				elseif (!empty($response))
				{
					exit_with_ok_html(print_r($response, true));
				}
			}


			function handler_template($handler, $func)
			{
				return empty($handler) ? "$func.html" : "$handler/$func.html";
			}

			function handler_layout($handler)
			{
				if (!empty($handler) and template_file_exists("$handler/layout.html"))
				{
					return "$handler/layout.html";
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