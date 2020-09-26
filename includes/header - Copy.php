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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-gb" xml:lang="en-gb">
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

<title><?php @include_once('./includes/pagetitle.php'); ?></title>
<link rel="stylesheet" type="text/css" media="screen" href="./menu/superfish.css" />
<link rel="stylesheet" href="./includes/lightboxthickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="./includes/staticstyles.css" />
<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" type="image/ico" href="favicon.ico" />
<!-- <script language="javascript" src="./menu/jquery-1.2.6.js"></script> -->
<script type="text/javascript" src="./includes/swfobject.js"></script>
<script type="text/javascript" src="./includes/jquery.js"></script>
<script type="text/javascript" src="./includes/jquery.ui.js"></script>
<script type="text/javascript" src="./includes/thickbox.js"></script>
<script type="text/javascript" src="./menu/hoverIntent.js"></script>
<script type="text/javascript" src="./menu/superfish.js"></script>

<script type="text/javascript" src="./includes/prototype.js"></script>
<script type="text/javascript" src="./includes/scriptaculous.js?load=effects,builder"></script>
<script type="text/javascript" src="./includes/lightbox.js"></script>
<script type="text/javascript" src="./includes/jquery.collapser.min.js"></script>

<!--<script src="https://www.google.com/recaptcha/api.js" async defer></script>-->
<script src="https://www.google.com/recaptcha/api.js?render=explicit"></script>


<script type="text/javascript" src="./includes/agency.js"></script>

<script type="text/javascript">

    jQuery(document).ready(function(){
        jQuery("ul.sf-menu").superfish({
            animation: {height:'show'},   // slide-down effect without fade-in
			speed:       'fast',                          // faster animation speed
		    delay:     0               // 1.2 second delay on mouseout
        });
		
		
		jQuery('.logincollapse').collapser({
			target: 'next',
			targetOnly: 'div',
			expandHtml: '<img src="images/icon_login_down.gif" />',
			collapseHtml: '<img src="images/icon_login_up.gif" />',
		});		
    });
	
	
image1 = new Image();
image1.src = "images/icon_login_up.gif";

image2 = new Image();
image2.src = "images/logindropbox.png";

</script>

</head>
<body>
<div style="position: absolute; top: 0px; left: 0px; background-image: url('images/bg_images.png'); width: 1238px; height: 1717px; z-index:-1"></div>

<?php
if(URL_SITENAME != 'www.theagencyonline.com') {
	echo '<div style="padding:3px; background-color:yellow; text-align:center; position:fixed; width:200px; z-index:99"><b>Testing Server: ' . URL_SITENAME . '</b></div>';
}
?>
<div id="AGENCYwrapper" align="center">
	 <table cellpadding="0" cellspacing="0" border="0">
	 <tr><td width="0" valign="top" align="right">    
<?php
if(!isset($_SESSION['user_id'])) {
?>     
<div style="margin-right:20px">
	<?php echo showbox('codeleft'); ?>
</div>
<?php
}
?>
	</td>
	<td align="center" valign="top">
		<div style="clear:both; overflow:hidden">
			<?php echo showbox('codetop'); ?>
		</div>    
    

		<div id="AGENCYWholePage">
	
	
	<div style="clear:both; position:relative; border-top:12px solid white">
<?php   

