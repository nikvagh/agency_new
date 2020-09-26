<?php
include('header_code.php');
?>

<!DOCTYPE html>
<html>

<head>
  <title>Casting Calls</title>
  <?php include('head.php'); ?>
  <?php include('common_css.php'); ?>
</head>

<body>
  <?php 
    include('header.php'); 
    if(isset($_GET['casting_id'])){
      $casting_id = $_GET['casting_id'];
      $casting_q = mysql_query("select ac.*,ap.firstname,ap.lastname from agency_castings ac
                                LEFT JOIN agency_profiles as ap ON ap.user_id = ac.casting_director
                                WHERE ac.casting_id =".$casting_id." GROUP BY casting_id
                              ");
      $casting = array();
      if (mysql_num_rows($casting_q) > 0) {
        while ($row = mysql_fetch_assoc($casting_q)) {
          $casting = $row;
        }
      }
    }

    // echo "<pre>";
    // print_r($casting);
    // echo "</pre>";
  ?>

  <!-- <div class="container-fluid breadcrumb-box text-center">
    <ul class="btn-group breadcrumb">
      <li><a href="<?php //echo $base_url; ?>" class="">Home</a></li>
      <li><a class="">Casting Calls</a></li>
  </div> -->

  <div style="" class="middle_cross">
    <div class="container-fluid">
      <h1 class="color-white">Casting Calls</h1>
    </div>
  </div>

  <div class="container-fluid filter-box"> 
    <div class="col-sm-12">
      <?php
        // $cond = "";
        // $sql_list = "select ac.*,ap.firstname,ap.lastname from agency_castings ac 
        // LEFT JOIN agency_profiles ap ON ac.casting_director = ap.user_id
        // LEFT JOIN agency_castings_unions acu ON ac.casting_id = acu.casting_id
        // LEFT JOIN agency_castings_jobtype acj ON ac.casting_id = acj.casting_id
        // LEFT JOIN agency_castings_roles acr ON ac.casting_id = acr.casting_id
        // LEFT JOIN agency_castings_roles_vars acrv ON acr.role_id = acrv.role_id
        // WHERE live = 1 AND deleted = 0 AND casting_date >= CURDATE() ".$cond." 
        // GROUP BY ac.casting_id";
        // $result = mysql_query($sql_list);
        // if (mysql_num_rows($result) > 0) {
        //   while ($row = mysql_fetch_assoc($result)) {
            
      ?>

        <div class="row">
          <div class="col-sm-12" style="border: 2px solid gray;margin-bottom: 20px;box-shadow: 0px 0px 5px gray;">

            <div class="row">
              <div class="col-sm-12">
                <h1 class="text-uppercase weight-bold color-theme"><?php echo $casting['job_title']; ?> <?php echo date('[m/d/y]', strtotime($row['post_date'])); ?></h1>
                <h4 class="weight-normal">TAGS: <?php $allTags = explode(',',$casting['tags']); foreach($allTags as $val){ echo '<span class="label label-default">'.$val.'</span> '; } ?></h4>
                <h4 class="weight-normal">EXPIRES: <?php echo date('M d, Y', strtotime($casting['casting_date'])); ?></h4>
                <hr/>

                <!-- <h3>START DATE</h3>
                <h4><?php //echo $casting['company']; ?>  </h4>
                <hr/> -->
                
                <h3>CASTING DATE</h3>
                <h4><?php echo date('M d, Y', strtotime($casting['casting_date'])); ?>  </h4>
                <hr/>

                <h3>UNION STATUS</h3>
                <?php 
                    $castings_unions_q = mysql_query("select * from agency_castings_unions
                                              WHERE casting_id =".$casting_id."
                                            ");
                    if (mysql_num_rows($castings_unions_q) > 0) {
                      while ($unions_row = mysql_fetch_assoc($castings_unions_q)) {
                        echo '<span class="label label-primary">'.$unions_row['union_name'].'</span> ';
                      }
                    }
                ?>
                <hr/>

                <h3>LOCATION</h3>
                <h4><strong>Seeking Talent From </strong><?php echo $casting['location_casting']; ?>  </h4>
                <!-- <hr/> -->
              </div>
            </div>

          </div>

          <div class="col-sm-12 bg-theme" style="margin-bottom: 20px;">
            <h3 class="color-white weight-bold">VIEW ROLES</h3>
          </div>

            <?php 
                $casting_role_q = mysql_query("select * from agency_castings_roles
                                          WHERE casting_id =".$casting_id."
                                        ");
                if (mysql_num_rows($casting_role_q) > 0) {
                  while ($role_row = mysql_fetch_assoc($casting_role_q)) {

                    $age_lower_matched[] = $role_row['age_lower'];
                    $age_upper_matched[] = $role_row['age_upper'];
                    $height_lower_matched[] = $role_row['height_lower'];
                    $height_upper_matched[] = $role_row['height_upper'];
            ?>
                <div class="col-sm-12" style="border: 2px solid gray;margin-bottom: 20px;box-shadow: 0px 0px 5px gray;padding-bottom:20px">

                  <h2 class="text-uppercase weight-bold text-primary"><?php echo $role_row['name']; ?> </h2>
                  <h4>DESCRIPTION</h4>
                  <p><?php echo $role_row['description']; ?></p>
                  <a href="talent/casting-view.php?casting_id=<?php echo $casting_id; ?>" class="btn-xs btn-theme btn-flat color-white" style="text-decoration:none">SUBMIT MY PROFILE</a>

                </div>
            <?php
                }
              }
          ?>


        </div>
      <?php 
        //   }
        // }
      ?>
    </div>

  </div>

  <span class="clearfix"></span>
  <br/>
  
  <?php
  include('footer_js.php');
  include('footer.php');
  ?>

</body>

</html>