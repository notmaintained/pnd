<?php

	function test_handler_func_resolver()
	{
		should_return(array('', 'foo'), when_passed('foo'));
		should_return(array('foo', 'bar'), when_passed('foo/bar'));
		should_return(array('foo/bar', 'baz'), when_passed('foo/bar/baz'));
	}


	function test_handler_file()
	{
		should_return(handler_dir('foo')."foo.handler.php", when_passed('foo'));
		should_return(handler_dir('foo/bar')."bar.handler.php", when_passed('foo/bar'));
		should_return(handler_dir('foo/bar/baz')."baz.handler.php", when_passed('foo/bar/baz'));
	}

	function test_handler_dir()
	{
		should_return(php_self_dir().'handlers'.DIRECTORY_SEPARATOR.'foo'.DIRECTORY_SEPARATOR, when_passed('foo'));
	}

?>