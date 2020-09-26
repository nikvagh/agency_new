<?php
include('header_code.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Contact Us</title>
  <?php include('head.php'); ?>
  <?php include('common_css.php'); ?>
</head>

<body>
  <?php

  @include('header.php');
  ?>


  <div class="main-slider">
    <div id="myCarousel" class="carousel slide carousel-fade" data-ride="carousel">

      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
        <li data-target="#myCarousel" data-slide-to="3"></li>
        <li data-target="#myCarousel" data-slide-to="4"></li>
      </ol>

      <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img src="image/3.jpg" alt="New York" class="img-reponsive">
          <div class="carousel-caption">
            <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2>
            <hr>
            <p>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</p>
            <div class="apply">
              <a href="#" class="btn"><i class="fa fa-backward"></i> &nbsp; APPLY NOW &nbsp; <i class="fa fa-forward"></i></a>
            </div>
          </div>
        </div>

        <div class="item">
          <img src="image/2.jpg" alt="Chicago" class="img-reponsive">
          <div class="carousel-caption">
            <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2>
            <hr>
            <p>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</p>
            <div class="apply">
              <a href="#" class="btn"><i class="fa fa-backward"></i> &nbsp; APPLY NOW &nbsp; <i class="fa fa-forward"></i></a>
            </div>
          </div>
        </div>

        <div class="item">
          <img src="image/5.jpg" alt="Los Angeles" class="img-reponsive">
          <div class="carousel-caption">
            <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2>
            <hr>
            <p>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</p>
            <div class="apply">
              <a href="#" class="btn"><i class="fa fa-backward"></i> &nbsp; APPLY NOW &nbsp; <i class="fa fa-forward"></i></a>
            </div>
          </div>
        </div>

        <div class="item">
          <img src="image/6.jpg" alt="Los Angeles" class="img-reponsive">
          <div class="carousel-caption">
            <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2>
            <hr>
            <p>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</p>
            <div class="apply">
              <a href="#" class="btn"><i class="fa fa-backward"></i> &nbsp; APPLY NOW &nbsp; <i class="fa fa-forward"></i></a>
            </div>
          </div>
        </div>

        <div class="item">
          <img src="image/1.jpg" alt="Los Angeles" class="img-reponsive">
          <div class="carousel-caption">
            <h2>WELCOME TO THE AGENCY <br>NOW BETTER THAN EVER....</h2>
            <hr>
            <p>"The Agency really cares about finding you work.<br>I've booked jobs without even having to submit!"</p>
            <div class="apply">
              <a href="#" class="btn"><i class="fa fa-backward"></i> &nbsp; APPLY NOW &nbsp; <i class="fa fa-forward"></i></a>
            </div>
          </div>
        </div>


      </div>

    </div>



  </div>

  <?php
  $sent = false;

  if (!empty($_POST['submitcontact'])) {
    // Your code here to handle a successful verification
    $name = $_POST['name'];
    $company = $_POST['company'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $notes = $_POST['notes'];

    $msg = '';
    if (empty($name)) {
      $msg .= '<p>Please Enter Your Name</p>';
    }

    if (!eregi('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
      $msg .= '<p>Please Enter a valid Email address</p>';
    }

    if (empty($msg)) {
      $mailmessage = '<body><b>Name</b>: ' . $name  . '<br />
              <b>Company</b>: ' . $company  . '<br />
              <b>Email</b>: ' . $email  . '<br />
              <b>Phone</b>: ' . $phone  . '<br /><br />
              <b>Notes</b>: ' . nl2br($notes) . '</body>';


      $subject = 'AgencyOnline: Contact Form';
      $toemail = 'info@theagencyonline.com';

      // $toemail = 'ungabo@yahoo.com';

      require_once('PHPMailer/class.phpmailer.php');

      $mail             = new PHPMailer(); // defaults to using php "mail()"

      $body             = $mailmessage; // file_get_contents('contents.html');
      // $body             = preg_replace('/[\]/','',$body);
      // echo $body;

      $mail->SetFrom($toemail, 'The Agency Online');

      $mail->AddReplyTo($email, $name);

      $address = $toemail;
      $mail->AddAddress($address, "AGENCY: Contact Form");

      $mail->Subject    = $subject;

      $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

      $mail->MsgHTML($body);


      if (!$mail->Send()) {
        // $msg .= "Mailer Error: " . $mail->ErrorInfo;
        // $sent = false;
        $sent = true;
      } else {
        // echo "Message sent!";
        $sent = true;
      }
    }
  }
  if (!empty($msg)) {
  ?>
    <div id="failmessage" class="text-center" style="padding:10px;background:#a94442;color:#fff">
      <br />
      There was a problem with your information. Please review and try again.
      <b><?php echo $msg; ?></b>
      <a href="javascript:void(0)" onclick="document.getElementById('failmessage').style.display='none'" class="btn btn-danger">close</a>
      <br />
    </div>
  <?php
  }
  if ($sent) {
    echo '<div align="center" style="padding:10px;background:#28a745;color:#fff"><b>THANK YOU FOR CONTACTING US.</b></div>';
    $_POST = array();
  } 
  // else {
  ?>

    <section id="contact">
      <div class="section-content">
        <h1 class="section-header">Get in <span class="content-header wow fadeIn " data-wow-delay="0.2s" data-wow-duration="2s"> Touch with us</span></h1>
        <h3>Have questions or need help from the Agency's staff?</h3>
        <h4>Send us a message and we'll get back to you as soon as we can.</h4>
      </div>
      <div class="contact-section">
        <div class="container">
          <form method="post" action="contacts.php">
            <div class="col-md-6 form-line">
              <div class="form-group">
                <label for="exampleInputUsername">Your name</label>
                <input type="text" class="form-control" size="25" required="required" name="name" <?php if (!empty($_POST['name'])) echo 'value="' . $_POST['name'] . '"'; ?> placeholder="Enter Your Nmae">
              </div>
              <div class="form-group">
                <label for="exampleInputUsername">Company name</label>
                <input type="text" class="form-control" required="required" id="" name="company" size="25" <?php if (!empty($_POST['company'])) echo 'value="' . $_POST['company'] . '"'; ?> placeholder=" Enter Your Company Name">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail">Email Address</label>
                <input type="email" class="form-control" required="required" id="exampleInputEmail" name="email" size="25" <?php if (!empty($_POST['email'])) echo 'value="' . $_POST['email'] . '"'; ?> placeholder=" Enter Your Email Address">
              </div>
              <div class="form-group">
                <label for="telephone">Contact Number</label>
                <input type="tel" class="form-control" required="required" id="telephone" name="phone" size="25" <?php if (!empty($_POST['phone'])) echo 'value="' . $_POST['phone'] . '"'; ?> placeholder=" Enter Contact Number">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="description">Your Message</label>
                <textarea class="form-control" required="required" id="description" name="notes" placeholder="Enter Your Message"><?php if (!empty($_POST['notes'])) echo $_POST['notes']; ?></textarea>
              </div>
              <div>
                <input type="hidden" name="submitcontact" value="1" />
                <button type="submit" value="submit" class="btn btn-default submit"><i class="fa fa-paper-plane" aria-hidden="true"></i> Send Message</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>

  <?php
  // }

  include('footer_js.php');
  @include('footer.php');
  ?>

</body>

</html>