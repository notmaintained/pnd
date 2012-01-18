<?php

	require_once dirname(__FILE__).'/../pnd.php';
	requires ('path', 'helper', 'request', 'response');


	function handler_($method, $paths, $conds, $func)
	{
		return compact('method', 'paths', 'conds', 'func', 'path_matches');
	}


	function handle_all($path)
	{
		handle_request('*', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_head($path)
	{
		handle_request('HEAD', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_get($path)
	{
		handle_request('GET', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_query($path)
	{
		handle_request('GET', $path, array('query'=>true), array_slice(func_get_args(), 1));
	}

	function handle_post($path)
	{
		handle_request('POST', $path, array(), array_slice(func_get_args(), 1));
	}

	function handle_post_action($path, $action)
	{
		handle_request('POST', $path, array('action'=>$action), array_slice(func_get_args(), 2));
	}

	function handle_request($method, $paths, $conds, $funcs)
	{
		if (!is_array($paths)) $paths = array($paths);
		foreach ($paths as $key=>$val) if (!is_int($key)) named_paths_($key, $val);
		foreach($funcs as $func) handlers_(handler_($method, $paths, $conds, $func));
	}

		function named_paths_($name=NULL, $path=NULL, $reset=false)
		{
			static $name_paths = array();
			if ($reset) return $name_paths = array();
			if (!is_null($name) and is_null($path)) return isset($name_paths[$name]) ? $name_paths[$name] : false;
			$name_paths[$name] = $path;
			return $name_paths;
		}

		function handlers_($handler=NULL, $reset=false)
		{
			static $handlers = array();

			if ($reset) return $handlers = array();
			if (is_null($handler)) return $handlers;

			$handlers[] = $handler;
			return $handlers;
		}


	function next_handler()
	{
		$args = func_get_args();
		$handler = next_handler_match_($args[0], $matches);

		if (!is_null($handler))
		{
			if (is_callable($handler['func']))
			{
				$response = call_user_func_array($handler['func'], array_merge(array($args, $matches)));
			}
			else trigger_error_("Invalid handler func", E_USER_ERROR);

			return $response;
		}

		//TODO: this should be overridable by the user
		exit_with_404_plain('Not Found');

	}

		function next_handler_match_($req, &$matches)
		{
			static $handlers; if (!isset($handlers)) $handlers = handlers_();

			while ($handler = array_shift($handlers) and ($matched_handler = handler_match_($handler, $req, $matches)))
			{
				return $matched_handler;
			}
		}

			function handler_match_($handler, $req, &$matches=NULL)
			{
				$method_matched = (is_equal($req['method'], $handler['method']) or is_equal('*', $handler['method']));

				foreach ($handler['paths'] as $path)
				{
					if ($path_matched = path_match($path, $req['path'], $matches)) break;
				}

				$action_cond_failed = (isset($handler['conds']['action']) and
				                      (!isset($req['form']['action']) or
									   !is_equal ($handler['conds']['action'], strtolower(str_underscorize($req['form']['action'])))));

				$query_cond_failed = (isset($handler['conds']['query']) and
				                      is_equal(true, $handler['conds']['query']) and
									  empty($req['query']));

				if ($method_matched and $path_matched and !$action_cond_failed and !$query_cond_failed)
				{
					return	$handler;
				}
			}



	function respond()
	{
		next_handler(request_());
	}


	function path_match_include($path, $file)
	{
		$req = request_();
		if (path_match($path, $req['path'])) include $file;
	}

	function handler_path_macro($paths, $func)
	{
		handler_macro('*', $paths, array(), $func);
	}

	function handler_macro($method, $paths, $conds, $func)
	{
		$req = request_();
		$handler = handler_($method, $paths, $conds, $func);
		if (handler_match_($handler, $req, $matches))
		{
			if (is_callable($handler['func']))
			{
				call_user_func_array($handler['func'], array_merge(array($req, $matches)));
			}
			else trigger_error_("Invalid handler macro func", E_USER_ERROR);
		}
	}

?>