<?php

	require_once dirname(__FILE__).'/../bombay.php';
	//TODO: 'error'
	requires ('helpers', 'request', 'response', 'route', 'template', 'form', 'emailmodule', 'db', 'handler');


	function yield_to_glue() //should be called after all handle_* routes
	{
		exit_with_glue_flush_response(request_(), next_func());
	}


	function next_func()
	{
		static $req; if (!isset($req)) $req = request_();
		$next = route_pipeline()
		$args = func_get_args();

		if (!empty($next))
		{
			list($next_func, $path_matches) = $next;
			$req['path_matches'] = $path_matches;

			$func = $next_func;
			if ( (is_object($func) and is_equal('Closure', get_class($func))) or ($func = handler_func_exists($next_func)))
			{
				$response = call_user_func($func, array_merge(array($req), $args));
			}
			else trigger_error("Required func ($next_func) not found.", E_USER_ERROR);

			if (!empty($response) and !isset($response['template']) and is_string($next_func))
			{
				$response['template'] = $next_func;
			}

			return $response;
		}

		//TODO: this should be overridable by the app to match its 404 page
		exit_with_404_plain('Not Found');

	}


		function exit_with_glue_flush_response($req, $response)
		{
			if (empty($response)) return;
			elseif (is_valid_response($response)) exit_with($response);
			exit_with(glue_response($req, $response));
		}


		function glue_response($req, $response)
		{
			$template_vars = is_array($response) ? array_merge(compact('req'), $response) : array_merge(compact('req'), array('content'=>$response));
			$headers = array_merge(array('content-type'=>'text/html'), response_headers($response));

			if (isset($template_vars['template']))
			{
				$handler_template = $template_vars['template'];
				list($handler, $template) = handler_func_resolver($handler_template);
				unset($template_vars['template']);
				//TODO: feels liks a ugly hack to assume func from template but works well for handler-less (template-only) routes
				if (!isset($template_vars['handler'])) $template_vars['handler'] = $handler;
				if (!isset($template_vars['func'])) $template_vars['func'] = $template;

				if (template_file_exists(handler_template($handler_template)))
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
								template_render(handler_template($handler_template), $template_vars)
							);
						}
						else
						{
							return response_
							(
								response_status_code($template_vars),
								$headers,
								template_compose
								(
									handler_template($handler_template),
									$template_vars,
									handler_template($layout),
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
								handler_template($handler_template),
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

?>