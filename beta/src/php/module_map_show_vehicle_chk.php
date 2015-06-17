<?php
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
  
    $DEBUG = 0;     
    $acc_id = $_POST['acc_id'];       
    echo "map_show_vehicle##";  
      
    /*$query = "SELECT account_id FROM account WHERE user='$user'";
    $result = mysql_query($query,$DbConnection);
    $row = mysql_fetch_object($result);
    $user_account_id = $row->account_id;*/
          
    $query = "SELECT vehicle_name,vehicle_assignment.device_imei_no FROM vehicle,vehicle_assignment WHERE vehicle.vehicle_id=vehicle_assignment.vehicle_id(+) and vehicle.vehicle_id IN (SELECT vehicle_id FROM vehicle_grouping WHERE ".
    "group_account_id='$acc_id' AND vehicle_group_id = (SELECT vehicle_group_id FROM account_detail WHERE account_id='$acc_id')) ".
    " AND vehicle.status='1'";  
      
    //echo " query=".$query." <br>";    
    //$query = "SELECT vehicle.VehicleID , vehicle.VehicleName , vehicle.VehicleSerial FROM vehicle WHERE create_id='$account_id' AND status='1'";      
   
    if($DEBUG) print_query($query);     	
    //date_default_timezone_set('Asia/Calcutta');
  	$current_time = date('Y/m/d H:i:s');
  	$today_date=explode(" ",$current_time);
  	$today_date1=$today_date[0];   
  	$today_date2 = str_replace("/","-",$today_date1);
  
  	if($DEBUG)
  	print $query;
  	$result_vehicle = mysql_query($query,$DbConnection); ////////using device_serial for report
  	$num12 = mysql_num_rows($result_vehicle);
  	$vehicle_size=0;	
  		
  	while($row1 = @mysql_fetch_object($result_vehicle))
  	{
  		$vehiclename[$vehicle_size]=$row1->VehicleName; 
      $vserial[$vehicle_size]=$row1->VehicleSerial;  	 
  		//$vehicleid[$vehicle_size]=$row1->VehicleID;  		
      //print_message("vid=",$vehicleid[$vehicle_size]);
  		$vehicle_size++;
  	}		
  
		 echo'<table align="center"><TR><TD align="center" colspan="8">
     <table border=1 rules=all bordercolor="#e5ecf5" cellspacing=0 cellpadding=0 align="center">		
			<!--<tr>
				<td class="text" align="center">
					<input type="checkbox" name="all" value="1" onClick="javascript:SelectAll(this.form);">&nbsp;Select All				         
        </td>			
			</tr> 
		</table>
    </TD></TR>-->';
		
   
    if($vehicle_size)
    {
      	$v=1;
        for($i=0;$i<$vehicle_size;$i++)
        {
        //echo " v_serial=".$vserial[$i]." v_name=".$vehiclename[$i];
          $xml_file = "../xml_vts/xml_data/".$today_date2."/".$vserial[$i].".xml"; 
          //echo " xml_file=". $xml_file;             
          echo'
         <TR align="center">	
  				<TD align="left">  				
  					<INPUT TYPE="checkbox" name="imeino[]" VALUE="'.$vserial[$i].'">
  				</TD>
  				
  				<TD align="left" class="report_text">';			
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
  					<font color="#0000FF" face="Verdana" size="2" align="left">'.$vehiclename[$i].'</FONT>
  					';
  					}				
  			   echo'</TD>
           </TR>';
  					
						/*$v++;
						if($v==5)
						{	
							echo'</TR>';
							$v=1;			
						} */
        }
        //echo'</TR>';        
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
					</tr>
        </table>";
    }   
?>
