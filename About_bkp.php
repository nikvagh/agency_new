<!DOCTYPE html>
<html lang="en">
<head>
<title>About</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="style1.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
 <link rel="stylesheet" href="css/font-awesome.min.css">
  <script src="js/jquery.min.js"></script>
  <script type="text/javascript" src="includes/agency.js"></script>
  <script src="js/bootstrap.min.js"></script>
  
  </head>

  <body>
 <?php

@include('header.php');
?>  	


<div class="about-slider">
<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
      <li data-target="#myCarousel" data-slide-to="4"></li>
      <li data-target="#myCarousel" data-slide-to="5"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="image/2 Slider.jpg" alt="New York" width="1200" height="700">
        <div class="carousel-caption">
          <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2><hr>
          <h4>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</h4>
          <div class="apply">
          	<a href="#"><i class="fa fa-backward"></i><p>APPLY NOW</p><i class="fa fa-forward"></i></a>
          </div>
        </div>      
      </div>

      <div class="item">
        <img src="image/design-banner.jpg" alt="Chicago" width="1200" height="700">
        <div class="carousel-caption">
          <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2><hr>
          <h4>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</h4>
           <div class="apply">
          	<a href="#"><i class="fa fa-backward"></i><p>APPLY NOW</p><i class="fa fa-forward"></i></a>
          </div>
        </div>      
      </div>
    
      <div class="item">
        <img src="image/design-banner2.jpg" alt="Los Angeles" width="1200" height="700">
        <div class="carousel-caption">
          <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2><hr>
          <h4>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</h4>
           <div class="apply">
          	<a href="#"><i class="fa fa-backward"></i><p>APPLY NOW</p><i class="fa fa-forward"></i></a>
          </div>
        </div>      
      </div>

      <div class="item">
        <img src="image/design-banner.jpg" alt="Los Angeles" width="1200" height="700">
        <div class="carousel-caption">
          <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2><hr>
          <h4>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</h4>
           <div class="apply">
          	<a href="#"><i class="fa fa-backward"></i><p>APPLY NOW</p><i class="fa fa-forward"></i></a>
          </div>
        </div>      
      </div>

      <div class="item">
        <img src="image/design-banner1.jpg" alt="Los Angeles" width="1200" height="700">
        <div class="carousel-caption">
          <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2><hr>
          <h4>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</h4>
           <div class="apply">
          	 <a href="#"><i class="fa fa-backward"></i><p>APPLY NOW</p><i class="fa fa-forward"></i></a>
          </div>
        </div>      
      </div>

      
    </div>
</div>
</div>

   
  
   

<?php //include('showcase_long.php'); 

$maxchars_title = 30;

$maxchars_content = 76;

$perpage = 10; 

?>















<div class="trap">



<div class="trapezoid2">



  <div class="casting">



  <a class="call-button" href="#">CASTING CALL</a>



  <a class="call-button2" href="#">LATEST NEWS</a>



</div>



</div>



<div class="trapezoid3">



<div class="casting2">



<ul>



<div class="btn-group show-on-hover">

          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">

            LOCATION <span class="caret"></span>

          </button>

          <ul class="dropdown-menu" role="menu">

            <?php

            echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=&\');">ALL CASTINGS</a></li>';

            $loc = 'New York City Area';

            echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=' . $loc . '&\');">' . $loc . '</a></li>';

            $loc = 'Los Angeles/Southern Cal.';

            echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=' . $loc . '&\');">' . $loc . '</a></li>';

            $loc = 'Other';

            echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=' . $loc . '&\');">' . $loc . '</a></li>';

            ?>

         </ul>

        </div>

        <?php

        $sql = "SELECT job_type FROM agency_castings_drop_job ORDER BY job_type";

        $result = mysql_query($sql);

        $num_results = mysql_num_rows($result);

        if($num_results > 0) {

        ?>

        <div class="btn-group show-on-hover">

          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">

            JOB TYPE <span class="caret"></span>

          </button>

          <ul class="dropdown-menu" role="menu">

            <?php

              echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&\');">ALL CASTINGS</a></li>';

              while($row = sql_fetchrow($result)) {

                $job = $row['job_type'];

                echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&jobtype=' . $job . '&\');">' . $job . '</a></li>';

              }

            ?>

         </ul>

        </div>

        <?php

        }

          $sql = "SELECT union_name FROM agency_castings_drop_unions ORDER BY union_name";

          $result = mysql_query($sql);

          $num_results = mysql_num_rows($result);

          if($num_results > 0) {

        ?>

        <div class="btn-group show-on-hover">

          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">

            UNION <span class="caret"></span>

          </button>

          <ul class="dropdown-menu" role="menu">

           <?php

              echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&\');">ALL CASTINGS</a></li>';

              while($row = sql_fetchrow($result)) {

                $union = $row['union_name'];

                echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&union=' . $union . '&\');">' . $union . '</a></li>';

              }

            ?>

         </ul>

        </div>

      <?php } ?>





