<?php

	requires ('helpers');


	function template_render($template, $template_vars=array(), $template_dir=NULL)
	{
		$template_dir = absolute_template_dir($template, $template_dir, debug_backtrace(), php_self_dir());
		$template_file = template_file($template, $template_dir);

		if (file_exists($template_file))
		{
			if (!empty($template_vars) and is_array($template_vars))
			{
				extract($template_vars);
			}

			ob_start();
			require $template_file;
			$buffer = ob_get_contents();
			ob_end_clean();

			return $buffer;
		}
		else
		{
			trigger_error("Required template ($template_file) not found.", E_USER_ERROR);
		}

		return false;
	}

		function absolute_template_dir($template, $template_dir, $debug_backtrace, $php_self_dir)
		{
			if (empty($template_dir))
			{
				$is_absolute_path = is_equal('/', $template[0]);
				$caller = array_shift($debug_backtrace);
				$template_dir = $is_absolute_path ? $php_self_dir : dirname($caller['file']).DIRECTORY_SEPARATOR;
			}

			return $template_dir;
		}

		function template_file($template, $template_dir)
		{
			$template_dir = rtrim($template_dir, '/\\').DIRECTORY_SEPARATOR;
			$template = ltrim($template, '/\\');
			return slashes_to_directory_separator("$template_dir$template.php");
		}

?>