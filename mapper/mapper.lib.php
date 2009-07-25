<?php

	requires ('route');

	function map_request_to_handler($request, $routes)
	{
		if ($matches = route_match($routes, $request)
		    and ($handler_func = handler_func_exists($matches, $request)))
		{
			$handler_func();
		}
		else send_404_response();
	}

		function handler_func_exists($matches)
		{
			$handler_func = $matches['handler'].'_'.$matches['func'];
			$handler_catchall = $matches['handler'].'_catchall';
			$catchall = '_catchall';
			//if (file_exists(handler_file())) include handler_file();
			if (function_exists($handler_func)) return $handler_func;
			if (function_exists($handler_catchall)) return $handler_catchall;
			if (function_exists($catchall)) return $catchall;
			print_r(get_defined_vars());
			return false;
		}


	function send_404_response()
	{
		echo 'Not Found';
	}


?>