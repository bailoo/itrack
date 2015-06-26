<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];
include_once('common_xml_element.php');
include_once("get_all_dates_between.php");
include_once("sort_xml_datagap.php");
//include_once("sort_xml.php");

//include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("report_get_parsed_string.php");
include_once("util.hrminsec.php");
include_once("report_title.php");
include_once("read_filtered_xml.php");

set_time_limit(1800);

$DEBUG = 0;
$device_str = $_POST['vehicleserial'];  

$vserial = explode(':',$device_str);
$vsize=count($vserial);

$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];

$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);

$no_gps_interval = $_POST['no_gps_interval'];
$no_data_interval = $_POST['no_data_interval'];

$skip_nogps_interval = (double)$no_gps_interval*60;
$skip_nodata_interval = (double)$no_data_interval*60;

//$imei_datagap = array();
$vname_datagap = array();
//$imei_datagap = array();
//$vname_datagap = array(array());

$t1_no_gps = array(array());       // t1_no_gps
$t2_no_gps = array(array());

$t1_no_data = array(array());      // t1_no_data
$t2_no_data = array(array());

$tdiff_no_gps = array(array());
$tdiff_no_data = array(array());        

if($vsize>0)
{
	//echo '<br>in write0';
	$t=time();         
	$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$t.".xml";
	write_datagap_report_xml($vserial, $date1, $date2, $skip_nogps_interval, $skip_nodata_interval, $xmltowrite,$root);
}
  
