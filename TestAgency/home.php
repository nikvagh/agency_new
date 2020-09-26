<?php
$pagetitle = 'Home';
@include('includes/header.php');

if(!is_active() && !isset($_SESSION['user_id'])) {
	// now done with jquery
	// include('./includes/register_process.php');  // registration forms processing; only needed if not logged in

	$homeundermenu_out = showbox('homeundermenu_out');
	if($homeundermenu_out) {
		// echo '<div style="clear:both">' . $homeundermenu_out . '</div>';
	}

?>
<div style="clear:both; position:relative; height:284px">
<div style="position:absolute; top:0px; margin-left:-55px; width:900px; height:260px; overflow:hidden; border: 8px solid #FFFFFF; background-color:white;">
<?php
	// testimonials
	$testimonials = array();
	$max_testimonials = 10;
	$count = 0;
	
	for($i=1; $i <= $max_testimonials; $i++) {
		$this_testimonial = showbox('testimonials_' . $i);
		if($this_testimonial) {
			$testimonials[$count++] = $this_testimonial;
		}
	}

	$num_testimonials = sizeof($testimonials);
	
	// select random testimonial to show first:
	// $current = rand(0, $num_testimonials-1);
	$current = 0; // changed to not random
	$testimonial_width = 900;
	
	if($num_testimonials == 1) { // no need for divs if only one testimonial
		echo $testimonials[0];
	} else {
		echo '<script> var current_testimonial = ' . ($current + 1) . '; var freezeshow = false;</script>';
		echo '<div id="testimonial_list" style="position:absolute; margin-left:-' . $current * $testimonial_width . 'px; height:260px; width:' . $num_testimonials * $testimonial_width . 'px">';
		foreach($testimonials as $t) {
			echo '<div style="float:left; width:' . $testimonial_width . 'px; height:260px; overflow:hidden">' . $t . '</div>';
		}
		echo '</div>';
		// $t_nav = '<a href="javascript:void(0)" onclick="current_testimonial = testimonial_shift(1, current_testimonial, ' . $num_testimonials . ')"style="text-decoration:none; float:right; color:gray">view more ></a>';
		
		echo '<div align="center" style="position:absolute; width:' . ($testimonial_width-20) . 'px; height:12px; top:235px; overflow:hidden; text-align:right">
		<a href="javascript:void(0)" onclick="freezeshow=true; testimonial_shift(1, current_testimonial); current_testimonial=1"><img id="testimonial_dot1" src="images/dots.png" style="margin-top:-12px; margin-bottom:12px; margin-right:4px; margin-left:4px" /></a>';
		for($k =2; $k <= $num_testimonials; $k++) {
			echo '<a href="javascript:void(0)" onclick="freezeshow=true; testimonial_shift(' . $k . ', current_testimonial); current_testimonial=' . $k . '"><img id="testimonial_dot' . $k . '" src="images/dots.png" width="12" height="24" style="margin-right:4px; margin-left:4px" /></a>';
		}
		echo '</div>';
	}
?>
</div>

<div style="position:absolute; top:65px; margin-left:-134px;">
	<a href="http://www.theagencyonline.com/index2.php?pageid=81">
    	<img src="images/tab_two_weeks.png" alt="two weeks free!" />
    </a>
</div>
<div style="position:absolute; top:65px; margin-left:853px;">
	<a href="browse.php">
		<img src="images/tab_browse.png" alt="Browse!" />
    </a>
</div>
<div style="position:absolute; top:145px; margin-left:853px;">
	<a href="contact.php">
		<img src="images/tab_submit.png" alt="Submit a Casting!" />
    </a>
</div>

<div style="position:absolute; top:220px; margin-left:-38px;">
	<a class="thickbox" onclick="loaddiv('popupcontent', 'join_options')" href="#TB_inline?height=395&amp;width=450&amp;inlineId=hiddenModalContent"><img src="images/apply_now.png" alt="Apply Now!" /></a>
</div>
<!--
<div style="position:absolute; top:235px; margin-left:170px;">
    <a href="http://www.theagencyonline.com/index2.php?pageid=59" style="text-decoration:none; font-weight:bold; color:#EEE; font-size:12px">(How Does It Work?)</a>
</div>
-->

<script type="text/javascript" language="javascript">
var num_testimonials = <?php echo $num_testimonials; ?>;
function testimonial_slideshow() {
	if(freezeshow) {
		clearInterval(testimonial_show);
	} else {
		var t_old = current_testimonial;
		current_testimonial++;
		if(current_testimonial > num_testimonials) {
			current_testimonial = 1;
		}
		var t_new = current_testimonial;
		testimonial_shift(t_new, t_old);
	}
}

testimonial_show = setInterval(testimonial_slideshow,8000);
</script>
<?php
	if(!empty($t_nav)) {
		// echo '<div style="position:absolute; top:150px; width:260px;">' . $t_nav . '</div>';
	}
?>
<img src="images/home_out_promo.gif" border="0" usemap="#Map" style="margin-top:50px; margin-left:20px">
<map name="Map">
  <area shape="rect" coords="1,1,181,36" href="#TB_inline?height=240&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv('popupcontent', 'join_options')" class="thickbox">
  <area shape="rect" coords="1,73,181,128" href="#TB_inline?height=240&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv('popupcontent', 'join_options')" class="thickbox">
  <area shape="rect" coords="23,42,156,68" href="index2.php?pageid=62&title=How+It+Works">
</map>
</div>
<?php
} else {

	$homeundermenu_in = showbox('homeundermenu_in');
	if($homeundermenu_in) {
		echo '<div style="clear:both">' . $homeundermenu_in . '</div>';
	}
}
?>


