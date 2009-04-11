<?php

	function test_unsafe_swx_map()
	{
		should_return(array(0=>'foo'), when_passed(array(array('foo')), '/foo'));
		should_return(array(0=>'bar', 'foo'=>'bar', 1=>'bar'), when_passed(array(array('{foo}')), 'bar'));
	}

	function test__unsafe_swx_map_regex_pattern()
	{
		should_return('/^(?P<foo>[^\\/]+)(bar)?$/', when_passed('{foo}[bar]'));
	}

	function test__swx_map_convert_optional_parts()
	{
		should_return('foo(bar)?', when_passed('foo[bar]'));
		should_return('(foo)?(bar)?', when_passed('[foo][bar]'));
		should_return('foo((bar)?)?', when_passed('foo[[bar]]'));
		should_return('foo(bar)?]', when_passed('foo[bar]]'));
	}
	
	function test__unsafe_swx_map_convert_named_parts()
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