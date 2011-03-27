<?php

	requires ('helpers', 'template');


	function handler_file($handler)
	{
		return handler_dir($handler)."$handler.handler.php";
	}

	function handler_dir($handler)
	{
		$app_dir = php_self_dir();
		return empty($handler) ? $app_dir : $app_dir.'handlers'.DIRECTORY_SEPARATOR.$handler.DIRECTORY_SEPARATOR;
	}

	function handler_templates_dir($handler)
	{
		return handler_dir($handler).'templates'.DIRECTORY_SEPARATOR;
	}

	function handler_func_exists($handler, $func)
	{
		$handler_func = "{$handler}_{$func}";
		if (function_exists($handler_func)) return $handler_func;

		if (!empty($handler))
		{
			$handler_file = handler_file($handler);
			if (file_exists($handler_file)) require_once $handler_file;
			if (function_exists($handler_func)) return $handler_func;
		}

		return false;
	}


	function handler_template($handler, $template)
	{
		return handler_templates_dir($handler)."$handler.$template.html";
	}

	function handler_layout($handler)
	{
		$layout = handler_templates_dir($handler)."$handler.layout.html";
		if (!empty($handler) and template_file_exists($layout)) return $layout;
		else return handler_templates_dir('')."layout.html";
	}

	function call_handler_func($handler, $func)
	{
		$params = array_slice(func_get_args(), 2);
		if ($handler_func = handler_func_exists($handler, $func, php_self_dir()))
			return call_user_func_array($handler_func, $params);
	}

?>