<div style="width:225px; float:left;" id="shoutdiv">
<?php
if($loggedin) {
?>
	<div style="width:225px;">
		<?php echo showbox('homeleft'); ?>
	</div>
<?php
}
?>
<?php include('includes/shoutbox.php'); ?>
</div>


<div class="AGENCYcolumnright">
<?php
if($loggedin) {
?>
	<div style="clear:both">
		<?php echo showbox('homemiddle-right'); ?>
	</div>
	<div style="width:225px; float:right;">
		<?php echo showbox('homeright'); ?>
	</div>
	
	<div style="width:300px; float:right; padding-right:30px">
		<?php echo showbox('homemiddle'); ?>
	</div>
<?php
}



/* ==================   CASTINGS =============== */

$maxchars_title = 30;
$maxchars_content = 76;
$perpage = 50; // how many castings items to show per page
?>
<div style="height:28px; clear:both">
	<div class="AGENCYRed AGENCYGeneralTitle" style="clear:both; cursor: pointer; width: 150px; float:left" onclick="window.location='news.php?showall=castings'">Casting Calls</div>


<div class="casting_drop" <?php // if(!is_super_admin()) { echo 'style="display:none"'; } ?>>
<ul class="sf-menu">
<?php
$sql = "SELECT casting_location FROM agency_castings_drop_loc ORDER BY casting_location";
$result = mysql_query($sql);
$num_results = mysql_num_rows($result);
if($num_results > 0) {
?>
	<li><a href="javascript:void(0)" style="font-size:13px">Location</a>
		<ul>
<?php
	if(isset($_GET['test'])) {
		$_SESSION['test'] = 1;
		// echo '<li>TEST</li>';
	}
	echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=&\');">ALL CASTINGS</a></li>';
	$loc = 'New York City Area';
	echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=' . $loc . '&\');">' . $loc . '</a></li>';
	$loc = 'Los Angeles/Southern Cal.';
	echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=' . $loc . '&\');">' . $loc . '</a></li>';
	$loc = 'Other';
	echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=' . $loc . '&\');">' . $loc . '</a></li>';
	
	
	/*
	while($row = sql_fetchrow($result)) {
		$loc = $row['casting_location'];
		echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=' . $loc . '&\');">' . $loc . '</a></li>';
	} */
?>
		</ul>
    </li>
<?php
}

$sql = "SELECT job_type FROM agency_castings_drop_job ORDER BY job_type";
$result = mysql_query($sql);
$num_results = mysql_num_rows($result);
if($num_results > 0) {
?>
    <li><a href="javascript:void(0)" style="font-size:13px">Job Type</a>
		<ul>
<?php
	echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&\');">ALL CASTINGS</a></li>';
	while($row = sql_fetchrow($result)) {
		$job = $row['job_type'];
		echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&jobtype=' . $job . '&\');">' . $job . '</a></li>';
	}
?>
    	</ul>
    </li>
<?php
}


$sql = "SELECT union_name FROM agency_castings_drop_unions ORDER BY union_name";
$result = mysql_query($sql);
$num_results = mysql_num_rows($result);
if($num_results > 0) {
?>
    <li><a href="javascript:void(0)" style="font-size:13px">Union</a>
		<ul>
<?php
	echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&\');">ALL CASTINGS</a></li>';
	while($row = sql_fetchrow($result)) {
		$union = $row['union_name'];
		echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&union=' . $union . '&\');">' . $union . '</a></li>';
	}
?>
    	</ul>
    </li>
<?php
}



if(agency_account_type() == 'talent') { 
?>
	<li><a href="javascript:void(0)">Filter</a>
		<ul>
<?php
	echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=&\');">View All Castings</a></li>'; 
 	echo '<li><a href="javascript:void(0)" style="" onClick="loaddiv(\'castingdiv\', false, \'ajax/morecastings.php?page=1&perpage=' . $perpage . '&location=&matches=true&\');">View My Matches</a></li>'; 
	
	/* } else {
		echo '<li><a href="javascript:void(0)" style="" onClick="alert(\'As a Member you will be able to filter Castings based on your account settings.  If you are already a member please log in to use this feature.\');">View My Matches</a></li>';
	} */
 
?>      
    	</ul>
    </li> 
<?php
}
?>
</ul>
</div>




