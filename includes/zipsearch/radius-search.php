<?php
	// THIS WILL CREATE AN ARRAY CALLED $ziparray WHICH WILL BE A LIST OFF ALL ZIP CODES IN RANGE

	// include the functions to access the database and perform calculations
	require("database.php");
	require("functions.php");
	
	// retrieve the zip code and distance values from the form
	$zipcode = $_REQUEST['zipcode'];
	$miles = $_REQUEST['miles'];
	
	$query = "SELECT LATITUDE, LONGITUDE FROM agency_zip WHERE ZIP_CODE = '$zipcode'";

	if (!$result = mysql_query($query))
	{
		// There was an error in the sql statement
		print "there was an error in the sql statement, "; // .mysql_error()."<br><b>$sql</b>";
		exit;
	}
	elseif ($result == "")
	{
		// the zip code does not exists
		echo "The zip code was not found in the database";
	}
	else
	{
		// the zip code was found so process the data
		$myrow = mysql_fetch_array($result);
		
		$lat = $myrow['LATITUDE'];
		$lon = $myrow['LONGITUDE'];
		
		// get the min/max latitudes and longitudes for the radius search
		// you can comment this section out if you want to use the sql statement to perform the calculations
		$lat_range = $miles / 69.172;
		$lon_range = abs($miles / (cos($lon) * 69.172));
		
		$min_lat = $lat - $lat_range; 
		$max_lat = $lat + $lat_range;
		$min_lon = $lon - $lon_range;
		$max_lon = $lon + $lon_range;

		// it is possible to do the radius search and the distance calculation all at the same time
		
	//	$sql = "select * from << YOUR TABLE NAME >> AS z WHERE (SQRT((69.172 * (".$lat." - z.latitude)) * (69.172 * (".$lat." - z.latitude)) + (53.0 *(".$lon." - z.longitude)) * (53.0 *(".$lon." - z.longitude))) <= ".$miles." )"; 

		// apply the min/max coordinates to the sql query to only select those items within range
		$query = "SELECT * FROM agency_zip WHERE 
				((LATITUDE >= $min_lat AND LATITUDE <= $max_lat) AND
				(LONGITUDE >= $min_lon AND LONGITUDE <= $max_lon))";


		if (!$result2 = mysql_query($query))
		{
			// There was an error in the sql statement
			print "there was an error in the sql statement, "; // .mysql_error()."<br><b>$sql</b>";
			exit;
		}
		else
		{
			// records were returned so now check the distances to make sure they fall within the radius
			// printf ("%s - %s - %s<br><br>\n", "ZIP CODE", "CITY", "DISTANCE");
			$ziparray = array();
			while ($myrow = mysql_fetch_array($result2))
			{
				// check the distance to make sure it's less than the entered radius
				
				$dist = distance($lat, $lon, $myrow["LATITUDE"], $myrow["LONGITUDE"]);
				if ($dist <= $miles)
				{
					// the zip code is within the requested radius so print the results
					// printf ("%s - %s - %s<br>\n", $myrow["ZIP_CODE"], $myrow["CITY_NAME"], $dist);
					
					// create array of zips
					$ziparray[] = $myrow["ZIP_CODE"];
				}
			}
		}
	}
	// print_r($ziparray);
	// mysql_close($connection)
?>