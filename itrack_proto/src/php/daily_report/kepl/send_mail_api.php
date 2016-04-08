<?php 
/*$to = 'rizwan@iembsys.com'; 
$subject = 'VTS_REPORT_EXPERGROUP_'.$previous_date;
$message = 'VTS_REPORT_EXPERGROUP_'.$previous_date; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
//$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
$headers .= "Cc: jyoti.jaiswal@iembsys.com";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title.".xls";
$file_path = $file_path.".xls"; */
echo "\nFILE PATH2=".$file_path;
$attachment = chunk_split(base64_encode(file_get_contents($file_path))); 


ob_start(); //Turn on output buffering 
?> 
--PHP-mixed-<?php echo $random_hash; ?>  
Content-Type: multipart/alternative; boundary="PHP-alt-<?php echo $random_hash; ?>" 

--PHP-alt-<?php echo $random_hash; ?>  
Content-Type: text/plain; charset="iso-8859-1" 
Content-Transfer-Encoding: 7bit


--PHP-alt-<?php echo $random_hash; ?>  
Content-Type: text/html; charset="iso-8859-1" 
Content-Transfer-Encoding: 7bit

<h2><?php echo $message; ?></h2> 

--PHP-alt-<?php echo $random_hash; ?>-- 

--PHP-mixed-<?php echo $random_hash; ?>  
Content-Type: application/zip; name="<?php echo $filename_title; ?>"  
Content-Transfer-Encoding: base64  
Content-Disposition: attachment  

<?php echo $attachment; ?> 
--PHP-mixed-<?php echo $random_hash; ?>-- 

<?php 
//copy current buffer contents into $message variable and delete current output buffer 
$message = ob_get_clean(); 
//send the email 
$mail_sent = @mail( $to, $subject, $message, $headers ); 
//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed" 
echo $mail_sent ? "Mail sent" : "Mail failed"; 
?>
