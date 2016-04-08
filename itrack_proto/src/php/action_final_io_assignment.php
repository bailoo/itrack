<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	
	$list_fname=getIoAssignmentFnameList($DbConnection);
	//echo "listfaname=".$list_fname."<br>";

	$DEBUG=0;	
	$vehicle_ids = $_POST['vehicle_ids'];
	$vehicle_ids_1=explode(",",$vehicle_ids);
	$veh_size=sizeof($vehicle_ids_1);
	$types = $_POST['types'];
	
	//echo "vehicle_ids=".$vehicle_ids."<br>types=".$types;
	$query_string="";
	for($i=0;$i<($veh_size-1);$i++)
	{
		//echo"vehicle_id=".$vehicle_ids_1[$i];
		$types_1=explode("#",$types);
		$type_size=sizeof($types_1);		
		$types_2=explode(":",$types_1[$i]);
		$io_size=sizeof($types_2);
		for($j=0;$j<($io_size-1);$j++)
		{
			$tmp_field = explode(',',$types_2[$j]);
			//echo"field=".$tmp_field[0]."<br>value=".$tmp_field[1]."<BR>";		
			if($j==($io_size-2))
			{
				$io_field_name=$io_field_name.$tmp_field[0];
				/*if($tmp_field[1]=="select")
				{
					$tmp_field[1]=0;
				}*/
				$io_field_value=$io_field_value."'".$tmp_field[1]."'";
			}
			else
			{
				$io_field_name=$io_field_name.$tmp_field[0].",";
				/*if($tmp_field[1]=="select")
				{
					$tmp_field[1]=0;
				}*/
				$io_field_value=$io_field_value."'".$tmp_field[1]."'".",";		
			}
			if($fi==(sizeof($tmp_post_account_feature1)-2))
			{
				$query_string=$query_string.$tmp_field[0]."=".$tmp_field[1];
			}
			else
			{
				$query_string=$query_string.$tmp_field[0]."=".$tmp_field[1].",";
			} 
		}		
		$num_rows=getIoAssignmentNumRow($list_fname,$vehicle_ids_1[$i],$DbConnection);
		if($num_rows>0)
		{
			$result=updateIoAssignmentStr($query_string,$vehicle_ids_1[$i],$DbConnection);				
		}
		else
		{				
			$result=insertIoAssignmentStr($io_field_name,$io_field_value,$vehicle_ids_1[$i],1,$DbConnection);
			//echo "query1=".$query."<br>";				
		}
		
		$io_field_name="";
		$io_field_value="";
		$query_string="";		
	}	
	if($result)
	{
		$message = "<center><br><br><FONT color=\"green\"><strong>Action Performed Successfully!</strong></font></center>";
	}
	else
	{
		$message="<center><br><br><FONT color=\"red\"><strong>Unable to Performed This Action</strong></font></center>";
	}

	echo'<br><table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
			<tr>
				<td colspan="3" align="center"><b>'.$message.'</b></td>    
			</tr>
		</table><br>'; 
	echo'<center><a href="javascript:show_option(\'manage\',\'io_assignment\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
  //include_once("manage_action_message.php");
?>
        