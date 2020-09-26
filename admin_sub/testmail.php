<?php include('header.php'); ?>

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
                      WHERE atc.user_id = ".$user_id."
                    ");

  }
?>

<div id="page-wrapper">
  <!-- <div class=""> -->
      <!-- Page Heading -->
    <div class="well" id="main">

      <div class="container-fluid">

        <?php if (mysql_num_rows($result) > 0) { ?>
            <div class="col-sm-12">
              <h3>Talent are scheduled for following castings</h3>
            </div>
            <?php while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { ?>
                <div class="col-sm-4">
                  <div class="card">
                    Project : <?php echo $row['job_title']; ?><br/>
                    Role Name : <?php echo $row['name']; ?><br/>
                    Booking Status : <?php if($row['booked'] == "Y"){ echo "Yes"; }else{ echo "No"; } ?>

                    <?php if($row['booked'] == "Y"){ ?>
                      <hr/>

                      Date : <?php echo $row['casting_date']; ?><br/>
                      <!-- Time : <?php //echo $row['job_title']; ?><br/> -->
                      Location : <?php echo $row['location_casting']; ?><br/>
                      Description : <?php echo $row['description']; ?><br/>
                      Pay Rate: <?php echo $row['rate_day']; ?><br/>
                      Usage Rate: <?php echo $row['rate_usage']; ?><br/>
                      Usage Term: <?php echo $row['usage_time']; ?><br/>
                      Usage Area: <?php echo $row['usage_location']; ?><br/>

                    <?php } ?>
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
<?php include('footer.php'); ?>