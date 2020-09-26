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
  <?php include('header.php'); ?>

  <?php 
    if(isset($_POST['submit']) && $_POST['submit'] == "Search"){
      // echo "<pre>";
      // print_r($_POST);

      if(isset($_POST['filter_casting'])){
        $_SESSION['casting_filter']['filter_casting'] = $_POST['filter_casting'];
      }

      if(isset($_POST['filter_gender'])){
        $_SESSION['casting_filter']['filter_gender'] = $_POST['filter_gender'];
      }

      if(isset($_POST['filter_location'])){
        $_SESSION['casting_filter']['filter_location'] = $_POST['filter_location'];
      }
      $_SESSION['casting_filter']['filter_show_location'] = $_POST['filter_show_location'];
      $_SESSION['casting_filter']['filter_union'] = $_POST['filter_union'];
      $_SESSION['casting_filter']['filter_job_type'] = $_POST['filter_job_type'];
    }

    if(isset($_POST['submit']) && $_POST['submit'] == "Clear"){
      // echo "111";
      unset($_SESSION['casting_filter']);
    }

    // echo "<pre>";
    // print_r($_SESSION);

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
    <div class="bg-theme container-fluid">
      
      <form method="post">
        <div class="col-md-12">
          <h3 class="color-white">FILTER RESULTS</h3>
        </div>
        <div class="col-md-7">
          <div class="col-sm-12 bg-white line-box">
            <label class="weight-normal">Show: </label> 
            <label class="weight-normal"><input type="radio" name="filter_casting" value="all_casting" <?php if(isset($_SESSION['casting_filter']['filter_casting']) && $_SESSION['casting_filter']['filter_casting'] == 'all_casting'){ echo "checked"; } ?>/> All Castings</label> &nbsp;&nbsp;&nbsp;&nbsp;
            <label class="weight-normal"><input type="radio" name="filter_casting" value="matching_my_profile" <?php if(isset($_SESSION['casting_filter']['filter_casting']) && $_SESSION['casting_filter']['filter_casting'] == 'matching_my_profile'){ echo "checked"; } ?>/> Matching My Profile</label> &nbsp;&nbsp;&nbsp;&nbsp;
          </div>
          <div class="col-sm-12 bg-white line-box">
              <label class="weight-normal">Gender: </label>
              <label class="weight-normal"><input type="checkbox" name="filter_gender[]" value="M" <?php if(isset($_SESSION['casting_filter']['filter_gender']) && in_array('M',$_SESSION['casting_filter']['filter_gender'])){ echo "checked"; } ?>/> Male</label> &nbsp;&nbsp;&nbsp;&nbsp;
              <label class="weight-normal"><input type="checkbox" name="filter_gender[]" value="F" <?php if(isset($_SESSION['casting_filter']['filter_gender']) && in_array('F',$_SESSION['casting_filter']['filter_gender'])){ echo "checked"; } ?>/> Female</label> &nbsp;&nbsp;&nbsp;&nbsp;
              <label class="weight-normal"><input type="checkbox" name="filter_gender[]" value="Transgender" <?php if(isset($_SESSION['casting_filter']['filter_gender']) && in_array('Transgender',$_SESSION['casting_filter']['filter_gender'])){ echo "checked"; } ?>/> Trans</label> &nbsp;&nbsp;&nbsp;&nbsp;
              <label class="weight-normal"><input type="checkbox" name="filter_gender[]" value="All" <?php if(isset($_SESSION['casting_filter']['filter_gender']) && in_array('All',$_SESSION['casting_filter']['filter_gender'])){ echo "checked"; } ?>/> All</label> &nbsp;&nbsp;&nbsp;&nbsp;
          </div>
          <div class="col-sm-12 bg-white line-box">
            <label class="weight-normal">Casting Location: </label>
            <label class="weight-normal"><input type="radio" name="filter_location" value="my_location" <?php if(isset($_SESSION['casting_filter']['filter_location']) && $_SESSION['casting_filter']['filter_location'] == 'my_location'){ echo "checked"; } ?>/> My Location </label> &nbsp;&nbsp;&nbsp;&nbsp;
            <label class="weight-normal"><input type="radio" name="filter_location" value="all_location" <?php if(isset($_SESSION['casting_filter']['filter_location']) && $_SESSION['casting_filter']['filter_location'] == 'all_location'){ echo "checked"; } ?>/> All Locations </label> &nbsp;&nbsp;&nbsp;&nbsp;
            <select class="form-control-custom" name="filter_show_location">
              <option value="">Select Location</option>
              <?php foreach ($locationarray as $key => $val) { ?>
                <option value="<?php echo $val; ?>" <?php if(isset($_SESSION['casting_filter']['filter_show_location']) && $_SESSION['casting_filter']['filter_show_location'] == $val){ echo "selected"; } ?>><?php echo $val; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="col-md-5">
          <div class="col-sm-12 bg-white line-box">
            <label class="weight-normal">Unions: </label>
            <select class="form-control-custom" name="filter_union">
              <option value="">Select Location</option>
              <?php foreach ($jobunionarray as $key => $val) { ?>
                <option value="<?php echo $val; ?>" <?php if(isset($_SESSION['casting_filter']['filter_union']) && $_SESSION['casting_filter']['filter_union'] == $val){ echo "selected"; } ?>><?php echo $val; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-12 bg-white line-box">
            <label class="weight-normal">Job Type: </label>
            <select class="form-control-custom" name="filter_job_type">
              <option value="">Select Job Type</option>
              <?php foreach ($jobtypearray as $key => $val) { ?>
                <option value="<?php echo $val; ?>" <?php if(isset($_SESSION['casting_filter']['filter_job_type']) && $_SESSION['casting_filter']['filter_job_type'] == $val){ echo "selected"; } ?>><?php echo $val; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="col-sm-12 line-box text-right">
            <input type="submit" name="submit" value="Clear" class="btn btn-filter btn-flat"/>
            <input type="submit" name="submit" value="Search" class="btn btn-filter btn-flat"/>
          </div>
        </div>

      </form>

    </div>
  </div>


  <div class="container-fluid filter-box"> 
    <div class="col-sm-9">
      <?php
        $cond = "";
              
        // echo "<pre>";
        // print_r($_SESSION['casting_filter']);
        // echo "</pre>";

        if(isset($_SESSION['casting_filter']['filter_gender']) && !empty($_SESSION['casting_filter']['filter_gender']) ){
          if(!in_array('All',$_SESSION['casting_filter']['filter_gender'])){
            $gender_ary = array();$gender_str = "";
            foreach($_SESSION['casting_filter']['filter_gender'] as $gender){
              $gender_ary[] = " acrv.var_type = 'gender' AND acrv.var_value = '".$gender."' ";
            }
            if(!empty($gender_ary)){
              $gender_str = implode(' OR ',$gender_ary);
              $gender_str = ' AND ( '.$gender_str.' ) ';
            }
            $cond = $gender_str;
          }
        } 

        if(isset($_SESSION['casting_filter']['filter_show_location']) && $_SESSION['casting_filter']['filter_show_location'] != ""){
          $cond .= " AND ac.location_casting = '".$_SESSION['casting_filter']['filter_show_location']."' ";
        }

        if(isset($_SESSION['casting_filter']['filter_union']) && $_SESSION['casting_filter']['filter_union'] != ""){
          $cond .= " AND acu.union_name = '".$_SESSION['casting_filter']['filter_union']."' ";
        }

        if(isset($_SESSION['casting_filter']['filter_job_type']) && $_SESSION['casting_filter']['filter_job_type'] != ""){
          $cond .= " AND acj.jobtype = '".$_SESSION['casting_filter']['filter_job_type']."' ";
        }
        
        
        $sql_list = "select ac.*,ap.firstname,ap.lastname from agency_castings ac 
        LEFT JOIN agency_profiles ap ON ac.casting_director = ap.user_id
        LEFT JOIN agency_castings_unions acu ON ac.casting_id = acu.casting_id
        LEFT JOIN agency_castings_jobtype acj ON ac.casting_id = acj.casting_id
        LEFT JOIN agency_castings_roles acr ON ac.casting_id = acr.casting_id
        LEFT JOIN agency_castings_roles_vars acrv ON acr.role_id = acrv.role_id
        WHERE live = 1 AND deleted = 0 AND casting_date >= CURDATE() ".$cond." 
        GROUP BY ac.casting_id";
        $result = mysql_query($sql_list);
        if (mysql_num_rows($result) > 0) {
          while ($row = mysql_fetch_assoc($result)) {
            
      ?>

        <div class="row">
          <div class="col-sm-12" style="border: 2px solid gray;margin-bottom: 20px;box-shadow: 0px 0px 5px gray;">
            <!-- <div class="home-box"> -->

            <div class="row">
              <div class="col-sm-9">
                <h3 class="text-uppercase weight-bold text-info"><?php echo $row['job_title']; ?></h3>
                <h4 class="weight-bold text-success">Day Rate: <?php echo $row['rate_day']; ?></h4>
              </div>
              <div class="col-sm-3 text-right">
                <h4><?php echo date('[m/d/y]', strtotime($row['post_date'])); ?></h4>
              </div>
              <div class="col-sm-9">
                <p>Job Type: <strong>

                  <?php 
                      $job_type_q = mysql_query("select * from agency_castings_jobtype
                                                WHERE casting_id =".$row['casting_id']."
                                              ");
                      if (mysql_num_rows($job_type_q) > 0) {
                        while ($job_row = mysql_fetch_assoc($job_type_q)) {
                          echo '<span class="label label-default">'.$job_row['jobtype'].'</span> ';
                        }
                      }
                  ?>

                </strong> 
                
                <br/> <br/>
                
                
                Union:<strong> 
                  
                  <?php
                    $castings_unions_q = mysql_query("select * from agency_castings_unions
                                              WHERE casting_id =".$row['casting_id']."
                                            ");
                    if (mysql_num_rows($castings_unions_q) > 0) {
                      while ($unions_row = mysql_fetch_assoc($castings_unions_q)) {
                        echo '<span class="label label-default">'.$unions_row['union_name'].'</span> ';
                      }
                    } 
                  ?>
                    
                  <br/> <br/>

                Location:<strong> <?php echo $row['location_casting']; ?> </strong></p>
              </div>
              <div class="col-sm-3">
                <a href="casting-call-details.php?casting_id=<?php echo $row['casting_id']; ?>"><i class="fa fa-angle-down fa-2x"></i></a>
              </div>
            </div>
              
            <!-- </div> -->
          </div>
        </div>

      <?php 
          }
        }
      ?>
    </div>

    <div class="col-sm-3">
        <h3 class="text-center"><strong>Recently</strong> Viewed</h3>

        <div class="container-fluid">
            <div class="col-sm-12" style="border: 2px solid gray;margin-bottom: 20px;box-shadow: 0px 0px 5px gray;">
              <?php
                $cond = "";
                $sql_list = "select ac.*,ap.firstname,ap.lastname from agency_castings ac 
                LEFT JOIN agency_profiles ap ON ac.casting_director = ap.user_id
                LEFT JOIN agency_castings_unions acu ON ac.casting_id = acu.casting_id
                LEFT JOIN agency_castings_jobtype acj ON ac.casting_id = acj.casting_id
                LEFT JOIN agency_castings_roles acr ON ac.casting_id = acr.casting_id
                LEFT JOIN agency_castings_roles_vars acrv ON acr.role_id = acrv.role_id
                WHERE live = 1 AND deleted = 0 AND casting_date >= CURDATE() ".$cond." 
                GROUP BY ac.casting_id ORDER BY ac.casting_id DESC limit 5";
                $result = mysql_query($sql_list);
                if (mysql_num_rows($result) > 0) {
                  while ($row = mysql_fetch_assoc($result)) {  
              ?>
          
                <!-- <div class="home-box"> -->

                <div class="row">
                  <div class="col-sm-9">
                    <a><h4 class="text-uppercase weight-bold"><?php echo $row['job_title']; ?></h4></a>
                  </div>
                  <!-- <div class="col-sm-3 text-right">
                    <h4><?php echo date('[m/d/y]', strtotime($row['post_date'])); ?></h4>
                  </div> -->
                  <div class="col-sm-9">
                    <!-- <p>Job Type: <strong><?php echo $row['job_types']; ?></strong> Union:<strong> <?php echo $row['union_names']; ?></strong> <br/>
                    Location:<strong> <?php echo $row['location_casting']; ?> </strong></p> -->
                  </div>
                  <!-- <div class="col-sm-3">
                    <a href="#"><i class="fa fa-angle-down fa-2x"></i></a>
                  </div> -->
                </div>
                  
                <!-- </div> -->
              
              <?php 
                  } 
                }
              ?>

          </div>
        </div>

        <h3 class="text-center"><strong>FEATURED</strong></h3>
        <?php 
          $sql_new_talent = "SELECT * FROM forum_users fu 
                    LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id
                    WHERE account_type = 'talent'
                    ORDER BY ap.created_at DESC LIMIT 10";
          $result_new_talent = mysql_query($sql_new_talent);
        ?>
        <div class="container-fulid" style="padding:10px;">
          <?php while($row = sql_fetchrow($result_new_talent)) { ?>
            <!-- profile-view.php?user_id=<?php //echo $row['user_id']; ?> -->
            <a href="#" class="new-talent-img-box">
              <?php
                if(file_exists('uploads/users/' . $row['user_id'] . '/profile_pic/thumb/128x128_' . $row['user_avatar'])) {
                  $user_avatar = 'uploads/users/' . $row['user_id'] . '/profile_pic/thumb/128x128_' . $row['user_avatar'];
                }else{
                  $user_avatar = 'images/friend.gif';
                }
              ?>
              <img src="<?php echo $user_avatar; ?>" style="margin-bottom: 10px;"/>
            </a>
          <?php } ?>
        </div>
                      
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