<?php
//echo "ONE";
include_once("../util_session_variable.php");
include_once("../get_all_dates_between.php");
//echo "TWO";
$HOST = "itrackdb.c4pqfsdaiccz.us-east-1.rds.amazonaws.com";
$DBASE = "iespl_vts_beta";
$USER = "bailoo";
$PASSWD = 'neon04$VTS';
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("could not connect to DB test");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$imei1 = $_POST['imei'];
$vname1 = $_POST['vname'];

$alert_name = array();
$vname = array();
$sts = array();				
$message = array();
$phone = array();
$person_name = array();

/*$date1_tmp = explode('/',$_POST['date1_input']);
$date1 = $date1_tmp[2]."-".$date1_tmp[1]."-".$date1_tmp[0];
$date2_tmp = explode('/',$_POST['date2_input']);
$date2 = $date1_tmp[2]."-".$date2_tmp[1]."-".$date2_tmp[0];
*/
$date1 = str_replace('/','-',$_POST['date1_input']);
$date2 = str_replace('/','-',$_POST['date2_input']);

$vname1 = trim($vname1);
//$vname1 = "MP19 HA 2167";
//$date1 = "2014-05-02";
//$date2 = "2014-05-03";
//echo "<br>REC".$imei1." ,".$vname1;

date_default_timezone_set("Asia/Calcutta");
$current_datetime = date("Y-m-d H:i:s");
$current_date = date("Y-m-d");

//echo "dbcon=".$DbConnection;
echo '<center>';
echo '<table align="center">';
$detail ="";

if($imei1!="")
{
	//echo "one";
	$query = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name, vehicle_grouping.account_id FROM vehicle,vehicle_grouping,vehicle_assignment WHERE ".
	"vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id ".
	"AND vehicle_assignment.device_imei_no='$imei1' AND vehicle.status=1 ".
	"AND vehicle_assignment.status=1";
		
	$result = mysql_query($query,$DbConnection);
	$numrow = mysql_num_rows($result);
		
	if($row = mysql_fetch_object($result))
	{      
		$acc_id_tmp = $row->account_id;
		//echo "<br>acid=".$acc_id_tmp;      
		$account_id1 = $acc_id_tmp;
		$vehicle_id1 = $row->vehicle_id;
		$vehicle_name1 = $row->vehicle_name;

		$query2 = "SELECT user_id from account WHERE account_id='$account_id1' AND status=1";    
		$result2 = mysql_query($query2,$DbConnection);
		//echo "<br>".$query;    
		if($row2 = mysql_fetch_object($result2))
		{
			$userid = $row2->user_id; 	  
		}   
	}
}
else if($vname1!="")
{
	$vehicle_name1 = $vname1;
	//echo "one";
	$query = "SELECT DISTINCT vehicle.vehicle_id,vehicle_assignment.device_imei_no, vehicle_grouping.account_id FROM ".
	"vehicle_grouping,vehicle_assignment,vehicle WHERE ".
	"vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id ".
	"AND vehicle_grouping.vehicle_id = vehicle.vehicle_id  AND vehicle_assignment.vehicle_id = vehicle.vehicle_id ".
	"AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id ".
	"AND vehicle.vehicle_name='$vname1' AND vehicle.status=1 ".
	"AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";  
	//echo $query;
	$result = mysql_query($query,$DbConnection);

	if($row = mysql_fetch_object($result))
	{
		$vehicle_id1 = $row->vehicle_id;
		$imei = $row->device_imei_no;  
		$account_id2 = $row->account_id;

		$query2 = "SELECT user_id from account WHERE account_id='$account_id2' AND status=1";    
		$result2 = mysql_query($query2,$DbConnection);
		//echo "Q=".$query;    
		if($row2 = mysql_fetch_object($result2))
		{
			$userid = $row2->user_id;
		}
	}      
}

$query = "SELECT alert_id,alert_name FROM alert WHERE status=1";
$result = mysql_query($query,$DbConnection);
if($row=mysql_fetch_object($result))
{
	$alert_id[] = $row->alert_id;
	$alert_name[] = $row->alert_name;
}
//Vehicle	Date	SMS Detail	PhoneNo
//<marker alert_id="10" vehicle_id="2035" escalation_id="164" sts="2014-05-03 00:40:01" 
//message="Your vehicle MP19 HA 2167 is at 1.03 km from MDR, Danapur, Patna, Bihar, 800001, ???????????? ????????????????????? at 2014-05-02 23:59:09" 
//phone="9425426029" person_name="Mintu_New" email="-" status="3339732"/>

