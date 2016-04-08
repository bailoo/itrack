<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	
	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
	require_once 'PHPExcel/IOFactory.php';
	$objPHPExcel_1 = null;
	
	$DEBUG=0;
	set_time_limit(18000);
	
	$action_type1 = $_POST['action_type'];
	$local_account_id = $_POST['local_account_id'];
	//$account_size=sizeof($local_account_ids);
	//$type = $_POST['file_type'];
	//echo "local_account_ids=".$local_account_id." ,action_type1=".$action_type1;

	$query_userid = "SELECT user_id FROM account WHERE account_id='$local_account_id' AND status=1";
	$result_userid = mysql_query($query_userid, $DbConnection);
	if($row_userid = mysql_fetch_object($result_userid))
	{
		$user_id = $row_userid->user_id;
	}
	
	//TYPE =0 : CUSTOMER
	//TYPE =1 : PLANT
	//echo  "<br>LOCAL AC=".$local_account_ids;

	$old_value= Array();		$new_value=Array();			$field_name=Array();		$table_name="station"; 

	global $VehicleNo;			//SENT FILE
	global $CustomerCode;
	global $CustomerName;
	global $InvoiceNo;
	global $InvoiceAmount;
	global $EmailID;
	global $Remarks;
	global $DriverName;
	global $DriverPhoneNo;
	global $TargetTime;

	$VehicleNo = Array();			//SENT FILE
	$CustomerCode = Array();
	$CustomerName = Array();
	$InvoiceNo = Array();
	$InvoiceAmount = Array();
	$EmailID = Array();
	$Remarks = Array();
	$DriverName = Array();
	$DriverPhoneNo = Array();
	$TargetTime = Array();		
	
	/*
	function check_availability($customer,$name)
	{
		global $type;
		//echo "<br>CHECK";
		global $DbConnection;
		global $account_id;
		//   $query = "SELECT customer_no FROM station WHERE customer_no='$customer' AND station_name='$name' AND type='$type' AND status=1";
		$query = "SELECT customer_no FROM station WHERE customer_no='$customer' AND type='$type' AND user_account_id='$account_id' AND status=1";
		//echo "<br>Q=".$query;
		$result = mysql_query($query,$DbConnection);
		$numrows = mysql_num_rows($result);
		//	echo "numrows=".$numrows;
		return $numrows;   
	} 
	*/
	  
	if($action_type1=="add") 
	{ 
		//echo "Uploaded successfully";
		$target_path = "invoice_data/";
		
		$target_path = $target_path . basename( $_FILES['file']['name']); 
		
		//move_uploaded_file($_FILES['file']['tmp_name'], $target_path);
		
		if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) 
		{
			//echo "The file ".  basename( $_FILES['file']['name']). " has been uploaded";
		} else
		{
			//echo "There was an error uploading the file, please try again!";
		}		
		//***************READ XLS FILE RECORD ***********************
		/////////////////////////////////////////////////////////////     

		//$path = "/var/www/html/vts/beta/src/php/upload/".$_FILES['file']['name'];
		$read_excel_path = "/var/www/html/vts/beta/src/php/invoice_data/".$_FILES['file']['name'];
		//$read_excel_path = "C:\\xampp/htdocs/beta/src/php/invoice_data/".$_FILES['file']['name'];
		read_uploaded_file($read_excel_path);
    
		//***********************************************************
		//######### INSERT INTO DATABASE
		
		for($i=0;$i<sizeof($VehicleNo);$i++)
		{
			/*echo "VehicleNo=".$VehicleNo[$i];
			echo ", CustomerCode=".$CustomerCode[$i];
			echo ", CustomerName=".$CustomerName[$i];
			echo ", InvoiceNo=".$InvoiceNo[$i];
			echo ", InvoiceAmount=".$InvoiceAmount[$i];
			echo ", EmailID=".$EmailID[$i];
			echo ", Remarks=".$Remarks[$i];
			echo ", DriverName=".$DriverName[$i];
			echo ", DriverPhoneNo=".$DriverPhoneNo[$i];
			echo ", TargetTime=".$TargetTime[$i]."<br>";*/
					
			//1. ######### GENERATE DOCKET NUMBER ##################
			//###################################################
			$query1="SELECT MAX(docket_no) as max_count from consignment_info where account_id='$local_account_id'";
			//echo"query=".$query1."<br>";
			if($DEBUG) print_query($query1);
			$result1=mysql_query($query1,$DbConnection);
			$max_no = mysql_fetch_object($result1);
			$max_no1=$max_no->max_count;
			$max_no1 = preg_replace("/[^0-9]/", '', $max_no1);          
			//echo "<br>max_no1=".$max_no1."<br>";
			$dock_no = "";
			$dock_str = "";
			
			if($max_no1=="")
			{
				$dock_no = $user_id."/0001";
			}
			else
			{ 
				$max_no1=$max_no1+1; 
				//echo "mas_no1=".$max_no1."<br>";                    
				if($max_no1<=9)
				{$dock_str="000";}
				else if($max_no1>=10 && $max_no1<=99)
				{$dock_str="00";}
				else if($max_no1>=100 && $max_no1<=999)
				{$dock_str="0";}
				else
				{$dock_str="";}       
				$dock_no = $user_id."/".$dock_str.$max_no1;            
			}
			$from_place = "-";
			$to_place = "-";
			$consignee_name = $CustomerName[$i];
			$start_date = $date;
			$end_date = date('Y-m-d H:i:s', strtotime($start_date .' +7 day'));
			
			$query1 = "SELECT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle WHERE vehicle.vehicle_name='$VehicleNo[$i]' AND vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle.status=1 AND vehicle_assignment.status=1";
			$result1 = mysql_query($query1, $DbConnection);
			if($row1 = mysql_fetch_object($result1))
			{
				$vehicle_imei = $row1->device_imei_no;
			}
			$QueryConsignment ="INSERT INTO `consignment_info`(account_id,device_imei_no,vehicle_name,from_place,to_place,".
					"consignee_name,start_date,end_date,docket_no,create_id,create_date,email_id,status,remark) VALUES('".
					"$local_account_id','$vehicle_imei','$VehicleNo[$i]','$from_place',".
					"'$to_place','$consignee_name','$start_date','$end_date','$dock_no','$account_id','$date','$EmailID[$i]',1,".
					"'$Remarks[$i]')";  
			//echo "Query=".$QueryConsignment."<br>";
			if($DEBUG) print_query($QueryConsignment);
			$Result1=mysql_query($QueryConsignment,$DbConnection);			
			//# DOCKET NUMBER GENERATED ###########

			//2.######## INSERT INTO INVOICE DATABASE
			$QueryInvoice = "INSERT INTO `invoice`(vehicle_no,customer_code,customer_name,invoice_no,invoice_amount,email_id,".
					"remarks,driver_name,driver_phone_no,target_time,account_id,tracking_no,status) VALUES('".
					"$VehicleNo[$i]','$CustomerCode[$i]','$CustomerName[$i]','$InvoiceNo[$i]',".
					"'$InvoiceAmount[$i]','$EmailID[$i]','$Remarks[$i]','$DriverName[$i]','$DriverPhoneNo[$i]','$TargetTime[$i]','$account_id','$dock_no',1)";
			$Result2=mysql_query($QueryInvoice,$DbConnection);
			//########## INSERTION COMPLETED*/
		}
		$flag = 1;
		$action_perform="Added";				
		/*$query=$query_string1.$query_string2; 
		//echo "<br>".$query;  		
	
		if($DEBUG ==1 )print_query($query);     
		$result=mysql_query($query,$DbConnection);          	  
		if($result){$flag=1;$action_perform="Added";}*/			
	}
  
	if($action_type1=="edit")
	{
		$invoice_serials1 = $_POST['invoice_serials'];
		//$invoice_arr = explode(',',$invoice_serials1);
		
		$query_tracking_no = "SELECT tracking_no FROM invoice WHERE sno IN($invoice_serials1)";
		$result_tracking_no = mysql_query($query_tracking_no,$DbConnection);
		$tracking_no = "";
		while($row_tracking_no = mysql_fetch_object($result_tracking_no))
		{
			$tracking_no.= "'".trim($row_tracking_no->tracking_no)."',";
		}
		$tracking_no = substr($tracking_no, 0, -1);
		$tracking_no = trim($tracking_no);
		
		$query1 = "UPDATE invoice SET status=0 WHERE sno IN($invoice_serials1)";
		//echo $query;
		$result1 = mysql_query($query1,$DbConnection);
				
		$query2 = "UPDATE consignment_info SET status=0 WHERE docket_no IN($tracking_no)";
		//echo $query2;
		$result2 = mysql_query($query2,$DbConnection);
		
		$flag = 2;
		$action_perform="Closed";
	}
 
	if($flag==1 || $flag==2)
	{
		$msg = "Invoice ".$action_perform." Successfully";
		$msg_color = "green";				
	}
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  
	echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
	if($flag==1)
	{
		echo'<center><a href="#" onclick="javascript:window.close();" class="back_css">&nbsp;<b>Close this window</b></a></center>';                 
	}
	else if($flag==2)
	{
		echo'<center><a href="#" onclick="javascript:manage_show_file(\'src/php/manage_invoice.htm\');" class="back_css">&nbsp;<b>Back</b></a></center>';                 
	}
  
