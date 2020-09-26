<?php
	echo 'The Agency';

	if(isset($_GET['newsid'])) {
		$newsid = escape_data((int)$_GET['newsid']);
		echo ' - ' . @mysql_result(@mysql_query("SELECT title FROM agency_news WHERE newsid='$newsid'"), 0, 'title');


	} else if(isset($_GET['page'])) {
		$page = escape_data((int)$_GET['page']);
		$query_page = "SELECT MenuName FROM agency_pages, agency_content WHERE agency_pages.Active='1' AND agency_pages.PageID=agency_content.PageID AND agency_pages.PageID='$page'";

		$result = @mysql_query($query_page);
		if($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
			echo ' - ' . $row['MenuName'];
		}
	} else if(isset($_GET['u'])) {
		$uid = escape_data((int)$_GET['u']);
		echo ' - ' . @mysql_result(@mysql_query("SELECT firstname FROM agency_profiles WHERE user_id='$uid'"), 0, 'firstname') . '\'s Profile';
	} else if(isset($pagetitle)) {
		echo ' - ' . $pagetitle;
	}
?>