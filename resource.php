<?php
include('header_code.php');
?>

<!DOCTYPE html>
<html>

<head>
  <title>Resources</title>
  <?php include('head.php'); ?>
  <?php include('common_css.php'); ?>
</head>

<body>
  <?php include('header.php'); ?>
  <div class="container-fluid breadcrumb-box text-center">
    <ul class="btn-group breadcrumb">
      <li><a href="<?php echo $base_url; ?>" class="">Home</a></li>
      <li><a class="">Resources</a></li>
  </div>
  </div>

  <!-- <div class="trap">
      <div class="trapezoid2">
        <div class="casting">
          <a class="call-button" href="#">NEW FEATURES:</a>
          <a class="call-button2" href="#">THE BASICS:</a>
        </div>
      </div>
      <div class="trapezoid3">
        <div class="casting2">

        </div>
      </div>
    </div> -->

  <div style="" class="right_cross_img_box">
    <div class="container-fluid">
      <h1>Resources</h1>
    </div>
  </div>

  <br/><br/><br/>
  <div class="conatiner-fluid border-box-wrapper sp-box">
    <div class="col-sm-12">

        <nav>
          <!-- <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist"> -->
            <ul class="nav nav-tabs nav-fill">
              <?php 
                $sp_category_sql = "SELECT * from agency_service_category";
                $sp_category_query = mysql_query($sp_category_sql);
                $scategory = array();
                while ($row = mysql_fetch_assoc($sp_category_query)) {
                  $scategory[] = $row;
                }
              ?>
              <?php foreach($scategory as $key=>$val){ ?>
                <li class="<?php if($key == 0){ echo 'active'; } ?>">
                  <a class="nav-item nav-link" data-toggle="tab" href="#nav-<?php echo $val['service_category_id']; ?>"><?php echo $val['service_category_name']; ?></a>
                </li>
              <?php } ?>
              <!-- <li class="active"><a class="nav-item nav-link" data-toggle="tab" href="#nav-home">Home</a></li>
              <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-profile" >Profile</a></li>
              <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-contact" >Contact</a></li>
              <li><a class="nav-item nav-link" data-toggle="tab" href="#nav-about" >About</a></li> -->
            </ul>
          <!-- </div> -->
        </nav>
        <div class="tab-content">
          <?php foreach($scategory as $key=>$val){ ?>
            <div class="tab-pane fade in <?php if($key == 0){ echo 'active'; } ?>" id="nav-<?php echo $val['service_category_id']; ?>">
              <?php 
                $sp_sql = "SELECT * from agency_service_provider WHERE FIND_IN_SET('".$val['service_category_name']."',service_category) AND status = 'approved' ";
                $sp_query = mysql_query($sp_sql);
                if (mysql_num_rows($sp_query) > 0) {
                  while ($row = mysql_fetch_assoc($sp_query)) {
                    ?>
                      <p>
                        Name : <?php echo $row['name']; ?><br/>
                        Website : <?php echo $row['website']; ?><br/>
                        Phone  : <?php echo $row['phone']; ?><br/>
                        Email  : <?php echo $row['email']; ?><br/>
                        Description Of Service : <?php echo $row['description_of_service']; ?><br/>
                      </p>
                      <?php 
                        if( $row['featured_photo'] != '' &&  file_exists('./uploads/featured_photo/' . $row['featured_photo'])){ ?>
                        <img src="<?php echo 'uploads/featured_photo/' . $row['featured_photo']; ?>" style="width: 100px;"/>
                      <?php } ?>
                      <div class="hr_box_theme"> <hr> </div>
                    <?php
                  }
                }else{
              ?>
                Not Found Service Provider
              <?php } ?>
            </div>
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