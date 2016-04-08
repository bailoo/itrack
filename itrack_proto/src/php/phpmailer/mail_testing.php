<?php
require_once "Mail.php";
$from = "Sandra Sender <shamsparwez786@gmail.com>";

$to = "Ramona Recipient <rahulojha05@gmail.com>";

$subject = "Hi!";

$body = "Hi,\n\nHow are you? This is the mail from  linux ";



//$host = "smtp.gmail.com";
$host = "smtp.cc.iitk.ac.in";

//$username = "shamsparwez786@gmail.com";
$username ="iespl_brij";

$password = "brij123";


echo $password;
$headers = array ('From' => $from,

  'To' => $to,

  'Subject' => $subject);

$smtp = Mail::factory('smtp',

  array ('host' => $host,

    'auth' => true,

    'username' => $username,

    'password' => $password));
echo "hi";
$mail = $smtp->send($to, $headers, $body);

echo"mail".$mail;

if (PEAR::isError($mail)) {

  echo("<p>" . $mail->getMessage() . "</p>");

 } else {

  echo("<p>Message successfully sent!</p>");

 }

?>