//########## GET SMS RECORD FROM XML
get_sms_xml_data($vehicle_id1, $vehicle_name1, $date1, $date2);

	
function get_sms_xml_data($vid_input, $vname_input, $datefrom, $dateto)
{
	//echo "<b>str=".$vid_input." ,". $vname_input." ,". $datefrom." ,". $dateto;	
	global $alert_id;
	global $alert_name;
	global $vname;
	global $sts;				
	global $message;
	global $phone;
	global $person_name;	
	
	get_All_Dates($datefrom, $dateto, &$userdates);

	$startdate = $datefrom." 00:00:00";
	$enddate = $dateto." 23:59:59";
	
	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);
 									
	for($i=0;$i<=($date_size-1);$i++)
	{	 	    
		$xml_file = "/sms_log_ln/".$userdates[$i].".xml";	

		if (file_exists($xml_file)) 
		{
			$total_lines = count(file($xml_file));
			//echo "<br>Total lines orig=".$total_lines;

			$xml = @fopen($xml_file, "r") or $fexist = 0;  

			while(!feof($xml))          // WHILE LINE != NULL
			{
				$c++;
				$DataValid = 0;
				//echo fgets($file). "<br />";
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE											

				//if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
	      		if(strlen($line)>20)
				{
					$status = preg_match('/vehicle_id="[^"]+/', $line, $vid_tmp);
					$vid_tmp1 = explode("=",$vid_tmp[0]);
					$vid_xml = preg_replace('/"/', '', $vid_tmp1[1]);
					if(($vid_xml!="") && trim($vid_input)==trim($vid_xml))
					{
						$DataValid = 1;
					}
				}

				if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
				{					
					$status = preg_match('/sts="[^"]+/', $line, $sts_tmp);
					$sts_tmp1 = explode("=",$sts_tmp[0]);
					$sts_xml = preg_replace('/"/', '', $sts_tmp1[1]);					
				}				
				//echo "Final0=".$sts." datavalid=".$DataValid;

				if($sts_xml!=null)
				{
					if( ($sts_xml >= $startdate) && ($sts_xml <= $enddate) && ($sts_xml!="-") && ($DataValid==1) )
					{							           	
						$status = preg_match('/alert_id="[^"]+/', $line, $alert_id_tmp);
						if($status==0)
						{
							continue;
						}
		  
						$status = preg_match('/message="[^"]+/', $line, $message_tmp);
						if($status==0)
						{
						  continue;               
						}
						//echo "test6".'<BR>';
						$status = preg_match('/phone="[^" ]+/', $line, $phone_tmp);
						if($status==0)
						{
						  continue;
						}

						$status = preg_match('/person_name="[^" ]+/', $line, $person_name_tmp);
						if($status==0)
						{
						  continue;
						}						
										
						$alert_id_tmp1 = explode("=",$alert_id_tmp[0]);
						$alert_id_xml = preg_replace('/"/', '', $alert_id_tmp1[1]);
						$alert_name_tmp="";
						for($k=0;$k<sizeof($alert_id);$k++)
						{
							if(trim($alert_id_xml)==trim($alert_id[$k]))
							{
								$alert_name_tmp = $alert_name[$k];
								break;
							}
						}
						$alert_name[] = $alert_name_tmp;
						$vname[] = $vname_input;
						$sts[] = $sts_xml;						

						$message_tmp1 = explode("=",$message_tmp[0]);
						$message[] = preg_replace('/"/', '', $message_tmp1[1]);

						$phone_tmp1 = explode("=",$phone_tmp[0]);
						$phone[] = preg_replace('/"/', '', $phone_tmp1[1]);

						$person_name_tmp1 = explode("=",$person_name_tmp[0]);
						$person_name[] = preg_replace('/"/', '', $person_name_tmp1[1]);

					}   // else closed    				               				    				
				} // $sts_date_current >= $startdate closed					
				////////// FINAL DIST CLOSED          		
			}   // while closed							
			fclose($xml);            
		} // if (file_exists closed
	}  // for closed 

	//echo "Test1";
	//fclose($fh);
}


//### PRINT RESULT
echo '
<html>
<body>
<div align="right"><a href="../home.php" style="text-decoration:none;"><font color=blue size=3><strong>Home</strong></font></a>&nbsp;&nbsp;<a href="../logout.php" style="text-decoration:none;"><font color=green size=3><strong>Logout</strong></font></a></div> 
<hr>
<table align="center">
	<tr>
	<td align="center"><font color=green><strong>SMS DETAIL</strong></font><div style="height:8px;"></div></td>
	</tr>
</table>
<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3 style="color: black;font-size: 8pt;margin: 0px;padding: 0px;font-weight: normal;">	
	<tr>
		<td align="left"><b>SNo</b></td>
		<td align="left"><b>VehicleName</b></td>
		<td align="left"><b>PersonName</b></td>
		<td align="left"><b>PhoneNo</b></td>
		<td align="left"><b>AlertName</b></td>			
		<td align="left"><b>ServerTime</b></td>
		<td align="left"><b>Message</b></td>			
	</tr>';
	
$sno =1;	
for($i=0;$i<sizeof($vname);$i++)
{								              	              
    echo'<tr>
	<td class="text" align="left" width="4%"><b>'.$sno.'</b></td>        												
	<td class="text" align="left">'.$vname[$i].'</td>		
    <td class="text" align="left">'.$person_name[$i].'</td>
	<td class="text" align="left">'.$phone[$i].'</td>		
    <td class="text" align="left">'.$alert_name[$i].'</td>	
	<td class="text" align="left">'.$sts[$i].'</td>		
    <td class="text" align="left">'.$message[$i].'</td>
	</tr>';						
	$sno++;
}
echo '</table>
</body>
</html>
';						
?>
