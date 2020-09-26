<?php
  // TESTING
  /* 
    if(is_admin()) {
      echo '<div id="debug" style="position:fixed; left:0; top:20px; overflow:auto; width:1200px; height:50px; color: white; background-color:black; padding:10px;">' . $_COOKIE['lightbox'] . '</div>';
    } */
  $page = "lightbox";
  $page_selected = "lightbox";
  include('header.php');
  include('../forms/definitions.php');
  include('../includes/agency_dash_functions.php');

  	// echo "<br/>";

	if(isset($_GET['lightbox'])){
	    $lightbox_id = $_GET['lightbox'];
	}

	$notification = array();
	if(isset($_POST['Remove']) && $_POST['Remove'] == "Remove Role"){
	  	// echo "<pre>";
	  	// print_r($_POST);
	  	// echo "</pre>";

	  	if(isset($_POST['check_users']) && !empty($_POST['check_users']) ){
	  		$dlt = 0;
	  		foreach($_POST['check_users'] as $usr){
	  			$remove_user_lightbox = "DELETE FROM agency_lightbox_users 
	  										WHERE lightbox_id = ".$lightbox_id."
	  										AND user_id = ".$usr."
	  									";
	  			if(mysql_query($remove_user_lightbox)){
	  				$dlt++;
	  			}
	  		}
	  		if($dlt > 0){
		  		$notification['success'] = "User Removed Successfully From Lightbox";
		  	}
	  	}
	}

	if(isset($_POST['remove_lightbox']) && $_POST['remove_lightbox'] == 'Remove Lightbox'){
		$remove_user_lightbox = "DELETE FROM agency_lightbox_users 
	  							WHERE lightbox_id = ".$lightbox_id."
	  							";
	  	if(mysql_query($remove_user_lightbox)){
			$remove_lightbox_sql = "DELETE FROM agency_lightbox 
	  							WHERE lightbox_id = ".$lightbox_id."
	  							";
			if(mysql_query($remove_lightbox_sql)){
				$url="casting-call.php";
				header('location:'.$url);
			}
		}
	}

	if(isset($_POST['send_mail']) && $_POST['send_mail'] == 'SEND'){
		// echo "<pre>";
	 //  	print_r($_POST);
	 //  	echo "</pre>";

		$to_email = $_POST['emailto'];
		$subject = "Agency:Lightbox";
		$msg = $_POST['email_content'];

	  	if(send_mail($to_email,$subject,$msg)){
	  		$notification['success'] = "Email Sended Successfully";
	  	}
	}

	if(isset($_GET['lightbox'])){
	    // $lightbox_id = $_GET['lightbox'];
	    $client_id = $_SESSION['user_id'];

	    $lightbox_sql = "select al.* from agency_lightbox al
	                    LEFT JOIN agency_castings ac ON ac.casting_id = al.casting_id 
	                    WHERE lightbox_id = ".$lightbox_id."";
	    $result_lightbox = mysql_query($lightbox_sql);
	    $lightbox = array();
	    while($row = sql_fetchrow($result_lightbox)) { 
	      $lightbox = $row;
	    }

	    $lightbox_users_sql = "select alu.*,al.*,ap.*,fu.*,group_concat(apu.union_name) as union_name from agency_lightbox_users alu
	                    LEFT JOIN agency_lightbox al ON al.lightbox_id = alu.lightbox_id
	                    LEFT JOIN agency_profiles ap ON alu.user_id = ap.user_id
	                    LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
	                    LEFT JOIN agency_profile_unions apu ON apu.user_id = ap.user_id
	                    WHERE alu.lightbox_id = ".$lightbox_id."
	                    GROUP BY ap.user_id";

	    $result_lightbox_users = mysql_query($lightbox_users_sql);
	    $lightbox_users = array();
	    while($row = sql_fetchrow($result_lightbox_users)) { 
	      $lightbox_users[] = $row;
	    }
	}
	// echo "<pre>";
 //  	print_r($lightbox_users);
 //  	echo "</pre>";

	if(isset($_POST['copy_lbox']) && $_POST['copy_lbox'] == "COPY"){
		// echo "<pre>";
	 //  	print_r($_POST);
	 //  	echo "</pre>";

	  	$time = time();
	  	$casting_id_ins = "";
	  	if(isset($_POST['keep_roles'])){
	  		if($lightbox['casting_id'] != ""){
			  	$casting_id_ins = "casting_id = ".$lightbox['casting_id'].",";
			}
		}

	  	$sql_lightbox_ins = "INSERT INTO agency_lightbox 
	  				SET 
	  				client_id = ".$_SESSION['user_id'].",
	  				lightbox_name = '".$_POST['copy_name']."',
	  				lightbox_description = '".$_POST['copy_description']."',
	  				".$casting_id_ins."
	  				timecode = '".$time."'
	  			";
	  	if(mysql_query($sql_lightbox_ins)){

	  		$new_lightbox_id = mysql_insert_id();
	  		foreach($lightbox_users as $l_user_ins){

	  			$role_id_ins = "";
			  	if(isset($_POST['keep_roles'])){
			  		if($l_user_ins['role_id'] != ""){
					  	$role_id_ins = "role_id = ".$l_user_ins['role_id'].",";
					}
				}

				$sql_lightbox_user_ins = "INSERT INTO agency_lightbox_users 
	  				SET 
	  				".$role_id_ins."
	  				lightbox_id = ".$new_lightbox_id.",
	  				user_id = ".$l_user_ins['user_id']."
	  			";

	  			mysql_query($sql_lightbox_user_ins);
	  		}

	  		$notification['success'] = "Lightbox Copied Successfully";

	  	}
	}

	if(isset($_POST['msg_lbox']) && $_POST['msg_lbox'] == "SEND"){
		$add_msg_cnt = 0;
		foreach($lightbox_users as $l_user_ins){

			$send_array = array(
				'message' => $_POST['light_message'],
				'subject' => $_POST['light_subject'],
				'user' => $l_user_ins['user_id'],
				'lightbox_id' => $lightbox_id
			);

			if(send_message_dash($send_array)){
				$add_msg_cnt++;
			}

			if($add_msg_cnt > 0){
				$notification['success'] = "Message Send Successfully";
			}

			// $sql_lightbox_user_ins = "INSERT INTO agency_lightbox_users 
	  // 				SET 
	  // 				".$role_id_ins."
	  // 				lightbox_id = ".$new_lightbox_id.",
	  // 				user_id = ".$l_user_ins['user_id']."
	  // 			";

	  // 			if(mysql_query($sql_lightbox_user_ins)){
	  // 				$add_msg_cnt++;
	  // 			}
		}
	}