if(!empty($loggedin)) {
	echo '<span style="float: left;">';
	$query = "SELECT firstname, lastname FROM agency_profiles WHERE user_id='$loggedin'";
	$result = @mysql_query($query);
	if($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo '<span class="AGENCYRed" style="font-weight:bold">Welcome, ' . $row['firstname'] . ' ' . $row['lastname'] . '!</span>';


		if(is_admin() && !empty($_SESSION['editmode'])) {
			$adminlink = '?killPEM=true';
		} else {
			$adminlink = '';
		}



		echo '&nbsp;&nbsp;';
	}

	/*
	$newmessages = mysql_result(mysql_query("SELECT COUNT(*) as 'num' FROM agency_messages WHERE to_id='$loggedin' AND viewed='0'"),0);
	if($newmessages > 0) {
		echo '<a href="messages.php" style="font-weight:bold; color:gray">Messages(' . $newmessages . ')</a>';
	} else {
    	echo '<a href="messages.php" style="color:gray">Inbox</a>';
	}
	*/
	if(is_super_admin()) {
		echo '&nbsp;&nbsp;<a href="adminXYZ/index.php' . $adminlink . '"><b>ADMIN</b></a>';
	} else if (is_admin()) {
		echo '&nbsp;&nbsp;<a href="admin_sub/index.php' . $adminlink . '"><b>ADMIN</b></a>';
	}
		
			
	echo '</span>';
}
?>    
    
			<div style="position: absolute; right: 0; margin-top: -5px; z-index: 2;">
			<a target="_blank" href="https://twitter.com/theagencyOnline">
				<img width="21" height="21" alt="" src="../userfiles/image/twitter1.jpg" /></a>            
			<a target="_blank" href="http://www.facebook.com/pages/The-Agency-wwwtheagencyOnlinecom/107832902632632">
				<img width="21" height="21" alt="" src="../userfiles/image/facebook1.jpg" /></a>
			<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FThe-Agency-wwwtheagencyOnlinecom%2F107832902632632&amp;layout=button_count&amp;show_faces=false&amp;width=80&amp;action=like&amp;font&amp;colorscheme=light&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:80px; height:21px;" allowTransparency="true"></iframe>   
        </div>
        
		<div style="position: absolute; right: 140px; margin-top: -5px; z-index: 2;">
	<form action="search_google.php" id="cse-search-box">
          <input type="hidden" name="cx" value="008874514896725589454:j0jwxphmxho" />
          <input type="hidden" name="cof" value="FORID:11" />
          <input type="hidden" name="ie" value="UTF-8" />
          <input type="text" name="q" size="20" />
          <input type="submit" name="sa" value="Search" />
      </form>
       </div>
	 </div>
	
	<div id="AGENCYLogo">
		<a href="./"><img src="./images/banner.gif" border="0" alt="The Agency Online" title="The Agency Online" /></a>




<div id="AGENCYtoplogin" style="width:200px; margin-bottom:8px; margin-right:12px;">
<?php
if(!isset($_SESSION['user_id'])) {
	$noreg = 'Y'; // for testing on payment.php page
?>

            
            
            <div class="logincollapse" style="float:right"><a href="login.php"><img src="images/icon_login_down.gif" border="0" /></a></div>
            
            <div id="logindropbox">
            <div style="padding:30px; width:240px">
        <form method="post" action="login.php" name="phpbblogin" style="padding:0px; margin:0px">
            
				USERNAME<br />
                <input name="username" type="text" style="width:208px" /><br /><br />
				PASSWORD<br />
			<input name="password" type="password" style="width:208px" /><br />
            <a href="forgotpassword.php" style="font-size:9px; font-weight:normal; text-decoration:none;">Forgot your Username or Password?</a>
            <br /><br /><br />
				<input name="redirect" value="<?php echo $_SERVER["REQUEST_URI"]; ?>" type="hidden" />
				<input type="hidden" name="login" value="login" />
                <input type="submit" name="submit" value="  Login  " /><span style="font-weight:normal; padding-left:20px;"><input type="checkbox" name="rememberme" /> remember me</span>

        </form>
        <br /><br />
        
        </div>
        </div><div style="float:right"><a href="#TB_inline?height=500&amp;width=450&amp;inlineId=hiddenModalContent" onclick="jQuery('#popupcontent').load('ajax/register_forms.php');" class="thickbox"><img src="images/icon_signup.gif" border="0" /></a></div>

<?php
} else {
	if($_SESSION['user_id']) { // check if user is logged in
		$loggedin = $_SESSION['user_id'];
		if(is_active()) {
			$noreg = 'no1';
		} else {
			$noreg = 'no2';
		}
	}

	echo '<div style="padding-top: 6px;"><a href="logout.php"><img src="images/logout.gif" border="0" /></a></div>';
}

?>
		</div>











		</div>
		<div id="AGENCYPageMiddle">
			<div id="AGENCYmenu">
			<ul class="sf-menu">
<?php
if(!empty($_SESSION['user_id'])) {
	// if logged in, show all menu options for that account type
	$menuaddon = "AND (WhoSees='" . agency_account_type() . "' || WhoSees='both' || WhoSees='all')";
} else { // not logged in, only show pages visible to everyone
	// $menuaddon = "AND WhoSees='all'";
	$menuaddon = "AND (WhoSees='talent' || WhoSees='both' || WhoSees='all')";
	// or if the type is set, show for that user type
	// this doesn't work because MyAccount displays when they are not logged in
	/* if(isset($_GET['type'])) {
		if($_GET['type'] == 'client') {
			$menuaddon = "AND (WhoSees='client' || WhoSees='both' || WhoSees='all')";
		} else if($_GET['type'] == 'talent') {
			$menuaddon = "AND (WhoSees='member' || WhoSees='both' || WhoSees='all')";
		}
	} */
}

