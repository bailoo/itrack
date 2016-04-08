<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo"<br>			
			<form name='manage1' method='post'>
				<center> 				
				<br>                                    
  ";      
   echo'
   	   <fieldset class=\'manage_fieldset\'>
				<legend><strong>Bus Arrival</strong></legend>
  				<table border=0 cellspacing=0 cellpadding=0 class=\'module_left_menu\' align="center">   				 
           ';
  				// for buses
          
          echo'
          <tr>
            <td><b>Bus &nbsp;&nbsp;</b></td>
            <td><b> : &nbsp;&nbsp;</b></td>
    			   <td>
              <select name="bus_id" id="bus_id" Onchange="javascript:return get_shift(\'get_shift\',\'pick\')">
              <option value="select">Select</option>';
              /*$query="SELECT shift_id,shift_name FROM shift WHERE user_account_id='$common_id1' AND status='1'";
    				
            				$result=mysql_query($query,$DbConnection);
    								$row_result=mysql_num_rows($result);								
    								if($row_result!=null)
          					{
          						while($row=mysql_fetch_object($result))
          						{							
    									$shift_id =$row->shift_id;
    									$shift_name =$row->shift_name;
      								echo'<option value="'.$shift_id.'">'.$shift_name.'</option>';
                      }
    								}
    						*/
                    get_user_vehicle($root,$common_id1);		
    								echo'
								    </select>
								 </td>
                 </tr>
                 '; 
          echo'
          <tr>
            <td><b>Shift &nbsp;&nbsp;</b></td>
            <td><b> : &nbsp;&nbsp;</b></td>
    			   <td>
              <select name="shift_id" id="shift_id" Onchange="javascript:return get_busstop_for_arrival(\'arrival\',this.form)">
              <option value="select">Select</option>';
            /*$query="SELECT shift_id,shift_name FROM shift WHERE user_account_id='$common_id1' AND status='1'";
    				
            				$result=mysql_query($query,$DbConnection);
    								$row_result=mysql_num_rows($result);								
    								if($row_result!=null)
          					{
          						while($row=mysql_fetch_object($result))
          						{							
    									$shift_id =$row->shift_id;
    									$shift_name =$row->shift_name;
      								echo'<option value="'.$shift_id.'">'.$shift_name.'</option>';
                      }
    								}
    				*/				
    								echo'
								    </select>
								 </td>
                 </tr>
                 ';
                 
   
         echo'   
  			   </table>
			 </fieldset>   
			';
     // echo $query;
      echo'  	
		 <fieldset class=\'manage_fieldset\'>
				<legend><strong>Bus Stops</strong></legend>
				<table border=0 cellspacing=0 cellpadding=0 class=\'module_left_menu\' id="EntryTable" align="center">
			   </table>
			</fieldset>
      <input type="hidden" name="rowcnt" id="rowcnt" value="0">
      <input type="hidden" name="busstops" id="busstops">  
     ';
      
		 echo'	 
				<br>
					<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_busstop(\'setarrival\')" value="Set Arrival">&nbsp;<input type="reset" value="Cancel">
				<br><a href="javascript:show_option(\'manage\',\'busstop\');" class="back_css">&nbsp;<b>Back</b></a>
				</center>
			</form>';	



 			function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
			{	
				//date_default_timezone_set('Asia/Calcutta');
        $current_date = date('Y-m-d');
      
        $xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
        // echo "xml_file=". $xml_file;	
      	//echo $vehicleSerial[$i];
      	if(file_exists($xml_file))
      	{								
          echo'<option VALUE="'.$vehicle_id.'">'.$vehicle_name.' * </option>';
      	}
      	else
      	{							
          echo'<option VALUE="'.$vehicle_id.'">'.$vehicle_name.'</option>';
      	}

			}


			function get_user_vehicle($AccountNode,$account_id)
			{
				global $vehicleid;
				global $vehicle_cnt;
				global $td_cnt;
				global $DbConnection;
				if($AccountNode->data->AccountID==$account_id)
				{
					$td_cnt =0;
					for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
					{			    
						$vehicle_id = $AccountNode->data->VehicleID[$j];
						$vehicle_name = $AccountNode->data->VehicleName[$j];
						$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
						$vehicle_tag = $AccountNode->data->VehicleTag[$j];
						if($vehicle_id!=null)
						{
							for($i=0;$i<$vehicle_cnt;$i++)
							{
								if($vehicleid[$i]==$vehicle_id)
								{
									break;
								}
							}			
							if($i>=$vehicle_cnt)
							{
								$vehicleid[$vehicle_cnt]=$vehicle_id;
								$vehicle_cnt++;
								$td_cnt++;
								//$query="SELECT vehicle_id FROM route_assignment WHERE vehicle_id='$vehicle_id' AND status='1'";
								//$result=mysql_query($query,$DbConnection);
								//$num_rows=mysql_num_rows($result);
								//if($num_rows==0)
								//if($vehicle_tag=="bus")
								//{							
									common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
								//}
								if($td_cnt==3)
								{
									$td_cnt=0;
								}
							}
						}
					}
				}
				$ChildCount=$AccountNode->ChildCnt;
				for($i=0;$i<$ChildCount;$i++)
				{ 
					get_user_vehicle($AccountNode->child[$i],$account_id);
				}
			}

			
?>  
