<?php
	// TESTING
	/* 
	if(is_admin()) {
		echo '<div id="debug" style="position:fixed; left:0; top:20px; overflow:auto; width:1200px; height:50px; color: white; background-color:black; padding:10px;">' . $_COOKIE['lightbox'] . '</div>';
	} */
	$page = "talent_search";
	$page_selected = "talent_search";
	include('header.php');
	include('../forms/definitions.php');
	include('../includes/agency_dash_functions.php');
?>

<?php
	$add_success = "";
	if(isset($_POST['user_to_lightbox_submit'])){

		// echo "1111";
		// echo "<br/>";
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$result_box = "Y";

		if($_POST['lightbox_id'] != ""){
			// old lightbox
			$lightbox_id = $_POST['lightbox_id'];
		}else{
			$timecode = time();
			$sql_lightbox_add = "INSERT INTO agency_lightbox
					SET 
					client_id = ".$_SESSION['user_id'].",
					lightbox_name = '".$_POST['title']."',
					lightbox_description = '".$_POST['description']."',
					timecode = '".$timecode."'
					";
			if(mysql_query($sql_lightbox_add)){
				$lightbox_id = mysql_insert_id();
			}
		}

		$l_users = explode(',',$_POST['users']);

		foreach($l_users as $usr){
			$lightvbox_user_check_sql = "select * from agency_lightbox_users alu
									WHERE lightbox_id = ".$lightbox_id."
									AND user_id = ".$usr."
									";
			$lightvbox_user_check_res = mysql_query($lightvbox_user_check_sql);
			if(mysql_num_rows($lightvbox_user_check_res) > 0){
				// as it is
				// echo "1111";
				// echo "<br/>";
				// echo "<pre>";
				// print_r($usr);
				// echo "</pre>";
				// $add_success = "Y";
			}else{
				$sql_user_to_lightbox_add = "INSERT INTO agency_lightbox_users
					SET 
					lightbox_id = ".$lightbox_id.",
					user_id = '".$usr."'
					";
				if(mysql_query($sql_user_to_lightbox_add)){
					$add_success = "Y";
				}
			}

			if($add_success == "Y"){
				$notification['success'] = "Users added To Lightbox Successfully";
			}
		}

		// echo "<pre>";
		// print_r($_POST);
		// exit;
	}

	define("PERPAGE", 12);// how many results to post per page
	require '../includes/PageNavigator.php';
	//max per page

	define("OFFSET", "offset");
	//get query string
	$offset=@escape_data((int)$_GET[OFFSET]);

	//check variable
	if (!isset($offset)){
		$recordoffset = 0;
	} else {
		//calc record offset
		$recordoffset = $offset*PERPAGE;
	}
	$result_box = "";

	if(isset($_POST['quick_serach'])){
		$result_box = "Y";
		unset($_SESSION['t_search']);
		$_SESSION['t_search']['age_start'] = $_POST['age_start'];
		$_SESSION['t_search']['age_end'] = $_POST['age_end'];
		$_SESSION['t_search']['gender'] = $_POST['gender'];

		$eth_array = array();
		if($_POST['ethnicity'] != ""){
			$eth_array[] = $_POST['ethnicity'];
		}
		$_SESSION['t_search']['ethnicity'] = $eth_array;
		$_SESSION['t_search']['location'] = $_POST['location'];
	}

	if(isset($_POST['submitsearch'])) {
		$result_box = "Y";

		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$_SESSION['t_search']['firstname'] = $_POST['firstname'];
		$_SESSION['t_search']['lastname'] = $_POST['lastname'];
		$_SESSION['t_search']['gender'] = $_POST['gender'];
		$_SESSION['t_search']['location'] = $_POST['location'];
		$_SESSION['t_search']['zip'] = $_POST['zip'];
		$_SESSION['t_search']['age_start'] = $_POST['age_start'];
		$_SESSION['t_search']['age_end'] = $_POST['age_end'];
		$_SESSION['t_search']['height_feet_start'] = $_POST['height_feet_start'];
		$_SESSION['t_search']['height_inches_start'] = $_POST['height_inches_start'];
		$_SESSION['t_search']['height_feet_end'] = $_POST['height_feet_end'];
		$_SESSION['t_search']['height_inches_end'] = $_POST['height_inches_end'];
		$_SESSION['t_search']['weight_start'] = $_POST['weight_start'];
		$_SESSION['t_search']['weight_end'] = $_POST['weight_end'];
		$_SESSION['t_search']['waist_start'] = $_POST['waist_start'];
		$_SESSION['t_search']['waist_end'] = $_POST['waist_end'];
		$_SESSION['t_search']['shoe_start'] = $_POST['shoe_start'];
		$_SESSION['t_search']['shoe_end'] = $_POST['shoe_end'];
		$_SESSION['t_search']['suit_start'] = $_POST['suit_start'];
		$_SESSION['t_search']['suit_end'] = $_POST['suit_end'];

		if(isset($_POST['shirt'])){
			$_SESSION['t_search']['shirt'] = $_POST['shirt'];
		}
		
		// $_SESSION['t_search']['neck_start'] = $_POST['neck_start'];
		// $_SESSION['t_search']['neck_end'] = $_POST['neck_end'];
		// $_SESSION['t_search']['sleeve_start'] = $_POST['sleeve_start'];
		// $_SESSION['t_search']['sleeve_end'] = $_POST['sleeve_end'];
		$_SESSION['t_search']['inseam_start'] = $_POST['inseam_start'];
		$_SESSION['t_search']['inseam_end'] = $_POST['inseam_end'];
		$_SESSION['t_search']['bust_start'] = $_POST['bust_start'];
		$_SESSION['t_search']['bust_end'] = $_POST['bust_end'];
		$_SESSION['t_search']['cup_start'] = $_POST['cup_start'];
		$_SESSION['t_search']['cup_end'] = $_POST['cup_end'];
		$_SESSION['t_search']['hips_start'] = $_POST['hips_start'];
		$_SESSION['t_search']['hips_end'] = $_POST['hips_end'];
		$_SESSION['t_search']['dress_start'] = $_POST['dress_start'];
		$_SESSION['t_search']['dress_end'] = $_POST['dress_end'];
		$_SESSION['t_search']['experience'] = $_POST['experience'];

		if(isset($_POST['hair_color'])){
			$_SESSION['t_search']['hair_color'] = $_POST['hair_color'];
		}
		if(isset($_POST['eye_color'])){
			$_SESSION['t_search']['eye_color'] = $_POST['eye_color'];
		}
		if(isset($_POST['ethnicity'])){
			$_SESSION['t_search']['ethnicity'] = $_POST['ethnicity'];
		}
		if(isset($_POST['category'])){
			$_SESSION['t_search']['category'] = $_POST['category'];
		}
		if(isset($_POST['category2'])){
			$_SESSION['t_search']['category2'] = $_POST['category2'];
		}
		if(isset($_POST['unions'])){
			$_SESSION['t_search']['unions'] = $_POST['unions'];
		}

		$_SESSION['t_search']['language'] = $_POST['language'];
		$_SESSION['t_search']['sports_music'] = $_POST['sports_music'];
		$_SESSION['t_search']['skills_other'] = $_POST['skills_other'];
	}

	if(isset($_POST['submitclear'])) {
		unset($_SESSION['t_search']);
		$result_box = "N";
	}

	if(isset($_POST['new_search'])) {
		// ECHO "1111";exit;
		unset($_SESSION['t_search']);
		$result_box = "N";
		$url = "clienthome.php";
		header('location:'.$url);
	}

	if(isset($_POST['change_search'])) {
		// ECHO "222";exit;
		// unset($_SESSION['t_search']);
		$result_box = "N";
		$url = "clienthome.php";
		header('location:'.$url);
	}

	// if(isset($_GET['mode'])){
	// 	if($_GET['mode'] == "new_search"){

	// 		echo "2222";exit;
	// 		unset($_SESSION['t_search']);

	// 		$url = "clienthome.php";
	// 		header('location:'.$url);
	// 	}
	// }

	if(isset($_GET['offset'])){
		echo "offset";
		$result_box = "Y";
	}


	// echo "<pre>";
	// print_r($_SESSION['t_search']);
	// echo "</pre>";


	// if (isset($_COOKIE['agencysearch']) && (isset($_GET['reset']) || isset($_POST['submitsearch']))) {
	// 	unset($_SESSION['currentsearch']);
	// 	unset($_SESSION['countsearch']);
	// 	foreach ($_COOKIE['agencysearch'] as $name => $value) {
	//         setcookie("agencysearch[$name]", "");
	// 		if(is_array($_COOKIE['agencysearch'][$name])) {
	// 			foreach ($_COOKIE['agencysearch'][$name] as $n => $v) {
	// 				setcookie("agencysearch[$name][$n]", "");
	// 			}
	// 		}
	//     }
	// 	if(isset($_GET['reset'])) {
	// 		$url = $_SERVER['REQUEST_URI'];
	// 		ob_end_clean(); // Delete the buffer.
	// 		header("Location: $url");
	// 		exit(); // Quit the script.
	// 	}
	// }

 //   	if (isset($_POST['submitsearch'])) { // Handle the form.
 //   		//	if(agency_account_type() == 'client' && is_active()) { // check if user is logged in and approved
 //   			$sql_start = "SELECT DISTINCT p.user_id, p.registration_date, p.firstname, p.lastname, p.experience, p.phone, p.resume, p.resume_text";

	// 		$sql = " FROM agency_profiles AS p, forum_users AS u";
	// 		if(!empty($_POST['category'])) {
	// 			$sql .= ", agency_profile_categories AS c";
	// 		}
	// 		if(!empty($_POST['unions'][0])) {
	// 			$sql .= ", agency_profile_unions AS un";
	// 		}
	// 		if(!empty($_POST['ethnicity'][0])) {
	// 			$sql .= ", agency_profile_ethnicities AS eth";
	// 		}			

	// 		$sql .= " WHERE p.user_id=u.user_id AND ";

	// 		if(!empty($_POST['category'])) {
	// 			$sql .= "c.user_id=p.user_id AND ";
	// 		}
	// 		if(!empty($_POST['unions'][0])) {
	// 			$sql .= "un.user_id=p.user_id AND ";
	// 		}
	// 		if(!empty($_POST['ethnicity'][0])) {
	// 			$sql .= "eth.user_id=p.user_id AND ";
	// 		}			

 //   			function addsql($var1, $var2=false) {
	// 			global $malearray;
	// 			global $femalearray;
	// 			$sql = '';
	// 			$run = true;

	// 			if(in_array($var1, $femalearray) || in_array($var1, $malearray)) {
	// 				if(isset($_POST['gender'])) {
	// 					if($_POST['gender'] == 'M') {
	// 						if(!in_array($var1, $malearray)) {
	// 							$run = false;
	// 						}
	// 					}
	// 					if($_POST['gender'] == 'F') {
	// 						if(!in_array($var1, $femalearray)) {
	// 							$run = false;
	// 						}
	// 					}
	// 				}
	// 			}

	// 			if($run) {
	// 				$value1 = escape_data($_POST[$var1]);
	// 				$value2 = escape_data($_POST[$var2]);
	// 				if(!empty($value1) && !empty($value2)) {
	// 					if($value2 >= $value1) {
	// 						$sql = "p.$var1 >= '$value1' AND p.$var1 <= '$value2' AND ";
	// 						setcookie("agencysearch[$var1]", $value1);
	// 						setcookie("agencysearch[$var2]", $value2);
	// 					}
	// 				} else 	if(!empty($value1) && empty($value2)) {
	// 					$sql = "p.$var1 = '$value1' AND ";
	// 					setcookie("agencysearch[$var1]", $value1);
	// 				}
	// 			}
	// 			return $sql;
	// 		}

	// 		if(!empty($_POST['firstname'])) {
 //   				$firstname = $_POST['firstname'];
 //   				setcookie("agencysearch[firstname]", $_POST['firstname']);
 //   				$var = mysql_real_escape_string($firstname);
 //   				$sql .= "p.firstname LIKE '$var%' AND ";
	// 		}
			
	// 		if(!empty($_POST['lastname'])) {
 //   				$lastname = $_POST['lastname'];
 //   				setcookie("agencysearch[lastname]", $_POST['lastname']);
 //   				$var = mysql_real_escape_string($lastname);
 //   				$sql .= "p.lastname LIKE '$var%' AND ";
	// 		}
			
	// 		if(!empty($_POST['location'])) {
 //   				$location = $_POST['location'];
 //   				setcookie("agencysearch[location]", $_POST['location']);
 //   				$var = mysql_real_escape_string($location);
 //   				$sql .= "p.location='$var' AND ";
	// 		}
			
	// 		// $sql .= addsql('firstname');
	// 		// $sql .= addsql('lastname');
	// 		$sql .= addsql('gender');
	// 		$sql .= addsql('weight', 'weight2');
	// 		$sql .= addsql('waist', 'waist2');
	// 		// $sql .= addsql('hair');
	// 		// $sql .= addsql('eyes');
	// 		$sql .= addsql('shoe', 'shoe2');

	// 		 $sql .= addsql('shirt', 'shirt2');
	// 		 $sql .= addsql('neck', 'neck2');
	// 		 $sql .= addsql('sleeve', 'sleeve2');
	// 		 $sql .= addsql('inseam', 'inseam2');
	// 		 $sql .= addsql('bust', 'bust2');
	// 		 $sql .= addsql('cup', 'cup2');
	// 		 $sql .= addsql('hips', 'hips2');
	// 		 $sql .= addsql('dress', 'dress2');

	// 		 $sql .= addsql('experience');
	// 		 // $sql .= addsql('ethnicity');
	// 		 $sql .= addsql('country');
			
	// 		/* if(!empty($_POST['state'])) {
	// 			 $sql .= addsql('state');
	// 		 }
	// 		if(!empty($_POST['city'])) {
	// 			$sql .= addsql('city');
	// 		} */


	// 		if(!empty($_POST['zipcode']) && !empty($_POST['miles'])) {
	// 			include('../includes/zipsearch/radius-search.php');
				
	// 			if(empty($ziparray)) {
	// 				// zip code is not found, then search for only zip code which will show no results
	// 				$sql .= "p.zip = '$zipcode' AND ";
	// 			} else {
	// 				$sql .= "p.zip IN ('" . implode("','", $ziparray) . "') AND ";
	// 				setcookie("agencysearch[zipcode]", $_POST['zipcode']);
	// 				setcookie("agencysearch[miles]", $_POST['miles']);
	// 			}
	// 		} else {
	// 			if(!empty($_POST['state'])) {
	// 				 $sql .= addsql('state');
	// 			 }
	// 			if(!empty($_POST['city'])) {
	// 				$sql .= addsql('city');
	// 			}
	// 		}




	// 		if(!empty($_POST['language'])) {
 //   				$language = $_POST['language'];
 //   				setcookie("agencysearch[language]", $_POST['language']);
 //   				$var = mysql_real_escape_string($language);
 //   				$sql .= "p.skills_language LIKE '%$var%' AND ";
	// 		}
	// 		if(!empty($_POST['sports_music'])) {
 //   				$sports_music = $_POST['sports_music'];
 //   				setcookie("agencysearch[sports_music]", $_POST['sports_music']);
 //   				$var = mysql_real_escape_string($sports_music);
 //   				$sql .= "p.skills_sports_music LIKE '%$var%' AND ";
	// 		}
	// 		if(!empty($_POST['skills_other'])) {
 //   				$skills_other = $_POST['skills_other'];
 //   				setcookie("agencysearch[skills_other]", $_POST['skills_other']);
 //   				$var = mysql_real_escape_string($skills_other);
 //   				$sql .= "p.skills_other LIKE '%$var%' AND ";
	// 		}

 //   			if(!empty($_POST['height_feet'])) {
 //   				$height = $_POST['height_feet'] * 12;
 //   				setcookie("agencysearch[height_feet]", $_POST['height_feet']);
 //   				if(!empty($_POST['height_inches'])) {
 //   					$height += $_POST['height_inches'];
 //   					setcookie("agencysearch[height_inches2]", $_POST['height_inches2']);
	// 			}
 //   				$var = (int) mysql_real_escape_string($height);
 //   				$sql .= "p.height >='$var' AND ";
	// 		}
 //   			if(!empty($_POST['height_feet2'])) {
 //   				$height = $_POST['height_feet2'] * 12;
 //   				setcookie("agencysearch[height_feet2]", $_POST['height_feet2']);
 //   				if(!empty($_POST['height_inches2'])) {
 //   					$height += $_POST['height_inches2'];
 //   					setcookie("agencysearch[height_inches]", $_POST['height_inches']);
	// 			}
 //   				$var = (int) mysql_real_escape_string($height);
 //   				$sql .= "p.height <='$var' AND ";
	// 		}

	// 		if($_POST['gender'] != 'F') {
	// 			if(!empty($_POST['suit']) && !empty($_POST['suitvariation'])) {
	// 				$suit = $_POST['suit'] + $_POST['suitvariation'];
	// 				// echo $suit;
	// 				setcookie("agencysearch[suit]", $_POST['suit']);
	// 				setcookie("agencysearch[suitvariation]", $_POST['suitvariation']);
	// 				$var = mysql_real_escape_string($suit);
	// 				$sql .= "p.suit >='$var' AND ";
	// 			}
	// 			if(!empty($_POST['suit2']) && !empty($_POST['suitvariation2'])) {
	// 				$suit = $_POST['suit2'] + $_POST['suitvariation2'];
	// 				setcookie("agencysearch[suit2]", $_POST['suit2']);
	// 				setcookie("agencysearch[suitvariation2]", $_POST['suitvariation2']);
	// 				$var = mysql_real_escape_string($suit);
	// 				$sql .= "p.suit <='$var' AND ";
	// 			}
	// 		}

	// 		$today = date('m-d');
	// 		$thisyear = date('Y');
 //   			if(!empty($_POST['age'])) {
 //   				$age = $_POST['age'];
 //   				setcookie("agencysearch[age]", $_POST['age']);
 //   				$var = (int) mysql_real_escape_string($age);
	// 			$var = ($thisyear - $var) . '-' . $today;
 //   				$sql .= "p.birthdate <='$var' AND ";
	// 		}
 //   			if(!empty($_POST['age2'])) {
 //   				$age2 = $_POST['age2'];
 //   				setcookie("agencysearch[age2]", $_POST['age2']);
 //   				$var = (int) mysql_real_escape_string($age2);
	// 			$var = ($thisyear - $var) . '-' . $today;
 //   				$sql .= "p.birthdate >='$var' AND ";
	// 		}

 //   			if(!empty($_POST['shirt']) && $_POST['gender'] != 'F') {
	// 			$shirt = array();
 //   				$shirt = $_POST['shirt'];
	// 			$num_shirts = sizeof($shirt);
	// 			$sql .= "(";
	// 			foreach($shirt as $value) {
	// 				$num_shirts--;
	// 				setcookie("agencysearch[shirt][$num_shirts]", $value);
	// 				$value = mysql_real_escape_string($value);
	// 				$sql .= "p.shirt ='$value'";
	// 				if($num_shirts > 0) {
	// 					$sql .= " OR ";
	// 				} else {
	// 					$sql .= ") AND ";
	// 				}
	// 			}
	// 		}
			
 //   			if(!empty($_POST['hair'])) {
	// 			$hair = array();
 //   				$hair = $_POST['hair'];
	// 			$num_hair = sizeof($hair);
	// 			$sql .= "(";
	// 			foreach($hair as $value) {
	// 				$num_hair--;
	// 				setcookie("agencysearch[hair][$num_hair]", $value);
	// 				$value = mysql_real_escape_string($value);
	// 				$sql .= "p.hair ='$value'";
	// 				if($num_hair > 0) {
	// 					$sql .= " OR ";
	// 				} else {
	// 					$sql .= ") AND ";
	// 				}
	// 			}
	// 		}

 //   			if(!empty($_POST['eyes'])) {
	// 			$eyes = array();
 //   				$eyes = $_POST['eyes'];
	// 			$num_eyes = sizeof($eyes);
	// 			$sql .= "(";
	// 			foreach($eyes as $value) {
	// 				$num_eyes--;
	// 				setcookie("agencysearch[eyes][$num_eyes]", $value);
	// 				$value = mysql_real_escape_string($value);
	// 				$sql .= "p.eyes ='$value'";
	// 				if($num_eyes > 0) {
	// 					$sql .= " OR ";
	// 				} else {
	// 					$sql .= ") AND ";
	// 				}
	// 			}
	// 		}

 //   			if(!empty($_POST['category'])) {
	// 			$category = array();
 //   				$category = $_POST['category'];
	// 			$num_category = sizeof($category);
	// 			$sql .= "(";
	// 			foreach($category as $value) {
	// 				$num_category--;
	// 				setcookie("agencysearch[category][$num_category]", $value);
	// 				$value = mysql_real_escape_string($value);
	// 				$sql .= "c.category ='$value'";
	// 				if($num_category > 0) {
	// 					$sql .= " OR ";
	// 				} else {
	// 					$sql .= ") AND ";
	// 				}
	// 			}
	// 		}

 //   			if(!empty($_POST['unions'][0])) {
	// 			$unions = array();
 //   				$unions = $_POST['unions'];
	// 			$unions = array_filter($unions);
	// 			$num_unions = sizeof($unions);
	// 			if($num_unions > 0) {
	// 				$sql .= "(";
	// 				foreach($unions as $value) {
	// 					$num_unions--;
	// 					setcookie("agencysearch[unions][$num_unions]", $value);
	// 					$value = mysql_real_escape_string($value);
	// 					$sql .= "un.union_name ='$value'";
	// 					if($num_unions > 0) {
	// 						$sql .= " OR ";
	// 					} else {
	// 						$sql .= ") AND ";
	// 					}
	// 				}
	// 			}
	// 		}
			
 //   			if(!empty($_POST['ethnicity'][0])) {
	// 			$ethnicity = array();
 //   				$ethnicity = $_POST['ethnicity'];
	// 			$ethnicity = array_filter($ethnicity);
	// 			$num_eth = sizeof($ethnicity);
	// 			if($num_eth > 0) {
	// 				$sql .= "(";
	// 				foreach($ethnicity as $value) {
	// 					$num_eth--;
	// 					setcookie("agencysearch[ethnicity][$num_eth]", $value);
	// 					$value = mysql_real_escape_string($value);
	// 					$sql .= "eth.ethnicity ='$value'";
	// 					if($num_eth > 0) {
	// 						$sql .= " OR ";
	// 					} else {
	// 						$sql .= ") AND ";
	// 					}
	// 				}
	// 			}
	// 		}				
		
	// }

