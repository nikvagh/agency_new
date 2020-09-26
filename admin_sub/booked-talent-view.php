<?php 
  $page_selected = "booked";
  include('header.php');
  // include('functions.php');
  include('../includes/agency_dash_functions.php');

  $notification = array();
  if(isset($_POST['talent_casting_id']) && $_POST['talent_casting_id'] != ""){

    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
    $cond = "";
    if($_POST['booking'] == "casting"){
      $cond = " AND atc.casting_booking = 'Y'";
    }elseif($_POST['booking'] == "confirmed"){
      $cond = " AND atc.confirm_booking = 'Y'";
    }

    // $reminder_res = mysql_query("select * from agency_talent_casting atc
    //                   LEFT JOIN forum_users u ON u.user_id = atc.user_id 
    //                   LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
    //                   LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id
    //                   WHERE atc.talent_casting_id = ".$cond."
    //                 ");

    $reminder_res = mysql_query("select * from agency_talent_casting atc
                      LEFT JOIN forum_users u ON u.user_id = atc.user_id 
                      LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
                      LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id
                      WHERE atc.talent_casting_id = ".$_POST['talent_casting_id']." ".$cond."
                    ");

    while ($row = mysql_fetch_assoc($reminder_res)) {

      $reminder_email = $row['user_email'];
      $subject = 'Booking Reminder';

      $msg = '<p>we are inform you that your booking information as following:</p>';
      $msg .= 'Date : '.$row['date'].'<br/>';
      $msg .= 'Time : '.$row['time'].'<br/>';
      $msg .= 'Location : '.$row['location'].'<br/>';
      $msg .= 'Description : '.$row['description'].'<br/>';
      $msg .= 'Pay Rate : '.$row['pay_rate'].'<br/>';
      $msg .= 'Usage Rate : '.$row['usage_rate'].'<br/>';
      $msg .= 'Usage Term : '.$row['usage_term'].'<br/>';
      $msg .= 'Usage Area : '.$row['usage_area'].'<br/>';

      if(send_mail($reminder_email,$subject,$msg)){
        $notification['success'] = "Reminder sent successfully.";
      }else{
        $notification['error'] = "Reminder sending failed!";
      }

    }
    $_POST = array();
    
    // exit;
  }
?>

<?php 
    if($_GET['user_id']){

      $user_id = $_GET['user_id'];


      // $result = mysql_query("select * from agency_talent_casting atc
      //                 LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
      //                 LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id 
      //                 WHERE atc.user_id = ".$user_id." AND atc.booked = 'Y'
      //               ");
      $cond = "";
      if($_GET['booking'] == "casting"){
        $cond = " AND atc.casting_booking = 'Y'";
      }elseif($_GET['booking'] == "confirmed"){
        $cond = " AND atc.confirm_booking = 'Y'";
      }

      // echo "select atc.*,ac.job_title,ac.casting_id,acr.name from agency_talent_casting atc
      //                       LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
      //                       LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id 
      //                       WHERE atc.user_id = ".$user_id." ".$cond."
      //               ";

      $result = mysql_query("select atc.*,ac.job_title,ac.casting_id,acr.name from agency_talent_casting atc
                            LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
                            LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id 
                            WHERE atc.user_id = ".$user_id." ".$cond."
                    ");

  }
?>

<div id="page-wrapper">
  <!-- <div class=""> -->
      <!-- Page Heading -->
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

        <h3>Bookings</h3>
        <div class="row">
          <?php if (mysql_num_rows($result) > 0) { ?>
              <?php while ($row = mysql_fetch_assoc($result)) { ?>
                  <div class="col-md-3">
                    <div class="box box-theme">
                      <div class="box-body">
                        <!-- Project : <?php echo $row['job_title']; ?><br/>
                        Role Name : <?php echo $row['name']; ?><br/>
                        Casting Booking Status : <?php if($row['casting_booking'] == "Y"){ echo "Yes"; }else{ echo "No"; } ?><br/>
                        Confirmed Booking Status : <?php if($row['confirm_booking'] == "Y"){ echo "Yes"; }else{ echo "No"; } ?><br/> -->
                        <!-- <hr/> -->

                        Date : <?php echo $row['date']; ?><br/>
                        Time : <?php echo $row['time']; ?><br/>
                        Location : <?php echo $row['location']; ?><br/>
                        Description : <?php echo $row['description']; ?><br/>
                        Pay Rate: <?php echo $row['pay_rate']; ?><br/>
                        Usage Rate: <?php echo $row['usage_rate']; ?><br/>
                        Usage Term: <?php echo $row['usage_term']; ?><br/>
                        Usage Area: <?php echo $row['usage_area']; ?><br/>
                        <hr/>

                        <form action="" method="post" class="form-inline">
                          <input type="hidden" name="talent_casting_id" value="<?php echo $row['talent_casting_id']; ?>" />
                          <input type="hidden" name="booking" value="<?php echo $_GET['booking']; ?>" />
                          <button type="submit" class="btn btn-theme btn flat">Send Reminder</button>
                        </form>

                      </div>
                    </div>
                  </div>
              <?php } ?>
          <?php }else{ ?>
             Talent not scheduled with any casting.
          <?php } ?>
        </div>

    </div>
  <!-- </div> -->
</div>


<?php include('footer_js.php'); ?>
<script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>
<?php include('footer.php'); ?>