<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
  
	if($ActionType=="add") 
	{ 
		$Query="SELECT consignment_code FROM consignment WHERE consignment_code='$pd->data[consignment_code]' AND create_id=$account_id".
				" AND status=1";
		$QueryResult=mysql_query($Query,$DbConnection);
		$ResultNumRow=mysql_num_rows($QueryResult);
		if($ResultNumRow>0)
		{
			$msg = "Consignment Code Already Exist Please Enter Another Consignment Code.";
			$msg_color = "red";
		}
		else
		{
			$Query="SELECT MAX(serial_no) FROM consignment";
			$QueryResult=mysql_query($Query,$DbConnection);
			$ResultRow=mysql_fetch_row($QueryResult);
			$SerialNo=$ResultRow[0];
			if($SerialNo=="")
			{
				$ConsignmentId="CSMT_1";
			}
			else
			{
				$ConsignmentId="CSMT_".$SerialNo;
			}
			$InsertQuery1="INSERT INTO consignment(consignment_id,consignment_code,consignment_name,pickup_type,pickup_point_id,".
						"pickup_date_time,delivery_type,delivery_point_id,delivery_date_time,distance,create_id,create_date,status) VALUES".
						"('$ConsignmentId','$ConsignmentCode','$ConsignmentName','$PickupType','$PickupPointId','$PickupDateTime',".
						"'$DeliveryType','$DeliveryPointId','$DeliveryDateTime','$Distance','$account_id','$date','1')";
			//echo "Query1=".$InsertQuery1."<br>";
			$InsertResult1=mysql_query($InsertQuery1,$DbConnection);
			$InsertQuery2="INSERT INTO consignment_assignment(consignment_id,vehicle_id,create_id,create_date,status) VALUES".
						  "('$ConsignmentId','$MotoVehicleId','$account_id','$date','1')";
			$InsertResult2=mysql_query($InsertQuery2,$DbConnection);
			if($InsertResult1 && $InsertResult2)
			{
				$msg = "Consignment Add And Assign Successfully.";
				$msg_color = "green";
			}
		}
	}    
  echo "<center>
			<br>		
			<FONT color=\"".$msg_color."\" size=\"2\">
				".$msg."				
			</FONT>
			<br>
		</center>";
	echo'<center>
			<a href="javascript:show_option(\'manage\',\'consignment\');" class="back_css">
				&nbsp;<b>Back</b>
			</a>
		</center>';   
?>
        
