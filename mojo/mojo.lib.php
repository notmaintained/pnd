<?php

//TODO: requires ('mojo') should eventually give you everything you need to create a web app (templates, forms, db, proxies, auth, ect.)

	requires ('mapper', 'request', 'route');

	$handlers_dir = php_self_dir().'handlers'.DIRECTORY_SEPARATOR;
	map_request_to_handler(request_(), default_routes(), $handlers_dir);


?>