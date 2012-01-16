<?php


	function test_segment_count()
	{
		should_return(1, when_passed(''));
		should_return(1, when_passed('/'));
		should_return(2, when_passed('//'));
		should_return(1, when_passed('foo'));
		should_return(1, when_passed('/foo'));
		should_return(2, when_passed('/foo/'));
		should_return(2, when_passed('/foo/bar'));
		should_return(3, when_passed('/foo/bar/'));
		should_return(3, when_passed('/foo/bar/baz'));
	}


	function test_sub_path()
	{
		should_return('', when_passed('', 0));
		should_return('/', when_passed('/', 0));
		should_return('//', when_passed('//', 0));
		should_return(false, when_passed('/foo', 2));
		should_return('/foo/bar/', when_passed('/foo/bar/', 0));
		should_return('/foo/bar', when_passed('/foo/bar', 0));
		should_return('/bar', when_passed('/foo/bar', -1));
		should_return('/foo', when_passed('/foo/bar', 0, 1));
		should_return('/bar', when_passed('/foo/bar/', 1, -1));
		should_return('/bar', when_passed('/foo/bar/', -2, -1));
		should_return('/foo/bar', when_passed('/foo/bar', -10));
		should_return('/foo/bar', when_passed('/foo/bar', 0, 10));
		should_return('foo', when_passed('foo/bar', 0, 1));
	}


	function test_sub_segment()
	{
		should_return('', when_passed('', 0));
		should_return('', when_passed('/', 0));
		should_return('', when_passed('//', 0));
		should_return(false, when_passed('/foo', 2));
		should_return('bar', when_passed('/foo/bar', -1));
		should_return('foo', when_passed('/foo/bar', -10));
		should_return('foo', when_passed('foo/bar', 0));
		should_return('foo', when_passed('/foo/bar'));
	}


	function test__sub_segments()
	{
		should_return(array(''), when_passed('', 0));
		should_return(array(''), when_passed('/', 0));
		should_return(array('', ''), when_passed('//', 0));
		should_return(false, when_passed('/foo', 2));
		should_return(array('foo', 'bar', ''), when_passed('/foo/bar/', 0));
		should_return(array('foo', 'bar'), when_passed('/foo/bar', 0));
		should_return(array('bar'), when_passed('/foo/bar', -1));
		should_return(array('foo'), when_passed('/foo/bar', 0, 1));
		should_return(array('bar'), when_passed('/foo/bar/', 1, -1));
		should_return(array('bar'), when_passed('/foo/bar/', -2, -1));
		should_return(array('foo', 'bar'), when_passed('/foo/bar', -10));
		should_return(array('foo', 'bar'), when_passed('/foo/bar', 0, 10));
		should_return(array('foo'), when_passed('foo/bar', 0, 1));
	}


	function test_path_match()
	{
		//TODO: Needs more tests!
		should_return(true, when_passed('/{handler}/', '/users/'));
	}

	function test_path_pattern_to_pattern()
	{
		should_return('/^(?P<foo>[^\\/]+)(bar)?$/', when_passed('{foo}[bar]'));
	}

	function test_convert_optional_parts_to_regex()
	{
		should_return('foo(bar)?', when_passed('foo[bar]'));
		should_return('(foo)?(bar)?', when_passed('[foo][bar]'));
		should_return('foo((bar)?)?', when_passed('foo[[bar]]'));
		should_return('foo(bar)?]', when_passed('foo[bar]]'));
	}

	function test_convert_named_parts_to_regex()
	{
		should_return('foo(?P<bar>[^/]+)', when_passed('foo{bar}'));
		should_return('foo(?P<bar>.+)', when_passed('foo{bar:any}'));
	}

	function test_convert_named_part_filters_to_regex()
	{
		require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'filters.config.php';

		should_return('(?P<foo>[^/]+)', when_passed(array('{foo}', 'foo'), $filters));
		should_return('(?P<foo>.+)', when_passed(array('{foo:any}', 'foo:any'), $filters));
	}

?>