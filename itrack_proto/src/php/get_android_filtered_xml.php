<?php	
	include_once('util_session_variable.php');	
	include_once('googleAndroidMapApi.php');
	$lineTmpTrack="";
	$lat_arr_last=array();
	$lat_arr_last=array();
	if($processType=="RAW DATA")
	{
		//echo "in if";
		$routeFilePath="demo_group/raw_data/".$fileName;		
		$flag=1;
	}
	else if($processType=="FILTERED DATA")
	{
		//echo "in if 1";
		$routeFilePath="demo_group/filtered_data/".$fileName;	
		$flag=1;
	}
	else
	{
		//echo "in if 2";
		$routeFilePath1="demo_group/raw_data/".$fileName;
		$routeFilePath2="demo_group/filtered_data/".$fileName;
		$flag=2;
	}
	if($flag==1) /// this for both raw and filtered data
	{
		//echo "in flag 1";
		getTrackMap($routeFilePath,&$lineTmpTrack);
		$lineF=explode("@",substr($lineTmpTrack,0,-1));					
		for($n=0;$n<(sizeof($lineF)-1);$n++)
		{
			$line1=explode(",",$lineF[$n]);	
			$lat_arr_last[]=$line1[0];
			$lng_arr_last[]=$line1[1];	
		}	
		$googleMapthisapi=new GoogleMapHelper();								
		echo $googleMapthisapi->addMultipleMarker("map_canvas",$lat_arr_last,$lng_arr_last,$campareType);
	}
	else if($flag==2)
	{
		//echo "in flag 2";
		if($campareType==1)
		{
			//echo "in campareType 1";
			getTrackMap($routeFilePath1,&$lineTmpTrack);
			$lineF=explode("@",substr($lineTmpTrack,0,-1));					
			for($n=0;$n<(sizeof($lineF)-1);$n++)
			{
				$line1=explode(",",$lineF[$n]);	
				$lat_arr_last[]=$line1[0];
				$lng_arr_last[]=$line1[1];	
			}		
			$googleMapthisapi=new GoogleMapHelper();								
			echo $googleMapthisapi->addMultipleMarker("map_canvas",$lat_arr_last,$lng_arr_last,$campareType);
		}
		if($campareType==2)
		{
			//echo "in campareType 2";
			getTrackMap($routeFilePath2,&$lineTmpTrack);
			$lineF=explode("@",substr($lineTmpTrack,0,-1));					
			for($n=0;$n<(sizeof($lineF)-1);$n++)
			{
				$line1=explode(",",$lineF[$n]);	
				$lat_arr_last[]=$line1[0];
				$lng_arr_last[]=$line1[1];	
			}		
			$googleMapthisapi=new GoogleMapHelper();								
			echo $googleMapthisapi->addMultipleMarker("map_canvas",$lat_arr_last,$lng_arr_last,$campareType);
		}		
	}
	
	
	function getTrackMap($routeFilePath,&$lineTmpTrack)
	{
		$xml = @fopen($routeFilePath, "r") or $fexist = 0;
		if (file_exists($routeFilePath)) 
		{      
			while(!feof($xml))          // WHILE LINE != NULL
			{
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
				//$line1=explode(",",$line);
				//echo "lat=".$line1[0]." lng=".$line1[1]."<br>";
				
				$lineTmpTrack=$lineTmpTrack.$line."@";
			}	   // while closed
		} // if original_tmp exist closed   
		fclose($xml);            
		//unlink($xml_original_tmp);
	}
	
?>