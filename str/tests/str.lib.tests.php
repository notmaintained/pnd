<?php

	function test_str_slashes_to_directory_separator()
	{
		should_return(DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR, when_passed('/test/'));
		should_return(DIRECTORY_SEPARATOR.'test'.DIRECTORY_SEPARATOR, when_passed('\\test\\'));
	}


	function test_str_contains()
	{
		should_return(true, when_passed('/', 'test/test'));
		should_return(false, when_passed('/', 'testtest'));
	}


	function test_str_underscorize()
	{
		should_return('A_e_I_o_U_9_', when_passed('A!e@I#o%U&9-'));
		should_return('A_e_I_o_U_9_', when_passed('#!@A!e@I#o%U&9-'));
	}

	function test_str_hyphenate()
	{
		should_return('A-e-I-o-U-9-', when_passed('A!e@I#o%U&9-'));
		should_return('A-e-I-o-U-9-', when_passed('#!@A!e@I#o%U&9-'));
	}

	function test_str_humanize()
	{
		should_return('foo bar baz', when_passed('foo_bar-baz'));
	}

	function test_str_singularize()
	{
		should_return('product', when_passed('products'));
	}

	function test_str_xss_sanitize()
	{
		should_return('&lt;script type=&quot;javascript&quot;&gt;alert(&quot;hello &amp; welcome&quot;)&lt;/script&gt;', when_passed('<script type="javascript">alert("hello & welcome")</script>'));
	}

?>