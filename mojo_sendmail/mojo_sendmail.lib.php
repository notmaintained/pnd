<?php

	function mojo_sendmail($handler, $email, $resource)
	{
		require_once handler_email_file($handler, php_self_dir());
		$args = func_get_args();
		$handler_email_func_args = array_slice($args, 2);
		$handler_email_func = "{$handler}_{$email}_email";
		$params = call_user_func_array($handler_email_func, $handler_email_func_args);
		 // TODO: is this all I need to send to the email template?
		$template_vars = array('resource'=>$resource, 'params'=>$params);
		if (isset($params['message'])) $message = $params['message'];
		else $message = template_compose(handler_email_template($handler, $email), $template_vars,
		                                 handler_email_layout($handler), $template_vars);

		return emailmodule_sendmail($params['from'], $params['to'], $params['subject'], $message);
	}

		function handler_email_file($handler, $app_dir)
		{
			return $app_dir.'handlers'.DIRECTORY_SEPARATOR.$handler.DIRECTORY_SEPARATOR."$handler.email.php";
		}

//TODO: The fact that for the app level ones you have to pass an empty string for the handler param in mojo_sendmail sucks
		function handler_email_template($handler, $email)
		{
			return empty($handler) ? "email/$email.txt" : "$handler/email/$email.txt";
		}

		function handler_email_layout($handler)
		{
			if (!empty($handler) and template_file_exists("$handler/email/layout.txt"))
			{
				return "$handler/email/layout.txt";
			}
//TODO: sucks that this cannot be email/layout cause then we cannot have a handler called email - maybe I could name it to _email
			return "email_layout.txt";
		}

?>