?>

<div id="page-wrapper">
    <div class="" id="main">

    	<?php if(isset($notification['success'])){ ?>
          <div class="alert alert-success" role="alert" id="alert-success-form" style="">
              <?php echo $notification['success']; ?>
          </div>
        <?php } ?>
        <?php if(isset($notification['error'])){ ?>
            <div class="alert alert-danger" role="alert" id="alert-danger-form" style="">
                <?php echo $notification['error']; ?>
            </div>
        <?php } ?>

	    <div class="row">
	        <div class="col-sm-12 col-md-12" id="">

	          	<h3>Lightbox - <?php echo $lightbox['lightbox_name']; ?></h3>
	          	<div class="box">
	            	<div class="box-body">

		                <div class="text-center">
		                  <!-- Lightboxes&nbsp;&nbsp; -->
		                  <!-- <a href="#" class="AGENCY_graybutton btn btn-sm btn-theme btn-flat" style="">Manage</a></div>
		                  <br> -->
		                  <!-- <p style="">WHAT IS: <span style="">NEW FACES EXPERIENCED PROFESSIONAL ?</span></p> -->
		                  <p><strong>LIGHTBOX NAME: <?php echo $lightbox['lightbox_name']; ?></strong></p>

		                  <?php if($lightbox['casting_id'] != ""){ ?>
		                    <p>This lightbox is linked with the casting:<a href="casting-view.php?<?php echo $lightbox['casting_id']; ?>"> <?php echo $lightbox['job_title']; ?></a></p>
		                  <?php } ?>
		                </div>

		                <form class="checkboxes" method="post" name="" id="" action="">
		                  	<div class="lightbox-casting">

			                    <div class="row-flex">
			                      <?php foreach($lightbox_users as $usr){ ?>
			                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12" style="margin-bottom:30px;">
			                          <!-- <div class="light-demo"> -->

			                            <div class="card" style="height: 100%">
			                              <?php
			                                if(file_exists('../uploads/users/' . $usr['user_id'] . '/profile_pic/thumb/'. '128x128_' . $usr['user_avatar'])){
			                                  $profile_pic = '../uploads/users/' . $usr['user_id'] . '/profile_pic/thumb/'. '128x128_' . $usr['user_avatar'];
			                                }else{
			                                  $profile_pic = '../images/friend.gif';
			                                }
			                              ?>
			                              <a style="height: 128px;">
			                                <img src="<?php echo $profile_pic; ?>">
			                              </a>
			                              <br/>
			                              <label> <input type="checkbox" name="check_users[]" value="<?php echo $usr['user_id']; ?>"><?php echo $usr['firstname'].' '.$usr['lastname']; ?></label>
			                              <p>Union: <?php echo $usr['union_name']; ?></p>
			                              <a href="mailto:<?php echo $usr['user_email']; ?>"><?php echo $usr['user_email']; ?></a>
			                              <p><?php echo $usr['phone']; ?></p>
			                            </div>

			                          <!-- </div> -->
			                        </div>
			                      <?php } ?>
			                    </div>

			                    <div class="first-btn">
			                      <input type="button" class="check-all btn btn-theme btn-sm btn-flat" value="Select Role">
			                      <input type="reset" class="btn btn-theme btn-sm btn-flat"value="Unselect Role">
			                      <input type="submit" name="Remove" id="remove_role" class="btn btn-theme btn-sm btn-flat" value="Remove Role">
			                    </div>

			                    <div id="sendtoform" style="display: block;padding:20px">
			                    	<div class="row">
			                    		<div class="col-sm-6 col-sm-offset-3 col-md-offset-3 col-md-6 col-xs-12">
			                    		
			                    			<div class="form-group">
			                    				<lavel>Email address of recipient</lavel>
			                    				<input type="text" name="emailto" id="emailto" class="form-control">
			                    			</div>

			                    			<div class="form-group">
			                    				<lavel>Your Message</lavel>
			                    				<textarea name="email_content" class="form-control">I thought you might be interested in the some talent from TheAgencyOnline.com.  Just follow the link below.
			                    				&#13;&#10;https://www.theagencyonline.com/lightbox.php?lightbox=<?php echo $lightbox_id; ?>&amp;code=<?php echo time(); ?></textarea>
			                    			</div>

			                    			<input type="submit" name="send_mail" id="send_mail" value="SEND">&nbsp;&nbsp;&nbsp;
			                        		<input type="button" onclick="document.getElementById('sendtoform').style.display='none'" value="CANCEL">

			                    		</div>
			                    	</div>
			                    </div>

			                    <div id="copyform" style="display: block; padding: 20px;">
			                    	<div class="row">
			                    		<div class="col-sm-6 col-sm-offset-3 col-md-offset-3 col-md-6 col-xs-12">
			                    			<div class="form-group">
			                    				<lavel>New Lightbox Name</lavel>
			                    				<input type="text" name="copy_name" id="copy_name" class="form-control">
			                    			</div>

			                    			<div class="form-group">
			                    				<lavel>Description</lavel>
			                    				<textarea name="copy_description" class="form-control"></textarea>
			                    			</div>

											<div class="form-group">
			                    				<label><input type="checkbox" name="keep_roles" id=""/> Keep Roles In New Lightbox </label>
			                    			</div>

			                    			<input type="submit" name="copy_lbox" id="copy_lbox" value="COPY" />&nbsp;&nbsp;&nbsp;
			                        		<input type="button" onclick="document.getElementById('copyform').style.display='none'" value="CANCEL">
			                    		</div>
			                    	</div>
			                    </div>

			                    <div id="msgform" class="text-center" style="display: block; padding: 20px; background: #ccc;margin-bottom: 30px;float: left;width: 100%;">
			                    	<div class="row">
			                    		<div class="col-sm-6 col-sm-offset-3 col-md-offset-3 col-md-6 col-xs-12">
			                    			<div class="form-group">
			                    				<lavel>Subject</lavel>
			                    				<input type="text" name="light_subject" id="light_subject" class="form-control">
			                    			</div>
			                    			<div class="form-group">
			                    				<lavel>Message</lavel>
			                    				<textarea name="light_message" id="light_message" class="form-control"></textarea>
			                    			</div>

			                    			<input type="submit" name="msg_lbox" id="msg_lbox" value="SEND" />&nbsp;&nbsp;&nbsp;
			                        		<input type="button" onclick="document.getElementById('msgform').style.display='none'" value="CANCEL">
			                    		</div>
			                    	</div>
			                    </div>

			                    <div class="second-btn">
			                      <!-- <input type="button" name="" class="btn btn-theme btn-sm btn-flat" value="Print/Save"> -->
			                      <input type="button" name="" class="btn btn-theme btn-sm btn-flat" id="message-btn" value="Send Message">
			                      <input type="button" name="" class="btn btn-theme btn-sm btn-flat" id="Friend-btn" value="Send Lightbox to Friend">
			                      <input type="button" name="" class="btn btn-theme btn-sm btn-flat" id="copy-btn" value="Copy">
			                      <input type="submit" name="remove_lightbox" id="remove_lightbox" class="btn btn-theme btn-sm btn-flat" value="Remove Lightbox">
			                      <!-- <input type="button" name="" class="btn btn-theme btn-sm btn-flat" value="Admin: Email to"> -->
			                      <!-- <input type="submit" name="" class="btn btn-theme btn-sm btn-flat" name="autonotify" value="Admin: Auto-Notify" onclick="return confirm('ALL people in this lightbox will be sent an automated email letting them know about this casting.  Reminder: This auto feature sends to ALL people, not just checked people.')"> -->
			                    </div>

		                  	</div>
		                </form>

		            </div>
		        </div>

	        </div>
	    </div>

    </div>
