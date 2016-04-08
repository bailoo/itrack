<?php	
	//echo "cwd:".getcwd();
	
  include_once('lib/BUG.php');
	include_once('lib/Util.php');
	include_once('lib/VTSFuel.php');
	include_once("report_title.php");
  include_once('lib/VTSMySQL.php'); 
  include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	 
	$imeis[] = "734";
	$startDateTime = "2011-12-10 00:00:00";
	$endDateTime = "2011-12-10 13:44:58";
	$timeInterval = "30";
	
	$startDate = date('Y-m-d', strtotime($startDateTime));
	$endDate = date('Y-m-d', strtotime($endDateTime));
	$endDate1 = date('Y-m-d', strtotime($endDate)+(1*24*60*60));
	$timeIntervalTS = $timeInterval*60;
	
	
	if($_SERVER["REMOTE_ADDR"]== "202.3.77.11")
	{
	 echo "<br>imeis:".$imeis." startDateTime:".$startDateTime." ,startDate:".$startDate." ,endDateTime:".$endDateTime." ,enddate:".$endDate." enddate1:".$endDate1." ,timeIntervalTS:".$timeIntervalTS;
  }
?>

<?php
  $time_list = UTIL::getAllTimes($startDateTime, $endDateTime, $timeIntervalTS);
	$datetime_now = date("Y:m:d H:i:s", time()); 	
	$vsize =0; 	
  foreach($imeis as $imei)
	{ 	      
	  echo '<br>dbcon:'.$DbConnection;
	  
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
						echo '<br>fuel[imei][datetime]:'.$fuel[$imei][$datetime];
					}
				}
			}
		}		
	 $vsize++;	
	}
?>

<?php
  echo'<center><br>';
       
        $imei_size =0;  
     
        foreach($imeis as $imei)
        {
          //echo '<br>count:'.$imei;
          
          $imei_size =1;    $fuel_size =0;
          foreach($fuel[$imei] as $datetime => $fuel_imei)
          {
            $fuel_size=1;
          }
          if($fuel_size)
          {
            $vname = VTSMySQL::getVehicleNameOfVehicle($DbConnection, $imei);
            $imei1 = VTSMySQL::getIMEIOfVehicle($DbConnection, $imei);
            
            echo "<br>vname:".$vname." # imei1:".$imei1;
                       
            echo '<center>';           
                          
                    foreach($fuel[$imei] as $datetime => $fuel_imei)
                    {                     
                      echo '<br>DT:'.$datetime.' # Fuel:'.$fuel_imei;
                      /*if($fuel_imei<1000)
                      {
                        echo '<br>DT:'.$datetime.' # Fuel:'.$fuel_imei;
                        
                      }*/             		
                    }
              echo "</table>";
    		      $cnt=0;    		
                //echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
                //echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime]\">";                									
                //echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Fuel level(%)]\">";
              $x++;
      } // if fuel_size closed
    }	
        
  echo'</center>'; 
   
?>
