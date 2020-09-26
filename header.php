<div class="top-header container-fluid">
  <!-- <div class="wrapper"> -->
    <div class="logo">
      <a href="index.php"><img src="image/The-agancy-logo2.png"></a>
    </div>
    <div class="social-btn">
      <ul>
        <li><a target="_blank" href="http://www.facebook.com/pages/The-Agency-wwwtheagencyOnlinecom/107832902632632"><img src="image/facebook.png"></a></li>
        <li><a target="_blank" href="https://twitter.com/theagencyOnline"><img src="image/twitter.png"></a></li>
        <li><a target="_blank" href="https://instagram.com/theagencyonline_"><img src="image/instagram.png"></a></li>
        <li><a target="_blank" href="https://www.youtube.com/channel/UCmVOTD_oJ1iRmzDnkjKb6Lw"><img src="image/youtube.png"></a></li>
        <li><a target="_blank" href="https://Linkedin.com/company/theagencyonline"><img src="image/linkedin.png"></a></li>
      </ul>
    </div>
  <!-- </div> -->
</div>
<div class="second-header2 container-fluid">
  <!-- <div class="wrapper"> -->

    <div class="col-sm-6 col-xs-6">
      <div class="search-header-form">
        <form action="new.php" id="cse-search-box" class="form-inline">
          <input type="hidden" name="cx" value="008874514896725589454:j0jwxphmxho" />
          <input type="hidden" name="cof" value="FORID:11" />
          <input type="hidden" name="ie" value="UTF-8" />
          <input type="text" name="q" size="20" class="form-control input-sm input-search-header"/>
          <input type="submit" name="sa" value="Search" class="btn btn-sm btn-flat btn-search-header"/>
        </form>
      </div>
    </div>

    <div class="col-sm-6 col-xs-6">
      <div class="right-box">
        <div class="login">
          <?php if($loggedin){ ?>
            <a href="logout.php">LOG OUT</a>
          <?php }else{ ?>
            <a href="login.php">LOG IN</a>
          <?Php } ?>
        </div>

        <div class="sign">
          <?php if($loggedin){ ?>
          <?php 
            $console = "";
            if($_SESSION['account_type'] == 'admin'){
              $console = "admin_sub";
            }else if($_SESSION['account_type'] == 'super_admin'){
              $_SESSION['superadmin'] = 'Y';
              $console = "adminXYZ";
            }else if($_SESSION['account_type'] == 'talent'){
              $console = "talent";
            }else if($_SESSION['account_type'] == 'talent_manager'){
              $console = "talent-manager";
            }else if($_SESSION['account_type'] == 'client'){
              $console = "casting-manager";
            }
          ?>
          <a href="<?php echo $base_url.$console; ?>">CONSOLE</a>
          <?php }else{ ?>
            <a href="talent_signup.php">SIGN UP</a>
          <?Php } ?>
        </div>

        <span class="clearfix"></span>

        <!-- <div class="login">
          <ul>
            <li class="drop">
              <a href="login.php">LOG IN</a>
            </li>
          </ul>
        </div>
        <div class="sign">
          <ul>
            <li class="drop">
              <a href="talent_signup.php">SIGN UP</a>
            </li>
          </ul>
        </div> -->

      </div>
    </div>

  <!-- </div> -->
</div>

<span class="clearfix"></span>

<!-- <nav class="navbar navbar-default navbar-fixed-top"> -->
<nav class="navbar navbar-default">
  <div class="container1">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle btn btn-flat" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <!-- <i class="fa fa-th fa-2x"></i> -->
      </button>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="About.php">About Us</a></li>
        <li><a href="casting-call.php">Casting Calls</a></li>
        <li><a href="resource.php">Resources</a></li>
        <li><a href="agency_angle.php">The Agency Angle</a></li>
        <li><a href="contacts.php">Contact us</a></li>
        <li><a href="funding.php">Funding Box</a></li>
      </ul>
    </div>
  </div>
</nav>

<span class="clearfix"></span>

<!-- </body>
</html> -->