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
  <title>Talent Sign Up</title>
  <?php include('head.php'); ?>
  <?php include('common_css.php'); ?>
  <!-- date & time picker -->
	<link rel="stylesheet" media="all" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/ui-lightness/jquery-ui.min.css" />
  <link rel="stylesheet" media="all" type="text/css" href="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.css" />
  
  <style type="text/css">
    .part {
      display: none;
    }

    .part.active {
      display: block;
    }

    .next {
      float: right;
    }

    .help-block {
      color: #a94442;
    }
  </style>
</head>

<body>
  <?php include('header.php'); ?>

  <?php
  use \Gumlet\ImageResize;
  use \Gumlet\ImageResizeException;
  include('ImageResize/ImageResize.php');

  $notification = array();
  if (isset($_POST['firstname']) && isset($_POST['lastname'])) {

    // echo "<pre>";
    // print_r($_POST);
    // print_r($_FILES);
    // exit;

    $password = _hash($_POST['password']);
    $user_type = 1;
    $user_ip = getRealIpAddr();
    $user_regdate = time();

    $sql_forum_ins = "INSERT into forum_users 
                                SET
                                username = '" . $_POST['username'] . "',
                                username_clean = '" . $_POST['username'] . "',
                                user_email = '" . $_POST['email'] . "',
                                user_password = '" . $password . "',
                                user_ip = '" . $user_ip . "',
                                user_regdate = '" . $user_regdate . "'
                        ";
    if (mysql_query($sql_forum_ins)) {
      $user_id_ins = mysql_insert_id();

      $height_ft = $_POST['height_ft'];
      $height_inch = $_POST['height_inch'];
      $height = ($height_ft*12) + $height_inch;
    
      $sql_profile_ins = "INSERT into agency_profiles 
                                    SET
                                    user_id = '" . $user_id_ins . "',
                                    account_type = 'talent',
                                    firstname = '" . $_POST['firstname'] . "',
                                    lastname = '" . $_POST['lastname'] . "',
                                    cell_phone = '" . $_POST['cell_phone'] . "',
                                    phone = '" . $_POST['phone'] . "',
                                    location = '" . $_POST['location'] . "',
                                    gender = '" . $_POST['gender'] . "',
                                    birthdate = '" . $_POST['birthdate'] . "',
                                    weight = '" . $_POST['weight'] . "',
                                    height_ft = '" . $_POST['height_ft'] . "',
                                    height_inch = '" . $_POST['height_inch'] . "',
                                    height = '" . $height . "',
                                    nationality = '" . $_POST['nationality'] . "',
                                    hair_color = '" . $_POST['hair_color'] . "',
                                    hair_length = '" . $_POST['hair_length'] . "',
                                    eye_color = '" . $_POST['eye_color'] . "',
                                    eye_shape = '" . $_POST['eye_shape'] . "',
                                    bust = '" . $_POST['bust'] . "',
                                    shirt = '" . $_POST['shirt'] . "',
                                    Kids = '" . $_POST['Kids'] . "',
                                    dress = '" . $_POST['dress'] . "',
                                    hips = '" . $_POST['hips'] . "',
                                    glove = '" . $_POST['glove'] . "',
                                    cup = '" . $_POST['cup'] . "',
                                    shoe = '" . $_POST['shoe'] . "',
                                    jacket = '" . $_POST['jacket'] . "',
                                    pants = '" . $_POST['pants'] . "',
                                    inseam = '" . $_POST['inseam'] . "',
                                    hat = '" . $_POST['hat'] . "'
                                ";

      if (mysql_query($sql_profile_ins)) {
        
        if (isset($_POST['ethnicities'])) {
          foreach ($_POST['ethnicities'] as $val) {
            $sql_ethnicities_ins = "INSERT into agency_profile_ethnicities 
                      SET
                      user_id = '" . $user_id_ins . "',
                      ethnicity = '" . $val . "'
                  ";
            mysql_query($sql_ethnicities_ins);
          }
        }

        if (isset($_POST['type_of_casting'])) {
          foreach ($_POST['type_of_casting'] as $val) {
            $sql_casting_ins = "INSERT into agency_profile_castings 
                      SET
                      user_id = '" . $user_id_ins . "',
                      casting_type = '" . $val . "'
                  ";
            mysql_query($sql_casting_ins);
          }
        }

        if (isset($_POST['union_status'])) {
          foreach ($_POST['union_status'] as $val) {
            $sql_union_ins = "INSERT into agency_profile_unions 
                      SET
                      user_id = '" . $user_id_ins . "',
                      union_name = '" . $val . "'
                  ";
            mysql_query($sql_union_ins);
          }
        }

        $identical_twin_triplet = "N";
        if (isset($_POST['identical_twin_triplet'])) {
          $identical_twin_triplet = "Y";
        }
        $sql_union_ins = "INSERT into agency_talent 
                                SET
                                user_id = '" . $user_id_ins . "',
                                other_region = '" . $_POST['other_region'] . "',
                                parent_guardian = '" . $_POST['parent_guardian'] . "',
                                pregnant = '" . $_POST['pregnant'] . "',
                                twin_triplete = '" . $_POST['twin_triplete'] . "',
                                identical_twin_triplet = '" . $identical_twin_triplet . "',
                                veteran = '" . $_POST['veteran'] . "',
                                military_branch = '" . $_POST['military_branch'] . "',
                                years_in_service = '" . $_POST['years_in_service'] . "',
                                work_extra = '" . $_POST['work_extra'] . "',
                                cut_hair = '" . $_POST['cut_hair'] . "',
                                play_younger = '" . $_POST['play_younger'] . "',
                                have_passport = '" . $_POST['have_passport'] . "',
                                work_permit = '" . $_POST['work_permit'] . "',
                                full_nudity = '" . $_POST['full_nudity'] . "'
                            ";
        if (mysql_query($sql_union_ins)) {

          // upload headshot
          $folder_headshot = 'uploads/users/' . $user_id_ins . '/headshot/';
          $folder_headshot_thumb = $folder_headshot . 'thumb/';

          $allowed_headshot = array('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
          if (isset($_FILES['headshot']) && $_FILES['headshot']['name'] != "") {
            if (in_array($_FILES['headshot']['type'], $allowed_headshot)) {

              if (!is_dir($folder_headshot_thumb)) {
                mkdir($folder_headshot_thumb, 0777, true);
              }

              // Move the file over.
              $filename_headshot = filename_new($_FILES['headshot']['name']);
              $destination_headshot = $folder_headshot . $filename_headshot;
              if (move_uploaded_file($_FILES['headshot']['tmp_name'], "$destination_headshot")) {
                foreach ($headshot_thumb as $height => $width) {
                  $image = new ImageResize($destination_headshot);
                  $image->resizeToHeight($height);
                  $image->save($folder_headshot_thumb . $height . 'x' . $width . '_' . $filename_headshot);
                }
                $filename_headshot_db = $filename_headshot;

                $update_headshot = "UPDATE agency_profiles 
                        SET
                        headshot = '".$filename_headshot_db."'
                        WHERE  
                        user_id = ".$user_id_ins."";
                mysql_query($update_headshot);

              } else {
                // $notification['error'] = "Something Wrong With Headshot Picture.";
              }
            } else {
              // Invalid type.
              // $notification['error'][] = "Something Wrong With Headshot Picture.";
            }
            
          }

          $notification['success'] = "Account Created Successfully.";
        }

      }
    }

  }

  ?>
  <div class="menu">
    <ul>
      <li><a href="talent_signup.php" class="active">TALENT</a></li>
      <li><a href="client_signup.php">CLIENT</a></li>
      <li><a href="agent-signup.php">TALENT MANAGER/AGENT</a></li>
    </ul>
  </div>

  <div class="signup-content">
    <div class="container">

      <div class="talent-form">
        <h2>WELCOME</h2>
        <hr class="welcome">
        <h3>CREATE YOUR ACCOUNT</h3>

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

          <fieldset id="part_1" class="part active">
            <!-- <legend>Account information</legend> -->
            <div class="col-sm-12 talent-demo">
              <div class="col-sm-6">
                <div class="form-group">
                  <input type="text" placeholder="FIRST NAME" class="form-control-custom" name="firstname" >
                </div>
              </div>

              <div class="col-sm-6">
                <div class="form-group">
                  <input type="text" placeholder="LAST NAME" class="form-control-custom" name="lastname" >
                </div>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="EMAIL ADDRESS" class="form-control-custom" name="email" >
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="USERNAME" class="form-control-custom" name="username">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="password" placeholder="PASSWORD" class="form-control-custom" name="password" id="password">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="password" placeholder="CONFIRM PASSWORD" class="form-control-custom" name="confirm_password">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="PHONE NUMBER" class="form-control-custom" name="phone">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group">
                <input type="text" placeholder="CELL PHONE NUMBER" class="form-control-custom" value="">
              </div>
            </div>

            <div class="col-sm-12">
              <a class="btn btn-theme btn-flat color-white previous disabled" id="">Previous</a>
              <a class="btn btn-theme btn-flat color-white next">Next</a>
            </div>
          </fieldset>

          <fieldset id="part_2" class="part">
            <!-- <legend>Account information</legend> -->

            <div class="row">
              <div class="form-txt">
                <h4>
                  ENTER YOUR DETAILS BELOW TO RECEIVE MATCHING ROLES<span style="color: #612; font-weight: 700;"> IMMEDIATELY!</span>
                  <hr>
                </h4>
              </div>
              <div class="col-sm-12">
                <div class="col-sm-6">
                  <div class="form-group demo-form">
                    <label class="control-label">CHOOSE A PRIMARY REGION <span>*</span></label>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <select name="location" class="form-control-custom" onchange="">
                      <option value=""></option>
                      <?php
                      foreach ($locationarray as $location) {
                        echo '<option value="' . $location . '">' . $location . '</option>';
                      }
                      ?>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>


            <div class="row">
              <div class="col-sm-12">
                <div class="col-sm-6">
                  <div class="form-group demo-form">
                    <label class="control-label">OTHER REGIONS</label>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <select class="form-control-custom" name="other_region">
                      <option value=""></option>
                      <?php
                      foreach ($locationarray as $location) {
                        echo '<option value="' . $location . '">' . $location . '</option>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

              </div>
            </div>


            <div class="row">
              <div class="col-sm-12">
                <div class="center">
                  <h4 class="color-white">PORTRAYABLE ETHNICITIES (CHOOSE ALL THAT APPLY) </h4>
                  <div class="form-txt">
                    <hr>
                  </div>
                </div>

                <div class="check-box-form">
                  <?php
                  for ($i = 0; isset($ethnicityarray[$i]); $i++) {
                    echo '<label class="color-white weight-normal"><input type="checkbox" name="ethnicities[]" id="ethnicities[' . $i . ']" value="' . $ethnicityarray[$i] . '"';
                    if (in_array($ethnicityarray[$i], $ethnicities)) echo ' checked';
                    echo ' /> ' . $ethnicityarray[$i] . '</label>';
                    echo '<br/>';
                  }
                  ?>
                  <span class="check_err"></span>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="form-txt">
                  <hr>
                </div>
                <div class="col-sm-2">
                  <!-- <div class="form-group"> -->
                  <label class="control-label color-white weight-normal">GENDER <span>*</span></label>
                  <!-- </div> -->
                </div>

                <div class="col-sm-6">
                  <div class="check-box-form">
                    <label class="color-white"><input type="radio" name="gender" value="M" <?php if (!empty($gender)) {
                                                                                              if ($gender == 'M') echo 'checked';
                                                                                            } ?> /> Male</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="color-white"><input type="radio" name="gender" value="F" <?php if (!empty($gender)) {
                                                                                              if ($gender == 'F') echo 'checked';
                                                                                            } ?> /> Female</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label class="color-white"><input type="radio" name="gender" value="O" <?php if (!empty($gender)) {
                                                                                              if ($gender == 'O') echo 'checked';
                                                                                            } ?> />Transgender</label>
                    <span class="radio_err"></span>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-12">
              <a class="btn btn-theme btn-flat color-white previous" id="">Previous</a>
              <a class="btn btn-theme btn-flat color-white next">Next</a>
            </div>
          </fieldset>

          <fieldset id="part_3" class="part">
            <!-- <legend>Personal information</legend> -->

            <div class="gender-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">PORTRAYABLE AGE RANGE </label>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <select name="age_start" class="form-control-custom">
                        <option value="select"></option>
                        <?php for ($i = 1; $i <= 100; $i++) { ?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-1">
                    <div class="form-group demo-form">
                      <label class="control-label">to</label>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <select name="age_to" class="form-control-custom">
                        <option value="select"></option>
                        <?php for ($i = 1; $i <= 100; $i++) { ?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="gender-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Types Of Casting <span>*</span></label>
                    </div>
                  </div>
                  <div class="col-sm-8">
                    <?php foreach ($castingarray as $val) { ?>
                      <div class="col-md-4"><label class="color-white weight-normal"> <input type="checkbox" name="type_of_casting[]" value="<?php echo $val; ?>" /><?php echo $val; ?></label></div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="gender-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Union Status:</label>
                    </div>
                  </div>
                  <div class="col-sm-8">
                    <?php foreach ($jobunionarray as $val) { ?>
                      <div class="col-md-4"><label class="color-white weight-normal"> <input type="checkbox" name="union_status[]" value="<?php echo $val; ?>" /><?php echo $val; ?></label></div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="gender-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Headshot:</label>
                    </div>
                  </div>
                  <div class="col-sm-8">
                    <input type="file" class="form-control-custom" name="headshot" />
                  </div>
                </div>
              </div>
            </div>

            <div class="form-txt">
              <br /><br /><br /><br />
              <h4>
                BIOGRAPHICAL DATA
                <hr>
              </h4>
            </div>

            <div class="biographical-section">
              <div class="row">

                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Date OF Birth:</label>
                    </div>
                  </div>
                  <div class="col-sm-8 color-white">
                    <input type="text" id="birthdate" name="birthdate" value="" class="form-control-custom"> (Can Use Appearing Age Of Birth If Over 18 Years Of Age)
                  </div>
                </div>

              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Parent/Guardian:</label>
                    </div>
                  </div>

                  <div class="col-sm-8">
                    <div class="form-group">
                      <input type="text" name="parent_guardian" value="" class="form-control-custom">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">

                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Pregnant?</label>
                    </div>
                  </div>
                  <div class="col-sm-8">
                    <div class="form-group">
                      <select name="pregnant" class="form-control-custom">
                        <option value="">-select-</option>
                        <option value="No">No</option>
                        <option value="Yes">Yes</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">

                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Twin/Triplet? </label>
                    </div>
                  </div>

                  <div class="col-sm-8">
                    <div class="form-group">
                      <select name="twin_triplete" class="form-control-custom">
                        <option value=""></option>
                        <option value="Twin">Twin</option>
                        <option value="Triplet">Triplet</option>
                      </select>
                      <label class="color-white weight-normal"><input type="checkbox" name="identical_twin_triplet" value=""> Identical twin/triplets? </label>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Weight(lbs):</label>
                    </div>
                  </div>
                  <div class="col-sm-8">
                    <div class="form-group">
                      <select name="weight" class="form-control-custom">
                        <option value="">-- Select --</option>
                        <?php for ($i = 0; $i <= 300; $i++) { ?>
                          <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">

                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Height</label>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label color-white weight-normal">Feet</label>
                      <select name="height_ft" class="form-control-custom">
                        <?php for ($i = 1; $i <= 10; $i++) { ?>
                          <option value="<?php echo $i; ?>" <?php if ($userInfo['height_ft'] == $i) {
                                                              echo "selected";
                                                            } ?>><?php echo $i; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label color-white weight-normal">Inch</label>
                      <select name="height_inch" class="form-control-custom">
                        <?php for ($i = 0; $i <= 11; $i++) { ?>
                          <option value="<?php echo $i; ?>" <?php if ($userInfo['height_inch'] == $i) {
                                                              echo "selected";
                                                            } ?>><?php echo $i; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">

                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Veteran: </label>
                    </div>
                  </div>
                  <div class="col-sm-8">
                    <div class="form-group">
                      <select name="veteran" class="form-control-custom">
                        <option value="">--select--</option>
                        <option value="N">No</option>
                        <option value="Y">Yes</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Military Branch: </label>
                    </div>
                  </div>
                  <div class="col-sm-8">
                    <div class="form-group">
                      <input type="text" name="military_branch" class="form-control-custom" placeholder="" value="">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">

                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Years in Service: </label>
                    </div>
                  </div>

                  <div class="col-sm-8">
                    <select name="years_in_service" class="form-control-custom">
                      <option value="">-- Select --</option>
                      <?php for ($i = 1; $i <= 50; $i++) { ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                      <?php } ?>
                    </select>
                  </div>

                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label color-white weight-normal">Nationality:</label>
                    </div>
                  </div>

                  <div class="col-sm-8">
                    <div class="form-group">
                      <select name="nationality" id="nationality" class="form-control-custom">
                        <option value="">-- Select --</option>
                        <?php foreach ($countryarray as $key => $val) { ?>
                          <option value="<?php echo $val; ?>" <?php if ($userInfo['nationality'] == $val) {
                                                                echo "selected";
                                                              } ?>><?php echo $val; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">

                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Hair </label>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label color-white weight-normal">Hair Color </label>
                      <select name="hair_color" id="hair_color" class="form-control-custom">
                        <option value="">-- Select --</option>
                        <?php foreach ($haircolorarray as $val) { ?>
                          <option value="<?php echo $val; ?>" <?php if ($userInfo['hair_color'] == $val) {
                                                                echo "selected";
                                                              } ?>><?php echo $val; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label color-white weight-normal">Hair Length </label>
                      <input type="text" name="hair_length" id="hair_length" value="" class="form-control-custom" />
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">

                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Eye</label>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label color-white weight-normal">Eye Color </label>
                      <select name="eye_color" id="eye_color" class="form-control-custom">
                        <option value="">-- Select --</option>
                        <?php foreach ($eyecolorarray as $val) { ?>
                          <option value="<?php echo $val; ?>" <?php if ($userInfo['eye_color'] == $val) {
                                                                echo "selected";
                                                              } ?>><?php echo $val; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label color-white weight-normal">Eye shape </label>
                      <select name="eye_shape" id="eye_shape" class="form-control-custom">
                        <option value="">-- Select --</option>
                        <?php foreach ($eyeShapeArray as $val) { ?>
                          <option value="<?php echo $val; ?>" <?php if ($userInfo['eye_shape'] == $val) {
                                                                $et_select = "selected";
                                                              } ?>><?php echo $val; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">

                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Size </label>
                    </div>
                  </div>

                  <div class="col-sm-8">

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Bust size: </label>
                      </div>
                      <div class="form-group">
                        <select name="bust" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Shirt size:</label>
                      </div>
                      <div class="form-group">
                        <select name="shirt" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Kids size:</label>
                      </div>
                      <div class="form-group">
                        <select name="Kids" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Dress size:</label>
                      </div>
                      <div class="form-group">
                        <select name="dress" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Hips size:</label>
                      </div>
                      <div class="form-group">
                        <select name="hips" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Glove size:</label>
                      </div>
                      <div class="form-group">
                        <select name="glove" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Cup size:</label>
                      </div>
                      <div class="form-group">
                        <select name="cup" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Shoe size:</label>
                      </div>
                      <div class="form-group">
                        <select name="shoe" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Jacket size:</label>
                      </div>
                      <div class="form-group">
                        <select name="jacket" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Pants size:</label>
                      </div>
                      <div class="form-group">
                        <select name="pants" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Waist size:</label>
                      </div>
                      <div class="form-group">
                        <select name="jacket" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Inseam :</label>
                      </div>
                      <div class="form-group">
                        <select name="inseam" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Hat size:</label>
                      </div>
                      <div class="form-group">
                        <select name="hat" class="form-control-custom">
                          <?php for ($i = 0; $i <= 5; $i++) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>

                  </div>

                </div>

              </div>
            </div>


            <!-- <div class="gender-section">
                <div class="row">
                  <div class="col-sm-12">
                    <div class="col-sm-2">
                      <div class="form-group demo-form">
                        <label class="control-label">Social Media:</label>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <select name="city" class="form-control-custom">
                          <option value="">FB</option>
                          <option value="">Insta</option>
                          <option value="">Twitter</option>
                          <option value="">Linkedin</option>
                          <option value="">IMDB</option>
                          <option value="">Snapchat</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->

            <div class="biographical-section">
              <div class="col-sm-4">
                <div class="form-group demo-form">
                  <label class="control-label">Are you willing to work as an extra?</label>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <select name="work_extra" class="form-control-custom">
                    <option value=""></option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Willing to cut hair:</label>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <select name="cut_hair" class="form-control-custom">
                        <option value=""></option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">

                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Eighteen to play younger: </label>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <select name="play_younger" class="form-control-custom">
                        <option value=""></option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
            </div>


            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Do you have a passport? </label>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <select name="have_passport" class="form-control-custom">
                        <option value=""></option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">

                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">Work Permit (if younger than 18)?</label>
                    </div>
                  </div>

                  <div class="col-sm-4">
                    <div class="form-group">
                      <select name="work_permit" class="form-control-custom">
                        <option value=""></option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                      </select>
                    </div>
                  </div>

                </div>
              </div>
            </div>


            <div class="biographical-section">
              <div class="row">
                <div class="col-sm-12">
                  <div class="col-sm-4">
                    <div class="form-group demo-form">
                      <label class="control-label">I am interested in roles with partial or full nudity?</label>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <select name="full_nudity" class="form-control-custom">
                        <option value=""></option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <br />
            <br />

            <?php
            // if (!empty($_GET['talent-manager'])) {
            //   $query = "SELECT firstname, lastname, location FROM agency_profiles WHERE user_id=92";
            //   $result = @mysql_query($query); //print_r($result);
            //   if ($row = @mysql_fetch_array($result, mysql_ASSOC)) {
            //     //echo '<span class="AGENCYRed" style="font-weight:bold">Welcome, ' . $row . ' ' . $row['lastname'] . '!</span>';
            //   }
            ?>

            <!-- <div class="biographical-section">
                <div class="row">

                  <div class="col-sm-12">
                    <div class="col-sm-3">
                      <div class="form-group demo-form">
                        <label class="control-label">Talent Manager Name:</label>
                      </div>
                    </div>

                    <div class="col-sm-6">
                      <div class="form-group">
                        <input type="text" placeholder="" value="<?= $row['firstname'] ?>" readonly="readonly">
                      </div>
                    </div>
                  </div>

                </div>
              </div> -->

            <?php //} 
            ?>


            <!-- <p><input class="btn btn-success" type="submit" value="submit"></p> -->
            <!-- <p><input class="btn btn-success next submit" type="button" value="submit"></p> -->

            <div class="col-sm-12">
              <a class="btn btn-theme btn-flat color-white previous" id="">Previous</a>
              <input type="button" class="btn btn-lg btn-theme btn-flat color-white next submit" value="submit">
            </div>

          </fieldset>

        </form>

      </div>
    </div>
  </div>
  
  <?php include('footer.php'); ?>
  <?php include('footer_js.php'); ?>

  <script>
    // var currentTab = 0; // Current tab is set to be the first tab (0)
    // showTab(currentTab); // Display the current tab
    // function showTab(n) {
    //   // This function will display the specified tab of the form...
    //   var x = document.getElementsByClassName("tab");
    //   x[n].style.display = "block";
    //   //... and fix the Previous/Next buttons:

    //   if (n == 0) {
    //     document.getElementById("prevBtn").style.display = "none";
    //   } else {
    //     document.getElementById("prevBtn").style.display = "inline";
    //   }

    //   if (n == (x.length - 1)) {
    //     document.getElementById("nextBtn").innerHTML = "Submit";
    //   } else {
    //     document.getElementById("nextBtn").innerHTML = "Next";
    //   }
    //   //... and run a function that will display the correct step indicator:
    //   fixStepIndicator(n)
    // }

    // function nextPrev(n) {
    //   // This function will figure out which tab to display
    //   var x = document.getElementsByClassName("tab");
    //   // Exit the function if any field in the current tab is invalid:
    //   if (n == 1 && !validateForm()) return false;
    //   // Hide the current tab:
    //   x[currentTab].style.display = "none";
    //   // Increase or decrease the current tab by 1:
    //   currentTab = currentTab + n;
    //   // if you have reached the end of the form...
    //   if (currentTab >= x.length) {
    //     // ... the form gets submitted:
    //     document.getElementById("regForm").submit();
    //     return false;
    //   }
    //   // Otherwise, display the correct tab:
    //   showTab(currentTab);
    // }

    // function validateForm() {
    //   // This function deals with validation of the form fields
    //   var x, y, i, valid = true;
    //   x = document.getElementsByClassName("tab");
    //   y = x[currentTab].getElementsByTagName("input");
    //   // A loop that checks every input field in the current tab:

    //   for (i = 0; i < y.length; i++) {
    //     // If a field is empty...
    //     if (y[i].value == "") {
    //       // add an "invalid" class to the field:
    //       y[i].className += " invalid";
    //       // and set the current valid status to false
    //       valid = false;
    //     }
    //   }

    //   // If the valid status is true, mark the step as finished and valid:
    //   if (valid) {
    //     document.getElementsByClassName("step")[currentTab].className += " finish";
    //   }
    //   return valid; // return the valid status
    // }

    // function fixStepIndicator(n) {
    //   // This function removes the "active" class of all steps...
    //   var i, x = document.getElementsByClassName("step");
    //   for (i = 0; i < x.length; i++) {
    //     x[i].className = x[i].className.replace(" active", "");
    //   }
    //   //... and adds the "active" class on the current step:
    //   x[n].className += " active";

    // }
  </script>

  <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/jquery.validate.js"></script>
  <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.js"></script>

  <script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
  <script type="text/javascript" src="dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
  <script type="text/javascript" src="dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
  <script type="text/javascript" src="dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {

      $('#birthdate').datepicker({
    	  changeMonth: true,
        changeYear: true,
        maxDate: 0,
        dateFormat: 'yy-mm-dd',
      });

      // Custom method to validate username
      // $.validator.addMethod("usernameRegex", function(value, element) {
      // 	return this.optional(element) || /^[a-zA-Z0-9]*$/i.test(value);
      // }, "Username must contain only letters, numbers");

      $(".submit").click(function() {
        console.log('dddd');
        $("#myform").submit();
      });

      $(".next").click(function() {
        var form = $("#myform");
        form.validate({
          errorElement: 'span',
          errorClass: 'help-block',
          highlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').addClass("has-error err");
          },
          unhighlight: function(element, errorClass, validClass) {
            $(element).closest('.form-group').removeClass("has-error err");
          },
          rules: {
            firstname: {
              required: true,
            },
            lastname: {
              required: true,
            },
            email: {
              required: true,
              email: true,
              remote: {
                url: "ajax/front_request.php",
                type: "post",
                data: {
                  name: 'user_email_unique_insert'
                }
              }
            },
            username: {
              required: true,
              remote: {
                url: "ajax/front_request.php",
                type: "post",
                data: {
                  name: 'user_username_unique_insert'
                }
              }
            },
            password: {
              required: true,
            },
            confirm_password: {
              required: true,
              equalTo: '#password',
            },
            phone: {
              required: true,
            },
            location: {
              required: true,
            },
            gender: {
              required: true
            },
            birthdate:{
              required: true
            },
            'ethnicities[]':{
              required: true
            },
            'shirt':{
              required: true
            },
            'shoe':{
              required: true
            },
            'pants':{
              required: true
            },
            'Waist':{
              required: true
            },
            'inseam':{
              required: true
            }
          },
          messages: {
            email: {
              remote: "Email already exist.",
            },
            username: {
              remote: "Username already exist.",
            }
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
        if (form.valid() === true) {

          current_fs = $('.part.active');
          current_id_str = $('.part.active').attr('id');
          current_num_str = current_id_str.split('_');
          current_num = parseInt(current_num_str[1]);
          // console.log(current_num);

          // if ($('#account_information').is(":visible")){
          // 	current_fs = $('#account_information');
          // 	next_fs = $('#company_information');
          // }else if($('#company_information').is(":visible")){
          // 	current_fs = $('#company_information');
          // 	next_fs = $('#personal_information');
          // }

          next_fs = $('#part_' + (current_num + 1));
          $('.part').removeClass('active');
          next_fs.addClass('active');
          next_fs.show();
          current_fs.hide();
        }
      });

      $('.previous').click(function() {
        // if($('#company_information').is(":visible")){
        // 	current_fs = $('#company_information');
        // 	next_fs = $('#account_information');
        // }else if ($('#personal_information').is(":visible")){
        // 	current_fs = $('#personal_information');
        // 	next_fs = $('#company_information');
        // }

        current_fs = $('.part.active');
        current_id_str = $('.part.active').attr('id');
        current_num_str = current_id_str.split('_');
        current_num = parseInt(current_num_str[1]);

        prev_fs = $('#part_' + (current_num - 1));
        $('.part').removeClass('active');
        prev_fs.addClass('active');
        prev_fs.show();
        current_fs.hide();
      });

    });
  </script>
  <script>
      if (window.history.replaceState) {
          window.history.replaceState(null, null, window.location.href);
      }
  </script>
</body>

</html>