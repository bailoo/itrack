<?php
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION['root'];
global $vehicleid;
$vehicleid=array();
global $vehicle_cnt; 
$vehicle_cnt=0;
global $td_cnt;
$td_cnt =0;
$qSV="SELECT * FROM secondary_vehicle WHERE create_id=$account_id AND shift='$shitTime' AND status=1";  /// s->secondary , v->vehicle
$rSV=mysql_query($qSV,$DbConnection);  // r->result , s->secondary , v->vehicle
global $sVArr;  /// s->secondary , v->vehicle
global $sVSize;
$sVArr=array();
while($rowSV=mysql_fetch_object($rSV))
{
	$sVArr[]=$rowSV->vehicle_id;
}
$sVSize=sizeof($sVArr);
//echo "q=".$account_id."<br>";
echo"shitVehicleBlock##";
echo'<table class="menu">
			<tr>
				<td height="10px" align="center" >
				&nbsp;<input type="checkbox" name="all" value="1" onClick="javascript:selectAllCheckboxElement(this.form,\'secondaryVehicles[]\');">
				&nbsp;&nbsp;Select All
			</td>
		</tr>
	</table>'; 
echo"<div style='height:400px;overflow:auto'>

<table class='menu' ><tr>";
PrintAllVehicle($root, $account_id);
echo "</table></div>";	

function PrintAllVehicle($root, $local_account_id)
{
	global $vehicleid;
	global $vehicle_cnt;
	global $td_cnt;
	global $pageAction;
	global $sVArr;  /// s->secondary , v->vehicle
	global $sVSize;
	$type = 0;
	//echo "accountId=".$root->data->AccountID."accountId1=".$local_account_id;
	if($root->data->AccountID==$local_account_id)
	{
		
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
										
					$assignFlag=0;
					for($ci=0;$ci<$sVSize;$ci++)
					{
						if($sVArr[$ci]==$vehicle_id)
						{
							$assignFlag=1;
							break;
						}
					}
					if($assignFlag==0)
					{
					$td_cnt++;
					echo "
							<td>
								<input type='checkbox' name='secondaryVehicles[]' value=".$vehicle_id.">
							</td>
							<td>
								".$vehicle_name."
							</td>
						";
						if($td_cnt%7==0)
						{
							$td_cnt=0;
							echo"</tr><tr>";
						}
					}
				  //$td_cnt++;
				//common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName); 											
				}
			}
		}
	}
	$ChildCount=$root->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
	 PrintAllVehicle($root->child[$i],$local_account_id);
	}
}
?>