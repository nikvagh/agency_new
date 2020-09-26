<!DOCTYPE html>
<html>
<head>
  <title>Casting Call</title>
 
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style1.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
 <link rel="stylesheet" href="css/font-awesome.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
</head>
<body>
<?php

@include('header.php');
?>  

<div class="container">
  
  <!-- Trigger the modal with a button -->
  

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-lg">
   <div class="modal-content">
   
  <div class="modal-body">


 <div class="call-popup">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
<div class="container-fluid">
<h2 class="pop-txt">SK-II VO CASTING [10/07/18]</h2>
<h3><strong>TAGS:</strong> <span class="txt">TV & VIDEO: MULTIMEDIA</span> <span class="txt2">SCRIPTED TV & VIDEO</span></h3>
<h4><strong>EXPIRES: </strong>November 16, 2018</h4>
<hr>

<h2 class="basic-txt">START DATE</h2>
<h3 class="basic-txt"><strong>Grant Wilfley Casting</strong></h3>
<p>Belle, casting dir.</p>
<hr> 

<h2 class="basic-txt">DESCRIPTION</h2>
<h3 class="basic-txt">Casting SAG_AFTRA and nonunion men and women to portray uscale restaurant goers for work on the STARZ show "Sweetbitter."</h3>
<hr>

<h2 class="basic-txt">CASTING DATE</h2>
<h3 class="basic-txt">Shoot dates are Oct.23 & 24 (matching work; must be available both days) in Brooklyn, NY.</h3>
<hr>

<h2 class="basic-txt">UNION STATUS</h2>
<h3 class="basic-txt">Nonunion rate is $143/10 hrs. SAS-AFTRA BG rates apply to union members.</h3>
<hr>

<h2 class="basic-txt">LOCATION</h2>
<h3 class="basic-txt"><strong>Seeking talent from:</strong> NewYork,NY</h3>

</div>
</div>


  </div>
 <div class="container-fluid">      
<div class="view">
  <h2>VIEW ROLES</h2>
</div>

<div class="popup-box">

<h4><strong>DESCRIPTION:</strong></h4>
<p>GWC is now seeking non-union talent to be Extras in the film. According to Comicbook.com, the upcoming Joker movie is filming under the name “Romeo”. According
to reports, the upcoming feature film will center around…</p>

<a href="#" class="btn-profile">SUBMIT MY PROFILE</a>

</div>

<div class="popup-box">

<h4><strong>DESCRIPTION:</strong></h4>
<p>Casting Directors are looking for all types of actors and talent to fill both featured roles and background Extras in an upcoming film shooting locally. This is a non-union
opportunity to work in a production…</p>

<a href="#" class="btn-profile">SUBMIT MY PROFILE</a>

</div>

<div class="popup-box">

<h4><strong>DESCRIPTION:</strong></h4>
<p>Casting Directors are looking for all types of actors and talent to fill both featured roles and background Extras in an upcoming film shooting locally. This is a non-union
opportunity to work in a production…</p>

<a href="#" class="btn-profile">SUBMIT MY PROFILE</a>

</div>



</div>

      </div>
    </div>
  </div>
</div>




<div class="stripe-img">
<div class="col-sm-12">

<h2 class="casting-txting">CASTING CALLS</h2>
</div>
</div>

<div class="container-fluid">
<div class="form-casting">
<div class="col-sm-12">
<form action="/action_page.php">
<div class="col-sm-6">
<div class="filter">
<h3>FILTER RESULTS</h3>
<div class="field-form">
Show: <input type="radio" name="" value=""> All Castings

  <input type="radio" name="" value=""> Matching My Profile
</div>

<div class="field-form">
Gender: <input type="checkbox" name="" value=""> Male
  <input type="checkbox" name="" value=""> Female
  <input type="checkbox" name="" value="" checked> Trans
  <input type="checkbox" name="" value="" checked> All
</div>

<div class="field-form">
Casting Location: <input type="radio" name="" value=""> My Location

  <input type="radio" name="" value=""> All Location
<div class="select">
  <select>
  <option value="volvo">Select Location</option>
  <option value="saab">xyz</option>
  <option value="opel">abc</option>
  <option value="audi">abcd</option>
</select>
<div class="exclusive"><p><input type="checkbox" name="" value=""> Exclude jobs calling for Nationwide/worldwide submissions</p></div>
</div>
</div>



