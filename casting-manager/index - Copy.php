<?php
@include('sidebar.php')
?>

 <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
   <div class="row well" id="main" style="padding: 19px 0 !important;">
   <div class="col-sm-12 col-md-12 " id="content">
<div class="col-sm-4">
<div class="profile-box">
<p><strong>MY PROFILE</strong></p>
<hr>
<div class="pro-img">
<a href="#" class="text-center">View Profile</a>    
<img src="img/avatar.jpg">
</div>
<div class="prof-txt">
<p>BIOGRAPHY:<br>
Name : <?= $row['firstname'] ?>
</p><br>
<span style="color: green;">Location: <?= $row['location'] ?></span>
</div>
</div>

<div class="notification-box">
<p style="padding-left: 10px;"><strong>NOTIFICATIONS</strong></p>


<div class="tab-content">
                    <div class="tab-pane active" id="profile">
                      <table class="table">
                        <tbody>
                          <tr>
                            <td>
                              <div class="form-check">
                                <i class="fa fa-user"></i>
                                 
                            </div></td>
                            <td>No current NOTIFICATION.</td>

                            <td class="td-actions text-right">
                             <p></p>
                            </td>
                          </tr>

                          
                        </tbody>
                      </table>
                    </div>



                  
                  </div>
</div>


<div class="notification-box message">
<p style="padding-left: 10px;"><strong>MESSAGES</strong></p>


<div class="tab-content">
                    <div class="tab-pane active" id="profile">
                      <table class="table">
                        <tbody>
                          <tr>
                            <td>
                              <div class="form-check">
                                <span class="name">J</span>
                                 
                            </div></td>
                            <td>No New Message.</td>

                            <td class="td-actions text-right">
                             <p></p>
                            </td>
                          </tr>

                          
                        </tbody>
                      </table>
                    </div>



                  
                  </div>
</div>




</div>

<div class="col-sm-4">

<div class="row">
             <div class="col-md-6" style="padding: 5px;">
<div class="new-user">
<p>Active Casting Call</p>
<h3>5</h3> 
<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:50%">
      
    </div>
  </div>
</div>
  </div>
  <div class="col-md-6" style="padding: 5px;">
<div class="new-user casing">
<p>Submission</p>
<h3>26</h3>
 
<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width:30%">
      </div>
    </div>
  </div>
</div>
             </div>
<div class="main-hill">
<div class="hill-pic">
<img class="img" src="img/cover.jpg">

<div class="demo-text">
<p>Agency Angle &gt; Latest Blog Post</p>
<h3>How to take quality without breaking the bank</h3>

</div>
</div>
</div>

<div class="calendar-wrapper">
  <button id="btnPrev" type="button">Prev</button>
	  <button id="btnNext" type="button">Next</button>
  <div id="divCal"></div>
</div>

<div class="online1">
             
              <span class="on-not1">The Agency Online</span>
              <span class="on-social"><i class="fa fa-facebook" aria-hidden="true"></i></span>
              <br>
              <div class="on-text"><p>Lorem Ipsum dummy text.Ipsum dummy text</p></div>
        </div>

<div class="online2">
            
              <span class="on-not1">The Agency Online</span>
              <span class="on-social"><i class="fa fa-twitter" aria-hidden="true"></i></span>
              <br>
              <div class="on-text"><p>Lorem Ipsum dummy text. Ipsum dummy text</p></div>
        </div>

</div>

<div class="col-sm-4">

<div class="casted">
  <p style="float: left; font-weight: 700;">QUICK TALENT SEARCH</p>
 
 <p style="float: right; color: #00BCD4;"><i class="fa fa-circle-o" aria-hidden="true"></i></p>
  <p style="float: right; padding-right: 20px; color: #00BCD4; font-weight: 700;">LOCATION <i class="fa fa-angle-down"> </i></p>


<form action="clienthome.php?mode=search<?php if(isset($_GET['configure'])) echo '&configure=true'; ?>" method="post" name="searchform">
  <div style="width:47%; float: left;">
<input type="text" name="age" placeholder="Age">
</div>
<div style="width:47%;float: right;">
<input type="text" name="age2" placeholder="To">
</div>
<div style="width:100%;">
<input type="text" name="gender" placeholder="Gender">
</div>
<div style="width:100%;">
<input type="text" name="" placeholder="Ethnicity">
</div>
<div style="width:100%;">
<input type="text" name="" placeholder="Experience">
</div>
<div style="width:100%;">
<input value="Search" name="submitsearch" type="submit" class="serch-btn">
</div>
  </form>
 </div>


 <div class="casting-box">
     
