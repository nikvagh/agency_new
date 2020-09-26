<?php
  $page_selected = "dashboard";
  $page = "dashboard";
  include('header.php');
  include('../includes/agency_dash_functions.php');
?>

<div id="page-wrapper">
    <!-- <div class="container-fluid"> -->
        <!-- Page Heading -->
        <!-- <div class="row " id="main" style="padding: 19px 0 !important;"> -->
            <div class="row" id="content">
                <div class="col-sm-4">

                  <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-theme">
                      <h3 class="widget-user-username text-white"><?php echo $user['firstname'].' '.$user['lastname']; ?></h3>
                      <!-- <h5 class="widget-user-desc">Founder &amp; CEO</h5> -->
                    </div>
                    <div class="widget-user-image">
                      <?php 
                        if(file_exists('../uploads/users/' . $user['user_id'] . '/profile_pic/thumb/128x128_'.$user['user_avatar']) ){
                          $image = '../uploads/users/' . $user['user_id'] . '/profile_pic/thumb/128x128_'.$user['user_avatar'];
                        }else{
                          $image = '../images/friend.gif';
                        }
                      ?>
                        <img class="img-circle" src="<?php echo $image; ?>" alt="User Avatar" style="width: 84px;height: 84px;">
                    </div>
                    <div class="box-footer">
                      <div class="row">

                        <?php 
                          $result_friend = mysql_query("select af.* from agency_friends af
                            where (af.friend_id = ".$user['user_id']." OR af.user_id = ".$user['user_id'].")
                            AND confirmed = 1 AND denied = 0
                          ");
                        ?>

                        <?php
                          $sql_projects = "SELECT casting_id FROM agency_castings WHERE casting_director = " . $_SESSION['user_id'] . " AND deleted='0'";
                          $result_projects = mysql_query($sql_projects);
                        ?>
                        <div class="col-sm-4 border-right">
                          <div class="description-block">
                            <h5 class="description-header"><?php echo mysql_num_rows($result_projects); ?></h5>
                            <span class="description-text">PROJECTS</span>
                          </div>
                        </div>

                        <div class="col-sm-4 border-right">
                          <div class="description-block">
                            <h5 class="description-header"><?php echo mysql_num_rows($result_friend); ?></h5>
                            <span class="description-text">FRIENDS</span>
                          </div>
                        </div>

                        <?php
                          $sql_submission = "SELECT submission_id FROM agency_mycastings am
                                            LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
                                            LEFT JOIN agency_castings ac ON ac.casting_id = acr.casting_id
                                            WHERE ac.casting_director = ".$user['user_id']."
                                            GROUP BY am.submission_id";
                          $result_submission = mysql_query ($sql_submission);
                        ?>
                        <div class="col-sm-4">
                          <div class="description-block">
                            <h5 class="description-header"><?php echo mysql_num_rows($result_submission); ?></h5>
                            <span class="description-text">SUBMISSION</span>
                          </div>
                        </div>

                      </div>
                      <!-- /.row -->
                    </div>
                    <div class="box-footer no-padding">
                      <ul class="nav nav-stacked">
                        <?php if($user['bio'] != ""){ ?>
                          <li>
                            <a class="text-center"><?php echo $user['bio']; ?></a>
                          </li>
                        <?php } ?>
                        <!-- <li> -->
                          <!-- <a href="#">Tags:  -->
                          <!-- <span class="pull-right badge bg-green">12</span> -->
                          <!-- </a> -->
                        <!-- </li> -->
                      </ul>
                    </div>
                  </div>

                  <div class="box box-info">
                    <div class="box-header with-border">
                      <h3 class="box-title">Notification</h3>
                    </div>

                    <div class="box-body box-comments">
                      <?php
                        $sql_notification = "SELECT an.* FROM agency_notification an
                            LEFT JOIN forum_users fu ON an.from_id = fu.user_id
                            WHERE to_id=".$_SESSION['user_id']." AND an.status='active' ORDER BY an.created_at DESC limit 10";
                          $query_notification = mysql_query($sql_notification);

                          $result = array();
                          if (mysql_num_rows($query_notification) > 0) {
                            while ($row = mysql_fetch_array($query_notification, MYSQL_ASSOC)) {
                        ?>
                          <div class="box-comment">
                              <img class="img-circle img-sm" src="../images/friend.gif" alt="User Image">
                              <div class="comment-text">
                                 <span class="username">
                                      <?php //echo $row['title']; ?>
                                      <span class="text-muted pull-right"><?php echo $row['message']; ?></span>
                                  </span>
                                  <?php //echo $row['title']; ?>
                              </div>
                          </div>

                          <?php } ?>
                        <?php }else{ ?>
                            <div class="text-center">
                              No New Notification
                            </div>
                        <?php } ?>
                    </div>

                    <!-- <div class="box-footer text-center">
                      <a href="messages.php" target="_blank" class="uppercase">View All</a>
                    </div> -->
                  </div>

                  <div class="box box-success">
                    <div class="box-header with-border">
                      <h3 class="box-title">Messages</h3>
                    </div>

                    <div class="box-body box-comments">
                      <?php
                      $sql = "SELECT * FROM agency_messages WHERE to_id=" . $_SESSION['user_id'] . " AND deleted='0' ORDER BY date_entered DESC LIMIT 5";

                      $query = mysql_query($sql);
                      $result = array();
                      if (mysql_num_rows($query) > 0) {
                        while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
                      ?>
                          <div class="box-comment">
                            <img class="img-circle img-sm" src="../images/friend.gif" alt="User Image">
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
                      <div class="small-box bg-primary">
                          <div class="inner">
                              <?php
                                $sql_active_casting = "SELECT casting_id FROM agency_castings WHERE casting_director = " . $_SESSION['user_id'] . " AND deleted='0' and live = '1'";
                                $result_active_casting = mysql_query($sql_active_casting);
                              ?>
                              <h3><?php echo mysql_num_rows($result_active_casting); ?></h3>
                              <p>Active Casting Calls </p>
                          </div>
                          <div class="icon">
                              <i class="fa fa-user"></i>
                          </div>
                          <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                      </div>
                    </div>

                    <div class="col-lg-6 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-olive text-white">
                              <div class="inner">
                                <?php
                                  $sql_submission_new = "SELECT submission_id FROM agency_mycastings am
                                                    LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
                                                    LEFT JOIN agency_castings ac ON ac.casting_id = acr.casting_id
                                                    WHERE ac.casting_director = ".$user['user_id']."  and am.new_submission = '1'
                                                    GROUP BY am.submission_id";
                                  $result_submission_new = mysql_query ($sql_submission_new);
                                ?>
                                  <h3><?php echo mysql_num_rows($result_submission_new); ?></h3>
                                  <p>New Submissions </p>
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

                  <div class="row">
                    <div class="col-md-12">
                      <div id="dashCal"></div>
                      <br/>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="small-box bg-fb text-white">
                            <div class="inner">
                              <span class="on-not1 text-uppercase"><b><?php echo $site_name = get_agency_var('site_name'); ?></b></span>
                              <span class="on-social"><i class="fa fa-facebook" aria-hidden="true"></i></span>
                              <br><br>
                              <p><?php echo get_agency_var('dashboard_fb_text'); ?></p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="small-box bg-twitter text-white">
                            <div class="inner">
                              <span class="on-not1 text-uppercase"><b><?php echo $site_name; ?></b></span>
                              <span class="on-social"><i class="fa fa-twitter" aria-hidden="true"></i></span>
                              <br><br>
                              <p><?php echo get_agency_var('dashboard_twitter_text'); ?></p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="col-sm-4">

                    <?php include('quick_search.php'); ?>

                    <div class="info-box">
                      <div class="box-header with-border">
                          <h3 class="box-title">My Casting Calls</h3>
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
                                      $sql = "SELECT * FROM agency_castings WHERE casting_director = " . $_SESSION['user_id'] . " AND deleted='0' ORDER BY post_date DESC LIMIT 5";
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
                                                  <a href="casting-view.php?casting_id=<?php echo $row['casting_id']; ?>" class="btn btn-xs btn-flat bg-olive">view</a>
                                                  <a href="casting-update.php?casting_id=<?php echo $row['casting_id']; ?>" class="btn btn-xs btn-flat bg-navy">Edit</a>
                                              </td>
                                          </tr>
                                      <?php } ?>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                    </div>

                    <div class="info-box bg-yellow">
                        <div class="box-header">
                            <h3 class="box-title">Request</h3>
                        </div>

                        <div class="box-body">
                            <?php
                            // $result = mysql_query("select atr.*,ap.firstname,ap.lastname,ac.job_title,ap1.firstname as firstname_by,ap1.lastname as lastname_by 
                            //                       from agency_talent_request atr
                            //                       LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id 
                            //                       LEFT JOIN agency_profiles ap1 ON ap1.user_id = atr.request_by 
                            //                       LEFT JOIN agency_castings ac ON ac.casting_id = atr.casting_id
                            //                       where atr.request_status = 'pending'
                            //                     ");
                            // if (mysql_num_rows($result) > 0) {
                            ?>
                                <?php //while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { ?>
                                    <!-- <span class="info-box-number"><?php //echo $row['firstname_by'] . ' ' . $row['lastname_by']; ?></span> -->
                                    <!-- <span class="progress-description"> -->
                                        <?php //echo $row['request_instruction']; ?>
                                    <!-- </span> -->
                                <?php //} ?>
                                <!-- <a class="btn bg-navy btn-flat margin pull-right" href="request-list.php">See All Requests</a> -->
                            <?php //} else { ?>
                                <div class="text-center">No New Request.</div>
                            <?php //} ?>


                        </div>
                        <!-- /.info-box-content -->
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