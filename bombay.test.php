<?php

	// This file should exist even if it is empty. This is a hack to auto-include bombay.php so that requires() is available while running the tests.

	function test_array_stripslashes_()
	{
		should_return
		(
			array("f'oo", "b'ar", array("fo'o", "b'ar")),
			when_passed(array("f\\'oo", "b\\'ar", array("fo\\'o", "b\\'ar")))
		);
	}

?>