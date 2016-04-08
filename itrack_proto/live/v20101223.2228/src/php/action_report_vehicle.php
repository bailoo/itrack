<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

  $vehicle_str = $_POST['vid'];
  $option_str = $_POST['option'];  
  
  $vehicleid = explode(':',$vehicle_str);
  $option1 = explode(':',$option_str);
  
  $vsize = sizeof($vehicleid);
  $size_option = sizeof($option1);
  
  for($i=0;$i<$size_option;$i++)
  {
    if($option1[$i] == "1")
        $name = "1"; 
    
    if($option1[$i] == "2")
        $number = "1";
    
    if($option1[$i] == "3")
        $max_speed = "1";
    
    if($option1[$i] == "4")
       $type = "1";
    
    if($option1[$i] == "5")
       $tag = "1"; 
    
    if($option1[$i] == "6")
       $geo_name = "1"; 
    
    if($option1[$i] == "7") 
       $route_name = "1";   
  }  
			        		
  if($vsize)
  {
  	echo'<form method = "post" action="src/php/report_getpdf_type3.php?size='.$vsize.'" target="_blank">';
  	
  	for($j=0;$j<$vsize;$j++)
  	{
  		$query="SELECT vehicle.VehicleName, vehicle.vehicle_number, vehicle.max_speed, ".
      "vehicle.VehicleType, vehicle.tag, geofence_info.geo_name, route_info.route_name ".
      "FROM vehicle,device_info, geofence_info, route_info, device_lookup  WHERE ".
      "device_info.VehicleID = vehicle.VehicleID AND ".
      "vehicle.geo_id = geofence_info.geo_id AND ".
      "vehicle.route_id = route_info.route_id AND ".
      "device_info.device_imei_no= device_lookup.device_imei_no AND ".
      "vehicle.VehicleID='$vehicleid[$j]' ORDER BY vehicle.name ASC";
      
  		//print_query($query);
      $result = mysql_query($query,$DbConnection);
  		$row = mysql_fetch_object($result);
  		$sno[$j]=$j+1;
  		$vname[$j]=$row->VehicleName;
  		$vnumber[$j]=$row->vehicle_number;
  		$vmax_speed[$j]=$row->max_speed;
  		$vtype[$j]=$row->VehicleType;
  		$vtag[$j]=$row->tag;
  		$vgeo_name[$j]=$row->geo_name;	  
  		$vroute_name[$j]=$row->route_name;  
      
      //print_message("VID1", $name.",".$number.",".$max_speed.",".$type.",".$tag.$geo_name.",".$route_name);
      //print_message("VID2",$vname[$j].",".$vnumber[$j].",".$vmax_speed[$j].",".$vtype[$j].",".$vtag[$j].",".$vgeo_name[$j].",".$vroute_name[$j]);		
  	}
  }
  
  echo'
  <table border=0 width = 100% cellspacing=2 cellpadding=0>
  <tr>
  	<td height=10 class="report_heading" align="center">Vehicle Report</td>
  </tr>
  </table><br>';
  	  	
   //DISPLAY VEHICLE REPORT   
  $result_count = sizeof($vname);
  //print_message("RES=",$result_count);
  
  if($result_count)
  {    
    echo'  
    <table border=1 rules=all bordercolor="lightblue" width="95%" align="center" cellspacing=0 cellpadding=3>
      <tr>
      <td class="text" align="left"><b>SNo</b></td>      
    ';
			if($name)
				echo'<td class="text" align="left"><b>Vehicle Name</b></td>';
			
      if($number)
					echo'<td class="text" align="left"><b>Vehicle Number</b></td>';
			
      if($max_speed)				
				echo'<td class="text" align="left"><b>Max Speed</b></td>';
				
			if($type)
				echo'<td class="text" align="left"><b>Type</b></td>';
				
			if($tag)					
					echo'<td class="text" align="left"><b>Tag</b></td>';
          	
			if($vgeo_name)
				echo'<td class="text" align="left"><b>Geofence Name</b></td>';		
				
			if($vroute_name)
				echo'<td class="text" align="left"><b>Route Name</b></td>';		
				
			echo'</tr>';
			for($j=0;$j<$vsize;$j++)
			{
				echo'<tr>';
				echo'<td class="text" align="left">'.$sno[$j].'</td>';
				
				if($name)
				  echo'<td class="text" align="left">&nbsp;&nbsp;'.$vname[$j].'</td>';
				  
				if($number)
					echo'<td class="text" align="left">'.$vnumber[$j].'</td>';
					
				if($max_speed)
					echo'<td class="text" align="left">'.$vmax_speed[$j].'</td>';
					
				if($type)
					echo'<td class="text" align="left">'.$vtype[$j].'</td>';
					
				if($tag)
					echo'<td class="text" align="left">'.$vtag[$j].'</td>';
								
				if($geo_name)
					echo'<td class="text" align="left">'.$vgeo_name[$j].'</td>';
					
				if($route_name)
					echo'<td class="text" align="left">'.$vroute_name[$j].'</td>';
				
				echo'</tr>';
			}
      
      echo'
				</table><br>			
       ';
													
				echo'<form method="post" action="report_getpdf_type3.php?size='.$vsize.'" target="_blank">';
        $title='Vehicle Details';
				echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
				if($no_opt)
				{
					for($i=0;$i<$vsize;$i++)
					{
						echo"<input TYPE=\"hidden\" VALUE=\"$sno[$i]\" NAME=\"temp[$i][SNo]\">";
						if($option1==1)
							echo"<input TYPE=\"hidden\" VALUE=\"$vname[$i]\" NAME=\"temp[$i][Vehicle Number]\">";						
						if($option2==1)
							echo"<input TYPE=\"hidden\" VALUE=\"$vnumber[$i]\" NAME=\"temp[$i][Vehicle Number]\">";
						if($option3==1)
							echo"<input TYPE=\"hidden\" VALUE=\"$vmax_speed[$i]\" NAME=\"temp[$i][Max Speed]\">";
						if($option4==1)
							echo"<input TYPE=\"hidden\" VALUE=\"$vtype[$i]\" NAME=\"temp[$i][Type]\">";										
						if($option5==1)
							echo"<input TYPE=\"hidden\" VALUE=\"$vtag[$i]\" NAME=\"temp[$i][Tag]\">";
						if($option6==1)
							echo"<input TYPE=\"hidden\" VALUE=\"$vgeo_name[$i]\" NAME=\"temp[$i][Tag]\">";
						if($option7==1)
							echo"<input TYPE=\"hidden\" VALUE=\"$vroute_name[$i]\" NAME=\"temp[$i][Tag]\">";                        							
					}								
				}							
       echo'
				<table align="center">
					<tr>
						<td width=25%></td>
						<td><input type="submit" value="Get Report" class="noprint">&nbsp;<input type="submit" value="Email" onclick="mail_report()" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;								
				</td>
					</tr>
				</table>
				</form>
       ';      
      }
			else
			{
				print"<center><FONT color=\"Red\"><strong>No Result Found</strong></font></center>";						
			}  					
?>
					