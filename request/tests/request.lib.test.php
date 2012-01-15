<?php

	function test_valid_body_()
	{
        $data = 'hello world';
        should_return($data, when_passed($data));
        should_return(NULL, when_passed(false));
	}

?>