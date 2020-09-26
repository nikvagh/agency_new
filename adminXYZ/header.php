<?php

	// Start output buffering.
	ob_start();
	// Initialize session.
	session_start();
	// echo "<pre>";print_r($_SESSION);exit;
	/* ========= THIS IS TO SHOW ERROS FOR TESTING.  NEEDS A CODE BUT SHOULD STILL BE REMOVED WHEN NOT TESTING ======== */
	if(isset($_GET['showerrors'])) {
		if($_GET['showerrors'] == 'rex39') {
			$_SESSION['showerrors'] = 'rex39';
		}
	}
	if(isset($_SESSION['showerrors'])) { // this should be removed when done testing
		if($_SESSION['showerrors'] == 'rex39') {
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
	}
	/* ============================================  END TESTING CODE ==================================================*/

	if(!empty($_GET['killPEM'])) {
		$_SESSION['user_id'] = (int) $_SESSION['admin'];
	}

	include('../includes/vars.php');
	include('../includes/mysql_connect.php');
	include('../includes/agency_functions.php');
	include('../forms/definitions.php');

	if(empty($_SESSION['superadmin'])) {
		$url = '../index.php';
		ob_end_clean(); // Delete the buffer.
		header("Location: $url");
		exit(); // Quit the script.
	}

	$superadmin = true;

	// determine if there are pending experience change requests:
	$query = "SELECT user_id, firstname, lastname FROM agency_profiles WHERE experience<>exp_request AND exp_request IS NOT NULL";
	$exp_result = mysql_query($query);
	if(mysql_num_rows($exp_result) > 0) {
		$exp_highlight = true;
	} else {
		$exp_highlight = false;
	}

	$sql = "select * from forum_users u
				LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id 
				WHERE u.user_id = ".$_SESSION['user_id']."";

	$query = mysql_query($sql);
	$user = array();
	if (mysql_num_rows($query) > 0) {
		while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
			$user = $row;
		}
	}

?>
<!-- <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Admin Area</title>
<link rel="stylesheet" type="text/css" href="adminstyles.css" />
</head>
<body>
<div id="wrapper" style="margin-left:20px">
	<div id="WholePage">
	<div id="Logo">
		<?php //if (file_exists('../images/banner.jpg')) echo '<img src="../images/banner.jpg">'; else if (file_exists('../images/banner.gif')) echo '<img src="../images/banner.gif">'; ?></div>
		<div id="PageMiddle">
		  <div style="float:left">
			<div id="menu"  class="menu">
			  <a href="../home.php">VIEW SITE</a>
			  <a href="newpage.php">Add new Page</a>
			  <a href="pages.php">Manage Pages</a>
			  <a href="news.php">News</a>
			  <a href="blocks.php">Ads/Blocks</a>
			  <a href="testimonials.php">Testimonials</a>
			  <a href="members.php"<?php //if($exp_highlight) echo ' style="color:red"'; ?>>Members</a>
			  <a href="mentors.php">Mentors</a>
			  <a href="discounts.php">Discount Codes</a>
			  <a href="payment.php">Payment</a>
			  <a href="index.php">Help</a>
			  <a href="../logout.php">Log Out</a>

			 </div>
			</div>
			<div id="main" class="main"> -->


<!DOCTYPE html>
<html lang="en">
<head>
  	<title>Super Admin</title>
  	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  	<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  	<link rel="stylesheet" href="../dashboard/assets/css/bootstrap.min.css">
  	<link rel="stylesheet" href="../dashboard/assets/css/font-awesome.min.css">
  	<link rel="stylesheet" href="../dashboard/assets/css/style.css">
  	<link rel="stylesheet" href="../dashboard/assets/css/AdminLTE.min.css">

  	<!-- <link rel="stylesheet" href="../dashboard/assets/DataTables/datatables.min.css"> -->
  	<link rel="stylesheet" href="../dashboard/assets/DataTables/dataTables.bootstrap.min.css">

  	<!-- date & time picker -->
  	<link rel="stylesheet" media="all" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/ui-lightness/jquery-ui.min.css" />
  	<link rel="stylesheet" media="all" type="text/css" href="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.css" />

    <?php if($page == "dashboard"){ ?>
	  <!-- chart -->
	  <link rel="stylesheet" media="all" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.css" />
	<?php } ?>

	<?php if($page == "messages" || $page == "service_provider_form" ){ ?>
	  <!-- select 2 -->
	  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
	<?php } ?>

	<?php if($page == "casting_call_form" || $page == "article_form"){ ?>
		<link href="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.css" rel="stylesheet" />
	<?php } ?>

	<?php if($page == "profile_view"){ ?>
		<link href="../dashboard/assets/OwlCarousel/owl.carousel.min.css" rel="stylesheet" />
		<link href="../dashboard/assets/fancybox/jquery.fancybox.min.css" rel="stylesheet" />
	<?php } ?>
  	<link rel="shortcut icon" href="img/fav2.png" type="image/x-icon">
