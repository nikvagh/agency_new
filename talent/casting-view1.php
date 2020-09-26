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
  $folder_card = '../uploads/users/' . $user_id . '/portfolio/';
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

  //   while ($row = mysql_fetch_array($reminder_res, mysql_ASSOC)) {

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
      $submit_ins_sql = "insert INTO agency_mycastings
                        SET 
                        user_id = ".$user_id.",
                        role_id = ".$_POST['role_id'].",
                        message = '".$_POST['note']."'
                        ";
      if(mysql_query($submit_ins_sql)){
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
  <?php while ($row = mysql_fetch_array($casting_q, mysql_ASSOC)) { ?>
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
                              while ($job_row = mysql_fetch_array($job_type_q, mysql_ASSOC)) {
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
                              while ($unions_row = mysql_fetch_array($castings_unions_q, mysql_ASSOC)) {
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
                      <?php while ($role_row = mysql_fetch_array($casting_role_q, mysql_ASSOC)) { ?>
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
                                          while ($gender_row = mysql_fetch_array($gender_q, mysql_ASSOC)) {
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
                                          while ($ethnicity_row = mysql_fetch_array($ethnicity_q, mysql_ASSOC)) {
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
                                <button class="btn btn-theme submission-btn" data-id="<?php echo $role_row['role_id']; ?>">Submission</button>
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
    <div class="modal-dialog modal-lg">
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
              
              <!-- Modal Body -->
             <!--  <div class="modal-body">
              		<label>Required Assets</label>
	          		<div class="required_doc">

	          		</div>
	                <div class="form-group">
                     	<label>Photo </label>
                        <?php
                          $sql_portfolio = "SELECT * FROM agency_photos WHERE user_id=".$user_id." AND headshot_thumb ='N' ";
                          $result_portfolio = mysql_query($sql_portfolio);
                        ?>
                        <?php if(mysql_num_rows($result_portfolio) > 0){ ?>
                          <div class="row text-center">

                            <?php while ($row = sql_fetchrow($result_portfolio)) { ?>
                              <?php if(file_exists($folder_portfolio_thumb. '128x128_' . $row['filename'])){ ?>

                                      <div class="col-md-3 margin-btm-15">
                                        <div class="card-no-padding">

                                        <a href="<?php echo $folder_portfolio . $row['filename']; ?>" class="block" style="height:128px">
                                          <img src="<?php echo $folder_portfolio_thumb. '128x128_' . $row['filename']; ?>">
                                        </a>
                                        <input type="checkbox" name="portfolio_del[<?php echo $row['image_id']; ?>]">

                                      </div>
                                    </div>

                              <?php } ?>
                            <?php } ?>

                          </div>
                        <?php }else{ ?>
                            <br/>
                            <label class="text-center">You have't Any Photo Upload</label>
                        <?php } ?>


                        <div class="form-group">
                          <label class="file-box">
                            <span class="name-box">Drag and Drop Files</span>
                            <input type="file" name="portfolio[]" class="form-control" multiple="" />
                          </label>
                        </div>

                        <label>Reel</label>
                        <?php 
                        	$query_reel = "SELECT * FROM agency_reel WHERE user_id='$profileid'";
                          	$result_reel = mysql_query ($query_reel);
                          	$num_reels = mysql_num_rows($result_reel);
                        ?>

                        <?php if($num_reels > 0) { ?>
	                        <div class="box-body">
	                            <label>Uploaded Videos</label>
	                            <br/>
	                            <?php while ($row = mysql_fetch_array ($result_reel, mysql_ASSOC)) { ?>

	                              <?php if($row['reel_host'] == 'youtube') { ?>
	                                <a href="<?php echo 'http://www.youtube-nocookie.com/embed/' . $row['reel_link_id']; ?>" target="_blank">view </a>
	                              <?php } else if($row['reel_host'] == 'vimeo') { ?>
	                                <a href="<?php echo 'http://player.vimeo.com/video/' . $row['reel_link_id']; ?>" target="_blank">view </a>
	                              <?php } ?>

	                              &nbsp;&nbsp;&nbsp;&nbsp;
	                              <label><input type="checkbox" name="video_del[<?php echo $row['reel_id']; ?>]"> check for submit</label>
	                              <br/>
	                            <?php } ?>
	                        </div>
                        <?php } ?>

                        <?php if($num_reels < 3) { ?>
                            <div class="box-body">
                              <div class="form-group">
                                  <label>Embed New Video</label>
                                  <br/>
                                  <label class="text-alert">
                                    <i class="fa fa-bell"></i> Please upload your video to either <a href="http://www.youtube.com" target="_blank">YouTube</a> or <a href="http://www.vimeo.com" target="_blank">Vimeo</a>.
                                    <br/>
                                    Once you have your video uploaded, please copy the URL (Link) to your video and paste (or type) it in the box below.
                                  </label>
                                  <input type="text" name="videourl" class="form-control"/>
                              </div>
                            </div>
                        <?php } ?>

                        <label>Self Tapes</label>
                        <?php 
                          $query_vo = "SELECT * FROM agency_vo WHERE user_id='$profileid'";
                          $result_vo = mysql_query ($query_vo);
                          $num_vos = mysql_num_rows($result_vo);
                        ?>
                        <?php if($num_vos > 0) { ?>
                          <div class="box-body">
                            <label>Uploded Audio</label>
                            <br/>
                            <?php while ($row = mysql_fetch_array ($result_vo, mysql_ASSOC)) { ?>
                              <div class="row">
                                <?php $vofile = $folder_audio . $row['vo_file']; ?>
                                <?php if(file_exists($vofile)) { ?>
                                  
                                  <div class="col-md-7">
                                    <audio controls>
                                      <source src="<?php echo $vofile; ?>" type="audio/mpeg">
                                    </audio>
                                  </div>
                                  <div class="col-md-5">
                                    <?php echo $row['vo_name']; ?> 
                                    <br/>
                                    <label>
                                      <input type="checkbox" name="audio_del[<?php echo $row['vo_id']; ?>]"> check for submit
                                    </label>
                                  </div>

                                <?php } ?>
                              </div>
                            <?php } ?>
                          </div>
                        <?php } ?>

                        <?php if($num_vos < 3) { ?>
                          <div class="box-body">
                              
                            <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                            <label>Upload New Voice Over</label>
                            <br/>
                            <label class="text-alert"> <i class="fa fa-bell"></i> Please upload an <u>MP3</u> file of your Voice Over audio.</label>
                            
                            <div class="form-group">
                              <label class=""> Title</label>
                              <input type="text" name="mp3name" class="form-control" value="<?php if(isset($_POST['mp3name'])){ echo $_POST['mp3name']; } ?>"/>
                            </div>

                            <div class="form-group">
                              <label class="file-box">
                                        <span class="name-box">Drag and Drop Files</span>
                                <input type="file" name="mp3file" class="form-control" />
                              </label>
                              <label class="text-alert"><i class="fa fa-bell"></i> Select an MP3 file from your computer (max size: 10MB) </label>
                            </div>

                          </div>
                        <?php } else { ?>
                          <div class="box-body">
                                <label class="text-danger">You may have a maximum of 3 Voice Overs on your page.  If you would like to add a new one, please delete one of your existing Voice Overs.</label>
                            </div>
                        <?php } ?>


                        <label>Resume</label>
                        <div class="form-group">
                          <label class="file-box">
                            <span class="name-box">Drag and Drop Files</span>
                            <input type="file" name="portfolio[]" class="form-control" multiple="" />
                          </label>
                        </div>

                        <label>Note</label>
                        <div class="form-group">
                          <textarea name="note" id="note" class="form-control"></textarea>
                        </div>
	                </div>
	                <input type="hidden" name="role_id" id="role_id" value="" />
              </div> -->

              <div class="modal-body">
              </div>
              
              <!-- Modal Footer -->
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <input type="submit" class="btn btn-theme submitBtn" name="submission_Save" value="Send" />
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

  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }


  $("#submissison_Form").on('submit',function(){
    console.log('yyy');
    // return false;
  })

  $(".submission-btn").on("click", function(e){
      e.preventDefault();

     var role_id = $(this).attr('data-id');
     var user_id = "<?php echo $_SESSION['user_id'] ?>";
     // AJAX request
     $.ajax({
        url: '../ajax/dashboard_request.php',
        type: 'post',
        data: {name:'get_role_byId',role_id: role_id,user_id: user_id},
        // dataType: 'json',
        success: function(response){ 

          // console.log(response);
          // console.log(response.role.required_materials);

          // req_assets = response.role.required_materials;
          // req_asset_ary = [];
          // if(req_assets != "" && req_assets != null){
          // 	req_asset_ary = req_assets.split(',');
          // }

          // html = '<option value=""></option>';
          // $.each(response, function(index, value){
          //   html += '<option value="'+value.user_id+'">'+value.firstname+' '+value.lastname+'</option>';
          // });
      //     	if ($.inArray('Photos', req_asset_ary) >= 0) {
      //     		req_class = "fa-check text-success";
		    // }else{
		    // 	req_class = "fa-times text-danger";
		    // }
          
      //     req_doc = "";
      //     req_doc += '<i class="fa '+req_class+'"></i> Photos';
      //     req_doc += '<i class="fa fa-check text-success"></i> Reels';
      //     req_doc += '<i class="fa fa-check text-success"></i> Self Taps';
      //     req_doc += '<i class="fa fa-check text-success"></i> Resume';
      //     req_doc += '<i class="fa fa-check text-success"></i> Note';
      //     $(".required_doc").html(req_doc);

          // $('#req_talent').html(html);
          // $('#role_id').val(role_id);
          // Add response in Modal body
          $('.modal-body').html(response);

          // Display Modal
          $('#submission_Modal').modal('show'); 
        }
      });
  });

</script>
<?php include('footer.php'); ?>