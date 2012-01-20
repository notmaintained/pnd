<?php

	require_once dirname(__FILE__).'/../pnd.php';
	requires ('helper', 'webserver');

//TODO, maybe rename this to request cause it needs to be public to allow overriding the current request
	function request_($override=array())
	{
		static $request;

		if (!isset($request) or !empty($override))
		{
			$request = array
			(
				'method'=> array_val($override, 'method', strtoupper(server_var('REQUEST_METHOD'))),
				'path'=> array_val($override, 'path', rawurldecode('/'.ltrim(webserver_specific('request_path'), '/'))),
				'query'=> array_val($override, 'query', $_GET),
				'form'=> array_val($override, 'form', $_POST),
				'server_vars'=> array_val($override, 'server_vars', $_SERVER),
				'headers'=> array_val($override, 'headers', webserver_specific('request_headers')),
				'body'=> array_val($override, 'body', valid_body_(file_get_contents('php://input')))
			);
		}

		return $request;
	}

		function valid_body_($body)
		{
			return is_equal(false, $body) ? NULL : $body;
		}

?>