function write_datagap_report_xml($vserial, $startdate, $enddate, $skip_nogps_interval, $skip_nodata_interval, $xmltowrite,$root)
{
	//echo '<br>in write1';
	global $DbConnection;
	global $vname_datagap;

	$maxPoints = 1000;
	$file_exist = 0;

	//$i=0;	
	for($i=0;$i<sizeof($vserial);$i++)
	{
		$vehicle_info=get_vehicle_info($root,$vserial[$i]);
		$vehicle_detail_local=explode(",",$vehicle_info);	
		$vname[$i] = $vehicle_detail_local[0];
		$vname_datagap[$i] = $vname[$i];
 
		get_detail_nogps($vserial[$i], $vname[$i], $startdate, $enddate, $skip_nogps_interval);
		get_detail_nodata($vserial[$i], $vname[$i], $startdate, $enddate, $skip_nodata_interval);
	//	merge_datagap($vserial[$i],$vname[$i],$xmltowrite);
		//echo   "t2".' '.$i;
	}
} 
  
  
  /////////// NO GPS OPENS ///////////////////////  
  function get_detail_nogps($imei, $vname, $startdate, $enddate, $skip_interval)
  {   
  	//echo '<br>in No data';
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	//global $imei_datagap;
	//global $vname_datagap;
    global $t1_no_gps;
    global $t2_no_gps;
    global $tdiff_no_gps;
    
    $date_1 = explode(" ",$startdate);
  	$date_2 = explode(" ",$enddate);

  	$datefrom = $date_1[0];
  	$dateto = $date_2[0];
  	$timefrom = $date_1[1];
  	$timeto = $date_2[1];
  
  	get_All_Dates($datefrom, $dateto, &$userdates);
  
  	//date_default_timezone_set("Asia/Calcutta");
  	$current_datetime = date("Y-m-d H:i:s");
  	$current_date = date("Y-m-d");
  	//print "<br>CurrentDate=".$current_date;
  	$date_size = sizeof($userdates);	
	
    for($i=0;$i<=($date_size-1);$i++)
  	{
		$valid_data = false;
  		$whole_day_nogps =0;
		$no_gps_found = false;
		
		if($i==0)
		{
		  //echo "<br>START";
		  $startdate_local = $startdate;
		  $enddate_local = $userdates[$i]." 23:59:59";
		}
		else if( $i == ($date_size-1) )
		{
		 // echo "<br>END";
		  $startdate_local = $userdates[$i]." 00:00:00";
		  $enddate_local = $enddate;
		}
		else
		{
		//  echo "<br>MID";
		  $startdate_local = $userdates[$i]." 00:00:00";
		  $enddate_local = $userdates[$i]." 23:59:59";          
		}
     		
       //echo $xml_current;
		$xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$imei.".xml";
		if (file_exists($xml_current))      
		{		
  			//echo "in else";
  			$xml_file = $xml_current;
  			$CurrentFile = 1;
  		}		
  		else
  		{
  			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$imei.".xml";
  			//$xml_file = $xml_sorted;
  			$CurrentFile = 0;
  		}
  		
  		//echo "<br>NODATA:xml in xml_data1 =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{				  
			$t=time();
			$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$i.".xml";

			if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
				copy($xml_file,$xml_original_tmp);
				//$xml_original_tmp = $xml_file;
			}
			else
			{
				//echo "<br>TWO<br>";
				$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$imei."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";

				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp, $userdates[$i]);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
			
			//echo "<br>xml_original_tmp=".$xml_original_tmp;      
			$total_lines = count(file($xml_original_tmp));
			//echo "<br>Total lines =".$total_lines;
			if($total_lines == 0)
			{
			  $whole_day_nodata = 1;
			}
			
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;
				
			///////// NO DATA LOGIC      	
			/*if($i==0)
			{
			  //echo "<br>START";
			  $startdate_local = $startdate;
			  $enddate_local = $userdates[$i]." 23:59:59";
			}
			else if( $i == ($date_size-1) )
			{
			 // echo "<br>END";
			  $startdate_local = $userdates[$i]." 00:00:00";
			  $enddate_local = $enddate;
			}
			else
			{
			//  echo "<br>MID";
			  $startdate_local = $userdates[$i]." 00:00:00";
			  $enddate_local = $userdates[$i]." 23:59:59";          
			}*/
					
			$t1 = $startdate_local;
			$t2 = $enddate_local;
			//echo "<br>Date=".$userdates[$i]." ,t1=".$t1." ,t2=".$t2;
				  
			$flag_start = 1;
			
			if (file_exists($xml_original_tmp)) 
			{
				//echo "\nBeforeF1:ve=".$ve." ,vd=".$vd;
				set_master_variable($userdates[$i]);
				//echo "\nAfterF1";
				
				while(!feof($xml))					// WHILE LINE != NULL
				{
					$gps_valid = 0;
					$line = fgets($xml);				// STRING SHOULD BE IN SINGLE QUOTE
					//echo "<br>line=".$line;      		
					$lat = get_xml_data('/'.$vd.'="\d+\.\d+[NS]\"/', $line);
					$lng = get_xml_data('/'.$ve.'="\d+\.\d+[EW]\"/', $line);

					$xml_date = get_xml_data('/'.$vh.'="[^"]+"/', $line);
					//$speed = get_xml_data('/'.$vf.'="[^"]+"/', $line);        
					
					if($flag_start)
					{
						$flag_start = 0;
					}
			
					if(strlen($lat)>2 && strlen($lng)>2) 
					{
						$gps_valid = 1; 
					}					
					if($xml_date!=null)
					{
						if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") )
						{
							if(($gps_valid) && (!$no_gps_found))
							{
								$t2 = $xml_date;
							}
							else if(!$gps_valid)
							{
								$no_gps_found = true;
								$t1 = $xml_date;
							}
							else if( ($gps_valid) && ($no_gps_found))
							{
								$tdiff = strtotime($t2)-strtotime($t1);
								//echo "<br>LOOP: T1 = ".$t1."\tT2 = ".$t2."\tTdiff = ".$tdiff."\tTskip = ".$skip_interval."<br>";
								if( $tdiff >= $skip_interval) // a big interval found
								{
									//$no_data = "<BR>NoDataA:T1 = ".$t1."\tT2 = ".$t2."\tTdiff = ".$tdiff;
									//echo "<br>IMEI=".$imei." ,DataGap found1:".$t1."\tT2 = ".$t2."\tTdiff = ".$tdiff;
									//fwrite($fh, $no_data);
									//$imei_datagap[] = $imei;
									//$vname_datagap[$imei] = 	$vname;
									$t1_no_gps[$imei][] = $t1;
									$t2_no_gps[$imei][] = $t2;
									$tdiff_no_gps[$imei][] = $tdiff;	
									//#######################
									//echo $no_data;
									$no_gps_found = false;
								}
							}
							$valid_data = true;
							//////////////////        				
						}
					}
				}  // WHILE CLOSED
				
				//####### CHECK END TIME DATAGAP				
				if($valid_data)
				{
					$tdiff = strtotime($enddate_local)-strtotime($t2);
				}
				else
				{
					$tdiff = strtotime($enddate_local)-strtotime($t1);
				}
				
				if((!$gps_valid) && ($tdiff >= $skip_interval)) // a big interval found
				{
					//echo "<BR>IMEI=".$imei." ,DataGap found2:T1 = ".$t2."\tT2 = ".$enddate_local."\tTdiff = ".$tdiff;					
					//fwrite($fh, $no_data);
					//$imei_datagap[] = $imei;
					//$vname_datagap[$imei] = 	$vname;
					$t1_no_gps[$imei][] = $t2;
					$t2_no_gps[$imei][] = $enddate_local;
					$tdiff_no_gps[$imei][] = $tdiff;	
					//#######################
					//echo $no_data;
				}				
				
				fclose($xml);            
				unlink($xml_original_tmp);         
			}// IF FILE EXISTS ORIGINAL TMP XML   
							   
		}// IF FILE EXISTS XML
		else
		{
			$whole_day_nogps =1;
		}
			  
		if($whole_day_nogps)
		{          
			//echo "<BR>IMEI=".$imei." ,DataGap found3:T1 = ".$startdate_local."\tT2 = ".$enddate_local."\tTdiff = ".$tdiff;
			$t1_no_gps[$imei][] = $startdate_local;
			$t2_no_gps[$imei][] = $enddate_local;
			$tdiff_no_gps[$imei][] = strtotime($enddate_local)-strtotime($startdate_local);
		}     
    } // FOR LOOP 
	   //////////////////////		
	  //fclose($xml);
	 fclose($fh);
  }	  // FUNCTION NO DATA CLOSED
  
  
  /////////// NO DATA OPENS ///////////////////////  
  function get_detail_nodata($imei, $vname, $startdate, $enddate, $skip_interval)
  {   
  	//echo '<br>in No data';
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	//global $imei_datagap;
	//global $vname_datagap;
    global $t1_no_data;
    global $t2_no_data;
    global $tdiff_no_data;
    
    $date_1 = explode(" ",$startdate);
  	$date_2 = explode(" ",$enddate);

  	$datefrom = $date_1[0];
  	$dateto = $date_2[0];
  	$timefrom = $date_1[1];
  	$timeto = $date_2[1];
  
  	get_All_Dates($datefrom, $dateto, &$userdates);
  
  	//date_default_timezone_set("Asia/Calcutta");
  	$current_datetime = date("Y-m-d H:i:s");
  	$current_date = date("Y-m-d");
  	//print "<br>CurrentDate=".$current_date;
  	$date_size = sizeof($userdates);	
    
    for($i=0;$i<=($date_size-1);$i++)
  	{
		$valid_data = false;
  		$whole_day_nodata =0;
		
		if($i==0)
		{
		  //echo "<br>START";
		  $startdate_local = $startdate;
		  $enddate_local = $userdates[$i]." 23:59:59";
		}
		else if( $i == ($date_size-1) )
		{
		 // echo "<br>END";
		  $startdate_local = $userdates[$i]." 00:00:00";
		  $enddate_local = $enddate;
		}
		else
		{
		//  echo "<br>MID";
		  $startdate_local = $userdates[$i]." 00:00:00";
		  $enddate_local = $userdates[$i]." 23:59:59";          
		}
     		
       //echo $xml_current;
		$xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$imei.".xml";
		if (file_exists($xml_current))      
		{		
  			//echo "in else";
  			$xml_file = $xml_current;
  			$CurrentFile = 1;
  		}		
  		else
  		{
  			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$imei.".xml";
  			//$xml_file = $xml_sorted;
  			$CurrentFile = 0;
  		}
  		
  		//echo "<br>NODATA:xml in xml_data1 =".$xml_file;	    	
		if (file_exists($xml_file)) 
		{				  
			$t=time();
			$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$i.".xml";

			if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
				copy($xml_file,$xml_original_tmp);
				//$xml_original_tmp = $xml_file;
			}
			else
			{
				//echo "<br>TWO<br>";
				$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$imei."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";

				copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
				SortFile($xml_unsorted, $xml_original_tmp, $userdates[$i]);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
			
			//echo "<br>xml_original_tmp=".$xml_original_tmp;      
			$total_lines = count(file($xml_original_tmp));
			//echo "<br>Total lines =".$total_lines;
			if($total_lines == 0)
			{
			  $whole_day_nodata = 1;
			}
			
			$xml = @fopen($xml_original_tmp, "r") or $fexist = 0;
				
			///////// NO DATA LOGIC      	
			/*if($i==0)
			{
			  //echo "<br>START";
			  $startdate_local = $startdate;
			  $enddate_local = $userdates[$i]." 23:59:59";
			}
			else if( $i == ($date_size-1) )
			{
			 // echo "<br>END";
			  $startdate_local = $userdates[$i]." 00:00:00";
			  $enddate_local = $enddate;
			}
			else
			{
			//  echo "<br>MID";
			  $startdate_local = $userdates[$i]." 00:00:00";
			  $enddate_local = $userdates[$i]." 23:59:59";          
			}*/
					
			$t1 = $startdate_local;
			$t2 = $enddate_local;
			//echo "<br>Date=".$userdates[$i]." ,t1=".$t1." ,t2=".$t2;
				  
			$flag_start = 1;
			
			if (file_exists($xml_original_tmp)) 
			{
				//echo "\nBeforeF1:ve=".$ve." ,vd=".$vd;
				set_master_variable($userdates[$i]);
				//echo "\nAfterF1";
				while(!feof($xml))					// WHILE LINE != NULL
				{
					$line = fgets($xml);				// STRING SHOULD BE IN SINGLE QUOTE
					//echo "<br>line=".$line;      		
					$xml_date = get_xml_data('/'.$vh.'="[^"]+"/', $line);
					//$speed = get_xml_data('/'.$vf.'="[^"]+"/', $line);        
					
					if($flag_start)
					{
						$flag_start = 0;
					}
			
					if($xml_date!=null)
					{
						if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") )
						{
							$t2 = $xml_date;        				
							
							$tdiff = strtotime($t2)-strtotime($t1);
							//echo "<br>LOOP: T1 = ".$t1."\tT2 = ".$t2."\tTdiff = ".$tdiff."\tTskip = ".$skip_interval."<br>";
							if( $tdiff >= $skip_interval) // a big interval found
							{
								//$no_data = "<BR>NoDataA:T1 = ".$t1."\tT2 = ".$t2."\tTdiff = ".$tdiff;
								echo "<br>IMEI=".$imei." ,DataGap found1:".$t1."\tT2 = ".$t2."\tTdiff = ".$tdiff;
								//fwrite($fh, $no_data);
								//$imei_datagap[] = $imei;
								//$vname_datagap[$imei] = 	$vname;
								$t1_no_data[$imei][] = $t1;
								$t2_no_data[$imei][] = $t2;
								$tdiff_no_data[$imei][] = $tdiff;	
								//#######################
								//echo $no_data;
							}
							$t1 = $xml_date;
							$valid_data = true;
							//////////////////        				
						}
					}
				}  // WHILE CLOSED
				
				//####### CHECK END TIME DATAGAP
				if($valid_data)
				{
					echo "<br>LastData1, T2=".$t2." ,Enddate=".$enddate_local;
					$tdiff = strtotime($enddate_local)-strtotime($t2);
				}
				else
				{
					echo "<br>LastData2, T2=".$t1." ,Enddate=".$enddate_local;
					$tdiff = strtotime($enddate_local)-strtotime($t1);
				}
				
				if( $tdiff >= $skip_interval) // a big interval found
				{
					//echo "<BR>IMEI=".$imei." ,DataGap found2:T1 = ".$t2."\tT2 = ".$enddate_local."\tTdiff = ".$tdiff;					
					//fwrite($fh, $no_data);
					//$imei_datagap[] = $imei;
					//$vname_datagap[$imei] = 	$vname;
					$t1_no_data[$imei][] = $t2;
					$t2_no_data[$imei][] = $enddate_local;
					$tdiff_no_data[$imei][] = $tdiff;	
					//#######################
					//echo $no_data;
				}				
				
				fclose($xml);            
				unlink($xml_original_tmp);         
			}// IF FILE EXISTS ORIGINAL TMP XML   
							   
		}// IF FILE EXISTS XML
		else
		{
			$whole_day_nodata =1;
		}
			  
		if($whole_day_nodata)
		{          
			//echo "<BR>IMEI=".$imei." ,DataGap found3:T1 = ".$startdate_local."\tT2 = ".$enddate_local."\tTdiff = ".$tdiff;
			$t1_no_data[$imei][] = $startdate_local;
			$t2_no_data[$imei][] = $enddate_local;
			$tdiff_no_data[$imei][] = strtotime($enddate_local)-strtotime($startdate_local);
		}     
    } // FOR LOOP 
	   //////////////////////		
	  //fclose($xml);
	 fclose($fh);
  }	  // FUNCTION NO DATA CLOSED

