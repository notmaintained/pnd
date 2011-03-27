<?php


	function db_connect($host, $username, $password, $database)
	{
		$connection = mysql_connect($host, $username, $password, true);
		if ($connection)
		{
			if (!mysql_select_db($database, $connection))
			{
				//Unable to select database.
				return false;
			}
		}
		else
		{
			//Can't connect to database server.
			return false;
		}

		return $connection;
	}


	function db_query($query, $params, $resource=NULL)
	{
		$replacement_pairs = array();
		foreach ($params as $key=>$value) $replacement_pairs['{'.$key.'}'] = $value;
		$replacement_pairs = array_map('mysql_real_escape_string', $replacement_pairs);
		$query = strtr($query, $replacement_pairs);
		$result = is_resource($resource) ? mysql_query($query, $resource) : mysql_query($query);
		return (!mysql_errno()) ? $result : false;
	}
/*
	function db_mysql_query_range($query, $from, $count)
	{
		return $query.' LIMIT '.(int)$from.','.(int)$count;
	}
*/

	function db_error()
	{
		return mysql_errno();
	}

	function db_rows($query, $params, $resource=NULL)
	{
		$result = db_query($query, $params, $resource);
		if ($result)
		{
			$rows = array();
			while ($row = mysql_fetch_assoc($result)) $rows[] = $row;
			return $rows;
		}

		return array();
	}

	function db_row($query, $params, $resource=NULL)
	{
		$result = db_query($query, $params, $resource);
		return $result ? mysql_fetch_assoc($result) : array();
	}

	function db_num_rows($result)
	{
		return $result ? mysql_num_rows($result) : array();
	}


	function db_result($result, $row=0)
	{
		return ($result and (mysql_num_rows($result) > $row)) ? mysql_result($result, $row) : false;
	}


	function db_affected_rows()
	{
		return mysql_affected_rows();
	}

	function db_insert_id()
	{
		return mysql_insert_id();
	}

	function db_update($table, $row, $row_id)
	{
	}


	function db_insert($table, $row)
	{

	}

?>