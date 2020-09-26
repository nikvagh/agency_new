<?php
	// function to calculate the distance between 2 points of latitude and longitude
	// returns # of miles 
	function distance($lat1, $lon1, $lat2, $lon2)
	{
		$t = $lon1 - $lon2;
		$d = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($t));
		$d = acos($d);
		$d = rad2deg($d);
		$m = $d * 60 * 1.1515;

		return $m;
	}
?>