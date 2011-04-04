<?php

	require_once dirname(__FILE__).'/../bombay.php';
	//TODO: 'error'
	requires ('helpers', 'request', 'response', 'route', 'template', 'form', 'emailmodule', 'db', 'handler');


	function yield_to_glue() //should be called after all handle_* routes
	{
		map_request_to_handler(request_(), routes());
	}


	function map_request_to_handler($req, $routes)
	{
		if ($route = route_match($routes, $req))
		{
			$req['path'] = $route['path_matches'];
			exit_with_mojo_flush_response($req, next_func($req, $route['funcs']));
		}

		exit_with_404_plain('Not Found');
	}


	function next_func($req, $pipeline)
	{
		if (!empty($pipeline))
		{
			$next_func = array_shift($pipeline);

			list($handler, $func) = func_resolver($next_func);

			if (!is_callable($func)) handler_autoloader($handler);

			if (is_callable($func))
			{
				$response = call_user_func($func, $req, $pipeline);

				if (!isset($response['template']) and is_string($next_func))
				{
					$response['template'] = $next_func;
				}

				return $response;
			}
			else trigger_error("Required func ($next_func) not found.", E_USER_ERROR);
		}
		else trigger_error("No func!", E_USER_ERROR);

	}

		function func_resolver($func)
		{
			if (function_exists('resolve_func')) return resolve_func($func);
			if (is_object($func) and is_equal('Closure', get_class($func))) return array('', $func);
			return default_resolver($func);
		}

		function template_resolver($template)
		{
			if (function_exists('resolve_template')) return resolve_template($template);
			return default_resolver($template);

		}

			function default_resolver($str)
			{
				if (is_equal(false, strpos($str, '/'))) return array('', $str);
				return explode('/', $str, 2);
			}

		function handler_autoloader($handler)
		{
			if (function_exists('autoload_handler')) return autoload_handler($handler);

			$handler_file = handler_file($handler);
			if (file_exists($handler_file)) require_once $handler_file;;
		}


		function exit_with_mojo_flush_response($req, $response)
		{
			if (is_valid_response($response)) exit_with($response);
			exit_with(glue_response($req, $response));
		}


		function glue_response($req, $response)
		{
			$template_vars = is_array($response) ? array_merge($req, $response) : array_merge($req, array('content'=>$response));
			$headers = array_merge(array('content-type'=>'text/html'), response_headers($template_vars));

			if (isset($template_vars['template']))
			{
				list($handler, $template) = template_resolver($template_vars['template']);
				unset($template_vars['template']);
				//TODO: feels liks a ugly hack to assume func from template but works well for handler-less (template-only) routes
				if (!isset($template_vars['handler'])) $template_vars['handler'] = $handler;
				if (!isset($template_vars['func'])) $template_vars['func'] = $template;

				if (template_file_exists(handler_template($handler, $template)))
				{
					if (isset($template_vars['layout']))
					{
						$layout = $template_vars['layout'];
						unset($template_vars['layout']);

						if (is_equal(false, $layout))
						{
							return response_
							(
								response_status_code($template_vars),
								$headers,
								template_render(handler_template($handler, $template), $template_vars)
							);
						}
						else
						{
							list($layout_handler, $layout_template) = template_resolver($layout);
							return response_
							(
								response_status_code($template_vars),
								$headers,
								template_compose
								(
									handler_template($handler, $template),
									$template_vars,
									handler_template($layout_handler, $layout_template),
									$template_vars
								)
							);
						}
					}
					else
					{
						return response_
						(
							response_status_code($template_vars),
							$headers,
							template_compose
							(
								handler_template($handler, $template),
								$template_vars,
								handler_layout($handler),
								$template_vars
							)
						);
					}

				}
			}

			return _200_plain(print_r($response, true));
		}


	function default_handler($handler=NULL)
	{
		static $default_handler;
		if (is_null($handler) and isset($default_handler)) return $default_handler;
		return $default_handler = $handler;
	}

?>