<?php
	include_once('util_session_variable.php');  	   //util_session_variable.php sets values in session
	include_once('util_php_mysql_connectivity.php');   //util_php_mysql_connectivity.php make set connection of user to database  
	$post_group_id = trim($_REQUEST['group_id']);
	$post_user_id =trim($_REQUEST['user_id']);
	$post_password=md5(trim($_REQUEST['password']));
	$post_vehicle_name =trim($_REQUEST['vehicle_name']);
	$post_from_place=trim($_REQUEST['from_place']);
	$post_to_place =trim($_REQUEST['to_place']);
	$post_consignee_name =trim($_REQUEST['consignee_name']);
	$post_start_date =trim($_REQUEST['start_date']);
	$post_end_date =trim($_REQUEST['end_date']);
	
	/*$post_group_id="";
	$post_user_id='demo';
	$post_password=md5('demo');
	$post_vehicle_name='Monthly_distance';
	$post_from_place='Rajesthan';
	$post_to_place='Kanpur';
	$post_consignee_name="tracker group ltd";
	$post_start_date='2013-06-09 10:10:10';
	$post_end_date='2013-06-11 23:10:10';*/
	
	$QueryDetail="SELECT account.account_id,account_detail.name FROM account INNER JOIN account_detail ON account_detail.".
			"account_id=account.account_id AND account.user_id='$post_user_id' AND account.group_id='$post_group_id'".
			" AND status=1";	
	//echo "query=".$QueryDetail."<br>";
	$ResultAccDetail=mysql_query($QueryDetail,$DbConnection);
	$RowAccDetail=mysql_fetch_row($ResultAccDetail);
	$account_id_local=$RowAccDetail[0];
	$account_id_name=$RowAccDetail[1];
	//echo "account_id_local=".$account_id_local." account_id_name=".$account_id_name."<br>";
	
	$QueryIMEI="SELECT vehicle_assignment.device_imei_no FROM vehicle_assignment INNER JOIN vehicle ON vehicle.vehicle_id".
			"=vehicle_assignment.vehicle_id AND vehicle.vehicle_name='$post_vehicle_name' AND vehicle.status=1 AND".
			" vehicle_assignment.status=1";
	//echo "query=".$QueryIMEI."<br>";
	$ResultIMEI=mysql_query($QueryIMEI,$DbConnection);
	$RowIMEI=mysql_fetch_row($ResultIMEI);
	$imei_no=$RowIMEI[0];
	
	$QueryInfo="SELECT * FROM consignment_info where device_imei_no='$imei_no' AND ((start_date BETWEEN".
			"'$post_start_date' AND '$post_end_date') OR (end_date BETWEEN '$post_start_date' AND '$post_end_date'))".
			" AND status=1";
    //echo "QueryInfo=". $QueryInfo."<br>";
	$ResultInfo=mysql_query($QueryInfo,$DbConnection);
	$NumRowsinfo=mysql_num_rows($ResultInfo);
	
	if($NumRowsinfo>0)
	{	
	$message = "<center>
					<br>
					<FONT color=\"green\">
						 <strong>A Docket Number Already Assign To This Vehicle During This Date Time</strong>
				   </font>
			   </center>";
		echo $message;
	} 
	else
	{		
		$query1="SELECT MAX(docket_no) as max_count from consignment_info where account_id=$account_id_local";
		//echo"query=".$query1."<br>";
		if($DEBUG) print_query($query1);
		$result1=mysql_query($query1,$DbConnection);
		$max_no = mysql_fetch_object($result1);
		$max_no1=$max_no->max_count;
		$max_no1 = preg_replace("/[^0-9]/", '', $max_no1);          
		//echo "mas_no1=".$max_no1."<br>";

		if($max_no1=="")
		{
				$dock_no=$account_id_name."/0001";
		}
		else
		{ 
				$max_no1=$max_no1+1; 
				//echo "mas_no1=".$max_no1."<br>";                    
				if($max_no1<9)

				{$dock_str="000";}
				else if($max_no1>=10 && $max_no1<=99)
				{$dock_str="00";}
				else if($max_no1>=100 && $max_no1<=999)
				{$dock_str="0";}
				else
				{$dock_str="";}       
				$dock_no =$account_id_name."/".$dock_str.$max_no1;            
		}
		$Query="INSERT INTO `consignment_info`(account_id,device_imei_no,vehicle_name,from_place,to_place,".
				"consignee_name,start_date,end_date,docket_no,create_id,create_date,status,remark) VALUES('".
				"$account_id_local','$imei_no','$post_vehicle_name','$post_from_place',".
				"'$post_to_place','$post_consignee_name','$post_start_date','$post_end_date','$dock_no','1','$date',1,".
				"'$remark')";  
		//echo "Query=".$Query."<br>";
		if($DEBUG) print_query($Query);
		$Result=mysql_query($Query,$DbConnection);
	
		if($Result)
		{
				$message = "<center>
							<br>
								<FONT color=\"green\">
									 <strong>Your Docket Number is : </strong>
							   </font>
								<font color='red'>
								   <strong> ".$dock_no."</strong>
								 </font></center>";
				echo $message;
				
		}   
		else
		{
			$message = "<center><br>
							<FONT color=\"red\">
								<strong>Sorry! Unable to process request.</strong></font>
						 </center>";
			echo $message;		   
		}      
	}	
?>