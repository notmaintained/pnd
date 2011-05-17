<?php

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


		should_return
		(
			array
			(
				'method'=>'POST',
				'paths'=>array('/'),
				'funcs'=>array('func'),
				'conds'=>array('query'=>true),
				'path_matches'=>array()
			),
			when_passed
			(
				array(array('method'=>'POST', 'paths'=>array('/'), 'funcs'=>array('func'), 'conds'=>array('query'=>true))),
				array('method'=>'POST', 'path'=>'/', 'query'=>array('foo'=>'bar')))
		);
	}

?>