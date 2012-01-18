<?php

	requires ('helper');


	define('STATUS_CONTINUE', 100);
	define('STATUS_SWITCHING_PROTOCOLS', 101);
	define('STATUS_OK', 200);
	define('STATUS_CREATED', 201);
	define('STATUS_ACCEPTED', 202);
	define('STATUS_NON_AUTHORITATIVE_INFORMATION', 203);
	define('STATUS_NO_CONTENT', 204);
	define('STATUS_RESET_CONTENT', 205);
	define('STATUS_PARTIAL_CONTENT', 206);
	define('STATUS_MULTIPLE_CHOICES', 300);
	define('STATUS_MOVED_PERMANENTLY', 301);
	define('STATUS_FOUND', 302);
	define('STATUS_SEE_OTHER', 303);
	define('STATUS_NOT_MODIFIED', 304);
	define('STATUS_USE_PROXY', 305);
	define('STATUS_TEMPORARY_REDIRECT', 307);
	define('STATUS_BAD_REQUEST', 400);
	define('STATUS_UNAUTHORIZED', 401);
	define('STATUS_PAYMENT_REQUIRED', 402);
	define('STATUS_FORBIDDEN', 403);
	define('STATUS_NOT_FOUND', 404);
	define('STATUS_METHOD_NOT_ALLOWED', 405);
	define('STATUS_NOT_ACCEPTABLE', 406);
	define('STATUS_PROXY_AUTHENTICATION_REQUIRED', 407);
	define('STATUS_REQUEST_TIME_OUT', 408);
	define('STATUS_CONFLICT', 409);
	define('STATUS_GONE', 410);
	define('STATUS_LENGTH_REQUIRED', 411);
	define('STATUS_PRECONDITION_FAILED', 412);
	define('STATUS_REQUEST_ENTITY_TOO_LARGE', 413);
	define('STATUS_REQUEST_URI_TOO_LONG', 414);
	define('STATUS_UNSUPPORTED_MEDIA_TYPE', 415);
	define('STATUS_REQUESTED_RANGE_NOT_SATISFIABLE', 416);
	define('STATUS_EXPECTATION_FAILED', 417);
	define('STATUS_INTERNAL_SERVER_ERROR', 500);
	define('STATUS_NOT_IMPLEMENTED', 501);
	define('STATUS_BAD_GATEWAY', 502);
	define('STATUS_SERVICE_UNAVAILABLE', 503);
	define('STATUS_GATEWAY_TIME_OUT', 504);
	define('STATUS_HTTP_VERSION_NOT_SUPPORTED', 505);


	function response_reason_phrase_($status_code)
	{
		static $reason_phrases;

		if (empty($reason_phrases))
		{
			$reason_phrases = array(
				STATUS_CONTINUE => 'Continue',
				STATUS_SWITCHING_PROTOCOLS => 'Sitching Protocols',
				STATUS_OK => 'OK',
				STATUS_CREATED => 'Created',
				STATUS_ACCEPTED => 'Accepted',
				STATUS_NON_AUTHORITATIVE_INFORMATION => 'Non-Authoritative Information',
				STATUS_NO_CONTENT => 'No Content',
				STATUS_RESET_CONTENT => 'Reset Content',
				STATUS_PARTIAL_CONTENT => 'Partial Content',
				STATUS_MULTIPLE_CHOICES => 'Multiple Choices',
				STATUS_MOVED_PERMANENTLY => 'Moved Permanently',
				STATUS_FOUND => 'Found',
				STATUS_SEE_OTHER => 'See Other',
				STATUS_NOT_MODIFIED => 'Not Modified',
				STATUS_USE_PROXY => 'Use Proxy',
				STATUS_TEMPORARY_REDIRECT => 'Temporary Redirect',
				STATUS_BAD_REQUEST => 'Bad Request',
				STATUS_UNAUTHORIZED => 'Unauthorized',
				STATUS_PAYMENT_REQUIRED => 'Payment Required',
				STATUS_FORBIDDEN => 'Forbidden',
				STATUS_NOT_FOUND => 'Not Found',
				STATUS_METHOD_NOT_ALLOWED => 'Method Not Allowed',
				STATUS_NOT_ACCEPTABLE => 'Not Acceptable',
				STATUS_PROXY_AUTHENTICATION_REQUIRED => 'Proxy Authentication Required',
				STATUS_REQUEST_TIME_OUT => 'Request Time-out',
				STATUS_CONFLICT => 'Conflict',
				STATUS_GONE => 'Gone',
				STATUS_LENGTH_REQUIRED => 'Length Required',
				STATUS_PRECONDITION_FAILED => 'Precondition Failed',
				STATUS_REQUEST_ENTITY_TOO_LARGE => 'Request Entity Too Large',
				STATUS_REQUEST_URI_TOO_LONG => 'Request-URI Too Long',
				STATUS_UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Type',
				STATUS_REQUESTED_RANGE_NOT_SATISFIABLE => 'Requested range not satisfiable',
				STATUS_EXPECTATION_FAILED => 'Expectation Failed',
				STATUS_INTERNAL_SERVER_ERROR => 'Internal Server Error',
				STATUS_NOT_IMPLEMENTED => 'Not Implemented',
				STATUS_BAD_GATEWAY => 'Bad Gateway',
				STATUS_SERVICE_UNAVAILABLE => 'Service Unavailable',
				STATUS_GATEWAY_TIME_OUT => 'Gateway Time-out',
				STATUS_HTTP_VERSION_NOT_SUPPORTED => 'HTTP Version not supported'
			);
		}

		return isset($reason_phrases[$status_code]) ? $reason_phrases[$status_code] : '';
	}


	function response_($status_code, $headers=array(), $body='')
	{
		$headers = array_change_key_case($headers, CASE_LOWER);
		return compact('status_code', 'headers', 'body');
	}


	function exit_with_200_html($body)
	{
		exit_with(STATUS_OK, array('content-type'=>'text/html'), $body);
	}

	function exit_with_200_plain($body)
	{
		exit_with(STATUS_OK, array('content-type'=>'text/plain'), $body);
	}

	function exit_with_404_html($body)
	{
		exit_with(STATUS_NOT_FOUND, array('content-type'=>'text/html'), $body);
	}

	function exit_with_404_plain($body)
	{
		exit_with(STATUS_NOT_FOUND, array('content-type'=>'text/plain'), $body);
	}

	function exit_with_500_html($body)
	{
		exit_with(STATUS_INTERNAL_SERVER_ERROR, array('content-type'=>'text/html'), $body);
	}

	function exit_with_500_plain($body)
	{
		exit_with(STATUS_INTERNAL_SERVER_ERROR, array('content-type'=>'text/plain'), $body);
	}

	function exit_with_302_plain($url)
	{
		exit_with(STATUS_FOUND, array('location'=>$url, 'content-type'=>'text/plain'), '$url');
	}


	function _200_html($body)
	{
		return response_(STATUS_OK, array('content-type'=>'text/html'), $body);
	}

	function _200_plain($body)
	{
		return response_(STATUS_OK, array('content-type'=>'text/plain'), $body);
	}

	function _404_html($body)
	{
		return response_(STATUS_NOT_FOUND, array('content-type'=>'text/html'), $body);
	}

	function _404_plain($body)
	{
		return response_(STATUS_NOT_FOUND, array('content-type'=>'text/plain'), $body);
	}

	function _500_html($body)
	{
		return response_(STATUS_INTERNAL_SERVER_ERROR, array('content-type'=>'text/html'), $body);
	}

	function _500_plain($body)
	{
		return response_(STATUS_INTERNAL_SERVER_ERROR, array('content-type'=>'text/plain'), $body);
	}

	function _302_plain($url)
	{
		return response_(STATUS_FOUND, array('location'=>$url, 'content-type'=>'text/plain'), '$url');
	}

	function exit_with()
	{
		$params = func_get_args();

		if (is_equal(3, count($params)))
			$response = response_($params[0], $params[1], $params[2]);
		elseif (is_equal(1, count($params)))
			$response = $params[0];
		else $response = '';

		$response = valid_response($response);
		$response = prepare_external_response($response);
		flush_http_status($response['status_code']);
		flush_headers($response['headers']);
		echo $response['body'];
		exit;
	}

		function valid_response($response)
		{
			if (!is_valid_response($response))
			{
				$status_code = STATUS_OK;
				$headers = array();

				if (is_array($response))
				{
					if (isset($response['status_code']))
					{
						$status_code = $response['status_code'];
						unset($response['status_code']);
					}

					if (isset($response['headers']) and is_array($response['headers']))
					{
						$headers = $response['headers'];
						unset($response['headers']);
					}
				}

				$response = response_($status_code, $headers, $response);
			}

			return $response;
		}

			function is_valid_response($response)
			{
				return (is_array($response)
				        and isset($response['status_code'], $response['headers'], $response['body'])
				        and is_array($response['headers']));
			}

		function prepare_external_response($response)
		{
			if (!isset($response['headers']['content-type']))
			{
				if (is_string($response['body']) or is_null($response['body']))
				{
					$response['headers']['content-type'] = 'text/plain';
				}
				else
				{
					$response['body'] = serialize($response['body']);
					$response['headers']['content-type'] = 'application/x-serialized-php';
				}
			}

			return $response;
		}

		function flush_http_status($status_code)
		{
			$reason_phrase = response_reason_phrase_($status_code);
			header("HTTP/1.1 $status_code $reason_phrase");
		}

		function flush_headers($headers)
		{
			foreach ($headers as $field_name=>$field_value)
			{
				header("$field_name: $field_value");
			}
		}


	function response_status_code($response)
	{
		return isset($response['status_code']) ? $response['status_code'] : STATUS_OK;
	}

	function response_headers($response)
	{
		return (isset($response['headers']) and is_array($response['headers'])) ? $response['headers'] : array();
	}

	function response_body($response)
	{
		if (is_array($response) and isset($response['body']))
		{
			return $response['body'];
		}
		elseif (is_string($response))
		{
			$response;
		}
		else return '';
	}

?>