//////////// DISPLAY MODULE ////////////  
$found = false;
echo '<center>';
echo '<div align="center"><strong>DATA GAP/ NOGPS Report-'.$date1.' to '.$date2.' </strong></div><br>';

echo '<table border=1 rules=all bordercolor="#e5ecf5" align="center" cellspacing=3 cellpadding=3 width="100%">
<tr>
<td>';
echo'<div style="overflow: auto;height: 410px;" align="center" width="100%">';							  
for($i=0;$i<$vsize;$i++)
{								                   
	//echo "<br>Size=".sizeof($t1_no_data[$vserial[$i]]);
	$sno = 1;
	$title='<br><br>DATA GAP : '.$vname_datagap[$i].' &nbsp;<font color=red>('.$vserial[$i].')</font>';

	echo'
	<table align="center" cellspacing=3 cellpadding=3 width="100%">
	<tr>
	<td class="text" align="center" style="height:8px;"><b>'.$title.'</b></td>
	</tr>
	</table>
	<table border=1 rules=all bordercolor="#e5ecf5" align="center" cellspacing=3 cellpadding=3 width="100%">	
	<tr height="3%">
		<td class="text" align="left"><b>Start</b></td>
		<td class="text" align="left"><b>End</b></td>
	<td class="text" align="left"><b>Difference(H:m:s)</b></td>          
	</tr>';  								

	for($j=0;$j<sizeof($t1_no_data[$vserial[$i]]);$j++)
	{		
		$found = true;
		$diff_nodata ="";
		
		if($tdiff_no_data[$vserial[$i]][$j]!="")
		  $diff_nodata = sec_to_time($tdiff_no_data[$vserial[$i]][$j]);

		echo '<tr valign="top">';
		echo'<td class="text" align="left">'.$t1_no_data[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$t2_no_data[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$diff_nodata.'</td>';
		echo '</tr>';		
	}
	echo'</table><br>';
}

if(!$found)
{
	echo "<br><div><center><font color=red><strong>Sorry No data gap found</strong></font></center></div>";
}
echo '</div>'; 
echo '</td>';


echo '<td>';
//########## NO GPS
$found = false;
echo'<div style="overflow: auto;height: 410px;" align="center" width="100%">';
for($i=0;$i<$vsize;$i++)
{								                   
	//echo "<br>Size=".sizeof($t1_no_gps[$vserial[$i]]);
	$sno = 1;
	$title='<br><br>NO GPS : '.$vname_datagap[$i].' &nbsp;<font color=red>('.$vserial[$i].')</font>';

	echo'
	<table align="center" cellspacing=3 cellpadding=3 width="100%">
	<tr>
	<td class="text" align="center" style="height:8px;"><b>'.$title.'</b></td>
	</tr>
	</table>
	<table border=1 rules=all bordercolor="#e5ecf5" align="center" cellspacing=3 cellpadding=3 width="100%">	
	<tr height="3%">
		<td class="text" align="left"><b>Start</b></td>
		<td class="text" align="left"><b>End</b></td>
	<td class="text" align="left"><b>Difference(H:m:s)</b></td>          
	</tr>';  								

	for($j=0;$j<sizeof($t1_no_gps[$vserial[$i]]);$j++)
	{		
		$found = true;
		$diff_nogps ="";
		
		if($tdiff_no_gps[$vserial[$i]][$j]!="")
		  $diff_nogps = sec_to_time($tdiff_no_gps[$vserial[$i]][$j]);

		echo '<tr valign="top">';
		echo'<td class="text" align="left">'.$t1_no_gps[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$t2_no_gps[$vserial[$i]][$j].'</td>';
		echo'<td class="text" align="left">'.$diff_nogps.'</td>';
		echo '</tr>';		
	}
	echo'</table><br>';
}

if(!$found)
{
	echo "<br><div><center><font color=red><strong>Sorry No GPS Not found</strong></font></center></div>";
}
echo '</div>';
echo '</td>';
echo '</tr></table>'; 			 
					
echo '</center>';			 

/////////////  	
?>