function read_uploaded_file($read_excel_path)
{
	global $VehicleNo;			//UPLOADED FILE
	global $CustomerCode;
	global $CustomerName;
	global $InvoiceNo;
	global $InvoiceAmount;
	global $EmailID;
	global $Remarks;
	global $DriverName;
	global $DriverPhoneNo;
	global $TargetTime;		
	
	//echo "<br>READ_SENT_FILE";
	//echo "\nPath=".$path;
	//######### SENT FILE NAME CLOSED 			
	$objPHPExcel_1 = null;
	$objPHPExcel_1 = PHPExcel_IOFactory::load($read_excel_path);	

	$cellIterator = null;
	$column = null;
	$row = null;

	//################ FIRST TAB ############################################
	$read_completed = false;
	$read_red = false;
	foreach ($objPHPExcel_1->setActiveSheetIndex(0)->getRowIterator() as $row) 
	{
		$cellIterator = $row->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells(false);
		$i=0;
		foreach ($cellIterator as $cell) 
		{
			//if (!is_null($cell)) 
			//{
				$column = $cell->getColumn();
				$row = $cell->getRow();										
				
				if($row>1)
				{
					//echo "\nRecord:".$row;
					$tmp_val="A".$row;
					$vehicle_tmp = $objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
															
					if($vehicle_tmp=="")
					{
						$read_completed = true;
						break;
					}
					$VehicleNo[] = $vehicle_tmp;
					//echo "\nRow=".$row." ,Val=".$objPHPExcel_1->getActiveSheet()->getCell($tmp_val)->getValue();
					
					$tmp_val="B".$row;
					$CustomerCode[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="C".$row;
					$CustomerName[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="D".$row;
					$InvoiceNo[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="E".$row;
					$InvoiceAmount[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="F".$row;
					$EmailID[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="G".$row;
					$Remarks[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="H".$row;
					$DriverName[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					
					$tmp_val="I".$row;
					$DriverPhoneNo[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();

					$tmp_val="J".$row;
					//$TargetTime[] = $objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getValue();
					$TargetTime[] = PHPExcel_Style_NumberFormat::toFormattedString($objPHPExcel_1->getActiveSheet(0)->getCell($tmp_val)->getCalculatedValue(), 'YYYY-mm-dd hh:mm:ss');					

					//echo "\nRow=".$row." read";
					break;
				}				
			//}		
		}
		if($read_completed)
		{
			break;
		}
	}	
	//#################################################################
}  
  
?>
        
