<?php
  function send_mail($to_email,$subject,$msg){
      // echo "send_mail";
      $timecode = strtotime("NOW");
      $timecode = md5($timecode);

      // $from = "no-reply@theagencyonline.com";
      // $headers = "From: ".$from;
      // $headers .= "Content-type: text/html\r\n";

      $headers = 'To: $to_email' . "\r\n";
      $headers .= 'From: $from' . "\r\n";
      $headers .= "Content-type: text/html;\r\n";

      $message = '<html><body>'.$msg.'</body></html>';

      // echo $to_email;
      // echo "<br/>";
      // echo $subject;
      // echo "<br/>";
      // echo $message;
      // echo "<br/>";
      // exit;
      
      if(mail($to_email, $subject, $message, $headers)){
        return true;
      }else{
        return false;
      }
  }
?>