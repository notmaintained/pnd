<?php

	function test_request_method_()
	{
		should_return('PUT', when_passed('put'));
	}

	function test_request_path_()
	{
		should_return('/foo', when_passed('foo'));
		should_return('/foo', when_passed('/foo'));
		should_return('/foo bar', when_passed('/foo bar'));
	}


	function test_request_body_()
	{
        $data = 'hello world';
        should_return($data, when_passed($data));
        should_return(NULL, when_passed(''));
	}

?>