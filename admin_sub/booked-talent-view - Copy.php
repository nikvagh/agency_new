<?php 
  include('header.php');
  // include('functions.php');
  include('../includes/agency_dash_functions.php');

  $notification = array();
  if(isset($_POST['talent_casting_id']) && $_POST['talent_casting_id'] != ""){

    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";

    $reminder_res = mysql_query("select * from agency_talent_casting atc
                      LEFT JOIN forum_users u ON u.user_id = atc.user_id 
                      LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
                      LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id
                      WHERE atc.talent_casting_id = ".$_POST['talent_casting_id']."
                    ");

    while ($row = mysql_fetch_array($reminder_res, MYSQL_ASSOC)) {

      $reminder_email = $row['user_email'];
      $subject = 'Booking Reminder';

      $msg = '<p>we are inform you that your booking information as following:</p>';
      $msg .= 'Date : '.$row['casting_date'].'<br/>';
      $msg .= 'Location : '.$row['location_casting'].'<br/>';
      $msg .= 'Description : '.$row['description'].'<br/>';
      $msg .= 'Pay Rate : '.$row['rate_day'].'<br/>';
      $msg .= 'Usage Rate : '.$row['rate_usage'].'<br/>';
      $msg .= 'Usage Term : '.$row['usage_time'].'<br/>';
      $msg .= 'Usage Area : '.$row['usage_location'].'<br/>';

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

      // echo  "select * from agency_talent_casting atc
      //                 LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
      //                 LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id 
      //                 WHERE atc.user_id = ".$user_id."
      //               ";
      $result = mysql_query("select * from agency_talent_casting atc
                      LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
                      LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id 
                      WHERE atc.user_id = ".$user_id." AND atc.booked = 'Y'
                    ");

  }
?>

<div id="page-wrapper">
  <!-- <div class=""> -->
      <!-- Page Heading -->
    <div class="well" id="main">  

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

      <div class="container-fluid">

        <?php if (mysql_num_rows($result) > 0) { ?>
            <div class="col-sm-12">
              <h3>Talent are Booked for following castings</h3>
            </div>
            <?php while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { ?>
                <div class="col-sm-4">
                  <div class="card">
                    Project : <?php echo $row['job_title']; ?><br/>
                    Role Name : <?php echo $row['name']; ?><br/>
                    Booking Status : <?php if($row['booked'] == "Y"){ echo "Yes"; }else{ echo "No"; } ?>
                    <hr/>

                    Date : <?php echo $row['casting_date']; ?><br/>
                    <!-- Time : <?php //echo $row['job_title']; ?><br/> -->
                    Location : <?php echo $row['location_casting']; ?><br/>
                    Description : <?php echo $row['description']; ?><br/>
                    Pay Rate: <?php echo $row['rate_day']; ?><br/>
                    Usage Rate: <?php echo $row['rate_usage']; ?><br/>
                    Usage Term: <?php echo $row['usage_time']; ?><br/>
                    Usage Area: <?php echo $row['usage_location']; ?><br/>
                    <hr/>

                    <form action="" method="post" class="form-inline">
                      <input type="hidden" name="talent_casting_id" value="<?php echo $row['talent_casting_id']; ?>"/>
                      <button type="submit" class="btn btn-info">Send Reminder</button>
                    </form>

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