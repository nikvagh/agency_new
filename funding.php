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
  <title>Funding Box</title>
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

    $sql_forum_ins = "INSERT into agency_funding_box 
                                SET
                                project_name = '" . $_POST['project_name'] . "',
                                project_type = '" . $_POST['project_type'] . "',
                                project_description = '" . $_POST['project_description'] . "',
                                funding_you_need = '" . $_POST['funding_you_need'] . "',
                                director_name = '" . $_POST['director_name'] . "',
                                contact_name = '" . $_POST['contact_name'] . "',
                                contact_email = '" . $_POST['contact_email'] . "',
                                contact_phone_number = '" . $_POST['contact_phone_number'] . "'
                        ";
    if (mysql_query($sql_forum_ins)) {
          $funding_id_ins = mysql_insert_id();

          // upload material
          $folder_material = 'uploads/funding_box/';

          if (isset($_FILES['material']) && $_FILES['material']['name'] != "") {
            // if (in_array($_FILES['material']['material'], $allowed_headshot)) {

              // Move the file over.
              $filename_material = filename_new($_FILES['material']['name']);
              $destination_material = $folder_material . $filename_material;
              if (move_uploaded_file($_FILES['material']['tmp_name'], "$destination_material")) {

                $filename_material_db = $filename_material;

                $update_material = "UPDATE agency_funding_box 
                        SET
                        material = '".$filename_material_db."'
                        WHERE  
                        agency_funding_box_id = ".$funding_id_ins."";
                mysql_query($update_material);

              } else {
                // $notification['error'] = "Something Wrong With Headshot Picture.";
              }
            // } else {
              // Invalid type.
              // $notification['error'][] = "Something Wrong With Headshot Picture.";
            // }
            
          }

          $notification['success'] = "Thank you for submitting your project for consideration. Our team will be in contact with you shortly.";
    }

  }

  ?>

  <div class="signup-content">
    <div class="container">

      <div class="talent-form">
        <h2>FUNDING BOX</h2>
        <hr class="welcome">

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

        <form class="" action="" method="POST" id="myform" enctype="multipart/form-data">

            <div class="col-sm-12">
              <div class="form-group">
                <!-- <label class="weight-normal color-white">Project Name</label> -->
                <input type="text" placeholder="Project Name" class="form-control-custom" name="project_name" >
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="Project Type" class="form-control-custom" name="project_type">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <textarea placeholder="Project Description" class="form-control-custom" name="project_description"></textarea>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <label class="weight-normal color-white">Do you have distribution </label>
                <label class="weight-normal color-white"><input type="radio" name="have_distribution" value="Yes"> Yes &nbsp;&nbsp;&nbsp;&nbsp;</label>
                <label class="weight-normal color-white"><input type="radio" name="have_distribution" value="No"> No &nbsp;&nbsp;&nbsp;&nbsp; </label>
                <spna class="radio_err"></spna>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="How much funding do you need?" class="form-control-custom" name="funding_you_need"/>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="Name of Director" class="form-control-custom" name="director_name"/>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="Contact Name" class="form-control-custom" name="contact_name"/>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="Contact Email" class="form-control-custom" name="contact_email"/>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="Contact Phone" class="form-control-custom" name="contact_phone_number">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="file" placeholder="Upload any materials relevant to your request" class="form-control-custom" name="material">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <label class="weight-normal color-white"><input type="checkbox" name="terms_confdition" /> Terms & Conditions <a href="">Click To View</a></label>
                <span class="check_err"></span>
              </div>
            </div>

            <div class="col-sm-12 text-center">
              <div class="form-group">
                <input type="submit" class="btn btn-lg btn-theme btn-flat color-white submit" name="submit" value="submit">
              </div>
            </div>

        </form>

      </div>
    </div>
  </div>
  
  <?php include('footer.php'); ?>
  <?php include('footer_js.php'); ?>

  <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js"></script>
  <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.js"></script>

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
            project_name: {
              required: true,
            },
            project_type: {
              required: true,
            },
            have_distribution: {
              required: true
            },
            funding_you_need: {
              required: true
            },
            director_name: {
              required: true,
            },
            contact_name: {
              required: true,
            },
            contact_email: {
              required: true,
              email: true
            },
            contact_phone_number: {
              required: true,
            },
            terms_confdition: {
              required: true,
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