<!DOCTYPE html>
<html>
<head>
  <title>Agent form</title>
 
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style1.css">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
 <link rel="stylesheet" href="css/font-awesome.min.css">
  
  <script src="js/bootstrap.min.js"></script>
   <link rel="shortcut icon" href="image/fav2.png" type="image/x-icon">
</head>
<body>
<?php

@include('header.php');
?>
<div class="menu">

<ul>
	<li><a href="talent_signup.php">TALENT</a></li>
<li><a href="client_signup.php">CLIENT</a></li>
<li><a href="agent-signup.php" class="active">TALENT MANAGER/AGENT</a></li>
</ul>
</div>


<div class="signup-content">
<div class="container">
<div class="signup-form2">
<h2>WELCOME</h2>
<hr class="welcome">
<h3>NEW AGENT / MANAGER SIGNUP </h3>
<div class="col-sm-12">
<div class="form-box">
<h3>In order to create your Agent or Manager account, please submit the formbelow. Once you've signed up, <span>please allow approximately 24hrs for processing.</span> We will contact you if we require any additional information.</h3>
</div>
</div>

<form action="/action_page.php">
    <div class="reg">
      <div class="row"> 
    <div class="col-sm-12">
        <div class="col-sm-6">
            <div class="form-group">
<input type="text-area" placeholder="FIRST NAME:" required = "required" value="">
</div>
</div>
 <div class="col-sm-6">
    <div class="form-group">
<input type="text-area" placeholder="LAST NAME:" required = "required" value="">
</div>
</div>
</div>
</div> 

<div class="row">
<div class="col-sm-12">
    <div class="col-sm-6">
    <div class="form-group">
<input type="text-area" placeholder="EMAIL ADDRESS:" required = "required" value="">
</div>
</div>
<div class="col-sm-6">
    <div class="form-group">
<input type="text-area" placeholder="USER NAME:" required = "required" value="">
</div>
</div>
</div>
</div>

<div class="row">
    <div class="col-sm-12">
     <div class="col-sm-6">
      <div class="form-group">
        <input type="password" placeholder="PASSWORD:" required = "required" value="">
      </div>
     </div>
     <div class="col-sm-6">
      <div class="form-group">
        <input type="password" placeholder="CONFORM PASSWORD:" required = "required" value="">
      </div>
     </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="form-group">
<input type="text-area" placeholder="COMPANY NAME:" value="">
</div>
</div>

<div class="col-sm-12">
    <div class="form-group">
<input type="text-area" placeholder="ADDRESS:" value="">
</div>
</div>

<div class="row"> 
    <div class="col-sm-12">
        <div class="col-sm-6">
            <div class="form-group">
<input type="text-area" placeholder="CITY:" value="">
</div>
</div>
        <div class="col-sm-6">
            <div class="form-group">
<select name="state">
    <option value="Alabama">Alabama</option>
    <option value="Alaska">Alaska</option>
    <option value="Arizona">Arizona</option>
    <option value="Arkansas">Arkansas</option>
    <option value="California">California</option>
    <option value="Colorado">Colorado</option>
    <option value="Connecticut">Connecticut</option>
    <option value="Delaware">Delaware</option>
    <option value="District Of Columbia">District Of Columbia</option>
    <option value="Florida">Florida</option>
    <option value="Georgia">Georgia</option>
    <option value="Hawaii">Hawaii</option>
    <option value="Idaho">Idaho</option>
    <option value="Illinois">Illinois</option>
    <option value="Indiana">Indiana</option>
    <option value="Iowa">Iowa</option>
    <option value="Kansas">Kansas</option>
    <option value="Kentucky">Kentucky</option>
    <option value="Louisiana">Louisiana</option>
    <option value="Maine">Maine</option>
    <option value="Maryland">Maryland</option>
    <option value="Massachusetts">Massachusetts</option>
    <option value="Michigan">Michigan</option>
    <option value="Minnesota">Minnesota</option>
    <option value="Mississippi">Mississippi</option>
    <option value="Missouri">Missouri</option>
    <option value="Montana">Montana</option>
    <option value="Neb raska">Nebraska</option>
    <option value="Nevada">Nevada</option>
    <option value="New Hampshire">New Hampshire</option>
    <option value="New Jersey">New Jersey</option>
    <option value="New Mexico">New Mexico</option> 
    <option value="New York">New York</option>
    <option value="North Carolina">North Carolina</option>
    <option value="North Dakota">North Dakota</option>
    <option value="Ohio">Ohio</option>
    <option value="Oklahoma">Oklahoma</option>
    <option value="Oregon">Oregon</option>
    <option value="Pennsylvania">Pennsylvania</option>
    <option value="Rhode">Rhode Island</option> 
    <option value="South Carolina">South Carolina</option>
    <option value="Tennessee">Tennessee</option>
    <option value="Texas">Texas</option>
    <option value="Utah">Utah</option>
    <option value="Vermont">Vermont</option>
    <option value="Virginia">Virginia</option>
    <option value="Washington">Washington</option>
    <option value="West Virginia">West Virginia</option>
    <option value="Wisconsin">Wisconsin</option>
    <option value="Wyoming">Wyoming</option>
  </select>
</div>
</div>
</div>
   
</div>

<div class="row"> 
    <div class="col-sm-12">
        <div class="col-sm-4">
            <div class="form-group">
<input type="text-area" placeholder="ZIP:" value="">
</div>
</div>
<div class="col-sm-8">
    <div class="form-group">
<select name="country">
    <option value="">USA</option>
  </select>
</div>
</div>
</div>
</div>

<div class="col-sm-12">
    <div class="form-group">
<input type="text-area" placeholder="CELL PHONE NUMBER" value="">
</div>
</div>


