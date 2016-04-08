<?php

include('Mail.php');
include('Mail/mime.php');
//include_once("util_php_mysql_connectivity.php");

//require_once "Mail.php";
//require_once "Mail/mime.php";
$crlf = "\n";
echo "please wait.. \n";
/*$attachment=$_POST['attachment'];
$fileatt = $attachment; // Path to the file 
$fileatt_type = "application/pdf"; // File Type 
$fileatt_name = "report.pdf"; // Filename that will be used for the file as the attachment 
$youremail=$_POST['youremail'];
$email_from = $youremail; // Who the email is from 
$emailsubject=$_POST['emailsubject'];
$email_subject = $emailsubject; // The Subject of the email 
$yourmessage=$_POST['yourmessage'];
$email_message = $yourmessage; // Message that the email has in it 
$email_to = "astaseen83@gmail.com"; // Who the email is too 
*/

//date_default_timezone_set('Asia/Calcutta');
$datetime_sent =date("Y-m-d H:i:s");	

$query = "SELECT * FROM mail_info_visit_report WHERE status=1";
//echo "<br>query=".$query;

$result = mysql_query($query,$DbConnection);

while($row = mysql_fetch_object($result))
{
  //echo "<br>in while";
  /*
  $fileatt = "PEAR.php"; // Path to the file 
  $fileatt_type = "application/pdf/php"; // File Type 
  $fileatt_name = "peartmp.php"; // Filename that will be used for the file as the attachment 
  $email_from = "iespltest@gmail.com"; // Who the email is from 
  $email_subject = "TEST SUBJECT"; // The Subject of the email 
  $email_message = "TEST MESSAGE"; // Message that the email has in it 
  $email_to = "taseen@iembsys.com,rizwan@iembsys.com"; // Who the email is too 
  */
  
  $sno = $row->sno;
  $fileatt1[] = $row->fileatt; // Path to the file 
  $fileatt_type1[] = $row->fileatt_type; // File Type 
  $fileatt_nam1[] = $row->fileatt_name; // Filename that will be used for the file as the attachment 
  $email_from1[] = $row->email_from; // Who the email is from 
  //$email_from1[] = "iespl_test@gmail.com";
  $email_subject1[] = $row->email_subject; // The Subject of the email 
  $email_message1[] = $row->email_message; // Message that the email has in it 
  $email_to1[] = $row->email_to; // Who the email is too 
  
  $query = "UPDATE mail_info_visit_report SET datetime_sent='$datetime_sent',status=0 WHERE sno='$sno'";
  $result1 = mysql_query($query,$DbConnection);
}

//echo "<br>size=".sizeof($email_to1);

for($i=0;$i<sizeof($email_to1);$i++)
{
  $fileatt = $fileatt1[$i];
  $fileatt_type = $fileatt_type1[$i];
  $fileatt_nam = $fileatt_nam1[$i];
  $email_from = $email_from1[$i];
  $email_subject = $email_subject1[$i];
  $email_message = $email_message1[$i];
  $email_to = $email_to1[$i];
  
  //echo "<br>email_to=".$email_to." ,email_subject=".$email_subject." ,email_message=".$email_message." ,email_from=".$email_from;
  
  if($email_to!="")
  {
    $headers = array(
    					'From' => $email_from,//email_from
    					'To'   => $email_to, //email_from
    					'Subject' => $email_subject //email_subject
    				);
    				
    // Creating the Mime message
     $mime = new Mail_mime($crlf);
     // Setting the body of the email
     $mime->setTXTBody($email_message);
     //$mime->setHTMLBody($html);
     // Add an attachment
    	$file_name = $fileatt_name;
    	$file = $fileatt;
    	 
    	$mime->addAttachment ($file, $fileatt_type, $file_name, true, "base64");
    
    // Set body and headers ready for base mail class 
     $email_message = $mime->get();
     $headers = $mime->headers($headers);
    //echo $headers;
     // SMTP authentication params
    
     /*$host="smtp.cc.iitk.ac.in";
     $port="25";
     $username="iespl_brij";
     $password="riz_1234";
    	echo $headers;    */
    
     /*$host="smtp.gmail.com";
     $port="465";
     $username="iespltest@gmail.com";
     $password="iespl_iembsys";*/
     
     $host="smtp.gmail.com";
     $port="465";
     $username="support@iembsys.co.in";
     $password="supq1w2e3r4";
     
    	//echo $headers; 
    $smtp = Mail::factory ('smtp', array ('host'=> $host, 'auth' => true, 'username' => $username , 'password' => $password) );
    //echo $smtp;
    $mail = $smtp->send($email_to,$headers,$email_message);
    //echo $smtp;
    //echo $mail;
    if(PEAR::isError($mail))
    {
    	echo("\nError sending Message!\n");
    }
    else
    {
    	echo("\nMessage delivered succesfully!\n");
    }
  
  } // if email_to closed
  unlink($fileatt);
} //for closed

?>