?>

<style type="text/css">
	.navigator{
		text-align: center;
	}
</style>

<div id="page-wrapper">
    <!-- <div class="container-fluid"> -->
        <!-- Page Heading -->
		<div class="" id="main">

   				<h3>Talent Search </h3>
	    		<?php if(isset($notification['success'])){ ?>
			        <div class="alert alert-success" role="alert">
			            <?php echo $notification['success']; ?>
			        </div>
		        <?php } ?>
		        <?php if(isset($notification['error'])){ ?>
		            <div class="alert alert-danger" role="alert">
		                <?php echo $notification['error']; ?>
		            </div>
		        <?php } ?>

   				<div class="row">
   					<div class="col-sm-12 col-md-12">

   						<div class="box">
   							<div class="box-body">

								<?php
									// if(isset($sql) && isset($sql_start)) {
									if($result_box == "Y") {

										$cond = "";
										if(isset($_SESSION['t_search']['firstname']) && $_SESSION['t_search']['firstname'] != ""){
											$cond .=" AND ap.firstname like '%".$_SESSION['t_search']['firstname']."%'";
										}

										if(isset($_SESSION['t_search']['lastname']) && $_SESSION['t_search']['lastname'] != ""){
											$cond .=" AND ap.lastname like '%".$_SESSION['t_search']['lastname']."%'";
										}

										if(isset($_SESSION['t_search']['gender']) && $_SESSION['t_search']['gender'] != ""){
											$cond .=" AND ap.gender = '".$_SESSION['t_search']['gender']."' ";
										}

										if(isset($_SESSION['t_search']['location']) && $_SESSION['t_search']['location'] != ""){
											$cond .=" AND ap.location = '".$_SESSION['t_search']['location']."' ";
										}

										if(isset($_SESSION['t_search']['zip']) && $_SESSION['t_search']['zip'] != ""){
											$cond .=" AND ap.zip = '".$_SESSION['t_search']['zip']."' ";
										}

										if(isset($_SESSION['t_search']['age_start']) && $_SESSION['t_search']['age_start'] != ""){
											$cond .=" AND (YEAR(CURDATE()) - YEAR(birthdate)) >= '".$_SESSION['t_search']['age_start']."' ";
										}

										if(isset($_SESSION['t_search']['age_end']) && $_SESSION['t_search']['age_end'] != ""){
											$cond .=" AND (YEAR(CURDATE()) - YEAR(birthdate)) <= '".$_SESSION['t_search']['age_end']."' ";
										}

										// height start
										$h_feet_start = 0; $h_inches_start = 0; $h_feet_end = 0; $h_inches_end = 0;
										if(isset($_SESSION['t_search']['height_feet_start']) && $_SESSION['t_search']['height_feet_start'] != ""){
											$h_feet_start = $_SESSION['t_search']['height_feet_start']*12;
										}
										if(isset($_SESSION['t_search']['height_inches_start']) && $_SESSION['t_search']['height_inches_start'] != ""){
											$h_inches_start = $_SESSION['t_search']['height_inches_start'];
										}

										if(isset($_SESSION['t_search']['height_feet_end']) && $_SESSION['t_search']['height_feet_end'] != ""){
											$h_feet_end = $_SESSION['t_search']['height_feet_end']*12;
										}
										if(isset($_SESSION['t_search']['height_inches_end']) && $_SESSION['t_search']['height_inches_end'] != ""){
											$h_inches_end = $_SESSION['t_search']['height_inches_end'];
										}

										$height_start = $h_feet_start + $h_inches_start;
										$height_end = $h_feet_end + $h_inches_end;

										if($height_start > 0){
											$cond .=" AND ap.height >= '".$height_start."' ";
										}
										if($height_end > 0){
											$cond .=" AND ap.height <= '".$height_end."' ";
										}
										// height end 

										
										if(isset($_SESSION['t_search']['weight_start']) && $_SESSION['t_search']['weight_start'] != ""){
											$cond .=" AND ap.weight >= '".$_SESSION['t_search']['weight_start']."' ";
										}

										if(isset($_SESSION['t_search']['weight_end']) && $_SESSION['t_search']['weight_end'] != ""){
											$cond .=" AND ap.weight <= '".$_SESSION['t_search']['weight_end']."' ";
										}


										if(isset($_SESSION['t_search']['waist_start']) && $_SESSION['t_search']['waist_start'] != ""){
											$cond .=" AND ap.waist >= '".$_SESSION['t_search']['waist_start']."' ";
										}

										if(isset($_SESSION['t_search']['waist_end']) && $_SESSION['t_search']['waist_end'] != ""){
											$cond .=" AND ap.waist <= '".$_SESSION['t_search']['waist_end']."' ";
										}

										if(isset($_SESSION['t_search']['shoe_start']) && $_SESSION['t_search']['shoe_start'] != ""){
											$cond .=" AND ap.shoe >= '".$_SESSION['t_search']['shoe_start']."' ";
										}

										if(isset($_SESSION['t_search']['shoe_end']) && $_SESSION['t_search']['shoe_end'] != ""){
											$cond .=" AND ap.shoe <= '".$_SESSION['t_search']['shoe_end']."' ";
										}
										
										if(isset($_SESSION['t_search']['suit_start']) && $_SESSION['t_search']['suit_start'] != ""){
											$cond .=" AND ap.suit >= '".$_SESSION['t_search']['suit_start']."' ";
										}

										if(isset($_SESSION['t_search']['suit_end']) && $_SESSION['t_search']['suit_end'] != ""){
											$cond .=" AND ap.suit <= '".$_SESSION['t_search']['suit_end']."' ";
										}
										
										if(isset($_SESSION['t_search']['shirt']) && !empty($_SESSION['t_search']['shirt'])){
											$shirt_str = implode("','",$_SESSION['t_search']['shirt']);
											$cond .=" AND ap.shirt IN ('".$shirt_str."') ";
										}
										
										if(isset($_SESSION['t_search']['inseam_start']) && $_SESSION['t_search']['inseam_start'] != ""){
											$cond .=" AND ap.inseam >= '".$_SESSION['t_search']['inseam_start']."' ";
										}
										if(isset($_SESSION['t_search']['inseam_end']) && $_SESSION['t_search']['inseam_end'] != ""){
											$cond .=" AND ap.inseam <= '".$_SESSION['t_search']['inseam_end']."' ";
										}

										if(isset($_SESSION['t_search']['bust_start']) && $_SESSION['t_search']['bust_start'] != ""){
											$cond .=" AND ap.bust >= '".$_SESSION['t_search']['bust_start']."' ";
										}
										if(isset($_SESSION['t_search']['bust_end']) && $_SESSION['t_search']['bust_end'] != ""){
											$cond .=" AND ap.bust <= '".$_SESSION['t_search']['bust_end']."' ";
										}

										if(isset($_SESSION['t_search']['cup_start']) && $_SESSION['t_search']['cup_start'] != ""){
											$cond .=" AND ap.cup >= '".$_SESSION['t_search']['cup_start']."' ";
										}
										if(isset($_SESSION['t_search']['cup_end']) && $_SESSION['t_search']['cup_end'] != ""){
											$cond .=" AND ap.cup <= '".$_SESSION['t_search']['cup_end']."' ";
										}

										if(isset($_SESSION['t_search']['hips_start']) && $_SESSION['t_search']['hips_start'] != ""){
											$cond .=" AND ap.hips >= '".$_SESSION['t_search']['hips_start']."' ";
										}
										if(isset($_SESSION['t_search']['hips_end']) && $_SESSION['t_search']['hips_end'] != ""){
											$cond .=" AND ap.hips <= '".$_SESSION['t_search']['hips_end']."' ";
										}

										if(isset($_SESSION['t_search']['dress_start']) && $_SESSION['t_search']['dress_start'] != ""){
											$cond .=" AND ap.dress >= '".$_SESSION['t_search']['dress_start']."' ";
										}
										if(isset($_SESSION['t_search']['dress_end']) && $_SESSION['t_search']['dress_end'] != ""){
											$cond .=" AND ap.dress <= '".$_SESSION['t_search']['dress_end']."' ";
										}

										if(isset($_SESSION['t_search']['hair_color']) && !empty($_SESSION['t_search']['hair_color'])){
											$hair_color_str = implode("','",$_SESSION['t_search']['hair_color']);
											$cond .=" AND ap.hair_color IN ('".$hair_color_str."') ";
										}

										if(isset($_SESSION['t_search']['eye_color']) && !empty($_SESSION['t_search']['eye_color'])){
											$eye_color_str = implode("','",$_SESSION['t_search']['eye_color']);
											$cond .=" AND ap.eye_color IN ('".$eye_color_str."') ";
										}

										if(isset($_SESSION['t_search']['eye_color']) && !empty($_SESSION['t_search']['eye_color'])){
											$eye_color_str = implode("','",$_SESSION['t_search']['eye_color']);
											$cond .=" AND ap.eye_color IN ('".$eye_color_str."') ";
										}

										if(isset($_SESSION['t_search']['ethnicity']) && !empty($_SESSION['t_search']['ethnicity'])){
											$ethnicity_str = implode("','",$_SESSION['t_search']['ethnicity']);
											$cond .=" AND ap.ethnicity IN ('".$ethnicity_str."') ";
										}

										if(isset($_SESSION['t_search']['category']) && !empty($_SESSION['t_search']['category'])){
											$categoryAry = array();
											foreach($_SESSION['t_search']['category'] as $val){
												$categoryAry[] = "apc.category = '".$val."' ";
											}

											$cat_str = "";
											if(!empty($categoryAry)){
												$cat_str = implode(' OR ',$categoryAry);
												$cond .= " AND (".$cat_str.")";
											}
										}

										if(isset($_SESSION['t_search']['unions']) && !empty($_SESSION['t_search']['unions'])){
											$unionsAry = array();
											foreach($_SESSION['t_search']['unions'] as $val){
												if($val != ""){
													$unionsAry[] = "apu.unions = '".$val."' ";
												}
											}

											$union_str = "";
											if(!empty($unionsAry)){
												$union_str = implode(' OR ',$unionsAry);
												$cond .= " AND (".$union_str.")";
											}
										}
										
										if(isset($_SESSION['t_search']['language']) && $_SESSION['t_search']['language'] != "" ){
											// $cond .=" AND ap.language = '".$_SESSION['t_search']['language']."' ";
										}

										if(isset($_SESSION['t_search']['sports_music']) && $_SESSION['t_search']['sports_music'] != "" ){
											// $cond .=" AND ap.language = '".$_SESSION['t_search']['language']."' ";
										}

										if(isset($_SESSION['t_search']['skills_other']) && $_SESSION['t_search']['skills_other'] != "" ){
											// $cond .=" AND ap.language = '".$_SESSION['t_search']['language']."' ";
										}

										$order_by = "";
										if(isset($_GET['sort'])){
											$order_by .= "ORDER BY ";
											if($_GET['sort'] == "name"){
												$order_by .= " ap.firstname";
												if(isset($_GET['sort_type'])){
													$order_by .= " DESC";
												}else{
													$order_by .= " ASC";
												}
											}

											if($_GET['sort'] == "date"){
												$order_by .= " ap.created_at";
												if(isset($_GET['sort_type'])){
													$order_by .= " DESC";
												}else{
													$order_by .= " ASC";
												}
											}

											if($_GET['sort'] == "exp"){
												$order_by .= " ap.experience";
												if(isset($_GET['sort_type'])){
													$order_by .= " DESC";
												}else{
													$order_by .= " ASC";
												}
											}
										}

										// =================
										$select = "select fu.*,ap.* ";
										$sql = " from forum_users fu 
												LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id 
												LEFT JOIN agency_profile_categories apc ON apc.user_id = fu.user_id
												LEFT JOIN agency_profile_unions apu ON apu.user_id = fu.user_id
												WHERE
												ap.account_type='talent' AND fu.user_type='0' ".$cond." 
												GROUP BY fu.user_id
												".$order_by."
												";
										
										// $sql_count = "SELECT COUNT(*) from forum_users fu 
										// 				LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id 
										// 				WHERE ap.account_type='talent' AND fu.user_type='0' ORDER BY ap.created_at DESC";

										// $sql_count = "SELECT COUNT(*) ".$sql;

										$sql = $select.$sql;

										// $sql = $sql_start . $sql; // add first part for normal query
										// $_SESSION['currentsearch'] = $sql; // save this part of query for pagination
										// $_SESSION['countsearch'] = $sql_count;
										
										// $sqlsort = 'p.firstname ASC, p.user_id ASC'; // sets default sorting order
										?>
											<div class="text-center">

												<?php
													if(isset($_GET['sort']) && $_GET['sort'] == 'name' && isset($_GET['sort_type'])){
														$sort_type = "";
													}else{
														$sort_type = "&sort_type=reverse";
													}
													$name_sort = "clienthome.php?mode=search&sort=name".$sort_type."&offset=0";
												?>

												<a href="<?php echo $name_sort; ?>" class="btn btn-default btn-xs">A-Z:Z-A</a>&nbsp;&nbsp;&nbsp;

												<?php
													if(isset($_GET['sort']) && $_GET['sort'] == 'date' && isset($_GET['sort_type'])){
														$sort_type = "";
													}else{
														$sort_type = "&sort_type=reverse";
													}
													$date_sort = "clienthome.php?mode=search&sort=date".$sort_type."&offset=0";
												?>
												<a href="<?php echo $date_sort; ?>" class="btn btn-default btn-xs">Newest-Oldest:Oldest-Newest</a>&nbsp;&nbsp;&nbsp;

												<?php
													if(isset($_GET['sort']) && $_GET['sort'] == 'exp' && isset($_GET['sort_type'])){
														$sort_type = "";
													}else{
														$sort_type = "&sort_type=reverse";
													}
													$exp_sort = "clienthome.php?mode=search&sort=exp".$sort_type."&offset=0";
												?>
												<a href="<?php echo $exp_sort; ?>" class="btn btn-default btn-xs">Pro-NewFaces:NewFaces-Pro</a>

											</div>

											<br/>
											<!-- <br/> -->

										<?php
											$sql_count = $sql;
											$sql .= " LIMIT $recordoffset,". PERPAGE; 
										?>

										<?php 

											// echo "1111";
									// } else if(isset($_SESSION['currentsearch']) && isset($_SESSION['countsearch']) && isset($_GET['offset'])) {
										// if(!empty($_GET['reverse'])) {
										// 	$orderby = "p.firstname DESC, p.user_id DESC";
										// } else {
										// 	$orderby = "p.firstname ASC, p.user_id ASC";
										// }		
										// // echo 'Sort by: ';
										// if(!empty($_GET['sort'])) {
										// 	if($_GET['sort'] == 'date') {
										// 		if(!empty($_GET['reverse'])) {
										// 			$orderby = "p.payProcessedDate ASC";
										// 		} else {
										// 			$orderby = "p.payProcessedDate DESC";
										// 		}
										// 		echo '<a href="clienthome.php?mode=search&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">A-Z:Z-A</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=date&offset=0' . (!isset($_GET['reverse']) ? '&reverse=true' : '') . '" class="AGENCY_graybutton" style="font-size:12px; font-weight:bold" onclick="remind=false">Newest-Oldest:Oldest-Newest</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=exp&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Pro-NewFaces:NewFaces-Pro</a>';
										// 	}
										// 	if($_GET['sort'] == 'exp') {
										// 		if(!empty($_GET['reverse'])) {
										// 			$orderby = "p.experience ASC";
										// 		} else {
										// 			$orderby = "p.experience DESC";
										// 		}
										// 		echo '<a href="clienthome.php?mode=search&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">A-Z:Z-A</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=date&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Newest-Oldest:Oldest-Newest</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=exp&offset=0' . (!isset($_GET['reverse']) ? '&reverse=true' : '') . '" class="AGENCY_graybutton" style="font-size:12px; font-weight:bold" onclick="remind=false">Pro-NewFaces:NewFaces-Pro</a>';
										// 	}
										// } else {
										// 	echo '<a href="clienthome.php?mode=search&offset=0' . (!isset($_GET['reverse']) ? '&reverse=true' : '') . '" class="AGENCY_graybutton" style="font-size:12px; font-weight:bold" onclick="remind=false">A-Z:Z-A</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=date&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Newest-Oldest:Oldest-Newest</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=exp&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Pro-NewFaces:NewFaces-Pro</a>';
										// }
										
										// $sql = $_SESSION['currentsearch'] . " ORDER BY $orderby LIMIT $recordoffset,". PERPAGE;
										// $sql_count = $_SESSION['countsearch'];
									}
								?>
							
								<?php 
									// echo "<br/>";
									// echo "res="; $result_box;

									// if(isset($sql) && isset($sql_count)) { 
									if($result_box == "Y") {
											// echo 'Testing: ' . $sql;
											/*echo '<a href="ajax/lightbox_add.php?height=350&amp;width=300&amp;inlineId=hiddenModalContent" class="thickbox AGENCY_graybutton" style="float:right">add checked to lightbox</a>';
											echo '<a href="javascript:void(0)" onclick="uncheckAll(); uncheckAll(\'addme\'); remind=true;" class="AGENCY_graybutton" style="float:right; margin-right:10px">uncheck all</a>';
											echo '<a href="javascript:void(0)" onclick="checkAll(); checkAll(\'addme\'); remind=true;" class="AGENCY_graybutton" style="float:right; margin-right:10px">check all</a>';
											echo '<br clear="all" /><br clear="all" />';
											echo '<div class="AGENCYresultlist">';*/

											// get lightbox cookie and turn it into an array
											// if(!empty($_COOKIE['lightbox'])) {
											// 	$lightboxcookie = array();
											// 	$lightboxcookie = explode(',', $_COOKIE['lightbox']);
											// }

											// echo $sql;
											// echo "<br/>";
											// echo $sql_count;
											// echo "<br/>";

											$result_count = mysql_query($sql_count);
											$total_rec = mysql_num_rows($result_count);

											$result = mysql_query($sql);
											if(mysql_num_rows($result) == 0) {
												echo 'no results with the given search parameters<br /><br />';
											} 
											// else if(isset($_GET['configure'])) {
											// 	// SAVE QUERY FOR "NEWEST TALENT MATCHES
											// 	mysql_query("DELETE FROM agency_search_matches WHERE user_id='$profileid'");
											// 	$searchquery = addslashes($searchquery);
											// 	mysql_query("INSERT INTO agency_search_matches (user_id, searchquery) VALUES ('$profileid', '$searchquery')");
											// 	if(mysql_affected_rows() == 1) {
											// 		// saved successfully
											// 		echo '<div class="AGENCYsubmitmessage" style="border:0;">Congratulations!  Your Newest Talent Matches have been set!<br /><br />As new members fit your search parameters, they will appear in you Newest Talent Matches box on the right.</div>';
											// 	}
											// }
											?>

											<form action="javascript:void(0)" method="post" name="resultform">
												<!-- <table class="table datatable table-striped" cellpadding="10"> -->
													<div class="row-flex">
														<?php 
															$current = 1;
															$number_across = 3;
															$varemail="bookings@theagencyonline.com";
															$varphone="212-944-0801";
														?>

														<?php while($row = sql_fetchrow($result)) { ?>

															<!-- <tr> -->
																<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom:30px;">
																	<div class="card" style="height: 100%">

																		<?php
																			$uid = $row['user_id'];
																			$email = $row['user_email'];
																			$displayname = $row['firstname'].' '.$row['lastname'] ;

																			if(file_exists('../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/'. '128x128_' . $row['user_avatar'])){
												                       			$profile_pic = '../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/'. '128x128_' . $row['user_avatar'];
											                      			}else{
											                      				$profile_pic = '../images/friend.gif';
											                      			}
																		?>
																		<a style="height: 128px;"><img src="<?php echo $profile_pic; ?>" /></a>
																		<br />

																		<?php
																			// check to see if this user should already be checked
																			// $checkme = '';
																			// if(isset($lightboxcookie)) {
																			// 	if(in_array($uid, $lightboxcookie)) {
																			// 		$checkme = 'checked ';
																			// 	}
																			// }
																		?>
																		
																		<label>
																			<input type="checkbox" id="<?php echo 'addme_' . $uid; ?>" name="addme[<?php echo $uid; ?>]" value="<?php echo $uid; ?>" class="user_check" />
																			<?php echo $displayname; ?>
																		</label>

																		<p>
																			<img src="<?php echo '../images/' . $experienceimages[$row['experience']] . '.gif'; ?>" 
																				onmouseout="document.getElementById('experience_popup').style.display='none'" 
																				onmouseover="document.getElementById('experience_popup').style.display='' 
																			">
																		</p>
																		<br />

																		<p>
																			<a href="./ajax/compcard_mini.php?u=<?php echo $uid; ?>&height=400&amp;width=450" class="thickbox"></a>
																		</p>
																		<p>
																			<a href="<?php echo 'mailto:' . $row['user_email']; ?>"><?php echo $row['user_email']; ?></a>
																		</p>

																		<?php 
																			if(!empty($row['phone']) && agency_privacy($uid, 'phone')) {
																				echo '<p>' . $row['phone'] .'</p>';
																			}
																		?>

																		<?php if(!empty($row['resume'])) { ?>
																			<?php
																				$resume_file = "";
																				if(file_exists('../uploads/users/' . $row['user_id'] . '/resume/'. $row['resume'])){
													                       			$resume_file = '../uploads/users/' . $row['user_id'] . '/resume/'. $row['resume'];
													                       			
												                      			}
																			?>
																			<?php if($resume_file != "") { ?>
																				<p>
																					<a href="<?php echo $resume_file; ?>" target="_blank">
																						<img src="../images/resume1.gif" border="0" style="padding-top:5px;" >
																					</a>
																				</p>
																			<?php } ?>
																		<?php } ?>

																		<?php 
																		// check for reel/vo
																		if( mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vo WHERE user_id='$uid'"),0) || mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_reel WHERE user_id='$uid'"),0 )) {
																			?>	
																				<p>
																					<a target="_blank" href="<?php echo 'profile-view.php?user_id='.$uid; ?>#reel">
																						<img src="images/reelVO.gif" border="0" style="padding-top:5px;" >
																					</a>
																				</p>
																		<?php } ?>

																		<?php 
																			// UNION STATUS
																			$sql4 = "SELECT * FROM agency_profile_unions WHERE user_id='$uid'";
																			$result4=mysql_query($sql4);
																			$num_results4 = mysql_num_rows($result4);
																			$current4 = 1;
																		?>
																		<?php if($num_results4) { ?>
																			<p>
																				<span class="AGENCYCompCardLabel">Union: </span>
																				<span class="AGENCYCompCardStat">
																					<?php
																						while($row4 = sql_fetchrow($result4)) {
																							echo escape_data($row4['union_name']);
																							if($current4 < $num_results4) echo ', ';
																							$current4++;
																						}
																					?>
																				</span>
																			</p>
																		<?php } ?>

																	</div>
																</div>

																<?php
																	if($current == $number_across) {
																		$current = 1;
																	} else {
																		$current++;
																	}
																?>
															<!-- </tr> -->

														<?php } ?>

													</div>
												<!-- </table> -->
											</form>

											<?php
											echo '<form action="javascript:void(0)" method="post" name="resultform">';
												echo '<table width="100%" cellpadding="10">';
												$current = 1;
												$number_across = 3;
												$varemail="bookings@theagencyonline.com";
												$varphone="212-944-0801";
														
												while($row = sql_fetchrow($result)) {
													if($current == 1) {
														echo '<tr>';
													}			
													echo '<td align="center" valign="top">';
													$uid = $row['user_id'];
													$displayname = $row['firstname'];
													if(agency_privacy($uid, 'lastname')) {
														$displayname .= ' ' . $row['lastname'];
													}
													$displayname = '<span style="color:' . $experiencecolors[$row['experience']] . '">' . $displayname . '</span>';
													$posterfolder = 'images/' . $uid . '_' . $row['registration_date'] . '.jpg';//print_r($posterfolder);die();
													$email = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$uid'"), 0, 'user_email');
													echo '<img src="';
														if(file_exists($posterfolder . 'avatar.jpg')) {
															echo   $posterfolder;
														} else if(file_exists($posterfolder . 'avatar.gif')) {
															echo   $posterfolder . 'avatar.gif';
														} else {
															echo $posterfolder;
														}
													
													echo '" /><br />' .
														'<input type="checkbox" id="addme' . $uid . '" name="addme' . $uid . '" onclick="remind=true; lightbox_check(\'lightbox\', this, \'' . $uid . '\');" ' . $checkme . '/><b>' .
														$displayname . '</b><br /><img src="images/' . $experienceimages[$row['experience']] . '.gif" onmouseout="document.getElementById(\'experience_popup\').style.display=\'none\'" onmouseover="document.getElementById(\'experience_popup\').style.display=\'\'"><br /><br />' .
														'<a href="./ajax/compcard_mini.php?u=' . $uid . '&height=400&amp;width=450" class="thickbox"></a><br />' .
														'<a href="mailto:' . $varemail . '">' . $varemail . '</a>';
													if(!empty($row['phone']) && agency_privacy($uid, 'phone')) {
														echo '<br />' . $varphone;
													}
													
												
													$resumeicon = false;
													if(!empty($row['resume'])) {
														if(file_exists($posterfolder . '/' . $row['resume'])) {
															echo '<br /><a href="' . $posterfolder . $row['resume'] . '" target="_blank"><img src="images/resume1.gif" border="0" style="padding-top:5px;" ></a>';
															$resumeicon = true;
														}
													}
													
													// echo "Dddd";
													// check for reel/vo
													if( mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vo WHERE user_id='$uid'"),0) || mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_reel WHERE user_id='$uid'"),0 )) {
															if($resumeicon) {
																echo '&nbsp;&nbsp;';
															} else {
																echo '<br />';
															}
															echo '<a target="_blank" href="profile-view.php?&user_id=' . $uid . '">
															<img src="images/reelVO.gif" border="0" style="padding-top:5px;" >
															</a>';
													}
														
													// UNION STATUS
													 $sql4 = "SELECT * FROM agency_profile_unions WHERE user_id='$uid'";
													 $result4=mysql_query($sql4);
													 $num_results4 = mysql_num_rows($result4);
													 $current4 = 1;
													 if($num_results4) {
														echo '<br /><span class="AGENCYCompCardLabel">Union: </span><span class="AGENCYCompCardStat">';
														while($row4 = sql_fetchrow($result4)) {
															echo escape_data($row4['union_name']);
															if($current4 < $num_results4) echo ', ';
															$current4++;
														}
														echo '</span>';
													 }	
													 
													
													if($current == $number_across) {
														echo '</tr>';
														$current = 1;
													} else {
														$current++;
													}
															
																	 
													// echo '</div><br clear="all" /><hr style="clear:both">';
												}
												echo '</table></form><br clear="all" />';

												// $totalrecords = @mysql_result(mysql_query($sql_count), 0);

												$totalrecords = $total_rec;

												$numpages = ceil($totalrecords/PERPAGE);
												$otherparameter = '&mode=search';
												//create category parameter
												// if(!empty($_GET['reverse'])) {
												// 	$otherparameter .= '&reverse=true';
												// }
												if(!empty($_GET['sort_type'])) {
													$otherparameter .=  '&sort_type=' . escape_data($_GET['sort_type']);
												}
												if(!empty($_GET['sort'])) {
													$otherparameter .= '&sort=' . escape_data($_GET['sort']);
												}
												// $otherparameter .= '" onclick="remind=false;';

												// echo $otherparameter;
												// echo "<br/>";
												//create if needed
												if($numpages>1){
												  	//create navigator
												  	$nav = new PageNavigator($pagename, $totalrecords, PERPAGE,
													$recordoffset, 20, $otherparameter);
												    echo $nav->getNavigator();
												}

												?>
												<br/>

												<div class="row">
													<div class="col-sm-6">
														<form action="" method="post">
															<input type="submit" name="new_search" class="btn btn-theme btn-flat" value="New Search" />
															<input type="submit" name="change_search" class="btn btn-theme btn-flat" value="Change Search" />
														</form>
													</div>

													<div class="col-sm-6 text-right">
														<div class="btn-group">
															<button class="btn btn-primary btn-flat check_all_btn" value="">Check All</button>
															<button class="btn btn-warning btn-flat uncheck_all_btn" value="">Uncheck All</button>
															<button class="btn btn-success btn-flat add_to_lightbox_btn" value="">Add Checked To Lightbox</button>
														</div>
													</div>
												</div>

												<?php
												 if(isset($_GET['configure'])) {
													echo ' or <a href="clienthome.php?mode=search&configure=true" class="AGENCY_graybutton">reconfigure</a>';
												}

												// echo '<script type="text/javascript">
												// 		var ProcessArray = new Array();
												// 		if(checkCookie(\'lightbox\')) { // if cookie already set, reminder is active
												// 			var remind = true;
												// 		} else {
												// 			var remind = false;
												// 		}
												// 		window.onbeforeunload = exitCheck;
														
												// 		// this is causing problems in some browsers where when you close the ThickBox window it thinks the page is being exited and kills the cookie.  Bad.
												// 		// window.onunload = exitCheck2;
												// 		function exitCheck(evt){
												// 			if(remind) {
												// 				return "If you have checked off talent which have not yet been added to a lightbox, you may wish to do so at this point.";
												// 				// deleteCookie(\'lightbox\');
												// 			}
												// 		}
														
												// 		/* function exitCheck2(evt){
												// 			if(remind) {
												// 				deleteCookie(\'lightbox\');
												// 			}
												// 		} */
														
												// </script>';

												/*echo '<a href="ajax/lightbox_add.php?height=350&amp;width=300&amp;inlineId=hiddenModalContent" class="thickbox AGENCY_graybutton" style="float:right">add checked to lightbox</a>';
												echo '<a href="javascript:void(0)" onclick="uncheckAll(); uncheckAll(\'addme\'); remind=true;" class="AGENCY_graybutton" style="float:right; margin-right:10px">uncheck all</a>';
												echo '<a href="javascript:void(0)" onclick="checkAll(); checkAll(\'addme\'); remind=true;" class="AGENCY_graybutton" style="float:right; margin-right:10px">check all</a>';
												echo '<br clear="all" /></div>';*/
												?>

								<?php } else { ?>

										<!-- <form name="searchit" action="clienthome.php?mode=search" method="post" style="display:none" >
											<input type="hidden" name="submitsearch">
										</form> -->

										<!--  *******************************   START SEARCH FORM   *******************************   -->
										<form action="clienthome.php?mode=search<?php if(isset($_GET['configure'])) echo '&configure=true'; ?>" method="post" name="searchform">

									    	<div class="row">
									    		<div class="col-sm-12">
											        	<!-- <span class="AGENCYRed" style="font-size:12px">Enter Search Parameters (all fields optional):&nbsp;&nbsp;&nbsp;</span> -->
														
														<?php //if(is_active()) { ?>
															<!-- <input type="button" value="BROWSE" onclick="document.searchit.submit()" /> -->
																<!-- &nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="<?php //if(isset($_GET['configure'])) echo 'CONFIGURE'; else echo 'SEARCH'; ?>" name="submitsearch" /> -->
															<?php //if(isset($_COOKIE['agencysearch'])) { ?>
											    				<!-- &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="CLEAR ALL FIELDS<?php //if(isset($_GET['configure'])) echo '/CANCEL'; ?>" onclick="document.location='clienthome.php?mode=search&reset=true'" /> -->
															<?php //} else if(isset($_GET['configure'])) { ?>
												    			<!-- &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="CANCEL" onclick="document.location='clienthome.php?mode=search&reset=true'" /> -->
															<?php //} ?>
														<?php //}else{ ?>
														    <!-- <input type="button" value="Search"> -->
														<?php //} ?>
										

														<table width="50%" border="0" cellpadding="3" cellspacing="2" bgcolor="white" align="left">
														   	<tr>
														    	<td class="AGENCYregtableleft" width="25%">First Name:</td>
														    	<td class="AGENCYregtableright">
															    	<input type="text" name="firstname" value="<?php if (isset($_SESSION['t_search']['firstname'])){ echo $_SESSION['t_search']['firstname']; } ?>" />
															    </td>
															</tr>
														  	<tr>
															    <td class="AGENCYregtableleft">Last Name:</td>
															    <td class="AGENCYregtableright">
															    	<input type="text" name="lastname" value="<?php if (isset($_SESSION['t_search']['lastname'])){ echo $_SESSION['t_search']['lastname']; } ?>" />
															    </td>
															</tr>

															<tr>
															    <td class="AGENCYregtableleft">Gender:</td>
															    <td class="AGENCYregtableright">
															    	<select name="gender">
																    	<option value="" > Any</option>
																    	<option value="M" <?php if (isset($_SESSION['t_search']['gender']) && $_SESSION['t_search']['gender'] == "M") { echo "selected"; } ?> > Male</option>
																    	<option value="F" <?php if (isset($_SESSION['t_search']['gender']) && $_SESSION['t_search']['gender'] == "F") { echo "selected"; } ?> > Female</option>
																    	<option value="Transgender" <?php if (isset($_SESSION['t_search']['gender']) && $_SESSION['t_search']['gender'] == "Transgender") { echo "selected"; } ?> > Transgender</option>
															    	</select>
															    </td>
														  	</tr>

															<tr>
														    	<td class="AGENCYregtableleft">Location:</td>
														    	<td class="AGENCYregtableright">
														    		<select name="location">
														    		<option value="">Any</option>
																		<?php for($i=0; isset($locationarray[$i]); $i++) { ?>
																			<option value="<?php echo $locationarray[$i]; ?>" <?php if (isset($_SESSION['t_search']['location']) && $_SESSION['t_search']['location'] == $locationarray[$i]) { echo "selected"; } ?>
																				><?php echo $locationarray[$i]; ?></option>
																		<?php } ?>
															    	</select>
														  		</td>
														  	</tr>
														      
														      
														      		<!--
																	     <tr>
																	        <td class="AGENCYregtableleft">Location:</td>
																	        <td class="AGENCYregtableright">
																	<?php
																	$sql = "SELECT DISTINCT country FROM agency_cities WHERE country IS NOT NULL AND country<>'' AND country<>'United States' ORDER BY country";
																	$result = @mysql_query($sql);
																	if(mysql_num_rows($result) >= 0) {
																	?>
																	<select style="width:186px" name="country" onchange="if(this.value == 'United States') { document.getElementById('AGENCY_search_city').innerHTML=''; loaddiv('AGENCY_search_state', false, 'ajax/statelist.php?country='+this.value+'&'); } else { document.getElementById('AGENCY_search_state').innerHTML=''; loaddiv('AGENCY_search_city', false, 'ajax/citylist.php?country='+this.value+'&'); }">
																			<option value=""> - Any Country - </option>
																	        <option value="United States"<?php if($country == 'United States') echo ' selected'; ?>>United States</option>
																	<?php
																		while ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) { // If there are projects
																			echo '<option value="' . $row['country'] .'"';
																			if($country == $row['country']) echo ' selected="selected"';
																			echo '>' . $row['country'] . '</option>';
																		}
																	?>
																	        </select><br />
																	<?php
																	} else {
																	?>
																	<input type="hidden" name="country" value="United States" />
																	<?php
																	}
																	?>
																	<span id="AGENCY_search_state"></span>
																	<span id="AGENCY_search_city"></span>
																	<?php
																	$endscript .= '<script type="text/javascript">
																	if(document.searchform.country.value == \'United States\') {
																		loaddiv(\'AGENCY_search_state\', false, \'ajax/statelist.php?country=\'+document.searchform.country.value+\'&\');
																		loaddiv(\'AGENCY_search_city\', false, \'ajax/citylist.php?state=' . $_COOKIE['agencysearch']['state'] . '&\', true);
																	} else {
																		loaddiv(\'AGENCY_search_city\', false, \'ajax/citylist.php?country=\'+document.searchform.country.value+\'&\');
																	}
																	</script>';
																	?>
																	        </td>
																	      </tr>
																	-->	 

															<tr>
														        <!-- <td class="AGENCYregtableleft">Zip Search</td> -->
														        <td class="AGENCYregtableright">Zip Code:</td>
														        <td class="AGENCYregtableright">
															    	<input type="text" name="zip" id="zip" value="<?php if (isset($_SESSION['t_search']['zip']) && $_SESSION['t_search']['zip']) { echo $_SESSION['t_search']['zip']; } ?>" />
																    <!-- Distance:
																	<select name="miles" id="miles">
																		<option value="1"<?php //if (!empty($miles)) { if($miles == '1') echo ' selected'; } ?> >1 mile</option>
																		<option value="5"<?php //if (!empty($miles)) { if($miles == '5') echo ' selected'; } ?> >5 miles</option>
																		<option value="10"<?php //if (!empty($miles)) { if($miles == '10') echo ' selected'; } ?> >10 miles</option>
																		<option value="25"<?php //if (!empty($miles)) { if($miles == '25') echo ' selected'; } ?> >25 miles</option>
																		<option value="50"<?php //if (!empty($miles)) { if($miles == '50') echo ' selected'; } ?> >50 miles</option>
																		<option value="100"<?php //if (!empty($miles)) { if($miles == '100') echo ' selected'; } ?> >100 miles</option>
																		<option value="500"<?php //if (!empty($miles)) { if($miles == '500') echo ' selected'; } ?> >500 miles</option>
																		<option value="1000"<?php //if (!empty($miles)) { if($miles == '1000') echo ' selected'; } ?> >1000 miles</option>
																		<option value="">Worldwide</option>
																	</select>	 -->  
																</td>
															</tr>
															  
														    <tr>
														        <td class="AGENCYregtableleft">Age:</td>
														        <td class="AGENCYregtableright">
														        <select class="thin" name="age_start">
															        <option value="">Any</option>
																	<?php for($i=1; $i<=120; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['age_start']) && $_SESSION['t_search']['age_start'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																</select>
																&nbsp;&nbsp;to&nbsp;&nbsp;
														        <select class="thin" name="age_end">
															        <option value="">Any</option>
																	<?php for($i=1; $i<=120; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['age_end']) && $_SESSION['t_search']['age_end'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																</select> years
														        </td>
														    </tr>

														    <tr>
														        <td class="AGENCYregtableleft vertical-top">Height:</td>
														        <td class="AGENCYregtableright">
															        <select class="thin" name="height_feet_start">
																        <option value="">Any</option>
																		<?php for($i=1; $i<=7; $i++) { ?>
																			<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['height_feet_start']) && $_SESSION['t_search']['height_feet_start'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select> feet

																	<?php 
																		// echo "??=".$_SESSION['t_search']['height_inches_start'];
																		// echo "<br/>";
																		// for($hi_start=0; $hi_start<=11; $hi_start++) { 
																		// echo "i=".$hi_start;
																		// echo "<br/>";
																	?>

																	<?php //if (isset($_SESSION['t_search']['height_inches_start']) && $_SESSION['t_search']['height_inches_start'] === $hi_start) { echo "selected"; } ?>
																	<?php //} ?>

																	<select class="thin" name="height_inches_start">
																		<option value="">Any</option>
																		<?php for($hi_start=0; $hi_start<=11; $hi_start++) { ?>
																			<option value="<?php echo $hi_start; ?>" <?php if (isset($_SESSION['t_search']['height_inches_start']) && $_SESSION['t_search']['height_inches_start'] === $hi_start) { echo "selected"; } ?>>
																				<?php echo $hi_start; ?>
																			</option>
																		<?php } ?>
																	</select> inches

																	<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to<br />

																	<select class="thin" name="height_feet_end">
																	 	<option value="">Any</option> 
																		<?php for($i=1; $i<=7; $i++) { ?>
																			<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['height_feet_end']) && $_SESSION['t_search']['height_feet_end'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select> feet

																	<select class="thin" name="height_inches_end">
																		<option value="">Any</option>
																		<?php for($i=0; $i<=11; $i++) { ?>
																			<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['height_inches_end']) && $_SESSION['t_search']['height_inches_end'] === $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select> inches
														        </td>
														    </tr>

														    <tr>
														        <td class="AGENCYregtableleft">Weight:</td>
														        <td class="AGENCYregtableright">
															        <select class="thin" name="weight_start">
															        <option value="">Any</option>
																	<?php for($i=10; $i<=800; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['weight_start']) && $_SESSION['t_search']['weight_start'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																	</select>
																	&nbsp;&nbsp;to&nbsp;&nbsp;
															        <select class="thin" name="weight_end">
															        <option value="">Any</option>
																	<?php for($i=10; $i<=800; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['weight_end']) && $_SESSION['t_search']['weight_end'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																	</select> pounds
														        </td>
														    </tr>

														    <tr>
														        <td class="AGENCYregtableleft">Waist:</td>
														        <td class="AGENCYregtableright">
																<select class="thin" name="waist_start">
																	<option value="">Any</option>
																	<?php for($i=20; $i<=60; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['waist_start']) && $_SESSION['t_search']['waist_start'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																</select>
																&nbsp;&nbsp;to&nbsp;&nbsp;

																<select class="thin" name="waist_end">
																<option value="">Any</option>
																<?php for($i=20; $i<=60; $i++) { ?>
																	<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['waist_end']) && $_SESSION['t_search']['waist_end'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																<?php } ?>
																</select> inches
														        </td>
														    </tr>

														    <tr>
														        <td class="AGENCYregtableleft">Shoe Size:</td>
														        <td class="AGENCYregtableright">
																<select class="thin" name="shoe_start">
																<option value="">Any</option>
																	<?php for($i=1; $i<=21; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['shoe_start']) && $_SESSION['t_search']['shoe_start'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																</select>
																&nbsp;&nbsp;to&nbsp;&nbsp;
																<select class="thin" name="shoe_end">
																<option value="">Any</option>
																<?php for($i=1; $i<=21; $i++) { ?>
																	<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['shoe_end']) && $_SESSION['t_search']['shoe_end'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																<?php } ?>
																</select> US sizes
														        </td>
														    </tr>

														    <tr class="maleclass">
														        <td class="AGENCYregtableleft">Suit:</td>
														        <td class="AGENCYregtableright">
																<select class="thin" name="suit_start">
																	<option value="">Any</option>
																	<?php for($i=30; $i<=60; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['suit_start']) && $_SESSION['t_search']['suit_start'] == $i) { echo "selected"; } ?> ><?php echo $i; ?></option>
																	<?php } ?>
																</select>
																<!-- &nbsp;<select class="thin" name="suitvariation">
																	<option value="0.1" <?php //if(!empty($suitvariation)) { if($suitvariation == '0.1') echo 'selected="selected"'; } ?>>XS</option>
																	<option value="0.2" <?php //if(!empty($suitvariation)) { if($suitvariation == '0.2') echo 'selected="selected"'; } ?>>S</option>
																	<option value="0.3" <?php //if(!empty($suitvariation)) { if($suitvariation == '0.3') echo 'selected="selected"'; } ?>>R</option>
																	<option value="0.4" <?php //if(!empty($suitvariation)) { if($suitvariation == '0.4') echo 'selected="selected"'; } ?>>L</option>
																	<option value="0.5" <?php //if(!empty($suitvariation)) { if($suitvariation == '0.5') echo 'selected="selected"'; } ?>>XL</option>
																	<option value="0.6" <?php //if(!empty($suitvariation)) { if($suitvariation == '0.6') echo 'selected="selected"'; } ?>>XXL</option>
																</select> -->

																<!-- <br /> -->
																&nbsp;&nbsp;to&nbsp;&nbsp;
																<!-- <br /> -->
																<select class="thin" name="suit_end">
																	<option value="">Any</option>
																	<?php for($i=30; $i<=60; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['suit_end']) && $_SESSION['t_search']['suit_end'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																</select>
																<!-- &nbsp;<select class="thin" name="suitvariation2">
																	<option value="0.1" <?php //if(!empty($suitvariation2)) { if($suitvariation2 == '0.1') echo 'selected="selected"'; } ?>>XS</option>
																	<option value="0.2" <?php //if(!empty($suitvariation2)) { if($suitvariation2 == '0.2') echo 'selected="selected"'; } ?>>S</option>
																	<option value="0.3" <?php //if(!empty($suitvariation2)) { if($suitvariation2 == '0.3') echo 'selected="selected"'; } ?>>R</option>
																	<option value="0.4" <?php //if(!empty($suitvariation2)) { if($suitvariation2 == '0.4') echo 'selected="selected"'; } ?>>L</option>
																	<option value="0.5" <?php //if(!empty($suitvariation2)) { if($suitvariation2 == '0.5') echo 'selected="selected"'; } ?>>XL</option>
																	<option value="0.6" <?php //if(!empty($suitvariation2)) { if($suitvariation2 == '0.6') echo 'selected="selected"'; } ?>>XXL</option>
																</select> --> US sizes
														        </td>
														    </tr>

														    <tr class="maleclass">
														        <td class="AGENCYregtableleft vertical-top">Shirt:</td>
														        <td class="AGENCYregtableright">
																<input type="checkbox" name="shirt[]" value="S" <?php if (isset($_SESSION['t_search']['shirt']) && in_array('S',$_SESSION['t_search']['shirt'])){ echo 'checked'; } ?> />Small<br />
																<input type="checkbox" name="shirt[]" value="M" <?php if (isset($_SESSION['t_search']['shirt']) && in_array('M',$_SESSION['t_search']['shirt'])){ echo 'checked'; } ?> />Medium<br />
																<input type="checkbox" name="shirt[]" value="L" <?php if (isset($_SESSION['t_search']['shirt']) && in_array('L',$_SESSION['t_search']['shirt'])){ echo 'checked'; } ?> />Large<br />
																<input type="checkbox" name="shirt[]" value="XL" <?php if (isset($_SESSION['t_search']['shirt']) && in_array('XL',$_SESSION['t_search']['shirt'])){ echo 'checked'; } ?> />X-Large<br />
																<input type="checkbox" name="shirt[]" value="XXL" <?php if (isset($_SESSION['t_search']['shirt']) && in_array('XXL',$_SESSION['t_search']['shirt'])){ echo 'checked'; } ?> />XX-Large<br />
																<input type="checkbox" name="shirt[]" value="Other" <?php if (isset($_SESSION['t_search']['shirt']) && in_array('Other',$_SESSION['t_search']['shirt'])){ echo 'checked'; } ?> />Other
														        </td>
														    </tr>

														    <!-- <tr class="maleclass">
														        <td class="AGENCYregtableleft">Neck:</td>
														        <td class="AGENCYregtableright">
																<select class="thin" name="neck_start">
																	<option value="">Any</option>
																	<?php //for($i=8; $i<=30; $i=$i+.5) { ?>
																		<option value="<?php //echo $i; ?>" <?php //if (isset($_SESSION['t_search']['neck_start']) && $_SESSION['t_search']['neck_start'] == $i) { echo "selected"; } ?>><?php //echo $i; ?></option>
																	<?php //} ?>
																</select>
																&nbsp;&nbsp;to&nbsp;&nbsp;
																<select class="thin" name="neck_end">
																	<option value="">Any</option>
																	<?php //for($i=8; $i<=30; $i=$i+.5) { ?>
																		<option value="<?php //echo $i; ?>" <?php //if (isset($_SESSION['t_search']['neck_end']) && $_SESSION['t_search']['neck_end'] == $i) { echo "selected"; } ?>><?php //echo $i; ?></option>
																	<?php //} ?>
																</select> Inches
														        </td>
														    </tr> -->

														    <!-- <tr class="maleclass">
														        <td class="AGENCYregtableleft">Sleeve:</td>
														        <td class="AGENCYregtableright">
																<select class="thin" name="sleeve_start">
																	<option value="">Any</option>
																	<?php //for($i=20; $i<=50; $i++) { ?>
																		<option value="<?php //echo $i; ?>" <?php //if (isset($_SESSION['t_search']['sleeve_start']) && $_SESSION['t_search']['sleeve_start'] == $i) { echo "selected"; } ?>><?php //echo $i; ?></option>
																	<?php //} ?>
																</select>
																&nbsp;&nbsp;to&nbsp;&nbsp;
																<select class="thin" name="sleeve_end">
																	<option value="">Any</option>
																	<?php //for($i=20; $i<=50; $i++) { ?>
																		<option value="<?php //echo $i; ?>" <?php //if (isset($_SESSION['t_search']['sleeve_end']) && $_SESSION['t_search']['sleeve_end'] == $i) { echo "selected"; } ?>><?php //echo $i; ?></option>
																	<?php //} ?>
																</select> Inches
														        </td>
														    </tr> -->
															  
														    <tr>
														        <td class="AGENCYregtableleft">Inseam:</td>
														        <td class="AGENCYregtableright">
																<select class="thin" name="inseam_start">
																	<option value="">Any</option>
																	<?php for($i=8; $i<=50; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['inseam_start']) && $_SESSION['t_search']['inseam_start'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																</select>
																&nbsp;&nbsp;to&nbsp;&nbsp;
																<select class="thin" name="inseam_end">
															 		<option value="">Any</option>
																	<?php for($i=8; $i<=50; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['inseam_end']) && $_SESSION['t_search']['inseam_end'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																</select> Inches
														        </td>
														    </tr>
														      
														    <tr class="femaleclass">
														        <td class="AGENCYregtableleft">Bust:</td>
														        <td class="AGENCYregtableright">
																<select class="thin" name="bust_start">
																	<option value="">Any</option>
																	<?php for($i=8; $i<=50; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['bust_start']) && $_SESSION['t_search']['bust_start'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																</select>
																&nbsp;&nbsp;to&nbsp;&nbsp;
																<select class="thin" name="bust_end">
																	<option value="">Any</option>
																	<?php for($i=8; $i<=50; $i++) { ?>
																		<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['bust_end']) && $_SESSION['t_search']['bust_end'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																	<?php } ?>
																</select> Inches
														        </td>
														    </tr>      
														      
														    <tr class="femaleclass">
														        <td class="AGENCYregtableleft">Cup Size:</td>
														        <td class="AGENCYregtableright">
																	<select class="thin" name="cup_start">
																		<option value="">Any</option>
																		<?php foreach($bracups as $value=>$size) { ?>
																			<option value="<?php echo $value; ?>" <?php if (isset($_SESSION['t_search']['bust_end']) && $_SESSION['t_search']['cup_start'] == $value) { echo "selected"; } ?>><?php echo $size; ?></option>
																		<?php } ?>
																	</select>
																	&nbsp;&nbsp;to&nbsp;&nbsp;
																	<select class="thin" name="cup_end">
																		<option value="">Any</option>
																		<?php foreach($bracups as $value=>$size) { ?>
																			<option value="<?php echo $value; ?>" <?php if (isset($_SESSION['t_search']['bust_end']) && $_SESSION['t_search']['cup_end'] == $value) { echo "selected"; } ?>><?php echo $size; ?></option>
																		<?php } ?>
																	</select>
														        </td>
														    </tr>

														    <tr class="femaleclass">
														        <td class="AGENCYregtableleft">Hips:</td>
														        <td class="AGENCYregtableright">
																	<select class="thin" name="hips_start">
																		<option value="">Any</option>
																		<?php for($i=20; $i<=60; $i++) { ?>
																			<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['hips_start']) && $_SESSION['t_search']['hips_start'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select>
																	&nbsp;&nbsp;to&nbsp;&nbsp;
															 		<select class="thin" name="hips_end">
																		<option value="">Any</option>
																		<?php for($i=20; $i<=60; $i++) { ?>
																			<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['hips_end']) && $_SESSION['t_search']['hips_end'] == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select> Inches
														        </td>
														    </tr>

														    <tr class="femaleclass">
														        <td class="AGENCYregtableleft">Dress:</td>
														        <td class="AGENCYregtableright">
																	<select class="thin" name="dress_start">
																		<option value="">Any</option>
																		<?php for($i=0; $i<=40; $i++) { ?>
																			<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['dress_start']) && $_SESSION['t_search']['dress_start'] === $i) { echo "selected"; } ?>>
																				<?php echo $i; ?>
																			</option>
																		<?php } ?>
																	</select>
																	&nbsp;&nbsp;to&nbsp;&nbsp;
															 		<select class="thin" name="dress_end">
																		<option value="">Any</option>
																		<?php for($i=0; $i<=40; $i++) { ?>
																			<option value="<?php echo $i; ?>" <?php if (isset($_SESSION['t_search']['dress_end']) && $_SESSION['t_search']['dress_end'] === $i) { echo "selected"; } ?>><?php echo $i; ?></option>
																		<?php } ?>
																	</select> US sizes
														        </td>
															</tr>
														</table>

														<table width="50%" border="0" cellpadding="3" cellspacing="2" bgcolor="white" align="left">
														    <tr>
														        <td class="AGENCYregtableleft vertical-top" width="25%">Experience Level:</td>
														        <td class="AGENCYregtableright">
														        	<select class="thin" name="experience">
															        	<option value="">Any</option>
															        	<?php foreach($experiencearray as $value=>$name) { ?>
																			<option value="<?php echo $value; ?>" <?php if (isset($_SESSION['t_search']['experience']) && $_SESSION['t_search']['experience'] == $value) { echo "selected"; } ?>><?php echo $name; ?></option>
																		<?php } ?>
																	</select>
														       </td>
														    </tr>

														    <tr>
														        <td class="AGENCYregtableleft vertical-top">Hair Color:</td>
														        <td class="AGENCYregtableright">
																<!-- <div id="hairexpand" style="height:40px; overflow:hidden;"> -->
																<div id="hairexpand" style="">
																	<?php foreach($haircolorarray as $val){ ?>
																		<label class="font-weight-normal">
																			<input type="checkbox" name="hair_color[]" value="<?php echo $val; ?>" <?php if (isset($_SESSION['t_search']['hair_color']) && in_array($val,$_SESSION['t_search']['hair_color'])) { echo "checked"; } ?> /><?php echo $val; ?>
																		</label>
																		&nbsp;&nbsp;
										              				<?php } ?>
																</div>
																<!-- <a id="expandlink2" href="javascript:void(0)" onclick="getElementById('hairexpand').style.height=''; getElementById('expandlink2').style.display='none'">click to show full list</a> -->

														        </td>
														    </tr>

														    <tr>
														        <td class="AGENCYregtableleft vertical-top">Eye Color:</td>
														        <td class="AGENCYregtableright">
														        	<?php foreach($eyecolorarray as $val){ ?>
																		<label class="font-weight-normal">
																			<input type="checkbox" name="eye_color[]" value="<?php echo $val; ?>" <?php if (isset($_SESSION['t_search']['eye_color']) && in_array($val,$_SESSION['t_search']['eye_color'])) { echo "checked"; } ?> /><?php echo $val; ?>
																		</label>
																		&nbsp;&nbsp;
										              				<?php } ?>
														        </td>
														    </tr>  
															  	  
														    <tr>
														        <td class="AGENCYregtableleft vertical-top">Ethnicity:</td>
														        <td class="AGENCYregtableright">
														        	<?php foreach($ethnicityarray as $val){ ?>
																		<label class="font-weight-normal">
																			<input type="checkbox" name="ethnicity[]" value="<?php echo $val; ?>" <?php if (isset($_SESSION['t_search']['ethnicity']) && in_array($val,$_SESSION['t_search']['ethnicity'])) { echo "checked"; } ?> /><?php echo $val; ?>
																		</label>
																		&nbsp;&nbsp;
										              				<?php } ?>
														        </td>
														    </tr>

														    <tr>
														        <td class="AGENCYregtableleft vertical-top">Categories:</td>
														        <td class="AGENCYregtableright">
														        <!-- <div id="catexpand" style="height:40px; overflow:hidden;"> -->
														        <div id="catexpand" style="">
																	<?php
																		for($i=0; isset($categoryarray_1[$i]); $i++) {
																			echo '<label class="font-weight-normal"><input type="checkbox" name="category[]" value="' . $categoryarray_1[$i] . '"';
																			if(isset($_SESSION['t_search']['category'])) {
																   				if(in_array($categoryarray_1[$i], $_SESSION['t_search']['category'])) echo ' checked';
																			}
																			echo ' />' . $categoryarray_1[$i] .'</label> &nbsp;&nbsp;';
																		}
																		echo '<br/> ----------------------- <br />';
																		for($i=0; isset($categoryarray_2[$i]); $i++) {
																			echo '<label class="font-weight-normal"><input type="checkbox" name="category2[]" value="' . $categoryarray_2[$i] . '"';
																			if(isset($_SESSION['t_search']['category2'])) {
																   				if(in_array($categoryarray_2[$i], $_SESSION['t_search']['category2'])) echo ' checked';
																			}
																			echo ' />' . $categoryarray_2[$i] .'</label>  &nbsp;&nbsp;';
																		}
																	?>		
																</div>
																<!-- <a id="expandlink" href="javascript:void(0)" onclick="getElementById('catexpand').style.height=''; getElementById('expandlink').style.display='none'">click to show full list</a> -->
														        </td>
														    </tr>

														    <tr>
														        <td class="AGENCYregtableleft vertical-top">Union Status:</td>
														        <td class="AGENCYregtableright">
																	<?php
																	for($i=0; isset($unionarray[$i]); $i++) {
																		echo '<label class="font-weight-normal"><input type="checkbox" name="unions[]" value="' . $unionarray[$i] . '"';
																		if(isset($_SESSION['t_search']['unions'])) {
																	   		if(in_array($unionarray[$i], $_SESSION['t_search']['unions'])) echo ' checked';
																		}
																		echo ' /> ' . $unionarray[$i] . '</label> &nbsp;&nbsp;';
																	}

																	echo '<br/>Other:<br /><input type="text" name="unions[]" value="';
																	if(isset($_SESSION['t_search']['unions'])) {
																		foreach($_SESSION['t_search']['unions'] as $un) {
																			if(!in_array($un, $unionarray)) {
																				echo $un;
																				$showblank = false;
																			}
																		}
																	}
																	echo '" />';
																	?>
														        </td>
														    </tr>

														    <tr>
														        <td class="AGENCYregtableleft">Language:</td>
														        <td class="AGENCYregtableright">
														        	<input type="text" name="language" value="<?php if (isset($_SESSION['t_search']['language'])){ echo $_SESSION['t_search']['language']; } ?>" />
														        </td>
														 	</tr>

														    <tr>
														       	<td class="AGENCYregtableleft">Sports & Music:</td>
														        <td class="AGENCYregtableright">
														        	<input type="text" name="sports_music" value="<?php if (isset($_SESSION['t_search']['sports_music'])){ echo $_SESSION['t_search']['sports_music']; } ?>" />
														        </td>
														    </tr>
														    <tr>
														        <td class="AGENCYregtableleft">Other Skills/Physical Traits:</td>
														        <td class="AGENCYregtableright">
														        	<input type="text" name="skills_other" value="<?php if (isset($_SESSION['t_search']['skills_other'])){ echo $_SESSION['t_search']['skills_other']; } ?>" />
														        </td>
														    </tr>

														    <tr>
														      <td colspan="2" class="AGENCYregtableright">
														      *For skills, enter one word or phrase per search.  For example, &quot;french horn&quot; is okay, but &quot;violin, guitar, piano&quot; is not recommended.
														      </td>
														    </tr>
														</table>

														<br clear="all" /><br clear="all" />
														
												    	<input type="submit" value="Search" name="submitsearch" class="btn btn-theme btn-flat">
												    	<input type="submit" value="Clear" name="submitclear" class="btn btn-default btn-flat">
												</div>
											</div>

										</form>

										<!-- *******************************   END SEARCH FORM ******************************* -->

								<?php } ?>
						
								<?php 
									// if (!empty($gender)) {
									// 	if($gender=='M') echo '<script>changecss(\'.femaleclass\', \'display\', \'none\'); </script>';
									// 	if($gender=='F') echo '<script>changecss(\'.maleclass\', \'display\', \'none\'); </script>';
									// }
								?>

							</div>
						</div>

					</div>
				</div>

		</div>
</div>

<div class="modal fade" id="lightbox_form_Modal" role="dialog"></div>

<?php include('footer_js.php'); ?>
<!-- <script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 
 -->
<!-- <script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui.min.js"></script> -->

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script> -->
<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->

<script>
	$(document).ready( function () {
	  //   $('.datatable').DataTable({
	  //       "order": [[ 0, "desc" ]],
	  //       'columnDefs': [{
			//     'targets': [3], /* column index */
			//     'orderable': false, /* true or false */
			// }]
	  //   });

	    $(".check_all_btn").click(function(e){
	    	e.preventDefault();
	    	$('.user_check').prop('checked', true);
	    });

	    $(".uncheck_all_btn").click(function(e){
	    	e.preventDefault();
	    	$('.user_check').prop('checked', false);
	    });

	    $(".add_to_lightbox_btn").click(function(e){
	    	e.preventDefault();

	    	total_check = 0;
	    	check_users = [];
	    	$('.user_check').each(function(index, value) {
	    		if($(this).prop("checked") == true){
	    			total_check++;
	    			check_users.push($(this).val());
	            }
		    });

	    	if(total_check > 0){

				$.ajax({
			        url: '../ajax/dashboard_request.php',
			        type: 'post',
			        data: 'check_users='+check_users+'&name=lightbox_form_box',
			        // dataType: 'json',
			        success: function(response){
			          // console.log(response);
			          $('#lightbox_form_Modal').html(response);

			          // Display Modal
			          $('#lightbox_form_Modal').modal('show');
			        }
			    }); 

	    	}else{
	    		alert('Please Select Atleast One Talent');
	    	}

	    });

	    $(document).on('submit', '.lightbox_form', function(e) {
			// e.preventDefault();

			title = $('#title').val();
			lightbox_id = $('#lightbox_id').val();

			if(title == "" && lightbox_id ==""){
				e.preventDefault();
				$('#user-to-lightbox-err').html('Please add New lightbox or select any one.');
				$('#user-to-lightbox-err').css('display','block');
				return false;
			}else{
				$('.lightbox_form').submit();
			}
		});

	});
</script>
<?php include('footer.php'); ?>