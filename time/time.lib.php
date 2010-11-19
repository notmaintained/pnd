<?php

//TODO: Add tests!

	function time_since($timestamp)
	{
		$seconds_per_unit = array('year' => 60 * 60 * 24 * 365,
		                          'month' => 60 * 60 * 24 * 30,
		                          'week' => 60 * 60 * 24 * 7,
		                          'day' => 60 * 60 * 24,
		                          'hour' => 60 * 60,
		                          'minute' => 60,
		                          'second' => 1);

		$now = time();
		$distance_in_seconds = $now - $timestamp;
		$depth = 0;
		foreach ($seconds_per_unit as $unit=>$seconds)
		{
			$depth++;
			if (($count = floor($distance_in_seconds / $seconds)) != 0)
			{
				$distance_in_seconds = $distance_in_seconds - ($seconds * $count);
				break;
			}
		}

		$result = ($count == 1) ? '1 '.$unit : "$count {$unit}s";

		//remove this for full details
		if ($depth < 5)
		{
			return $result;
		}

		$seconds_per_unit = array_slice($seconds_per_unit, $depth);

		foreach ($seconds_per_unit as $unit=>$seconds)
		{
			if (($count = floor($distance_in_seconds / $seconds)) != 0)
			{
				$result .= ($count == 1) ? ', 1 '.$unit : ", $count {$unit}s";
				$distance_in_seconds = $distance_in_seconds - ($seconds * $count);

				//remove this for full details
				break;
			}
		}

		return $result;
	}


	function time_distance_in_words($from_timestamp, $to_timestamp, $include_seconds=false)
	{
        $distance_in_minutes = round(abs($to_timestamp - $from_timestamp)/60);
        $distance_in_seconds = round(abs($to_timestamp - $from_timestamp));

		if (in_range(0, 1, $distance_in_minutes))
		{
			if ($include_seconds)
			{
				if (in_range(0, 4, $distance_in_seconds)) return 'less than 5 seconds';
				if (in_range(5, 9, $distance_in_seconds)) return 'less than 10 seconds';
				if (in_range(10, 19, $distance_in_seconds)) return  'less than 20 seconds';
				if (in_range(20, 39, $distance_in_seconds)) return  'half a minute';
				if (in_range(40, 59, $distance_in_seconds)) return 'less than a minute';
				return '1 minute';
			}
			else
			{
				return (is_equal($distance_in_minutes, 0)) ? 'less than a minute' : '1 minute';
			}
		}

		if (in_range(2, 44, $distance_in_minutes)) return "$distance_in_minutes minutes";
		if (in_range(45, 89, $distance_in_minutes)) return 'about 1 hour';
		if (in_range(90, 1439, $distance_in_minutes)) return 'about '.round($distance_in_minutes / 60.0).' hours';
		if (in_range(1440, 2879, $distance_in_minutes)) return '1 day';
		if (in_range(2880, 43199, $distance_in_minutes)) return round($distance_in_minutes / 1440).' days';
		if (in_range(43200, 86399, $distance_in_minutes)) return 'about 1 month';
		if (in_range(86400, 525959, $distance_in_minutes)) return round($distance_in_minutes / 43200).' months';
		if (in_range(525960, 1051919, $distance_in_minutes)) return 'about 1 year';

		return 'over '.round($distance_in_minutes / 525960).' years';
	}

		function in_range($start, $end, $value)
		{
			return (($value >= $start) and ($value <= $end));
		}

?>