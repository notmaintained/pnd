<?php

	requires ('helpers');


	function template_render($template, $template_vars=array(), $template_dir=NULL)
	{
		if (empty($template_dir))
		{
			$caller = array_shift(debug_backtrace());
			$template_dir = dirname($caller['file']).DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR;
		}

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

		function template_file($template, $template_dir)
		{
			return slashes_to_directory_separator(add_trailing_slash($template_dir)."$template.php");
		}

			function add_trailing_slash($path)
			{
				return rtrim($path, '/\\').DIRECTORY_SEPARATOR;
			}

?>