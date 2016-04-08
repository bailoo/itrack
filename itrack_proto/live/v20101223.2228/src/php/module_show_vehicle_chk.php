<?php

    $query = "SELECT distinct device_lookup.device_imei_no, vehicle.VehicleName, vehicle.VehicleID,vehicle.VehicleSerial FROM device_lookup, vehicle, device_sales_info,device_info WHERE ".   
    "(device_sales_info.status=1) AND ". 
    "(device_info.device_imei_no = device_lookup.device_imei_no) AND ". 
    "(device_info.VehicleID = vehicle.VehicleID) and device_info.device_imei_no IN (SELECT device_imei_no FROM device_sales_info WHERE account_id = '$account_id') ". 
    "ORDER BY vehicle.VehicleName DESC";
 
    //print_query($query);     	
    date_default_timezone_set('Asia/Calcutta');
  	$current_time = date('Y/m/d H:i:s');
  	$today_date=explode(" ",$current_time);
  	$today_date1=$today_date[0];
  	//echo "today_date1=".$today_date1;
  	$today_date2 = str_replace("/","-",$today_date1);
  	//echo "<br>today_date1=".$today_date2;
  	//echo "query=".$query_vehicle;
  	$result_vehicle = mysql_query($query,$DbConnection); ////////using device_serial for report
  	$num12 = mysql_num_rows($result_vehicle);
  	$vehicle_size=0;	
  		
  	while($row1 = mysql_fetch_object($result_vehicle))
  	{
  		$vehiclename[$vehicle_size]=$row1->VehicleName; 
      $vserial[$vehicle_size]=$row1->VehicleSerial;  	 
  		$vehicleid[$vehicle_size]=$row1->VehicleID;  		
      //print_message("vid=",$vehicleid[$vehicle_size]);
  		$vehicle_size++;
  	}		
  
		echo '<TR>';
      if($vehicle_size)
      {
      	$v=1;
        for($i=0;$i<$vehicle_size;$i++)
        {
          $xml_file = "../xml_vts/xml_data/".$today_date2."/".$vserial[$i].".xml";              
          echo'	
  				<TD>  				
  					<INPUT TYPE="checkbox"  name="vid[]" VALUE="'.$vehicleid[$i].'">
  				</TD>
  				
  				<TD class="report_text">';			
  					if(file_exists($xml_file))
  					{		
            //echo "One";						
  					echo'
  					<font color="#FF0000" face="Verdana" size="2">'.$vehiclename[$i].'</FONT>
  					';
  					}
  					else  
  					{		
            //echo "two<br>";	
            //echo "v=".$vehiclename[$vehicle_size];				
  					echo'
  					<font color="#000000" face="Verdana" size="2">'.$vehiclename[$i].'</FONT>
  					';
  					}				
  			   echo'</TD>';
  					
						$v++;
						if($v==5)
						{	
							echo'</TR>';
							$v=1;			
						}
        }        
      }
      else
      {
            echo"<tr>
						<td>";
						print"<center>
								<FONT color=\"black\" size=\"2\">
									<strong>
										No Vehicle Exists
									</strong>
								</font>
							</center>
						</td>
					</tr>";
      }   
?>