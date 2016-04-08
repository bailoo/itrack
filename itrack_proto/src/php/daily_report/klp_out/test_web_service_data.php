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

$date1 = "03/05/2014";
$date2 = "04/05/2014";

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
			echo "vehicle_name=".$tmp_arr[$i]['VEHICLE_NO']." &nbsp;CONTAINER_NO=".$tmp_arr[$i]['CONTAINER_NO']." &nbsp;CUSTOMER=".$tmp_arr[$i]['CUSTOMER']." &nbsp;TARGET_IN_DATE=".$tmp_arr[$i]['TARGET_IN_DATE']." &nbsp;TARGET_OUT_DATE=".$tmp_arr[$i]['TARGET_OUT_DATE']." &nbsp;GATE_OUT_DATE=".$tmp_arr[$i]['GATE_OUT_DATE']." &nbsp;GATE_IN_DATE=".$tmp_arr[$i]['GATE_IN_DATE']."<br>";			
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
		//print_r($tmp_arr);
		echo '</pre>';
    }
}

