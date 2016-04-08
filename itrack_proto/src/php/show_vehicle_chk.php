<?php
	include_once("SessionVariable.php");
	include_once("PhpMysqlConnectivity.php");

  if($access=="1") ////////for direct user
  {
  	$query_vehicle = "select VehicleID,VehicleName,VehicleSerial from vehicle where  UserID='$login' and Status='ON' order by VehicleName DESC";
  }
 	  	
    //date_default_timezone_set('Asia/Calcutta');
  	$current_time = date('Y/m/d H:i:s');
  	$today_date=explode(" ",$current_time);
  	$today_date1=$today_date[0];
  	//echo "today_date1=".$today_date1;
  	$today_date2 = str_replace("/","-",$today_date1);
  	//echo "<br>today_date1=".$today_date2;
  	//echo "query=".$query_vehicle;
  	$result_vehicle = mysql_query($query_vehicle,$DbConnection); ////////we using device_serial for report
  	$num12 = mysql_num_rows($result_vehicle);
  	$vehicle_size=0;	
  		
  	while($row1 = mysql_fetch_object($result_vehicle))
  	{
  		$vehiclename[$vehicle_size]=$row1->VehicleName;
  		$vehicle_id[$vehicle_size]=$row1->VehicleID;
  		$vehicle_serial[$vehicle_size]=$row1->VehicleSerial;   
  		$vehicle_size++;
  	}		

  		echo'<table border="0" width=100% align="center">';     
  		echo '<TR>';
        if($vehicle_size)
        {
        	$v=1;
          for($i=0;$i<$vehicle_size;$i++)
          {
            $xml_file = "../xml_vts/xml_data/".$today_date2."/".$vehicle_serial[$i].".xml";    
            echo'	
      				<TD>
      					<INPUT TYPE="checkbox"  name="device_serial[]" VALUE="'.$vehicle_serial[$i].'">
      				</TD>
      				
      				<TD>';			
      					if(file_exists($xml_file))
      					{								
      					echo'
      					<font color="#FF0000" face="Verdana" size="2">'.$vehiclename[$i].'</FONT>
      					';
      					}
      					else
      					{							
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
        	echo'</table>
  '; 
?>
