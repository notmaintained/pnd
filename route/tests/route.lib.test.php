<?php

	function test_parse_route_params()
	{

		should_return(false, when_passed(array('GET', '/')));

		should_return
		(
			array('method'=>'GET', 'conds'=>array(), 'paths'=>array('/'), 'funcs'=>array('func')),
			when_passed(array('GET', '/', 'func'))
		);

		should_return
		(
			array('method'=>'GET', 'conds'=>array(), 'paths'=>array('/', '/home'), 'funcs'=>array('func')),
			when_passed(array('GET', array('/', '/home'), 'func'))
		);

		should_return
		(
			array('method'=>'GET', 'conds'=>array(), 'paths'=>array('/'), 'funcs'=>array('db', 'auth', 'func')),
			when_passed(array('GET', '/', array('db', 'auth'), 'func'))
		);

		should_return
		(
			array('method'=>'POST', 'conds'=>array('action'=>'save'), 'paths'=>array('/'), 'funcs'=>array('db', 'auth', 'func')),
			when_passed(array('POST', array('/', 'action'=>'save'), array('db', 'auth'), 'func'))
		);

		should_return
		(
			array('method'=>'GET', 'conds'=>array(), 'paths'=>array('/'), 'funcs'=>array('func')),
			when_passed(array('GET', '/', 'func'))
		);

		should_return
		(
			false,
			when_passed(array('POST', array('/', 'action'=>'save')))
		);
	}


	function test_routes()
	{
		should_return(array(), when_passed('', true));

		should_return
		(
			array
			(
				array('method'=>'GET','paths'=>array('/'),'funcs'=>array('home'),'conds'=>array())
			),
			when_passed(array('method'=>'GET','paths'=>array('/'),'funcs'=>array('home'),'conds'=>array()))
		);

		should_return
		(
			array
			(
				array('method'=>'GET','paths'=>array('/'),'funcs'=>array('home'),'conds'=>array())
			),
			when_passed()
		);
	}


	function test_route_match()
	{
		should_return
		(
			array
			(
				'method'=>'GET',
				'paths'=>array('/', '/{home}'),
				'funcs'=>array('func'),
				'conds'=>array(),
				'path_matches'=>array('home'=>'hand')
			),
			when_passed
			(
				array(array('method'=>'GET', 'paths'=>array('/', '/{home}'), 'funcs'=>array('func'), 'conds'=>array())),
				array('method'=>'GET', 'path'=>'/hand'))
		);


		should_return
		(
			array
			(
				'method'=>'POST',
				'paths'=>array('/'),
				'funcs'=>array('func'),
				'conds'=>array('action'=>'save'),
				'path_matches'=>array()
			),
			when_passed
			(
				array(array('method'=>'POST', 'paths'=>array('/'), 'funcs'=>array('func'), 'conds'=>array('action'=>'save'))),
				array('method'=>'POST', 'path'=>'/', 'form'=>array('action'=>'save')))
		);
	}

?>