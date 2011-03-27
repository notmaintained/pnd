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
			return handler_templates_dir($handler)."email/$email.txt";
		}

		function handler_email_layout($handler)
		{
			$handler_email_layout = handler_templates_dir($handler)."email/layout.txt";
			if (!empty($handler_email_layout) and template_file_exists($handler_email_layout)) return $handler_email_layout;
			else return handler_templates_dir('')."email/layout.html";
		}

?>