<p>MY CASTING CALL</p>
<?php
                $profileid = 2;
				$sql = "SELECT * FROM agency_castings WHERE posted_by='$profileid' AND deleted='0' ORDER BY post_date DESC LIMIT 5";
				$result=mysql_query($sql);
				if(mysql_num_rows($result) == 0) echo '<br /><br />You have not created any castings yet.<br /><br />';
				while($row = sql_fetchrow($result)) {
					$castingid = $row['casting_id'];
					$jobtitle = $row['job_title'];
					$live = $row['live'];
					$livenote = '';
					if(!$live) {
						$livenote = '<span style="color:red">NOTE: THIS CASTING IS NOT LIVE.</span>';
					}
					// style="text-decoration:none; padding-left:130px"><a href="news.php?castingid=' . $castingid . '&amp;title=' . urlencode($jobtitle) . '"
	   				echo '- <a href="news.php?castingid=' . $castingid . '" style="text-decoration:none; color:#000000;">' . $jobtitle . ' (view)</a>' .
	   					' (<a href="castingupdate.php?castingid=' . $castingid . '" style="text-decoration:none; color:#333333;">edit</a>) ' . $livenote . '<br />';
						
					// find submissions for this casting
					echo '<a href="clienthome.php?mode=castings&castingid=' . $castingid . '" style="text-decoration:none; padding-left:70px;';
					$sql2 = "SELECT * FROM agency_mycastings, agency_castings_roles WHERE agency_mycastings.role_id=agency_castings_roles.role_id AND agency_castings_roles.casting_id='$castingid' AND agency_mycastings.removed='0'";
					$result2=mysql_query($sql2);
					$num_castings = mysql_num_rows($result2);				
					if($num_castings == 0) {	
						echo ' color:#0066FF;">You Have No Submissions';
					} else {
						$sql2 = "SELECT * FROM agency_mycastings, agency_castings_roles WHERE agency_mycastings.role_id=agency_castings_roles.role_id AND agency_castings_roles.casting_id='$castingid' AND agency_mycastings.new_submission='1' AND agency_mycastings.removed='0'";
						$result2=mysql_query($sql2);
						$num_castings = mysql_num_rows($result2);
						if($num_castings == 0) {
							echo '">View Submissions (No New Submissions)';
						} else {
							echo '">View Submissions (You have ' . $num_castings . ' New Submissions!)';
						}
					}
					echo '</a><br /><br />';
				}
?>

              </div>

<div class="request-box">
<p>REQUESTS</p>
 </div>

</div>





   </div>
</div>
</div>
</div>


<script>
	var Cal = function(divId) {

  //Store div id
  this.divId = divId;

  // Days of week, starting on Sunday
  this.DaysOfWeek = [
    'Sun',
    'Mon',
    'Tue',
    'Wed',
    'Thu',
    'Fri',
    'Sat'
  ];

  // Months, stating on January
  this.Months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ];

  // Set the current month, year
  var d = new Date();

  this.currMonth = d.getMonth();
  this.currYear = d.getFullYear();
  this.currDay = d.getDate();

};

// Goes to next month
Cal.prototype.nextMonth = function() {
  if ( this.currMonth == 11 ) {
    this.currMonth = 0;
    this.currYear = this.currYear + 1;
  }
  else {
    this.currMonth = this.currMonth + 1;
  }
  this.showcurr();
};

// Goes to previous month
Cal.prototype.previousMonth = function() {
  if ( this.currMonth == 0 ) {
    this.currMonth = 11;
    this.currYear = this.currYear - 1;
  }
  else {
    this.currMonth = this.currMonth - 1;
  }
  this.showcurr();
};

// Show current month
Cal.prototype.showcurr = function() {
  this.showMonth(this.currYear, this.currMonth);
};

// Show month (year, month)
Cal.prototype.showMonth = function(y, m) {

  var d = new Date()
  // First day of the week in the selected month
  , firstDayOfMonth = new Date(y, m, 1).getDay()
  // Last day of the selected month
  , lastDateOfMonth =  new Date(y, m+1, 0).getDate()
  // Last day of the previous month
  , lastDayOfLastMonth = m == 0 ? new Date(y-1, 11, 0).getDate() : new Date(y, m, 0).getDate();


  var html = '<table>';

  // Write selected month and year
  html += '<thead><tr>';
  html += '<td colspan="7">' + this.Months[m] + ' ' + y + '</td>';
  html += '</tr></thead>';


  // Write the header of the days of the week
  html += '<tr class="days">';
  for(var i=0; i < this.DaysOfWeek.length;i++) {
    html += '<td>' + this.DaysOfWeek[i] + '</td>';
  }
  html += '</tr>';

  // Write the days
  var i=1;
  do {

    var dow = new Date(y, m, i).getDay();

    // If Sunday, start new row
    if ( dow == 0 ) {
      html += '<tr>';
    }
    // If not Sunday but first day of the month
    // it will write the last days from the previous month
    else if ( i == 1 ) {
      html += '<tr>';
      var k = lastDayOfLastMonth - firstDayOfMonth+1;
      for(var j=0; j < firstDayOfMonth; j++) {
        html += '<td class="not-current">' + k + '</td>';
        k++;
      }
    }

    // Write the current day in the loop
    var chk = new Date();
    var chkY = chk.getFullYear();
    var chkM = chk.getMonth();
    if (chkY == this.currYear && chkM == this.currMonth && i == this.currDay) {
      html += '<td class="today">' + i + '</td>';
    } else {
      html += '<td class="normal">' + i + '</td>';
    }
    // If Saturday, closes the row
    if ( dow == 6 ) {
      html += '</tr>';
    }
    // If not Saturday, but last day of the selected month
    // it will write the next few days from the next month
    else if ( i == lastDateOfMonth ) {
      var k=1;
      for(dow; dow < 6; dow++) {
        html += '<td class="not-current">' + k + '</td>';
        k++;
      }
    }

    i++;
  }while(i <= lastDateOfMonth);

  // Closes table
  html += '</table>';

  // Write HTML to the div
  document.getElementById(this.divId).innerHTML = html;
};

// On Load of the window
window.onload = function() {

  // Start calendar
  var c = new Cal("divCal");			
  c.showcurr();

  // Bind next and previous button clicks
  getId('btnNext').onclick = function() {
    c.nextMonth();
  };
  getId('btnPrev').onclick = function() {
    c.previousMonth();
  };
}

// Get element by id
function getId(id) {
  return document.getElementById(id);
}
</script>
</body>
</html>





















