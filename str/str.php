<?php

	require_once dirname(__FILE__).'/../pnd.php';
	requires ('helpers');


	function str_slashes_to_directory_separator($path)
	{
		return preg_replace('/[\/\\\]/', DIRECTORY_SEPARATOR, $path);
	}


	function str_contains($needle, $haystack)
	{
		return (strpos($haystack, $needle) !== false);
	}


	function str_underscorize($str)
	{
		$str = preg_replace('/^[^a-zA-Z0-9]+/', '', trim($str));
		return preg_replace('/[^a-zA-Z0-9]/', '_', trim($str));
	}


	function str_hyphenate($str)
	{
		$str = preg_replace('/^[^a-zA-Z0-9]+/', '', trim($str));
		return preg_replace('/[^a-zA-Z0-9]/', '-', trim($str));
	}


	function str_humanize($str)
	{
		return strtr(trim($str), array('-'=>' ', '_'=>' '));
	}


	function str_xss_sanitize($str, $charset='UTF-8')
	{
		return htmlspecialchars($str, ENT_QUOTES, $charset);
	}


	function str_random_alphanum($length=10)
	{
		$aZ09_without_similar_chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$len = strlen($aZ09_without_similar_chars) - 1;
		$random_alphanum = '';
		for($i=0; $i < $length; $i++) $random_alphanum .= $aZ09_without_similar_chars[mt_rand(0, $len)];
		return $random_alphanum;
	}


	//TODO: This is a hack. Make it more comprehensive.
	function str_singularize($str)
	{
		if (is_equal('s', substr($str, -1))) return substr($str, 0, -1);
	}


	//TODO: This is a hack. Make it more comprehensive.
	function str_pluralize($str)
	{
		return "{$str}s";
	}

?>