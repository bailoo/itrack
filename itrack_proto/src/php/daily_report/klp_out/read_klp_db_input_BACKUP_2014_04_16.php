<?php
$DBASE = "iespl_vts_beta";
$HOST = "localhost";
$USER = "root";
$PASSWD = "mysql";
$account_id = "715";
//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                    "vehicle_grouping.account_id='$account_id' AND vehicle.status=1 AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";					
				
$result_assignment = mysql_query($query_assignment,$DbConnection);

while($row_assignment = mysql_fetch_object($result_assignment))
{
	$vehicle_name_db[] = $row_assignment->vehicle_name;
}

//######## GET LOCATION
$query_location = "SELECT location_id,location_name FROM location WHERE create_id=715 AND status=1";			
$result_location = mysql_query($query_location,$DbConnection);

while($row_location = mysql_fetch_object($result_location))
{
	$location_id_db[] = $row_location->location_id;
	$location_name_db[] = $row_location->location_name;
}

//$query = "SELECT * FROM icd_input WHERE account_id='$account_id' AND icd_in_datetime='0000-00-00 00:00:00' AND status=1";
/*$query = "SELECT DISTINCT vehicle_name,icd_code,factory_code,factory_ea_datetime,factory_ed_datetime,icd_in_datetime,account_id,".
			"create_date,edit_date,status,remark,MAX(icd_out_datetime) as icd_out FROM icd_input ".
			"WHERE account_id='$account_id' GROUP BY vehicle_name";*/

$query_vehicle = "SELECT DISTINCT vehicle_name FROM icd_input WHERE account_id=715";
$result_vehicle = mysql_query($query_vehicle, $DbConnection);

while($row_vehicle = mysql_fetch_object($result_vehicle))
{
	$vehicle_unique = $row_vehicle->vehicle_name;
	
	$query_icd = "SELECT vehicle_name,container_no,icd_out_datetime,icd_code,icd_in_datetime,factory_ea_datetime,".
		"factory_ed_datetime,factory_code,remark FROM icd_input ".
		"WHERE vehicle_name = '$vehicle_unique' AND icd_out_datetime = ".
		"(SELECT MAX(icd_out_datetime) from icd_input where vehicle_name='$vehicle_unique')
		GROUP BY vehicle_name, icd_out_datetime";	
	//echo "\n".$query_icd;
	//}			
	/*$query = "SELECT vehicle_name,container_no,icd_out_datetime,icd_code,factory_code,factory_ea_datetime,factory_ed_datetime,icd_in_datetime,account_id,".
				"create_date,edit_date,status,remark FROM icd_input ".
				"WHERE account_id='$account_id' GROUP BY vehicle_name having MAX(icd_out_datetime)";*/
	//$query = "SELECT , course_id, MAX(icd_out_datetime,) as icd_out from tbl where username='user1' group by course_id;

	//echo "\n".$query;
	$result = mysql_query($query_icd,$DbConnection);

	while($row=mysql_fetch_object($result))
	{
		//if($row->icd_in_datetime=="0000-00-00 00:00:00")
		//
		//if($row->vehicle_name=='UP78CN7920' || $row->vehicle_name=='UP78CN7140' || $row->vehicle_name=='UP78CN7931' || $row->vehicle_name=='UP78CN8183' || $row->vehicle_name=='HR55R8192')
		//if($row->vehicle_name=='HR55R8192')
		{
			//echo "\nFOUND DATA";
			$icd_out_datetime_tmp = explode(' ',$row->icd_out_datetime);	
			$factory_ea_datetime_tmp = explode(' ',$row->factory_ea_datetime);
			$factory_ed_datetime_tmp = explode(' ',$row->factory_ed_datetime);
			if($row->icd_in_datetime!='0000-00-00 00:00:00')
			{
				$icd_in_datetime_tmp = explode(' ',$row->icd_in_datetime);
			}
			else
			{
				$icd_in_datetime_tmp[0]="";
				$icd_in_datetime_tmp[1]="";
			}		
			
			//$factory_code_str = $row->factory_code;
			//factory_arr = explode(',',$factory_code_str);
			
			//for($i=0;$i<sizeof($factory_arr);$i++)
			//{
				/*$row->account_id;
				$row->create_date;
				$row->edit_date;
				$row->status;*/		
				
				for($i=0;$i<sizeof($vehicle_name_db);$i++)
				{							
					if(trim($row->vehicle_name) == trim($vehicle_name_db[$i]))
					{
						$vehicle_name[] = $row->vehicle_name;
						$container_no[] = $row->container_no;					
						//$icd_code[] = $row->icd_code;
						$icd_outdate[] = $icd_out_datetime_tmp[0];
						$icd_outtime[] = $icd_out_datetime_tmp[1];
						$icd_indate[] = $icd_in_datetime_tmp[0];
						$icd_intime[] = $icd_in_datetime_tmp[1];										
						//$factory_code[] = $row->factory_code;
						$factory_expected_arrival_date[] = $factory_ea_datetime_tmp[0];
						$factory_expected_arrival_time[] = $factory_ea_datetime_tmp[1];
						$factory_expected_departure_date[] = $factory_ed_datetime_tmp[0];
						$factory_expected_departure_time[] = $factory_ed_datetime_tmp[1];	
						$remark[] = $row->remark;
																
						$factory_string_tmp = explode(',',$row->factory_code);
						$customer_code[] = $factory_string_tmp[0];
						$factory_code[] = $factory_string_tmp[1];
						$customer_name_tmp = "";
						//echo "\nSizeFC=".sizeof($factory_code_db);
						for($k=0;$k<sizeof($factory_code_db);$k++)
						{					
							//echo "\nF1=".$factory_string_tmp[0]." ,F2=".$factory_code_db[$k];
							if(trim($factory_string_tmp[0])==trim($factory_code_db[$k]))
							{						
								//echo "\nMATCH";
								$customer_name_tmp = $factory_name_db[$k];
								break;
							}
						}
						//$container_no[] = "";
						$customer_name[] = $customer_name_tmp;
						//$factory_code[] = $factory_string_tmp[1];
						
						for($i=0;$i<sizeof($location_id_db);$i++)
						{						
							if(trim($factory_string_tmp[1]) == trim($location_id_db[$i]))
							{
								$factory_name[] = $location_name_db[$i];	//FROM LOCATION TABLE
								break;
							}
						}
						break;
					}
				}
			//}
		}
	}
}

?>
