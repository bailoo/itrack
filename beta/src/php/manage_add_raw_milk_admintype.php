<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include('manage_route_vehicle_substation_inherit.php');
	include_once("util_account_detail.php");
	//echo "add##"; 
	$pending=0;
	$pending=$_REQUEST['pending'];
	if($pending=="")
	{
		$pending=0;
	}
	$root=$_SESSION['root'];
	//$common_id1=$_POST['common_id'];
	$common_id1=$account_id;
	
	//*****************************************Getting Admin Account ID and Current UserID*******************************************//	
	global $parent_admin_id;
	/*$query_account_admin_id="SELECT account_admin_id,admin_id FROM account_detail WHERE account_id='$account_id'";
	//echo $query_account_admin_id;
        
	$result_account_admin_id = mysql_query($query_account_admin_id, $DbConnection);
	$row_account_admin_id =mysql_fetch_object($result_account_admin_id);
	$aadminId=$row_account_admin_id->admin_id;
        */
        $row_account_admin_id=getAcccountAdminIdAdminId($account_id,$DbConnection);
	$aadminId=$row_account_admin_id[1];       
        $aaccount_admin_id=$row_account_admin_id[0]; 
	/*$query_admin_id="SELECT account_id FROM account_detail WHERE admin_id='$row_account_admin_id->account_admin_id'";
	//echo $query_admin_id;
	$result_admin_id = mysql_query($query_admin_id, $DbConnection);
	$row_admin_id =mysql_fetch_object($result_admin_id);
	$parent_admin_id=$row_admin_id->account_id;
        */
        $row_admin_id=getAcccountAccountId($aaccount_admin_id,$DbConnection);
	$parent_admin_id=$row_admin_id[0];
        //echo $parent_admin_id;
        
	global $user_name;
	/*$query="SELECT user_id from account where account_id='$account_id'";
	$result=mysql_query($query,$DbConnection);
	$row=mysql_fetch_object($result);
	$user_name=$row->user_id;
        */
        $user_name=getUserID($account_id,'1',$DbConnection);
	//echo $user_name;
        
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
	
	
	//plant
	//$query_plant = "SELECT customer_no,station_name FROM station WHERE type=1 AND user_account_id='$account_id' AND status=1";
	//$result_query = mysql_query($query_plant,$DbConnection);
        $data_cus_station1=getDetailAllCustomerNoStationNameStation($account_id,$DbConnection);
	//while($row=mysql_fetch_object($result_query))
        foreach($data_cus_station1 as $row)
	{
		//echo $row->customer_no;
		$final_plant_list[]=$row['final_plant_list'];
		$final_plant_name_list[]=$row['final_plant_name_list'];
	}
        
        $data_cus_station2=getCustomerNoStationNext($account_id,$DbConnection);
        //chilling plant
	//$query_chillplant = "SELECT customer_no,station_name FROM station WHERE type=2 AND user_account_id='$account_id' AND status=1";
	//$result_chillquery = mysql_query($query_chillplant,$DbConnection);
	//while($rowchill=mysql_fetch_object($result_chillquery))
        foreach($data_cus_station2 as $rowchill)
	{
		//echo $row->customer_no;
		/*$final_chillplant_list[]=$rowchill['final_plant_list'];
		$final_chillplant_name_list[]=$rowchill['final_plant_name_list'];*/
                $final_chillplant_list[]=$dt['final_chillplant_list'];
                $final_chillplant_name_list[]=$dt['final_chillplant_name_list'];
	}
	/*******************************************************************************************************************************/
	/*$parent_account_ids=array();
	assign_to_till_root($account_id);
	print_r($parent_account_ids);
	*/
        /*
	$final_lorry_list=array();
	$query_lorry_open = "SELECT lorry_no FROM invoice_mdrm WHERE invoice_status=1  AND status=1";
	//echo "QLO=".$query_lorry_open;
	$result_lorry_open = mysql_query($query_lorry_open,$DbConnection);
	while($row_lorry_open=mysql_fetch_object($result_lorry_open))
	{
		$final_lorry_list[]=$row_lorry_open->lorry_no;					
	}
        */
        $final_lorry_list=lorrylistAll($DbConnection);
	//print_r($final_lorry_list);
	
	global $option_transporter;
	global $uploadTransporter;
	$uploadTransporter=array();
	$option_transporter="<option value='select'>Select</option>";	
	global $tmCnt;
	$tmCnt=0;
	//echo "Pending=".$pending;
	if($pending==0)
	{
		select_group_account_hierarchy_transporter($root,$aadminId);
	}
	else if( $pending==1 || $pending==2)
	{
		select_group_account_hierarchy_transporter_subuser($root,$aadminId);
	}
	//global $tmCnt;
	//echo $tmCnt;
	/*echo"<select>";	
	echo $option_transporter;
	echo"</select>";*/
	//print_r($uploadTransporter);
	//print_r($AccountNode);
	
	function select_group_account_hierarchy_transporter($AccountNode,$aadminId)
	{
		//echo"AC";
		global $DbConnection;
		global $option_transporter;
		global $uploadTransporter;//bin type
	    global $tmCnt;
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
		$rowType=mysql_fetch_row($resultType);
                */
                $rowType= getUsertypeUserIDAccount($account_id_local,$DbConnection);
		$function_account_type=$rowType[0];
		$user_id1="";
		$user_id1=$rowType[1];
		//echo "userType=".$function_account_type."<br>";
		//echo"<option value='select'>Select</option>	";
		//echo "ad1=".$AccountNode->data->AccountAdminID ."ad2=".$aadminId."<br>";
		if($AccountNode->data->AccountAdminID==$aadminId)
		{
		
			if($function_account_type=='raw_milk' ){
				// $tmCnt++;
				
			  $option_transporter.="<option value=$account_id_local>$user_id1</option>";
			  //$option_transporter.="<option value=$account_id_local>$account_name</option>";
			  $uploadTransporter[trim(strtolower($user_id1))]=$account_id_local;
			  //$uploadTransporter[trim(strtolower($account_name))]=$account_id_local;
			
			}
		}
	//$tmCnt++;
		for($i=0;$i<$ChildCount;$i++)
		{     
			select_group_account_hierarchy_transporter($AccountNode->child[$i],$aadminId);
		} 
		
	}
	
	function select_group_account_hierarchy_transporter_subuser($AccountNode,$aadminId)
	{
		//echo"AC";
		global $DbConnection;
		global $option_transporter;
		global $uploadTransporter;//bin type
	    global $tmCnt;
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
		$rowType=mysql_fetch_row($resultType);
                */
                $rowType= getUsertypeUserIDAccount($account_id_local,$DbConnection);
		$function_account_type=$rowType[0];
		$user_id1="";
		$user_id1=$rowType[1];
		//echo "userType=".$function_account_type."<br>";
		//echo"<option value='select'>Select</option>	";
		//echo "ad1=".$AccountNode->data->AccountAdminID ."ad2=".$aadminId."<br>";
		//if($AccountNode->data->AccountAdminID==$aadminId)
		{
		
			if($function_account_type=='raw_milk' ){
				// $tmCnt++;
				
			  $option_transporter.="<option value=$account_id_local>$user_id1</option>";
			  //$option_transporter.="<option value=$account_id_local>$account_name</option>";
			  $uploadTransporter[trim(strtolower($user_id1))]=$account_id_local;
			  //$uploadTransporter[trim(strtolower($account_name))]=$account_id_local;
			
			}
		}
	//$tmCnt++;
		for($i=0;$i<$ChildCount;$i++)
		{     
			select_group_account_hierarchy_transporter_subuser($AccountNode->child[$i],$aadminId);
		} 
		
	}
	
	function read_sent_file($read_excel_path)
	{
		global $LRNO;  global $Vehicle; global $Transporter; global $mobileno; global $emailid; global $drivername; global $drivermobile; global $qty; global $snf_per; global $snf_kg;
		global $fat_kg; global $fat_per; global $milk_age; global $disp_time; global $target_time; global $plant; global $chillplant; global $tankertype;
		global $error_upload;
		global $objPHPExcel_1;
		global $uploadTransporter;
		
		$objPHPExcel_1 = null;
		$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);	

		$cellIterator = null;
		$column = null;
		$row = null;

		//################ FIRST TAB ############################################
		//$read_completed = false;
		//$read_red = false;
		foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) 
		{
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false);
			$i=0;
			foreach ($cellIterator as $cell) 
			{			
				$column = $cell->getColumn();
				$row = $cell->getRow();	
				//echo $column."-".$row."<br>";
							
				if($row>1)
				{	
					$tmp_val="A".$row;
					$lrno_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					//echo $lrno_tmp;
					if($lrno_tmp==""){break;}
					$LRNO[] = $lrno_tmp;
					
					$tmp_val="B".$row;
					$vehicle_tmp="";
					$vehicle_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();					
					$Vehicle[] = $vehicle_tmp;
					
					
					$tmp_val="C".$row;
					$email_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$emailid[] = $email_tmp;
					
					$tmp_val="D".$row;
					$mobile_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$mobileno[] = $mobile_tmp;
					
					$tmp_val="E".$row;
					$driver_name_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$drivername[] = $driver_name_tmp;
					
					$tmp_val="F".$row;
					$driver_mob_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$drivermobile[] = $driver_mob_tmp;
					
					$tmp_val="G".$row;
					$qty_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$qty[] = $qty_tmp;
					
					$tmp_val="H".$row;
					$fat_per_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$fat_per[] = $fat_per_tmp;
					
					$tmp_val="I".$row;
					$snf_per_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$snf_per[] = $snf_per_tmp;
					
					$tmp_val="J".$row;
					$fat_kg_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$fat_kg[] = $fat_kg_tmp;
					
					$tmp_val="K".$row;
					$snf_kg_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$snf_kg[] = $snf_kg_tmp;
					
					$tmp_val="L".$row;
					$milk_age_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$milk_age[] = $milk_age_tmp;
					
					$tmp_val="M".$row;
					$disp_time_tmp1 = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					if(!is_numeric($disp_time_tmp1))
					{
						$disp_time_tmp ="";
						$error_disp_tmp="<font color=red size=2>Dispatch Date Time Format Error in Excel (Col,Row)  ".$tmp_val."</red><br>";
					}
					else{
						$disp_time_tmp =PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY/mm/dd hh:mm:ss');
						$error_disp_tmp="";
					}
					//echo $disp_time_tmp1."<br>";
					//$formatCode1 = $objPHPExcel_1->getActiveSheet()->getStyle(tmp_val)->getNumberFormat()->getFormatCode();
					//echo $formatCode1."<br>";
					
					//$disp_time_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					$disp_time[] = $disp_time_tmp;
					
					$tmp_val="N".$row;
					$target_time_tmp1 = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					if(!is_numeric($target_time_tmp1))
					{
						$target_time_tmp ="";
						$error_target_tmp="<font color=red size=2>Target Date Time Format Error in Excel (Col,Row) ".$tmp_val."</red><br>";
					}
					else{
						$target_time_tmp =PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY/mm/dd hh:mm:ss');
						$error_target_tmp="";
					}
					
					
					//echo $target_time_tmp1."<br>";
					//$formatCode2 = $objPHPExcel_1->getActiveSheet()->getStyle(tmp_val)->getNumberFormat()->getFormatCode();
					//echo $formatCode2."<br>";
					
					$target_time[] = $target_time_tmp;
					
					$tmp_val="O".$row;
					$plant_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					if($plant_tmp==""){
						$error_plant_tmp="<font color=red size=2>Plant Can't Leave Blank in Excel (Col,Row) ".$tmp_val."</font><br>";
					}
					else{
						$error_plant_tmp="";
					}
					$plant[] = $plant_tmp;
					
					$tmp_val="P".$row;
					$chillplant_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					if($chillplant_tmp==""){
						$error_chillingplant_tmp="<font color=red size=2>Chilling PLant Can't Leave Blank in Excel (Col,Row) ".$tmp_val."</font><br>";
					}
					else{
						$error_chillingplant_tmp="";
					}
					$chillplant[] = $chillplant_tmp;
					
					$tmp_val="Q".$row;
					$tankertype_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					if($tankertype_tmp==""){
						$error_tankertype_tmp="<font color=red size=2>Tanker Type Can't Leave Blank in Excel (Col,Row) ".$tmp_val."</font><br>";
					}
					else{
						$error_tankertype_tmp="";
					}
					$tankertype[] = $tankertype_tmp;
					
					$tmp_val="R".$row;
					$transporter_tmp="";
					$transporter_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					/*if($transporter_tmp=='Harit')
					{
						echo "XX".$transporter_tmp."XX";
					}*/
					//$trans_trim=trim($transporter_tmp);
					//echo $trans_trim." /"; 
					$transporter_tmp=trim($transporter_tmp);
					//if($uploadTransporter[$transporter_tmp])
					$Transporter[] = $uploadTransporter[strtolower($transporter_tmp)];
					
					//--------------//
					
					$error_upload[]=$error_disp_tmp.$error_target_tmp.$error_plant_tmp.$error_chillingplant_tmp.$error_tankertype_tmp;
					//print_r($chillplant_tmp);
					break;
					
				}
			}
		}
		unlink($read_excel_path);
	}
	function read_sent_db($tid_p,$sno_p)
	{
		global $LRNO;  global $Vehicle; global $Transporter; global $mobileno; global $emailid; global $drivername; global $drivermobile; global $qty; global $snf_per; global $snf_kg;
		global $fat_kg; global $fat_per; global $milk_age; global $disp_time; global $target_time; global $plant; global $chillplant; global $tankertype;
		global $sno_id;
	
		//------fetching from database----------//
		global $DbConnection;
                
                $data_invoice=getDetailAllInvoiceMdrm($tid_p,$sno_p,$DbConnection);
		//$queryPending = "SELECT * FROM invoice_mdrm WHERE status=1 AND invoice_status=5 AND (vehicle_no is NULL OR vehicle_no='') AND transporter_account_id ='$tid_p' AND sno='$sno_p'";
		//echo $queryPending;
		//$resultPending = mysql_query($queryPending,$DbConnection);
               // while($rowPending = mysql_fetch_object($resultPending))
		foreach($data_invoice as $rowPending)
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
			$sno_id[] = $rowPending['sno_id'];
		}
		 
		//---------------------------------------//

	}
	function read_sent_db_pending($pid)
	{
		global $LRNO;  global $Vehicle; global $Transporter; global $mobileno; global $emailid; global $drivername; global $drivermobile; global $qty; global $snf_per; global $snf_kg;
		global $fat_kg; global $fat_per; global $milk_age; global $disp_time; global $target_time; global $plant; global $chillplant; global $tankertype;
		global $sno_id;
	
		//------fetching from database----------//
		global $DbConnection;
		//$queryPending = "SELECT * FROM invoice_mdrm WHERE status=1 AND invoice_status=5 AND (vehicle_no is NULL OR vehicle_no='') AND parent_account_id='$pid' ";
		//$queryPending = "SELECT * FROM invoice_mdrm WHERE status=1 AND invoice_status=5 AND (vehicle_no is NULL OR vehicle_no='') ";
		
                //$queryPending = "SELECT * FROM invoice_mdrm WHERE status=1 AND invoice_status=5";    
               
		//echo $queryPending;
		//$resultPending = mysql_query($queryPending,$DbConnection);
                $rowPending_data=getDetailAllInvoiceMdrmNext($DbConnection);
		//while($rowPending = mysql_fetch_object($resultPending))
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
				<u>RAW MILK INVOICE "; if($pending==1 || $pending==2){ echo"(<u>Pending Invoice</u>)" ;} echo"</u>
			</th>
		</tr>
		";
		if($pending=="0")
		{
		echo"
		<tr>
			<th>
			";
			if($upload_status=="1")
			{		
				echo"
				<input type=radio id=radio_input name=radio_input value='manual'  onclick=invoice_uploder_div_admin(this.value) >Manual &nbsp;&nbsp; <input type=radio id=radio_input name=radio_input value='excelupload' checked onclick=invoice_uploder_div_admin(this.value)>ExcelUpload
				";
			}
			else
			{
				echo"
				<input type=radio id=radio_input name=radio_input value='manual' checked onclick=invoice_uploder_div_admin(this.value) >Manual &nbsp;&nbsp; <input type=radio id=radio_input name=radio_input value='excelupload'  onclick=invoice_uploder_div_admin(this.value)>ExcelUpload
				";
			}
			
			echo"
			 &nbsp;&nbsp; 	<a href='manage_invoice_milk_add_upload.php?pending=2' class='hs2' target='_blank'><font color=red>Show Pending Invoice
			</th>
		</tr>
		
		<tr>
			<th>
			 <div id=uploader_div style='display:none;' >
				<center><h3>Invoice Excel File Upload:</h3></center>

				<table align=center border=1 width=40% rules=all cellpadding=10 cellpadding=10>
					<tr>
						<td>
							<form enctype='multipart/form-data' action='manage_invoice_milk_add_upload.htm' method='POST'>
								<table>
									<tr>											
										<td>Choose a file to upload<input type='hidden' name='MAX_FILE_SIZE' value='100000' /><input type='hidden' name='upload_status' id='upload_status' value='1' />	</td>
										<td>:</td> 
										<td><input name='uploadedfile' type='file' /></td>
										<td colspan='1' align='center'><input type='submit' value='Upload File' /></td>
									</tr>
									<tr>
										<td colspan=4><a  href='invoice_milk_format_excel.php?download_file=raw_milk_format.xlsx'>Download Invoice Format in Excel( Last Updated: 1/1/2015 )</a></td>
									</tr>
									
								</table>
							</form>
						</td>		
					</tr>
				</table>
			 </div>
			</th>
		</tr>
		";
		}
		echo"
	</table>
	<form name='manage1'>
	
	
	<input type='hidden' id='vehicle_list_hidden' value='".$all_vehicles."'>
	<table width=100%>
		
		<tr>
			<td>
				<div id=div_display_error ></div>
				<div id=div_display_error_upload ></div>
			</td>
		</tr>
		<tr>
			<td>
				<div id=invoice_interface >
				";
					
					//include('manage_add_raw_milk_usertype_interface.php');
					include('manage_add_raw_milk_admintype_interface.php');
					
				
					$upload_status=$_POST['upload_status'];
					//echo $upload_status;
					$timesta= time();
					if($upload_status=="1" && $pending!="1" && $pending!="2" )
					{	
						
						//?heck that we have a file
						if((!empty($_FILES["uploadedfile"])) && ($_FILES['uploadedfile']['error'] == 0)) {		 
							$filename1= basename($_FILES['uploadedfile']['name']);
							$filename=$timesta."_".$filename1;
							//echo "FILE+".$filename;
							$ext = substr($filename, strrpos($filename, '.') + 1);	
							//echo "<br>Ext=".$ext;
							if (($ext == "xlsx" || $ext == "xls") && ($_FILES["uploadedfile"]["size"] < 350000)) {
							//Determine the path to which we want to save this file
							  $newname = dirname(__FILE__).'/upload_rawmilk_invoice/'.$filename;
							  //echo $newname."<br>";
							  //Check if the file with the same name is already exists on the server
							  if (!file_exists($newname)) {
								//echo"<br>".$_FILES['uploadedfile']['tmp_name'];
								//Attempt to move the uploaded file to it's new place
								if ((move_uploaded_file($_FILES['uploadedfile']['tmp_name'],$newname))) {
								   //echo "It's done! The file has been saved as: ".$newname;
								} else {
								   echo "Error: A problem occurred during file upload!";
								}
							  } else {
								 echo "Error: File ".$_FILES["uploaded_file"]["name"]." already exists";
							  }
						  } else {
							 echo "Error: Only Excel  are accepted for upload";
						  }
						} else {
						 echo "Error: No file uploaded";
						}
						
						/// Reading Excel File ///
						if (file_exists($newname)) {
							$read_excel_path =$newname;
						
							define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
                                                        //require_once $_SERVER['DOCUMENT_ROOT']."/itrack/beta/src/php/PHPExcel/IOFactory.php";
							require_once '/var/www/html/vts/beta/src/php/PHPExcel/IOFactory.php';

							$objPHPExcel_1 = null;
							
							$LRNO=array();  $Vehicle = array(); $Transporter=array(); $mobileno=array(); $emailid=array(); $drivername=array(); $drivermobile=array(); $qty=array(); $snf_per=array(); $snf_kg=array();
							$fat_kg=array(); $fat_per=array(); $milk_age=array(); $disp_time=array(); $target_time=array(); $plant=array(); $chillplant=array();$tankertype=array();
							$error_upload=array();
							
							read_sent_file($read_excel_path);
							
							$total_lr=sizeof($LRNO);
							//print_r($chillplant);
							//echo $total_lr;
							$LR_js_array = json_encode($LRNO);
							$Vehicle_js_array = json_encode($Vehicle);
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
							$Transporter_js_array = json_encode($Transporter);
							//echo sizeof($chillplant);
							if(strlen(implode($error_upload)) == 0)							
							{
								echo"<font color=green size=2>File Uploaded Temporary, Please Click Add Now Button for further Validation and Final Submition </font>";
							}
							$error_upload_js_array = json_encode($error_upload);
							
							echo"<script> 
							addfieldTotal($total_lr); 
							var tot_loop=$total_lr;
							
								var LR_Tags=$LR_js_array;
								var Vehicle_Tags=$Vehicle_js_array;
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
								var Transporter_Tags=$Transporter_js_array;
								var error_upload_Tags=$error_upload_js_array;
								
								for(var l=0;l<$total_lr;l++)
								{
									//checking alphanumeric				
										
										document.getElementById('lrno:'+l).value=LR_Tags[l];
										alphanumeric_ucase_upload(LR_Tags[l],'lrno:'+l);
										document.getElementById('vehno:'+l).value=Vehicle_Tags[l];
										invoice_lorry_unique_upload('vehno:'+l,Vehicle_Tags[l]);
										invoice_lorry_unique_pre_upload(LR_Tags[l],'lrno:'+l);
										//alert(Transporter_Tags[l]);
										if(Transporter_Tags[l]==' ' || Transporter_Tags[l]==null  || Transporter_Tags[l]=='' )
										{
											document.getElementById('transporter:'+l).value='select';
										}
										else
										{
											document.getElementById('transporter:'+l).value=Transporter_Tags[l];
										}
										
										document.getElementById('email:'+l).value=emailid_Tags[l];
										document.getElementById('mobile:'+l).value=mobileno_Tags[l];
										document.getElementById('driver:'+l).value=drivername_Tags[l];
										document.getElementById('drivermobile:'+l).value=drivermobile_Tags[l];
										//document.getElementById('fat_kg:'+l).value=fat_kg_Tags[l];
										//document.getElementById('snf_kg:'+l).value=snf_kg_Tags[l];
										
										document.getElementById('qty:'+l).value=qty_Tags[l];
										document.getElementById('fat_per:'+l).value=fat_per_Tags[l];
										put_fat_kg(fat_per_Tags[l],'fat_per:'+l);
										
										document.getElementById('snf_per:'+l).value=snf_per_Tags[l];
										put_snf_kg(snf_per_Tags[l],'snf_per:'+l);
										
										document.getElementById('milk_age:'+l).value=milk_age_Tags[l];
										document.getElementById('disp_time:'+l).value=disp_time_Tags[l];
										document.getElementById('target_time:'+l).value=target_time_Tags[l];
										document.getElementById('plant:'+l).value=plant_Tags[l];
										document.getElementById('chillplant:'+l).value=chillplant_Tags[l];
										document.getElementById('chillplant:'+l).readonly = true;
										document.getElementById('tankertype:'+l).value=tankertype_Tags[l];
										document.getElementById('div_display_error').innerHTML= document.getElementById('div_display_error').innerHTML + error_upload_Tags[l];
									
								}
							</script>";
						}
						
					}
					
					if($pending=="1")
					{
						$LRNO=array();  $Vehicle = array(); $Transporter = array(); $mobileno=array(); $emailid=array(); $drivername=array(); $drivermobile=array(); $qty=array(); $snf_per=array(); $snf_kg=array();
							$fat_kg=array(); $fat_per=array(); $milk_age=array(); $disp_time=array(); $target_time=array(); $plant=array(); $chillplant=array();$tankertype=array();
							$sno_id=array();
							
							read_sent_db($tid_p,$sno_p);
							
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
									document.getElementById('sno_id:'+l).value=sno_id_Tags[l];
									
								}
								
								
							</script>";
					}
					
					if($pending=="2")
					{
						$LRNO=array();  $Vehicle = array(); $Transporter = array(); $mobileno=array(); $emailid=array(); $drivername=array(); $drivermobile=array(); $qty=array(); $snf_per=array(); $snf_kg=array();
							$fat_kg=array(); $fat_per=array(); $milk_age=array(); $disp_time=array(); $target_time=array(); $plant=array(); $chillplant=array();$tankertype=array();
							$sno_id=array();
							
							read_sent_db_pending($account_id);
							
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
									document.getElementById('sno_id:'+l).value=sno_id_Tags[l];
									
								}
								
								
							</script>";
					}
					
					
					
	echo'
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<center><br>
						<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_invoice_raw_milk_from_admin(\'add\')" value="Add Now">
						<br>
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
				<input type="text" id="vehicle_list" name="vehicle_list"  size="30" onKeyUp="getScriptPage_raw_milk_from_admin(this.value,this.id,\'box\')">
				<div id="box2" class="input-div-route" style="display:none"></div>
				</td>
				
			</tr>
			<tr><td colspan="2">
					<input type="button" value="Add" onclick="javascript:close_vehicle_list();">
				</td></tr>
		</table>
		
	</div>
    <input type="hidden" id="tmp_serial"/>
	
	</form>';
	
	function get_user_vehicle($AccountNode,$account_id)
	{
		//echo "hi".$account_id;
		global $vehicleid;
		global $vehicle_cnt;
		global $td_cnt;
		global $DbConnection;
		global $vehicle_list;
		if($AccountNode->data->AccountID==$account_id || $AccountNode->data->AccountTypeThirdParty=='1')
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
		/*	
		$query = "SELECT account_admin_id FROM account_detail WHERE account_id='$account_id_local1'";               
		//echo $query;
		$result=mysql_query($query,$DbConnection);
		$row=mysql_fetch_row($result);
		$admin_id=$row[0];
                */
                $row=  getAcccountAdminIdAdminId($account_id_local1,$DbConnection);
                $admin_id=$row[0];
		/*	
		$query1 = "SELECT account_id FROM account_detail WHERE admin_id='$admin_id'";
		//echo "<br>".$query;	
		$result=mysql_query($query1,$DbConnection);
		$row1=mysql_fetch_row($result);
		$function_account_id=$row1[0];
                */
                 $row1=getAccountIdByAdminId($admin_id,$DbConnection);
                $function_account_id=$row1;
		//echo "account_id=".$function_account_id.'<br>';
			
		/*$queryType="SELECT user_type from account WHERE account_id='$function_account_id'";
		//echo "<br>".$queryType;
		$resultType=mysql_query($queryType,$DbConnection);
		$rowType=mysql_fetch_row($resultType);
		$function_account_type=$rowType[0];*/
		//echo "userType=".$function_account_type."<br>";
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
