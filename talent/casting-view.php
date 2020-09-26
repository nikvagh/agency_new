<?php 
  $page = "casting_call_view";
  $page_selected = "casting_calls";
  include('header.php');
  include('../forms/definitions.php');
  include('../includes/agency_dash_functions.php');

  $user_id = $userid = $profileid = $_SESSION['user_id'];
  $user = $userInfo= get_talent_byId($_SESSION['user_id']);

  // delete profile pic
  $folder_profile_pic = '../uploads/users/' . $user_id . '/profile_pic/';
  $folder_profile_pic_thumb = $folder_profile_pic . 'thumb/';
  $folder_headshot = '../uploads/users/' . $user_id . '/headshot/';
  $folder_headshot_thumb = $folder_headshot . 'thumb/';
  $folder_card = '../uploads/users/' . $user_id . '/card/';
  $folder_card_thumb = $folder_card . 'thumb/';
  $folder_audio = '../uploads/users/' . $user_id . '/audio/';
  $folder_portfolio = '../uploads/users/' . $user_id . '/portfolio/';
  $folder_portfolio_thumb = $folder_portfolio . 'thumb/';

  $notification = array();
  if(isset($_POST['submission_Save'])){

    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

  //   $reminder_res = mysql_query("select * from agency_talent_casting atc
  //                     LEFT JOIN forum_users u ON u.user_id = atc.user_id 
  //                     LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
  //                     LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id
  //                     WHERE atc.talent_casting_id = ".$_POST['talent_casting_id']."
  //                   ");

  //   while ($row = mysql_fetch_assoc($reminder_res)) {

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

      

      $sql_ins = "INSERT into agency_mycastings
                  SET
                  user_id = ".$_SESSION['user_id'].",
                  role_id = ".$_POST['role_id']."
                ";

      if(mysql_query($sql_ins)){
        $notification['success'] = "Submit successfully.";
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

            <?php if(isset($notification['success'])){ ?>
              <div class="alert alert-success" role="alert">
                  <?php echo $notification['success']; ?>
              </div>
            <?php } ?>
            <?php if(isset($notification['error'])){ ?>
              <div class="alert alert-danger" role="alert">
                <?php echo $notification['error']; ?>
              </div>
            <?php } ?>

            <h3>Casting Calls </h3>

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
                              while ($job_row = mysql_fetch_assoc($job_type_q)) {
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
                              while ($unions_row = mysql_fetch_assoc($castings_unions_q)) {
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

              <div class="col-md-8">
                <div class="box box-theme">
                  <div class="box-header with-border">
                      <h3 class="box-title">Role Descriptions</h3>
                  </div>
                </div>

                  <div class="row">
                    <?php 
                       $casting_role_q = mysql_query("select * from agency_castings_roles
                                                  WHERE casting_id =".$casting_id."
                                                ");
                    ?>
                    <?php if (mysql_num_rows($casting_role_q) > 0) { ?>
                      <?php while ($role_row = mysql_fetch_assoc($casting_role_q)) { ?>
                          <?php 
                            $age_lower_matched[] = $role_row['age_lower'];
                            $age_upper_matched[] = $role_row['age_upper'];
                            $height_lower_matched[] = $role_row['height_lower'];
                            $height_upper_matched[] = $role_row['height_upper'];
                          ?>
                          <div class="col-md-6">
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
                                          while ($gender_row = mysql_fetch_assoc($gender_q)) {
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
                                          while ($ethnicity_row = mysql_fetch_assoc($ethnicity_q)) {
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
                              <!-- data-target="#submission_Modal" -->
                              <!-- data-toggle="modal"  -->
                                <?php 
                                  if(check_user_role_submit($_SESSION['user_id'],$role_row['role_id'])){
                                      echo "Already submitted";
                                  }else{ 
                                ?>
                                    <button class="btn btn-theme btn-sm btn-flat btn-submission-request-modal" data-id="<?php echo $role_row['role_id']; ?>">Submit My Profile</button>
                                <?php } ?>
                                  
                              </div>

                            </div>
                          </div>

                      <?php } ?>
                    <?php } ?>
                  </div>

            </div>

          </div>
      </div>

  <?php } ?>
<?php } ?>


<div class="modal fade" id="submission_Modal" role="dialog">
    <div class="modal-dialog">
      <form role="form" id="submissison_Form" method="post" action="">
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">Submisssion</h4>
              </div>

              <div class="Required Documnets">
              </div>
              
              <!-- Modal Body -->
              <div class="modal-body">
                  <div class="form-group">
                      <label>Photo</label>

                        <?php
                          $sql_portfolio = "SELECT * FROM agency_photos WHERE user_id=".$user_id."";
                          $result_portfolio = mysql_query($sql_portfolio);
                        ?>
                        <?php if(mysql_num_rows($result_portfolio) > 0){ ?>
                          <?php while ($row = sql_fetchrow($result_portfolio)) { ?>

                            <?php //if(file_exist($folder_portfolio_thumb. '/25x25_' . $row['filename'])){ ?>

                              <!-- <a href="<?php echo '../uploads/users/' .$user_id. '/portfolio/' . $row['filename']; ?>" target="_blank" data-fancybox="portfolio_thumb">view attachment</a>&nbsp;&nbsp;&nbsp;&nbsp;
                              <label><input type="checkbox" name="portfolio_del[<?php echo $row['image_id']; ?>]"> check to delete</label>
                              <br/> -->

                            <?php //} ?>

                          <?php } ?>
                        <?php } ?>

                        <div class="form-group">
                          <label class="file-box">
                            <span class="name-box">Drag and Drop Files</span>
                            <input type="file" name="portfolio[]" class="form-control" multiple="" />
                          </label>
                        </div>

                        <label>Reel</label>
                        <div class="form-group">
                          <label class="file-box">
                            <span class="name-box">Drag and Drop Files</span>
                            <input type="file" name="portfolio[]" class="form-control" multiple="" />
                          </label>
                        </div>

                        <label>Self Tapes</label>
                        <div class="form-group">
                          <label class="file-box">
                            <span class="name-box">Drag and Drop Files</span>
                            <input type="file" name="portfolio[]" class="form-control" multiple="" />
                          </label>
                        </div>

                        <label>Resume</label>
                        <div class="form-group">
                          <label class="file-box">
                            <span class="name-box">Drag and Drop Files</span>
                            <input type="file" name="portfolio[]" class="form-control" multiple="" />
                          </label>
                        </div>

                        <label>Note</label>
                        <div class="form-group">
                          <textarea name="submission_note" id="" cols="30" rows="6" class="form-control"></textarea>
                        </div>

                  </div>
              </div>
              
              <!-- Modal Footer -->
              <div class="modal-footer">
                  <input type="hidden" name="role_id" id="role_id" value=""/>
                  <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                  <input type="submit" class="btn btn-theme btn-flat submitBtn" name="submission_Save" value="Send" />
              </div>
          </div>
      </form>
    </div>
</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script>
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script>

<script src="../dashboard/assets/fileStyle/fileStyle.js"></script>
<script src="../dashboard/assets/fancybox/jquery.fancybox.min.js"></script>

<script>
   $('.datatable').DataTable({
          // "order": [[ 0, "desc" ]],
          // 'columnDefs': [{
          //     'targets': [3], 
          //     'orderable': false, 
          // }]
    });


    $(".btn-submission-request-modal").on("click", function(e){	
    	e.preventDefault();

	   var role_id = $(this).attr('data-id');
	   // AJAX request
	  //  $.ajax({
		//     url: '../ajax/dashboard_request.php',
		//     type: 'post',
		//     data: {name:'get_submission_byId',submission_id: submission_id},
		//     dataType: 'json',
		//     success: function(res){ 
          
		    	// console.log(res);
		    	// return false;

          // html = '';
          // $('#fittingModal .modal-body').html(html);

          $('#role_id').val(role_id);

          // Display Modal
          $('#submission_Modal').modal('show');

		  //   }
	  	// });
	});

  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>
<?php include('footer.php'); ?>