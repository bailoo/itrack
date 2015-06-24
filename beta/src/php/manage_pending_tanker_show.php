<script type="text/javascript" charset="utf-8">
		 $(document).ready(function() {
				//alert("exists=");
				$('#exampleTS').dataTable( {
				"iDisplayLength": 10,
				"fnDrawCallback": function ( oSettings ) {
				/* Need to redo the counters if filtered or sorted */
				if ( oSettings.bSorted || oSettings.bFiltered )
				{
				for ( var i=0, iLen=oSettings.aiDisplay.length ; i<iLen ; i++ )
				{
				$('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr ).html( i+1 );
				}
				}
				},
				"aoColumnDefs": [
				{ "bSortable": false, "aTargets": [ 0 ] }
				],
				"aaSorting": [[ 1, 'asc' ]]
				} );
		   
			
			} );


	</script>
<?php

//*****************************************Getting Admin Account ID and Current UserID*******************************************//
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include('manage_route_vehicle_substation_inherit.php');
	include_once("util_account_detail.php");	
	
	global $parent_admin_id;
	$query_account_admin_id="SELECT account_admin_id FROM account_detail WHERE account_id='$account_id'";
	//echo $query_account_admin_id;
	$result_account_admin_id = mysql_query($query_account_admin_id, $DbConnection);
	$row_account_admin_id =mysql_fetch_object($result_account_admin_id);
	
	$query_admin_id="SELECT account_id FROM account_detail WHERE admin_id='$row_account_admin_id->account_admin_id'";
	//echo $query_admin_id;
	$result_admin_id = mysql_query($query_admin_id, $DbConnection);
	$row_admin_id =mysql_fetch_object($result_admin_id);
	$parent_admin_id=$row_admin_id->account_id;
	
	//echo $parent_admin_id;
	$vehicle_list=array();
	$vehicle_id_list=array();
	$QueryALLVehicle="SELECT vehicle.vehicle_name , vehicle_assignment.device_imei_no ,vehicle.vehicle_id FROM vehicle,vehicle_grouping,vehicle_assignment WHERE vehicle_grouping.account_id=$parent_admin_id and vehicle_grouping.vehicle_id=vehicle.vehicle_id and vehicle_grouping.status=1 AND vehicle.status=1 AND vehicle_assignment.vehicle_id=vehicle.vehicle_id AND vehicle_assignment.status=1";
	$resultALLVehicle = mysql_query($QueryALLVehicle,$DbConnection);
	$String_Vehicle="";$v_name="";$String_Vehicle_not="";
	
	while($rowALLVehicle=mysql_fetch_object($resultALLVehicle))
	{
		$vehicle_list[]=$rowALLVehicle->vehicle_name;
		$vehicle_id_list[]=trim($rowALLVehicle->vehicle_id);
		//$String_Vehicle.=" pending_plant_tanker.vehicle_id=".$rowALLVehicle->vehicle_id." OR ";
		$String_Vehicle.=" pending_plant_tanker.vehicle_name='".$rowALLVehicle->vehicle_name."' OR ";
		$String_Vehicle_not.=" pending_plant_tanker.vehicle_name !='".$rowALLVehicle->vehicle_name."' AND ";
		$v_name.=trim($rowALLVehicle->vehicle_name).",";
	}
	if($String_Vehicle!="")
	{
		$String_Vehicle = substr($String_Vehicle, 0, -3);
		$String_Vehicle_not = substr($String_Vehicle_not, 0, -4);
		$v_name= substr($v_name, 0, -1);
	}
	$v_name=str_replace(' ','%20',$v_name);
	$v_name_from_db="";
	//for gps vehicle
	$QueryRemarks="SELECT pending_plant_tanker.*,account.user_id from pending_plant_tanker,account WHERE ($String_Vehicle) AND  pending_plant_tanker.status=1 AND account.status=1 AND pending_plant_tanker.edit_id=account.account_id AND pending_plant_tanker.pending_status=1";
	//echo $QueryRemarks;
	$resultRemarks = mysql_query($QueryRemarks,$DbConnection);
	$final_data=array();
	//$String_Account="";
	while($rowRemarks=mysql_fetch_object($resultRemarks))
	{
		//$final_data[$rowRemarks->vehicle_id]=$rowRemarks->sno."###".$rowRemarks->vehicle_name."###".$rowRemarks->remarks."###".$rowRemarks->pending_status."###".$rowRemarks->edit_date."###".$rowRemarks->user_id."###".$rowRemarks->gps;
		$final_data[$rowRemarks->vehicle_name]=$rowRemarks->sno."###".$rowRemarks->vehicle_name."###".$rowRemarks->remarks."###".$rowRemarks->pending_status."###".$rowRemarks->edit_date."###".$rowRemarks->user_id."###".$rowRemarks->gps."###".$rowRemarks->edit_id;
		$v_name_from_db.=trim($rowRemarks->vehicle_name).",";		
	}
	
	//for non gps vehicle
	$QueryRemarksNONGPS="SELECT pending_plant_tanker.*,account.user_id from pending_plant_tanker,account WHERE  ($String_Vehicle_not) AND  pending_plant_tanker.status=1 AND account.status=1 AND pending_plant_tanker.edit_id=account.account_id AND pending_plant_tanker.gps=0 AND pending_plant_tanker.pending_status=1 ";
	//echo $QueryRemarksNONGPS;
	$resultRemarksNONGPS = mysql_query($QueryRemarksNONGPS,$DbConnection);
	$final_dataNONGPS=array();
	//$String_Account="";
	while($rowRemarksNONGPS=mysql_fetch_object($resultRemarksNONGPS))
	{
		$final_dataNONGPS[]=$rowRemarksNONGPS->sno."###".$rowRemarksNONGPS->vehicle_name."###".$rowRemarksNONGPS->remarks."###".$rowRemarksNONGPS->pending_status."###".$rowRemarksNONGPS->edit_date."###".$rowRemarksNONGPS->user_id."###".$rowRemarksNONGPS->gps."###".$rowRemarksNONGPS->edit_id;
		//$String_Account.=" account.account_id=".$rowRemarks->edit_id." OR ";
		$v_name_from_db.=trim($rowRemarksNONGPS->vehicle_name).",";	
	}
	
	$v_name_from_db= substr($v_name_from_db, 0, -1);
	$v_name_from_db=str_replace(' ','%20',$v_name_from_db);
echo'
<form name="pending_form" id="pending_form"  method = "post" target="_blank">
<input type=hidden value='.$v_name.' id="gpsvlist">
<input type=hidden value='.$v_name.' id="vehicle_list_hidden">
<input type=hidden value="'.$v_name_from_db.'" id="vehicle_list_from_db">
<center>
	 <body id="dt_example" >
	   <div id="demo">				
	
		<table border="0" class="table  table-bordered menu table-hover" id="exampleTS" align="center"  rules=all bordercolor="#e5ecf5" width="90%">
			<thead>
			<tr bgcolor=silver>
				<td>SL</td><td>Vehicle</td><td>Type</td><td>Remarks</td><td>Create/Edit-Date</td><td>Edited By</td><td>Accept</td>
			</tr>
			</thead>
			';
			//print_r($final_data);
			$String_report_csv=",,Pending Tanker,,,\n";
			$String_report_csv .="SL,VEHICLE,REMARKS,EDIT-DATE,EDITED-BY \n";
			$i=0;$excel_cnt=1;
			//foreach($vehicle_id_list as $vl)
			foreach($vehicle_list as $vl)
			{
				
				$vehicle_name="-";
				$remarks="-";
				$pending_status="-";				
				$edit_date="-";
				$edit_id="-";
				if($final_data[$vl])
				{
					$sno='';$vehicle_name='';$remarks='';$pending_status=0;$edit_date='';$edit_id='';
					//echo $final_data[$vl] ;
					$tuaflag=explode("###",$final_data[$vl]);								
					$sno=$tuaflag[0];
					$vehicle_name=$tuaflag[1];
					$remarks=$tuaflag[2];
					$pending_status=$tuaflag[3];
					//echo "PS=".$pending_status;
					$edit_date=$tuaflag[4];
					$edit_id=$tuaflag[5];							
					$gps=$tuaflag[6];
					$acc_id=$tuaflag[7];
					if($gps==1)
					{
						$gps_status='GPS';
					}
					else
					{
						$gps_status='Non-GPS';
					}
					
					if($pending_status==1 )
					{
						if($user_type=="plant_raw_milk")
						{
							//echo $acc_id."-".$account_id."m<br>";
							if($account_id==$acc_id)
							{
								echo"
								<tr>
									<td></td>
									<td align=center>$vehicle_list[$i]
									<input type=hidden id='vname".$vl."' value='$vehicle_list[$i]' >
									</td>
									<td align=center>$gps_status</td>
									<td align=center><center><input type=text id='sno_remarks_".$vl."' size=110 value='$remarks' readonly ></center></td>							
									<td>$edit_date</td>
									<td>$edit_id</td>
									<td><input type=checkbox name='sno_chk[]' id='sno_chk_".$vl."' checked  value='$vl' > </td>
									<input type=hidden id='sno_update_".$vl."' value='1' >
									<input type=hidden id='type_new_old_".$vl."' value='0' >
									
								</tr>
								";
							}
							else
							{
								echo"
								<tr>
									<td></td>
									<td align=center>$vehicle_list[$i]
									<input type=hidden id='vname".$vl."' value='$vehicle_list[$i]' >
									</td>
									<td align=center>$gps_status</td>
									<td align=center><center><input type=text id='sno_remarks_".$vl."' size=110 value='$remarks' readonly ></center></td>							
									<td>$edit_date</td>
									<td>$edit_id</td>
									<td><input type=checkbox name='sno_chk[]' id='sno_chk_".$vl."' checked  value='$vl' disabled > </td>
									<input type=hidden id='sno_update_".$vl."' value='1' >
									<input type=hidden id='type_new_old_".$vl."' value='0' >
									
								</tr>
								";
							}
							
						}
						else
						{
							echo"
							<tr>
								<td></td>
								<td align=center>$vehicle_list[$i]
								<input type=hidden id='vname".$vl."' value='$vehicle_list[$i]' >
								</td>
								<td align=center>$gps_status</td>
								<td align=center><center><input type=text id='sno_remarks_".$vl."' size=110 value='$remarks' readonly  ></center></td>							
								<td>$edit_date</td>
								<td>$edit_id</td>
								<td><input type=checkbox name='sno_chk[]' id='sno_chk_".$vl."' checked  value='$vl' disabled > </td>
								<input type=hidden id='sno_update_".$vl."' value='1' >
								<input type=hidden id='type_new_old_".$vl."' value='0' >
								
							</tr>
							";
						}
						$String_report_csv.=$excel_cnt.",".$vehicle_list[$i].",".$remarks.",".$edit_date.",".$edit_id." \n";
						$excel_cnt++;
					}
					/*
					else
					{
						if($user_type=="plant_raw_milk")
						{
							echo"
							<tr>
								<td></td>
								<td align=center>$vehicle_list[$i]
								<input type=hidden id='vname".$vl."' value='$vehicle_list[$i]'  >
								</td>
								<td align=center>GPS</td>
								<td align=center><center><input type=text id='sno_remarks_".$vl."' size=110 ></center></td>
								<td>-</td>
								<td>-</td>
								<td><input type=checkbox name='sno_chk[]' id='sno_chk_".$vl."' value='$vl' > </td>
								<input type=hidden id='sno_update_".$vl."' value='0' >
								<input type=hidden id='type_new_old_".$vl."' value='0' >
								
							</tr>
							";
						}
					}
					*/
					
				}
				/*
				else
				{
					if($user_type=="plant_raw_milk")
					{
						echo"
						<tr>
							<td></td>
							<td align=center>$vehicle_list[$i]
							<input type=hidden id='vname".$vl."' value='$vehicle_list[$i]' >
							</td>
							<td align=center>GPS</td>
							<td align=center><center><input type=text id='sno_remarks_".$vl."' size=110 ></center></td>
							<td>-</td>
							<td>-</td>
							<td><input type=checkbox name='sno_chk[]' id='sno_chk_".$vl."' value='$vl' > </td>
							<input type=hidden id='sno_update_".$vl."' value='0' >
							<input type=hidden id='type_new_old_".$vl."' value='1' >
							
						</tr>
						";
					}
				}*/
				
				$i++;
			}
			
			
			foreach($final_dataNONGPS as $ngps)
			{
				$tuaflag=explode("###",$ngps);								
				$sno=$tuaflag[0];
				$vehicle_name=$tuaflag[1];
				$remarks=$tuaflag[2];
				$pending_status=$tuaflag[3];
				//echo "PS=".$pending_status;
				$edit_date=$tuaflag[4];
				$edit_id=$tuaflag[5];	
				
				$gps=$tuaflag[6];
				$acc_id=$tuaflag[7];
				
				if($gps==1)
				{
					$gps_status='GPS';
				}
				else
				{
					$gps_status='Non-GPS';
				}
				
				if($pending_status==1)
				{
					//echo '1ssss';
					if( $user_type=="plant_raw_milk")
					{
						//echo $acc_id."-".$account_id."<br>";
						if($account_id==$acc_id)
						{
							echo"
							<tr>
								<td></td>
								<td align=center>$vehicle_name</td>
								<td align=center>$gps_status</td>
								<td align=center><center><input type=text id='sno_remarks_N_".$vehicle_name."' size=110 value='$remarks' readonly ></center></td>							
								
								<td>$edit_date</td>
								<td>$edit_id</td>							
								<td><input type=checkbox name='sno_chk[]' id='sno_chk_N_".$vehicle_name."' checked  value='N_$vehicle_name' > </td>						
								<input type=hidden id='sno_update_N_".$vehicle_name."' value='1' >
								<input type=hidden id='type_new_old_N_".$vehicle_name."' value='0' >
								<input type=hidden id='vnameN_".$vehicle_name."' value='$vehicle_name' >
							</tr>
							";
						}
						else
						{
							echo"
							<tr>
								<td></td>
								<td align=center>$vehicle_name</td>
								<td align=center>$gps_status</td>
								<td align=center><center><input type=text id='sno_remarks_N_".$vehicle_name."' size=110 value='$remarks' readonly ></center></td>							
								
								<td>$edit_date</td>
								<td>$edit_id</td>							
								<td><input type=checkbox name='sno_chk[]' id='sno_chk_N_".$vehicle_name."' checked  value='N_$vehicle_name'  disabled> </td>						
								<input type=hidden id='sno_update_N_".$vehicle_name."' value='1' >
								<input type=hidden id='type_new_old_N_".$vehicle_name."' value='0' >
								<input type=hidden id='vnameN_".$vehicle_name."' value='$vehicle_name' >
							</tr>
							";
						}
						
					}
					else
					{
						echo"
						<tr>
							<td></td>
							<td align=center>$vehicle_name</td>
							<td align=center>$gps_status</td>
							<td align=center><center><input type=text id='sno_remarks_N_".$vehicle_name."' size=110 value='$remarks' readonly ></center></td>							
							<td>$edit_date</td>
							<td>$edit_id</td>							
							<td><input type=checkbox name='sno_chk[]' id='sno_chk_N_".$vehicle_name."' checked  value='N_$vehicle_name' disabled > </td>						
							<input type=hidden id='sno_update_N_".$vehicle_name."' value='1' >
							<input type=hidden id='type_new_old_N_".$vehicle_name."' value='0' >
							<input type=hidden id='vnameN_".$vehicle_name."' value='N_$vehicle_name' >
						</tr>
						";
					}
					
					$String_report_csv.=$excel_cnt.",".$vehicle_name.",".$remarks.",".$edit_date.",".$edit_id." \n";
					$excel_cnt++;
				}
				/*
				else
					{
						if( $user_type=="plant_raw_milk")
						{
							echo"
							<tr>
								<td></td>
								<td align=center>$vehicle_name</td>
								<td align=center>$gps_status</td>
								<td align=center><center><input type=text id='sno_remarks_N_".$vehicle_name."' size=110 value='' ></center></td>
								<td>-</td>
								<td>-</td>
								<td><input type=checkbox name='sno_chk[]' id='sno_chk_N_".$vehicle_name."' value='N_$vehicle_name' > </td>
								<input type=hidden id='sno_update_N_".$vehicle_name."' value='0' >
								<input type=hidden id='type_new_old_N_".$vehicle_name."' value='0' >
								<input type=hidden id='vnameN_".$vehicle_name."' value='$vehicle_name' >
							</tr>
							";
						}
					}
					*/
			}
				
				
			echo'
			
		</table>
	   </div>
	  </body>
		';
		if( $user_type=="plant_raw_milk")
		{
		echo'
		 <BR><BR><BR><br>
	  <!--<table width=450px  class="table-bordered menu table-hover"align="center"  rules=all bordercolor="#e5ecf5">
	    <tr bgcolor=silver>
			<td>VehicleType</td><td>VehicleName</td><td>Remarks</td>
		</tr>
		<tr bgcolor=ghostwhite>
						<td>NonGPS</td>
						<td align=center><input type=text id="NGPS"  value="" ></td>
						<td align=center><input type=text id="NREM" size=50 value="" ></td>						
		</tr>
	  </table>-->
	  <table width=450px  class="table-bordered menu table-hover"align="center"  rules=all bordercolor="#e5ecf5">
	    <tr bgcolor=silver>
			<td>VehicleName</td><td>Remarks</td>
		</tr>
		<tr bgcolor=ghostwhite>
						
						<td align=center><input type=text id="vehno"  value="" onclick="javascript:show_vehicle_list_pending_tanker(this.id)" Readonly ></td>
						<td align=center><input type=text id="veh_rem" size=50 value="" ></td>						
		</tr>
	  </table>
	  <BR>
		
		';
		}
		//echo "UT=".$user_type;
		if( $user_type=="plant_raw_milk")
		{
			//echo'<tt>* To Add information of NON GPS Vehicle, Please contact Admin/Center to Add Non GPS Vehicle to the List .</tt><br>';
			echo'<input type="button" value="Update" id="enter_button" onclick="javascript:return action_manage_pending_tanker_show(\'edit\')"/>';
		}
		else
		{
			//echo'<tt>* To Add NON GPS Vehicle, Select Vehicle From Manage->Add. Enter Vehicle Information after selecting User. Then Select Add button Only to Add Non-GPS vehicle</tt><br>';
		}
		echo'
		
		&nbsp;
		<input TYPE="hidden" VALUE="Raw_Milk_Invoice" NAME="csv_type">
		<input TYPE="hidden" VALUE="'.$String_report_csv.'" NAME="csv_string"> 
		<!--<input type="button" onclick="javascript:manage_csv_post(\'src/php/report_csv.php\',\'pending_form\');" value="Get CSV" class="noprint">-->
		<input type="button" onclick="javascript:manage_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">
		</center>
		
		<div id="blackout"> </div>
	<div id="divpopup_plant">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="skyblue">							
			<tr>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_vehicle_list_pending_tanker()" class="hs3">Close</a></td> 													
			</tr> 
			<tr>
				<td colspan="5" valign="top" align="justify">ADD VEHICLE</td>
			</tr>							
		</table>
		<br>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" rules="all" style="background-color:ghostwhite;">							
			<tr>
				<td>Select Vehicle :</td><td>
				<input type="text" id="vehicle_list" name="vehicle_list"  size="30" onKeyUp="getScriptPage_raw_milk(this.value,this.id,\'box\')">
				<div id="box2" class="input-div-route" style="display:none"></div>
				</td>
				
			</tr>
			<tr><td colspan="2">
					<input type="button" value="Add" onclick="javascript:close_vehicle_list();">
				</td></tr>
		</table>
		
	</div>
    <input type="hidden" id="tmp_serial"/>
		
	 </form>
 ';
?>
