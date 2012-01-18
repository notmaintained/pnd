<?php

	require_once dirname(__FILE__).'/../pnd.php';
	//TODO: 'error'
	requires ('helper', 'request', 'response', 'handler', 'template');


	handle_all('.*', function ($req)
	{
		exit_with_glue_flush_response($req, next_handler($req));
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