$query = "SELECT * FROM agency_pages WHERE Active='1' AND ChildOf='0' $menuaddon ORDER BY OrderID ASC";
$result = @mysql_query($query);
while ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
	$menuname = $row['MenuName'];
	$pageid = $row['PageID'];
	$link = $row['Link'];
	if(!is_active() && ($row['WhoSees'] == 'talent' || $row['WhoSees'] == 'both')) {
		echo '<li><a href="home.php" onclick="alert(\'Once you have signed up, been approved, and logged into your account, you will be able to explore our numerous resources.\')">' . $menuname . '</a>';
	} else {
		if($link == NULL) {
	?>
			<li><a href="index2.php?pageid=<?php echo $pageid; ?>&amp;title=<?php echo urlencode($menuname); ?>"><?php echo htmlspecialchars($menuname); ?></a>
	<?php
		} else {
	?>
			<li><a href="<?php if((substr($link, 0, 3) == 'www') && (substr($link, 0, 7) != 'http://') ) { echo 'http://'; } echo $link; ?>"><?php echo htmlspecialchars($menuname); ?></a>
	<?php
		}
	}
	$query2 = "SELECT * FROM agency_pages WHERE Active='1' AND ChildOf='$pageid' $menuaddon ORDER BY OrderID ASC";
	$result2 = @mysql_query($query2);
	if(mysql_num_rows($result2) > 0) {
		echo '<ul>';
		while ($row2 = @mysql_fetch_array ($result2, MYSQL_ASSOC)) {
			$menuname2 = $row2['MenuName'];
			$pageid2 = $row2['PageID'];
			$link2 = $row2['Link'];
			if(!is_active() && ($row2['WhoSees'] == 'talent' || $row2['WhoSees'] == 'both')) {
				echo '<li><a href="home.php" onclick="alert(\'Once you have signed up, been approved, and logged into your account, you will be able to explore our numerous resources.\')">' . $menuname2 . '</a>';
			} else {
				if($link2 == NULL) {
?>
					<li><a href="index2.php?pageid=<?php echo $pageid2; ?>&amp;title=<?php echo urlencode($menuname2); ?>"><?php echo htmlspecialchars($menuname2); ?></a>
<?php
				} else {
?>
					<li><a href="<?php if((substr($link2, 0, 3) == 'www') && (substr($link2, 0, 7) != 'http://') ) { echo 'http://'; } echo $link2; ?>"><?php echo htmlspecialchars($menuname2); ?></a>
<?php
				}
			}
			$query3 = "SELECT * FROM agency_pages WHERE Active='1' AND ChildOf='$pageid2' $menuaddon ORDER BY OrderID ASC";
			$result3 = @mysql_query($query3);
			if(mysql_num_rows($result3) > 0) {
				echo '<ul>';
				while ($row3 = @mysql_fetch_array ($result3, MYSQL_ASSOC)) {
					$menuname3 = $row3['MenuName'];
					$pageid3 = $row3['PageID'];
					$link3 = $row3['Link'];
					if(!is_active() && ($row3['WhoSees'] == 'talent' || $row3['WhoSees'] == 'both')) {
						echo '<li><a href="home.php" onclick="alert(\'Once you have signed up, been approved, and logged into your account, you will be able to explore our numerous resources.\')">' . $menuname3 . '</a>';
					} else {
						if($link3 == NULL) {
?>
							<li><a href="index2.php?pageid=<?php echo $pageid3; ?>&amp;title=<?php echo urlencode($menuname3); ?>"><?php echo ($menuname3); ?></a></li>
<?php
						} else {
?>
							<li><a href="<?php if((substr($link3, 0, 3) == 'www') && (substr($link3, 0, 7) != 'http://') ) { echo 'http://'; } echo $link3; ?>"><?php echo htmlspecialchars($menuname3); ?></a></li>
<?php
						}
					}
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
	echo '</li>';
}

if(agency_account_type() == 'talent') {
?>
	<li><a href="profile.php"><span class="AGENCYRed">MyProfile</span></a>
<?php
} else if(agency_account_type() == 'client') {
?>
	<li><a href="clienthome.php"><span class="AGENCYRed">MyAccount</span></a>
<?php
} else {
	echo '<li><a href="home.php" onclick="alert(\'Once you have signed up, been approved, and logged into your account, you will be able to explore our numerous resources.\')">MyProfile</a>';
}


?>
			<li><a href="http://blog.theagencyonline.com">The<span style="color:#0796dd">Agency</span>Angle</a></li>
            <li><a href="contact_main.php">Contact Us</a></li>
<?php
if(empty($_SESSION['user_id'])) {
?>            
            <li><a href="http://www.theagencyonline.com/index2.php?pageid=59" style="color:#ED4E07">How Does it Work?</a></li>
<?php
}
?>
		</ul>
			</div>

			<div class="AGENCYmain" align="left">

<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=en"></script>

				<div style="height:4px"></div>
