<?php

	function test_routes_()
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


	function test_route_match_()
	{
		should_return
		(
			array
			(
				'method'=>'GET',
				'paths'=>array('/', '/{home}'),
				'funcs'=>array('func'),
				'conds'=>array()
			),
			when_passed
			(
				array
				(
					'method'=>'GET',
					'paths'=>array('/', '/{home}'),
					'funcs'=>array('func'),
					'conds'=>array()
				),
				array
				(
					'method'=>'GET',
					'path'=>'/hand'
				)
			)
		);


		should_return
		(
			array
			(
				'method'=>'GET',
				'paths'=>array('/'),
				'funcs'=>array('func'),
				'conds'=>array()
			),
			when_passed
			(
				array
				(
					'method'=>'GET',
					'paths'=>array('/'),
					'funcs'=>array('func'),
					'conds'=>array()
				),
				array
				(
					'method'=>'GET',
					'path'=>'/',
					'query'=>array('foo'=>'bar')
				)
			)
		);

		should_return
		(
			array
			(
				'method'=>'GET',
				'paths'=>array('/'),
				'funcs'=>array('func'),
				'conds'=>array('query'=>true)
			),
			when_passed
			(
				array
				(
					'method'=>'GET',
					'paths'=>array('/'),
					'funcs'=>array('func'),
					'conds'=>array('query'=>true)
				),
				array
				(
					'method'=>'GET',
					'path'=>'/',
					'query'=>array('foo'=>'bar')
				)
			)
		);

		should_return
		(
			NULL,
			when_passed
			(
				array
				(
					'method'=>'GET',
					'paths'=>array('/'),
					'funcs'=>array('func'),
					'conds'=>array('query'=>true)
				),
				array
				(
					'method'=>'GET',
					'path'=>'/',
					'query'=>array()
				)
			)
		);


		should_return
		(
			array
			(
				'method'=>'POST',
				'paths'=>array('/'),
				'funcs'=>array('func'),
				'conds'=>array('action'=>'save_me')
			),
			when_passed
			(
				array
				(
					'method'=>'POST',
					'paths'=>array('/'),
					'funcs'=>array('func'),
					'conds'=>array('action'=>'save_me')
				),
				array
				(
					'method'=>'POST',
					'path'=>'/',
					'form'=>array('action'=>'Save Me')
				)
			)
		);

		should_return
		(
			array
			(
				'method'=>'POST',
				'paths'=>array('/'),
				'funcs'=>array('func'),
				'conds'=>array()
			),
			when_passed
			(
				array
				(
					'method'=>'POST',
					'paths'=>array('/'),
					'funcs'=>array('func'),
					'conds'=>array()
				),
				array
				(
					'method'=>'POST',
					'path'=>'/',
					'form'=>array('action'=>'Save Me')
				)
			)
		);

		should_return
		(
			NULL,
			when_passed
			(
				array
				(
					'method'=>'POST',
					'paths'=>array('/'),
					'funcs'=>array('func'),
					'conds'=>array('action'=>'save')
				),
				array
				(
					'method'=>'POST',
					'path'=>'/',
					'form'=>array('action'=>'Save Me')
				)
			)
		);

		should_return
		(
			NULL,
			when_passed
			(
				array
				(
					'method'=>'POST',
					'paths'=>array('/'),
					'funcs'=>array('func'),
					'conds'=>array('action'=>'save')
				),
				array
				(
					'method'=>'POST',
					'path'=>'/',
					'form'=>array()
				)
			)
		);
	}

?>