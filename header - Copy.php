<?php
define("URL_SITENAME", $_SERVER['HTTP_HOST']);
date_default_timezone_set('America/New_York');

// makes sure it is http and not https unless it is the payment page
if(isset($_SERVER['HTTPS'])) {
  if(($_SERVER["SCRIPT_NAME"] != '/payment.php' && $_SERVER["SCRIPT_NAME"] != '/account_update.php') && $_SERVER['HTTPS'] == 'on') {
    $url = 'http://' . URL_SITENAME . $_SERVER['REQUEST_URI'];
    header("Location: $url");
  }
}

if(($_SERVER["SCRIPT_NAME"] == '/payment.php' || $_SERVER["SCRIPT_NAME"] == '/account_update.php') && !empty($_GET['token']) && $_SERVER['HTTPS'] == 'on') {
  // if the session id was sent to the payment page, set the session
  $sessionID = $_GET['token'];
  session_id($sessionID);
}
  
// CONTACT EMAIL GOES HERE
$contactemail = 'info@theagencyonline.com';
$endscript = ''; // init var for running javascript after </html>

// Start output buffering.
ob_start();
// Initialize session.
session_start();


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

include('includes/mysql_connect.php');
include('includes/vars.php');
include('includes/agency_functions.php');
include('forms/definitions.php');


/* =================================================    FOR UPDATING; TEMPORARY    =========================== */
/*
if(isset($_GET['updatecode'])) {
  if($_GET['updatecode'] == '4j923if') {
    $_SESSION['updateaccess'] = true;
  }
}

if(!isset($_SESSION['updateaccess'])) {
  $url = 'update.php';
  ob_end_clean(); // Delete the buffer.
  header("Location: $url");
  exit(); // Quit the script. 
}
*/
/* ========================================================================================================== */


if(is_admin()) {
  if(!empty($_SESSION['editmode']) && $_GET['u']) {
    $_SESSION['user_id'] = (int) $_GET['u'];
  } else if(empty($_SESSION['editmode'])) {
    $_SESSION['user_id'] = (int) $_SESSION['admin'];
  }
}

if(isset($_SESSION['user_id'])) {
  $loggedin = (int)$_SESSION['user_id'];
} else {
  $loggedin = false;
}
if(!empty($_GET['u'])) {
  $profileid= (int) $_GET['u'];
}


// if user is not logged in, check if there's a "remember me" cookie
if(!isset($_SESSION['user_id'])) {
  if(!empty($_COOKIE['agency_arm'])) {
    $cookie = escape_data($_COOKIE['agency_arm']);
    $query = "SELECT user_id FROM agency_rememberme WHERE session_id='$cookie'";
    $result = @mysql_query($query);
    if ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
      $_SESSION['user_id'] = $row['user_id'];
    }
  }
}


unset($_SESSION['casting_location']); // this is for the castings filtering
?>
<!DOCTYPE html>
<html>
<head>
  <?php
if(isset($_GET['refresh'])) {
  echo '<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"><META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" >';
}
?>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="content-language" content="en-gb" />
<meta name="google-site-verification" content="S3EUw1KmJ_sbENtyGNrMJJCW8L0uohwKpggyNCejM00" />
<meta name="keywords" content="how to become a model, actor website, actor jobs, ny casting, casting agent, casting network, talent casting, online casting, acting agencies, acting agency, la casting, model agents, how to become an actor, modeling for teens, casting network, reality tv casting, top modeling agencies, acting agencies, models agencies, child modeling agencies, talent casting, new york casting, casting models, child talent, casting network, how to be a modell, how to modell, modelling in new york, becoming a modell, how to model, how to be a model, talent agency, model agency, models agencies, model jobs, modell jobs, modelling jobs, auditions, casting calls" />
<meta name="robots" content="index, follow" />
<meta name="author" content="TheAgency" />
<meta name="description" content="The Agency, New York. Online modeling agency." />

<meta http-equiv="X-UA-Compatible" content="IE=8" />
<link rel="shortcut icon" href="image/fav-icon.png" type="image/x-icon">


    <title><?php @include_once('includes/pagetitle.php'); ?></title>

<link rel="stylesheet" href="style1.css"> 
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
 <link rel="stylesheet" href="css/font-awesome.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
<div class="top-header">
<div class="logo">
  <a href="home.php"><img src="image/The-agancy-logo2.png"></a>
</div>
<div class="social-btn">
    <ul>
       <li><a href="http://www.facebook.com/pages/The-Agency-wwwtheagencyOnlinecom/107832902632632"><img src="image/facebook.png"></a></li>
  <li><a href="https://twitter.com/theagencyOnline"><img src="image/twitter.png"></a></li>
    
       <li><a href="https://instagram.com/theagencyonline_"><img src="image/instagram.png"></a></li>
         <li><a href="https://www.youtube.com/channel/UCmVOTD_oJ1iRmzDnkjKb6Lw"><img src="image/youtube.png"></a></li>
           <li><a href="https://Linkedin.com/company/theagencyonline"><img src="image/linkedin.png"></a></li>
        </ul>
    
</div>
</div>
<div class="second-header2">

  <div class="button">
      <form action="new.php" id="cse-search-box">
          <input type="hidden" name="cx" value="008874514896725589454:j0jwxphmxho" />
          <input type="hidden" name="cof" value="FORID:11" />
          <input type="hidden" name="ie" value="UTF-8" />
          <input type="text" name="q" size="20" />
          <input type="submit" name="sa" value="Search" />
      </form>
</div>




<div class="login">
<ul>
      
      <li class="drop">
        <a href="login.php">LOGIN</a>
        
        
      
      </li>
      
    </ul>
</div>
<div class="sign">
    <ul>
      
      <li class="drop">
        <a href="talent_signup.php">SIGN UP</a>
        
        
      
      </li>
      
    </ul>

</div>

</div>  

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container1">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="home.php">Home</a></li>
         <li><a href="About.php">About Us</a></li>
           
        <li><a href="casting-call.php">Casting Calls</a></li>
        
        <li><a href="resource.php">Resources</a></li>
        <li><a href="agency_angle.php">The Agency Angle</a></li>
        <li><a href="contacts.php">Contact us</a></li>
        <li><a href="funding.php">Funding Box</a></li>
        
      </ul>
    </div>
  </div>
</nav>
</body>
</html>