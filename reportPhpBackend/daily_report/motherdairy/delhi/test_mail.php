<?php

	//$to = 'Shreya.Shinde@motherdairy.com,Vijay.Singh@motherdairy.com,Ravindra.Negi@motherdairy.com,teetu.kumar@motherdairy.com,call1.center@motherdairy.com,ashish.sawhney@motherdairy.com,Kapil.dhaka@motherdairy.com,ashish@iembsys.co.in';
//	//$to = 'atul.malhotra@motherdairy.com,ashish@iembsys.co.in,rizwan@iembsys.com';
	//$to = 'atul.malhotra@motherdairy.com,newtest2@motherdairy.com,Itadmin@motherdairy.com,ved.raturi@motherdairy.com,Ithelpdesk.ppg@motherdairy.com,rizwan@iembsys.com';
	//$to = 'Newtest2@motherdairy.com,ITadmin@motherdairy.com,Itadmin.its@motherdairy.com,ved.raturi@motherdairy.com';
	//$to="hourlyreport4@gmail.com";
	//$to ="Vijay.Singh@motherdairy.com,Rahul.Tiwari@motherdairy.com,ashish@iembsys.co.in,Hakikat.Goyal@motherdairy.com";
	$to ="rizwan@iembsys.com";
	$subject = 'TEST SUBJECT';
	$message = 'TEST MESSAGE'; 
	$random_hash = md5(date('r', time()));  
	$headers = "From: support@iembsys.co.in\r\n";
	$headers .= "Cc: rizwan@iembsys.com";  

	mail($to, $subject, $message, $headers);
?>

