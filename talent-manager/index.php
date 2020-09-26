<?php
$page_selected = "dashboard";
$page = "dashboard";
include('header.php');
include('../includes/agency_dash_functions.php');

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";

if (isset($_POST['cancel_account'])) {

	$query_account_status_update = "UPDATE agency_profiles
								SET 
								account_status = 'cancel'
								WHERE
								user_id=" . $_SESSION['user_id'] . "
							";
	if (mysql_query($query_account_status_update)) {
		// $notification['success'] = "Account Cancelled Successfully";
		$url = '../logout.php';
		header("Location: $url");
		exit();
	}
}

$user = get_talent_byId($_SESSION['user_id']);

?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
	<!-- Page Heading -->
	<!-- <div class="well" id="main"> -->
	<div class="row" id="content">

		<div class="col-sm-12">
			<div class="row">

				<div class="col-sm-5">

					<div class="box box-widget widget-user-2">
						<div class="widget-user-header bg-alert">
							<div class="widget-user-image">
								<?php
									if (file_exists('../uploads/users/' . $user['user_id'] . '/profile_pic/thumb/128x128_' . $user['user_avatar'])) {
										$image = '../uploads/users/' . $user['user_id'] . '/profile_pic/thumb/128x128_' . $user['user_avatar'];
									} else {
										$image = '../images/friend.gif';
									}
								?>
								<img class="img-circle" src="<?php echo $image; ?>" alt="User Avatar" style="width: 65px;height: 65px;">
							</div>
							<h3 class="widget-user-username"><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></h3>
							<h5 class="widget-user-desc">Talent Manager</h5>
						</div>
						<div class="box-footer no-padding">
							<ul class="nav nav-stacked">
								<li><a href="edit-profile.php">Edit Profile </a></li>
								<li><a href="#">Edit Notification </a></li>
								<li><a href="#">Payment History </a></li>
								<li><a href="#">Account </a></li>
								<li><a href="#">Affiliate Link </a></li>
							</ul>
						</div>
					</div>

				</div>

				<div class="col-sm-7">
					<div class="info-box">
						<div class="box-header with-border">
							<h3 class="box-title">New Casting Calls</h3>
						</div>
						<div class="box-body no-padding">
							<div class="table-responsive">
								<table class="table no-margin table-striped">
									<thead>
										<tr>
											<th>Project</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT * FROM agency_castings WHERE posted_by != " . $_SESSION['user_id'] . " AND deleted='0' ORDER BY post_date DESC LIMIT 5";
										$result = mysql_query($sql);
										while ($row = sql_fetchrow($result)) {
										?>
											<tr>
												<td><?php echo $row['job_title']; ?></td>
												<td>
													<?php if ($row['live'] == 1) { ?>
														<span class="label label-default">Live</span>
													<?php } else { ?>
														<span class="label label-default">Pending</span>
													<?php } ?>
												</td>
												<td>
													<a href="casting-role.php?casting_id=<?php echo $row['casting_id']; ?>" class="btn btn-xs btn-flat bg-olive">Roles</a>
													<!-- <a href="casting-update.php?casting_id=<?php echo $row['casting_id']; ?>" class="btn btn-xs btn-flat bg-navy">Edit</a> -->
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
		</div>

		<div class="col-sm-12">
			<div class="row">

				<div class="col-sm-4">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Notifications</h3>
						</div>

						<div class="box-body box-comments">
							<?php
							$sql_notification = "SELECT an.* FROM agency_notification an
												LEFT JOIN forum_users fu ON an.from_id = fu.user_id
												WHERE to_id=" . $_SESSION['user_id'] . " AND an.status='active' ORDER BY an.created_at DESC limit 10";
							$query_notification = mysql_query($sql_notification);

							$result = array();
							if (mysql_num_rows($query_notification) > 0) {
								while ($row = mysql_fetch_assoc($query_notification)) {
							?>
									<div class="box-comment">
										<img class="img-circle img-sm" src="../images/friend.gif" alt="User Image">
										<div class="comment-text">
											<span class="username">
												<?php //echo $row['title']; 
												?>
												<span class="text-muted pull-right"><?php echo $row['message']; ?></span>
											</span>
											<?php //echo $row['title']; 
											?>
										</div>
									</div>

								<?php } ?>
							<?php } else { ?>
								<div class="text-center">
									No New Notification
								</div>
							<?php } ?>
						</div>

						<!-- <div class="box-footer text-center">
									<a href="messages.php" target="_blank" class="uppercase">View All</a>
									</div> -->
					</div>
				</div>

				<div class="col-sm-4">

					<?php 
						$sql = "SELECT am.*,fu.user_avatar FROM agency_messages am
								LEFT JOIN forum_users fu ON am.from_id = fu.user_id
								WHERE to_id=".$_SESSION['user_id']." AND deleted='0' AND viewed = '0' ORDER BY date_entered DESC
						";
						// $sql = "SELECT * FROM agency_messages WHERE to_id=" . $_SESSION['user_id'] . " AND deleted='0' AND viewed = '0' ORDER BY date_entered DESC LIMIT 5";
						$query = mysql_query($sql);
					?>

					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Messages</h3>
							<span class="pull-right-container">
								<small class="label pull-right bg-theme"><?php echo mysql_num_rows($query); ?></small>
							</span>
						</div>

						<div class="box-body box-comments">
							<?php
							$result = array();
							if (mysql_num_rows($query) > 0) {
								while ($row = mysql_fetch_assoc($query)) {
									if(file_exists('../uploads/users/' . $row['from_id'] . '/profile_pic/thumb/25x25_'.$row['user_avatar']) ){
										$msg_image = '../uploads/users/' . $row['from_id'] . '/profile_pic/thumb/25x25_'.$row['user_avatar'];
									}else{
										$msg_image = '../images/friend.gif';
									}
							?>
									<div class="box-comment">
										<img class="img-circle img-sm" src="<?php echo $msg_image; ?>" alt="User Image">
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
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Activity</h3>
						</div>

						<div class="box-body box-comments">
							<?php
							// $sql = "SELECT * FROM agency_messages WHERE to_id=" . $_SESSION['user_id'] . " AND deleted='0' ORDER BY date_entered DESC LIMIT 5";

							// $query = mysql_query($sql);
							// $result = array();
							// if (mysql_num_rows($query) > 0) {
							// 	while ($row = mysql_fetch_assoc($query)) {
							?>
									<!-- <div class="box-comment">
										<img class="img-circle img-sm" src="../images/friend.gif" alt="User Image">
										<div class="comment-text">
											<span class="username">
												<?php echo $row['from_name']; ?>
												<span class="text-muted pull-right"><?php echo $row['date_entered']; ?></span>
											</span>
											<?php echo $row['subject']; ?>
										</div>
									</div> -->

								<?php //} ?>
							<?php //} ?>
						</div>

						<!-- <div class="box-footer text-center">
							<a href="messages.php" target="_blank" class="uppercase">View All</a>
						</div> -->
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

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/Chart-js/samples/utils.js"></script> -->

<script>
	$('#dashCal').datepicker({
		// changeMonth: true,
		// changeYear: true,
		// minDate: 0,
	});
</script>

<?php include('footer.php'); ?>