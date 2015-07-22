<?php
$url = 'https://api.sendgrid.com/';
$user = 'iespl';
$pass = 'mailiespl2015';

$params = array(
    'api_user'  => $user,
    'api_key'   => $pass,
    'to'        => 'rizwan@iembsys.com',
    'subject'   => 'testing from curl',
    'html'      => 'testing body',
    'text'      => 'testing body',
    'from'      => 'support@iembsys.com',
  );

print_r($params);
$request =  $url.'api/mail.send.json';

// Generate curl request
$session = curl_init($request);
// Tell curl to use HTTP POST
curl_setopt ($session, CURLOPT_POST, true);
// Tell curl that this is the body of the POST
curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
// Tell curl not to return headers, but do return the response
curl_setopt($session, CURLOPT_HEADER, false);
// Tell PHP not to use SSLv3 (instead opting for TLS)
curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response = curl_exec($session);
print("RES=".$response);
curl_close($session);

// print everything out
print_r($response);
/*
$url = 'https://api.sendgrid.com/';
$user = 'iespl';
$pass = 'mailiespl2015';

//$fileName = 'D:\\BACKUP_MOTHERDAIRY_REPORTS/TEST_HOURLY_REPORT_MORNING_MOTHER_DELHI.xlsx';
$fileName = 'TEST_HOURLY_REPORT_MORNING_MOTHER_DELHI.xlsx';
$filePath = dirname(__FILE__);

$params = array(
    'api_user'  => $user,
    'api_key'   => $pass,
    'to'        => 'rizwan@iembsys.com',
    'subject'   => 'Test of file sends',
    'html'      => '<p> the HTML </p>',
    'text'      => 'the plain text',
    'from'      => 'example@sendgrid.com',
    //'files['.$fileName.']' => '@'.$fileName
    'files['.$fileName.']' => '@'.$filePath.'/'.$fileName
  );

print_r($params);

$request =  $url.'api/mail.send.json';

// Generate curl request
$session = curl_init($request);

// Tell curl to use HTTP POST
curl_setopt ($session, CURLOPT_POST, true);

// Tell curl that this is the body of the POST
curl_setopt ($session, CURLOPT_POSTFIELDS, $params);

// Tell curl not to return headers, but do return the response
curl_setopt($session, CURLOPT_HEADER, false);
// Tell PHP not to use SSLv3 (instead opting for TLS)
curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response = curl_exec($session);
curl_close($session);

// print everything out
print_r($response);
*/

$sendgrid = new SendGrid($api_user, $api_key);
$email    = new SendGrid\Email();

$email->addTo("test@sendgrid.com")
      ->setFrom("you@youremail.com")
      ->setSubject("Sending with SendGrid is Fun")
      ->setHtml("and easy to do anywhere, even with PHP");

$sendgrid->send($email);

?>