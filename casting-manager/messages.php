<?php	
	$page = "messages";
	$page_selected = "messages";
	include('header.php');

	if(empty($profileid)) {
		if($_SESSION['user_id']) { // check if user is logged in
			$profileid = $_SESSION['user_id'];	
			
			$sql = "SELECT * FROM agency_profiles WHERE user_id='$profileid'";
			$result=mysql_query($sql);
			if($userinfo = sql_fetchrow($result)) {
				// ok
			} else {
				echo 'ERROR 482';
				exit();
			}
				
		} else {
			$url = 'home.php';
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
		}
	}
?>

<div id="page-wrapper">
  <!-- <div class="container-fluid"> -->
      <!-- Page Heading -->
    <div class="" id="main">
    		<h3>Messages </h3>
    		<?php //if(isset($notification['success'])){ ?>
		        <div class="alert alert-success" role="alert" id="alert-success-form" style="display: none;">
		            <?php //echo $notification['success']; ?>
		        </div>
	        <?php //} ?>
	        <?php //if(isset($notification['error'])){ ?>
	            <div class="alert alert-danger" role="alert" id="alert-danger-form" style="display: none;">
	                <?php //echo $notification['error']; ?>
	            </div>
	        <?php //} ?>
	
			<div class="row">
				<div class="col-md-6">
					<div class="box box-theme">
						<div class="box-body">

							<ul class="nav nav-tabs">
							    <li><a data-toggle="tab" href="#inbox_tab" id="first_tab">Inbox</a></li>
							    <li><a data-toggle="tab" href="#sent_tab">Sent</a></li>
							    <li><a data-toggle="tab" href="#compose_tab">Compose</a></li>
							    <!-- <li><a data-toggle="tab" href="#menu3">Menu 3</a></li> -->
							</ul>

						  	<div class="tab-content">
							    <div id="inbox_tab" class="tab-pane fade">
							      	
							    </div>
							    <div id="sent_tab" class="tab-pane fade">
							      	
							    </div>
							    <div id="compose_tab" class="tab-pane fade">
							    	<br/>
							      	<form name="send_message" id="send_message" action="javascript:void(0)" method="post">
										<h4>Compose Message:</h4>
										<br/>

										<div class="form-group">
											<label>User Role</label>
											<select name="role" id="role" class="form-control">
												<option value=""></option>
												<option value="talent">Talent</option>
												<option value="talent_manager">Talent Manager</option>
												<option value="client">Casting Director</option>
											</select>
										</div>

										<div class="form-group">
						                  	<label>User</label>
											<select name="user" id="user" class="form-control">
												<option value=""></option>
											</select>
						                </div>

						                <div class="form-group">
											<label>Subject</label>
										    <input type="text" name="subject" id="subject" class="form-control"/>
									    </div>

									    <div class="form-group">
											<label>Message</label>
										    <textarea rows="10" name="message" id="message" class="form-control"></textarea>
									    </div>

									    <div class="form-group">
									    	<button type="submit" id="send_message_btn" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send</button>
									    </div>
								        <!-- <input type="hidden" value="" name="to" id="to_id">
								        <input type="hidden" value="true" name="sendit">
								        <input type="hidden" name="creation_time" value="1349132467">
								        <input type="hidden" name="form_token" value="478146734f72e7b9819baff01bf01a4c75e4f38e"> -->
								
							    	</form>
							    </div>
							    <!-- <div id="menu3" class="tab-pane fade">
							      	<h3>Menu 3</h3>
							      	<p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
							    </div> -->
						  	</div>

					  	</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="view_box"></div>
				</div>
			</div>

	</div>
	<!-- </div> -->
</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>
<script type="text/javascript">
	$('#user').select2();
</script>

