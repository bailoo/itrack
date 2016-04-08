<?php
/**
* Simple example script using PHPMailer with exceptions enabled
* @package phpmailer
* @version $Id$
*/

require 'PHPMailer_5.2.4/class.phpmailer.php';

try 
{
	$mail = new PHPMailer(true); //New instance, with exceptions enabled

	$body             = file_get_contents('PHPMailer_5.2.4/test/contents.html');
	$body             = preg_replace('/\\\\/','', $body); //Strip backslashes

	$mail->IsSMTP();                           // tell the class to use SMTP
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Port       = 25;                    // set the SMTP server port
	$mail->Host       = "localhost"; // SMTP server
	$mail->Username   = "shams.parwez@iembsys.com";     // SMTP server username
	$mail->Password   = "engineer";            // SMTP server password

	$mail->IsSendmail();  // tell the class to use Sendmail

	$mail->AddReplyTo("rizwan@iembsys.com","First Last");

	$mail->From       = "shams.parwez@iembsys.com";
	$mail->FromName   = "First Last";

	$to = "rizwan@iembsys.com";

	$mail->AddAddress($to);

	$mail->Subject  = "First PHP Mailer Message";

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->WordWrap   = 80; // set word wrap

	$mail->MsgHTML($body);

	$mail->IsHTML(true); // send as HTML

	$mail->Send();
	echo 'Message has been sent.';
} 
catch (phpmailerException $e) 
{
	echo $e->errorMessage();
}
?>