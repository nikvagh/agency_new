<?php 
  $page = "roster";
  $page_selected = "roster";
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
            <div class="col-sm-8 col-xs-12">
                <h2>Roster</h2>
                <div class="box box-theme">
                  <div class="box-body">
                    <table class="datatable table table-responsive table-striped">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>Name</th>
                          <th>Last Activity</th>
                          <th>Email</th>
                          <th>Phone</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $cond = "";

                          $sql_list = "select fu.user_email,fu.user_avatar,fu.user_lastvisit,fu.user_lastmark,ap.* from agency_profiles ap 
                              LEFT JOIN forum_users fu ON ap.user_id = fu.user_id
                              WHERE roster_id = ".$user_id." AND account_status = 'open' ".$cond." 
                              "; 

                          $result = mysql_query($sql_list);
                          if (mysql_num_rows($result) > 0) {
                            while ($row = mysql_fetch_assoc($result)) {
                              echo '<tr>';
                              echo '<td>'.$row['user_id'].'</td>';
                              ?>
                                <td>
                                  <?php
                                    if(file_exists('../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar']) ){
                                      $img_roster = '../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar'];
                                    }else{
                                      $img_roster = '../images/friend.gif';
                                    }
                                  ?>
                                  <!-- <a class=""> -->
                                    <img src="<?php echo $img_roster; ?>" class="img-responsive verticle-middle" width="50px" style="border-radius: 50px;height: 50px;"/>
                                    <?php echo $row['firstname'].' '.$row['lastname']; ?>
                                  <!-- </a> -->
                                  
                                </td>
                                <td>
                                  <?php
                                    $online_status = "";
                                    if($row['user_lastmark'] == 'N') { 
                                      if($row['user_lastvisit'] == 0){
                                      }else{
                                        $online_status = "count";
                                      }
                                    }else if($row['user_lastmark'] == 'Y'){
                                      $now = date('Y-m-d H:i:s');
                                      $login_diff = strtotime($now) - $row['user_lastvisit'];
                                      $login_hrs = round($login_diff / 3600); 
                                      if($login_hrs > 2){
                                        $online_status = "count";
                                      }else{
                                        $online_status = "online";
                                      }
                                    }
                                  ?>

                                  <?php if($online_status == "count"){ ?>
                                    <label> <i class="fa fa-clock-o"></i> <?php echo time_elapsed_string('@'.$row['user_lastvisit']); ?></label>
                                  <?php }else if($online_status == "online"){ ?>
                                    <label class="text-success"> <i class="fa fa-clock-o"></i> Online Now!</label>
                                  <?php } ?>
                                </td>
                              <?php
                              echo '<td>'.$row['user_email'].'</td>';
                              echo '<td>'.$row['phone'].'</td>';
                              echo '<td>';
                              // echo '<a href="casting-update.php?casting_id='.$row['casting_id'].'" class="btn btn-primary">Edit</a> ';
                              // echo '<a href="" class="btn btn-info btn-request" data-id="'.$row['casting_id'].'"><i class="fa fa-paper-plane"></i> Send Booking Request </a>&nbsp;';
                              ?>
                              <div class="btn-group">
                                <button type="button" class="btn btn- dropdown-toggle btn-lg" data-toggle="dropdown" aria-expanded="false">
                                  <i class="fa fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                  <li><a href="talent-edit.php?user_id=<?php echo $row['user_id']; ?>">Edit</a></li>
                                  <li><a href="../profile-view.php?user_id=<?php echo $row['user_id']; ?>">View</a></li>
                                  <!-- <li><a href="#">Dropdown link</a></li> -->
                                </ul>
                              </div>
                              <?php
                              // echo '<a href="casting-view.php?casting_id='.$row['casting_id'].'" class="btn btn-theme" data-id="'.$row['casting_id'].'"> View </a>';
                              echo '</td>';
                              echo '</tr>';
                            }
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
  $('.datatable').DataTable({
        "order": [[ 0, "desc" ]],
        'columnDefs': [{
        'targets': [4], /* column index */
        'orderable': false, /* true or false */
    }]
  });
</script>
<?php include('footer.php'); ?>