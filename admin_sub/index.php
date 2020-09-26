<?php
// include('header.php');
?>
        <!-- <p style="font-weight: bold; font-size: large">Welcome to the admin area</p> -->
<?php
//include('footer.php');
?>

<?php 	
	$page_selected = "dashboard";
	$page = "dashboard";
	include('header.php');
?>
 
    <div id="page-wrapper">
    	<!-- <div class="container-fluid"> -->
        	<!-- Page Heading -->
			<!-- <div class="well" id="main"> -->
				<div class="row" id="content">

					<div class="col-sm-4">
						<!-- <div class="profile-box card">
							<p><strong>MY PROFILE</strong></p>
							<hr>
							<div class="pro-img">
								<a href="#" class="text-center">View Profile</a>    
								<img src="../dashboard/assets/img/avatar.jpg">
							</div>
							<div class="prof-txt">
								<p>
									BIOGRAPHY:<br>
									Name : <?= $row['firstname'] ?>
								</p><br>
								<span style="">Location: <?= $row['location'] ?></span>
							</div>
						</div> -->
						<div class="card">
							<h4>ORDERS</h4>
							<canvas id="canvas_bar_chart"></canvas>
						</div>
						<br/>

						<div class="box box-info">
				            <div class="box-header with-border">
				              <h3 class="box-title">Notification</h3>
							</div>
							
							<div class="box-body box-comments">
								<?php
									$sql = "SELECT an.*,fu.user_avatar FROM agency_notification an
									LEFT JOIN forum_users fu ON an.from_id = fu.user_id
									WHERE to_id = ".$_SESSION['user_id']." AND status = 'active' ORDER BY notification_id DESC";

									$query = mysql_query($sql);
									$result = array();

									if (mysql_num_rows($query) > 0) {
										while ($row = mysql_fetch_assoc($query)) {
								?>
						                	<div class="box-comment">
												<?php 
													if(file_exists('../uploads/users/' . $row['from_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar']) ){
														$image = '../uploads/users/' . $row['from_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar'];
													}else{
														$image = '../images/friend.gif';
													}
												?>
												<img src="<?php echo $image; ?>" class="img-circle img-sm" alt="User Image">
												
						                		<div class="comment-text">
								                   <span class="username">
								                        <?php echo $row['title']; ?>
								                        <span class="text-muted pull-right"><?php echo $row['created_at']; ?></span>
								                    </span>
								                  	<?php echo $row['message']; ?>
								              	</div>
								            </div>

									<?php } ?>
								<?php } ?>
					        </div>
							
							<?php if (mysql_num_rows($query) == 0) { ?>
								<div class="box-body">
									<div class="text-center"> No New Notification </div>
								</div>
							<?php } ?>

				            <!-- <div class="box-footer text-center">
				              <a href="messages.php" target="_blank" class="uppercase">View All</a>
				            </div> -->
					    </div>

					    <div class="box box-success">
				            <div class="box-header with-border">
				              <h3 class="box-title">MESSAGES</h3>
				            </div>

				            <div class="box-body box-comments">
					            <?php
									// $sql = "SELECT * FROM agency_messages WHERE to_id=".$_SESSION['user_id']." AND deleted='0' ORDER BY date_entered DESC LIMIT 5";
									$sql = "SELECT am.*,fu.user_avatar FROM agency_messages am
									LEFT JOIN forum_users fu ON am.from_id = fu.user_id
									WHERE to_id = ".$_SESSION['user_id']." AND deleted='0' ORDER BY date_entered DESC LIMIT 5";

									$query = mysql_query($sql);
									$result = array();
									if (mysql_num_rows($query) > 0) {
										while ($row = mysql_fetch_assoc($query)) {
								?>
						                	<div class="box-comment">
												<?php 
													if(file_exists('../uploads/users/' . $row['from_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar']) ){
														$image = '../uploads/users/' . $row['from_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar'];
													}else{
														$image = '../images/friend.gif';
													}
												?>
												<img src="<?php echo $image; ?>" class="img-circle img-sm" alt="User Image">

						                		<div class="comment-text">
								                   <span class="username">
								                        <?php echo $row['from_name']; ?>
								                        <span class="text-muted pull-right"><?php echo $row['date_entered']; ?></span>
								                    </span>
								                  	<?php echo $row['subject']; ?>
								              	</div>
								            </div>

									<?php } ?>
								<?php } ?>
					        </div>

				            <div class="box-footer text-center">
				              <a href="messages.php" target="_blank" class="uppercase">View All</a>
				            </div>
					    </div>

					</div>

					<div class="col-sm-4">
						<div class="row">

							<div class="col-lg-6 col-xs-6">
					         	<!-- small box -->
						        <div class="small-box bg-aqua">
						            <div class="inner">
						            	<?php
					        				$sql = "SELECT count(user_id) as total_failed FROM agency_profiles";
											$total_user = mysql_result(mysql_query ($sql),0);
										?>
						              	<h3><?php echo $total_user; ?></h3>
						              	<p>USERS </p>
						            </div>
						            <div class="icon">
						              	<i class="fa fa-user"></i>
						            </div>
						            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						        </div>
					        </div>

							<div class="col-lg-6 col-xs-6">
					         	<!-- small box -->
						        <div class="small-box bg-primary">
						            <div class="inner">
						            	<?php
					        				$sql = "SELECT count(casting_id) as total_active_casting FROM agency_castings WHERE live = '1'";
											$total_active_casting = mysql_result(mysql_query ($sql),0);
										?>
						              	<h3><?php echo $total_active_casting; ?></h3>
						              	<p>TOTAL CASTING CALLS </p>
						            </div>
						            <div class="icon">
						              	<i class="fa fa-user"></i>
						            </div>
						            <a href="casting-list.php" target="_blank" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
						        </div>
					        </div>

             			</div>

						<div class="row">
				            <div class="col-md-12">
					          	<div class="box box-widget widget-user bg-black" style="background: url('../dashboard/assets/img/cover.jpg') center center;">
					            	<div class="widget-user-header">
					              		<h3 class="widget-user-username">Agency Angle <i class="fa fa-angle-right"></i> Latest Blog Post</h3>
					              		<h5 class="widget-user-desc">How To Take Quality Without Breaking The Bank</h5>
					            	</div>
					          	</div>
					        </div>
						</div>

						<!-- <div class="calendar-wrapper">
						  	<button id="btnPrev" type="button">Prev</button>
							<button id="btnNext" type="button">Next</button>
						  	<div id="divCal"></div>
						</div> -->
						<div class="row">
							<div class="col-md-6">

								<!-- <div class="container-fluid box-white card">
									<div class="col-md-6" style="border-right: 1px solid #ddd;">
										<b>PAGES <i class="fa fa-plus pull-right text-danger"></i></b>
										<h1 class="text-danger">8</h1>
									</div>
									<div class="col-md-6">
										<b>POSTS <i class="fa fa-plus pull-right text-primary"></i></b>
										<h1 class="text-primary">137</h1>
									</div>	
								</div> -->

								<div class="small-box bg-fb text-white">
									<div class="inner">
				          				<span class="on-not1 text-uppercase"><b><?php echo $site_name = get_agency_var('site_name'); ?></b></span>
				          				<span class="on-social"><i class="fa fa-facebook" aria-hidden="true"></i></span>
				      					<br><br>
				      					<p><?php echo get_agency_var('dashboard_fb_text'); ?></p>
				      				</div>
								</div>

								<div class="small-box bg-twitter text-white">
									<div class="inner">
									    <span class="on-not1 text-uppercase"><b><?php echo $site_name; ?></b></span>
									    <span class="on-social"><i class="fa fa-twitter" aria-hidden="true"></i></span>
									    <br><br>
									    <p><?php echo get_agency_var('dashboard_twitter_text'); ?></p>
								    </div>
								</div>

								<!-- <div class="notification-box card">
									<p style=""><strong>LATEST COMMENTS</strong></p>
									<hr/>

									<div class="tab-content">
					                    <div class="tab-pane active" id="profile">
					                      	
					                      	<div>
					                      		<b>MIKE NILSON</b>
					                      		<span class="pull-right text-gray1">2h</span>
					                      		<br/>
					                      		<span>like this one! keep going!</span>
					                      	</div>
					                      	<hr/>

					                      	<div>
					                      		<b>MIKE NILSON</b>
					                      		<span class="pull-right text-gray1">2h</span>
					                      		<br/>
					                      		<span>like this one! keep going!</span>
					                      	</div>
					                      	<hr/>

					                      	<div>
					                      		<b>MIKE NILSON</b>
					                      		<span class="pull-right text-gray1">2h</span>
					                      		<br/>
					                      		<span>like this one! keep going!</span>
					                      	</div>
					                      	<hr/>

					                    </div>
									</div>

									<hr/>
									<a href="" class="text-gray1 text-center text-bold block">ALL COMMENTS</a>
								</div> -->

							</div>
							<div class="col-md-6">
								<!-- <div class="card"> -->
								  	<div id="dashCal"></div>
								<!-- </div> -->

								<!-- <br/>
								<div class="notification-box card-no-padding last-blog-posts-box">
									<p class="pad-left-10 pad-right-10 mar-0"><strong>LAST BLOG POSTS</strong></p>
									<hr/>
									<a href="" class="block">How To Make Comp Card</a>
									<a href="" class="block">How To Make Comp Card</a>
									<a href="" class="block">How To Make Comp Card</a>
									<a href="" class="block">How To Make Comp Card</a>
									<a href="" class="block">How To Make Comp Card</a>
								</div> -->

							</div>
						</div>

					</div>

					<div class="col-sm-4">

						<!-- <div class="casted">
						  	<p style="">QUICK TALENT SEARCH</p>
						 	<p style=""><i class="fa fa-circle-o" aria-hidden="true"></i></p>
						  	<p style="">LOCATION <i class="fa fa-angle-down"> </i></p>

							<form action="clienthome.php?mode=search<?php //if(isset($_GET['configure'])) echo '&configure=true'; ?>" method="post" name="searchform">
							  	<div style="">
									<input type="text" name="age" placeholder="Age">
								</div>
								<div style="">
									<input type="text" name="age2" placeholder="To">
								</div>
								<div style="">
									<input type="text" name="gender" placeholder="Gender">
								</div>
								<div style="">
									<input type="text" name="" placeholder="Ethnicity">
								</div>
								<div style="">
									<input type="text" name="" placeholder="Experience">
								</div>
								<div style="">
									<input value="Search" name="submitsearch" type="submit" class="serch-btn">
								</div>
						  	</form>
					 	</div> -->

					 	<div class="card">
					 		<h4>VISITS</h4>
							<canvas id="canvas_line_chart"></canvas>
						</div>
						<br/>

						<div class="row">
							<div class="col-md-12">






								<!-- <div class="casting-box card bg-info"> -->
									<!-- <p><b>NEW CASTING CALL</b></p> -->
									<?php
						    //             $profileid = 2;
										// $sql = "SELECT * FROM agency_castings WHERE posted_by='$profileid' AND deleted='0' ORDER BY post_date DESC LIMIT 5";
										// $result=mysql_query($sql);
										// if(mysql_num_rows($result) == 0) echo '<br /><br />You have not created any castings yet.<br /><br />';
										// while($row = sql_fetchrow($result)) {
										// 	$castingid = $row['casting_id'];
										// 	$jobtitle = $row['job_title'];
										// 	$live = $row['live'];
										// 	$livenote = '';
										// 	if(!$live) {
										// 		$livenote = '<span style="color:red">NOTE: THIS CASTING IS NOT LIVE.</span>';
										// 	}
										// 	// style="text-decoration:none; padding-left:130px"><a href="news.php?castingid=' . $castingid . '&amp;title=' . urlencode($jobtitle) . '"
							   // 				echo '- <a href="news.php?castingid=' . $castingid . '" style="text-decoration:none; color:#000000;">' . $jobtitle . ' (view)</a>' .
							   // 					' (<a href="castingupdate.php?castingid=' . $castingid . '" style="text-decoration:none; color:#333333;">edit</a>) ' . $livenote . '<br />';
												
											// find submissions for this casting
											// echo '<a href="clienthome.php?mode=castings&castingid=' . $castingid . '" style="text-decoration:none; padding-left:70px;';
											// $sql2 = "SELECT * FROM agency_mycastings, agency_castings_roles WHERE agency_mycastings.role_id=agency_castings_roles.role_id AND agency_castings_roles.casting_id='$castingid' AND agency_mycastings.removed='0'";
											// $result2=mysql_query($sql2);
											// $num_castings = mysql_num_rows($result2);				
											// if($num_castings == 0) {	
											// 	echo ' color:#0066FF;">You Have No Submissions';
											// } else {
											// 	$sql2 = "SELECT * FROM agency_mycastings, agency_castings_roles WHERE agency_mycastings.role_id=agency_castings_roles.role_id AND agency_castings_roles.casting_id='$castingid' AND agency_mycastings.new_submission='1' AND agency_mycastings.removed='0'";
											// 	$result2=mysql_query($sql2);
											// 	$num_castings = mysql_num_rows($result2);
											// 	if($num_castings == 0) {
											// 		echo '">View Submissions (No New Submissions)';
											// 	} else {
											// 		echo '">View Submissions (You have ' . $num_castings . ' New Submissions!)';
											// 	}
											// }
											// echo '</a><br /><br />';
										// }
									?>
		      					<!-- </div> -->


		      					<div class="box box-info">
						            <div class="box-header with-border">
						              	<h3 class="box-title">NEW CASTING CALL</h3>
						            </div>
						            <div class="box-body">
							            <div class="table-responsive">
							                <table class="table no-margin">
							                  	<thead>
								                  	<tr>
								                    	<th>Project</th>
								                    	<th>Status</th>
								                    	<th>Action</th>
								                  	</tr>
							                  	</thead>
								                <tbody>
								                  	<?php
								                  		$sql = "SELECT * FROM agency_castings WHERE posted_by != ".$_SESSION['user_id']." AND deleted='0' ORDER BY post_date DESC LIMIT 5";
														$result=mysql_query($sql);
														while($row = sql_fetchrow($result)) { 
													?>
									                  	<tr>
									                    	<td><?php echo $row['job_title']; ?></td>
									                    	<td>
									                    		<?php if($row['live'] == 1){ ?>
											                    	<span class="label label-success">Live</span>
											                    <?php }else{ ?>
											                    	<span class="label label-warning">Pending</span>
											                    <?php } ?>
									                    	</td>
									                    	<td>
									                      		<a href="casting-view.php?casting_id=<?php echo $row['casting_id']; ?>" class="btn btn-xs btn-info">view</a>
									                      		<a href="casting-update.php?casting_id=<?php echo $row['casting_id']; ?>" class="btn btn-xs btn-primary">Edit</a>
									                    	</td>
									                  	</tr>
									                <?php } ?>
							                  	</tbody>
							                </table>
							            </div>
						            </div>
						        </div>
	      					</div>
	      				</div>

						<div class="row">
							<div class="col-md-12">

								<div class="box box-warning">
						            <div class="box-header with-border">
						              <h3 class="box-title">Request</h3>
						            </div>

						            <div class="box-body box-comments">
							            <?php
											$result = mysql_query("select atr.*,ap.firstname,ap.lastname,ac.job_title,ap1.firstname as firstname_by,ap1.lastname as lastname_by,fu.user_avatar
																	from agency_talent_request atr
																	LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id 
																	LEFT JOIN agency_profiles ap1 ON ap1.user_id = atr.request_by 
																	LEFT JOIN forum_users fu ON ap1.user_id = fu.user_id
																	LEFT JOIN agency_castings ac ON ac.casting_id = atr.casting_id
																	where atr.request_status = 'pending'
																");
											if (mysql_num_rows($result) > 0) {
										?>
											<?php	
												while ($row = mysql_fetch_assoc($result)) { ?>

								                	<div class="box-comment">
														<?php 
															if(file_exists('../uploads/users/' . $row['request_by'] . '/profile_pic/thumb/50x50_'.$row['user_avatar']) ){
																$image = '../uploads/users/' . $row['request_by'] . '/profile_pic/thumb/50x50_'.$row['user_avatar'];
															}else{
																$image = '../images/friend.gif';
															}
														?>
														<img src="<?php echo $image; ?>" class="img-circle img-sm" alt="User Image">

								                		<div class="comment-text">
										                   <span class="username">
										                        From: <?php echo $row['firstname_by'].' '.$row['lastname_by']; ?>
										                        <!-- <span class="text-muted pull-right">To: <?php //echo $row['firstname'].' '.$row['lastname']; ?></span> -->
										                    </span>
										                  	To: <?php echo $row['firstname'].' '.$row['lastname']; ?>
										              	</div>
										            </div>

													<!-- echo '<tr>';
													// echo '<td>'.$row['job_title'].'</td>';
													echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
													echo '<td>'.$row['firstname_by'].' '.$row['lastname_by'].'</td>';

													echo '<td>'.$row['request_date'].' '.$row['request_time'].'</td>';
													// echo '<td>'.$row['request_location'].'</td>';
													echo '<td>';
													echo $row['request_status'].'&nbsp;&nbsp;&nbsp;';
													echo ' -->
														<!-- <form method="post" name="pending" action="">
															<input type="hidden" name="talent_request_id" value="'.$row['talent_request_id'].'"/>
															<button type="submit" name="pending_submit" class="btn btn-success btn-xs">Click To Approve</button>
														</form>'; -->

													<!-- // echo '<a href="talent-request-list.php?talent_request_id='.$row['talent_request_id'].'" class="btn btn-success btn-xs">Click To Approve</a>';
													echo '</td>';
													echo '<td><a class="btn btn-theme btn-view-request btn-sm" data-id="'.$row['talent_request_id'].'">View</a></td>';
													echo '</tr>'; -->

											<?php } ?>
										<?php }else{ ?>
								               <div class="text-center">No New Request.</div>
										<?php } ?>
										
						        </div>

			            		<div class="box-footer text-center">
					              <a href="talent-request-list.php" class="uppercase" target="_blank">See All Requests</a>
					            </div>
							</div>

						</div>

					</div>

				</div>
			<!-- </div> -->

		<!-- </div> -->
	</div>

<?php include('footer_js.php'); ?>

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
<!-- <script type="text/javascript" src="../dashboard/assets/Chart-js/samples/utils.js"></script> -->


<script>
	// var Cal = function(divId) {

	//     //Store div id
	//     this.divId = divId;

	//     // Days of week, starting on Sunday
	//     this.DaysOfWeek = [
	// 	    'Sun',
	// 	    'Mon',
	// 	    'Tue',
	// 	    'Wed',
	// 	    'Thu',
	// 	    'Fri',
	// 	    'Sat'
	// 	];

	//     // Months, stating on January
	//     this.Months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];

	//     // Set the current month, year
	//     var d = new Date();

	//     this.currMonth = d.getMonth();
	//     this.currYear = d.getFullYear();
	//     this.currDay = d.getDate();

	// };

	// // Goes to next month
	// Cal.prototype.nextMonth = function() {
	//   if ( this.currMonth == 11 ) {
	//     this.currMonth = 0;
	//     this.currYear = this.currYear + 1;
	//   }
	//   else {
	//     this.currMonth = this.currMonth + 1;
	//   }
	//   this.showcurr();
	// };

	// // Goes to previous month
	// Cal.prototype.previousMonth = function() {
	//   if ( this.currMonth == 0 ) {
	//     this.currMonth = 11;
	//     this.currYear = this.currYear - 1;
	//   }
	//   else {
	//     this.currMonth = this.currMonth - 1;
	//   }
	//   this.showcurr();
	// };

	// // Show current month
	// Cal.prototype.showcurr = function() {
	//   this.showMonth(this.currYear, this.currMonth);
	// };

	// // Show month (year, month)
	// Cal.prototype.showMonth = function(y, m) {

	//   var d = new Date()
	//   // First day of the week in the selected month
	//   , firstDayOfMonth = new Date(y, m, 1).getDay()
	//   // Last day of the selected month
	//   , lastDateOfMonth =  new Date(y, m+1, 0).getDate()
	//   // Last day of the previous month
	//   , lastDayOfLastMonth = m == 0 ? new Date(y-1, 11, 0).getDate() : new Date(y, m, 0).getDate();


	//   var html = '<table>';

	//   // Write selected month and year
	//   html += '<thead><tr>';
	//   html += '<td colspan="7">' + this.Months[m] + ' ' + y + '</td>';
	//   html += '</tr></thead>';


	//   // Write the header of the days of the week
	//   html += '<tr class="days">';
	//   for(var i=0; i < this.DaysOfWeek.length;i++) {
	//     html += '<td>' + this.DaysOfWeek[i] + '</td>';
	//   }
	//   html += '</tr>';

	//   // Write the days
	//   var i=1;
	//   do {

	//     var dow = new Date(y, m, i).getDay();

	//     // If Sunday, start new row
	//     if ( dow == 0 ) {
	//       html += '<tr>';
	//     }
	//     // If not Sunday but first day of the month
	//     // it will write the last days from the previous month
	//     else if ( i == 1 ) {
	//       html += '<tr>';
	//       var k = lastDayOfLastMonth - firstDayOfMonth+1;
	//       for(var j=0; j < firstDayOfMonth; j++) {
	//         html += '<td class="not-current">' + k + '</td>';
	//         k++;
	//       }
	//     }

	//     // Write the current day in the loop
	//     var chk = new Date();
	//     var chkY = chk.getFullYear();
	//     var chkM = chk.getMonth();
	//     if (chkY == this.currYear && chkM == this.currMonth && i == this.currDay) {
	//       html += '<td class="today">' + i + '</td>';
	//     } else {
	//       html += '<td class="normal">' + i + '</td>';
	//     }
	//     // If Saturday, closes the row
	//     if ( dow == 6 ) {
	//       html += '</tr>';
	//     }
	//     // If not Saturday, but last day of the selected month
	//     // it will write the next few days from the next month
	//     else if ( i == lastDateOfMonth ) {
	//       var k=1;
	//       for(dow; dow < 6; dow++) {
	//         html += '<td class="not-current">' + k + '</td>';
	//         k++;
	//       }
	//     }

	//     i++;
	//   }while(i <= lastDateOfMonth);

	//   // Closes table
	//   html += '</table>';

	//   // Write HTML to the div
	//   document.getElementById(this.divId).innerHTML = html;
	// };

	// // On Load of the window
	// window.onload = function() {

	//   // Start calendar
	//   var c = new Cal("divCal");			
	//   c.showcurr();

	//   // Bind next and previous button clicks
	//   getId('btnNext').onclick = function() {
	//     c.nextMonth();
	//   };
	//   getId('btnPrev').onclick = function() {
	//     c.previousMonth();
	//   };
	// }

	$('#dashCal').datepicker({
    	// changeMonth: true,
    	// changeYear: true,
    	// minDate: 0,
    });

	// Get element by id
	function getId(id) {
	  return document.getElementById(id);
	}



</script>

<script>
	// var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var color = Chart.helpers.color;
	var barChartData = {
		labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
		datasets: [{
			label: 'Orders',
			backgroundColor: color('rgb(255, 99, 132)').alpha(0.5).rgbString(),
			borderColor: 'rgb(255, 99, 132)',
			borderWidth: 1,
			data: [
					5,7,82,2,85,30,8,55,37,20,29,30
				// randomScalingFactor(),
				// randomScalingFactor(),
				// randomScalingFactor(),
				// randomScalingFactor(),
				// randomScalingFactor(),
				// randomScalingFactor(),
				// randomScalingFactor()
			]
		}, 
		// {
		// 	label: 'Dataset 2',
		// 	backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
		// 	borderColor: window.chartColors.blue,
		// 	borderWidth: 1,
		// 	data: [
		// 		randomScalingFactor(),
		// 		randomScalingFactor(),
		// 		randomScalingFactor(),
		// 		randomScalingFactor(),
		// 		randomScalingFactor(),
		// 		randomScalingFactor(),
		// 		randomScalingFactor()
		// 	]
		// }
		]

	};

	// window.onload = function() {
	// 	var ctx = document.getElementById('canvas_bar_chart').getContext('2d');
	// 	window.myBar = new Chart(ctx, {
	// 		type: 'bar',
	// 		data: barChartData,
	// 		options: {
	// 			responsive: true,
	// 			legend: {
	// 				position: 'top',
	// 			},
	// 			title: {
	// 				// display: true,
	// 				// text: 'Chart.js Bar Chart'
	// 			}
	// 		}
	// 	});

	// };

	// document.getElementById('randomizeData').addEventListener('click', function() {
	// 	var zero = Math.random() < 0.2 ? true : false;
	// 	barChartData.datasets.forEach(function(dataset) {
	// 		dataset.data = dataset.data.map(function() {
	// 			return zero ? 0.0 : randomScalingFactor();
	// 		});

	// 	});
	// 	window.myBar.update();
	// });

	// var colorNames = Object.keys(window.chartColors);
	// document.getElementById('addDataset').addEventListener('click', function() {
	// 	var colorName = colorNames[barChartData.datasets.length % colorNames.length];
	// 	var dsColor = window.chartColors[colorName];
	// 	var newDataset = {
	// 		label: 'Dataset ' + (barChartData.datasets.length + 1),
	// 		backgroundColor: color(dsColor).alpha(0.5).rgbString(),
	// 		borderColor: dsColor,
	// 		borderWidth: 1,
	// 		data: []
	// 	};

	// 	for (var index = 0; index < barChartData.labels.length; ++index) {
	// 		newDataset.data.push(randomScalingFactor());
	// 	}

	// 	barChartData.datasets.push(newDataset);
	// 	window.myBar.update();
	// });

	// document.getElementById('addData').addEventListener('click', function() {
	// 	if (barChartData.datasets.length > 0) {
	// 		var month = MONTHS[barChartData.labels.length % MONTHS.length];
	// 		barChartData.labels.push(month);

	// 		for (var index = 0; index < barChartData.datasets.length; ++index) {
	// 			barChartData.datasets[index].data.push(randomScalingFactor());
	// 		}

	// 		window.myBar.update();
	// 	}
	// });

	// document.getElementById('removeDataset').addEventListener('click', function() {
	// 	barChartData.datasets.pop();
	// 	window.myBar.update();
	// });

	// document.getElementById('removeData').addEventListener('click', function() {
	// 	barChartData.labels.splice(-1, 1); // remove the label first

	// 	barChartData.datasets.forEach(function(dataset) {
	// 		dataset.data.pop();
	// 	});

	// 	window.myBar.update();
	// });
</script>

<script>
	// var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
	var config_line = {
		type: 'line',
		data: {
			labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
			datasets: [{
				label: 'Visits',
				backgroundColor: 'rgb(255, 99, 132)',
				borderColor: 'rgb(255, 99, 132)',
				data: [
					5,7,82,2,85,30,8,55,37,20,29,30
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor(),
					// randomScalingFactor()
				],
				fill: false,
			}, 
			// {
			// 	label: 'My Second dataset',
			// 	fill: false,
			// 	backgroundColor: window.chartColors.blue,
			// 	borderColor: window.chartColors.blue,
			// 	data: [
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor(),
			// 		randomScalingFactor()
			// 	],
			// }
			]
		},
		options: {
			responsive: true,
			// title: {
			// 	display: true,
			// 	text: 'Chart.js Line Chart'
			// },
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				x: {
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Month'
					}
				},
				y: {
					display: true,
					scaleLabel: {
						display: true,
						labelString: 'Value'
					}
				}
			}
		}
	};

	window.onload = function() {
		// bar chart
		var ctx = document.getElementById('canvas_bar_chart').getContext('2d');
		window.myBar = new Chart(ctx, {
			type: 'bar',
			data: barChartData,
			options: {
				responsive: true,
				legend: {
					position: 'top',
				},
				title: {
					// display: true,
					// text: 'Chart.js Bar Chart'
				}
			}
		});

		// line chat
		var ctx_line = document.getElementById('canvas_line_chart').getContext('2d');
		window.myLine = new Chart(ctx_line, config_line);
	};

	// document.getElementById('randomizeData').addEventListener('click', function() {
	// 	config.data.datasets.forEach(function(dataset) {
	// 		dataset.data = dataset.data.map(function() {
	// 			return randomScalingFactor();
	// 		});

	// 	});

	// 	window.myLine.update();
	// });

	// var colorNames = Object.keys(window.chartColors);
	// document.getElementById('addDataset').addEventListener('click', function() {
	// 	var colorName = colorNames[config.data.datasets.length % colorNames.length];
	// 	var newColor = window.chartColors[colorName];
	// 	var newDataset = {
	// 		label: 'Dataset ' + config.data.datasets.length,
	// 		backgroundColor: newColor,
	// 		borderColor: newColor,
	// 		data: [],
	// 		fill: false
	// 	};

	// 	for (var index = 0; index < config.data.labels.length; ++index) {
	// 		newDataset.data.push(randomScalingFactor());
	// 	}

	// 	config.data.datasets.push(newDataset);
	// 	window.myLine.update();
	// });

	// document.getElementById('addData').addEventListener('click', function() {
	// 	if (config.data.datasets.length > 0) {
	// 		var month = MONTHS[config.data.labels.length % MONTHS.length];
	// 		config.data.labels.push(month);

	// 		config.data.datasets.forEach(function(dataset) {
	// 			dataset.data.push(randomScalingFactor());
	// 		});

	// 		window.myLine.update();
	// 	}
	// });

	// document.getElementById('removeDataset').addEventListener('click', function() {
	// 	config.data.datasets.splice(0, 1);
	// 	window.myLine.update();
	// });

	// document.getElementById('removeData').addEventListener('click', function() {
	// 	config.data.labels.splice(-1, 1); // remove the label first

	// 	config.data.datasets.forEach(function(dataset) {
	// 		dataset.data.pop();
	// 	});

	// 	window.myLine.update();
	// });
</script>


<?php include('footer.php'); ?>