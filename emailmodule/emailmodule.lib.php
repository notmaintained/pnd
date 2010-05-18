<?php

	function emailmodule_sendmail($from, $to, $subject, $message)
	{
		$additional_headers = "From: $from";
		return mail($to, $subject, $message, $additional_headers);
	}

?>