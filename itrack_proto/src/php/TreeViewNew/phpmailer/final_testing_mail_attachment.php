<?php 
$attachment=$_POST['attachment'];
//$fileatt = "MBTLA2_ds.pdf"; // Path to the file 
$fileatt = $attachment; // Path to the file 

$fileatt_type = "application/pdf"; // File Type 

$fileatt_name = "dailyreports.pdf"; // Filename that will be used for the file as the attachment 

$youremail=$_POST['youremail'];
$email_from = $youremail; // Who the email is from 

$emailsubject=$_POST['emailsubject'];
$email_subject = $emailsubject; // The Subject of the email 

$yourmessage=$_POST['yourmessage'];
$email_message = $yourmessage; // Message that the email has in it 

$email_to = "astaseen83@gmail.com,shamsparwez2007@yahoo.co.in"; // Who the email is too 


$headers = "From: ".$email_from; 

$semi_rand = md5(time()); 
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
    
$headers .= "\nMIME-Version: 1.0\n" . 
            "Content-Type: multipart/mixed;\n" . 
            " boundary=\"{$mime_boundary}\""; 

$email_message .= "This is a multi-part message in MIME format.\n\n" . 
                "--{$mime_boundary}\n" . 
                "Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
               "Content-Transfer-Encoding: 7bit\n\n" . 
$email_message . "\n\n"; 



/* First File */ 


//$fileatt = "MBTLA2_ds.pdf"; // Path to the file                  
//$fileatt_type = "application/pdf"; // File Type 
//$fileatt_name = "MBTLA2_ds.pdf"; // Filename that will be used for the file as the attachment 

$file = fopen($fileatt,'rb'); 
$data = fread($file,filesize($fileatt)); 
fclose($file); 


$data = chunk_split(base64_encode($data)); 

$email_message .= "--{$mime_boundary}\n" . 
                  "Content-Type: {$fileatt_type};\n" . 
                  " name=\"{$fileatt_name}\"\n" . 
                  //"Content-Disposition: attachment;\n" . 
                  //" filename=\"{$fileatt_name}\"\n" . 
                  "Content-Transfer-Encoding: base64\n\n" . 
                 $data . "\n\n" . 
                  "--{$mime_boundary}\n"; 

$ok = @mail($email_to, $email_subject, $email_message, $headers); 

if($ok) { 
echo "<font face=verdana size=2>The file was successfully sent!</font>"; 
} else { 
die("Sorry the email could not be sent. Please go back and try again!"); 
} 
?>