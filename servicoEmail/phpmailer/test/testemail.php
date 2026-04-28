<?php
/**
* Simple example script using PHPMailer with exceptions enabled
* @package phpmailer
* @version $Id$
*/

require '../class.phpmailer.php';

try {
	$mail = new PHPMailer(true); //New instance, with exceptions enabled

	$body             = file_get_contents('contents.html');
	$body             = preg_replace('/\\\\/','', $body); //Strip backslashes
        
        $_Host 		=  gethostbyname("smtp.office365.com");
        $_Username 	=  "webmaster@unimedmanaus.coop.br";
        $_Password 	= "W3b@Un!m3d";
        
	$mail->IsSMTP();                           // tell the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 587;                    // set the SMTP server port
	$mail->Host       = "smtp.office365.com"; // SMTP server
	$mail->Username   = "webmaster@unimedmanaus.coop.br";     // SMTP server username
	$mail->Password   = "W3b@Un!m3d";            // SMTP server password
        $mail->SMTPSecure = 'ssl';

	//$mail->IsSendmail();  // tell the class to use Sendmail

	$mail->AddReplyTo("name@domain.com","First Last");

	$mail->From       = "webmaster@unimedmanaus.coop.br";
	$mail->FromName   = "First Last";

	$to = "iaraujo.israel@gmail.com";

	$mail->AddAddress($to);

	$mail->Subject  = "First PHPMailer Message";

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 80; // set word wrap

	$mail->MsgHTML($body);

	$mail->IsHTML(true); // send as HTML

	$mail->Send();
	echo 'Message has been sent.';
} catch (phpmailerException $e) {
	echo $e->errorMessage();
}
?>