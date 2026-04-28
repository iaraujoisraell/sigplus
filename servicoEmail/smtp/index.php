<?php

  use phpmMailer\phpmMailer\PHPMailer;
  use phpmMailer\phpmMailer\Exception;
  //require 'vendor/phpmailer/phpmailer/src/Exception.php';
  //require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
  //require 'vendor/phpmailer/phpmailer/src/SMTP.php';
  
  require 'vendor/autoload.php';
  
  $mail = new PHPMailer();
  $mail->IsSMTP();
  echo 'aqui'; exit;
  $mail->SMTPDebug  = 0;  
  $mail->SMTPAuth   = TRUE;
  $mail->SMTPSecure = "tls";
  $mail->Port       = 465;
  $mail->Host       = "mail.sigplus.online";
  $mail->Username   = "araujo@sigplus.online";
  $mail->Password   = "Sigplus2022";

  $mail->IsHTML(true);
  $mail->AddAddress("iaraujo.israel@gmail.com", "Israel Araujo");
  $mail->SetFrom("araujo@sigplus.online", "Sigplus");
  //$mail->AddReplyTo("reply-to-email", "reply-to-name");
  //$mail->AddCC("cc-recipient-email", "cc-recipient-name");
  $mail->Subject = "Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
  $content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";

  $mail->MsgHTML($content); 
  if(!$mail->Send()) {
    echo "Error while sending Email.";
    var_dump($mail);
  } else {
    echo "Email sent successfully";
  }
?>