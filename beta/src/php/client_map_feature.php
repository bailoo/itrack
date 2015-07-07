<?php
	$time_microsecond=microtime();
	$time_microsecond=explode(" ",$time_microsecond);
	$pathtowrite="src/php/client_map_feature_data/".$account_id."_customer_".$time_microsecond[1].".xml";
	//echo "path=".$pathtowrite."<br>";
	
	
	/*$pathtowrite_2="src/php/client_map_feature_data/".$account_id."_schedule_location.xml";
	//echo "path=".$pathtowrite."<br>";
	$fh12 = fopen($pathtowrite_2, 'w') or die("can't open file 3"); // new
	fwrite($fh12, "<t1>");  
	fclose($fh12);*/
	
	$fh = fopen($pathtowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);
  
	$query = "SELECT DISTINCT station_name,station_coord,customer_no,type FROM station WHERE user_account_id='$account_id' AND type='0' AND status=1";
	$result = mysql_query($query,$DbConnection);
	//echo $query."<br>";  
	$size=0;
	$customerArr=array();
	while($row=mysql_fetch_object($result))
	{
            //$station[$size]=preg_replace('/[^a-zA-Z0-9-]/', '', $row->station_name);
            //$station[$size]=preg_replace('/[^a-zA-Z0-9-]/', '', $row->station_name);
            if($row->customer_no=="12")
            {
                echo "true";
            }
            $station[$size]=$row->station_name;
            $customer[$size]=$row->customer_no;
            $coord = $row->station_coord;
            $type[$size] = $row->type;  	
            $coord1 = explode(',',$coord);
            $lat[$size]= substr(trim($coord1[0]),0,-1);
            $lng[$size]= substr(trim($coord1[1]),0,-1);
            $customerArr[trim($row->customer_no)]= "marker lat='".trim($lat[$size])."' lng='".trim($lng[$size])."' station='".str_replace("&","AND",$station[$size])."' customer='".trim($row->customer_no)."'";
            //$customerArr[trim($row->customer_no)]= "marker lat='".trim($lat[$size])."' lng='".trim($lng[$size])."' customer='".trim($row->customer_no)."'";
            $size++;
	}	
	$fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append 
   
	for($i=0;$i<$size;$i++)
	{
            //$station[$i] = "abc";
            //$line = "\n".$line.'< marker lat="'.trim($lat[$i]).'" lng="'.trim($lng[$i]).'" station="'.$station[$i].'" customer="'.$customer[$i].'"/>';
            $station[$i] = str_replace('/', 'by', $station[$i]);
            $station[$i] = str_replace('\\', 'by', $station[$i]);
            $station[$i] = str_replace('&', 'and', $station[$i]);
            $linetowrite = "\n<marker lat=\"".trim($lat[$i])."\" lng=\"".trim($lng[$i])."\" station=\"".str_replace("&","AND",$station[$size])."\" customer=\"".$customer[$i]."\" type=\"".$type[$i]."\"/>";
            fwrite($fh, $linetowrite);  
            //echo "In loop";     	
	} //loop $j closed

	fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
	fwrite($fh, "\n</t1>");  
	fclose($fh);
	 
	$pathtowrite="";
	$fh	= "";
	$query = "";
	$result="";
	$row="";
	
	$pathtowrite="src/php/client_map_feature_data/".$account_id."_plant_".$time_microsecond[1].".xml";
	$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);
  
	$query = "SELECT DISTINCT station_name,station_coord,customer_no,type FROM station WHERE user_account_id='$account_id' AND type='1' AND status=1";
	$result = mysql_query($query,$DbConnection);
	//echo $query."<br>";  
	$size=0;
	//$plantArr=array();
	while($row=mysql_fetch_object($result))
	{
            $station[$size]=$row->station_name;
            $customer[$size]=$row->customer_no;
            $coord = $row->station_coord;
            $type[$size] = $row->type;  	
            $coord1 = explode(',',$coord);
            $lat[$size]= trim($coord1[0]);
            $lng[$size]= trim($coord1[1]);  
            //$plantArr[$row->customer_no]="marker lat='".trim($lat[$size])."' lng='".trim($lng[$size])."' station='".$station[$size]."' customer='".trim($row->customer_no)."'";
            $size++;
	}	
	$fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append 
   
	for($i=0;$i<$size;$i++)
	{
		//$station[$i] = "abc";
		//$line = "\n".$line.'< marker lat="'.trim($lat[$i]).'" lng="'.trim($lng[$i]).'" station="'.$station[$i].'" customer="'.$customer[$i].'"/>';
		$station[$i] = str_replace('/', 'by', $station[$i]);
		$station[$i] = str_replace('\\', 'by', $station[$i]);
		$station[$i] = str_replace('&', 'and', $station[$i]);
		$linetowrite = "\n<marker lat=\"".trim($lat[$i])."\" lng=\"".trim($lng[$i])."\" station=\"".$station[$i]."\" customer=\"".$customer[$i]."\" type=\"".$type[$i]."\"/>";
		fwrite($fh, $linetowrite);  
		//echo "In loop";     	
	} //loop $j closed

	fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
	fwrite($fh, "\n</t1>");  
	fclose($fh);
	
	$pathtowrite="";
	$fh	= "";
	$query = "";
	$result="";
	$row="";
	
	$pathtowrite="src/php/client_map_feature_data/".$account_id."_chillingPlant_".$time_microsecond[1].".xml";
	$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);
  
	$query = "SELECT DISTINCT station_name,station_coord,customer_no,type FROM station WHERE user_account_id='$account_id' AND type='2' AND status=1";
	$result = mysql_query($query,$DbConnection);
	//echo $query."<br>";  
	$size=0;
	//$plantArr=array();
	while($row=mysql_fetch_object($result))
	{
		$station[$size]=$row->station_name;
		$customer[$size]=$row->customer_no;
		$coord = $row->station_coord;
		$type[$size] = $row->type;  	
		$coord1 = explode(',',$coord);
		$lat[$size]= trim($coord1[0]);
		$lng[$size]= trim($coord1[1]);  
		//$plantArr[$row->customer_no]="marker lat='".trim($lat[$size])."' lng='".trim($lng[$size])."' station='".$station[$size]."' customer='".trim($row->customer_no)."'";
		$size++;
	}	
	$fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append 
   
	for($i=0;$i<$size;$i++)
	{
		//$station[$i] = "abc";
		//$line = "\n".$line.'< marker lat="'.trim($lat[$i]).'" lng="'.trim($lng[$i]).'" station="'.$station[$i].'" customer="'.$customer[$i].'"/>';
		$station[$i] = str_replace('/', 'by', $station[$i]);
		$station[$i] = str_replace('\\', 'by', $station[$i]);
		$station[$i] = str_replace('&', 'and', $station[$i]);
		$linetowrite = "\n<marker lat=\"".trim($lat[$i])."\" lng=\"".trim($lng[$i])."\" station=\"".$station[$i]."\" customer=\"".$customer[$i]."\" type=\"".$type[$i]."\"/>";
		fwrite($fh, $linetowrite);  
		//echo "In loop";     	
	} //loop $j closed

	fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
	fwrite($fh, "\n</t1>");  
	fclose($fh);
	
	$_SESSION['uniqueChillingPlant'] = $pathtowrite;
	
	$_SESSION['unique_client_customer'] = $account_id."_customer_".$time_microsecond[1];
	$_SESSION['unique_client_plant'] = $account_id."_plant_".$time_microsecond[1];
	
	for($k=0;$k<@$size_feature;$k++)
  	{
  		//$feature_id_session[$k];
  		if($feature_name_session_login[$k] == "station")
  		{
  		 $flag_station = 1;
  		  break;
		}
      //echo "<br>feature_name=".$feature_name_session[$k];
    }
	
	//if($account_id==231)
	//echo "flag_station=".$flag_substation."<br>";
	if(@$flag_station==1)
	{
		$fileEMPath="src/php/gps_report/".$account_id."/master";
		$filePath="src/php/gps_report/".$account_id."/master";
		foreach(glob($fileEMPath.'/*.*') as $fileEM) 
		{
			$fileNameEM=explode("/",$fileEM);
			$strPosition = strpos($fileNameEM[5], '#7');
			//echo "efm=".$eveningFileName."<br>";
			if($strPosition!="")
			{
				$eveningFileName=$fileEM;
			}			
			$strPosition = strpos($fileNameEM[5], '#8');
			//echo "mfm=".$morningFileName."<br>";
			if($strPosition!="")
			{
				$morningFileName=$fileEM;
			}
                        $strPosition = strpos($fileNameEM[5], '#6');
			//echo "mfm=".$morningFileName."<br>";
			if($strPosition!="")
			{
                            $transporterName=$fileEM;
			}
		}
		if(file_exists($eveningFileName))
		{
			$pathtowrite="";
			$fh	= "";
			$pathtowrite="src/php/client_map_feature_data/".$account_id."_customerRouteEvening_".$time_microsecond[1].".xml";
			$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
			fwrite($fh, "<t1>"); 
			$file = fopen($eveningFileName,"r");
			$routeArrEvening=array();
			while(! feof($file))
			{
				$csvEveningArr=fgetcsv($file);
				//echo "customer=".$csvEveningArr[0]."routeNo".$csvEveningArr[3]."<br>";
				//echo "customerNo=".$customerArr[74521]."<br>";
				if(trim($csvEveningArr[0])=="12")
				{
					//echo "<br>present in file";
				}
				
				$trimCustomerNo=trim($csvEveningArr[0]);
				if($customerArr[$trimCustomerNo]=="12")
				{
					//echo "<br>present in file customer";
				}
				//echo "val=".$dsfdasfds."<br>";
				if($customerArr[$trimCustomerNo]!="")
				{
					//echo "customerNo=".$customerArr[$trimCustomerNo]."<br>";
					$routeArr=explode("/",$csvEveningArr[3]);
					$routeArrSize=sizeof($routeArr);
					for($i=0;$i<$routeArrSize;$i++)
					{
						$routeArrEvening[$routeArr[$i]]=$routeArr[$i];
						$linetowrite = "\n<".$customerArr[$trimCustomerNo]." routeNo='".$routeArr[$i]."' type='2'/>";
						//echo "<textarea>".$linetowrite."</textarea>";
						fwrite($fh, $linetowrite);
					}
				}		
			}
			fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
			fwrite($fh, "\n</t1>");  
			fclose($fh);
			
			$_SESSION['uniqueCustomerRouteEvening'] = $pathtowrite;
			$_SESSION['uniqueRouteArrEvening'] = $routeArrEvening;
		}
		if(file_exists($morningFileName))
		{
			$pathtowrite="";
			$fh	= "";
			$pathtowrite="src/php/client_map_feature_data/".$account_id."_customerRouteMorning_".$time_microsecond[1].".xml";
			$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
			fwrite($fh, "<t1>");		
			$file = fopen($morningFileName,"r");
			$routeArrMorning=array();
			while(! feof($file))
			{
				$csvMorningArr=fgetcsv($file);	
				//echo "plantNo=".$plantArr[$csvMorningArr[0]]."<br>";
				$trimCustomerNo=trim($csvMorningArr[0]);
				if(trim($csvMorningArr[0])=="12")
				{
					echo "<br>present in file Morning";
				}
				if($customerArr[$trimCustomerNo]!="")
				{
					//echo "plantNo=".$customerArr[$trimCustomerNo]."<br>";
					$routeArr=explode("/",$csvMorningArr[3]);
					$routeArrSize=sizeof($routeArr);
					for($i=0;$i<$routeArrSize;$i++)
					{
						$routeArrMorning[$routeArr[$i]]=$routeArr[$i];
						$linetowrite = "\n<".$customerArr[$trimCustomerNo]." routeNo='".$routeArr[$i]."' type='3'/>";
						//echo "<textarea>".$linetowrite."</textarea>";
						fwrite($fh, $linetowrite);
					}
				}		
			}
			fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
			fwrite($fh, "\n</t1>");  
			fclose($fh);
			$_SESSION['uniqueCustomerRouteMorning'] = $pathtowrite;
			$_SESSION['uniqueRouteArrMorning'] = $routeArrMorning;
		}
                if(file_exists($transporterName))
		{			
                    $transporterRouteArr=array();
                    $file = fopen($transporterName,"r");
                    while(! feof($file))
                    {
                            $csvTransporterArr=fgetcsv($file);
                            $transporterRouteArr[$csvTransporterArr[0]]=$csvTransporterArr[1];

                    }
                    //print_r($transporterRouteArr);
                    $_SESSION['uniqueRouteTransporters'] = json_encode($transporterRouteArr);
		}
		
		/*$pathtowrite="";
		$fh	= "";
		$pathtowrite="src/php/client_map_feature_data/".$account_id."_plantRouteEvening_".$time_microsecond[1].".xml";
		$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
		fwrite($fh, "<t1>"); 
		$file = fopen($eveningFileName,"r");
		while(! feof($file))
		{
			$csvEveningArr=fgetcsv($file);
			
			if($plantArr[$csvEveningArr[1]]!="")
			{
				//echo "plantArr=".$plantArr[71197]."<br>";
				$routeArr=explode("/",$csvEveningArr[3]);
				$routeArrSize=sizeof($routeArr);
				for($i=0;$i<$routeArrSize;$i++)
				{
					$linetowrite = "\n<".$plantArr[$csvEveningArr[1]]." routeNo='".$routeArr[$i]."' type='4'/>";
					//echo "<textarea>".$linetowrite."</textarea>";
					fwrite($fh, $linetowrite);
				}
			}		
		}
		fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
		fwrite($fh, "\n</t1>");  
		fclose($fh);
		
		$_SESSION['uniquePlantRouteEvening'] = $pathtowrite;
		
		
		
		$pathtowrite="";
		$fh	= "";
		$pathtowrite="src/php/client_map_feature_data/".$account_id."_plantRouteMorning_".$time_microsecond[1].".xml";
		$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
		fwrite($fh, "<t1>");		
		$file = fopen($morningFileName,"r");
		while(! feof($file))
		{
			$csvMorningArr=fgetcsv($file);	
			//echo "plantNo=".$plantArr[$csvMorningArr[0]]."<br>";
			if($plantArr[$csvMorningArr[1]]!="")
			{
				//echo "plantNo=".$plantArr[$csvMorningArr[1]]."<br>";
				$routeArr=explode("/",$csvMorningArr[3]);
				$routeArrSize=sizeof($routeArr);
				for($i=0;$i<$routeArrSize;$i++)
				{
					$linetowrite = "\n<".$plantArr[$csvMorningArr[1]]." routeNo='".$routeArr[$i]."' type='5'/>";
					//echo "<textarea>".$linetowrite."</textarea>";
					fwrite($fh, $linetowrite);
				}
			}		
		}
		fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
		fwrite($fh, "\n</t1>");  
		fclose($fh);
		$_SESSION['uniquePlantRouteMorning'] = $pathtowrite;*/
	}

?>
