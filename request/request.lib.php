<?php

	require_once dirname(__FILE__).'/../bombay.php';
	requires ('helpers', 'webserver');

	function request_()
	{
		static $request;

		if (!isset($request))
		{
			$request = array
			(
				'method'=>method_hack_(strtoupper(server_var('REQUEST_METHOD')), $_POST),
				'path'=>rawurldecode('/'.ltrim(webserver_specific('request_path'), '/')),
				'query'=>$_GET,
				'form'=>$_POST,
				'server_vars'=>$_SERVER,
				'headers'=>webserver_specific('request_headers'),
				'body'=>valid_body_(file_get_contents('php://input'))
			);
		}

		return $return;
	}

		function method_hack_($method, $form)
		{
			if (isset($form['_METHOD']) and ctype_alpha($form['_METHOD'])) return $form['_METHOD'];
			return $method;
		}

		function valid_body_($body)
		{
			return is_equal(false, $body) ? NULL : $body;
		}

?>