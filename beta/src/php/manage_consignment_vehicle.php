<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$query1="SELECT vehicle_color from account_preference WHERE account_id='$account_id'";
	$result1=mysql_query($query1,$DbConnection);
	$row1=mysql_fetch_object($result1);
	$vehicle_color1=$row1->vehicle_color;

	$vcolor = explode(':',$vehicle_color1); //account_name:active:inactive
	$vcolor1 = "#".$vcolor[0];
	$vcolor2 = "#".$vcolor[1];
	$vcolor3 = "#".$vcolor[2];

	$root=$_SESSION['root'];
	$vehicleid=array();
	$vehicle_cnt;	
	$td_cnt=0;
	//echo "account_id_local=".$account_id_local1."<br>";
echo 'show_moto_vehicle##
		<fieldset class="report_fieldset">
			<legend>Select Vehicle</legend>						
				<table border=0  cellspacing=0 cellpadding=0  width="100%">
					<tr>
						<td align="center">							
							<div style="overflow: auto;height: 150px; width: 650px;" align="center">
								<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';									               
									get_all_vehicle($account_id_local1);																			
							echo'</table>
							</div>
						</td>
					</tr>
				</table>
		  </fieldset>
		 <center>
			<input type="button" Onclick="javascript:moto_show_vehicle(1)" value="Submit" id="enter_button_1">
		</center>';

	function get_all_vehicle($local_account_id)
	{
		global $root;
		global $vehicleid;
		global $vehicle_cnt;  
		$vehicle_cnt=0;		
		PrintAllVehicle($root, $local_account_id);
	}

	function PrintAllVehicle($root, $local_account_id)
	{
		global $vehicleid;
		global $vehicle_cnt;
		global $td_cnt;
		global $type_tag;
		global $vcolor1;
		global $vcolor2;
		global $vcolor3;
		global $title;

		global $current_date;
		$vehicle_name_arr=array();
		$imei_arr=array();
		$vehicleid_or_imei_arr=array();
		$vehicle_color=array(); 
	
		if($root->data->AccountID==$local_account_id)
		{
		  $td_cnt =0;
			for($j=0;$j<$root->data->VehicleCnt;$j++)
			{			    
				$vehicle_id = $root->data->VehicleID[$j];
				$vehicle_name = $root->data->VehicleName[$j];
				$vehicle_imei = $root->data->DeviceIMEINo[$j];
			
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
						$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
						if (file_exists($xml_current))
						{
							$color = $vcolor2;
							$vehicle_name_arr[$color][] =$vehicle_name;							
							$vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;						
						}
						else
						{ 
							$color = $vcolor3;      					  
							$vehicle_name_arr[$color][] =$vehicle_name; 							
							$vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;						
						}															
					}
				}
			}
		}
		$color = $vcolor2;common_display_vehicle($vehicle_name_arr[$color],$vehicleid_or_imei_arr[$color],$color,'');
		$color = $vcolor3; common_display_vehicle($vehicle_name_arr[$color],$vehicleid_or_imei_arr[$color],$color,'');
		$ChildCount=$root->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{ 
			PrintAllVehicle($root->child[$i],$local_account_id);
		}
	}
		
	function common_display_vehicle($vehicle_name_arr,$vehicleid_or_imei,$color,$vehicle_type_arr)
	{	
		global $report_station_halt_option;	
		//echo "report_station_halt_option=".$report_station_halt_option."<br>";
		if(sizeof($vehicle_name_arr)>0)
		{
			natcasesort($vehicle_name_arr);
			foreach($vehicle_name_arr as $vehicle)
			{ 
				//echo "<br>in common display";
				$cnt++;          
				if($cnt==1)
				{
					echo'<tr>';
				}         		
						echo'<td align="left">
								<INPUT TYPE="radio"  name="vehicleserial" VALUE="'.$vehicleid_or_imei[$vehicle].'">
							</td>
							<td class=\'text\'> 				
								<font color="'.$color.'">
									'.$vehicle.'
								</font>';								
						echo'</td>';         
				if($cnt==3)
				{
					echo'</tr><tr>';$cnt=0;
				}
			}
		}            
	}

?>