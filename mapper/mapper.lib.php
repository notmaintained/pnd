<?php

	requires ('request', 'route');

	function map_requests_to_handlers($custom_routes=array())
	{
		$request = request_();
		$routes = routes_($custom_routes);
		if ($matches = route_match($routes, $request)
		    and ($handler = handler_func_exists($matches, $request)))
		{
			$handler();
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