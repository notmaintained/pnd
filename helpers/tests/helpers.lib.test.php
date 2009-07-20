<?php

	function test_slashes_to_directory_separator()
	{
		should_return(DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR, when_passed('/test/'));
		should_return(DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR, when_passed('\\test\\'));
	}


	function test_str_contains()
	{
		should_return(true, when_passed('/', 'test/test'));
		should_return(false, when_passed('/', 'testtest'));
	}


	function test_str_underscorize()
	{
		should_return('A_e_I_o_U_9_', when_passed('A!e@I#o%U&9-'));
	}


	function test_str_sanitize()
	{
		should_return('&lt;script type=&quot;javascript&quot;&gt;alert(&quot;hello &amp; welcome&quot;)&lt;/script&gt;', when_passed('<script type="javascript">alert("hello & welcome")</script>'));
	}


	function test_is_equal()
	{
		should_return(true, when_passed(true, 1));
		should_return(false, when_passed(true, 0));
	}


	function test_server_var()
	{
		should_return($_SERVER['PHP_SELF'], when_passed('PHP_SELF'));
		should_return(NULL, when_passed('FOO_BAR_BAZ'));

		unset($_SERVER['REMOTE_ADDR']);
		should_return(getenv('REMOTE_ADDR'), when_passed('REMOTE_ADDR'));

		$env_var = 'FOO'.rand();
		putenv("$env_var=bar");
		should_return('bar', when_passed($env_var));
	}


	function test_file_exists_()
	{
		should_return(__FILE__, when_passed(__FILE__));
		should_return(false, when_passed(__FILE__.rand()));
	}


	function test_function_exists_()
	{
		should_return(__FUNCTION__, when_passed(__FUNCTION__));
		should_return(false, when_passed(__FUNCTION__.rand()));
	}


	function test_array_keys_exist()
	{
		should_return(true, when_passed(array('key1', 'key2'), array('key1'=>'value', 'key2'=>'value', 'key3'=>'value')));
		should_return(false, when_passed(array('key1', 'key4'), array('key1'=>'value', 'key2'=>'value', 'key3'=>'value')));
	}

?>