</div>
</div>
<div class="col-sm-6">
<div class="right-form">
<div class="union">
<h3>Union:</h3>
<select>
  <option value="volvo">SAF-AFTRA</option>
  <option value="saab">xyz</option>
  <option value="opel">abc</option>
  <option value="audi">abcd</option>
</select>

</div>
<div class="union">
  <h3>Job Type:</h3>
  <select>
  <?php

        $sql = "SELECT job_type FROM agency_castings_drop_job ORDER BY job_type";

        $result = mysql_query($sql);

        $num_results = mysql_num_rows($result);

        if($num_results > 0) {
          while($row = sql_fetchrow($result)) {

                $job = $row['job_type']; ?>
                <option value="<?= $job ?>"><?= $job ?></option>

        <?php }} ?>
</select>

</div>
<div class="search-btn">
  <a href="#">Search</a>
</div>
</div>

</div>  

</form>
</div>
</div>
</div>



<div class="jobs about-jobs">
  <div class="container">
<div class="col-sm-12">
 <div class="castingflow">
<div class="col-sm-9">



  <?php

  $maxchars_title = 30;

  $maxchars_content = 76;

  $perpage = 10; 


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



  echo '<h3>'. $jobtitle . '<i class="fa fa-angle-down" aria-hidden="true" data-toggle="modal" data-target="#myModal"></i></h3>';

  echo '<h4 class="date">['. $postdate . ']</h4>';

  echo '<ul><li><img src="image/icon.png"></li>
        <li><h5>Female, aged 18 to 22</h5></li>
        </ul>';

  echo '<p>Job Type: <strong>'. $jobtype_html .'</strong>   Union:<strong>'.$jobunion_html.'</strong>  <br>Location:<strong>'.$location.'</strong></p>';

  echo '<a href="news.php?castingid="'.$castingid. '&amp;title='.urlencode($jobtitle).'>More Info&gt;</a>';

?>

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

<div class="col-sm-3">

  <h3 class="recent-txt"><strong>RECENTLY</strong> VIEWED</h3>
<div class="right-sidebar-casting">
  <div class="col-sm-12">
<div class="col-sm-4">
<img src="image/Mark logo.png">

</div>
<div class="col-sm-8">
<div class="edit">
<h4>ADVERTISING COMPANY <br>OPEN CASTING CALL</h4>
<h5>Multiple Roles</h5>
<p>New York,USA</p>
  </div>
</div>
</div>

<div class="col-sm-12">
<div class="col-sm-4">
<img src="image/Logo_1.png">

</div>
<div class="col-sm-8">
<div class="edit">
<h4>AMI ADVERTISING COMPANY<br>OPEN CASTING CALL</h4>
<h5>Comedy Roles</h5>
<p>Chicago, USA</p>
  </div>
</div>
</div>

 <div class="col-sm-12">
<div class="col-sm-4">
<img src="image/Logo_2.png">

</div>
<div class="col-sm-8">
<div class="edit">
<h4>ADVERTISING COMPANY <br>OPEN CASTING CALL</h4>
<h5>Multiple Roles</h5>
<p>New York,USA</p>
  </div>
</div>
</div>

<div class="col-sm-12">
<div class="col-sm-4">
<img src="image/artifx-logos-Final--vectors-ami-advertising_full.png">

</div>
<div class="col-sm-8">
<div class="edit">
<h4>AMI ADVERTISING COMPANY<br>OPEN CASTING CALL</h4>
<h5>Comedy Roles</h5>
<p>Chicago, USA</p>
  </div>
</div>
</div>

<div class="col-sm-12">
<div class="col-sm-4">
<img src="image/Logo_3.png">

</div>
<div class="col-sm-8">
<div class="edit">
<h4>ADVERTISING COMPANY <br>OPEN CASTING CALL</h4>
<h5>Multiple Roles</h5>
<p>New York,USA</p>
  </div>
</div>
</div>

<div class="col-sm-12">
<div class="col-sm-4">
<img src="image/Logo_4.png">

</div>
<div class="col-sm-8">
<div class="edit">
<h4>ADVERTISING COMPANY <br>OPEN CASTING CALL</h4>
<h5>Multiple Roles</h5>
<p>New York,USA</p>
  </div>
</div>
</div>



</div>
<div class="read blogs">
<a href="#" id="myModal">SEE MORE</a>
</div>
<h3 class="feat-txt">FEATURED</h3>
<div class="empty-box">
</div>


</div>

</div>
</div>
</div>









<?php

@include('footer.php');
?>
</body>
</html>




