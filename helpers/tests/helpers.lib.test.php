<?php


	function test_is_equal()
	{
		should_return(true, when_passed(true,true));
		should_return(false, when_passed(true, 1));
		should_return(false, when_passed(false, 0));
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

?>