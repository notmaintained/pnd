<?php

//TODO: requires ('mojo') should eventually give you everything you need to create a web app (templates, forms, db, proxies, auth, ect.)

	requires ('request', 'route', 'mapper');

	map_request_to_handler(request_(), default_routes());

?>