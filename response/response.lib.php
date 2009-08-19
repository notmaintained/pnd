<?php

/* response.lib.php
 *
 * function email($str){return preg_replace('/^(.*)\/(.*)/', '$2@$1', $str);}
 * Authors: Sandeep Shetty email('gmail.com/sandeep.shetty')
 *
 * Copyright (C) 2005 - date('Y') Collaboration Science,
 * http://collaborationscience.com/
 *
 * This file is part of Bombay.
 *
 * Bombay is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * Bombay is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 * 
 * To read the license please visit http://www.gnu.org/copyleft/gpl.html
 *
 *
 *-------10--------20--------30--------40--------50--------60---------72
 */

	requires ('helpers');


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


	function response_reason_phrase($status_code)
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
		$headers = (!is_array($headers)) ? array() : $headers;
		$headers = array_change_key_case($headers, CASE_LOWER);
		return array('status_code' => $status_code, 'headers' => $headers, 'body' => $body);
	}


	function flush_response($response)
	{
		$response = valid_response($response);
		$response = prepare_external_response($response);
		flush_http_status($response['status_code']);
		flush_headers($response['headers']);
		echo $response['body'];
	}


		function valid_response($response)
		{			
			if (!response_is_valid($response))
			{
				$response = response_(STATUS_OK, array(), $response);
			}

			return $response;
		}

			function response_is_valid($response)
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
			$reason_phrase = response_reason_phrase($status_code);
			header("HTTP/1.0 $status_code $reason_phrase");
		}

		function flush_headers($headers)
		{
			foreach ($headers as $field_name=>$field_value)
			{
				header("$field_name: $field_value");
			}
		}

?>