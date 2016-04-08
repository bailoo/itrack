<?php 
//copy current buffer contents into $message variable and delete current output buffer 
$message = "Hi";
$to = "rizwan@iembsys.com";
$subject = "TestSub";
//$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
//$headers .= "Cc: rizwan@iembsys.com";  
$headers .= "Cc: rizwan@iembsys.com";	
//$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
					
//$message = ob_get_clean(); 
//send the email 
$mail_sent = @mail( $to, $subject, $message, $headers ); 
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
echo $mail_sent ? "Mail sent" : "Mail failed"; 
?>
