<?php

	requires ('helpers', 'webserver');


	function request_()
	{
		return array('method'    => request_method_(server_var('REQUEST_METHOD')),
		             'path'      => request_path_(webserver_specific('request_path')),
		             //TODO: sanitize these!
		             'query'     => $_GET,
		             'form_data' => $_POST,
		             'headers'   => webserver_specific('request_headers'),
		             'body'      => request_body_(file_get_contents('php://input')));
	}

		function request_method_($method)
		{
			return strtoupper($method);
		}

		function request_path_($path)
		{
			return str_sanitize(rawurldecode('/'.ltrim($path, '/')));
		}

		function request_body_($body)
		{
			return empty($body) ? NULL : $body;
		}

?>