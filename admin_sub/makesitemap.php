<?php
require_once ('../includes/mysql_connect.php'); // Connect to the database.

function sitemap() {
	$output = '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' . "\n";
	$output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
	
	$output .="	<url>" .
      "		<loc>http://www.theagencyonline.com/</loc>\n" .
      "		<lastmod>" . date('Y-m-d') . "</lastmod>\n" .
      "		<changefreq>weekly</changefreq>\n" .
      "		<priority>0.8</priority>\n" .
   	"	</url>";


	$query = "SELECT agency_pages.PageID, agency_content.last_update, agency_pages.MenuName FROM agency_pages, agency_content WHERE agency_pages.PageID=agency_content.PageID AND agency_pages.Active='1' AND agency_pages.WhoSees='All' ORDER BY agency_pages.PageID";
	$result = @mysql_query ($query);
	while ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.	
		$output .= "	<url>\n" . 
     	 "		<loc>http://www.theagencyonline.com/index2.php?page=" . $row['PageID'] . htmlentities("&title=" . $row['MenuName']) . "</loc>\n" . 
     	 "		<lastmod>" . date('Y-m-d', strtotime($row['last_update'])) . "</lastmod>\n" .
   		 "	</url>\n";
	}

	$output .= "	<url>\n" . 
     	 "		<loc>http://www.theagencyonline.com/news.php</loc>\n" . 
     	 "		<lastmod>" . date('Y-m-d') . "</lastmod>\n" .
   		 "	</url>\n";
	
	$output .= "	<url>\n" . 
     	 "		<loc>http://www.theagencyonline.com/home.php</loc>\n" . 
     	 "		<lastmod>" . date('Y-m-d') . "</lastmod>\n" .
   		 "	</url>\n";

	$query = "SELECT NewsID, last_update, Title FROM agency_news WHERE Active='1' ORDER BY NewsID DESC";
	$result = @mysql_query ($query);
	while ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.	
		$output .= "	<url>\n" . 
     	 "		<loc>http://www.theagencyonline.com/newsid.php?newsid=" . $row['NewsID'] . htmlentities("&title=" . $row['Title']) . "</loc>\n" . 
     	 "		<lastmod>" . date('Y-m-d', strtotime($row['last_update'])) . "</lastmod>\n" .
   		 "	</url>\n";
	}
	
	$query = "SELECT casting_id, post_date, job_title FROM agency_castings WHERE deleted='0' ORDER BY casting_id DESC";
	$result = @mysql_query ($query);
	while ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.	
		$output .= "	<url>\n" . 
     	 "		<loc>http://www.theagencyonline.com/news.php?castingid=" . $row['casting_id'] . htmlentities("&title=" . $row['job_title']) . "</loc>\n" . 
     	 "		<lastmod>" . date('Y-m-d', strtotime($row['post_date'])) . "</lastmod>\n" .
   		 "	</url>\n";
	}
	
	$query = "SELECT agency_profiles.user_id, agency_profiles.registration_date FROM agency_profiles, forum_users WHERE forum_users.user_id=agency_profiles.user_id AND forum_users.user_type='0' AND agency_profiles.account_type='talent' ORDER BY agency_profiles.user_id DESC";
	$result = @mysql_query ($query);
	while ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.	
		$output .= "	<url>\n" . 
     	 "		<loc>http://www.theagencyonline.com/profile.php?u=" . $row['user_id'] . "</loc>\n" . 
     	 "		<lastmod>" . date('Y-m-d', $row['registration_date']) . "</lastmod>\n" .
   		 "	</url>\n";
	}
	
	$output .= '</urlset>';
	
	$page = '../sitemap.xml';
	$content = stripslashes($output);
	$handle_write = fopen($page, 'w+');
	fwrite($handle_write, $content);
	fclose($handle_write);
} // end function

sitemap();

// mysql_close(); // Close the database connection.
?>