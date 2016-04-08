<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$root=$_SESSION['root'];  
	$tmp=$root->data->AccountName;
	$DEBUG = 0;    
	$vehicle_id1 = $_POST['vehicle_id'];
	//echo "vehicle_id=".$vehicle_id1."account_name=".$tmp;
	echo "ajax_vehicle_information##";

	$group=array(array());
	$group_cnt;
	
	$vehicle=array();
	$vehicle_cnt;
	//$vehicle_cnt;
	$ColumnNo;
	$RowNo;
	$count;
	$MaxColumnNo;

	if($vehicle_id1!=null)     
	{
	$group_cnt=0;
	echo "<table border=1 cellspacing=0 cellpadding=0 class='module_left_menu' align='center' width='70%'>";			
		GetVehicle($root,$vehicle_id1); 
	echo"</table>";

	}	
	function GetVehicle($AccountNode,$vehicle_id1)
	{
		global $vehicle;
		global $vehicle_cnt;
		
		$group_id = $AccountNode->data->AccountGroupID;
		$group_name = $AccountNode->data->AccountGroupName;
		$account_id = $AccountNode->data->AccountID;
		$account_name = $AccountNode->data->AccountName;

		for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
		{
			$vehicle_id = $AccountNode->data->VehicleID[$j];			
			$vehicle_name = $AccountNode->data->VehicleName[$j];
					
			for($i=0;$i<$vehicle_cnt;$i++)
			{
				if($vehicle[$i]==$vehicle_id)
				{
					break;
				}
			}			
			if($i>=$vehicle_cnt)
			{
				$vehicle[$vehicle_cnt]=$vehicle_id;
				$vehicle_cnt++;
				if($vehicle_id1==$vehicle_id)
				{
				echo'<tr>';
						if($group_name!=null)
						{
							echo'<td>&nbsp;'.$group_name.'</td>';
						}					
						echo'<td>&nbsp;'.$account_name.'</td>
					</tr>';
				}
			}
		}

		$ChildCount=$AccountNode->ChildCnt;

		for($i=0;$i<$ChildCount;$i++)
		{ 
			GetVehicle($AccountNode->child[$i],$vehicle_id1);
		}    
	}
?>