</div>



<?php include('footer_js.php'); ?>
<!-- <script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 
 -->
<!-- <script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui.min.js"></script> -->

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script> -->
<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->

<script>
  $(function() {
    $('.check-all').on('click', function() {
      $('.checkboxes input:checkbox').prop('checked', true);
    });

    $('.uncheck-all').on('click', function() {
      $('.checkboxes input:checkbox').prop('checked', false);
    });
  });
</script>

<script>
  // $(document).ready(function() {
    $("#sendtoform").hide();
    $("#Friend-btn").click(function() {
      $("#sendtoform").show();
    });
  // });
</script>

<script>
  // $(document).ready(function() {
    $("#copyform").hide();
    $("#copy-btn").click(function() {
      $("#copyform").show();
    });
  // });
</script>

<script>
  // $(document).ready(function() {
    $("#msgform").hide();
    $("#message-btn").click(function() {
      $("#msgform").show();
    });
  // });
</script>

<script>
	$("#remove_role").click(function(e) {
		var checked_count = $('input[name="check_users[]"]:checked').length;
		if(checked_count == 0){
			alert("Please Select User Role");
			return false;
		}
	});

	$("#remove_lightbox").click(function(e) {
		if(confirm('Are You Sure Want to Delete Lightbox ?')){
			return true;
		}else{
			return false;
		}
	});

	$("#send_mail").click(function(e) {
		var regExp = /^([\w\.\+]{1,})([^\W])(@)([\w]{1,})(\.[\w]{1,})+$/;
		email = $("#emailto").val();
		// console.log(email);

		if(regExp.test(email)){
		}else{
			console.log('dddd');
			alert('please enter valid email');
			return false;
		}
	});

	$("#copy_lbox").click(function(e) {
		copy_name = $("#copy_name").val();
		if(copy_name === ""){
			alert('please enter lightbox name');
			return false;
		}
	});

	$("#msg_lbox").click(function(e) {
		light_subject = $("#light_subject").val();
		light_message = $("#light_message").val();
		if(light_subject === "" || light_message === ""){
			alert('Please Enter Subject and Message');
			return false;
		}
	});

</script>
<script>
    if (window.history.replaceState) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php include('footer.php'); ?>