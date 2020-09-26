<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include('header_code.php');
include('includes/agency_dash_functions.php');
?>

<!DOCTYPE html>
<html>

<head>
  <title>Forgot Password</title>
  <?php include('head.php'); ?>
  <?php include('common_css.php'); ?>
  <!-- date & time picker -->
	<!-- <link rel="stylesheet" media="all" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/ui-lightness/jquery-ui.min.css" />
  <link rel="stylesheet" media="all" type="text/css" href="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.css" /> -->
  <style type="text/css">
    .help-block {
      color: #a94442;
    }
  </style>
</head>

<body>
  <?php include('header.php'); ?>

  <?php
  $notification = array();
  if (isset($_POST['submit'])) {

    // echo "<pre>";
    // print_r($_POST);
    // print_r($_FILES);
    // exit;

    $sql_select = "SELECT * FROM forum_users WHERE user_email = '".$_POST['email']."' ";
    $query_select = mysql_query($sql_select);
    if (mysql_num_rows($query_select) > 0) {
			while ($row = @mysql_fetch_assoc ($query_select)) {
        // echo "<pre>";print_r($row);

        $password_email = randomStr(6);
        // echo "<br/>";
        $password_db = _hash($password_email);
        $sql_forum_update = "UPDATE forum_users 
                                SET
                                user_password = '" . $password_db . "'
                                WHERE
                                user_id = ".$row['user_id']."
                        ";

        if(mysql_query($sql_forum_update)){
          // send mail

          $to_email = $row['user_email'];
          $subject = "AGENCY - FORGOT PASSWORD";
          $msg = "YOUR NEW PASSWORD IS <b>".$password_email."</b>";
          if(send_mail($to_email,$subject,$msg)){
            $notification['success'] = "New Password Sent Successfully. Check Your Mail Box.";
          }
        }

			}
		}else{
      $notification['error'] = "Invalid Email. check Your Email And Retry.";
    }

  }
  ?>

  <div class="forgot-content container-fluid">
    <!-- <div class="container"> -->

      <!-- <div class="forgot-form"> -->
        <!-- <h2>Forgot Password</h2> -->
        <!-- <hr class="welcome"> -->

        <?php if (isset($notification['success'])) { ?>
            <br/>
            <div class="alert alert-success clearfix text-left" role="alert">
                <?php echo $notification['success']; ?>
            </div>
        <?php } ?>
        <?php if (isset($notification['error'])) { ?>
            <br/>
            <div class="alert alert-danger clearfix text-left" role="alert">
                <?php echo $notification['error']; ?>
            </div>
        <?php } ?>
        
        <div class="wrapper">
          <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12">
            <h4 class="color-white weight-normal text-center">PLEASE ENTER YOUR EMAIL ADDRESS. YOUR USERNAME WILL BE EMAILED TO YOU. <br/>YOUR PASSWORD WILL BE RESET AND SENT TO YOU AS WELL.<br/> YOUR OLD PASSWORD WILL NO LONGER BE VALID.</h4>
            <form class="" action="" method="POST" id="myform" enctype="multipart/form-data">
                <!-- <br/><br/> -->
                <div class="col-sm-12">
                  <div class="form-group">
                    <!-- <label class="weight-normal color-white">Project Name</label> -->
                    <input type="text" placeholder="Email" class="form-control-custom" name="email" >
                  </div>
                </div>

                <br/>
                <div class="col-sm-12 text-center">
                  <div class="form-group">
                    <input type="submit" class="btn btn-lg btn-theme btn-flat color-white" name="submit" value="SEND NEW PASSWORD">
                  </div>
                </div>
            </form>
          </div>
        </div>

      <!-- </div> -->

    <!-- </div> -->
  </div>
  
  <?php include('footer.php'); ?>
  <?php include('footer_js.php'); ?>

  <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js"></script>
  <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.js"></script>

  <!-- <script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
  <script type="text/javascript" src="dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
  <script type="text/javascript" src="dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

  <script type="text/javascript">
    // $(document).ready(function() {

    //   $('#birthdate').datepicker({
    // 	  changeMonth: true,
    //     changeYear: true,
    //     maxDate: 0,
    //     dateFormat: 'yy-mm-dd',
    //   });

      $("#myform").validate({
          errorElement: 'span',
          errorClass: 'help-block',
          highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error err");
          },
          unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error err");
          },
          rules: {
            email: {
              required: true,
              email: true
            },
          },
          messages: {
          },
          errorPlacement: function(error, element) {
            if (element.attr("type") == "radio") {
              error.insertAfter(element.parent().parent().children('.radio_err'));
            } else if(element.attr("type") == "checkbox"){
              error.insertAfter(element.parent().parent().children('.check_err'));
            } else {
              element.after(error); // default error placement
            }
          }
          // ,submitHandler: function (form) {
          // 	console.log("Submitted!");
          // 	form.submit();
          // }
      });

  </script>
  <script>
      if (window.history.replaceState) {
          window.history.replaceState(null, null, window.location.href);
      }
  </script>
</body>

</html>