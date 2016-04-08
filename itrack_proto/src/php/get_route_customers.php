<?php
include_once('main_vehicle_information_1.php');
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$pathtowrite = $_REQUEST['xml_file_customer'];
$route1 = $_REQUEST['route'];
$vehicle1 = $_REQUEST['vehicle'];
//echo "V=".$vehicle1;
//$route1 = "200001";

$route_input = array();
$customer_input = array();
$vehicle_input = array();
$transporter_input = array();

get_customer_detail($account_id, "ZPME");
get_customer_detail($account_id, "ZPMM");

//echo "<br>sizeofcustomer_input=".sizeof($customer_input);
$customer_input1 = array_unique($customer_input);
//$route_input1 = $route_input;
$customer_str = "";
if(sizeof($customer_input1)>0)					
{							
	foreach ($customer_input1 as $customer_new) 
	{
		$customer_str = $customer_str.$customer_new.",";
	}
}
$customer_str = substr($customer_str, 0, -1);

$station_str = substr($station_str,0,-1);
$query = "SELECT DISTINCT customer_no,station_coord,type FROM station WHERE customer_no IN($customer_str) AND user_account_id='$account_id' AND status=1";
//echo "<br>".$query;
$result = mysql_query($query, $DbConnection);

while($row = mysql_fetch_object($result))
{
	$station_tmp[] = $row->customer_no;
	$coord = $row->station_coord;
	$coord1 = explode(',',$coord);
  	$lat[]= trim($coord1[0]);
  	$lng[]= trim($coord1[1]);
	$type[]= $row->type;	
	//echo "<br>Query executed:".$coord;
}

$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
fwrite($fh, "<t1>");  
fclose($fh);

$fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append 


//######### GET REMARK 
$query_remark = "SELECT DISTINCT remark FROM route_assignment2 WHERE route_name='$route1' AND status=1";
$result_remark = mysql_query($query_remark,$DbConnection);

if($row2=mysql_fetch_object($result_remark))
{
	//$imei = $row->device_imei_no;																	
	//$route = $row->route_name;
	$remark = $row2->remark;
}	
//########## REMARK CLOSED	
					
//echo "Q=".$query_remark." ,rem=".$remark;
//echo "<br>sizeStation=".sizeof($station_tmp);

//############ CODE FOR TRANSPORTER ##################
$transporter_string = "";
$transporter_input1 = array_unique($transporter_input);
//echo "SizeTPT=".sizeof($transporter_input);
if(sizeof($transporter_input1)>0)					
{							
	foreach ($transporter_input1 as $transporter_new) 
	{
		$transporter_string = $transporter_string.$transporter_new."/";
	}
}
$transporter_string = substr($transporter_string, 0, -1);
$transporter_string = $vehicle1.":".$transporter_string;
//echo "TPT=".$transporter_string;
	
for($i=0;$i<sizeof($station_tmp);$i++)
{	
	//echo "<br>station_tmp=".$station_tmp[$i]." ,station=".$station[$j];
	if(($lat[$i]!="") && ($lng[$i]!=""))
	{
		$linetowrite = "\n<marker lat=\"".trim($lat[$i])."\" lng=\"".trim($lng[$i])."\" station_no=\"".$station_tmp[$i]."\" route_no=\"".$route1."\" remark=\"".$remark."\" transporter=\"".$transporter_string."\" type=\"".$type[$i]."\"/>";								
		//echo "<br>".$linetowrite;
		fwrite($fh, $linetowrite);		
	}
}

$tmp = "-";
if(sizeof($station_tmp)==0)
{
		$linetowrite = "\n<marker lat=\"".$tmp."\" lng=\"".$tmp."\" station_no=\"".$tmp."\" route_no=\"".$route1."\" remark=\"".$remark."\" transporter=\"".$transporter_string."\" type=\"".$tmp."\"/>";								
		//echo "<br>".$linetowrite;
		fwrite($fh, $linetowrite);	
}
fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
fwrite($fh, "\n</t1>");  
fclose($fh);

//echo $pathtowrite;

//######### READ XLS DATA CLOSED ###########

//############ FUNTION ROUTE DETAIL ####################
function get_customer_detail($account_id, $shift_time)
{
	//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;			
	global $route1;
	global $route_input;
	global $customer_input;
	global $vehicle_input;
	global $transporter_input;
	global $vehicle1;
	//echo "V=".$vehicle1;

	//$dir = "c:\\gps_report/231/master";
	//$dir = "c:\\halt2/test_master";
	$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
	//echo "<br>dir=".$dir;
	
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		//echo "<A HREF=\"$file\">$file</A><BR>\n";
		$file_tmp = explode("#",$file);
		$file_ext = explode(".",$file_tmp[2]);
		if($file_ext[0]!="")
		{			
			//echo "\nfile_ext[0]=".$file_ext[0];
			if( ($file_ext[0] == "4") && ($shift_time=="ZPME") )		//###### EVENING FILE
			{
				$path = $dir."/".$file;

				$row = 1;
				if (($handle = fopen($path, "r")) !== FALSE)
				{
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
					{
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";
						$row++;
							
						if($num<10)
						{
							continue;
						}
						if($row > 2)
						{
							//$shift_input[] = $data[4];
							if(trim($route1) == trim($data[6]))
							{
								$route_input[] = $data[6];
								$customer_input[] = $data[9];
							}
							
							//echo "v1=".$vehicle1" ,v2=".$data[8];
							/*if(trim($vehicle1) == trim($data[7]))
							{								
								//echo "FOUND TPT1";
								$transporter_input[] = $data[8];
							}*/
							//echo "\nEV:r=".$row." ,data[4]=".$data[4]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[9]=".$data[9];
						}
					}
					fclose($handle);
					//echo "\nsizeof(route_input)=".sizeof($route_input);
				}
			}
			 
			if( ($file_ext[0] == "5") && ($shift_time=="ZPMM") )			//###### MORNING FILE
			{
				$path = $dir."/".$file;
				$row = 1;
				if (($handle = fopen($path, "r")) !== FALSE) {
						
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";
						$row++;
							
						if($num<10)
						{
							continue;
						}
						if($row > 2)
						{
							if(trim($route1) == trim($data[6]))
							{
								$route_input[] = $data[6];
								$customer_input[] = $data[9];
							}													
						}
					}
					fclose($handle);
				}
			}
			if($file_ext[0] == "6")	//######## TRANSPORTER FILE
			{
				$path = $dir."/".$file;

				$row = 1;
				if (($handle = fopen($path, "r")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";
					$row++;
					/*for ($c=0; $c < $num; $c++) {
					 //echo "\ndata=".$data[$c] . "<br />\n";
						if($c==0)
						{
							$vehicle_t[] = $data[$c];
						}
						else if($c==1)
						{
							$transporter[] = $data[$c];
						}                                 
					  }*/
					  
						if($row > 1)
						{
							if(trim($vehicle1) == trim($data[0]))
							{
								$transporter_input[] = $data[1];
							}													
						}					  
					}
					fclose($handle);
				}     
			} //IF FORMAT 5			
		}  //
	}
	closedir($dh);
} //function closed			

//############ FUNCTION ROUTE CLOSED ########################
