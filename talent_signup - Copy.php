
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
  <title>Create Account</title>
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



      <li><a href="talent_signup.php" class="active">TALENT</a></li>



      <li><a href="client_signup.php">CLIENT</a></li>



      <li><a href="agent-signup.php">TALENT MANAGER/AGENT</a></li>



    </ul>



  </div>











  <div class="signup-content">



    <div class="container">



      <div class="talent-form">



        <h2>WELCOME</h2>



        <hr class="welcome">



        <h3>CREATE YOUR ACCOUNT</h3>







        <form action="/action_page.php" id="regForm">





          <div class="tab">

            <div class="next1">

              <div class="col-sm-12 talent-demo">



                <div class="col-sm-6">



                  <div class="form-group">



                    <input type="text-area" placeholder="FIRST NAME" required="required" name="firstname" <?php if (!empty($_POST['firstname'])) {
                                                                                                            echo 'value="' . $_POST['firstname'] . '"';
                                                                                                          } ?>>



                  </div>



                </div>



                <div class="col-sm-6">



                  <div class="form-group">



                    <input type="text-area" placeholder="LAST NAME" required="required" name="lastname" <?php if (!empty($_POST['lastname'])) {
                                                                                                          echo 'value="' . $_POST['lastname'] . '"';
                                                                                                        } ?>>



                  </div>



                </div>



              </div>











              <div class="col-sm-12">



                <div class="form-group">



                  <input type="text-area" placeholder="EMAIL ADDRESS" required="required" name="email" <?php if (!empty($_POST['email'])) {
                                                                                                          echo 'value="' . $_POST['email'] . '"';
                                                                                                        } ?>>



                </div>



              </div>







              <div class="col-sm-12">



                <div class="form-group">



                  <input type="text-area" placeholder="USERNAME" required="required" name="username">



                </div>



              </div>







              <div class="col-sm-12">



                <div class="form-group">



                  <input type="text-area" placeholder="PASSWORD" required="required" name="joinpassword">



                </div>



              </div>







              <div class="col-sm-12">



                <div class="form-group">



                  <input type="text-area" placeholder="CONFIRM PASSWORD" required="required" name="confirmpassword">



                </div>



              </div>







              <div class="col-sm-12">



                <div class="form-group">



                  <input type="text-area" placeholder="PHONE NUMBER" required="required" name="phone" <?php if (!empty($_POST['phone'])) {
                                                                                                        echo 'value="' . $_POST['phone'] . '"';
                                                                                                      } ?>>



                </div>



              </div>







              <div class="col-sm-12">



                <div class="form-group">



                  <input type="text-area" placeholder="CELL PHONE NUMBER" value="">



                </div>



              </div>



            </div>



          </div>











          <div class="tab">



            <div class="form-txt">



              <h4>ENTER YOUR DETAILS BELOW TO RECEIVE MATCHING ROLES<span style="color: #612; font-weight: 700;"> IMMEDIATELY!</span></h4>



              <hr>



            </div>









            <div class="row">



              <div class="col-sm-12">



                <div class="col-sm-6">



                  <div class="form-group demo-form">



                    <label class="control-label">CHOOSE A PRIMARY REGION <span>*</span></label>



                  </div>



                </div>



                <div class="col-sm-6">



                  <div class="form-group">



                    <select name="location" onchange="if(this.value=='Other') { document.getElementById('otherlocation').style.display=''; } else { document.getElementById('otherlocation').style.display='none'; }">



                      <?php



                      foreach ($locationarray as $location) {



                        echo '<option value="' . $location . '">' . $location . '</option>';
                      }



                      ?>



                      <option value="Other">Other</option>



                    </select>



                  </div>



                </div>



              </div>



            </div>







            <div class="row">



              <div class="col-sm-12">



                <div class="col-sm-6">



                  <div class="form-group demo-form">



                    <label class="control-label">OTHER REGIONS</label>



                  </div>



                </div>



                <div class="col-sm-6">



                  <div class="form-group">



                    <select required name="city">



                      <option value="New York">New York</option>



                      <option value="California">California</option>



                      <option value="DC">DC</option>



                      <option value="Nashville">Nashville</option>



                      <option value="Florida">Florida</option>



                      <option value="Mid-Atlantic">Mid-Atlantic</option>



                      <option value="Southeast">Southeast</option>



                      <option value="Northwest">Northwest</option>



                      <option value="Pacific Coastal">Pacific Coastal</option>



                    </select>



                  </div>



                </div>



              </div>



            </div>









            <div class="center">



              <h3>PORTRAYABLE ETHNICITIES (CHOOSE ALL THAT APPLY) </h3>



              <hr>



            </div>



            <div class="check-box-form">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-4"></div>



                  <div class="col-sm-4">



                    <?php







                    for ($i = 0; isset($ethnicityarray[$i]); $i++) {



                      echo '<p><input type="checkbox" name="ethnicities[]" id="ethnicities[' . $i . ']" value="' . $ethnicityarray[$i] . '"';



                      if (in_array($ethnicityarray[$i], $ethnicities)) echo ' checked';



                      echo ' /> ' . $ethnicityarray[$i] . '</p>';
                    }



                    ?>







                  </div>



                  <div class="col-sm-4"></div>



                </div>



              </div>



            </div>







            <div class="gender-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-2">



                    <div class="form-group demo-form">



                      <label class="control-label">GENDER <span>*</span></label>



                    </div>



                  </div>



                  <div class="col-sm-6">



                    <div class="check-box-form transgender">







                      <p> <input type="radio" required="required" name="gender" value="M" <?php if (!empty($gender)) {
                                                                                            if ($gender == 'M') echo 'checked';
                                                                                          } ?> /> Male&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                        <input type="radio" required="required" name="gender" value="F" <?php if (!empty($gender)) {
                                                                                          if ($gender == 'F') echo 'checked';
                                                                                        } ?> /> Female&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="gender" value="O" <?php if (!empty($gender)) {
                                                                                                                                                                                              if ($gender == 'O') echo 'checked';
                                                                                                                                                                                            } ?> />Transgender</p>



                    </div>



                  </div>



                </div>



              </div>



            </div>





          </div>



          <div class="tab">







            <div class="gender-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-4">



                    <div class="form-group demo-form">



                      <label class="control-label">PORTRAYABLE AGE RANGE <span>*</span></label>



                    </div>



                  </div>



                  <div class="col-sm-3">



                    <div class="form-group">



                      <select name="city">



                        <option value=""></option>



                        <option value=""></option>



                      </select>



                    </div>



                  </div>



                  <div class="col-sm-1">



                    <div class="form-group demo-form">



                      <label class="control-label">to</label>



                    </div>



                  </div>



                  <div class="col-sm-3">



                    <div class="form-group">



                      <select name="city">



                        <option value=""></option>



                        <option value=""></option>



                      </select>



                    </div>



                  </div>



                </div>



              </div>



            </div>











            <div class="gender-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-1">



                    <div class="form-group demo-form">



                      <label class="control-label">Weight:</label>



                    </div>



                  </div>



                  <div class="col-sm-2">



                    <div class="form-group">



                      <select required name="city">



                        <option value="">lbs</option>



                        <option value="">1</option>



                        <option value="">2</option>



                        <option value="">3</option>



                        <option value="">3</option>



                        <option value="">4</option>



                        <option value="">5</option>







                      </select>



                    </div>



                  </div>







                  <div class="col-sm-1">



                    <div class="form-group demo-form">



                      <label class="control-label">Height:</label>



                    </div>



                  </div>



                  <div class="col-sm-2">



                    <div class="form-group">



                      <select required name="city">



                        <option value="">ft</option>



                        <option value="">0</option>



                        <option value="">1</option>



                        <option value="">2</option>



                        <option value="">3</option>



                        <option value="">3</option>



                        <option value="">4</option>



                        <option value="">5</option>







                      </select>



                    </div>



                  </div>











                  <div class="col-sm-1">



                    <div class="form-group demo-form">



                      <label class="control-label">Inches: </label>



                    </div>



                  </div>



                  <div class="col-sm-2">



                    <div class="form-group">



                      <select required name="city">



                        <option value="">Inches</option>



                        <option value="">0</option>



                        <option value="">1</option>



                        <option value="">2</option>



                        <option value="">3</option>



                        <option value="">3</option>



                        <option value="">4</option>



                        <option value="">5</option>



                      </select>



                    </div>



                  </div>











                  <div class="col-sm-1">



                    <div class="form-group demo-form">



                      <label class="control-label">cm: </label>



                    </div>



                  </div>



                  <div class="col-sm-2">



                    <div class="form-group">



                      <select required name="city">



                        <option value="">cm</option>



                        <option value="">0</option>



                        <option value="">1</option>



                        <option value="">2</option>



                        <option value="">3</option>



                        <option value="">3</option>



                        <option value="">4</option>



                        <option value="">5</option>



                      </select>



                    </div>



                  </div>







                </div>



              </div>



            </div>















            <div class="gender-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-3">



                    <div class="form-group demo-form">



                      <label class="control-label">Veteran: </label>



                    </div>



                  </div>



                  <div class="col-sm-3">



                    <div class="form-group">



                      <select name="city" required>



                        <option value="">No</option>



                        <option value="">Yes</option>



                      </select>



                    </div>



                  </div>



                  <div class="col-sm-3">



                    <div class="form-group demo-form">



                      <label class="control-label">Military Branch: </label>



                    </div>



                  </div>



                  <div class="col-sm-3">



                    <div class="form-group">



                      <select name="city" required>



                        <option value="">Years in Service:</option>



                        <option value="">1</option>



                        <option value="">2</option>



                        <option value="">3</option>



                        <option value="">4</option>



                        <option value="">5</option>



                        <option value="">6</option>



                        <option value="">7</option>



                        <option value="">8</option>



                        <option value="">9</option>



                        <option value="">10</option>



                        <option value="">11</option>



                        <option value="">12</option>



                        <option value="">13</option>



                        <option value="">14</option>



                        <option value="">15</option>



                        <option value="">16</option>



                        <option value="">17</option>



                        <option value="">18</option>



                        <option value="">19</option>



                        <option value="">20</option>



                        <option value="">21</option>



                        <option value="">22</option>



                        <option value="">23</option>



                        <option value="">24</option>



                        <option value="">25</option>



                        <option value="">26</option>



                        <option value="">27</option>



                        <option value="">28</option>



                        <option value="">29</option>



                        <option value="">30</option>



                        <option value="">31</option>



                        <option value="">32</option>



                        <option value="">33</option>



                        <option value="">34</option>



                        <option value="">35</option>



                        <option value="">36</option>



                        <option value="">37</option>



                        <option value="">38</option>



                        <option value="">39</option>



                        <option value="">40</option>



                        <option value="">41</option>



                        <option value="">42</option>



                        <option value="">43</option>



                        <option value="">44</option>



                        <option value="">45</option>



                        <option value="">46</option>



                        <option value="">47</option>



                        <option value="">48</option>



                        <option value="">49</option>



                        <option value="">50</option>







                      </select>



                    </div>



                  </div>



                </div>



              </div>



            </div>



















            <div class="gender-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-3">



                    <div class="form-group demo-form">



                      <label class="control-label">UNION STATUS:</label>



                    </div>



                  </div>



                  <div class="col-sm-9">



                    <div class="checkbox-option">



                      <ul>



                        <li>
                          <p> <input type="checkbox" name="vehicle1" value=""> AEA</p>
                        </li>



                        <li>
                          <p> <input type="checkbox" name="vehicle1" value=""> AFM</p>
                        </li>



                        <li>
                          <p> <input type="checkbox" name="vehicle1" value="">AFTRA</p>
                        </li>



                        <li>
                          <p> <input type="checkbox" name="vehicle1" value=""> AGMA</p>
                        </li>



                        <li>
                          <p> <input type="checkbox" name="vehicle1" value="">SAG</p>
                        </li>



                        <li>
                          <p> <input type="checkbox" name="vehicle1" value=""> Non-union</p>
                        </li>



                        <li>
                          <p> <input type="checkbox" name="vehicle1" value="">Non-specific</p>
                        </li>



                      </ul>



                    </div>



                  </div>



                </div>



              </div>



            </div>















            <div class="biographical-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="form-group demo-form Bio">



                    <label class="control-label">Biographical Data:</label>



                    <input type="date" id="start" name="trip-start" value="" min="" max=""> (Can Use Appearing Age Of Birth If Over 18 Years Of Age)



                  </div>



                </div>



              </div>



            </div>











            <div class="biographical-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-3">



                    <div class="form-group demo-form">



                      <label class="control-label">Parent/Guardian:</label>



                    </div>



                  </div>



                  <div class="col-sm-6">



                    <div class="form-group">



                      <input type="text-area" placeholder="" value="" required>



                    </div>



                  </div>



                </div>



              </div>



            </div>















            <div class="gender-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-3">



                    <div class="form-group demo-form">



                      <label class="control-label">Pregnant?</label>



                    </div>



                  </div>



                  <div class="col-sm-3">



                    <div class="form-group">



                      <select name="city" required>



                        <option value="">No</option>



                        <option value="">Yes</option>



                      </select>



                    </div>



                  </div>



                  <div class="col-sm-3">



                    <div class="form-group demo-form">



                      <label class="control-label">Twin/Triplet? </label>



                    </div>



                  </div>



                  <div class="col-sm-3">



                    <div class="form-group">



                      <select name="city" required>



                        <option value="">Twin</option>



                        <option value="">Triplet</option>



                      </select>



                    </div>



                  </div>



                </div>



              </div>



            </div>







            <div class="gender-section">



              <div class="col-sm-12">



                <div class="col-sm-6">



                  <div class="form-group demo-form twin">



                    <p>Identical twin/triplets?<input type="checkbox" name="vehicle1" value=""> </p>



                  </div>



                </div>



              </div>



            </div>











            <div class="gender-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-2">



                    <div class="form-group demo-form">



                      <label class="control-label">Nationality:</label>



                    </div>



                  </div>



                  <div class="col-sm-4">



                    <div class="form-group">



                      <select name="city" required>



                        <option value="">Select</option>



                        <option value="">USA</option>



                        <option value="">AUS</option>



                        <option value="">England</option>



                      </select>



                    </div>



                  </div>



                </div>



              </div>



            </div>









            <div class="gender-section">



              <div class="row">



                <div class="col-sm-12">







                  <div class="col-sm-2">



                    <div class="form-group demo-form">



                      <label class="control-label">Hair Color: </label>



                    </div>



                  </div>



                  <div class="col-sm-4">



                    <div class="form-group">



                      <select name="city" required>



                        <option value="">Select</option>



                        <option value="">Brown</option>



                        <option value="">Black</option>



                      </select>



                    </div>



                  </div>







                  <div class="col-sm-2">



                    <div class="form-group demo-form">



                      <label class="control-label">Hair Length: </label>



                    </div>



                  </div>



                  <div class="col-sm-4">



                    <div class="form-group">



                      <select name="city" required>



                        <option value="">Select</option>



                        <option value="">1</option>



                        <option value="">2</option>



                      </select>



                    </div>



                  </div>











                </div>



              </div>



            </div>











            <div class="gender-section">



              <div class="row">



                <div class="col-sm-12">



                  <div class="col-sm-2">



                    <div class="form-group demo-form">



                      <label class="control-label">Eye Color: </label>



                    </div>



                  </div>



                  <div class="col-sm-4">



                    <div class="form-group">



                      <select name="city" required>



                        <option value="">Select</option>



                        <option value="">Brown</option>



                        <option value="">Black</option>



                      </select>



                    </div>



                  </div>



                  <div class="col-sm-2">



                    <div class="form-group demo-form">



                      <label class="control-label">Eye shape : </label>



                    </div>



                  </div>



                  <div class="col-sm-4">



                    <div class="form-group">



                      <select name="city" required>



                        <option value="">Select</option>



                        <option value="">slant</option>



                        <option value="">oval</option>



                        <option value="">pear</option>



                      </select>



                    </div>



                  </div>



                </div>



              </div>



            </div>















            <div class="col-sm-12">



              <div class="row">



                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Bust size: </label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>



                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>







                    </select>



                  </div>



                </div>







                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Shirt size:</label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>







                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>







                    </select>



                  </div>



                </div>











                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Kids Sizes:



                    </label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>



                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>



                    </select>



                  </div>



                </div>











                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Dress size:</label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>



                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>



                    </select>



                  </div>



                </div>



              </div>



            </div>















            <div class="col-sm-12">



              <div class="row">



                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Hips size: </label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>



                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>







                    </select>



                  </div>



                </div>







                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Glove size:</label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>







                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>







                    </select>



                  </div>



                </div>











                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Cup size:



                    </label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>



                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>



                    </select>



                  </div>



                </div>











                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Shoe Size:



                    </label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>



                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>



                    </select>



                  </div>



                </div>



              </div>



            </div>











            <div class="col-sm-12">



              <div class="row">



                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Jacket Size:</label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>



                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>







                    </select>



                  </div>



                </div>







                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Pants size:</label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>







                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>







                    </select>



                  </div>



                </div>











                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Waist Size:







                    </label>



                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>



                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>



                    </select>



                  </div>



                </div>











                <div class="col-sm-2">



                  <div class="form-group demo-form">



                    <label class="control-label">Inseam:</label>







                  </div>



                </div>



                <div class="col-sm-1">



                  <div class="form-group">



                    <select name="city" required>



                      <option value="">0</option>



                      <option value="">1</option>



                      <option value="">2</option>



                      <option value="">3</option>



                      <option value="">3</option>



                      <option value="">4</option>



                      <option value="">5</option>



                    </select>



                  </div>



                </div>



              </div>



            </div>





            <div class="gender-section">

              <div class="row">

                <div class="col-sm-12">

                  <div class="col-sm-2">

                    <div class="form-group demo-form">

                      <label class="control-label">Social Media:</label>

                    </div>

                  </div>

                  <div class="col-sm-4">



                    <div class="form-group">



                      <select name="city" required>

                        <option value="">FB</option>

                        <option value="">Insta</option>

                        <option value="">Twitter</option>

                        <option value="">Linkedin</option>

                        <option value="">IMDB</option>

                        <option value="">Snapchat</option>

                      </select>

                    </div>

                  </div>



                  <div class="col-sm-4">

                    <div class="form-group demo-form">

                      <label class="control-label">Are you willing to work as an extra?</label>

                    </div>

                  </div>

                  <div class="col-sm-2">



                    <div class="form-group">



                      <select name="city" required>

                        <option value="">Yes</option>

                        <option value="">No</option>

                      </select>

                    </div>

                  </div>

                </div>

              </div>

            </div>







            <div class="gender-section">

              <div class="row">

                <div class="col-sm-12">

                  <div class="col-sm-3">

                    <div class="form-group demo-form">

                      <label class="control-label">Willing to cut hair:</label>

                    </div>

                  </div>

                  <div class="col-sm-2">



                    <div class="form-group">



                      <select name="city" required>

                        <option value="">Yes</option>

                        <option value="">No</option>

                      </select>

                    </div>

                  </div>



                  <div class="col-sm-4">

                    <div class="form-group demo-form">

                      <label class="control-label">Eighteen to play younger: </label>

                    </div>

                  </div>

                  <div class="col-sm-2">



                    <div class="form-group">



                      <select name="city" required>

                        <option value="">Yes</option>

                        <option value="">No</option>

                      </select>

                    </div>

                  </div>

                </div>

              </div>

            </div>





            <div class="gender-section">

              <div class="row">

                <div class="col-sm-12">

                  <div class="col-sm-3">

                    <div class="form-group demo-form">

                      <label class="control-label">Do you have a passport? </label>

                    </div>

                  </div>

                  <div class="col-sm-2">



                    <div class="form-group">



                      <select name="city" required>

                        <option value="">Yes</option>

                        <option value="">No</option>

                      </select>

                    </div>

                  </div>



                  <div class="col-sm-4">

                    <div class="form-group demo-form">

                      <label class="control-label">Work Permit (if younger than 18)?</label>

                    </div>

                  </div>

                  <div class="col-sm-2">



                    <div class="form-group">



                      <select name="city">

                        <option value="">Yes</option>

                        <option value="">No</option>

                      </select>

                    </div>

                  </div>

                </div>

              </div>

            </div>



            <div class="gender-section">

              <div class="row">

                <div class="col-sm-12">

                  <div class="col-sm-10">

                    <div class="form-group demo-form">

                      <label class="control-label">I am interested in roles with partial or full nudity?</label>

                    </div>

                  </div>

                  <div class="col-sm-2">



                    <div class="form-group">



                      <select name="city">

                        <option value="">Yes</option>

                        <option value="">No</option>

                      </select>

                    </div>

                  </div>

                </div>

              </div>

            </div>



            <?php

            if (!empty($_GET['talent-manager'])) {

              $query = "SELECT firstname, lastname, location FROM agency_profiles WHERE user_id=92";

              $result = @mysql_query($query); //print_r($result);

              if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {

                //echo '<span class="AGENCYRed" style="font-weight:bold">Welcome, ' . $row . ' ' . $row['lastname'] . '!</span>';

              }

            ?>

              <div class="biographical-section">



                <div class="row">



                  <div class="col-sm-12">



                    <div class="col-sm-3">



                      <div class="form-group demo-form">



                        <label class="control-label">Talent Manager Name:</label>



                      </div>



                    </div>



                    <div class="col-sm-6">



                      <div class="form-group">



                        <input type="text-area" placeholder="" value="<?= $row['firstname'] ?>" readonly="readonly">



                      </div>



                    </div>



                  </div>



                </div>



              </div>

            <?php } ?>



















          </div>



















          <div style="overflow:inherit;">

            <div style="float:right;">

              <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>

              <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>

            </div>

          </div>



          <div style="text-align:center;margin-top:60px;">

            <span class="step"></span>

            <span class="step"></span>

            <span class="step"></span>



          </div>

        </form>

      </div>

    </div>

  </div>



  <script>
    var currentTab = 0; // Current tab is set to be the first tab (0)

    showTab(currentTab); // Display the current tab



    function showTab(n) {

      // This function will display the specified tab of the form...

      var x = document.getElementsByClassName("tab");

      x[n].style.display = "block";

      //... and fix the Previous/Next buttons:

      if (n == 0) {

        document.getElementById("prevBtn").style.display = "none";

      } else {

        document.getElementById("prevBtn").style.display = "inline";

      }

      if (n == (x.length - 1)) {

        document.getElementById("nextBtn").innerHTML = "Submit";

      } else {

        document.getElementById("nextBtn").innerHTML = "Next";

      }

      //... and run a function that will display the correct step indicator:

      fixStepIndicator(n)

    }



    function nextPrev(n) {

      // This function will figure out which tab to display

      var x = document.getElementsByClassName("tab");

      // Exit the function if any field in the current tab is invalid:

      if (n == 1 && !validateForm()) return false;

      // Hide the current tab:

      x[currentTab].style.display = "none";

      // Increase or decrease the current tab by 1:

      currentTab = currentTab + n;

      // if you have reached the end of the form...

      if (currentTab >= x.length) {

        // ... the form gets submitted:

        document.getElementById("regForm").submit();

        return false;

      }

      // Otherwise, display the correct tab:

      showTab(currentTab);

    }



    function validateForm() {

      // This function deals with validation of the form fields

      var x, y, i, valid = true;

      x = document.getElementsByClassName("tab");

      y = x[currentTab].getElementsByTagName("input");

      // A loop that checks every input field in the current tab:

      for (i = 0; i < y.length; i++) {

        // If a field is empty...

        if (y[i].value == "") {

          // add an "invalid" class to the field:

          y[i].className += " invalid";

          // and set the current valid status to false

          valid = false;

        }

      }

      // If the valid status is true, mark the step as finished and valid:

      if (valid) {

        document.getElementsByClassName("step")[currentTab].className += " finish";

      }

      return valid; // return the valid status

    }



    function fixStepIndicator(n) {

      // This function removes the "active" class of all steps...

      var i, x = document.getElementsByClassName("step");

      for (i = 0; i < x.length; i++) {

        x[i].className = x[i].className.replace(" active", "");

      }

      //... and adds the "active" class on the current step:

      x[n].className += " active";

    }
  </script>







  <?php



  @include('footer.php');



  ?>