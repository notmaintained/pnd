<?php

	function test__swx_map_convert_optional_parts()
	{
		should_return('foo(bar)?', when_passed('foo[bar]'));
		should_return('(foo)?(bar)?', when_passed('[foo][bar]'));
		should_return('foo((bar)?)?', when_passed('foo[[bar]]'));
		should_return('foo(bar)?]', when_passed('foo[bar]]'));
	}
	
	function test__swx_map_convert_names_parts()
	{
		should_return('foo(?P<bar>[^/]+)', when_passed('foo{bar}'));
		should_return('foo(?P<bar>.+)', when_passed('foo{bar:any}'));
	}

	function test__swx_map_expand_named_part_filters()
	{
		$filters = array
		(
			'word'    => '\w+',
			'alpha'   => '[a-zA-Z]+',
			'digits'  => '\d+',
			'number'  => '\d*.?\d+',
			'segment' => '[^/]+',
			'any'     => '.+'
		);

		should_return('(?P<foo>[^/]+)', when_passed(array('{foo}', 'foo'), $filters));
		should_return('(?P<foo>.+)', when_passed(array('{foo:any}', 'foo:any'), $filters));
	}


?>