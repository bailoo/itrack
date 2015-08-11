<?php
	include_once('util_android_php_mysql_connectivity.php');  	   //util_session_variable.php sets values in session
	include_once('util_android_session_variable.php');   //util_php_mysql_connectivity.php make set connection of user to database  
	set_time_limit(300);	
	include_once("android_calculate_distance.php");
	include_once("android_select_landmark_report.php");
	include_once("util_android_hr_min_sec.php");   
	$google_place=explode(":",$geocodedPostcodes);
	//$google_place=$_SESSION['place_name_halt_arr1'];	
	//unset($_SESSION['place_name_halt_arr1']);
	/*echo "imei1=".$imei_prev."<br>vname1=".$vname_prev."<br>lat1=".$lat_prev."<br>lng1=".$lng_prev."<br>arr_time1=".$arr_time_prev.
		 "<br>dep_time_str=".$dep_time_prev."<br>duration_str=".$duration_prev."google_place=".$geocodedPostcodes."<br>";*/
	$imei=explode(":",$imei_prev);
	$vname=explode(":",$vname_prev);
	$lat=explode(":",$lat_prev);
	$lng=explode(":",$lng_prev);
	$arr_time=explode(",",$arr_time_prev);
	$dep_time=explode(",",$dep_time_prev);
	$in_tmp=explode(",",$in_tmp_prev);
	$out_tmp=explode(",",$out_tmp_prev);
	$duration=explode(":",$duration_prev);			
    $vsize = sizeof($imei); 
		
	for($i=0;$i<(sizeof($imei)-1);$i++)
	{	         							                    
		$sum_halt_hr = $sum_halt_hr + $duration[$i];					
		$lt1 = $lat[$i];
		$lng1 = $lng[$i];
		//echo "lat=".$lt1."lng=".$lng1."<br>";
		$alt1 = "-";		 
		$landmark="";
		//echo "before_landmark=".$landmark."<br>".$lt1.",".$lng1."<br>";
		get_landmark($lt1,$lng1,&$landmark);    // CALL LANDMARK FUNCTION
		//echo "after_landmark=".$landmark."<br>";
		$place = $landmark;
		if($place=="")
		{
				$place = preg_replace('/भारत गणराज्य/', '', $google_place[$i]);
				$place = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $google_place[$i]);
				//get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
		}
		
		$placename[$i] = $place;	
		$hms1 = secondsToTime($duration[$i]);
		$duration_tmp = $hms1[h].":".$hms1[m].":".$hms1[s];		
		$latLng=$lt_tmp.", ".$lng_tmp;
		
		$halt_report_data[]=array("deviceImeiNo"=>$imei[$i],"location"=>$placename[$i],"vehicleName"=>$vname[$i],"arrivalTime"=>$arr_time[$i],"departureTime"=>$dep_time[$i],"inTemperature"=>$in_tmp[$i],"outTemperature"=>$out_tmp[$i],"haltDuration"=>$duration_tmp,"latitudeLongitude"=>$latLng);		
	}
	echo json_encode($halt_report_data);
?> 
	
