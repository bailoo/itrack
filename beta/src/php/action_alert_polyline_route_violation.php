<?php
set_time_limit(80000);
//==========include libraray and class and function==============//
include('class_polyline_edge.php');
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("read_filtered_xml.php");
include_once('common_xml_element.php');
include_once("get_all_dates_between.php");
include_once('user_type_setting.php');
if($person_user_type==1)
{
	include_once("sort_xml_person.php");
}
else
{
	include_once("sort_xml.php");
}
include_once("report_title.php");
//=========Session==============================================//
$root=$_SESSION["root"];
//=========post/get data value======================================//
$device_str = $_POST['vehicleserial'];
$vserial = $device_str;
$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];
$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);
$user_interval = $_POST['user_interval'];
//=======[FILE]Code for getting lat lng from FILE of selected vehicle with date time and interval=========//

$t=time();
$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$t.".xml";
$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
fwrite($fh, "<t1>");  
fclose($fh);
$vehicle_info=get_vehicle_info($root,$vserial);
//echo $vehicle_info;
$vehicle_detail_local=explode(",",$vehicle_info);	
$vname = $vehicle_detail_local[0];
//echo $vname;
get_xml_data($vserial, $vname, $date1, $date2, $user_interval, $xmltowrite);//calling function
$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
fwrite($fh, "\n</t1>");  
fclose($fh);
function get_xml_data($vehicle_serial, $vname, $startdate, $enddate, $user_interval, $xmltowrite)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);
	
	$datefrom = $date_1[0];	
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];
	//==echo $datefrom."". $dateto;
	
	$fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag =0;
	
	get_All_Dates($datefrom, $dateto, &$userdates);
	//=====date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");	
	$date_size = sizeof($userdates);
	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append
	$j = 0;
	for($i=0;$i<=($date_size-1);$i++)
	{
		$startdate1 = $startdate;
		$enddate1 = $enddate;
		$xml_current ="../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
		include("/var/www/html/vts/beta/src/php/common_xml_path.php");
		$xml_current =$xml_data."/".$userdates[$i]."/".$vehicle_serial.".xml";
		if (file_exists($xml_current))      
		{		
			//==echo "in else1";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file =$sorted_xml_data."/".$userdates[$i]."/".$vehicle_serial.".xml";
			//==echo "in else".$xml_file;
			$CurrentFile = 0;
		}
		if (file_exists($xml_file)) 
		{
			//==echo"exist";
			$t=time();
			$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
			if($CurrentFile == 0)
			{
				copy($xml_file,$xml_original_tmp);
			}
			else
			{
				$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp,$userdates[$i]);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
			$total_lines = count(file($xml_original_tmp));
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
			$c = -1;
			$f=0;
			if (file_exists($xml_original_tmp)) 
			{
				set_master_variable($userdates[$i]);
				while(!feof($xml))          // WHILE LINE != NULL
				{
					$c++;
					$DataValid = 0;
					$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
					if(strlen($line)>20)
					{
						$linetmp =  $line;
					}
					$linetolog =  $logcnt." ".$line;
					$logcnt++;
					if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{ 
						$lat_value = explode('=',$lat_match[0]);
						$lng_value = explode('=',$lng_match[0]);						
						if((strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") && (strpos($lat_value[1],'.')==3 && strpos($lng_value[1],'.')==3))
						{
							$DataValid = 1;
						}
					}
					if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
					{					
						$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
						$datetime_tmp1 = explode("=",$datetime_tmp[0]);
						$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
						$xml_date = $datetime;
					}
					if($xml_date!=null)
					{
						if( ($xml_date >= $startdate1 && $xml_date <= $enddate1  && $xml_date >= $xml_date_latest && $xml_date<=($userdates[$i]." 23:59:59")) && ($xml_date!="-") && ($DataValid==1) )
						{
							$xml_date_latest = $xml_date;
							$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
							if($status==0)
							{
							  continue;               
							}							
							$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
							if($status==0)
							{
							  continue;
							}
							$lat_tmp1 = explode("=",$lat_tmp[0]);
							$lat = preg_replace('/"/', '', $lat_tmp1[1]);
		
							$lng_tmp1 = explode("=",$lng_tmp[0]);
							$lng = preg_replace('/"/', '', $lng_tmp1[1]);
							if($firstdata_flag==0)
							{
								$firstdata_flag = 1;
								$interval = (double)$user_interval*60;
								$time1 = $datetime;					
								$date_secs1 = strtotime($time1);								
								$date_secs1 = (double)($date_secs1 + $interval); 
								$date_secs2 = 0;  

							}
							else
							{ 
								$time2 = $datetime;											
								$date_secs2 = strtotime($time2);
								$vserial=$vehicle_serial;
								if( ($date_secs2 >= $date_secs1))// || ($f == $total_lines-5))
								{
									$lat_lng_data = "\n<marker vname=\"".$vname."\" imei=\"".$vserial."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" lat=\"".$lat."\" lng=\"".$lng."\"/>";			          						
									
									$linetowrite = $lat_lng_data; 
									fwrite($fh, $linetowrite);  		

									//reassign time1
									$time1 = $datetime;
									$date_secs1 = strtotime($time1);
									$date_secs1 = (double)($date_secs1 + $interval);
								}								
							}
						}
					}
					$j++;          
					$f++;
				}
			}
			fclose($xml);            
			unlink($xml_original_tmp);
		}
		//end if
	}//for closed 
	$lat_lng_data = "\n<marker vname=\"".$vname."\" imei=\"".$vserial."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" lat=\"".$lat."\" lng=\"".$lng."\"/>";						          						
	$linetowrite = $lat_lng_data; 
	fwrite($fh, $linetowrite); 
	//reassign time1
	$time1 = $datetime;
	$date_secs1 = strtotime($time1);
	$date_secs1 = (double)($date_secs1 + $interval);		    						
	fclose($fh);
}


//=======[DATABASE]Getting vehicle route/polyline latlng from database========//
$query_polyline = "SELECT polyline_coord,polyline_name FROM polyline WHERE polyline_id IN(SELECT polyline_id FROM polyline_assignment WHERE status=1 AND ".
"vehicle_id IN (SELECT vehicle_id FROM vehicle_assignment WHERE device_imei_no='$vserial' AND status=1)) AND ".
"user_account_id='$account_id' AND status=1";  
$res_polyline = mysql_query($query_polyline,$DbConnection);
if($row_polyline = mysql_fetch_object($res_polyline))
{
	$polyline_coord_tmp = $row_polyline->polyline_coord;
	$polyline_coord = base64_decode($polyline_coord_tmp);//polyline in base64 must be decode before use

	$polyline_coord = str_replace('),(',' ',$polyline_coord);
	$polyline_coord = str_replace('(','',$polyline_coord);
	$polyline_coord = str_replace(')','',$polyline_coord);
	$polyline_coord = str_replace(', ',',',$polyline_coord);	
	$polyline_data=explode(" ",$polyline_coord);
	$polyline_name=$row_polyline->polyline_name;
	
	$chk_latlng_array=array();
	$data_date_array=array();
	//samples
	/*$chk_latlng_array[]="26.513034341866465, 80.24422645568848";
	$chk_latlng_array[]="26.512343102724035, 80.26216506958008";
	$chk_latlng_array[]="26.503587046862997, 80.27263641357422";
	$chk_latlng_array[]="26.494830323685452, 80.2796745300293";	*/
	///////////////////  READ  XML 	//////////////////////////////				                      
	$xml_path = $xmltowrite; 
	read_lat_lng_xml($xml_path, &$imei, &$vnamea, &$datefrom, &$dateto, &$lat ,&$lng );
	for($i=0;$i<sizeof($imei);$i++)
	{
		$chk_latlng_array[]=substr($lat[$i], 0,-1).",".substr($lng[$i], 0,-1);
		$data_date_array[]=$dateto[$i];
		
	}
//=======[Class/Object] Class called for checking point on edge=============//
	$get_data=new class_polyline_edge();	
	$data_result = $get_data->get_polyline($polyline_data,$chk_latlng_array,$data_date_array); //both parameters in array
	//print_r($data_result);//data_result in array 
//=====To manipulate and display on HTML Table============================//
	echo'<form method = "post" target="_blank">';
	echo '<center>';
	echo'<br>';
	report_title("Route Deviation Report",$date1,$date2);
	echo'<div style="overflow: auto;height: 500px; width: 100%" align="center">';
	$title="Route ($polyline_name) Deviation Report : $vname &nbsp;<font color=red>(".$vserial.")</font>";
	$title1="Route ($polyline_name) Deviation Report : $vname &nbsp;(".$vserial.")";
	echo'<br>
	<table align="center" width=100%>
		<tr>
			<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
		</tr>
	</table>
	<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=1 cellpadding=3>	
				<tr>
					<td class="text" align="left"><b>SNo</b></td>
					<td class="text" align="left"><b>Date From</b></td>
					<td class="text" align="left"><b>Date To</b></td>
					<td class="text" align="left"><b>Status</b></td>					
					<td class="text" align="left"><b>Location</b></td>						
				</tr>
	';
	$csv_string = "";
	$csv_string = $csv_string.$title1.",,,,\n";
	//$csv_string = $csv_string."SNo,DateTime,Status,VehicleLocation \n";
	$csv_string = $csv_string."SNo,DateFrom,DateTo,Status,Location \n";
	
	$sno = 1;
	$j=0;
	$k=0;
	echo"<input TYPE=\"hidden\" VALUE=\"$title1\" NAME=\"title\">";
	for($i=0;$i<sizeof($data_result);$i++)
	{
		echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		//echo'<td class="text" align="left">'.$dateto[$i].'</td>';
		$result_route=explode(":",$data_result[$i]);
		$datetime_from=explode(" ",$result_route[4]);
		echo'<td class="text" align="left">'.$datetime_from[0].' '.str_replace('-',':',$datetime_from[1]).'</td>'; //date from
		//echo'<td class="text" align="left">'.$result_route[4].' - '.$result_route[5].'</td>'; //date between		
		$datetime_to=explode(" ",$result_route[5]);
		echo'<td class="text" align="left">'.$datetime_to[0].' '.str_replace('-',':',$datetime_to[1]).'</td>'; //date from
		echo'<td class="text" align="left">'.$result_route[0].'</td>';	//status	
		echo"<td class='text' align='left'>$result_route[6]</td>";	//location
		$datefrom1[$k] = $datetime_from[0].' '.str_replace('-',':',$datetime_from[1]);
		$dateto1[$k] = $datetime_to[0].' '.str_replace('-',':',$datetime_to[1]);	
		//$dateto1[$k] = $result_route[4].' - '.$result_route[5];	
		$route_msg[$k] = $result_route[0];
		$vehicle_point[$k] = $result_route[6];
		echo'</tr>';
		//for csv 
		//$csv_string = $csv_string.$sno.','.$dateto1[$k].','.$route_msg[$k].','.str_replace(',','.',$vehicle_point[$k])."\n"; 
		$csv_string = $csv_string.$sno.','.$datefrom1[$k].','.$dateto1[$k].','.$route_msg[$k].','.str_replace(',','.',$vehicle_point[$k])."\n"; 
		$sno++;
		$k++; 
	}
	
	echo '</table>';
	
	$sn=0;
	for($x=0;$x<$k;$x++)
	{
		$sn++;
		/*
		$datetmp1 = $dateto1[$x];														
		$route_msg1 = $route_msg[$x];
		$vehicle_point1 = $vehicle_point[$x];
		echo"<input TYPE=\"hidden\" VALUE=\"$sn\" NAME=\"temp[$x][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][DateTime]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$route_msg1\" NAME=\"temp[$x][Status]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_point1\" NAME=\"temp[$x][LatLng]\">";
		*/
		$datetmp0 = $datefrom1[$x];
		$datetmp1 = $dateto1[$x];														
		$route_msg1 = $route_msg[$x];
		$vehicle_point1 = $vehicle_point[$x];
		echo"<input TYPE=\"hidden\" VALUE=\"$sn\" NAME=\"temp[$x][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$datetmp0\" NAME=\"temp[$x][DateFrom]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][DateTo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$route_msg1\" NAME=\"temp[$x][Status]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_point1\" NAME=\"temp[$x][Location]\">";
	}
	
	echo "</div>";
	//=====PDF and EXCEL REPORT===============================================//
	
	
	$vsize = sizeof($data_result);
	//echo"size=".$vsize;
	echo'	
    <table align="center">
		<tr>
			<td>'; 		
			echo'<input TYPE="hidden" VALUE="route_deviation" NAME="csv_type">';
			echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
			echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
			&nbsp;';           
			echo'</td>		
		</tr>
	</table>
	</form>
	';					 
	unlink($xml_path);
}
else
{
	print"<center><FONT color=\"Red\" size=2><strong>No Data Record</strong></font></center>";
}
echo '</center>';
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	

function read_lat_lng_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$lat ,&$lng)
{  
    $fexist =1;    
    $xml = fopen($xml_path, "r") or $fexist = 0;    
    $count = count(file($xml_path));
    if($fexist)
    {
      $i=0;    	
      while(!feof($xml))          // WHILE LINE != NULL
	  {
    	$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE   
        if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )
        {
            $status = preg_match('/imei="[^"]+/', $line, $imei_tmp);            
            if($status==0)
            {
              continue;
            }         
            $status = preg_match('/vname="[^"]+/', $line, $vname_tmp);
            if($status==0)
            {
              continue;
            }            
            $status = preg_match('/datefrom="[^"]+/', $line, $datefrom_tmp);            
            if($status==0)
            {
              continue;
            }            
            $status = preg_match('/dateto="[^"]+/', $line, $dateto_tmp);            
            if($status==0)
            {
              continue;
            }            
            $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
            if($status==0)
            {
              continue;
            }
            $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
            if($status==0)
            {
              continue;
            }
            // Store Name with Value                  
            $tmp = explode("=",$imei_tmp[0]);
            $imei[$i] = preg_replace('/"/', '', $tmp[1]);                        
            
            $tmp = explode("=",$vname_tmp[0]);
            $vname[$i] = preg_replace('/"/', '', $tmp[1]);
                        
            $tmp = explode("=",$datefrom_tmp[0]);
            $datefrom[$i] = preg_replace('/"/', '', $tmp[1]);
                         
            $tmp = explode("=",$dateto_tmp[0]);
            $dateto[$i] = preg_replace('/"/', '', $tmp[1]);              

			$tmp = explode("=",$lat_tmp[0]);
            $lat[$i] = preg_replace('/"/', '', $tmp[1]); 
			
            $tmp = explode("=",$lng_tmp[0]);
            $lng[$i] = preg_replace('/"/', '', $tmp[1]); 
            $i++;                      
        } //  // IF LINE 0                        
      }   // WHILE CLOSED
   }   // IF FEXIST
}  // FUNCTION DISTANCE CLOSED
?>