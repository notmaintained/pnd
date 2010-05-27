<?php

	requires ('helpers');


	function template_render($template, $template_vars=array(), $template_dir=NULL)
	{
		if ($template_file = template_file_exists($template, $template_dir))
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
			trigger_error("Required template ($template) not found.", E_USER_ERROR);
		}

		return false;
	}

		function template_file_exists($template, $template_dir=NULL)
		{
			$template_file = template_file($template, $template_dir);
			return file_exists_($template_file);
		}

			function template_file($template, $template_dir=NULL)
			{
				if (template_has_absolute_path($template))
				{
					$template_dir = '';
				}
				else
				{
					$template_dir = template_dir
					(
						$template_dir,
						templates_dir(),
						php_self_dir()
					);
				}

				$template_dir = !empty($template_dir) ? rtrim($template_dir, '/\\').DIRECTORY_SEPARATOR : $template_dir;
				$template = ltrim($template, '/\\');
				return slashes_to_directory_separator("$template_dir$template.php");
			}

				function template_has_absolute_path($template)
				{
					$begins_with_directory_separator = is_equal($template{0}, DIRECTORY_SEPARATOR);
					$begins_with_windows_drive_letter = is_equal(substr($template, 1, 2), ':\\');
					$begins_with_network_drive = is_equal(substr($template, 0, 2), '\\\\');
					return
					(
						$begins_with_directory_separator or
						$begins_with_windows_drive_letter or
						$begins_with_network_drive
					);
				}

				function template_dir($template_dir, $templates_dir, $php_self_dir)
				{
					if (!is_null($template_dir)) return $template_dir;
					if (!is_null($templates_dir)) return $templates_dir;
					return $php_self_dir.'templates'.DIRECTORY_SEPARATOR;
				}

				function templates_dir($dir=NULL)
				{
					static $template_dir;
					if (is_null($dir) and isset($template_dir)) return $template_dir;
					return $template_dir = $dir;
				}


	function template_compose($template, $template_vars)
	{
		$args = array_slice(func_get_args(), 2);
		$content = template_render($template, $template_vars);

		while(!empty($args))
		{
			$template = array_shift($args);
			$template_vars = empty($args) ? array() : array_shift($args);
			$content = array('content'=>$content);
			$template_vars = array_merge($template_vars, $content);
			$content = template_render($template, $template_vars);
		}

		return $content;
	}

?>