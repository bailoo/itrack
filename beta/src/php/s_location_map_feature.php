<?php
	$time_microsecond=microtime();
	$time_microsecond=explode(" ",$time_microsecond);
	
	$pathtowrite="src/php/schedule_location_data/".$account_id."_location_".$time_microsecond[1].".xml";
	//echo "path=".$pathtowrite."<br>";
	$fh = fopen($pathtowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);
  
	$query = "SELECT DISTINCT location_id,location_name,geo_point FROM schedule_location WHERE account_id='$account_id' AND status=1";
	$result = mysql_query($query,$DbConnection);
	//echo $query."<br>";  
	$size=0;
	while($row=mysql_fetch_object($result))
	{
		$location_id[$size]=$row->location_id;
		$location_name[$size]=$row->location_name;
		$geo_point = $row->geo_point;
		$type[$size] = $row->type;  	
		$geo_point1 = explode(',',$geo_point);
		$lat[$size]= trim($geo_point1[0]);
		$lng[$size]= trim($geo_point1[1]);  	
		$size++;
	}	
	$fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append 
   
	for($i=0;$i<$size;$i++)
	{
		//$station[$i] = "abc";
		//$line = "\n".$line.'< marker lat="'.trim($lat[$i]).'" lng="'.trim($lng[$i]).'" station="'.$station[$i].'" customer="'.$customer[$i].'"/>';
		$location_name[$i] = str_replace('/', 'by', $location_name[$i]);
		$location_name[$i] = str_replace('\\', 'by', $location_name[$i]);
		$location_name[$i] = str_replace('&', 'and', $location_name[$i]);
		$linetowrite = "\n<marker lat=\"".trim($lat[$i])."\" lng=\"".trim($lng[$i])."\" location_name=\"".trim($location_name[$i])."\"/>";
		fwrite($fh, $linetowrite);  
		//echo "In loop";     	
	} //loop $j closed

	fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
	fwrite($fh, "\n</t1>");  
	fclose($fh);
	 	
	$_SESSION['unique_schedule_location'] = $account_id."_location_".$time_microsecond[1];
?>
