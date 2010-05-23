<?php

	$routes = array
	(
		array
		(
			'method'=>'GET',
			'path'=>'/admin/',
			'defaults'=>array
			(
				'handler'=>'admin',
				'func'=>'home'
			)
		),

		array
		(
			'method'=>'GET',
			'path'=>'/admin/{handler}/',
			'query'=>true,
			'defaults'=>array
			(
				'func'=>'query'
			)
		),

		array
		(
			'method'=>'GET',
			'path'=>'/admin/{handler}/',
			'defaults'=>array
			(
				'func'=>'home'
			)
		),

		array
		(
			'method'=>'GET',
			'path'=>'/admin/{handler}/{id:any}',
			'defaults'=>array
			(
				'func'=>'show'
			)
		),

		array
		(
			'method'=>'PUT',
			'path'=>'/admin/{handler}/{id:any}',
			'defaults'=>array
			(
				'func'=>'save'
			)
		),

		array
		(
			'method'=>'DELETE',
			'path'=>'/admin/{handler}/{id:any}',
			'defaults'=>array
			(
				'func'=>'delete'
			)
		),

		array
		(
			'method'=>'POST',
			'path'=>'/admin/{handler}/',
			'defaults'=>array
			(
				'func'=>post_action()
			)
		),

		array
		(
			'method'=>'',
			'path'=>'/[{id:any}]',
			'defaults'=>array
			(
				'handler'=>'',
				'func'=>'catchall'
			)
		)

	);

?>