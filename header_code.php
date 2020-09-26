<?php
  define("URL_SITENAME", $_SERVER['HTTP_HOST']);
  date_default_timezone_set('America/New_York');

  // makes sure it is http and not https unless it is the payment page
  // if(isset($_SERVER['HTTPS'])) {
  //   if(($_SERVER["SCRIPT_NAME"] != '/payment.php' && $_SERVER["SCRIPT_NAME"] != '/account_update.php') && $_SERVER['HTTPS'] == 'on') {
  //     $url = 'http://' . URL_SITENAME . $_SERVER['REQUEST_URI'];
  //     header("Location: $url");
  //   }
  // }

  if (($_SERVER["SCRIPT_NAME"] == '/payment.php' || $_SERVER["SCRIPT_NAME"] == '/account_update.php') && !empty($_GET['token']) && $_SERVER['HTTPS'] == 'on') {
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
  if (isset($_GET['showerrors'])) {
    if ($_GET['showerrors'] == 'rex39') {
      $_SESSION['showerrors'] = 'rex39';
    }
  }
  if (isset($_SESSION['showerrors'])) {
    if ($_SESSION['showerrors'] == 'rex39') {
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
  // echo "<pre>";
  // print_r($_SESSION); 
  // echo "</pre>";
  // exit;

  // if (is_admin()) {
  //   if (!empty($_SESSION['editmode']) && $_GET['u']) {
  //     $_SESSION['user_id'] = (int) $_GET['u'];
  //   } else if (empty($_SESSION['editmode'])) {
  //     $_SESSION['user_id'] = (int) $_SESSION['admin'];
  //   }
  // }

  // exit;

  if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
    $loggedin = (int) $_SESSION['user_id'];
  } else {
    $loggedin = false;
  }

  if (!empty($_GET['u'])) {
    $profileid = (int) $_GET['u'];
  }

  // if($loggedin){
  //   echo "yes";
  // }else{
  //   echo "no";
  // }

  // echo $loggedin;

  // if user is not logged in, check if there's a "remember me" cookie
  // if (!isset($_SESSION['user_id'])) {
  //   if (!empty($_COOKIE['agency_arm'])) {
  //     $cookie = escape_data($_COOKIE['agency_arm']);
  //     $query = "SELECT user_id FROM agency_rememberme WHERE session_id='$cookie'";
  //     $result = @mysql_query($query);
  //     if ($row = @mysql_fetch_array($result, mysql_ASSOC)) {
  //       $_SESSION['user_id'] = $row['user_id'];
  //     }
  //   }
  // }

  // this is for the castings filtering
?>