<?php

	$to ='atul.malhotra@motherdairy.com,newtest2@motherdairy.com,Itadmin@motherdairy.com,Ithelpdesk.ppg@motherdairy.com,ved.raturi@motherdairy.com';
	$subject = 'TEST SUBJECT';
	$message = 'TEST MESSAGE'; 
	$random_hash = md5(date('r', time()));  
	$headers = "From: support@iembsys.co.in\r\n";
	$headers .= "Cc: rizwan@iembsys.com";  

	mail($to, $subject, $message, $headers);
?>

