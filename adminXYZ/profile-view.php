<?php
	include('header.php');

	$not_found = "N";
	if(isset($_GET['user_id']) && $_GET['user_id'] != ""){
		$user_id = $_GET['user_id'];

		$sql = "SELECT ap.account_type FROM agency_profiles ap 
				WHERE ap.user_id=".$user_id."";
		$result=mysql_query($sql);
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				// echo "<pre>";print_r($row);
				if($row['account_type'] == "talent"){
					$url = "profile-view-talent.php?user_id=".$user_id;
				}else if($row['account_type'] == "client"){
					// $url = "profile-view-client.php?user_id=".$user_id;
					$url = "profile-view-talent.php?user_id=".$user_id;
				}else if($row['account_type'] == "talent_manager"){
					// $url = "profile-view-talent_manager.php?user_id=".$user_id;
					$url = "profile-view-talent.php?user_id=".$user_id;
				}else if($row['account_type'] == "admin"){
					$url = "profile-view-admin.php?user_id=".$user_id;
				}
				header("Location: $url");
				exit();
			}
		}else{
			$not_found = "Y";
		}
	}else{
		$not_found = "Y";
	}

	if($not_found == "Y"){
		$url = "index.php";
		header("Location: $url");
		exit();
	}
?>