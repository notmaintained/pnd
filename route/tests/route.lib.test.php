<?php

	
	function test_route_match()
	{
		should_return(array('handler'=>'', 'func'=>'query'),
		              when_passed(default_routes(), array('method'=>'GET',
		                                                  'path'=>'/',
		                                                  'query'=>array('baz'=>'quz'))));

		should_return(array('handler'=>'users', 'func'=>'query'),
		              when_passed(default_routes(), array('method'=>'GET',
		                                                  'path'=>'/users/',
		                                                  'query'=>array('baz'=>'quz'))));

		should_return(array('handler'=>'', 'func'=>'home'),
		              when_passed(default_routes(), array('method'=>'GET',
		                                                  'path'=>'/',
		                                                  'query'=>array())));

		should_return(array('handler'=>'users', 'func'=>'home'),
		              when_passed(default_routes(), array('method'=>'GET',
		                                                  'path'=>'/users/',
		                                                  'query'=>array())));

		should_return(array('handler'=>'', 'func'=>'show', 'id'=>'foo'),
		              when_passed(default_routes(), array('method'=>'GET',
		                                                  'path'=>'/foo',
		                                                  'query'=>array())));

		should_return(array('handler'=>'users', 'func'=>'show', 'id'=>'foo'),
		              when_passed(default_routes(), array('method'=>'GET',
		                                                  'path'=>'/users/foo',
		                                                  'query'=>array())));

		should_return(array('handler'=>'', 'func'=>'save'),
		              when_passed(default_routes(), array('method'=>'PUT',
		                                                  'path'=>'/',
		                                                  'query'=>array())));

		should_return(array('handler'=>'users', 'func'=>'save'),
		              when_passed(default_routes(), array('method'=>'PUT',
		                                                  'path'=>'/users/',
		                                                  'query'=>array())));

		should_return(array('handler'=>'', 'func'=>'save', 'id'=>'foo'),
		              when_passed(default_routes(), array('method'=>'PUT',
		                                                  'path'=>'/foo',
		                                                  'query'=>array())));

		should_return(array('handler'=>'users', 'func'=>'save', 'id'=>'foo'),
		              when_passed(default_routes(), array('method'=>'PUT',
		                                                  'path'=>'/users/foo',
		                                                  'query'=>array())));

		should_return(array('handler'=>'', 'func'=>'delete'),
		              when_passed(default_routes(), array('method'=>'DELETE',
		                                                  'path'=>'/',
		                                                  'query'=>array())));

		should_return(array('handler'=>'users', 'func'=>'delete'),
		              when_passed(default_routes(), array('method'=>'DELETE',
		                                                  'path'=>'/users/',
		                                                  'query'=>array())));

		should_return(array('handler'=>'', 'func'=>'delete', 'id'=>'foo'),
		              when_passed(default_routes(), array('method'=>'DELETE',
		                                                  'path'=>'/foo',
		                                                  'query'=>array())));

		should_return(array('handler'=>'users', 'func'=>'delete', 'id'=>'foo'),
		              when_passed(default_routes(), array('method'=>'DELETE',
		                                                  'path'=>'/users/foo',
		                                                  'query'=>array())));

		should_return(array('handler'=>'', 'func'=>'post'),
		              when_passed(default_routes(), array('method'=>'POST',
		                                                  'path'=>'/',
		                                                  'query'=>array())));

		should_return(array('handler'=>'users', 'func'=>'post'),
		              when_passed(default_routes(), array('method'=>'POST',
		                                                  'path'=>'/users/',
		                                                  'query'=>array())));

		should_return(array('handler'=>'', 'func'=>'login'),
		              when_passed(default_routes(), array('method'=>'POST',
		                                                  'path'=>'/login',
		                                                  'query'=>array())));

		should_return(array('handler'=>'users', 'func'=>'poke'),
		              when_passed(default_routes(), array('method'=>'POST',
		                                                  'path'=>'/users/poke',
		                                                  'query'=>array())));

	}

?>