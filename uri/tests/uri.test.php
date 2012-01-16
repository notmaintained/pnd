<?php

	function test_uri_scheme()
	{
		should_return('http', when_passed(NULL));
		should_return('https', when_passed('on'));
	}


	function test_uri_host()
	{
		should_return('127.0.0.1', when_passed('127.0.0.1'));
		should_return('127.0.0.1', when_passed('127.0.0.1:80'));
	}


	function test_uri_port()
	{
		should_return('', when_passed('127.0.0.1'));
		should_return('80', when_passed('127.0.0.1:80'));
	}


	function test_uri_path()
	{
		should_return('/foobar', when_passed('/foobar/index.php'));
		should_return('', when_passed('/index.php'));
	}


	function test_uri_absolute_base()
	{
		should_return('http://example.com/foobar/', when_passed('http', 'example.com', '', '/foobar'));
		should_return('http://example.com:80/foobar/', when_passed('http', 'example.com', '80', '/foobar'));
		should_return('http://example.com/', when_passed('http', 'example.com', '', ''));
	}

	function test_uri_relative_base()
	{
		should_return('/', when_passed(''));
		should_return('/foobar/', when_passed('/foobar'));
	}

?>