</ul>



</div>



</div>







</div>



<div class="home-jobs">

<div class="col-sm-12">

<div class="col-sm-7">

  <div id="castingdiv">

  <?php



// If the user is logged in an in a location, the default will be their location

$sql_location = '';

if(isset($_SESSION['user_id'])) {

  $location = user_location();

  if(in_array($location, $locationarray)) {

    $sql_location = "AND location_casting='$location'";

    $link_location = '&location=' . $location;

    $_SESSION['casting_location'] = $location; // need this for tracking changes in job type, this will be cleared in header

  }

}





$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM `agency_castings` WHERE deleted='0' AND live='1' $sql_location"),0);



$sql = "SELECT * FROM agency_castings WHERE deleted='0' AND live='1' $sql_location ORDER BY post_date DESC LIMIT $perpage";

$result=mysql_query($sql);

while($row = sql_fetchrow($result)) { ?>

  <div class="row">

  <div class="col-sm-1"></div>

  <div class="col-sm-11">

  <div class="row casting-post">

<?php



  $castingid = $row['casting_id'];

  $jobtitle = $row['job_title'];

  $location = $row['location_casting'];

  $postdate = date('m/d/y', strtotime($row['post_date']));

  $notes = strip_tags(stripslashes($row['notes']));

  if (strlen($notes) > $maxchars_content) {

    $notes = substr($notes,0,$maxchars_content) . '...';

    // $notes = preg_replace("/\s+[,\.!?\w-]*?$/",'....',$notes);

  }



  $jobtype_html = ''; // this is done this way to figure out the icon to be used before outputting the job type

  $jobicon = false; // flag for if the icon has been displayed yet

  $sql2 = "SELECT jobtype FROM agency_castings_jobtype WHERE casting_id='$castingid'";

  $result2 = mysql_query($sql2);

  $num_results = mysql_num_rows($result2);

  if($num_results > 0) {    

    while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {

      $jobtype = $row2['jobtype'];

      $jobtype_html .= $jobtype;

      if($num_results-- > 1) $jobtype_html .= ', ';        

    }    

  }



  $jobunion_html = '';//union

  $sql2 = "SELECT union_name FROM agency_castings_unions WHERE casting_id='$castingid'";

  $result2 = mysql_query($sql2);

  $num_results = mysql_num_rows($result2);

  if($num_results > 0) {

    while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {

      $jobunion_html .= $row2['union_name'];

      if($num_results-- > 1) $jobunion_html .= ', ';

    }

  }



  echo '<a href="news.php?castingid=' . $castingid . '&amp;title=' .  urlencode($jobtitle) . '"><h3>'. $jobtitle . '</h3></a>';

  echo '<h4 class="date">['. $postdate . ']</h4>';

  echo '<p>Job Type: <strong>'. $jobtype_html .'</strong>   Union:<strong>'.$jobunion_html.'</strong>  Location:<strong>'.$location.'</strong></p>';

  echo '<p>'.$notes.'</p>';

  echo '<a href="news.php?castingid="'.$castingid. '&amp;title='.urlencode($jobtitle).'>More Info&gt;</a>';

?>

</div>

</div>

</div>

<?php } 



if($total > $perpage) {

?>

  <div id="morecastings1">

    <div align="right" style="margin:20px">

      <a href="javascript:void(0)" style="font-size:14px; font-weight:bold">VIEW MORE CASTINGS</a>

    </div>

  </div>

<?php

}

?>

</div>

</div>



<?php include('newsfeed.php'); ?>

</div>

</div>

<?php

@include('footer.php');
?> 

  </body>
 
  </html>
  