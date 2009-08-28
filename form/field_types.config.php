<?php


	$field_types = array
	(

		'custom'=>array
		(
			
		),

		'email'=>array
		(
			'validators'=>array
			(
				'email'=>'basic',
				'required'=>true
			),
			'filters'=>array
			(
				'before'=>array('trim', 'strtolower'),
				'after'=>array(array('htmlentities', ENT_QUOTES))
			),
			'error_msgs'=>array
			(
				'email'=>'not a valid email address'
			)
		),
		
		'amount'=>array
		(
			'validators'=>array
			(
				'matches'=>'\d*.?\d+',
				'required'=>true
			),
			'filters'=>array
			(
				'before'=>array('trim'),
				'after'=>array(array('htmlentities', ENT_QUOTES))
			),
			'error_msgs'=>array
			(
				'matches'=>'should be numeric'
			)
		),
		
		'text'=>array
		(
			'validators'=>array
			(
				'matches'=>'[\w ]+',
				'required'=>true
			),
			'filters'=>array
			(
				'before'=>array('trim'),
				'after'=>array(array('htmlentities', ENT_QUOTES))
			)
		)

		//TODO: html_text that does not have the htmlentities filter
	);

?>