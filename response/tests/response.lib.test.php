<?php

	function test_response_is_valid()
	{
		should_return(true, when_passed(array('status_code'=>'', 'headers'=>array(), 'body'=>'')));
		should_return(false, when_passed(array('status_code'=>'', 'headers'=>'', 'body'=>'')));
	}

?>