<script type="text/javascript">

	$(document).ready(function(){
		$("#first_tab").trigger('click');
	});

	// $(".alert").hide();
	// $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
	$('a[data-toggle="tab"]').on('click', function (e) {

		$(".view_box").html('');
	  	var target = $(e.target).attr("href");
	  	// alert(target);
	  	var target_new = target.replace('#', '');
	  	// alert(target_new);

		if(target == "#inbox_tab" || target == "#sent_tab"){

			if(target == "#inbox_tab"){
				msg_type = "inbox";
			}else if(target == "#sent_tab"){
				msg_type = "sent";
			}

			// datatable_inbox = $('.datatable_inbox').DataTable();
			// datatable_sent = $('.datatable_sent').DataTable();

			$.ajax({
			    url: '../ajax/dashboard_request.php',
			    type: 'post',
			    data: {name:'get_message_list',msg_type:msg_type},
			    // dataType: 'json',
			    success: function(response){

			    	// console.log(response);
			    	// return false;

			    	$(target).html(response);

			    	// html = '<option value=""></option>';
			    	// $.each(response, function(index, value){
				    // 	html += '<option value="'+value.user_id+'">'+value.firstname+' '+value.lastname+'</option>';
				    // });

			    	// $('#req_talent').html(html);
			    	// $('#casting_id').val(casting_id);
				    // // Add response in Modal body
				    // // $('.modal-body').html(response);

				    // Display Modal
				    // datatable_inbox.ajax.reload();
				    // datatable_sent.ajax.reload();

				    $('.datatable_inbox').DataTable().destroy();
					$('.datatable_sent').DataTable().destroy();

				    $('.datatable_inbox').DataTable({
				        "order": [[ 2, "desc" ]]
				    }).draw();
				    $('.datatable_sent').DataTable({
				        "order": [[ 2, "desc" ]]
				    }).draw();
			    }
		  	});
		}

	  	// if(target == "#compose_tab"){

	  	// }
	});

	$('#role').on('change',function(){
		role = $(this).val();

		$( "#user" ).prop( "disabled", true );

		$.ajax({
		    url: '../ajax/dashboard_request.php',
		    type: 'post',
		    data: {name:'get_users_by_role',role:role},
		    dataType: 'json',
		    success: function(response){

		    	// console.log(response);
		    	// return false;

		    	html = '<option value=""></option>';
		    	$.each(response, function(index, value){
			    	html += '<option value="'+value.user_id+'">'+value.firstname+' '+value.lastname+'</option>';
			    });

		    	$('#user').html(html);

		    	$( "#user" ).removeAttr("disabled");
		    }
	  	});
	});

	$("#send_message").validate({
		rules: {
			role: "required",
			user: "required",
			subject: "required",
			message: "required"
		},
		messages: {
			// lastname: "Please enter your lastname",
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if (element.prop("type") === "checkbox") {
				error.insertAfter(element.parent("label"));
			} else if(element.prop("id") === "user"){
				error.insertAfter(element.siblings(".select2"));
			} else {
				error.insertAfter( element );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (){
			// alert("submitted!");

			formData = new FormData($('#send_message')[0]);
			formData.append('name', 'send_msg_from_admin');

		    var casting_id = $(this).attr('data-id');
		   // AJAX request
		   $.ajax({
			    url: '../ajax/dashboard_request.php',
			    type: 'post',
			    data: formData,
			    dataType: 'json',
			    processData: false,
				contentType: false,
				cache:false,
			    success: function(response){ 

			    	if(response){
			    		$("#alert-success-form").html('Message Sent Successfully.');
			    		$("#alert-success-form").show();
						setTimeout(function() { $("#alert-success-form").hide(); }, 4000);
			    	}else{
						$("#alert-danger-form").html('something went wrong. you can'+"'"+'t send message.');
						$("#alert-danger-form").show();
						setTimeout(function() { $("#alert-danger-form").hide(); }, 4000);
			    	}
			    	// console.log(response);
			    	return false; 
			    }
		  	});

		   $('.select2').select2();
		   $("#send_message").find("input[type=text], textarea, select").val("");

		}
	});

	// $(document).on('validate', '#reply', function(){
	

	$(document).on('click', '#send_message_reply_btn', function(){
		// $("#reply").submit();

		$("#reply").validate({
			rules: {
				messagereply: "required",
			},
			messages: {
				// lastname: "Please enter your lastname",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass("help-block");
				if (element.prop("type") === "checkbox") {
					error.insertAfter(element.parent("label"));
				} else if(element.prop("id") === "user"){
					error.insertAfter(element.siblings(".select2"));
				} else {
					error.insertAfter( element );
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
			},
			submitHandler: function (){
				// alert("submitted!");

				formData = new FormData($('#reply')[0]);
				formData.append('name', 'send_msg_reply_from_admin');

			    var casting_id = $(this).attr('data-id');
			   // AJAX request
			   $.ajax({
				    url: '../ajax/dashboard_request.php',
				    type: 'post',
				    data: formData,
				    dataType: 'json',
				    processData: false,
					contentType: false,
					cache:false,
				    success: function(response){ 

				    	// console.log(response);
				    	// return false;

				    	if(response.success){
				    		$("#alert-success-form").html(response.success);
				    		$("#alert-success-form").show();
							setTimeout(function() { $("#alert-success-form").hide(); }, 4000);
				    	}else if(response.error){
				    		$("#alert-danger-form").html(response.error);
							$("#alert-danger-form").show();
							setTimeout(function() { $("#alert-danger-form").hide(); }, 4000);
				    	}

				    }
			  	});

			   // $('.select2').select2();
			   $("#reply").find("input[type=text], textarea, select").val("");

			}
		});

	});

	function view_message(type,id){
		// console.log(type);
		if(type == 'message_id'){
			data1 = "message_id="+id;
		}else if(type == 'sent_id'){
			// data1 = sent_id:id
			data1 = 'sent_id='+id;
		}

		$('.view_box').html('<div class="overlay text-center"><h2><i class="fa fa-refresh fa-spin"></i><h2></div>');
		
		$.ajax({
		    url: '../ajax/dashboard_request.php?name=view_msg&'+data1,
		    type: 'get',
		    // data: data1,
		    success: function(response){

		    	// console.log(response);
		    	// return false;
		    	// console.log(type);

		    	$('.view_box').html(response);
		    	// $('.view_box').html('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');

		    	// html = '<option value=""></option>';
		    	// $.each(response, function(index, value){
			    // 	html += '<option value="'+value.user_id+'">'+value.firstname+' '+value.lastname+'</option>';
			    // });

		    	// $('#user').html(html);
		    	// $( "#user" ).removeAttr("disabled");
		    }
	  	});
	}

	function delete_message(type,id){

		if(confirm("Are you sure you wish to delete this message?")){

			// console.log(type);
			if(type == 'deletemessage'){
				data1 = "deletemessage="+id;
			}else if(type == 'deletesent'){
				data1 = 'deletesent='+id;
			}

			$.ajax({
			    url: '../ajax/dashboard_request.php?name=delete_msg&'+data1,
			    type: 'get',
			    // data: data1,
			    success: function(response){

			    	// console.log(response);

			    	if(response){
			    		$("#alert-success-form").html('Message Delete Successfully.');
			    		$("#alert-success-form").show();
						setTimeout(function() { $("#alert-success-form").hide(); }, 4000);
			    	}else{
						$("#alert-danger-form").html('something went wrong. you can'+"'"+'t delete message.');
						$("#alert-danger-form").show();
						setTimeout(function() { $("#alert-danger-form").hide(); }, 4000);
			    	}

			    	$(".nav-tabs > li.active a").trigger('click');


			    }
		  	});

		}
	}

	$(document).on('click', '.close_view_box', function(){
		$(this).parents('.box').hide();
	});

 //    $("#send_message_btn").on("click", function(e){
 //    	e.preventDefault();
	// });

</script>
<?php
	include('footer.php');

	// if($_GET['refresh'] && !$submitmessage) {
	// 	echo '<script type="text/javascript">document.location=\'profile.php?u=' . $profileid . '\';</script>';
	// }
?>
