<?php 
  include('header_code.php');
  if($loggedin){
    $url = "index.php";
    header("Location: $url");
    exit;
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('head.php'); ?>
  <?php include('common_css.php'); ?>
</head>

<body>
  <?php include('header.php'); ?>
  <?php
  // define('IN_PHPBB', true);
  // $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './phpbb/';
  // $phpEx = substr(strrchr(__FILE__, '.'), 1);
  // require($phpbb_root_path . 'common.' . $phpEx);
  // require($phpbb_root_path . 'includes/functions_user.' . $phpEx);
  // require($phpbb_root_path . 'includes/functions_module.' . $phpEx);
  $err = "N";

  if(isset($_POST['submit']) && $_POST['submit'] == "LOGIN") {

  $sql = "SELECT user_id, user_password,user_type FROM forum_users WHERE username='".$_POST["username"]."'";
  $result = mysql_query($sql);
  if($row = sql_fetchrow($result)) {

    // echo "<pre>";print_r($row);
    include('functions_login.php');

    // echo $_POST['password'];
    // echo $row['user_password'];exit;

    if(phpbb_check_hash($_POST['password'], $row['user_password'])) {
      $_SESSION['user_id'] = $row['user_id'];

      $login_time = time();
      $query_forum = "UPDATE forum_users SET user_lastmark = 'Y',user_lastvisit = ".$login_time." WHERE user_id = ".$_SESSION['user_id']."";
      @mysql_query($query_forum);
        
      $sql_ap = "SELECT  * FROM agency_profiles WHERE user_id='".$_SESSION['user_id']."'";
      $result_ap = mysql_query($sql_ap);
      if($ap = sql_fetchrow($result_ap)) {
        $_SESSION['account_type'] = $ap['account_type'];

        // echo "<br/><br/><br/>";
        // echo "<pre>";print_r($ap);
        // exit; 

        if($_SESSION['account_type'] == 'admin'){
          $_SESSION['admin'] = 'Y';
          $url = "admin_sub";

          // $sql = "SELECT * FROM agency_admins WHERE user_id='".$_SESSION['user_id']."'";
          // $query_admin = mysql_query($sql);
          // if(mysql_num_rows($query_admin) > 0) {
          //   // $url = "admin_sub";
          //   if($row1 = sql_fetchrow($query_admin)) {
          //     if($row1['super'] == 0){
          //       $_SESSION['admin'] = 'Y';
          //       $url = "admin_sub";
          //     }else{
          //       $_SESSION['superadmin'] = 'Y';
          //       $url = "adminXYZ";
          //     }
          //   }
            
          // }else{
          //   $err = "Y";
          // }
        }else if($_SESSION['account_type'] == 'super_admin'){
          $_SESSION['superadmin'] = 'Y';
          $url = "adminXYZ";
        }else if($_SESSION['account_type'] == 'talent'){
          $url = "talent";
        }else if($_SESSION['account_type'] == 'talent_manager'){
          $url = "talent-manager";
        }else if($_SESSION['account_type'] == 'client'){
          $url = "casting-manager";
        }

      }

      // $url = "profile.php";
      // echo "<pre>";
      // print_r($_SESSION);exit;

      // if($row['user_type'] == 0){
      //   $url = "adminXYZ";
      // }else{
      // }
      // $url = "admin_sub";
      // $url = "casting-manager";
    
    }else{
      $err = "Y";
    }
  }else{
    $err = "Y";
  }
  // echo "<pre>";print_r($_POST);exit;
  if($err == "Y"){
    $msg = "There was a problem with your login information. Please carefully type in your Username and Password again. If you continue to experience problems please contact us. ";
  }else{
    header("Location: $url");
  }

}
?>

<div class="login-page-content container-fluid">

  <!-- <div class="container"> -->
      <div class="wrapper">
          <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">

            <?php if (isset($msg)) { ?>
                <div class="alert alert-danger clearfix text-left" role="alert">
                    <?php echo $msg; ?>
                </div>
            <?php } ?>

            <form method="post" action="login.php">
                  <h3 class="text-center color-white">ALREADY A MEMBER? SIGN IN</h3>

                  <div class="col-sm-12">
                    <div class="form-group">
                      <input name="username" type="text" class="form-control-custom" placeholder="USERNAME" style="text-align: center;padding:15px;"/>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <input name="password" type="password" class="form-control-custom" placeholder="PASSWORD" style="text-align: center;padding:15px;"/>
                    </div>
                  </div>
                  <div class="col-sm-12 text-center">
                    <input name="redirect" value="../profile.php" type="hidden" />
                    <input type="hidden" name="login" value="login" />
                    <input type="submit" name="submit" value="LOGIN" class="btn btn-lg btn-theme btn-flat color-white" style="padding-left: 50px; padding-right: 50px;"/>
                    <br/><br/>
                    <label class="text-white weight-normal color-white"><input type="checkbox" name="rememberme" /> Remember Me</label>
                    <br/><br/>
                    <h4 class="weight-normal"><a href="forgotpassword.php" class="color-white">Forgot Your Password?</a></h4>
                  </div>

            </form>
          </div>
      </div>
  <!-- </div> -->

</div>
<?php
include('footer_js.php');
include('footer.php');
?>

</body>

</html>