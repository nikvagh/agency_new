<?php 
  $page = "casting_call_view";
  $page_selected = "casting_calls";
  include('header.php');
  include('../forms/definitions.php');
  include('../includes/agency_dash_functions.php');

  // $notification = array();
  // if(isset($_POST['talent_casting_id']) && $_POST['talent_casting_id'] != ""){

  //   // echo "<pre>";
  //   // print_r($_POST);
  //   // echo "</pre>";

  //   $reminder_res = mysql_query("select * from agency_talent_casting atc
  //                     LEFT JOIN forum_users u ON u.user_id = atc.user_id 
  //                     LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
  //                     LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id
  //                     WHERE atc.talent_casting_id = ".$_POST['talent_casting_id']."
  //                   ");

  //   while ($row = mysql_fetch_array($reminder_res, MYSQL_ASSOC)) {

  //     $reminder_email = $row['user_email'];
  //     $subject = 'Booking Reminder';

  //     $msg = '<p>we are inform you that your booking information as following:</p>';
  //     $msg .= 'Date : '.$row['casting_date'].'<br/>';
  //     $msg .= 'Location : '.$row['location_casting'].'<br/>';
  //     $msg .= 'Description : '.$row['description'].'<br/>';
  //     $msg .= 'Pay Rate : '.$row['rate_day'].'<br/>';
  //     $msg .= 'Usage Rate : '.$row['rate_usage'].'<br/>';
  //     $msg .= 'Usage Term : '.$row['usage_time'].'<br/>';
  //     $msg .= 'Usage Area : '.$row['usage_location'].'<br/>';

  //     if(send_mail($reminder_email,$subject,$msg)){
  //       $notification['success'] = "Reminder sent successfully.";
  //     }else{
  //       $notification['error'] = "Reminder sending failed!";
  //     }

  //   }
  //   $_POST = array();
    
  //   // exit;
  // }
  // echo "<br/><br/><br/>";
  $time = time();

  if(isset($_POST['submit']) && $_POST['submit'] == "Delete Casting"){

    $ssql1 = "SELECT lightbox_id FROM agency_lightbox WHERE casting_id=".$_POST['casting_id']."";
    $squery1 = mysql_query($ssql1);
    if (mysql_num_rows($squery1) > 0) {
      while ($row = mysql_fetch_assoc($squery1)) {
        $dsql_lite1 = "DELETE FROM agency_lightbox_users WHERE lightbox_id=".$row['lightbox_id']."";
        mysql_query($dsql_lite1);

        $dsql_lite2 = "DELETE FROM agency_lightbox WHERE lightbox_id=".$row['lightbox_id']."";
        mysql_query($dsql_lite2);
      }
    }
    // exit;

    $dsql1 = "DELETE FROM agency_castings_roles_vars WHERE casting_id=".$_POST['casting_id']."";
    mysql_query($dsql1);
    $dsql2 = "DELETE FROM agency_castings_roles WHERE casting_id=".$_POST['casting_id']."";
    mysql_query($dsql2);
    $dsql3 = "DELETE FROM agency_castings_unions WHERE casting_id=".$_POST['casting_id']."";
    mysql_query($dsql3);
    $dsql4 = "DELETE FROM agency_castings_jobtype WHERE casting_id=".$_POST['casting_id']."";
    mysql_query($dsql4);
    $dsql5 = "DELETE FROM agency_castings WHERE casting_id=".$_POST['casting_id']."";
    if(mysql_query($dsql5)){
      $_SESSION['flashdata'] = "Casting Deleted Successfully";
      header("Location: casting-list.php");
    }
  }

  if(isset($_POST['user_to_lightbox_submit']) && $_POST['user_to_lightbox_submit'] == "Auto Find"){

    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    // exit;

    // $query_auto_find = "SELECT * FROM agency_lightbox WHERE casting_id=".$_POST['casting_id']." AND lightbox_type = 'auto_find' ";
    // $result_auto_find = mysql_query($query_auto_find);

    // if (mysql_num_rows($result_auto_find) > 0) {
    //   if ($row = mysql_fetch_assoc($result_auto_find)) {
    //     header("Location: ../lightbox.php?lightbox_id=".$row['lightbox_id']);
    //   }
    
    if(isset($_POST['lightbox_id']) && $_POST['lightbox_id'] != ""){

      header("Location: ../lightbox.php?lightbox_id=".$_POST['lightbox_id']);

    }else{

      $casting_id = $_POST['casting_id']; 
      $age_lower_matched = array();
      $age_upper_matched = array();
      $height_lower_matched = array();
      $height_upper_matched = array();
      $gender_matched = array();

      $casting_q = mysql_query("select ac.*,ap.firstname,ap.lastname from agency_castings ac
                              LEFT JOIN agency_profiles as ap ON ap.user_id = ac.casting_director
                              WHERE ac.casting_id =".$casting_id." GROUP BY casting_id
                            ");
      if (mysql_num_rows($casting_q) > 0) {
        while ($row = mysql_fetch_assoc($casting_q)) {

            //insert lightnox
            $lightbox_ins = "INSERT INTO agency_lightbox 
                              SET 
                              client_id = ".$_POST['client_id'].",
                              lightbox_name = '".$_POST['lightbox_name']."',
                              lightbox_description = 'auto-find results',
                              casting_id = '".$casting_id."',
                              lightbox_type = 'auto_find',
                              timecode = '".$time."'
                            ";
            mysql_query($lightbox_ins);
            $lightbox_id = mysql_insert_id();
            // ====================

            $casting_role_q = mysql_query("select * from agency_castings_roles
                                    WHERE casting_id =".$casting_id."
                                  ");
          if (mysql_num_rows($casting_role_q) > 0) {
            while ($role_row = mysql_fetch_assoc($casting_role_q)) {

              // $age_lower_matched[] = $role_row['age_lower'];
              // $age_upper_matched[] = $role_row['age_upper'];
              // $height_lower_matched[] = $role_row['height_lower'];
              // $height_upper_matched[] = $role_row['height_upper'];

              $gender_q = mysql_query("select * from agency_castings_roles_vars
                                        WHERE casting_id =".$casting_id." AND role_id = ".$role_row['role_id']." AND var_type = 'gender'
                                      ");
              if (mysql_num_rows($gender_q) > 0) {
                while ($gender_row = mysql_fetch_assoc($gender_q)) {
                  $gender_matched[] = $gender_row['var_value'];

                }
              }

              $match['gender'] = array_unique($gender_matched);

              $gender_cond = array();
              if(in_array("M",$match['gender'])){
                $gender_cond[] = "gender = 'M'";
              }
              if(in_array("F",$match['gender'])){
                $gender_cond[] = "gender = 'F'";
              }
              if(in_array("Transgender",$match['gender'])){
                $gender_cond[] = "gender = 'Transgender'";
              }
              if(in_array("Other",$match['gender'])){
                $gender_cond[] = "gender = 'Other'";
              }

              if(!empty($gender_cond)){
                $gender_str = implode(' OR ',$gender_cond);
                $cond .= ' AND ('.$gender_str.')';
              }

              $matched_q = mysql_query("select *,YEAR(CURDATE()) - YEAR(birthdate) as age from agency_profiles  AS age
                                      WHERE 1 AND height >= '".$role_row['height_lower']."' AND height <= '".$role_row['height_upper']."' ". 
                                              $cond. " 
                                              AND (YEAR(CURDATE()) - YEAR(birthdate)) >= '".$role_row['age_lower']."'
                                              AND (YEAR(CURDATE()) - YEAR(birthdate)) <= '".$role_row['age_upper']."'
                                    ");
              if (mysql_num_rows($matched_q) > 0) {
                while ($matched_row = mysql_fetch_assoc($matched_q)) {
                    // echo "<br/>";
                    // echo "user_id = ".$matched_row['user_id']." role_id = ".$role_row['role_id'];

                    //insert lightnox
                      $lightbox_user_ins = "INSERT INTO agency_lightbox_users 
                                SET 
                                lightbox_id = ".$lightbox_id.",
                                user_id = ".$matched_row['user_id'].",
                                role_id = ".$role_row['role_id']."
                              ";
                    mysql_query($lightbox_user_ins);
                    // $lightbox_id = mysql_insert_id();
                    // ====================

                }
              }

            }
          }

          header("Location: ../lightbox.php?lightbox_id=".$lightbox_id);

        }
      }

      // $sql_ins = "INSERT INTO agency_lightbox
      //   name = "";
      // ";
      // echo "111";
      // header("Location: ../lightbox.php?casting_id=");
    }
  }

  if(isset($_POST['user_to_lightbox_submit']) && $_POST['user_to_lightbox_submit'] == "Auto Submit"){

    // $query_auto_submit = "SELECT * FROM agency_lightbox WHERE casting_id=".$_POST['casting_id']." AND lightbox_type = 'auto_submit' ";
    // $result_auto_submit = mysql_query($query_auto_submit);

    // if (mysql_num_rows($result_auto_submit) > 0) {
    //   if ($row = mysql_fetch_assoc($result_auto_submit)) {
    //     header("Location: ../lightbox.php?lightbox_id=".$row['lightbox_id']);
    //   }
    // }else{

    if(isset($_POST['lightbox_id']) && $_POST['lightbox_id'] != ""){
      // echo "111";
      header("Location: ../lightbox.php?lightbox_id=".$_POST['lightbox_id']);

    }else{
      // echo "222";
      $casting_id = $_POST['casting_id'];
      $age_lower_matched = array();
      $age_upper_matched = array();
      $height_lower_matched = array();
      $height_upper_matched = array();
      $gender_matched = array();

      $casting_q = mysql_query("select ac.*,ap.firstname,ap.lastname from agency_castings ac
                              LEFT JOIN agency_profiles as ap ON ap.user_id = ac.casting_director
                              WHERE ac.casting_id =".$casting_id." GROUP BY casting_id
                            ");
      if (mysql_num_rows($casting_q) > 0) {
        while ($row = mysql_fetch_assoc($casting_q)) {

            //insert lightnox
            $lightbox_ins = "INSERT INTO agency_lightbox 
                              SET 
                              client_id = ".$_POST['client_id'].",
                              lightbox_name = '".$_POST['lightbox_name']."',
                              lightbox_description = 'auto-submit results',
                              casting_id = '".$casting_id."',
                              lightbox_type = 'auto_submit',
                              timecode = '".$time."'
                            ";
            mysql_query($lightbox_ins);
            $lightbox_id = mysql_insert_id();
            // ====================

            $casting_role_q = mysql_query("select * from agency_castings_roles
                                    WHERE casting_id =".$casting_id."
                                  ");
          if (mysql_num_rows($casting_role_q) > 0) {
            while ($role_row = mysql_fetch_assoc($casting_role_q)) {

              // $gender_q = mysql_query("select * from agency_castings_roles_vars
              //                           WHERE casting_id =".$casting_id." AND role_id = ".$role_row['role_id']." AND var_type = 'gender'
              //                         ");
              // if (mysql_num_rows($gender_q) > 0) {
              //   while ($gender_row = mysql_fetch_assoc($gender_q)) {
              //     $gender_matched[] = $gender_row['var_value'];

              //   }
              // }

              // $match['gender'] = array_unique($gender_matched);

              // $gender_cond = array();
              // if(in_array("M",$match['gender'])){
              //   $gender_cond[] = "gender = 'M'";
              // }
              // if(in_array("F",$match['gender'])){
              //   $gender_cond[] = "gender = 'F'";
              // }
              // if(in_array("Transgender",$match['gender'])){
              //   $gender_cond[] = "gender = 'Transgender'";
              // }
              // if(in_array("Other",$match['gender'])){
              //   $gender_cond[] = "gender = 'Other'";
              // }

              // if(!empty($gender_cond)){
              //   $gender_str = implode(' OR ',$gender_cond);
              //   $cond .= ' AND ('.$gender_str.')';
              // }

              // $matched_q = mysql_query("select *,YEAR(CURDATE()) - YEAR(birthdate) as age from agency_profiles  AS age
              //                         WHERE 1 AND height >= '".$role_row['height_lower']."' AND height <= '".$role_row['height_upper']."' ". 
              //                                 $cond. " 
              //                                 AND (YEAR(CURDATE()) - YEAR(birthdate)) >= '".$role_row['age_lower']."'
              //                                 AND (YEAR(CURDATE()) - YEAR(birthdate)) <= '".$role_row['age_upper']."'
              //                       ");

              $matched_q = mysql_query("select * from agency_mycastings am
                                        WHERE 1 AND role_id = '".$role_row['role_id']."'
                                      ");
              if (mysql_num_rows($matched_q) > 0) {
                while ($matched_row = mysql_fetch_assoc($matched_q)) {
                    // echo "<br/>";
                    // echo "user_id = ".$matched_row['user_id']." role_id = ".$role_row['role_id'];

                    //insert lightnox
                      $lightbox_user_ins = "INSERT INTO agency_lightbox_users 
                                SET 
                                lightbox_id = ".$lightbox_id.",
                                user_id = ".$matched_row['user_id'].",
                                role_id = ".$role_row['role_id']."
                              ";
                    mysql_query($lightbox_user_ins);
                    // $lightbox_id = mysql_insert_id();
                    // ====================

                }
              }

            }
          }

          header("Location: ../lightbox.php?lightbox_id=".$lightbox_id);

        }
      }

    }
  }

?>

<?php
  $age_lower_matched = array();
  $age_upper_matched = array();
  $height_lower_matched = array();
  $height_upper_matched = array();
  $gender_matched = array();

  if($_GET['casting_id']){
      $casting_id = $_GET['casting_id'];
      $casting_q = mysql_query("select ac.*,ap.firstname,ap.lastname from agency_castings ac
                              LEFT JOIN agency_profiles as ap ON ap.user_id = ac.casting_director
                              WHERE ac.casting_id =".$casting_id." GROUP BY casting_id
                            ");
  }
?>

<?php if (mysql_num_rows($casting_q) > 0) { ?>
  <?php while ($row = mysql_fetch_assoc($casting_q)) { ?>
      <?php
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";
      ?>

      <div id="page-wrapper">
          <div class="" id="main">  
            <h3>Casting Calls </h3>
            <div class="row">
              <div class="col-md-12">
                <form action="" method="post">
                  <input type="hidden" name="casting_id" id="casting_id" value="<?php echo $casting_id; ?>">
                  <input type="hidden" name="casting_name" id="casting_name" value="<?php echo $row['job_title']; ?>">
                  <input type="hidden" name="client_id" id="client_id" value="<?php echo $row['casting_director']; ?>">
                  <a href="casting-update.php?casting_id=<?php echo $casting_id; ?>" class="btn btn-flat btn-theme">Edit</a>
                  <input type="submit" name="submit" class="btn btn-flat btn-theme delete_casting" value="Delete Casting">
                  <input type="button" name="submit" class="btn btn-flat btn-theme auto_find_btn" value="Auto Find">
                  <input type="submit" name="submit" class="btn btn-flat btn-theme auto_submit_btn" value="Auto Submit">
                </form>
              </div>
            </div>
            <br/>

            <div class="row">
              <div class="col-md-4">
                <div class="box box-theme">
                  <div class="box-header with-border">
                      <h3 class="box-title">Casting Information</h3>
                  </div>

                  <div class="box-body">
                    <div class="col-sm-6">
                      <strong>Project</strong>
                      <p class="text-muted"><?php echo $row['job_title']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Casting Location</strong>
                      <p class="text-muted"><?php echo $row['location_casting']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Shoot Location:</strong>
                      <p class="text-muted"><?php echo $row['location_shoot']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Casting Date: </strong>
                      <p class="text-muted"><?php echo $row['casting_date']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Shoot Date/Range: </strong>
                      <p class="text-muted"><?php echo $row['shoot_date_start'].' - '.$row['shoot_date_start']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Casting Director: </strong>
                      <p class="text-muted"><?php echo $row['firstname'].' '.$row['lastname']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Company/Link</strong>
                      <p class="text-muted"><?php echo $row['company']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Client/Artist</strong>
                      <p class="text-muted"><?php echo $row['artist']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Job Type</strong>
                      <p>
                        <?php 
                           $job_type_q = mysql_query("select * from agency_castings_jobtype
                                                      WHERE casting_id =".$casting_id."
                                                    ");
                            if (mysql_num_rows($job_type_q) > 0) {
                              while ($job_row = mysql_fetch_array($job_type_q, MYSQL_ASSOC)) {
                                echo '<span class="label label-primary">'.$job_row['jobtype'].'</span> ';
                              }
                            }
                        ?>
                      </p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Union Status</strong>
                      <p>
                        <?php 
                           $castings_unions_q = mysql_query("select * from agency_castings_unions
                                                      WHERE casting_id =".$casting_id."
                                                    ");
                            if (mysql_num_rows($castings_unions_q) > 0) {
                              while ($unions_row = mysql_fetch_array($castings_unions_q, MYSQL_ASSOC)) {
                                echo '<span class="label label-primary">'.$unions_row['union_name'].'</span> ';
                              }
                            }
                        ?>
                      </p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Day Rate</strong>
                      <p class="text-muted"><?php echo $row['rate_day']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Usage Rate</strong>
                      <p class="text-muted"><?php echo $row['rate_usage']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Usage - Type(s)</strong>
                      <p class="text-muted"><?php echo $row['usage_type']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Usage - Term</strong>
                      <p class="text-muted"><?php echo $row['usage_time']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Usage - Area</strong>
                      <p class="text-muted"><?php echo $row['usage_location']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Notes</strong>
                      <p class="text-muted"><?php echo $row['notes']; ?></p>
                      <hr>
                    </div>

                    <div class="col-sm-6">
                      <strong>Tags</strong>
                      <p class="text-muted">
                        <?php 
                          if($row['tags'] != ""){ 
                          $tags = explode(',', $row['tags']);
                          foreach($tags as $tag){
                        ?>
                            <span class="label label-primary"><?php echo $tag; ?></span>
                          <?php } ?>
                        <?php } ?>
                      </p>
                      <hr>
                    </div>

                    <!-- <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>
                    <p>
                      <span class="label label-danger">UI Design</span>
                      <span class="label label-success">Coding</span>
                      <span class="label label-info">Javascript</span>
                      <span class="label label-warning">PHP</span>
                      <span class="label label-primary">Node.js</span>
                    </p>
                    <hr> -->
                  </div>

                </div>
              </div>

              <div class="col-md-4">
                <div class="box box-theme">
                  <div class="box-header with-border">
                      <h3 class="box-title">Role Descriptions</h3>
                  </div>
                </div>

                  <?php 
                     $casting_role_q = mysql_query("select * from agency_castings_roles
                                                WHERE casting_id =".$casting_id."
                                              ");
                      if (mysql_num_rows($casting_role_q) > 0) {
                        while ($role_row = mysql_fetch_assoc($casting_role_q)) {

                          $age_lower_matched[] = $role_row['age_lower'];
                          $age_upper_matched[] = $role_row['age_upper'];
                          $height_lower_matched[] = $role_row['height_lower'];
                          $height_upper_matched[] = $role_row['height_upper'];
                  ?>
                  <form method="post" class="role_form">

                    <div class="box">
                      <div class="box-body">

                        <div class="col-sm-6">
                          <strong>Character Name</strong>
                          <p class="text-muted"><?php echo $role_row['name']; ?></p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Age Range</strong>
                          <p class="text-muted"><?php echo $role_row['age_lower'].' - '.$role_row['age_upper']; ?></p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Gender</strong>
                          <p>
                            <?php 
                               $gender_q = mysql_query("select * from agency_castings_roles_vars
                                                          WHERE casting_id =".$casting_id." AND role_id = ".$role_row['role_id']." AND var_type = 'gender'
                                                        ");
                                if (mysql_num_rows($gender_q) > 0) {
                                  while ($gender_row = mysql_fetch_array($gender_q, MYSQL_ASSOC)) {
                                    $gender_matched[] = $gender_row['var_value'];
                                    echo '<span class="label label-primary">'.$gender_row['var_value'].'</span> ';
                                  }
                                }
                            ?>
                          </p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Ethnicity </strong>
                          <p>
                            <?php 
                               $ethnicity_q = mysql_query("select * from agency_castings_roles_vars
                                                          WHERE casting_id =".$casting_id." AND role_id = ".$role_row['role_id']." AND var_type = 'ethnicity'
                                                        ");
                                if (mysql_num_rows($ethnicity_q) > 0) {
                                  while ($ethnicity_row = mysql_fetch_array($ethnicity_q, MYSQL_ASSOC)) {
                                    echo '<span class="label label-primary">'.$ethnicity_row['var_value'].'</span> ';
                                  }
                                }
                            ?>
                          </p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Hight(Inch) </strong>
                          <p class="text-muted"><?php echo $role_row['height_lower'].' - '.$role_row['height_upper']; ?></p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Requirement </strong>
                          <p class="text-muted"><?php echo $role_row['requirement']; ?></p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Description</strong>
                          <p class="text-muted"><?php echo $role_row['description']; ?></p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Language</strong>
                          <p class="text-muted"><?php echo $role_row['language']; ?></p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Accent</strong>
                          <p class="text-muted"><?php echo $role_row['accent']; ?></p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Special Skills</strong>
                          <p class="text-muted"><?php echo $role_row['special_skills']; ?></p>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Reference Photo</strong>
                            <div class="row">
                              <div class="col-sm-4">
                                <?php if($role_row['reference_photo'] != ""){ ?>
                                  <img src="<?php echo '../attachments/roles/' . $role_row['role_id'] . '/'.$role_row['reference_photo']; ?>" class="img-responsive"/>
                                <?php } ?>
                              </div>
                            </div>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Sides</strong>
                          <div class="row">
                            <div class="col-sm-4">
                              <?php if($role_row['sides'] != ""){ ?>
                                <img src="<?php echo '../attachments/roles/' . $role_row['role_id'] . '/'.$role_row['sides']; ?>" class="img-responsive"/>
                              <?php } ?>
                            </div>
                          </div>
                          <hr>
                        </div>

                        <div class="col-sm-6">
                          <strong>Required materials</strong>
                          <p class="text-muted">
                              <?php 
                                if($role_row['required_materials'] != ""){ 
                                $required_materials = explode(',', $role_row['required_materials']);
                                foreach($required_materials as $val){
                              ?>
                                  <span class="label label-primary"><?php echo $val; ?></span>
                                <?php } ?>
                            <?php } ?>
                          </p>
                        </div>

                      </div>

                      <div class="box-footer text-right">
                        <input type="hidden" name="role_id" value="<?php echo $role_row['role_id']; ?>">
												<!-- <input type="submit" name="autofind" class="btn btn-theme btn-flat btnSubmit" value="Auto Find"> -->
                      </div>

                    </div>

                  </form>
                  <?php
                        }
                      }
                  ?>
              </div>

              <div class="col-md-4">
                <div class="box box-theme">
                  <div class="box-header with-border">
                      <h3 class="box-title">Matched Talent</h3>
                  </div>

                  <?php
                    // echo "<pre>";
                    // print_r($gender_matched);
                    // echo "</pre>";

                    $match['min_age'] = min($age_lower_matched);
                    $match['max_age'] = max($age_upper_matched);
                    $match['min_height'] = min($height_lower_matched);
                    $match['max_height'] = max($height_upper_matched);
                    $match['gender'] = array_unique($gender_matched);

                    // echo "<pre>";
                    // print_r($match);
                    // echo "</pre>";

                    // $age_lower_matched = ;
                  ?>

                  <div class="box-body">
                    <table class="table table-striped datatable">
                      <thead>
                        <tr>
                          <td>Name</td>
                          <td>AGE</td>
                          <td>HEIGHT</td>
                          <!-- <td></td> -->
                        </tr>
                      </thead>

                        <?php
                            $cond = "";

                            $gender_cond = array();
                            if(in_array("M",$match['gender'])){
                              $gender_cond[] = "gender = 'M'";
                            }
                            if(in_array("F",$match['gender'])){
                              $gender_cond[] = "gender = 'F'";
                            }
                            if(in_array("Transgender",$match['gender'])){
                              $gender_cond[] = "gender = 'Transgender'";
                            }
                            if(in_array("Other",$match['gender'])){
                              $gender_cond[] = "gender = 'Other'";
                            }

                            if(!empty($gender_cond)){
                              $gender_str = implode(' OR ',$gender_cond);
                              $cond .= ' AND ('.$gender_str.')';
                            }
                            
                            // YEAR(CURDATE()) - YEAR(birthdate)

                            $matched_q = mysql_query("select *,YEAR(CURDATE()) - YEAR(birthdate) as age from agency_profiles  AS age
                                                      WHERE 1 AND height >= '".$match['min_height']."' AND height <= '".$match['max_height']."' ". 
                                                              $cond. " 
                                                              AND (YEAR(CURDATE()) - YEAR(birthdate)) >= '".$match['min_age']."'
                                                              AND (YEAR(CURDATE()) - YEAR(birthdate)) <= '".$match['max_age']."'
                                                    ");
                            if (mysql_num_rows($matched_q) > 0) {
                              while ($matched_row = mysql_fetch_array($matched_q, MYSQL_ASSOC)) {
                        ?>
                            <tr>
                              <td><a href="profile-view.php?user_id=<?php echo $matched_row['user_id']; ?>"><?php echo $matched_row['firstname'].' '.$matched_row['lastname']; ?></a></td>
                              <td><?php echo $matched_row['age']; ?></td>
                              <td><?php echo $matched_row['height']; ?></td>
                              <!-- <td><a><?php //echo $matched_row['firstname']; ?></a></td>
                              <td><a><?php //echo $matched_row['firstname']; ?></a></td> -->
                            </tr>
                        <?php
                              }
                            }
                        ?>

                    </table>
                  </div>

                </div>
              </div>
            </div>

          </div>
      </div>

  <?php } ?>
<?php } ?>

<div class="modal fade" id="autofind_Modal" role="dialog"></div>
<div class="modal fade" id="lightbox_form_Modal" role="dialog"></div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<script>
   $('.datatable').DataTable({
          // "order": [[ 0, "desc" ]],
          // 'columnDefs': [{
          //     'targets': [3], 
          //     'orderable': false, 
          // }]
    });

    $(".role_form").submit(function(e){
      e.preventDefault();
      form = $(this).serialize();
      // var role_id = $(this).attr('data-id');
      // console.log(buttonpressed);

        // AJAX request
        $.ajax({
            url: '../ajax/dashboard_request.php',
            type: 'post',
            data: form+'&name=autofind_admin_by_role',
            // dataType: 'json',
            success: function(response){
              // console.log(response);
              $('#autofind_Modal').html(response);

              // Display Modal
              $('#autofind_Modal').modal('show'); 
            }
        });
    });

    $(".delete_casting").click(function(){
      if(confirm('are you sure want to delete this casting ?')){

      }else{
        return false;
      }
    });

    // ====================

    $(".auto_find_btn").click(function(e){
      e.preventDefault();
      casting_id = $("#casting_id").val();
      client_id = $("#client_id").val();
      casting_name = $("#casting_name").val();

      $.ajax({
          url: '../ajax/dashboard_request.php',
          type: 'post',
          data: 'casting_id='+casting_id+'&casting_name='+casting_name+'&client_id='+client_id+'&name=check_autofind_lightbox&lightbox_type=auto_find',
          // dataType: 'json',
          success: function(response){
            // console.log(response);
            $('#lightbox_form_Modal').html(response);

            // Display Modal
            $('#lightbox_form_Modal').modal('show');
          }
      }); 

      return false;

    });


    $(document).on('submit', '.lightbox_form', function(e) {
			// e.preventDefault();

			lightbox_name = $('#lightbox_name').val();
			lightbox_id = $('#lightbox_id').val();

			if(lightbox_name == "" && lightbox_id ==""){
				e.preventDefault();
				$('#user-to-lightbox-err').html('Please add New lightbox or select any one.');
				$('#user-to-lightbox-err').css('display','block');
				return false;
			}else{
				// $('.lightbox_form').submit();
			}
		});

    // =========================
    
    $(".auto_submit_btn").click(function(e){
      e.preventDefault();
      casting_id = $("#casting_id").val();
      client_id = $("#client_id").val();
      casting_name = $("#casting_name").val();

      $.ajax({
          url: '../ajax/dashboard_request.php',
          type: 'post',
          data: 'casting_id='+casting_id+'&casting_name='+casting_name+'&client_id='+client_id+'&name=check_autosubmit_lightbox&lightbox_type=auto_submit',
          // dataType: 'json',
          success: function(response){
            // console.log(response);
            $('#lightbox_form_Modal').html(response);

            // Display Modal
            $('#lightbox_form_Modal').modal('show');
          }
      }); 

      return false;

    });


    // $(document).on('submit', '.lightbox_form', function(e) {
		// 	// e.preventDefault();

		// 	title = $('#title').val();
		// 	lightbox_id = $('#lightbox_id').val();

		// 	if(title == "" && lightbox_id ==""){
		// 		e.preventDefault();
		// 		$('#user-to-lightbox-err').html('Please add New lightbox or select any one.');
		// 		$('#user-to-lightbox-err').css('display','block');
		// 		return false;
		// 	}else{
		// 		// $('.lightbox_form').submit();
		// 	}
		// });

    // =========================

    if (window.history.replaceState) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php include('footer.php'); ?>