<?php

	requires ('helpers');


	function template_render($template, $template_vars=array(), $layout=NULL, $layout_vars=array())
	{
		$template_dir = template_dir_absolute($template, template_dir(), debug_backtrace(), php_self_dir());
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

			if (isset($layout))
			{
				if (empty($layout_vars)) $layout_vars = array();
				$layout_vars = array_merge($layout_vars, array('content' => $buffer));
				return call_user_func('template_render', $layout, $layout_vars);
			}

			return $buffer;

		}
		else
		{
			trigger_error("Required template ($template_file) not found.", E_USER_ERROR);
		}

		return false;
	}

		function template_dir_absolute($template, $template_dir, $debug_backtrace, $php_self_dir)
		{
			if (empty($template_dir))
			{
				$is_absolute_path = is_equal('/', $template[0]);
				$is_rendering_layout = !isset($debug_backtrace[0]['file']);
				$caller_index =  $is_rendering_layout ? 2 : 0;
				$caller = $debug_backtrace[$caller_index];
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

		function template_dir($dir=NULL)
		{
			static $template_dir;
			if (is_null($dir) and isset($template_dir)) return $template_dir;
			return $template_dir = $dir;
		}

?>