<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include('manage_route_vehicle_substation_inherit.php');
	include_once("util_account_detail.php");
	//echo "add##"; 
	$root=$_SESSION['root'];
	//$common_id1=$_POST['common_id'];
	$common_id1=$account_id;
	//echo 'payas='.$account_id;
	//*****************************************Getting Admin Account ID and Current UserID*******************************************//	
	global $parent_admin_id;
        /* 
	$query_account_admin_id="SELECT account_admin_id FROM account_detail WHERE account_id='$account_id'";
	//echo $query_account_admin_id;
	$result_account_admin_id = mysql_query($query_account_admin_id, $DbConnection);
	$row_account_admin_id =mysql_fetch_object($result_account_admin_id);
        */
        $row_account_admin_id =getAccountAdminId($account_id,$DbConnection);
	/*
	$query_admin_id="SELECT account_id FROM account_detail WHERE admin_id='$row_account_admin_id->account_admin_id'";
	//echo $query_admin_id;
	$result_admin_id = mysql_query($query_admin_id, $DbConnection);
	$row_admin_id =mysql_fetch_object($result_admin_id);
	$parent_admin_id=$row_admin_id->account_id;*/
        $parent_admin_id=getAccountIdByAdminId($row_account_admin_id,$DbConnection);
	
	global $user_name;
	/*
        $query="SELECT user_id from account where account_id='$account_id'";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$user_name=$row->user_id;
        */
        $user_name=getUserID($account_id,1,$DbConnection);
	
	$vehicle_list=array();
	get_user_vehicle($root,$account_id);
	//print_r($vehicle_list);
	$vehicle_list1 = array_unique($vehicle_list);	
	$final_vehicle_list=array();
	$all_vehicles = "";
	//$v2 = " DL12345";
	//$v3 = trim($v2);
	//echo "v3=".$v3;
	foreach($vehicle_list1 as $vl){
		$final_vehicle_list[]=$vl;
		//echo "vl=".$vl."<br>"; 
		$all_vehicles.= trim($vl).",";
		//echo "all_vehicles=".$all_vehicles."<br>";
	}
	//$js_array=json_encode(final_vehicle_list);
	$all_vehicles = substr($all_vehicles, 0, -1);
	//$all_vehicles = trim($all_vehicles);
	$all_vehicles = str_replace(' ','%20',$all_vehicles);
	//echo  $user_name;	
	
	//===Getting Plant 
	$final_plant_list=array();
    $final_plant_name_list=array();
	$final_plant_list[0]="";
    $final_plant_name_list[0]="";

	//===Getting Chilling Plant
	$final_chillplant_list=array();
    $final_chillplant_name_list=array();
	$final_chillplant_list[0]="";
    $final_chillplant_name_list[0]="";
	
	
	$final_lorry_list=array();
	if($user_type=="raw_milk"){		
		$raw_milk_account = assign_to_till_root($account_id);
		
			//echo $account_id;
	
			//print_r($raw_milk_account);
			//print_r($parent_account_ids);
		if($raw_milk_account){
		    foreach($raw_milk_account as $rma)
			{
				//plant
				/*$query_plant = "SELECT customer_no,station_name FROM station WHERE type=1 AND user_account_id='$rma' AND status=1";
				$result_query = mysql_query($query_plant,$DbConnection);
				while($row=mysql_fetch_object($result_query))
				{
					//echo $row->customer_no;
					$final_plant_list[]=$row->customer_no;
					$final_plant_name_list[]=$row->station_name;
				}*/
				//plant
				$DataPlant = getDetailAllCustomerNoStationNameStation($rma,$DbConnection);
				foreach($DataPlant as $dt)
				{
					$final_plant_list[]=$dt['final_plant_list'];
					$final_plant_name_list[]=$dt['final_plant_name_list'];
					
				}
				
			}
			
		}
	}
	else
	{
		/*//plant
		$query_plant = "SELECT customer_no,station_name FROM station WHERE type=1 AND user_account_id='$parent_admin_id' AND status=1";
		$result_query = mysql_query($query_plant,$DbConnection);
		while($row=mysql_fetch_object($result_query))
		{
			//echo $row->customer_no;
			$final_plant_list[]=$row->customer_no;
			$final_plant_name_list[]=$row->station_name;
		}
		//chilling plant
		$query_chillplant = "SELECT customer_no,station_name FROM station WHERE type=2 AND user_account_id='$parent_admin_id' AND status=1";
		$result_chillquery = mysql_query($query_chillplant,$DbConnection);
		while($rowchill=mysql_fetch_object($result_chillquery))
		{
			//echo $row->customer_no;
			$final_chillplant_list[]=$rowchill->customer_no;
			$final_chillplant_name_list[]=$rowchill->station_name;
		}*/
               //plant
		
		$DataPlantP = getDetailAllCustomerNoStationNameStation($parent_admin_id,$DbConnection);
		foreach($DataPlantP as $dt)
		{
			$final_plant_list[]=$dt['final_plant_list'];
			$final_plant_name_list[]=$dt['final_plant_name_list'];
			
		}
		
		//chilling plant		
		$dataChil = getCustomerNoStationNext($parent_admin_id,$DbConnection);
		foreach($dataChil as $dt)
		{
			//echo $row->customer_no;
			$final_chillplant_list[]=$dt['final_chillplant_list'];
			$final_chillplant_name_list[]=$dt['final_chillplant_name_list'];
		}
	}	/*******************************************************************************************************************************/
	
	
	
	//default chilling plant
	/*$default_customer_no="";
	$query_default_chillplant = "SELECT customer_no FROM transporter_chilling_plant_assignment WHERE account_id='$account_id' AND status=1";
	//echo $query_default_chillplant."<br>";
	$result_default_chillquery = mysql_query($query_default_chillplant,$DbConnection);
	$numrows_default_chillquery  = mysql_num_rows($result_default_chillquery);	
	if($numrows_default_chillquery!=0)
	{
		$row=mysql_fetch_object($result_default_chillquery);
		$default_customer_no = $row->customer_no;
		//echo "RM=".$default_customer_no;
	}
	else{
		$default_customer_no ="";
	}
        */
        $default_customer_no=getCustomerNoTCPA($account_id,$DbConnection);;
	global $self_child_transporter_id;
	$self_child_transporter_id="";
	global $option_transporter;
	$option_transporter="<option value='select'>Select</option>";	
	select_group_account_hierarchy_transporter($root);
	/*echo"<select>";	
	echo $option_transporter;
	echo"</select>";*/
	$self_child_transporter_id=substr($self_child_transporter_id,0,-1);
	//echo "<br>".$self_child_transporter_id;
	function select_group_account_hierarchy_transporter($AccountNode)
	{
		//echo"AC";
		global $DbConnection;
		global $self_child_transporter_id;
		global $option_transporter;
		// $option_transporter="AC";
		$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
		$account_id_local=$AccountNode->data->AccountID;
		$group_id_local=$AccountNode->data->AccountGroupID;
		//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
		$account_name=$AccountNode->data->AccountName;
		$ChildCount=$AccountNode->ChildCnt;		
		/*$queryType="SELECT user_type,user_id from account WHERE account_id='$account_id_local'";
		//echo "<br>".$queryType;
		$resultType=mysql_query($queryType,$DbConnection);
		$rowType=mysql_fetch_row($resultType);*/
                $rowType=getUsertypeUserIDAccount($account_id_local,$DbConnection);
		$function_account_type=$rowType[0];
		$user_id1=$rowType[1];
		//echo "userType=".$function_account_type."<br>";
		//echo"<option value='select'>Select</option>	";
		if($function_account_type=='raw_milk' ){
			
		   //$option_transporter.="<option value=$account_id_local>$account_name</option>";
		   $option_transporter.="<option value=$account_id_local>$user_id1</option>";
			$self_child_transporter_id.="'$account_id_local'".',';
		
		}
	
		for($i=0;$i<$ChildCount;$i++)
		{     
			select_group_account_hierarchy_transporter($AccountNode->child[$i]);
		} 
		
	}
	
	//getting chilling plant of supplier /raw milk
	if($user_type=="raw_milk")
	{
		$default_customer_no_supplier=array();
		/*$query_default_chillplant_supplier = "SELECT customer_no FROM transporter_chilling_plant_assignment WHERE account_id IN($self_child_transporter_id) AND status=1";
		//echo $query_default_chillplant_supplier."<br>";
		$query_default_chillplant_supplier = mysql_query($query_default_chillplant_supplier,$DbConnection);
		while($rowchillA=mysql_fetch_object($query_default_chillplant_supplier))
		{
			$default_customer_no_supplier[] = $rowchillA->customer_no;
			
		}*/               
		$dataCN = getDetailAllCustomerNoTCPA($self_child_transporter_id,$DbConnection);
		foreach($dataCN as $dt)
		{
				
			$default_customer_no_supplier[] = $dt['default_customer_no_supplier'];
			
		}
                
		$conditionStr="";
		foreach($default_customer_no_supplier as $crma)
		{
			$conditionStr=$conditionStr." station.customer_no='$crma' OR ";
		}
		$conditionStr=substr($conditionStr,0,-3);
		/*//chill plant
		$query_chillplant = "SELECT customer_no,station_name FROM station WHERE type=2 AND ($conditionStr) AND status=1";
		//echo $query_chillplant;
		$result_chillquery = mysql_query($query_chillplant,$DbConnection);
		while($rowchill=mysql_fetch_object($result_chillquery))
		{
			$final_chillplant_list[]=$rowchill->customer_no;
			$final_chillplant_name_list[]=$rowchill->station_name;
		}*/
                //chill plant
		
		$dataCNN = getCustomerNoCustNameStation($conditionStr,$DbConnection);
		foreach($dataCNN as $dt)
		{
			$final_chillplant_list[]=$dt['final_chillplant_list'];
			$final_chillplant_name_list[]=$dt['final_chillplant_name_list'];
		}
		
		//getting invoice lorry number of only open for desired transporter
		/*$query_lorry_open = "SELECT lorry_no FROM invoice_mdrm WHERE invoice_status=1 AND transporter_account_id IN($self_child_transporter_id) AND status=1";
		//echo "QLO=".$query_lorry_open;
		$result_lorry_open = mysql_query($query_lorry_open,$DbConnection);
		while($row_lorry_open=mysql_fetch_object($result_lorry_open))
		{
			$final_lorry_list[]=$row_lorry_open->lorry_no;					
		}*/
                $final_lorry_list=lorrylistTransporterAll($self_child_transporter_id,$DbConnection);
		//=================================================================
	}
	//print_r($final_lorry_list);
	
	
	function read_sent_db($account_id)
	{
		//echo"AC=".$account_id;
		global $LRNO;  global $Vehicle; global $Transporter; global $mobileno; global $emailid; global $drivername; global $drivermobile; global $qty; global $snf_per; global $snf_kg;
		global $fat_kg; global $fat_per; global $milk_age; global $disp_time; global $target_time; global $plant; global $chillplant; global $tankertype;
		global $sno_id;
	
		//------fetching from database----------//
		global $DbConnection;
                
		/*$queryPending = "SELECT * FROM invoice_mdrm WHERE status=1 AND invoice_status=5 AND (vehicle_no is NULL OR vehicle_no='') AND transporter_account_id IN ($account_id) ";
		//echo $queryPending;
		$resultPending = mysql_query($queryPending,$DbConnection);
		while($rowPending = mysql_fetch_object($resultPending))
		{
			$LRNO[] = $rowPending->lorry_no;
			$Vehicle[] = $rowPending->vehicle_no;
			$Transporter[] =  $rowPending->transporter_account_id;
			$emailid[] = $rowPending->email;
			$mobileno[] = $rowPending->mobile;
			$drivername[] = $rowPending->driver_name;
			$drivermobile[] = $rowPending->driver_mobile;
			$qty[] = $rowPending->qty_kg;
			$fat_per[] = $rowPending->fat_percentage;
			$snf_per[] = $rowPending->snf_percentage;
			$fat_kg[] = $rowPending->fat_kg;
			$snf_kg[] = $rowPending->snf_kg;
			$milk_age[] = $rowPending->milk_age;
			$disp_time[] = $rowPending->dispatch_time;
			$target_time[] = $rowPending->target_time;
			$plant[] = $rowPending->plant;
			$chillplant[] = $rowPending->chilling_plant;
			$tankertype[] =  $rowPending->tanker_type;
			$sno_id[] = $rowPending->sno;
		}*/
		 $rowPending_data=getDetailAllInvoiceMdrmNextAP($account_id,$DbConnection);
                 foreach($rowPending_data as $rowPending )
		{
			$LRNO[] = $rowPending['LRNO'];
			$Vehicle[] = $rowPending['Vehicle'];
			$Transporter[] =  $rowPending['Transporter'];
			$emailid[] = $rowPending['emailid'];
			$mobileno[] = $rowPending['mobileno'];
			$drivername[] = $rowPending['drivername'];
			$drivermobile[] = $rowPending['drivermobile'];
			$qty[] = $rowPending['qty'];
			$fat_per[] = $rowPending['fat_per'];
			$snf_per[] = $rowPending['snf_per'];
			$fat_kg[] = $rowPending['fat_kg'];
			$snf_kg[] = $rowPending['snf_kg'];
			$milk_age[] = $rowPending['milk_age'];
			$disp_time[] = $rowPending['disp_time'];
			$target_time[] = $rowPending['target_time'];
			$plant[] = $rowPending['plant'];
			$chillplant[] = $rowPending['chillplant'];
			$tankertype[] =  $rowPending['tankertype'];
			$sno_id[] =$rowPending['sno_id'];
		}
		//---------------------------------------//

	}
	echo"
	<table width=100%>
		<tr>
			<th>
				<u>RAW MILK INVOICE</u>
			</th>
		</tr>
		<!--<tr>
			<th>
			";
			if($upload_status=="1")
			{		
				echo"
				<input type=radio id=radio_input name=radio_input value='manual'  onclick=invoice_uploder_div(this.value) >Manual &nbsp;&nbsp; <input type=radio id=radio_input name=radio_input value='excelupload' checked onclick=invoice_uploder_div(this.value)>ExcelUpload
				";
			}
			else
			{
				echo"
				<input type=radio id=radio_input name=radio_input value='manual' checked onclick=invoice_uploder_div(this.value) >Manual &nbsp;&nbsp; <input type=radio id=radio_input name=radio_input value='excelupload'  onclick=invoice_uploder_div(this.value)>ExcelUpload
				";
			}
			echo"
			</th>
		</tr>
		
		<tr>
			<th>
			 <div id=uploader_div style='display:none;' >
				<center><h3>Invoice Excel File Upload:</h3></center>

				<table align=center border=1 width=40% rules=all cellpadding=10 cellpadding=10>
					<tr>
						<td>
							<form enctype='multipart/form-data' action='home.htm' method='POST'>
								<table>
									<tr>											
										<td>Choose a file to upload<input type='hidden' name='MAX_FILE_SIZE' value='100000' /><input type='hidden' name='upload_status' id='upload_status' value='1' />	</td>
										<td>:</td> 
										<td><input name='uploadedfile' type='file' /></td>
										<td colspan='1' align='center'><input type='submit' value='Upload File' /></td>
									</tr>
									<tr>
										<td colspan=4><a href='src/php/invoice_milk_format_excel.php?download_file=raw_milk_format.xlsx'>Download Invoice Format in Excel</a></td>
									</tr>
								</table>
							</form>
						</td>		
					</tr>
				</table>
			 </div>
			</th>
		</tr>-->
	</table>
	<form name='manage1'>
	
	
	<input type='hidden' id='vehicle_list_hidden' value='".$all_vehicles."'>
	<table width=100%>
		
		
		<tr>
			<td>
				<div id=invoice_interface >
				";
					
					//include('manage_add_raw_milk_usertype_interface.php');
					
					include('manage_add_raw_milk_usertype_interface_approve.php');
					
						
							$LRNO=array();  $Vehicle = array(); $Transporter = array(); $mobileno=array(); $emailid=array(); $drivername=array(); $drivermobile=array(); $qty=array(); $snf_per=array(); $snf_kg=array();
							$fat_kg=array(); $fat_per=array(); $milk_age=array(); $disp_time=array(); $target_time=array(); $plant=array(); $chillplant=array();$tankertype=array();
							$sno_id=array();
							
							read_sent_db($self_child_transporter_id);
							
							$total_lr=sizeof($LRNO);
							//print_r($chillplant);
							//echo $total_lr;
							$LR_js_array = json_encode($LRNO);
							$Vehicle_js_array = json_encode($Vehicle);
							$Transporter_js_array = json_encode($Transporter);
							$mobileno_js_array = json_encode($mobileno);
							$emailid_js_array = json_encode($emailid);
							$drivername_js_array = json_encode($drivername);
							$drivermobile_js_array = json_encode($drivermobile);
							$qty_js_array = json_encode($qty);
							$snf_per_js_array = json_encode($snf_per);
							$snf_kg_js_array = json_encode($snf_kg);
							$fat_kg_js_array = json_encode($fat_kg);
							$fat_per_js_array = json_encode($fat_per);
							$disp_time_js_array = json_encode($disp_time);
							$milk_age_js_array = json_encode($milk_age);
							$target_time_js_array = json_encode($target_time);
							$plant_js_array = json_encode($plant);
							$chillplant_js_array = json_encode($chillplant);
							$tankertype_js_array = json_encode($tankertype);
							$sno_id_js_array = json_encode($sno_id);
							//echo "tq".$tankertype_js_array; 
							
							echo"<script> 
							
							addfieldTotal($total_lr); 
							var tot_loop=$total_lr;
							
							
								var LR_Tags=$LR_js_array;
								var Vehicle_Tags=$Vehicle_js_array;
								var Transporter_Tags=$Transporter_js_array;
								var mobileno_Tags=$mobileno_js_array;
								var emailid_Tags=$emailid_js_array;
								var drivername_Tags=$drivername_js_array;
								var drivermobile_Tags=$drivermobile_js_array;
								var qty_Tags=$qty_js_array;
								var snf_per_Tags=$snf_per_js_array;
								var snf_kg_Tags=$snf_kg_js_array;
								var fat_kg_Tags=$fat_kg_js_array;
								var fat_per_Tags=$fat_per_js_array;
								var disp_time_Tags=$disp_time_js_array;
								var milk_age_Tags=$milk_age_js_array;
								var target_time_Tags=$target_time_js_array;
								var plant_Tags=$plant_js_array;
								var chillplant_Tags=$chillplant_js_array;
								var tankertype_Tags=$tankertype_js_array;
								var sno_id_Tags=$sno_id_js_array;
								
								for(var l=0;l<$total_lr;l++)
								{
									document.getElementById('lrno:'+l).value=LR_Tags[l];
									document.getElementById('vehno:'+l).value=Vehicle_Tags[l];
									document.getElementById('transporter:'+l).value=Transporter_Tags[l];
									document.getElementById('email:'+l).value=emailid_Tags[l];
									document.getElementById('mobile:'+l).value=mobileno_Tags[l];
									document.getElementById('driver:'+l).value=drivername_Tags[l];
									document.getElementById('drivermobile:'+l).value=drivermobile_Tags[l];
									document.getElementById('fat_kg:'+l).value=fat_kg_Tags[l];
									document.getElementById('snf_kg:'+l).value=snf_kg_Tags[l];
									
									document.getElementById('qty:'+l).value=qty_Tags[l];
									document.getElementById('fat_per:'+l).value=fat_per_Tags[l];
									put_fat_kg(fat_per_Tags[l],'fat_per:'+l);
									
									document.getElementById('snf_per:'+l).value=snf_per_Tags[l];
									put_snf_kg(snf_per_Tags[l],'snf_per:'+l);
									
									document.getElementById('milk_age:'+l).value=milk_age_Tags[l];
									document.getElementById('disp_time:'+l).value=disp_time_Tags[l];
									document.getElementById('target_time:'+l).value=target_time_Tags[l];
									document.getElementById('plant:'+l).value=plant_Tags[l];
									var selectbox_plant=document.getElementById('plant:'+l);
									for(i=selectbox_plant.options.length-1;i>=0;i--)
									{
										if(!selectbox_plant.options[i].selected)
										selectbox_plant.remove(i);
									}
									
									document.getElementById('chillplant:'+l).value=chillplant_Tags[l];
									document.getElementById('chillplant:'+l).readonly = true;
									if(tankertype_Tags[l]=='Production')
									{
										document.getElementById('tankertype:'+l).value=1;
									}
									else if(tankertype_Tags[l]=='Conversion')
									{
										document.getElementById('tankertype:'+l).value=2;
									}
									
									var selectbox_tankertype=document.getElementById('tankertype:'+l);
									for(i=selectbox_tankertype.options.length-1;i>=0;i--)
									{
										if(!selectbox_tankertype.options[i].selected)
										selectbox_tankertype.remove(i);
									}
									
									document.getElementById('sno_id:'+l).value=sno_id_Tags[l];
									
								}
								
								
							</script>";
						
					
	echo'
				</div>
			</td>
		</tr>
		<tr id="submit_show">
			<td>
				<center><br>
						<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_invoice_raw_milk(\'add\')" value="Add Now">
						<br><!--<input type="button" id="enter_button_back" name="enter_button_back" Onclick="javascript:show_option(\'manage\',\'add_raw_milk_usertype\');" value="Back/Add New" style="visibility:hidden;">
						<br>-->
						<div id="loading_status" name="loading_status" />	
			
				</center>
			</td>
		</tr>
	</table>
	
	<div id="blackout"> </div>
	<div id="divpopup_plant">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="skyblue">							
			<tr>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_vehicle_list()" class="hs3">Close</a></td> 													
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
	<script>
	//alert(tot_loop);
	/*if(tot_loop > 0)
	{
		
		document.getElementById("enter_button").disabled=false;
		
	}
	else
	{
		//alert("0");
		document.getElementById("enter_button").disabled=true;
	}*/
	</script>
	</form>';
	
	function get_user_vehicle($AccountNode,$account_id)
	{
		//echo "hi".$account_id;
		global $vehicleid;
		global $vehicle_cnt;
		global $td_cnt;
		global $DbConnection;
		global $vehicle_list;
		if($AccountNode->data->AccountID==$account_id)
		{
			$td_cnt =0;
			for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
			{			    
				$vehicle_id = $AccountNode->data->VehicleID[$j];
				$vehicle_name = $AccountNode->data->VehicleName[$j];
				$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
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
						
						
							$vehicle_list[]=$vehicle_name;
						
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
	function assign_to_till_root($account_id_local1)
	{	
		global $DbConnection;	
		global $parent_account_ids;	 
		global $acc_size;			
			
		/*$query = "SELECT account_admin_id FROM account_detail WHERE account_id='$account_id_local1'";	
		//echo $query;
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_row($result);
		$admin_id=$row[0];*/
                $row=  getAcccountAdminIdAdminId($account_id_local1,$DbConnection);
                $admin_id=$row[0];
			
		/*$query1 = "SELECT account_id FROM account_detail WHERE admin_id='$admin_id'";
		//echo "<br>".$query;	
		$result=mysql_query($query1,$DbConnection);
		$row1=mysql_fetch_row($result);
		$function_account_id=$row1[0];*/
                $row1=getAccountIdByAdminId($admin_id,$DbConnection);
                $function_account_id=$row1;
		//echo "account_id=".$function_account_id.'<br>';
		//echo "IN=".	$function_account_id;
		/*$queryType="SELECT user_type from account WHERE account_id='$function_account_id'";
		//echo "<br>".$queryType;
		$resultType=mysql_query($queryType,$DbConnection);
		$rowType=mysql_fetch_row($resultType);
		$function_account_type=$rowType[0];
		//echo "userType=".$function_account_type."<br>";*/
		$utype=getUserTypeAccount($function_account_id,$DbConnection);
                $function_account_type=$utype;
                
		if($function_account_type!='raw_milk')
		{
			$parent_account_ids[]=$function_account_id;
			//print_r($parent_account_ids);
			return $parent_account_ids;
		}		
		
		else
		{			
			$final_account_id=assign_to_till_root($function_account_id);
			//query to check non transporter from account table  usertype='raw_milk'
			////////
			$parent_account_ids[]=$function_account_id;
			//echo"acc1=".$function_account_id."<br>"."acc1=".$function_account_id."<br>"."acc1=".$parent_account_ids."<br>";				
			return $parent_account_ids;					
		}
		//return $account_id_local1;
	}
?>  
