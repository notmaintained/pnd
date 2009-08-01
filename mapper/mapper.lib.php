<?php

	requires ('route');

	function map_request_to_handler($request, $routes, $handlers_dir)
	{
		if ($matches = route_match($routes, $request)
		    and ($handler_func = handler_func_exists($matches, $handlers_dir)))
		{
			$handler_func($request);
		}
		else send_404_response();
	}

		function handler_func_exists($matches, $handlers_dir)
		{
			$handler = $matches['handler'];
			$func = $matches['func'];
			$handler_func = "{$handler}_{$func}";
			$handler_catchall = "{$handler}_catchall";
			$catchall = '_catchall';
			$handler_file = handler_file($handler, $handlers_dir);
			if (file_exists($handler_file)) include $handler_file;
			if (function_exists($handler_func)) return $handler_func;
			if (function_exists($handler_catchall)) return $handler_catchall;
			if (function_exists($catchall)) return $catchall;

			return false;
		}
		
			function handler_file($handler, $handlers_dir)
			{
				return "{$handlers_dir}{$handler}".DIRECTORY_SEPARATOR."$handler.handler.php";
			}


	function send_404_response()
	{
		echo 'Not Found';
	}


?>