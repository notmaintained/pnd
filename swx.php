<?php

/* swx.php
 *
 *
 *
 * function email($str){return preg_replace('/^(.*)\/(.*)/', '$2@$1', $str);}
 * Authors: Sandeep Shetty email('gmail.com/sandeep.shetty')
 *
 * Copyright (C) 2005 - date('Y') Collaboration Science,
 * http://collaborationscience.com/
 *
 * This file is part of Swx.
 *
 * Swx is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 *
 * Swx is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 * 
 * To read the license please visit http://www.gnu.org/copyleft/gpl.html
 *
 *
 *-------10--------20--------30--------40--------50--------60---------72
 */
 

	define('SWX_DIR', dirname(__FILE__).DIRECTORY_SEPARATOR);
	define('SWX_LIBRARY_FILE_EXT', '.lib.php');


	function uses()
	{
		$libraries = func_get_args();
		foreach ($libraries as $library) 
		{
			$library_file = SWX_DIR.$library.DIRECTORY_SEPARATOR.$library.SWX_LIBRARY_FILE_EXT;
			if (file_exists($library_file))
			{
				require_once $library_file;
			}
			else
			{
				trigger_error("Required library ($library) not found.", E_USER_ERROR);
			}
		}
	}

?>