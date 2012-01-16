<?php

	function test_routes_()
	{
		should_return(array(), when_passed('', true));

		should_return
		(
			array
			(
				route_('GET', array('/'), array(), array('home'))
			),
			when_passed(route_('GET', array('/'), array(), array('home')))
		);

		should_return
		(
			array
			(
				route_('GET', array('/'), array(), array('home'))
			),
			when_passed()
		);
	}


	function test_route_match_()
	{
		should_return
		(
			route_('GET', array('/', '/{home}'), array(), array('func')),
			when_passed
			(
				route_('GET', array('/', '/{home}'), array(), array('func')),
				request_(array('method'=>'GET', 'path'=>'/hand'))
			)
		);


		should_return
		(
			route_('GET', array('/'), array(), array('func')),
			when_passed
			(
				route_('GET', array('/'), array(), array('func')),
				request_(array('method'=>'GET', 'path'=>'/', 'query'=>array('foo'=>'bar')))
			)
		);

		should_return
		(
			route_('GET', array('/'), array('query'=>true), array('func')),
			when_passed
			(
				route_('GET', array('/'), array('query'=>true), array('func')),
				request_(array('method'=>'GET', 'path'=>'/', 'query'=>array('foo'=>'bar')))
			)
		);

		should_return
		(
			NULL,
			when_passed
			(
				route_('GET', array('/'), array('query'=>true), array('func')),
				request_(array('method'=>'GET', 'path'=>'/', 'query'=>array()))
			)
		);


		should_return
		(
			route_('POST', array('/'), array('action'=>'save_me'), array('func')),
			when_passed
			(
				route_('POST', array('/'), array('action'=>'save_me'), array('func')),
				request_(array('method'=>'POST','path'=>'/', 'form'=>array('action'=>'Save Me')))
			)
		);

		should_return
		(
			route_('POST', array('/'), array(), array('func')),
			when_passed
			(
				route_('POST', array('/'), array(), array('func')),
				request_(array('method'=>'POST', 'path'=>'/', 'form'=>array('action'=>'Save Me')))
			)
		);

		should_return
		(
			NULL,
			when_passed
			(
				route_('POST', array('/'), array('action'=>'save'), array('func')),
				request_(array('method'=>'POST', 'path'=>'/', 'form'=>array('action'=>'Save Me')))
			)
		);

		should_return
		(
			NULL,
			when_passed
			(
				route_('POST', array('/'), array('action'=>'save'), array('func')),
				request_(array('method'=>'POST', 'path'=>'/', 'form'=>array()))
			)
		);
	}

?>