<?php 
$page_selected = "scheduled";
include('header.php'); 
include('../includes/agency_dash_functions.php');
?>

<?php 
    if($_GET['user_id']){

      $user_id = $_GET['user_id'];
      $talent = get_user_byId($user_id);

      // echo  "select * from agency_talent_casting atc
      //                 LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
      //                 LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id 
      //                 WHERE atc.user_id = ".$user_id."
      //               "; 

      // LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
      // LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id 

      // $result = mysql_query("select atc.*,ac.job_title,ac.casting_id,acr.name from agency_talent_casting atc
      //                       LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
      //                       LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id 
      //                       WHERE atc.user_id = ".$user_id."
      //               ");

      $get_book_audition_sql = "SELECT am.*,ap.*,fu.*,ac.*,acr.* FROM agency_mycastings am
                    LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
                    LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id
                    LEFT JOIN agency_profiles ap ON ap.user_id = am.user_id
                    LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
                    WHERE am.audition_list = 'Y'
                    AND am.user_id = ".$user_id."
                    GROUP BY am.submission_id";

      $get_book_audition_res = mysql_query($get_book_audition_sql);
  }
?>

<div id="page-wrapper">
  <!-- <div class=""> -->
      <!-- Page Heading -->
    <div class="" id="main">

      <h3><?php echo 'Schedule Of '.$talent['firstname'].' '.$talent['lastname']; ?></h3>
      <div class="row">
          <?php if (mysql_num_rows($get_book_audition_res) > 0) { ?>
              <?php while ($row = mysql_fetch_assoc($get_book_audition_res)) { ?>
                <div class="col-md-3">
                  <div class="box box-theme">
                    <div class="box-body">
                      Project : <?php echo $row['job_title']; ?><br/>
                      Role Name : <?php echo $row['name']; ?><br/>
                      Casting Booking Status : <?php if($row['audition_list'] == "Y"){ echo "Yes"; }else{ echo "No"; } ?><br/>
                      Confirmed Booking Status : <?php if($row['audition_book'] == "Y"){ echo "Yes"; }else{ echo "No"; } ?><br/>
                      <br/><br/>
                      <a href="casting-view.php?casting_id=<?php echo $row['casting_id']; ?>" class="btn btn-theme" target="_blank"> View Project</a>
                      <?php if($row['audition_list'] == "Y" && $row['audition_book'] == "Y"){ ?>
                        <hr/>

                        Date : <?php echo $row['location_casting']; ?><br/>
                        <!-- Time : <?php //echo $row['time']; ?><br/> -->
                        Location : <?php echo $row['location']; ?><br/>
                        Description : <?php echo $row['description']; ?><br/>
                        Pay Rate: <?php echo $row['rate_day']; ?><br/>
                        Usage Rate: <?php echo $row['rate_usage']; ?><br/>
                        Usage Term: <?php echo $row['usage_time']; ?><br/>
                        Usage Area: <?php echo $row['usage_location']; ?><br/>

                      <?php } ?>
                    </div>
                  </div>
                </div>
              <?php } ?>
          <?php }else{ ?>
            <div class="col-md-12">
              <div class="box box-theme">
                  <div class="box-body">
                    Talent have not Any schedule.
                  </div>
              </div>
            </div>
          <?php } ?>
      </div>

    </div>
  <!-- </div> -->
</div>


<?php include('footer_js.php'); ?>
<?php include('footer.php'); ?>