<div class="form-txt">
    <h4>ACCEPTABLE REFERENCES; CASTING DIRECTOR, PRODUCTION, AD AGENCY, AGENCY OR MANAGER.</h4>
    <hr>
</div>

<div class="row"> 
    <div class="col-sm-12">
        <div class="col-sm-6">
    <div class="form-group demo-form">
        <label class="control-label">INDUSTRY REFERENCE #1 <span>*</span></label>
</div>
</div>
 <div class="col-sm-6">
  <div class="form-group">
<input type="text-area" placeholder="NAME:" required = "required" value="">
</div>
</div>
</div>
</div>

<div class="row"> 
    <div class="col-sm-12">
        <div class="col-sm-6">
    <div class="form-group demo-form">
        <label class="control-label"> INDUSTRY REFERENCE #1 <span>*</span></label>
</div>
</div>
 <div class="col-sm-6">
  <div class="form-group">
<input type="text-area" placeholder="PHONE NUMBER:" required = "required" value="">
</div>
</div>
</div>
</div>

<div class="row"> 
    <div class="col-sm-12">
        <div class="col-sm-6">
    <div class="form-group demo-form">
        <label class="control-label">INDUSTRY REFERENCE #1 <span>*</span></label>
</div>
</div>
 <div class="col-sm-6">
  <div class="form-group">
<input type="text-area" placeholder="TITLE:" required = "required" value="">
</div>
</div>
</div>
</div>

<div class="row"> 
    <div class="col-sm-12">
        <div class="col-sm-6">
    <div class="form-group demo-form">
        <label class="control-label">INDUSTRY REFERENCE #2 <span>*</span></label>
</div>
</div>
 <div class="col-sm-6">
  <div class="form-group">
<input type="text-area" placeholder="NAME:" value="">
</div>
</div>
</div>
</div>

<div class="row"> 
    <div class="col-sm-12">
        <div class="col-sm-6">
    <div class="form-group demo-form">
        <label class="control-label"> INDUSTRY REFERENCE #2 <span>*</span></label>
</div>
</div>
 <div class="col-sm-6">
  <div class="form-group">
<input type="text-area" placeholder="PHONE NUMBER:" value="">
</div>
</div>
</div>
</div>

<div class="row"> 
    <div class="col-sm-12">
        <div class="col-sm-6">
    <div class="form-group demo-form">
        <label class="control-label">INDUSTRY REFERENCE #2 <span>*</span></label>
</div>
</div>
 <div class="col-sm-6">
  <div class="form-group">
<input type="text-area" placeholder="TITLE:" value="">
</div>
</div>
</div>
</div>

<div class="row"> 
<div class="col-sm-12">
<div class="col-sm-8">
<div class="form-group demo-form">
<label class="control-label">WHAT REGION/CITIES DO YOU REPRESENT TALENT IN? <span>*</span></label>
</div>
</div>
 <div class="col-sm-4">
 <div class="form-group">
<select name="Commercial">
    <option value="">Florida</option>
    <option value="">Los Angeles</option>
    <option value="">New York</option>
    <option value="">Baltimore</option>
  </select>
</div>
</div>
</div>
</div>

<div class="row"> 
<div class="col-sm-12">
<div class="col-sm-8">
<div class="form-group demo-form">
<label class="control-label">HOW MANY TALENT DO YOU CURRENTLY REPRESENT? <span>*</span></label>
</div>
</div>
 <div class="col-sm-4">
 <div class="form-group">
<select name="Commercial">
    <option value="">10-20</option>
    <option value="">21-50</option>
    <option value="">50+</option>
  </select>
</div>
</div>
</div>
</div>

<div class="row"> 
<div class="col-sm-12">
<div class="col-sm-8">
<div class="form-group demo-form">
<label class="control-label">HOW LONG HAS YOUR COMPANY BEEN IN BUSINESS? <span>*</span></label>
</div>
</div>
 <div class="col-sm-4">
 <div class="form-group">
<select name="Commercial">
    <option value="">0-20</option>
    <option value="">20+</option>
  </select>
</div>
</div>
</div>
</div>

<div class="form-txt">
    <hr>
</div>

<div class="form-txt">
<h4>I DO NOT REQUIRE A TALENT AGENCY LICENSE IN MY REGION: <span>(If applicable)</span></h4>
</div>

<div class="col-sm-12">
    <div class="form-group demo-form account-row">
        <label class="control-label">Your talent agency License number or bond number?</label>
<input type="text-area" placeholder="" value="">
</div>
</div>

<div class="form-read">
<h4>(All managerswithin California and several other states must have a surety bond or belong to TalentManagers
Association to receive breakdowns or negotiate on behalf of talent.*)</h4>
</div>

<h4 class="red-txt">Please attach a copy of your license or bond when returning this form. * (insert file upload here)</h4>

<div class="row"> 
<div class="col-sm-12">
<div class="col-sm-8">
<div class="form-group demo-form">
<label class="control-label">SPECIFY WHICH TYPE OF PROJECTS YOU  <span>*</span><br>REPRESENT TALENT FOR</label>
</div>
</div>
 <div class="col-sm-4">
 <div class="form-group">
<select name="Commercial">
    <option value="">Commercial</option>
    <option value="">Print</option>
    <option value="">Theatrical</option>
    <option value="">Film</option>
    <option value="">Voice over</option>
    <option value="">Singing</option>
    <option value="">Special Skills</option>
  </select>
</div>
</div>
</div>
</div>

<div class="sign-submit">
<input type="submit" value="SUBMIT">
</div>





</div>
</form>


</div>
</div>
</div>

<?php

@include('footer.php');
?>

</body>
</html>