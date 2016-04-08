<?php
set_time_limit(18000);
//include_once("../database_ip.php");
$DBASE = "iespl_vts_beta";
$HOST = "localhost";
$USER = "root";
$PASSWD = "mysql";
$account_id = "715";
if($account_id == "715") $user_name = "klp_out";
//if($account_id == "231") $user_name = "delhi@";
echo "\nDBASE=".$DBASE." ,USER=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

// Pull in the NuSOAP code
require_once('lib/nusoap.php');
// Create the client instance
$client = new nusoap_client('http://27.251.75.182/ElogisolWebService/Service.asmx?wsdl', true);
// Check for an error
$err = $client->getError();
if ($err) 
{
    // Display the error
    echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
    // At this point, you know the call that follows will fail
}
// Call the SOAP method
//$date1="12/03/2014";
//$date2="13/03/2014";

$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));

$parts1 = explode('-',$previous_date);
$date1 = $parts1[2] . '/' . $parts1[1] . '/' . $parts1[0];

$parts2 = explode('-',$date);
$date2 = $parts2[2] . '/' . $parts2[1] . '/' . $parts2[0];

$date1="28/05/2014";
$date2="11/06/2014";
//$previous_date = date('Y-m-d', strtotime($date .' -1 day'));

$result = $client->call('GateInOut', array('FromDate' => $date1,'ToDate'=>$date2));
//$result = $client->call('GateInOut', array('FromDate' => '01/01/2014','ToDate'=>'02/01/2014'));
// Check for a fault
if ($client->fault) 
{
    echo '<h2>Fault</h2><pre>';
    print_r($result);
    echo '</pre>';
} 
else 
{
    // Check for errors
    $err = $client->getError();
    if ($err) 
	{
        // Display the error
        echo '<h2>Error</h2><pre>' . $err . '</pre>';
    } 
	else 
	{
        // Display the result
        echo '<h2>Result</h2><pre>';
        //print_r($result);
		$tmp_arr=$result['GateInOutResult']['diffgram']['GateInOut']['Table'];
		for($i=0;$i<sizeof($tmp_arr);$i++)
		{			
			//echo "vehicle_name=".$tmp_arr[$i]['VEHICLE_NO']." &nbsp;CONTAINER_NO=".$tmp_arr[$i]['CONTAINER_NO']." &nbsp;CUSTOMER=".$tmp_arr[$i]['CUSTOMER']." &nbsp;TARGET_IN_DATE=".$tmp_arr[$i]['TARGET_IN_DATE']." &nbsp;TARGET_OUT_DATE=".$tmp_arr[$i]['TARGET_OUT_DATE']." &nbsp;GATE_OUT_DATE=".$tmp_arr[$i]['GATE_OUT_DATE']." &nbsp;GATE_IN_DATE=".$tmp_arr[$i]['GATE_IN_DATE']."<br>";			
			//if($tmp_arr[$i]['VEHICLE_NO']=='UP78CN7844')
			{			
				$input_vehicle_name[] = $tmp_arr[$i]['VEHICLE_NO'];
				$input_container_no[] = $tmp_arr[$i]['CONTAINER_NO'];
				$input_factory_code[] =$tmp_arr[$i]['CUSTOMER'];
				
				$parts1 = explode(' ',$tmp_arr[$i]['TARGET_IN_DATE']);
				$parts2 = explode('/',$parts1[0]);
				if($parts2[2]=="")
				{
					$parts2[2] = '0000';
					$parts2[1] = '00';
					$parts2[0] = '00';
					$parts1[1] = '00:00';
				}
				$input_factory_ea_datetime_tmp = $parts2[2] . '-' . $parts2[1] . '-' . $parts2[0]." ".$parts1[1].":00";
				
				$parts1 = explode(' ',$tmp_arr[$i]['TARGET_OUT_DATE']);
				$parts2 = explode('/',$parts1[0]);
				if($parts2[2]=="")
				{
					$parts2[2] = '0000';
					$parts2[1] = '00';
					$parts2[0] = '00';
					$parts1[1] = '00:00';
				}			
				$input_factory_ed_datetime_tmp = $parts2[2] . '-' . $parts2[1] . '-' . $parts2[0]." ".$parts1[1].":00";

				$parts1 = explode(' ',$tmp_arr[$i]['GATE_OUT_DATE']);
				$parts2 = explode('/',$parts1[0]);
				if($parts2[2]=="")
				{
					$parts2[2] = '0000';
					$parts2[1] = '00';
					$parts2[0] = '00';
					$parts1[1] = '00:00';
				}			
				$input_icd_out_datetime_tmp = $parts2[2] . '-' . $parts2[1] . '-' . $parts2[0]." ".$parts1[1].":00";

				$parts1 = explode(' ',$tmp_arr[$i]['GATE_IN_DATE']);
				$parts2 = explode('/',$parts1[0]);
				if($parts2[2]=="")
				{
					$parts2[2] = '0000';
					$parts2[1] = '00';
					$parts2[0] = '00';
					$parts1[1] = '00:00';
				}			
				$input_icd_in_datetime_tmp = $parts2[2] . '-' . $parts2[1] . '-' . $parts2[0]." ".$parts1[1].":00";

				$input_factory_ea_datetime[] = $input_factory_ea_datetime_tmp;
				$input_factory_ed_datetime[] = $input_factory_ed_datetime_tmp;
				$input_icd_out_datetime[] = $input_icd_out_datetime_tmp;
				$input_icd_in_datetime[] = $input_icd_in_datetime_tmp;
			}
		}
		//print_r($tmp_arr);
		echo '</pre>';
    }
}

