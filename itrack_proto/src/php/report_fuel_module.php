<?php	
	//echo "cwd:".getcwd();	 
	$fuel_data = "";
  $imeis[] = $vid;
	$startDateTime = $arrivale_time;
	$endDateTime = $depature_time;
	$timeInterval = $user_interval;
	
	$startDate = date('Y-m-d', strtotime($startDateTime));
	$endDate = date('Y-m-d', strtotime($endDateTime));
	$endDate1 = date('Y-m-d', strtotime($endDate)+(1*24*60*60));
	$timeIntervalTS = $timeInterval*60;
	
	/*if($_SERVER["REMOTE_ADDR"]== "202.3.77.11")
	{
	 echo "<br>imeis:".$imeis[0]." startDateTime:".$startDateTime." ,startDate:".$startDate." ,endDateTime:".$endDateTime." ,enddate:".$endDate." enddate1:".$endDate1." ,timeIntervalTS:".$timeIntervalTS;
  } */

  $time_list = UTIL::getAllTimes($startDateTime, $endDateTime, $timeIntervalTS);
	$datetime_now = date("Y:m:d H:i:s", time()); 	
	$vsize =0; 	
  foreach($imeis as $imei)
	{ 	      
	  //echo '<br>dbcon:'.$DbConnection;	  
    $fuel_data = VTSFuel::getFuelData($DbConnection, $imei, $startDate, $endDate1);		
		if(sizeof($time_list)>0)
		{
			foreach($time_list as $datetime)
			{
				if(strtotime($datetime) <= strtotime($datetime_now))
				{
					$fuel_datetime =  VTSFuel::interpolateFuelData($fuel_data, $datetime);
					if($fuel_datetime >= 0)
					{
						$fuel[$imei][$datetime] = $fuel_datetime;
						//echo '<br>fuel[imei][datetime]:'.$fuel[$imei][$datetime];
					}
				}
			}
		}		
	 $vsize++;	
	}

       
$imei_size =0;  
$fcount = 0;
$fuel_total =0;

$time_1 = strtotime($startDateTime);
$time_2 = strtotime($endDateTime);

$max_fuel_io1 = VTSFuel::$max_fuel_io;
$max_fuel_value1 = VTSFuel::$max_fuel_value;


foreach($imeis as $imei)
{
  $imei_size =1;    $fuel_size =0;
  foreach($fuel[$imei] as $datetime => $fuel_imei)
  {
    $fuel_size=1;
  }
  if($fuel_size)
  {
    $vname = VTSMySQL::getVehicleNameOfVehicle($DbConnection, $imei);
    $imei1 = VTSMySQL::getIMEIOfVehicle($DbConnection, $imei);
    
    //echo "<br>vname:".$vname." # imei1:".$imei1;               
    foreach($fuel[$imei] as $datetime => $fuel_imei)
    {                     
      //echo '<br>DT:'.$datetime.' # Fuel:'.$fuel_imei;
      if($fuel_imei<1000)
      {
        //echo '<br>DT:'.$datetime.' # Fuel:'.$fuel_imei;
        //$fuel_str = $datetime."(".$fuel_imei.")";
        //$fuel_data = $fuel_data.$fuel_str."<br>";
        
        $time_fuel = strtotime($datetime);
        
        if(($time_fuel>$time_1)&&($time_fuel<$time_2))
        {
          $fuel_data = $fuel_imei;         				  
				  
				  if($fuel_data > $max_fuel_value1) 
				  {
				    $fuel_data = $max_fuel_value1;
          }
          
          $fuel_total+= $fuel_data; 
          $fcount++;           
          /*if( ($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189")
          {
            	
              echo "<br>DateTime window:".$startDateTime." to ".$endDateTime;
              echo ", fuel_data=".$fuel_data." ,(".$datetime.")";
          }*/                              
        }
      }             		
    }  		      
  } // if fuel_size closed
}

if( ($fuel_total>$fcount) && ($fuel_total!=0) && ($fcount!=0) )
{
  $fuel_data = $fuel_total / $fcount;      // Average fuel in Halt Time window
}

$fuel_data = round($fuel_data,0);

/*if( ($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189")
{
  echo "<br><br>POST_fuel_data=".$fuel_data."<br>";
}*/
          
?>
