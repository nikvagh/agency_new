<?php 
  $page = "requests";
  $page_selected = "requests";
  include('header.php');
  include('../includes/agency_dash_functions.php');

  $user_id = $_SESSION['user_id'];
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
            
            <div class="col-sm-4 col-xs-12">
                <?php
                  $booking_sql = "select atr.*,ap.firstname,ap.lastname,fu.user_avatar from agency_talent_request atr
                                    LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id
                                    LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
                                    WHERE ap.roster_id = ".$_SESSION['user_id']."
                                    AND atr.request_for = 'booking'
                                    AND atr.request_status = 'approve'
                                    AND atr.scheduled = 'N'
                                    AND atr.casting_id != 0
                                    AND atr.request_date >= CURDATE()
                                  ";
                  $booking_res = mysql_query($booking_sql);
                ?>
                <h3>Booking Requests (<?php echo mysql_num_rows($booking_res); ?>)</h3>
                <div class="box box-theme">
                  <div class="box-body">
                    <table class="datatable table table-responsive table-striped">
                      <tbody>
                        <?php
                          if (mysql_num_rows($booking_res) > 0) {
                            while ($row = mysql_fetch_assoc($booking_res)) {
                              ?>
                              
                              <tr>
                                  <td>
                                    <a href="casting-role.php?casting_id=<?php echo $row['casting_id']; ?>" class="" style="width: 50px;">
                                      <div class="col-md-3">
                                        <?php
                                          if(file_exists('../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar']) ){
                                            $img_casting = '../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar'];
                                          }else{
                                            $img_casting = '../images/friend.gif';
                                          }
                                        ?>
                                        <img src="<?php echo $img_casting; ?>" class="img-responsive verticle-middle" style="border-radius: 50px;height: 50px;width: 50px"/>
                                      </div>
                                      <div class="col-md-9">
                                        <?php echo 'Casting Rquest For '.$row['firstname'].' '.$row['lastname']; ?>
                                        <?php
                                          echo "<br/>";
                                          $days_remains = days_remain_string($row['request_date']);
                                          if($days_remains > 2){
                                            echo '<label> <i class="fa fa-clock-o"></i> '.$days_remains.' Days Remained</label>';
                                          }else{
                                            echo '<label class="text-danger"> <i class="fa fa-clock-o"></i> '.$days_remains.' Days Remained</label>';
                                          }
                                        ?>
                                      </div>
                                    </a>
                                  </td>
                              </tr>

                              <?php
                            }
                          }else{
                            ?>
                              <tr>
                                  <td>
                                    No Request Available
                                  </td>
                              </tr>
                            <?php
                          }
                        ?>
                      </tbody>
                    </table>

                  </div>
                </div>
            </div>

            <div class="col-sm-4 col-xs-12">
                <?php
                  $casting_sql = "select atr.*,ap.firstname,ap.lastname,fu.user_avatar from agency_talent_request atr
                                    LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id
                                    LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
                                    WHERE ap.roster_id = ".$_SESSION['user_id']."
                                    AND atr.request_for = 'casting'
                                    AND atr.request_status = 'approve'
                                    AND atr.scheduled = 'N'
                                    AND atr.casting_id != 0 
                                  ";
                  $casting_res = mysql_query($casting_sql);
                ?>
                <h3>Casting Requests (<?php echo mysql_num_rows($casting_res); ?>)</h3>
                <div class="box box-theme">
                  <div class="box-body">
                    <table class="datatable table table-responsive table-striped">
                      <tbody>
                        <?php
                          if (mysql_num_rows($casting_res) > 0) {
                            while ($row = mysql_fetch_assoc($casting_res)) {
                              ?>
                              
                              
                                <tr>
                                    <td>
                                      <a href="casting-role.php?casting_id=<?php echo $row['casting_id']; ?>" class="" style="width: 50px;">
                                        <div class="col-md-3">
                                          <?php
                                            if(file_exists('../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar']) ){
                                              $img_casting = '../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar'];
                                            }else{
                                              $img_casting = '../images/friend.gif';
                                            }
                                          ?>
                                          <img src="<?php echo $img_casting; ?>" class="img-responsive verticle-middle" style="border-radius: 50px;height: 50px;width: 50px"/>
                                        </div>
                                        <div class="col-md-9">
                                          <?php echo 'Casting Rquest For '.$row['firstname'].' '.$row['lastname']; ?>
                                          <?php
                                            echo "<br/>";
                                            $days_remains = days_remain_string($row['request_date']);
                                            if($days_remains > 2){
                                              echo '<label> <i class="fa fa-clock-o"></i> '.$days_remains.' Days Remained</label>';
                                            }else{
                                              echo '<label class="text-danger"> <i class="fa fa-clock-o"></i> '.$days_remains.' Days Remained</label>';
                                            }
                                          ?>
                                        </div>
                                      </a>
                                    </td>
                                </tr>

                              <?php
                            }
                          }else{
                            ?>
                              <tr>
                                  <td>
                                    No Request Available
                                  </td>
                              </tr>
                            <?php
                          }
                        ?>
                      </tbody>
                    </table>

                  </div>
                </div>
            </div>

            <div class="col-sm-4 col-xs-12">
                <?php
                    $comp_sql = "select atr.*,ap.firstname,ap.lastname,fu.user_avatar from agency_talent_request atr
                                      LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id
                                      LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
                                      WHERE ap.roster_id = ".$_SESSION['user_id']."
                                      AND atr.request_status = 'approve'
                                      AND atr.scheduled = 'Y'
                                      AND atr.casting_id != 0
                                    ";
                    $comp_res = mysql_query($comp_sql);
                ?>
                <h3>Completed (<?php echo mysql_num_rows($comp_res); ?>)</h3>
                <div class="box box-theme">
                  <div class="box-body">
                    <table class="datatable table table-responsive table-striped">
                      <tbody>
                        <?php
                          if (mysql_num_rows($comp_res) > 0) {
                            while ($row = mysql_fetch_assoc($comp_res)) {
                              ?>
                              
                              <tr>
                                  <td>
                                    <a href="casting-role.php?casting_id=<?php echo $row['casting_id']; ?>" class="" style="width: 50px;">
                                      <div class="col-md-3">
                                        <?php
                                          if(file_exists('../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar']) ){
                                            $img_casting = '../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar'];
                                          }else{
                                            $img_casting = '../images/friend.gif';
                                          }
                                        ?>
                                        <img src="<?php echo $img_casting; ?>" class="img-responsive verticle-middle" style="border-radius: 50px;height: 50px;width: 50px"/>
                                      </div>
                                      <div class="col-md-9">
                                        <?php echo 'Casting Rquest For '.$row['firstname'].' '.$row['lastname']; ?>
                                        <?php
                                          echo "<br/>";
                                          $days_remains = days_remain_string($row['request_date']);
                                          if($days_remains > 2){
                                            echo '<label> <i class="fa fa-clock-o"></i> '.$days_remains.' Days Remained</label>';
                                          }else{
                                            echo '<label class="text-danger"> <i class="fa fa-clock-o"></i> '.$days_remains.' Days Remained</label>';
                                          }
                                        ?>
                                      </div>
                                    </a>
                                  </td>
                              </tr>

                              <?php
                            }
                          }else{
                            ?>
                              <tr>
                                  <td>
                                    No Request Available
                                  </td>
                              </tr>
                            <?php
                          }
                        ?>
                      </tbody>
                    </table>

                  </div>
                </div>

            </div>

        </div>

    </div>
</div>


<?php include('footer_js.php'); ?>

<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> -->

<script type="text/javascript">
  // $('.datatable').DataTable({
  //       "order": [[ 0, "desc" ]],
  //       'columnDefs': [{
  //       'targets': [4], /* column index */
  //       'orderable': false, /* true or false */
  //   }]
  // });
</script>
<?php include('footer.php'); ?>