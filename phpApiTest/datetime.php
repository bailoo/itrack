<?php

	$date = new DateTime('2015-06-15 20:06:00');
	echo $date->format('U');
	echo "\n";
	
	date_default_timezone_set('UTC');

	$date = new DateTime('2015-06-15 20:08:00');
	echo $date->format('U');
	echo "\n";
	

?>
