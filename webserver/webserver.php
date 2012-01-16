<?php


	define('WEBSERVER', webserver(server_var('SERVER_SOFTWARE')));

	require_webserver_adapter('default');
	require_webserver_adapter(WEBSERVER);


		function webserver($server_software)
		{
			$server_softwares = array('Apache'        => 'apache',
									  'Microsoft-IIS' => 'iis',
									  'Microsoft-PWS' => 'pws',
									  'Xitami'        => 'xitami',
									  'Zeus'          => 'zeus',
									  'OmniHTTPd'     => 'omnihttpd');

			foreach ($server_softwares as $key=>$value)
			{
				if (str_contains($key, $server_software))
				{
					return $value;
				}
			}

			return 'unknown';
		}

		function require_webserver_adapter($webserver_adapter)
		{
			$webserver_adapter_file = webserver_adapter_file($webserver_adapter);
			if (file_exists($webserver_adapter_file)) require_once $webserver_adapter_file;
		}

			function webserver_adapter_file($webserver)
			{
				return dirname(__FILE__).DIRECTORY_SEPARATOR.'adapters'.DIRECTORY_SEPARATOR.$webserver.'.adapter.php';
			}


	function webserver_specific()
	{
		$params = func_get_args();
		$func = array_shift($params);
		$webserver_specific_func = webserver_specific_func(WEBSERVER, $func);
		return call_user_func_array($webserver_specific_func, $params);
	}

		function webserver_specific_func($webserver, $func)
		{
			$webserver_specific_func = "{$webserver}_specific_{$func}";
			if (function_exists($webserver_specific_func))
			{
				return $webserver_specific_func;
			}
			else
			{
				return "default_{$func}";
			}
		}

?>