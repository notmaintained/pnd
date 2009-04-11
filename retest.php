<?php

/* retest.php
 *
 * function email($str){return preg_replace('/^(.*)\/(.*)/', '$2@$1', $str);}
 * Authors: Sandeep Shetty email('gmail.com/sandeep.shetty')
 *
 * Copyright (C) 2005 - date('Y') Collaboration Science,
 * http://collaborationscience.com/
 *
 * This file is part of Retest.
 *
 * Retest is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * Inertia is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 * 
 * To read the license please visit http://www.gnu.org/copyleft/gpl.html
 *
 *
 *-------10--------20--------30--------40--------50--------60---------72
 */

	// display asserts using user_defined_function so that you can show functions that don't have tests.
	// Show count of executable lines
	// test output for following case: no test files, no tests function, no source
	// write an error handler to capture count of error so that the count of failures is reflective of what actually is


	retest_include_files();
	retest_call_test_functions();


		function retest_include_files()
		{
			foreach (retest_test_files() as $test_file) 
			{
				if ($source_file = retest_source_file($test_file))
				{
					include_once $source_file;
					retest_increment('source_files');
					retest_source_files($source_file);
				}

				include_once $test_file;
				retest_increment('test_files');
			}
		}

			function retest_test_files()
			{
				return retest_globr(dirname(__FILE__), '*.test.php');		
			}
				function retest_globr($dir, $pattern)
				{
					$files = glob($dir.DIRECTORY_SEPARATOR.$pattern);
					foreach (glob($dir.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $dirname)
					{
						$files = array_merge($files, retest_globr($dirname, $pattern));
					}

					return $files;
				}

			function retest_source_file($test_file)
			{
				$source_file = retest_remove_test_extension($test_file);

				if (file_exists($source_file))
				{
					return $source_file;
				}
				else
				{
					$source_file = preg_replace('/'.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.'tests'.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.'/',
												DIRECTORY_SEPARATOR,
												$source_file);
					if (file_exists($source_file)) return $source_file;
				}

				return false;
			}
				function retest_remove_test_extension($test_file)
				{
					return preg_replace('/\.test\.php$/', '.php', $test_file);
				}

			function retest_increment($counter_name)
			{
				return retest_counter($counter_name, true);
			}

				function retest_counter($counter_name, $increment=false)
				{
					static $counters;
					if (!isset($counters[$counter_name])) $counters[$counter_name] = 0;
					if ($increment) $counters[$counter_name]++;
					return $counters[$counter_name];
				}

		function retest_source_files($source_file=NULL)
		{
			static $source_files=array();
			if (is_null($source_file)) return $source_files;
			$source_files[] = $source_file;
			return $source_files;
		}

		function retest_call_test_functions()
		{
			retest_source_coverage('start');
			
			foreach (retest_test_functions() as $test_function)
			{
				retest_increment('tests');
				$test_function();
			}

			retest_source_coverage('stop');
		}
			function retest_test_functions()
			{
				$all_defined_functions = get_defined_functions();
				$user_defined_functions = $all_defined_functions['user'];
				return array_filter($user_defined_functions, 'restest_is_test_function');
			}
				function restest_is_test_function($function)
				{
					return preg_match('/^test_.*/', $function);
				}

			function retest_source_coverage($op='')
			{
				static $source_coverage=array();

				if ('start' == $op and function_exists('xdebug_start_code_coverage'))
				{
					xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
				}

				if ('stop' == $op and function_exists('xdebug_get_code_coverage'))
				{
					$source_coverage = xdebug_get_code_coverage();
					$source_coverage = retest_filter_code_coverage($source_coverage);
				}
				
				return $source_coverage;
			}

				function retest_filter_code_coverage($source_coverage)
				{
					//TODO: rename files created in tests to testfoo... so that they can be filtered out
					foreach ($source_coverage as $file=>$file_coverage)
					{
						if ((substr($file, -9) == '.test.php') or
						    (substr($file, -10) == 'retest.php') or
						    (substr($file, -15) == 'retest.test.php'))
							unset($source_coverage[$file]);
						else $source_coverage[$file] = array_filter($file_coverage, 'retest_is_negative_value');
					}

					foreach ($source_coverage as $file=>$file_coverage)
					{
						if (file_exists($file))
						{
							$source = file($file);
							foreach ($file_coverage as $line_number=>$value)
							{
								if ((trim($source[($line_number-1)]) == '}') or (trim($source[($line_number-1)]) == '{'))
									unset($source_coverage[$file][$line_number]);
								else $source_coverage[$file][$line_number] = $source[($line_number-1)];
							}
						}
					}

					$source_coverage = array_filter($source_coverage, create_function('$val', 'return !empty($val);'));

					return $source_coverage;
				}
					function retest_is_negative_value($val)
					{
						return $val < 0;
					}

			function retest_count_of_untested_lines($source_coverage)
			{
				return array_reduce($source_coverage, 'retest_accumulator', 0);
			}
				function retest_accumulator($counter, $value)
				{
					return $counter += count($value);
				}


	function should_return($expected_return_value, $when_passed=NULL, $msg=NULL)
	{
		$debug_backtrace = debug_backtrace();
		$function = extract_function_name_from_($debug_backtrace);
		if (function_exists($function))	$returned_value = call_user_func_array($function, $when_passed);
		else return trigger_error("Function $function does not exist");

		$satisfied = ($returned_value === $expected_return_value);
		retest_increment('assertions');
		$msg = retest_assert_description($satisfied, $function, $expected_return_value, $returned_value, $when_passed, $msg);
		$location = extract_assertion_location_from_($debug_backtrace);
		retest_assertions(array('function'=>$function, 'location'=>$location, 'message'=>$msg));

		if (!$satisfied)
		{
			retest_increment('failures');
			retest_failures(array('function'=>$function, 'location'=>$location, 'message'=>$msg));
		}

		return $satisfied;
	}
		function extract_function_name_from_($debug_backtrace)
		{
			$test_function = $debug_backtrace[1]['function'];
			$function = preg_replace('/^test_/', '', $test_function);		
			return $function;
		}

		function retest_assert_description($satisfied, $function, $expected_return_value, $returned_value, $passed_arguments, $msg)
		{
			if ($satisfied)
			{
				$function_call = "<strong>$function</strong>".'('.retest_array_to_argument_list($passed_arguments).')';
				//TODO: reason for %1\$s instead of %s: the $f in $function_call kicks in argument swaping in sprintf :(
				$msg = is_null($msg) ? sprintf("$function_call returns <em>%1\$s</em>", htmlspecialchars(var_export($returned_value, true))) : $msg;
			}
			else
			{
				$function_call = $function.'('.retest_array_to_argument_list($passed_arguments).')';
				//TODO: reason for %1\$s instead of %s: the $f in $function_call kicks in argument swaping in sprintf :(
				$msg = is_null($msg) ? sprintf("<strong>$function_call</strong> should have returned <strong>%1\$s</strong> but was <strong>%2\$s</strong>", htmlspecialchars(var_export($expected_return_value, true)), htmlspecialchars(var_export($returned_value, true))) : $msg;
			}
			
			return $msg;
		}

			function retest_array_to_argument_list($arguments)
			{
				$argument_list = '';

				if (is_array($arguments))
				{
					$arguments = array_map('retest_variable_to_string', $arguments);
					$argument_list = implode(', ', $arguments);
				}

				return $argument_list;
			}
				function retest_variable_to_string($argument)
				{
					return htmlspecialchars(var_export($argument, true));
				}

		function retest_assertions($assertion=NULL)
		{
			static $assertions=array();
			if (is_null($assertion)) return $assertions;

			$assertions[$assertion['function']][] = array('location' => $assertion['location'],
			                                              'message' => $assertion['message']);
			return $assertions;
						
		}

		function retest_failures($assertion=NULL)
		{
			static $assertions;

			$assertions = isset($assertions) ? $assertions : array();
			if (is_null($assertion)) return $assertions;

			$assertions[$assertion['function']][] = array('location' => $assertion['location'],
			                                              'message' => $assertion['message']);
			return $assertions;
		}

		function extract_assertion_location_from_($debug_backtrace)
		{
			return array('file'=>$debug_backtrace[0]['file'], 'line'=>$debug_backtrace[0]['line']);
		}

	function when_passed()
	{
		return func_get_args();
	}


?>


<?php //HTML helper
	
	function retest_meta_refresh($seconds=NULL)
	{
		if (!is_null($seconds))
		{
			return '<meta http-equiv="Refresh" content="'.$seconds.'; url=retest.php?refresh_in='.$seconds.'" />';			
		}
	}

	function retest_no_tests()
	{
		return (count(retest_test_functions()) == 0);		
	}


	function retest_status_red()
	{
		return (retest_counter('failures') > 0);		
	}


	function retest_red_or_green_bar()
	{
		return (retest_status_red()) ? "red-bar" : "green-bar";
	}

	function retest_pluralize($str, $no)
	{
		return (1 !== $no) ? $str.'s' : $str;
	}

	function retest_test_filter($test)
	{
		$test = preg_replace('/([A-Z])/', ' \1', $test);
		$test = preg_replace('/^test_/', '', $test);
		$test = strtolower($test);
		return $test;
//		return strtr(htmlentities($test), array('&shy;'=>'-'));
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3c.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
	<!-- meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /-->
	<?php if (isset($_GET['refresh_in'])) echo retest_meta_refresh(htmlspecialchars($_GET['refresh_in'])); ?> 
	<title>Retest - Red/Green/Refactor</title>
	<style type="text/css">

		body {
			margin: 0;
			padding: 10px 15px;
			font-family: 'lucida grande', 'lucida sans unicode', lucida, verdana, geneva, sans-serif;
			//font-size: 76%;
			font-size: 190%;
			background-color: #fff;
			color: #000000;
		}

		div#container {
			padding: 0; 
			font-size: 1.1em;
		}

		div#header {
			padding: 10px 15px 12px 15px;
			margin: 0;
//			background-color: #433C2A;
//			background-color: #BFDAE1;
			background-color: #EFCEB3;
			color: #fff;
		}

		h1 {
			font: 2em trebuchet ms, verdana;
			margin: 0;
		}

		div#header a {
			text-decoration: none;
			color: #fff;
		}

		div#result-bar {
			margin: 10px 0;
			padding: 10px 15px;
		}
		.red-bar { color: #fff; padding: 5px 8px; background-color: red;}
		.green-bar { color: #fff; padding: 5px 8px; background-color: #90B500; }
		.grey-bar { color: #fff; padding: 5px 8px; background-color: #999;}

		div#failures, div#behaviours, div#tests, div#test-files {
			margin: 10px 0 10px 2em;
			padding: 10px 15px;
//			background-color: #DAEAED;
			background-color: #EFEFEF;
			border: 1px solid #E0E0E0;
		}

		div#info, div#tdd, div#code-coverage {
			margin: 10px 0;
			padding: 10px 15px;
//			background-color: #DAEAED;
			background-color: #EFEFEF;
//			border: 1px solid #E0E0E0;
			color: #666;
		}

.frontcolumn {
/*	width: 30%;*/
	float: left;
	padding-right: 3%;
}

		div.status {
			float: right;
			margin: 0;
			padding: 4px 4px 0 0;
		}

		ul.info {
			margin: 0.5em 0 0 0;
			padding: 0;
		}

		.info li {
			margin: 0 0 0.7em 0;
			padding: 0;
			clear: both;
			list-style-type: none;
		}
		.info li a { font-weight: normal; }

		.num {
			background: #eee;
			border-right: 1px solid #ccc;
			border-bottom: 1px solid #ccc;
			float: left;
			text-align: center;
			margin: 0 5px 5px 0;
			line-height: 1.1em;
			padding: 2px;
			font-size: 11px;
			width: 3em;
			white-space: nowrap;
			color: #666;
			font-weight: bold;
		}

		.cc {
			font-size: 0.8em;
		}
	</style>
	<script language="javascript"> 

		function toggle($element)
		{
			if(document.getElementById($element).style.display == "block")
				document.getElementById($element).style.display = "none";
			else
				document.getElementById($element).style.display = "block";
		}

	</script>

</head>
<body>

<div id='container'>

	<div id="result-bar" class="<?php echo retest_red_or_green_bar(); ?>">
		<?php if (retest_no_tests()) { ?>
			Write a test.
		<?php } elseif (retest_status_red()) { ?>
			<strong>It failed!</strong> Failure is progress. Quickly make it work and get the bar green. Remember, quick green excuses all sins.
		<?php } else { ?>
			<strong>It worked!</strong> But is it clean? Refactor to remove any duplication. Remember, the cycle isn't complete till your code is clean.
		<?php } ?>
	</div>

	<div id="info">
		<ul class="info">
		<?php if (retest_status_red()) { ?>
			<li><strong><?php echo retest_counter('failures') ?></strong> <a href="javascript:toggle('failures');"><?php echo retest_pluralize('Failure', retest_counter('failures')) ?></a>
					<div id="failures" style="display: block">
					<?php
						echo "<ul>";
						if (retest_counter('failures') > 0)
						{
							foreach (retest_failures() as $function=>$details)
							{
								foreach ($details as $detail)
								{
									echo "<li>{$detail['message']} [in {$detail['location']['file']} on line {$detail['location']['line']}]</li>";
								}
							}
						}
						echo "</ul>";
					?>
					</div>
			</li>
			<?php } ?>			
			<li><strong><?php echo retest_counter('tests') ?></strong> <a href="javascript:toggle('tests');"><?php echo retest_pluralize('Test', retest_counter('tests')) ?></a>
				<div id="tests" style="display: none;">
					<?php
						echo "<ul>";
						$all_assertions = retest_assertions();
						foreach (retest_test_functions() as $test)
						{
							$function = retest_test_filter($test);
							$test_assertions = isset($all_assertions[$function]) ? $all_assertions[$function] : array();
							echo "<li>$function";
							echo '<div style="font-size: 0.6em; font-family: monospace;">';
							foreach ($test_assertions as $test_assertion)
							{
								echo $test_assertion['message']."<br />";
							}
							echo "</div>";
							echo "</li>";
						}
						
					//~ $id = md5($file);
					//~ echo "<li class=\"cc\"><a href=\"javascript:toggle('{$id}-code-coverage');\">{$file}</a>";
					//~ echo "<div id=\"{$id}-code-coverage\" style=\"display: none\">";
					//~ foreach ($lines_not_covered as $line_number=>$status)
					//~ {
						//~ echo "Line # $line_number :<code>$status</code><br />";
					//~ }
					//~ echo "</div>";
					//~ echo "</li>";
						
						echo "</ul>";
					?>
				</div>
			</li>
			<li><strong><?php echo retest_counter('test_files') ?></strong> <a href="javascript:toggle('test-files');">Test <?php echo retest_pluralize('File', retest_counter('test_files')) ?></a>
				<div id="test-files" style="display: none">
					<?php
						echo "<ul>";
						foreach (retest_test_files() as $test_file)
						{
							echo "<li>$test_file</li>";
						}
						echo "</ul>";
					?>
				</div>
			</li>
			<li><strong><?php echo retest_counter('source_files') ?></strong> <a href="javascript:toggle('source-files');">Source <?php echo retest_pluralize('File', retest_counter('source_files')) ?></a>
				<div id="source-files" style="display: none">
					<?php
						echo "<ul>";
						foreach (retest_source_files() as $source_file)
						{
							echo "<li>$source_file</li>";
						}
						echo "</ul>";
					?>
				</div>
			</li>

		</ul>
	</div>

	<div id="code-coverage">
		<strong><?php echo retest_count_of_untested_lines(retest_source_coverage()) ?></strong> <a href="javascript:toggle('untested-code');">Source lines not covered by tests (requires xdebug)</a>
		<div id="untested-code" style="display: none">
			<?php
				echo "<ul>";
				foreach (retest_source_coverage() as $file=>$lines_not_covered)
				{
					$id = md5($file);
					echo "<li class=\"cc\"><a href=\"javascript:toggle('{$id}-code-coverage');\">{$file}</a>";
					echo "<div id=\"{$id}-code-coverage\" style=\"display: none\">";
					foreach ($lines_not_covered as $line_number=>$status)
					{
						echo "Line # $line_number :<code>$status</code><br />";
					}
					echo "</div>";
					echo "</li>";
				}
				echo "</ul>";
			?>
		</div>
	</div>

	<div id="tdd">
		<a href="javascript:toggle('tdd-intro');">Test-Driven Development (TDD)</a>
		<div id='tdd-intro' style="display: none;">
			<p>The goal of TDD is <em>clean code that works</em>. The two simple rules of TDD are:
			<ul>
				<li>Write new code only if an automated test has failed</li>
				<li>Eliminate duplication.</li>
			</ul>
			</p>
			<p>The TDD cycle is 
			<ul>
				<li>Write a Test</li>
				<li>Quickly make it run</li>
				<li>Make it right</li>
			</ul>
			<p>
			<p>More specifically, it is 
			<ul>
				<li>Write a test</li>
				<li>Run all tests and fail</li>
				<li>Make a little change to pass the test, committing whatever sins necessary</li>
				<li>Run all tests and succeed</li>
				<li>Refactor to remove duplication</li>
			</ul>
			</p>
			<p>Red/Green/Refactor - the TDD mantra.</p>
			<p>If you follow TDD, you will be able to:
			<ul>
				<li>Start simply</li>
				<li>Write automated tests</li>
				<li>Refactor to add design decisions one at a time</li>
			</ul>
			</p>
			<p>For more to chew on get yourself a copy of <em>Test Driven Development: By Example</em> by <a href="http://wikipedia.org/wiki/Kent_Beck">Kent Beck</a>.
			</p>
		</div>
	</div>
</div>

</body>
</html>