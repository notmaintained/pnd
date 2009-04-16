<?php

	function test_unsafe_swx_mapper_match()
	{
		//TODO: Needs more tests!
		should_return(array(0=>'foo'), when_passed('foo', array('foo'), array('bar')));
		should_return(array(0=>'foo/baz', 'bar'=>'baz', 1=>'baz'), when_passed('foo/baz', array('bar/foo'), array('foo/{bar}')));
	}

	function test__unsafe_swx_mapper_pattern()
	{
		should_return('/^(?P<foo>[^\\/]+)(bar)?$/', when_passed('{foo}[bar]'));
	}

	function test__swx_mapper_convert_optional_parts_to_regex()
	{
		should_return('foo(bar)?', when_passed('foo[bar]'));
		should_return('(foo)?(bar)?', when_passed('[foo][bar]'));
		should_return('foo((bar)?)?', when_passed('foo[[bar]]'));
		should_return('foo(bar)?]', when_passed('foo[bar]]'));
	}
	
	function test__unsafe_swx_mapper_convert_named_parts_to_regex()
	{
		should_return('foo(?P<bar>[^/]+)', when_passed('foo{bar}'));
		should_return('foo(?P<bar>.+)', when_passed('foo{bar:any}'));
	}

	function test__swx_mapper_convert_named_part_filters_to_regex()
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