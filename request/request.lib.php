<?php

	require_once dirname(__FILE__).'/../bombay.php';
	requires ('helpers', 'webserver');


	function request_($override=array())
	{
		static $request;

		if (!isset($request) or !empty($override))
		{
			$request = array
			(
				'method'=> isset($override['method']) ?  $override['method'] : method_hack_(strtoupper(server_var('REQUEST_METHOD')), $_POST),
				'path'=> @$override['path'] ?: rawurldecode('/'.ltrim(webserver_specific('request_path'), '/')),
				'query'=> @$override['query'] ?: $_GET,
				'form'=> @$override['form'] ?: $_POST,
				'server_vars'=> @$override['server_vars'] ?: $_SERVER,
				'headers'=> @$override['headers'] ?:  webserver_specific('request_headers'),
				'body'=> @$override['body'] ?:  valid_body_(file_get_contents('php://input'))
			);
		}

		return $request;
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