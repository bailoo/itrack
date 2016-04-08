<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');   
	$account_id_local1 = $_POST['account_id_local'];
	$query = "SELECT account_id from account WHERE superuser='$superuser' AND user='$user' and grp='admin'";
	$result = @mysql_query($query, $DbConnection);
	$row = mysql_fetch_object($result);     	
	$user_account_id = $row->account_id;	

	$DEBUG=0;
	//$imei_no_1=$_POST['imei_no'];
	$type = $_POST['type'];

	$type=explode('#',$type);
	
	echo "acccount_id_local=".$user_account_id."<br>";
	

	if($DEBUG==1)
	{
	echo " type1=".$type[0]." type2=".$type[1];
	}
	 
	//$vname_1=$_POST['vname'];
	$create_id_1 =$_POST['create_id'];
  
  if($create_id_1!="")
  {   	
      //$query="SELECT DISTINCT VehicleID,VehicleName,VehicleSerial,tag,VehicleType FROM vehicle WHERE create_id='$create_id_1' AND status='1' AND VehicleSerrial IS NOT NUll";
      
      $query="SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name, vehicle_tag, vehicle_type, vehicle_assignment.device_imei_no FROM ".
      "vehicle, vehicle_assignment WHERE vehicle.create_id='$create_id_1' AND vehicle.status='1' AND ".
      "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.status='1'";      
      
      $result=mysql_query($query, $DbConnection);
      $vehicle_size=0;
      
      while($row1=mysql_fetch_object($result))
      {
        $vehicle_id[$vehicle_size]=$row1->vehicle_id;
        $vehiclename[$vehicle_size]=$row1->vehicle_name;
        $vehicle_serial[$vehicle_size]=$row1->device_imei_no;
        $vehicle_type[$vehicle_size]=$row1->vehicle_type;
        $tag[$vehicle_size]=$row1->vehicle_tag;  
        $vehicle_size++;    
      }

echo "<table border='0' class='module_left_menu'>
        <tr>
          <td colspan=''>
              Select Vehicles
          </td>
        </tr> 
        <tr>
          <td>
            Group By
  				</td>  				
  				<td>
  				  None
  				</td>
  				<td>
  				  Type
  				</td>
  				<td>
  				  Tag
  				</td> 				
        </tr>  
  	    <tr>
          <td colspan=''>
              <div style='height:200px' id='show_vehicle' style='display:none'></div>
          </td>
        </tr>
      </table>";
      
    echo '<table border="0" width=100% align="center">';     
  		echo '<TR>';
        if($vehicle_size)
        {
        	$v=1;
          for($i=0;$i<$vehicle_size;$i++)
          {
            $xml_file = "xml_vts/xml_data/".$today_date2."/".$vehicle_serial[$i].".xml";    
            echo'	
      				<TD><INPUT TYPE="checkbox"  name="device_serial[]" VALUE="'.$vehicle_serial[$i].'"></TD>
      				<TD>';			
      					if(file_exists($xml_file))
      					{								
      					 echo'<font color="#FF0000" face="Verdana" size="2">'.$vehiclename[$i].'</FONT>';
      					}
      					else
      					{							
      					 echo'<font color="#000000" face="Verdana" size="2">'.$vehiclename[$i].'</FONT>';
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
   echo '</table>';   
  }
  
  $add_route_name_1 =$_POST['add_route_name']; 
  $edit_route_name_1 = $_POST['edit_route_name']; 
  
  $add_geo_name_1 =$_POST['add_geo_name']; 
  $edit_geo_name_1 = $_POST['edit_geo_name'];
  
  $add_landmark_name_1 =$_POST['add_landmark_name']; 
  $edit_landmark_name_1 = $_POST['edit_landmark_name'];
 
  //$geo_name_1=$_POST['geo_name']; 
  //$req_type = $_POST['req_type'];
  
  if($type[1] == "geo")
  {
    $geo_name_1=$_POST['ls'];    /////////l=left  , s=search  in assignement mode
    $vname_to_geo_id=$_POST['rs'];    /////////l=right  , s=search  in assignement mode
  }
  else if($type[1] == "route")
  {
    $route_name_1=$_POST['ls'];    /////////l=left  , s=search  in assignement mode
    $vname_to_route_id=$_POST['rs'];    /////////l=right  , s=search  in assignement mode
  }
  else if($type[1] == "add_device")
  {
    $imei_add_device = $_POST['imei_no'];
  }
  else if($type[1] == "device_sale")
  {
    $imei_device_sale = $_POST['imei_no'];
    $super_user_1=$_POST['super_user'];
    $user_1=$_POST['user'];
    //$qos_1=$_POST['qos'];    
  }  
  else if($type[1] == "device_sale_all")
  {
    $msg_all = "";
    $btn_flag =1;
    
    $all_value = $_POST['all_value'];    
    $all_value1 = explode(':',$all_value);
    
    $imei_device_sale = $all_value1[0];
    $super_user_1 = $all_value1[1];
    $user_1 = $all_value1[2];
    //$qos_1=$_POST['qos'];    
  }     
  
  else if($type[1] == "dv_assign")
  {
    $imei_dv_assign = $_POST['ls'];    /////////l=left  , s=search  in assignement mode
    $vname_dv_assign = $_POST['rs'];    /////////r=right  , s=search  in assignement mode 
  } 
  else if($type[1] == "v_group")
  {
    $vname_group = $_POST['ls'];    /////////l=left  , s=search  in assignement mode
    $account_name_group = $_POST['rs'];    /////////r=right  , s=search  in assignement mode 
  }  

  //$route_name_1=$_POST['route_name'];
      
  if($imei_add_device != null)
  {
      if($type[0] == "new")
    	{
    		$query = "SELECT device_imei_no FROM device_manufacturing_info WHERE device_imei_no='$imei_add_device'";
    		 //echo "Q=".$query;    		  
      	$result = @mysql_query($query, $DbConnection);
      	
        if(@mysql_num_rows($result)) 
      	{
      	  $message="failure##<font color='red'>!!Not Available</font>";
      	}
      	else
      	{
      	  $message="success##<font color='green'>!! Available</font>";
        }
      	echo $message;  			
      }    
  }
  
  else if($imei_dv_assign != null)
  {
      if($type[0] == "existing_in_user")
      {     	
        $query = "SELECT device_imei_no FROM device_lookup WHERE ".
        "device_imei_no IN(SELECT device_imei_no FROM device_sales_info ".
        "WHERE user_account_id='$user_account_id') AND ".
        "device_imei_no NOT IN(SELECT device_imei_no FROM vehicle_assignment WHERE status='1') and ".
        "device_imei_no='$imei_dv_assign'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!!Available</font>";
      	}
      	else
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";
        }
      	echo $message;  		
      }  
  } 
    
  if($imei_device_sale!=null)
  {
    if($type[0] == "existing")
  	{
  		$query = "SELECT device_imei_no FROM device_manufacturing_info WHERE device_imei_no='$imei_device_sale'";
  		
    	$result = @mysql_query($query, $DbConnection);
    	
      if(@mysql_num_rows($result)) 
    	{
    	  $message="success##<font color='green'>!!Correct</font>";
    	}
    	else
    	{
    	 $message="failure##<font color='red'>!! Incorrect IMEI No</font>";
    	 $btn_flag = 0;
      }
    	if($type[1] != "device_sale_all")
    	{
        echo $message;
      }   			
    }    
  }
  
  if( ($super_user_1 !=null	|| $user_1!=null))
  { 
    if($super_user_1 !=null)
  	{
  		$query = "SELECT superuser FROM account WHERE superuser='$super_user_1'";
  	}	
    	 
    if($user_1 !=null)
  	{
  		$query = "SELECT user FROM account WHERE user='$user_1'";
  	}    
    //echo $query;
    $result = @mysql_query($query, $DbConnection);
    	
    if(@mysql_num_rows($result)) 
  	{
  	  $message="success##<font color='green'>!!Correct</font>";
  	}
  	else
  	{
  	 $message="failure##<font color='red'>!! Incorrect account</font>";
  	 $btn_flag = 0;
    }
  	
   	if($type[1] != "device_sale_all")
  	{
      echo $message;
    } 			  	 			
  } 
  
  if( (!$btn_flag) && ($type[1] == "device_sale_all") )
  {
    $message="failure##<font color='red'>!! Incorrect Fields</font>";
    echo $message;
  }
  
  /*else if($qos_1 !=null)
	{
		$query = "SELECT MaxVehicle FROM qos WHERE QOS='$qos_1'";
		
    //echo $query;
    $result = @mysql_query($query, $DbConnection);
    	
    if(@mysql_num_rows($result)) 
  	{
  	  $row = @mysql_fetch_object($result);
  	  $max_vehicle = $row->MaxVehicle;   	  
      $message = "success##<font color='green'>!!Correct [ Max-Vehicle - ".$max_vehicle."] </font>";
  	}
  	else
  	{
  	  $message=  "failure##<font color='red'>!! Incorrect</font>";
    }
  	echo $message;  					
	}*/	
  
  else if($vname_dv_assign !=null)
  {
      if($type[0] == "existing_not_assigned")
    	{    	
        $query = "SELECT vehicle_id,vehicle_name FROM vehicle WHERE ".
        "vehicle_id IN(SELECT vehicle_id FROM vehicle_grouping WHERE ".
        "vehicle_group_id =(SELECT vehicle_group_id FROM ".
        "account_detail WHERE account_id='$account_id') AND ".
        "vehicle_id IN(SELECT vehicle_id from vehicle_assignment WHERE status='1')) AND ".
        "VehicleName='$vname_dv_assign'";
  
      	if($DEBUG==1)
          print_query($query);
 
      	$result = @mysql_query($query, $DbConnection);
      	
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!! Available</font>";          
      	}
      	else
      	{
      	  $message="failure##<font color='red'>!!Not Available</font>";
        }
      	echo $message;  			
      }  
  }  
 
  else if($vname_group !=null)
  {
      if($type[0] == "existing_in_user")
    	{    	
        $query = "SELECT vehicle_id,vehicle_name FROM vehicle WHERE ".
        "vehicle_id IN(SELECT vehicle_id FROM vehicle_grouping WHERE ".
        "vehicle_group_id =(SELECT vehicle_group_id FROM ".
        "account_detail WHERE account_id='$account_id')) and status='1' and vehicle_name='$vname_group'";
        
        if($DEBUG) print_query($query);  
 
      	$result = @mysql_query($query, $DbConnection);
      	
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!! Available</font>";          
      	}
      	else
      	{
      	  $message="failure##<font color='red'>!!Not Available</font>";
        }
      	echo $message;  			
      }  
  }     
    
  else if($account_name_group !=null)
  {
      if($type[0] == "existing_in_user")
    	{    	
        $query = "SELECT account_id,user,grp FROM account WHERE ".
        "account_id IN (SELECT account_id FROM account_detail WHERE account_admin_id = '$admin_id' ORDER BY account_id)";
             
        //if($DEBUG) print_query($query);  
        
        $result = @mysql_query($query, $DbConnection);
            	
        $flag =0;
        while($row = mysql_fetch_object($result))
        {
          $accid2 = $row->account_id;    
          $user2 = $row->user;
          $grp2 = $row->grp;
                                                      
          if($user2!="" && $grp2 == "admin")
            $acc_g = $user2;
          else
            $acc_g = $grp2;
          
          $account_name_g = $acc_g;   // FOR DISPLAY   
          
          if($account_name_group == $account_name_g)
            $flag = 1;        
        }
        
        if($DEBUG) echo "flag=".$flag;
        
        if($flag)
        {
      	  $message="success##<font color='green'>!! Available</font>";          
      	}
      	else
      	{
      	  $message="failure##<font color='red'>!!Not Available</font>";
        }
      	echo $message;  			
      }  
  }       
   
  else if($geo_name_1 !=null)
  {
      if($type[0]="existing_in_user")
      {      	
        $query = "SELECT geo_id,geo_name FROM geofence WHERE user_account_id='$user_account_id' AND geo_name='$geo_name_1'";  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!!Available</font>";
      	}
      	else
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";
        }
      	echo $message;  		
      }  
  }
  
  else if($vname_to_geo_id !=null)
  {
      if($type[0] == "existing_not_assigned")
    	{
      	
        $query = "SELECT vehicle_id,vehicle_name FROM vehicle WHERE ".
        "vehicle_id IN(SELECT vehicle_id FROM vehicle_grouping WHERE ".
        "vehicle_group_id =(SELECT vehicle_group_id FROM ".
        "account_detail WHERE account_id='$account_id') AND ".
        "vehicle_id IN(SELECT vehicle_id from geo_assignment WHERE status='1')) AND ".
        "vehicle_name='$vname_to_geo_id'";
  
      	if($DEBUG==1)
          print_query($query);
 
      	$result = @mysql_query($query, $DbConnection);
      	
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!! Available</font>";          
      	}
      	else
      	{
      	  $message="failure##<font color='red'>!!Not Available</font>";
        }
      	echo $message;  			
      }  
  }
  else if($route_name_1 !=null)
  {
      if($type[0]=="existing_in_user")
      {       	
        $query = "SELECT route_id,route_name FROM route WHERE user_account_id='$user_account_id' AND  route_name='$route_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!!Available</font>";
      	}
      	else
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";
        }
      	echo $message;  		
      }  
  }
  
  else if($vname_to_route_id !=null)
  {
      if($type[0] == "existing_not_assigned")
    	{
      	
        $query = "SELECT vehicle_id,vehicle_name FROM vehicle WHERE ".
        "vehicle_id IN(SELECT vehicle_id FROM vehicle_grouping WHERE ".
        "vehicle_group_id =(SELECT vehicle_group_id FROM ".
        "account_detail WHERE account_id='$account_id') AND ".
        "vehicle_id IN(SELECT vehicle_id from route_assignment WHERE status='1')) AND vehicle_name='$vname_to_route_id'";
  
      	if($DEBUG==1)
          print_query($query);
 
      	$result = @mysql_query($query, $DbConnection);
      	
        if(@mysql_num_rows($result)) 
      	{
      	  $message="success##<font color='green'>!! Available</font>";          
      	}
      	else
      	{
      	  $message="failure##<font color='red'>!!Not Available</font>";
        }
      	echo $message;  			
      }  
  } 
  
  else if($add_route_name_1 !=null)
  {
      if($type[0]=="existing_not_assigned")
      {       	
        $query = "SELECT route_id,route_name FROM route WHERE user_account_id='$user_account_id' AND  route_name='$add_route_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";      	  
      	}
      	else
      	{ 
          $message="success##<font color='green'>!!Available</font>";      	 
        }
      	echo $message;  		
      }  
  }  
 
  else if($edit_route_name_1 !=null)
  {
      if($type[0]=="existing_in_user")
      {       	
        $query = "SELECT route_id,route_name FROM route WHERE user_account_id='$user_account_id' AND  route_name='$edit_route_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="failure##<font color='red'>!! Not Available</font>";
      	  
      	}
      	else
      	{
      	  $message="success##<font color='green'>!!Available</font>";
        }
      	echo $message;  		
      }  
  }
  
  else if($add_geo_name_1 !=null)
  {
      if($type[0]=="existing_in_user")
      {       	
        $query = "SELECT geo_id,geo_name FROM geofence WHERE user_account_id='$user_account_id' AND  geo_name='$add_geo_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";      	  
      	}
      	else
      	{ 
          $message="success##<font color='green'>!!Available</font>";      	 
        }
      	echo $message;  		
      }  
  }
  else if($edit_geo_name_1 !=null)
  {
      if($type[0]=="existing_in_user")
      {       	
        $query = "SELECT geo_id,geo_name FROM geofence WHERE user_account_id='$user_account_id' AND  geo_name='$edit_geo_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="failure##<font color='red'>!! Not Available</font>";
      	  
      	}
      	else
      	{
      	  $message="success##<font color='green'>!!Available</font>";
        }
      	echo $message;  		
      }  
  }
  
  else if($add_landmark_name_1 !=null)
  {
      if($type[0]=="existing_not_assigned")
      {       	
        $query = "SELECT landmark_id,landmark_name FROM landmark WHERE user_account_id='$user_account_id' AND  landmark_name='$add_landmark_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	 $message="failure##<font color='red'>!! Not Available</font>";      	  
      	}
      	else
      	{ 
          $message="success##<font color='green'>!!Available</font>";      	 
        }
      	echo $message;  		
      }  
  }
  else if($edit_landmark_name_1 !=null)
  {
      if($type[0]=="existing_in_user")
      {       	
        $query = "SELECT landmark_id,landmark_name FROM landmark WHERE user_account_id='$user_account_id' AND  landmark_name='$edit_landmark_name_1'";
  
      	if($DEBUG==1)
          print_query($query);
          
        $result = @mysql_query($query, $DbConnection);
     	     	                       
        if(@mysql_num_rows($result)) 
      	{
      	  $message="failure##<font color='red'>!! Not Available</font>";
      	  
      	}
      	else
      	{
      	  $message="success##<font color='green'>!!Available</font>";
        }
      	echo $message;  		
      }  
  }
 	
?>