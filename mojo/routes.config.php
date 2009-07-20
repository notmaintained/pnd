<?php

	$routes = array (

		array('method'=>'GET',
		      'path'=>'/[{handler}/].*',
		      'query'=>true,
		      'defaults'=>array('handler'=>'',
		                        'func'=>'query_')),

		array('method'=>'GET',
		      'path'=>'/[{handler}/]',
		      'defaults'=>array('handler'=>'',
		                        'func'=>'home_')),

		array('method'=>'GET',
		      'path'=>'/[{handler}/].*',
		      'defaults'=>array('handler'=>'',
		                        'func'=>'show_')),
//TODO should put & delete work on collections /, /users/  ??
		array('method'=>'PUT',
		      'path'=>'/[{handler}/].*',
		      'defaults'=>array('handler'=>'',
		                        'func'=>'save_')),

		array('method'=>'DELETE',
		      'path'=>'/[{handler}/].*',
		      'defaults'=>array('handler'=>'',
		                        'func'=>'delete_')),

		array('method'=>'POST',
		      'path'=>'/[{handler}/]{func}',
		      'defaults'=>array('handler'=>'')),
	);

?>