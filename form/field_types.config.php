<?php


	$field_types = array
	(

		'custom'=>array(),

		'email'=>array
		(
			'validators'=>array('email'=>'basic'),
			'filters'=>array('before'=>array('strtolower')),
			'error_msgs'=>array('email'=>'not a valid email address')
		),

		'amount'=>array
		(
			'validators'=>array('matches'=>'\d+(.?\d+)?'),
			'error_msgs'=>array('matches'=>'should be number greater than')
		),

		'text'=>array
		(
			'validators'=>array('matches'=>'.+')
		)
	);

?>