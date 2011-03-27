<?php

	$routes = array
	(

		array
		(
			'method'=>'GET',
			'path'=>'/[{handler}/]',
			'query'=>true,
			'defaults'=>array
			(
				'handler'=>'',
				'func'=>'query'
			)
		),

		array
		(
			'method'=>'GET',
			'path'=>'/[{handler}/]',
			'defaults'=>array
			(
				'handler'=>'',
				'func'=>'home'
			)
		),

		array
		(
			'method'=>'GET',
			'path'=>'/[{handler}/][{id:any}]',
			'defaults'=>array
			(
				'handler'=>'',
				'func'=>'show'
			)
		),

		array
		(
			'method'=>'PUT',
			'path'=>'/[{handler}/][{id:any}]',
			'defaults'=>array
			(
				'handler'=>'',
				'func'=>'save'
			)
		),

		array
		(
			'method'=>'DELETE',
			'path'=>'/[{handler}/][{id:any}]',
			'defaults'=>array
			(
				'handler'=>'',
				'func'=>'delete'
			)
		),
/*
		array
		(
			'method'=>'POST',
			'path'=>'/[{handler}/]',
			'defaults'=>array
			(
				'handler'=>'',
				'func'=>post_action()
			)
		)*/
	);

?>