/*for($i=0;$i<sizeof($input_factory_code);$i++)
{
	echo "<br>VEHICLE=".$input_vehicle_name[$i];
	echo ",FACTORY_CODE=".$input_factory_code[$i];	
	echo ",FACTORY_EA_DATETIME=".$input_factory_ea_datetime[$i];
	echo ",FACTORY_ED_DATETIME=".$input_factory_ed_datetime[$i];
	echo ",ICD_OUT_DATETIME=".$input_icd_out_datetime[$i];
	echo ",ICD_IN_DATETIME=".$input_icd_in_datetime[$i];
	echo "<br>";
}*/

$icd_code_global = "KLPL/ICD Panki";
$current_date = date('Y-m-d H:i:s');
//###### CHECK RECORDS IN DATABASE 

$query1 = "SELECT DISTINCT vehicle_name FROM icd_input";
$result1 = mysql_query($query1,$DbConnection);
while($row1=mysql_fetch_object($result1))
{
	$vehicle_uniq[] = $row1->vehicle_name;
}

if(sizeof($vehicle_uniq)==0)
{
	//echo "<br>INSERT";
	//1.INSERT FOR THE FIRST TIME
	for($i=0;$i<sizeof($input_vehicle_name);$i++)
	{
		echo "\nINSERTING FIRST ENTRIES..".$i;	
		if($input_icd_in_datetime[$i]!='0000-00-00 00:00:00')
		{
			$status=0;
		}
		else
		{
			$status=1;
		}
		$query_insert = "INSERT INTO icd_input(vehicle_name,container_no,icd_code,icd_out_datetime,factory_code,factory_ea_datetime,".
		"factory_ed_datetime,icd_in_datetime,account_id,create_date,status,remark) values('$input_vehicle_name[$i]','$input_container_no[$i]','$icd_code_global',".
		"'$input_icd_out_datetime[$i]','$input_factory_code[$i]','$input_factory_ea_datetime[$i]','$input_factory_ed_datetime[$i]',".
		"'$input_icd_in_datetime[$i]','$account_id','$current_date','$status','-')";
		//echo "<br>QueryInsert=".$query_insert;
		$result1 = mysql_query($query_insert, $DbConnection);
	}	
}
else
{
	for($i=0;$i<sizeof($vehicle_uniq);$i++)
	{
		$query2 = "SELECT sno,vehicle_name,container_no,icd_out_datetime,icd_code,icd_in_datetime,create_date,edit_date,factory_ea_datetime,factory_ed_datetime,factory_code,remark FROM icd_input WHERE vehicle_name = '$vehicle_uniq[$i]' AND icd_out_datetime = (SELECT MAX(icd_out_datetime) from icd_input where vehicle_name='$vehicle_uniq[$i]')";
		$result2 = mysql_query($query2, $DbConnection);
		
		echo "\nUPDATE";
		if($row2=mysql_fetch_object($result2))
		{
			$db_sno[] = $row2->sno;
			$db_vehicle_name[] = $row2->vehicle_name;
			$db_container_no[] = $row2->container_no;
			$db_icd_code[] = $row2->icd_code;
			$db_icd_out_datetime[] = $row2->icd_out_datetime;
			$db_factory_code[] = $row2->factory_code;
			$db_factory_ea_datetime[] = $row2->factory_ea_datetime;
			$db_icd_in_datetime[] = $row2->icd_in_datetime;
			$db_create_date[] = $row2->create_date;
			$db_edit_date[] = $row2->edit_date;
			$db_status[] = $row2->status;
			$db_remark[] = $row2->remark;	
		}		
	}

	//for($i=0;$i<sizeof($sno);$i++)
	$len_db = sizeof($db_sno);

	for($i=0;$i<sizeof($input_vehicle_name);$i++)
	{
		//for($j=0;$j<sizeof($customer);$j++)
		//$update_flag = false;
		//$update_counter = 0;
		//$insert_flag = false;			
		$flag_update=false;
		
		//echo "<br>SizeDBV=".sizeof($db_vehicle_name);
		for($j=0;$j<sizeof($db_vehicle_name);$j++)
		{
			//### EXISTING ENTRIES ::UPDATE
			//if( ($input_vehicle_name[$i]==$db_vehicle_name[$j]) && ($db_icd_out_datetime[$j]!="") && ($db_icd_in_datetime[$j]=="") && ($input_icd_in_datetime[$i]!="") && ($db_icd_out_datetime[$j]==$input_icd_out_datetime[$i]))
			
			//echo "<br><br>db_vehicle_name[$j]=".$db_vehicle_name[$j]." ,input_vehicle_name[$i]=".$input_vehicle_name[$i];
			//echo "<br>db_icd_out_datetime[$j]=".$db_icd_out_datetime[$j]." ,db_icd_in_datetime[$j]=".$db_icd_in_datetime[$j];
			//echo "<br>input_icd_in_datetime[$i]=".$input_icd_in_datetime[$i]." ,input_icd_out_datetime[$i]=".$input_icd_out_datetime[$i];
			
			if( (trim($db_vehicle_name[$j]) == trim($input_vehicle_name[$i])) && ($db_icd_out_datetime[$j]!="0000-00-00 00:00:00") && ($db_icd_in_datetime[$j]=="0000-00-00 00:00:00") && ($input_icd_in_datetime[$i]!="0000-00-00 00:00:00") && ($db_icd_out_datetime[$j]==$input_icd_out_datetime[$i]))
			{
				echo "\nMATCH::UPDATING ICD_IN_TIME..".$i." ,db_vehicle_name[j]=".$db_vehicle_name[$j];
				//$update_flag = true;
				$update_sno = $db_sno[$j];
				$update_icd_in_datetime = $input_icd_in_datetime[$i];

				$query_update = "UPDATE icd_input SET icd_in_datetime='$update_icd_in_datetime',edit_date='$current_date',status=0 WHERE sno='$update_sno'";
				$result = mysql_query($query_update,$DbConnection);
				
				//### UPDATE DATABASE ARRAY
				$db_icd_in_datetime[$j] = $update_icd_in_datetime;
				$flag_update = true;
				break;
			}
			//##### NEW ENTRIES :: INSERT
			//else if( ($input_vehicle_name[$i]==$db_vehicle_name[$j]) && ($db_icd_out_datetime[$j]!="") && ($db_icd_out_datetime[$j] < $input_icd_out_datetime[$i]) && ($input_icd_out_datetime[$i]!=""))
			else if( (trim($db_vehicle_name[$j]) == trim($input_vehicle_name[$i])) && ($db_icd_out_datetime[$j]!="0000-00-00 00:00:00") && ($db_icd_out_datetime[$j] < $input_icd_out_datetime[$i]) && ($input_icd_out_datetime[$i]!="0000-00-00 00:00:00"))			
			{
				if($db_icd_in_datetime[$j]!="0000-00-00 00:00:00")
				{
					echo "\ndb_icd_out_datetime[j]=".$db_icd_out_datetime[$j]." ,input_icd_out_datetime[i]=".$input_icd_out_datetime[$i];
					echo "\nMATCH::CLOSING : ICD_IN_TIME AND INSERTING NEW RECORD..".$i." ,db_vehicle_name[j]=".$db_vehicle_name[$j];
					//$insert_flag = true;				
					$new_vehicle_name = $input_vehicle_name[$i];
					$new_container_no = $input_container_no[$i];
					$new_icd_code = "KLPL/ICD Panki";
					$new_icd_out_datetime = $input_icd_out_datetime[$i];
					$new_factory_code = $input_factory_code[$i];
					$new_factory_ea_datetime = $input_factory_ea_datetime[$i];
					$new_factory_ed_datetime = $input_factory_ed_datetime[$i];
					$new_icd_in_datetime = $input_icd_in_datetime[$i];
					$new_create_date = $current_date;
										
					$query_insert = "INSERT INTO icd_input(vehicle_name,container_no,icd_code,icd_out_datetime,factory_code,factory_ea_datetime,factory_ed_datetime,icd_in_datetime,account_id,create_date,status,remark) values('$new_vehicle_name','$new_container_no','$new_icd_code','$new_icd_out_datetime','$new_factory_code','$new_factory_ea_datetime','$new_factory_ed_datetime','$new_icd_in_datetime','$account_id','$current_date',1,'-')";
					$result = mysql_query($query_insert, $DbConnection);

					//##### UPDATE DATABASE ARRAY -increase counter				
					$db_vehicle_name[$len_db] = $input_vehicle_name[$i];
					$db_icd_code[$len_db] = "KLPL/ICD Panki";
					$db_icd_out_datetime[$len_db] = $input_icd_out_datetime[$i];
					$db_factory_code[$len_db] = $input_factory_code[$i];
					$db_factory_ea_datetime[$len_db] = $input_factory_ea_datetime[$i];				
					$db_create_date[$len_db] = $current_date;
					$len_db++;					
				}
				//###### CLOSED DATABASE ARRAY				
				//$update_sno[] = $sno[$j];
				//$update_icd_out_datetime[] = $input_icd_out_datetime[$i];
				else if($db_icd_in_datetime[$j]=="0000-00-00 00:00:00")
				{
					echo "\nMATCH::UPDATING ICD_OUT TO->> ICD_IN_TIME..".$i." ,db_vehicle_name[j]=".$db_vehicle_name[$j];
					//$update_flag = true;
					$update_sno = $db_sno[$j];
					$update_icd_in_datetime = $input_icd_out_datetime[$i];
					
					$query_update = "UPDATE icd_input SET icd_in_datetime='$update_icd_in_datetime',edit_date='$current_date',status=0 WHERE sno='$update_sno'";
					//echo "<br>".$query_update;
					$result = mysql_query($query_update,$DbConnection);
					
					//### UPDATE DATABASE ARRAY
					$db_icd_in_datetime[$j] = $update_icd_in_datetime;					
				}
				/*else
				{
					$db_icd_in_datetime[$len_db] = $input_icd_in_datetime[$i];
				}*/
			
				$flag_update = true;
				break;				
			}
			else if( (trim($db_vehicle_name[$j]) == trim($input_vehicle_name[$i]) ) ) 
			{
				echo "\nvehicleMatched in ELSE:flagUpdate true:".$input_vehicle_name[$i];
				$flag_update = true;
			}
		}
		
		//echo "<br>FlagUpdate=".$flag_update;
		if(!$flag_update)
		{		
			if($input_icd_in_datetime[$i]=='0000-00-00 00:00:00')
			{
				echo "\nINSERTING NEW ENTRIES..FlagUpdateElse::".$i." ,input_vehicle_name=".$input_vehicle_name[$j];
				$query_insert = "INSERT INTO icd_input(vehicle_name,container_no,icd_code,icd_out_datetime,factory_code,factory_ea_datetime,factory_ed_datetime,icd_in_datetime,account_id,create_date,status,remark) values('$input_vehicle_name[$i]','$input_container_no[$i]','$icd_code_global','$input_icd_out_datetime[$i]','$input_factory_code[$i]','$input_factory_ea_datetime[$i]','$input_factory_ed_datetime[$i]','$input_icd_in_datetime[$i]','$account_id','$current_date',1,'1')";
				//echo "<br>QueryInsert=".$query_insert;
				$result1 = mysql_query($query_insert, $DbConnection);						
			}
		}
	}
}


// Display the request and response
/*echo '<h2>Request</h2>';
echo '<pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
echo '<h2>Response</h2>';
echo '<pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
// Display the debug messages
echo '<h2>Debug</h2>';
echo '<pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';*/
?>
