<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root = $_SESSION['root'];
	$DEBUG=0; 
	$account_id_local=explode(",",$account_id_local);
        $vehicle_imei_name=explode("@",$vehicle_imei_name);
        
	if($action_type =="add")                     ///////// ADD
	{
		/*$query_validate="SELECT * FROM consignment_info where device_imei_no='$vehicle_imei_name[0]' AND ((start_date BETWEEN".
				"'$start_date' AND '$end_date') OR (end_date BETWEEN '$start_date' AND '$end_date')) AND status=1";
	   // echo "query=". $query_validate."<br>";
		$result_validate=mysql_query($query_validate,$DbConnection);
		$num_rows=mysql_num_rows($result_validate);
		if($num_rows>0)
		{
			 $message = "<center>
								<br>
						<FONT color=\"green\">
							 <strong>A Docket Number Already Assign To This Vehicle During This Date Time</strong>
					   </font>
						<font color='red'>
						   <strong> ".$dock_no."</strong>
						 </font></center>";
		}   
		else                 
		{*/
			$query1="SELECT MAX(docket_no) as max_count from consignment_info where account_id=$account_id_local[0]";
			//echo"query=".$query1."<br>";
			if($DEBUG) print_query($query1);
			$result1=mysql_query($query1,$DbConnection);
			$max_no = mysql_fetch_object($result1);
			$max_no1=$max_no->max_count;
			$max_no1 = preg_replace("/[^0-9]/", '', $max_no1);          
			//echo "mas_no1=".$max_no1."<br>";

			if($max_no1=="")
			{
				$dock_no=$account_id_local[1]."/0001";
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
				$dock_no =$account_id_local[1]."/".$dock_str.$max_no1;            
			}
			$Query="INSERT INTO `consignment_info`(account_id,device_imei_no,vehicle_name,from_place,to_place,".
					"consignee_name,start_date,end_date,docket_no,create_id,create_date,status,remark) VALUES('".
					"$account_id_local[0]','$vehicle_imei_name[0]','$vehicle_imei_name[1]','$from_place',".
					"'$to_place','$consignee_name','$start_date','$end_date','$dock_no','$account_id','$date',1,".
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
					
			}   
			else
			{
				$message = "<center><br>
								<FONT color=\"red\">
									<strong>Sorry! Unable to process request.</strong></font>
							 </center>";
			   
			} 	 
		//}
	}
	
	echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
			<tr>
				<td colspan="3" align="center"><b>'.$message.'</b></td>    
			</tr>
		</table><br>'; 
	echo'<center><a href="javascript:show_option(\'manage\',\'consignment_info\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
  //include_once("manage_action_message.php");
?>
        