</head>
<body>
	<div id="noty-holder"></div>
	<div id="wrapper">
		<!-- Navigation -->
	    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	        <!-- Brand and toggle get grouped for better mobile display -->
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a class="navbar-brand" href="index.php">
	                <img src="../dashboard/assets/img/The-agancy-logo2.png" alt="LOGO" class="img-responsive">
	            </a>
	        </div>
	        
	        <!-- Top Menu Items -->
	        <ul class="nav navbar-right top-nav navbar-nav">

				<!-- <li class="dropdown messages-menu">
					<a href="casting-update.php" class="new-cast btn btn-primary btn-sm dropdown-toggle"><i class="fa fa-plus"> </i> New Casting</a>
				</li> -->

	            <li class="dropdown messages-menu">
            		<?php
						$sql = "SELECT * FROM agency_messages WHERE to_id=".$_SESSION['user_id']." AND deleted='0' AND viewed = '0' ORDER BY date_entered DESC";

						$query = mysql_query($sql);
						$result = array();
					?>
					<!-- <div class="envelope btn btn-default dropdown-toggle notifications" data-toggle="dropdown"><i class="fa fa-envelope"></i>
						<?php if (mysql_num_rows($query) > 0) { ?>
							<span class="num"><?php echo mysql_num_rows($query); ?></span>
						<?php } ?>
			        </div> -->

			        <!-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		              <i class="fa fa-envelope-o"></i>
		              <span class="label label-success"><?php if (mysql_num_rows($query) > 0) { echo mysql_num_rows($query); } ?></span>
		            </a> -->

					<ul class="dropdown-menu">
			            <li class="header">You have <?php if (mysql_num_rows($query) > 0) { echo mysql_num_rows($query); } ?> messages</li>
			            <li>
			                <div class="slimScrollDiv" style="">
			                	<ul class="menu" style="">
			                		<?php while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) { ?>
						                <li>
						                    <a href="messages.php">
						                      	<div class="pull-left">
						                        	<img src="../images/friend.gif" class="img-circle" alt="User Image">
						                      	</div>
						                      	<h4>
						                        	<?php echo $row['from_name']; ?>
						                        	<small><i class="fa fa-clock-o"></i> <?php echo date('H:i - d M',strtotime($row['date_entered'])); ?></small>
						                      	</h4>
						                      	<p><?php echo $row['subject']; ?></p>
						                    </a>

						                </li>
					                <?php } ?>
			                	</ul>
			                <div class="slimScrollBar" style=""></div></div>
			            </li>
			            <li class="footer"><a href="messages.php">See All Messages</a></li>
		            </ul>
	         	</li>

	         	<li class="dropdown notifications-menu">
		           <!--  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		              <i class="fa fa-bell-o"></i>
		              <span class="label label-warning"></span>
		            </a> -->
		            <ul class="dropdown-menu">
		              <!-- <li class="header">You have 10 notifications</li> -->
		              <li>
		                <ul class="menu">
		                  <!-- <li>
		                    <a href="#">
		                      <i class="fa fa-users text-aqua"></i> 5 new members joined today
		                    </a>
		                  </li>
		                  <li>
		                    <a href="#">
		                      <i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
		                      page and may cause design problems
		                    </a>
		                  </li>
		                  <li>
		                    <a href="#">
		                      <i class="fa fa-users text-red"></i> 5 new members joined
		                    </a>
		                  </li>
		                  <li>
		                    <a href="#">
		                      <i class="fa fa-shopping-cart text-green"></i> 25 sales made
		                    </a>
		                  </li>
		                  <li>
		                    <a href="#">
		                      <i class="fa fa-user text-red"></i> You changed your username
		                    </a>
		                  </li> -->
		                  <li>
		                  	<a class="text-center"> No New Notification </a>
		                  </li>

		                </ul>
		              </li>
		              <!-- <li class="footer"><a href="#">View all</a></li> -->
		            </ul>
		        </li>

	            <li class="dropdown user user-menu notifications-menu">
		            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
		            	<?php 
			            	if(file_exists('../uploads/users/' . $user['user_id'] . '/profile_pic/thumb/25x25_'.$user['user_avatar']) ){
			            		$image = '../uploads/users/' . $user['user_id'] . '/profile_pic/thumb/25x25_'.$user['user_avatar'];
			            	}else{
			            		$image = '../images/friend.gif';
			            	}
		            	?>
		              	<img src="<?php echo $image; ?>" class="user-image" alt="User Image" width="25px">
      				 	<span class="hidden-xs"><?php echo $user['firstname'].' '.$user['lastname']; ?></span>
		            </a>
		            <ul class="dropdown-menu">
		              	<li>
			                <ul class="menu">
			                  <li>
			                    <a href="myaccount.php">
			                      <i class="fa fa-user text-aqua"></i> Edit Profile
			                    </a>
			                  </li>
			                  <li>
			                    <a href="changepassword.php">
			                      <i class="fa fa-key text-yellow"></i> Change Password
			                    </a>
			                  </li>
			                  <li>
			                    <a href="../logout.php">
			                      <i class="fa fa-lock text-red"></i> Logout
			                    </a>
			                  </li>
			                </ul>
		              	</li>
		            </ul>
		        </li>


	        </ul>
	        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
	        <div class="collapse navbar-collapse navbar-ex1-collapse">
	            <ul class="nav navbar-nav side-nav">
	                <li><a href="index.php" class="<?php if($page_selected == 'dashboard'){ echo 'active'; } ?>">DASHBOARD</a></li>
				  	<li><a href="privilege-list.php" class="<?php if($page_selected == 'privilege'){ echo 'active'; } ?>">PRIVILEGES</a></li>

				  	<!-- <li>
				  		<?php
				  			$account_active_member = 'N';
				  			if($page_selected == 'talent_member' || $page_selected == 'client_member' || $page_selected == 'talent_manager_member'){ 
				  				$account_active_member = 'Y';
				  			}
				  		?>
				  		<a href="#" data-toggle="collapse" data-target="#member_sub" class="<?php if($page_selected == 'members'){ echo 'active'; } ?>">MEMBERS</a>
				  		<ul id="member_sub" class="treeview-menu collapse <?php if($account_active_member == 'Y'){ echo 'in'; } ?>">
	                       	<li>
	                       		<a href="talent-list.php" class="<?php if($page_selected == 'talent_member'){ echo 'active'; } ?>">
		                       		<i class="fa fa-circle-o"></i> &nbsp;
		                       		Talent
		                       	</a>
	                       	</li>
	                       	<li>
	                       		<a href="client-list.php" class="<?php if($page_selected == 'client_member'){ echo 'active'; } ?>">
	                       			<i class="fa fa-circle-o"></i> &nbsp;
		                       		Casting Manager
		                       	</a>
	                       	</li>
	                       	<li>
	                       		<a href="talent-manager-list.php" class="<?php if($page_selected == 'talent_manager_member'){ echo 'active'; } ?>">
	                       			<i class="fa fa-circle-o"></i> &nbsp;
		                       		Talent Manager
		                       	</a>
	                       	</li>		                    
                       	</ul>
				  	</li> -->
				  	
				  	<li><a href="../logout.php">LOG OUT</a></li>
	            </ul>
	        </div>
	        <!-- /.navbar-collapse -->
	    </nav>

<!-- =========================== -->