</div>

<div class="AGENCYhomescroll" id="castingdiv">

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
while($row = sql_fetchrow($result)) {
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
		$jobtype_html .= '<span class="AGENCYcastinglabel">Job Type:</span> <span class="AGENCYcastinginfo">';
		while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			$jobtype = $row2['jobtype'];
			$jobtype_html .= $jobtype;
			if($num_results-- > 1) $jobtype_html .= ', ';
			// place icon
			if(!$jobicon) {
				if(isset($castingicons[$jobtype])) {
					echo '<a href="news.php?castingid=' . $castingid . '&amp;title=' .  urlencode($jobtitle) . '"><img src="images/icons/' . $castingicons[$jobtype] . '" align="left" style="margin-right:3px; margin-bottom:10px" border="0" /></a>';
					$jobicon = true;
				}
			}		
		}
		$jobtype_html .= '</span>&nbsp;&nbsp;&nbsp;';
	}
	if(!$jobicon) {
		echo '<a href="news.php?castingid=' . $castingid . '&amp;title=' .  urlencode($jobtitle) . '"><img src="images/icons/FilmOTHER.jpg" align="left" style="margin-right:3px; margin-bottom:10px" border="0" /></a>';
	}

/*
	if(file_exists('images/castings/' . $castingid . '.jpg')) {
		echo '<a href="news.php?castingid=' . $castingid . '&amp;title=' .  urlencode($jobtitle) . '"><img src="images/castings/' . $castingid . '.jpg" align="left" style="margin-right:3px" border="0" /></a>';
	} else if(file_exists('images/castings/' . $castingid . '.gif')) {
		echo '<a href="news.php?castingid=' .  $castingid . '&amp;title=' .  urlencode($jobtitle) . '"><img src="images/castings/' . $castingid . '.gif" align="left" style="margin-right:3px" border="0" /></a>';
	}
*/
?>
	<span class="AGENCYindextitle"><?php echo $jobtitle; ?></span> <span style="font-size:10px; color:#666666">[<?php echo $postdate; ?>]</span> <br />
	<span class="AGENCYindexcontent" style="font-size:10px;">
<?php
	echo $jobtype_html;
	
	$sql2 = "SELECT union_name FROM agency_castings_unions WHERE casting_id='$castingid'";
	$result2 = mysql_query($sql2);
	$num_results = mysql_num_rows($result2);
	if($num_results > 0) {
		echo '<span class="AGENCYcastinglabel">Union:</span> <span class="AGENCYcastinginfo">';
		while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
			echo $row2['union_name'];
			if($num_results-- > 1) echo ', ';
		}
		echo '</span>';
	}
	
	echo '&nbsp;&nbsp;<span class="AGENCYcastinglabel">Location:</span> <span class="AGENCYcastinginfo">' . $location . '</span>';
?>

	<br /><?php echo $notes; ?> <a href="news.php?castingid=<?php echo $castingid; ?>&amp;title=<?php echo urlencode($jobtitle); ?>">More Info&gt;</a></span><br /><br clear="all" />
<?php
}
if($total > $perpage) {
?>
	<div id="morecastings1">
		<div align="right" style="margin:20px">
			<a href="javascript:void(0)" style="font-size:14px; font-weight:bold" onClick="loaddiv('morecastings1', false, 'ajax/morecastings.php?page=2&perpage=<?php echo $perpage; echo $link_location; ?>&')">VIEW MORE CASTINGS</a>
		</div>
	</div>
<?php
}
?>

</div>








<br><br>
<?php
include('includes/showcase_long.php');
echo '<br clear="all" /><br clear="all" />';

include('includes/newsfeed.php');
?>


</div>
<script>
function castings_resize() {

  var shoutdiv = document.getElementById('shoutdiv');
  var castingdiv = document.getElementById('castingdiv');
  var shoutdiv_height = shoutdiv.clientHeight;
  if(shoutdiv_height > 950) {
  	castingdiv.style.height = (shoutdiv_height-<?php
if(!isset($_GET['news'])) {
	echo '650';
} else {
	echo '500';
}
?>)+'px';
  }
}

<?php 
if(!empty($_GET['news'])) {
	echo 'document.getElementById(\'moreshouts\').style.display=\'block\'; 
	document.getElementById(\'morelink\').style.display=\'none\';'; 
}
?>
 
castings_resize();

var drop_location = false; // globals for tracking dropbox status
var drop_jobtype = false;
</script>
<br clear="all" />
<?php
@include('includes/footer.php');
?>
