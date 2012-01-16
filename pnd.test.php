<?php

	function test_array_stripslashes_()
	{
		should_return
		(
			array("f'oo", "b'ar", array("fo'o", "b'ar")),
			when_passed(array("f\\'oo", "b\\'ar", array("fo\\'o", "b\\'ar")))
		);
	}

?>