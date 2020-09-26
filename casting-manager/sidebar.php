<?php
include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');
include('../forms/definitions.php');
$loggedin = 2;
$query = "SELECT firstname, lastname, location FROM agency_profiles WHERE user_id='$loggedin'";
	$result = @mysql_query($query);//print_r($result);
	if($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
		//echo '<span class="AGENCYRed" style="font-weight:bold">Welcome, ' . $row . ' ' . $row['lastname'] . '!</span>';
	}//die();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  
  <title>Casting Manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
 <link rel="stylesheet" href="css/font-awesome.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="style.css">
  
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
                <img src="img/The-agancy-logo2.png" alt="LOGO"">
            </a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            
<li><a href="castingupdate.php" class="new-cast"><i class="fa fa-plus"> </i> New Casting</a></li>

            <li>
             
               <div class="envelope btn btn-default dropdown-toggle notifications" data-toggle="dropdown">
          <i class="fa fa-envelope"></i>
        <span class="num">5</span>
        </div>
        <ul class="dropdown-menu notify" role="menu">
            <li class="icon">
                <span class="icon"><i class="fa fa-envelope"></i></span>
                <span class="text">Someone Like Your Post</span>
            </li>
            <li class="icon">
                <span class="icon"><i class="fa fa-envelope"></i></span>
                <span class="text">Someone Like Your Photo</span>
            </li>
            <li class="icon">
                <span class="icon"><i class="fa fa-envelope"></i></span>
                <span class="text">Someone Dislike Your Post</span>
            </li>
            <li class="icon">
                <span class="icon"><i class="fa fa-envelope"></i></span>
                <span class="text">Someone Comment on Your Post</span>
            </li>
             <li class="icon">
                <span class="icon"><i class="fa fa-envelope"></i></span>
                <span class="text">Someone Comment on Your Post</span>
            </li>
        </ul>
         </li>   
             <li>
                   
            <div class="btn btn-default dropdown-toggle notifications" data-toggle="dropdown">
        <i class="fa fa-bell"></i>
        <span class="num">3</span>
        </div>
        <ul class="dropdown-menu notify" role="menu">
            <li class="icon">
                <span class="icon"><i class="fa fa-envelope"></i></span>
                <span class="text">Adam post a new casting.</span>
            </li>
            <li class="icon">
                <span class="icon"><i class="fa fa-envelope"></i></span>
                <span class="text">James applied for casting role.</span>
            </li>
            <li class="icon">
                <span class="icon"><i class="fa fa-envelope"></i></span>
                <span class="text">James like you post.</span>
            </li>
        </ul>  
             </li>           
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="img/avatar.jpg" class="avatar"><b class="fa fa-angle-down"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="myaccount.php"><i class="fa fa-fw fa-user"></i> Edit Profile</a></li>
                    <li><a href="changepassword.php"><i class="fa fa-fw fa-cog"></i> Change Password</a></li>
                    <li class="divider"></li>
                    <li><a href="http://tamba30.us/theagency/home.php"><i class="fa fa-fw fa-power-off"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li>
                    <a href="index.php">Casting Dashboard </a>
                   
                </li>
                <li>
                    <a href="messages.php?tab=Inbox">  Messages</a>
                </li>
               
                
                
                <li>
                    <a href="#" data-toggle="collapse" data-target="#submenu-1"> Casting Calls <i class="fa fa-fw fa-angle-down pull-right"></i></a>
                    <ul id="submenu-1" class="collapse">
                    <li> <a href="casting-call.php"> <i class="fa fa-angle-double-right"></i> Post a casting call</a></li>
                      <li> <a href="manage-casting.php?mode=castings&castingid=12663"> <i class="fa fa-angle-double-right"></i> Manage Castings</a></li>  
                    </ul>
                </li>
                
                
                
                <li>
                    <a href="clienthome.php"> Search For Tallent </a>
                   
                </li>
                
                
                
                <li>
                    <a href="submit-an-article.php">Submit an Article</a>
                </li>
                
               <li>
                    <a href="myaccount.php"> Settings </a>
                   
                </li>
                <li>
                    <a href="my-projects.php">My Projects</a>
                   
                </li>
                
                
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>





