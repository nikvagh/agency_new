<?php
@include('sidebar.php')
?>

 <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
   <div class="row well" id="main">
   <div class="col-sm-12 col-md-12 " id="content">
<div class="col-sm-8">
<h2>MY PROJECTS > PROJECTS NAME</h2>
<div class="main-projects">
<form class="form-horizontal" action="/action_page.php">
    <div class="form-group">
      <label class="control-label col-sm-3" for="email">NAME:</label>
      <div class="col-sm-9">
        <input type="text" class="form-control" id="name" placeholder="Enter Name" name="">
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-3" for="email">START DATE:</label>
      <div class="col-sm-9">
       <p class="date"> <input type = "date" id = "start-date" class="datepicker" placeholder="Start Date">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-3" for="email">END DATE:</label>
      <div class="col-sm-9">
       <input type = "date" id = "start-date" class="datepicker" placeholder="End Date">
      </div>
    </div>
   
  </form>
</div>
<h3 class="title-pro">PROJECTS DETAILS:</h3>
<div class="project-box">

<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
</div>

<h3 class="title-pro" style="float: left;width: 70%;">CASTINGS FOR THIS PROJECTS:</h3>
<div class="btn-group lightbox-btn" style="float: right;">
    <button class="btn btn-default" type="button">IMPORT CASTING</button>
    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#">Action</a></li>
      <li><a href="#">Another action</a></li>
      <li><a href="#">Something else here</a></li>
      <li class="divider"></li>
      <li><a href="#">Separated link</a></li>
    </ul>
  </div>

<div class="casting-table">
<form>
<table class="table table-striped">
  <tbody>
  	<tr>
      <td></td>
      <td>Roles</td>
      <td>Submission</td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>Loreal Paris</td>
      <td>1</td>
       <td>14</td>
       <td style="color: green; font-weight: 700">VIEW CASTING</td>
      <td><a class="delete" onclick="del()" style="font-size: 20px; color: red;">&times;</a></td>
    </tr>
    <tr>
      <td>Maybelline</td>
      <td>2</td>
       <td>15</td>
       <td style="color: green; font-weight: 700">VIEW CASTING</td>
      <td><a class="delete" onclick="del()" style="font-size: 20px; color: red;">&times;</a></td>
    </tr>
    <tr>
      <td>Chinese</td>
      <td>32</td>
       <td>13</td>
      <td style="color: green; font-weight: 700">VIEW CASTING</td>
      <td><a class="delete" onclick="del()" style="font-size: 20px; color: red;">&times;</a></td>
    </tr>
    <tr>
      <td>Micheal</td>
      <td>34</td>
       <td>16</td>
     <td style="color: green; font-weight: 700">VIEW CASTING</td>
      <td><a class="delete" onclick="del()" style="font-size: 20px; color: red;">&times;</a></td>
    </tr>
    <tr>
      <td>Sherri hill</td>
      <td>3</td>
       <td>1</td>
       <td style="color: green; font-weight: 700">VIEW CASTING</td>
     <td><a class="delete" onclick="del()" style="font-size: 20px; color: red;">&times;</a></td>
    </tr>
    <tr>
      <td>Jovani</td>
      <td>36</td>
       <td>10</td>
       <td style="color: green; font-weight: 700">VIEW CASTING</td>
      <td><a class="delete" onclick="del()" style="font-size: 20px; color: red;">&times;</a></td>
    </tr>
    <tr>
      <td>Kayte Spade</td>
      <td>31</td>
       <td>12</td>
       <td style="color: green; font-weight: 700">VIEW CASTING</td>
      <td><a class="delete" onclick="del()" style="font-size: 20px; color: red;">&times;</a></td>
    </tr>

  </tbody>
  
</table>

</form>

</div>


<h3 class="title-pro" style="float: left;width: 75%;">TALENT SELECTED FOR THIS PROJECT:</h3>
<div class="btn-group lightbox-btn" style="float: right;">
    <button class="btn btn-default" type="button">IMPORT TALENT</button>
    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button"><span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <li><a href="#">FROM CASTING</a></li>
      <li><a href="#">MANUALLY ADD</a></li>
      <li><a href="#">FROM AGENCY</a></li>
     
      <li><a href="#">FILL DETAILS</a></li>
    </ul>
  </div>

  <div class="talet-proj">
<img src="img/avatar.jpg">
<p>Name: John Doe</p>
<p>Role: Model 1</p>
<p><a href="">View Profile</a></p>
<p><a href="">Contact</a></p>
  </div>

</div>

<div class="col-sm-4">

<div class="casted">
  <p style="float: left; font-weight: 700;">QUICK TALENT SEARCH</p>
 
 <p style="float: right; color: #00BCD4;"><i class="fa fa-circle-o" aria-hidden="true"></i></p>
  <p style="float: right; padding-right: 20px; color: #00BCD4; font-weight: 700;">LOCATION <i class="fa fa-angle-down"> </i></p>


<form>
  <div style="width:47%; float: left;">
<input type="text" name="" placeholder="Age">
</div>
<div style="width:47%;float: right;">
<input type="text" name="" placeholder="To">
</div>
<div style="width:100%;">
<input type="text" name="" placeholder="Gender">
</div>
<div style="width:100%;">
<input type="text" name="" placeholder="Ethnicity">
</div>
<div style="width:100%;">
<input type="text" name="" placeholder="Experience">
</div>
<div style="width:100%;">
<input value="Search Talent" name="submit" type="submit" class="serch-btn">
</div>
  </form>
 </div>

<div class="documents">
<h3>PROJECTS DOCUMENTS</h3>
<div class="docu-tetx">
<i class="fa fa-file-text-o"><p style="font-size: 12px; color: #000;">Call Sheet</p></i>
<i class="fa fa-file-text-o"><p style="font-size: 12px; color: #000;">Style Sheet</p></i>
</div>
<p><a href="" style="color: green;float: right;font-weight: 700">+ADD NEW DOCUMENTS</a></p>
</div>

	</div>



   </div>
</div>
</div>
</div>





<script>
	$('.delete').on('click', function(event) {
	$(this).parents('tr').remove();
});

$(".add").on('click', function() {
	$('tr:last-child').clone(true).appendTo('tbody');
  // to make the clone removable, set clone(true)
});
</script>
</body>

</html>