<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

@include('header.php');


    
// define('IN_PHPBB', true);
// $phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './phpbb/';
// $phpEx = substr(strrchr(__FILE__, '.'), 1);
// require($phpbb_root_path . 'common.' . $phpEx);
// require($phpbb_root_path . 'includes/functions_user.' . $phpEx);
// require($phpbb_root_path . 'includes/functions_module.' . $phpEx);
$err = "N";

if(isset($_POST['submit']) && $_POST['submit'] == "LOGIN") {

  $sql = "SELECT user_id, user_password FROM forum_users WHERE username='".$_POST["username"]."'";
  $result = mysql_query($sql);
  if($row = sql_fetchrow($result)) {
    // echo "<pre>";print_r($row);
    include('functions_login.php');

    // echo $_POST['password'];
    // echo $row['user_password'];exit;

    if(phpbb_check_hash($_POST['password'], $row['user_password'])) {
      $_SESSION['user_id'] = $row['user_id'];
      // $url = "profile.php";
      // echo "<pre>";
      // print_r($_SESSION);exit;

      // $url = "adminXYZ";
      // $url = "admin_sub";
      $url = "casting-manager";
      header("Location: $url");
    }else{
      $err = "Y";
    }
  }else{
    $err = "Y";
  }
  // echo "<pre>";print_r($_POST);exit;
  if($err == "Y"){
    $msg = "There was a problem with your login information. Please carefully type in your Username and Password again. If you continue to experience problems please contact us. ";
  }

}

?>

<br />
<br />
<div class="signup-content">
<div class="container">
    <div class="login-form-new">
        <?php if(isset($msg)){ ?><p class="text-center"><?php echo $msg; ?></p><?php } ?>
        <form method="post" action="login.php">
          <table align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td colspan="2"><div align="center"><strong>Already a Member? Sign in.</strong></div><br /></td>
            </tr>
            <tr>
              <td align="right">Username:</td>
              <td><input name="username" type="text" size="25" /><br /><br /></td>
            </tr>
            <tr>
              <td align="right">Password:</td>
              <td><input name="password" type="password" size="25" /><br /><br /></td>
            </tr>
            <tr>
              <td class="p3">&nbsp;</td>
              <td>
                <input name="redirect" value="../profile.php" type="hidden" />
                <input type="hidden" name="login" value="login" />
                <input type="submit" name="submit" value="LOGIN" /><span style="font-weight:normal; padding-left:20px;"><input type="checkbox" name="rememberme" /> remember me</span>
                <div align="right"></div></td>
            </tr>
            <tr>
              <td class="p3">&nbsp;</td>
              <td><a href="forgotpassword.php" class="p3">Forgot Your Password?</a></td>

          </table>
        </form>
        
        </div>
        </div>
</div>
<?php
@include('footer.php');
?>
