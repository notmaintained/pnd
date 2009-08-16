<?php

	$routes = array (

		array('method'=>'GET',
		      'path'=>'/[{handler}/][{id:any}]',
		      'query'=>true,
		      'defaults'=>array('handler'=>'',
		                        'func'=>'query')),

		array('method'=>'GET',
		      'path'=>'/[{handler}/]',
		      'defaults'=>array('handler'=>'',
		                        'func'=>'home')),

		array('method'=>'GET',
		      'path'=>'/[{handler}/][{id:any}]',
		      'defaults'=>array('handler'=>'',
		                        'func'=>'show')),

		//TODO: should put & delete work on collections '/', '/users/'  ??
		array('method'=>'PUT',
		      'path'=>'/[{handler}/][{id:any}]',
		      'defaults'=>array('handler'=>'',
		                        'func'=>'save')),

		array('method'=>'DELETE',
		      'path'=>'/[{handler}/][{id:any}]',
		      'defaults'=>array('handler'=>'',
		                        'func'=>'delete')),

		array('method'=>'POST',
		      'path'=>'/[{handler}/]',
		      'defaults'=>array('handler'=>'',
		                        'func'=>'post')),

		array('method'=>'POST',
		      'path'=>'/[{handler}/]{func}')
	);

?>