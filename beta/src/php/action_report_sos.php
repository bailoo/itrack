<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];
include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("report_title.php");
include_once("read_filtered_xml.php");

$DEBUG = 0;

$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':',$device_str);
$vsize=count($vserial);

$date1 = $_POST['start_date'];
$date2 = $_POST['end_date']; 

$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);


if($vsize>0)
{
  for($i=0;$i<$vsize;$i++)
  {
    /*$query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
    //echo $query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname[$i] = $row->vehicle_name;*/
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $vname[$i] = $vehicle_detail_local[0];
  }  
  $current_dt = date("Y_m_d_H_i_s");	
  $xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
  write_engine_runhr_report_xml($vserial, $vname, $date1, $date2, $xmltowrite);
}

function write_engine_runhr_report_xml($vserial, $vname, $startdate, $enddate, $xmltowrite)
{
  $maxPoints = 1000;
	$file_exist = 0;

	$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);

	//$i=0;
	for($i=0;$i<sizeof($vserial);$i++)
	{  	
     //echo   "<br>vserial[i] =".$vserial[$i];
     get_engine_runhr_xml_data($vserial[$i], $vname[$i], $startdate, $enddate, $xmltowrite);
    //echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_engine_runhr_xml_data($vehicle_serial, $vname, $startdate, $enddate, $xmltowrite)
{
	global $DbConnection;
	$io = get_io($vehicle_serial,'engine');
	
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

	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append
  
  $runhr_duration =0 ;
  $flag =0;
  
   $StartFlag=0; 
  for($i=0;$i<=($date_size-1);$i++)
	{
		if($userdates[$i] == $current_date)
		{			
			//echo "in else";
			$xml_file = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml in get_xml_data =".$xml_file;	
    	
    if (file_exists($xml_file)) 
		{			     
      //$current_datetime1 = date("Y_m_d_H_i_s");
      $t=time();
      $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
      //$xml_log = "../../../xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
									      
      if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
        copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
        $xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
				//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";
				        
        copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
        SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
      $total_lines = count(file($xml_original_tmp));
      //echo "<br>Total lines orig=".$total_lines;
      
      $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
      //$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
      $logcnt=0;
      $DataComplete=false;                  
      $vehicleserial_tmp=null;
      $format =2;
      
      
      if (file_exists($xml_original_tmp)) 
      {      
        while(!feof($xml))          // WHILE LINE != NULL
  			{
  				$DataValid = 0;
          //echo fgets($file). "<br />";
  				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
  				//echo "<br>Line".$line;
  				if(strlen($line)>20)
  				{
  				  $linetmp =  $line;
          }
  				
  				$linetolog =  $logcnt." ".$line;
  				$logcnt++;
  				//fwrite($xmllog, $linetolog);
  
  				if(strpos($line,'fix="1"'))     // RETURN FALSE IF NOT FOUND
  				{
  					$format = 1;
            $fix_tmp = 1;
  				}
                
  				else if(strpos($line,'fix="0"'))
  				{
  				  $format = 1;
  					$fix_tmp = 0;
  				}			
  				
          if( (preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
          { 
            $lat_value = explode('=',$lat_match[0]);
            $lng_value = explode('=',$lng_match[0]);
            //echo " lat_value=".$lat_value[1];         
            if( (strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
            {
              $DataValid = 1;
            }
          }
          
          //if( (substr($line, 0,1) == '<') && (substr( (strlen($line)-1), 0,1) == '>') && ($fix_tmp==1) && ($f>0) && ($f<$total_lines-1) )        
          if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
  				{
  					preg_match('/datetime="[^"]+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
  					//echo "<br>str3tmp[0]=".$str3tmp[0];
  					//$xml_date = $str3tmp[0];
            $str3tmp1 = explode("=",$str3tmp[0]);  
            $xml_date = preg_replace('/"/', '', $str3tmp1[1]);            					
  				}				
          //echo "<br>xml_date=".$xml_date." datavalid=".$DataValid;
          
          if($xml_date!=null)
  				{				  
            //echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
            //$lat = $lat_value[1] ;
  					//$lng = $lng_value[1];
  					
  					if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
  					{							           	
              //echo "<br>One";             
              $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
              //echo "Status=".$status.'<BR>';
              if($status==0)
              {
                continue;
              }
              
              $status = preg_match('/lat="[^" ]+/', $line, $lat_tmp);
              if($status==0)
              {
                continue;               
              }
              //echo "test6".'<BR>';
              
              $status = preg_match('/lng="[^" ]+/', $line, $lng_tmp);
              if($status==0)
              {
                continue;
              }     
                                       
              //echo "<br>Format=".$format;	
              $status = preg_match('/'.$io.'="[^" ]+/', $line, $enginecount_tmp);                        
              //echo "<br>status=".$status;
              if($status==0)
              {
                continue;
              }
              
              $datetime = $xml_date;           
                          
              $vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);  
              $vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);                                            
  
              //$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);  
              //$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);                     
              
              $enginecount_tmp1 = explode("=",$enginecount_tmp[0]);  
              $engine_count = preg_replace('/"/', '', $enginecount_tmp1[1]);                                                                            	                         
              //echo "<br>enginecount=".$engine_count;
              
            	//$date_secs2 = strtotime($time2);              
            	if($engine_count>500 && $StartFlag==0)  //500
            	{                						
            		$time1 = $datetime;
            		$StartFlag = 1;
            	} 
            	else if($engine_count<500 && $StartFlag==1)   //500
            	{
            		$StartFlag = 2;
            	}
              $time2 = $datetime;
            
            	if($StartFlag == 2)
            	{
            		$StartFlag=0;
            		$runtime = strtotime($time2) - strtotime($time1);
            		//echo "<br>runtime=".$runtime;
            		//$runhr_duration = strtotime($runtime);
            		$hr =  (int)(($runtime)/3600);	 
            		//$runhr_duration = round($runhr_duration,2);
            		$min =  ($runtime)%3600;
            		$sec =  (int)(($min)%60);
            		$min =  (int)(($min)/60);
            
            		$engine_runhr_data = "\n< marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" engine_runhr=\"".$hr.':'.$min.':'.$sec."\"/>";						          						
            		//echo "<br>".$engine_runhr_data;
            		$linetowrite = $engine_runhr_data; // for distance       // ADD DISTANCE
            		fwrite($fh, $linetowrite); 
            	}                                     																                              										                               
  					} // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed
        }   // while closed
      } // if original_tmp exist closed
	  
	  if($StartFlag == 1)
	  {
		$StartFlag=0;
		$runtime = strtotime($time2) - strtotime($time1);
		//echo "<br>runtime=".$runtime;
		//$runhr_duration = strtotime($runtime);
		$hr =  (int)(($runtime)/3600);	 
		//$runhr_duration = round($runhr_duration,2);
		$min =  ($runtime)%3600;
		$sec =  (int)(($min)%60);
		$min =  (int)(($min)/60);
  
		$engine_runhr_data = "\n< marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" engine_runhr=\"".$hr.':'.$min.':'.$sec."\"/>";						          						
		//echo "<br>".$engine_runhr_data;
		$linetowrite = $engine_runhr_data; // for distance       // ADD DISTANCE
		fwrite($fh, $linetowrite); 
	  }
          			
     fclose($xml);            
		 unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 	                		                 	
	
	//echo "Test1";
	fclose($fh);
}

	
$m1=date('M',mktime(0,0,0,$month,1));
  
  echo'<center>';   
  report_title("SOS Report",$date1,$date2);   
	echo'<div style="overflow: auto;height: 285px; width: 600px;" align="center">';
   
  ///////////////////  READ SPEED XML 	//////////////////////////////				                      
  $xml_path = $xmltowrite;
  //echo "<br>xml_path=".$xml_path;
	read_engine_runhr_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$engine_runhr);
	//convert_in_two_dimension
  //echo "<br><br>size, vname=".sizeof($vname).", dt=".$datefrom[0];
	//////////////////////////////////////////////////////////////////////
  			             
  $j=-1;
  $k=0;
  			             
  for($i=0;$i<sizeof($imei);$i++)
	{								              
    if(($i===0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_engine_runhr =0;
      $sum_engine_runmin =0;
      $sum_engine_runsec =0;
      $total_engine_runhr[$j] =0;
      
      $sno = 1;
      $title='SOS Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
      $vname1[$j][$k] = $vname[$i];
      
      echo'
      <br><table align="center">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>
      <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
      <tr>
			<td class="text" align="left"><b>SNo</b></td>
			<td class="text" align="left"><b>DateTime From</b></td>
			<td class="text" align="left"><b>DateTime To</b></td>
			<td class="text" align="left"><b>SOS (hr:min:sec)</b></td>								
      </tr>';  								
    }                                                                        		
		
    $engine_runhr_str = explode(':',$engine_runhr[$i]);
    
    $hr = $engine_runhr_str[0];
    $min = $engine_runhr_str[1];
    $sec = $engine_runhr_str[2];
    
    //echo "<br>hr =".$hr." ,min=".$min." ,engine_runhr=".$engine_runhr[$i];      
    $sum_engine_runhr = $sum_engine_runhr + $hr;
    $sum_engine_runmin = $sum_engine_runmin + $min; 
    $sum_engine_runsec = $sum_engine_runmin + $sec;      
    
    //echo "<br>sum_engine_runhr =".$sum_engine_runhr." ,sum_engine_runmin=".$sum_engine_runmin;              		              
    echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$datefrom[$i].'</td>';		
    echo'<td class="text" align="left">'.$dateto[$i].'</td>';			
		echo'<td class="text" align="left">'.$engine_runhr[$i].'</td>';					
		echo'</tr>';	          		
		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
    
    $datefrom1[$j][$k] = $datefrom[$i];	
    $dateto1[$j][$k] = $dateto[$i];										
    $engine_runhr1[$j][$k] = $engine_runhr[$i];       			    				  				
	
	  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {
      echo '<tr style="height:20px;background-color:lightgrey">
      <td class="text"><strong>Total<strong>&nbsp;</td>
			<td class="text"><strong>'.$date1.'</strong></td>	
      <td class="text"><strong>'.$date2.'</strong></td>';									
      
			if($k>0)
			{                  
        $quotient_min =  (int)($sum_engine_runsec / 60);
        $remainder_sec =  (int)($sum_engine_runsec % 60);
        $sum_engine_runmin =  (int) ($sum_engine_runmin + $quotient_min);
        $quotient_hr =  (int)($sum_engine_runmin / 60);
        $remainder_min =  (int)($sum_engine_runmin % 60);
        $sum_engine_runhr = (int) ($sum_engine_runhr + $quotient_hr);
        
        //echo "<br>sum_engine_runhr =".$sum_engine_runhr." ,sum_engine_runmin=".$sum_engine_runmin;
        //echo "<br>quotient_hr =".$quotient_hr." ,remainder_min=".$remainder_min;
        $total_engine_runhr[$j] = $sum_engine_runhr.":".$remainder_min.":".$remainder_sec;
			}																		
			echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';
			echo'</tr>'; 
      echo '</table>';
		}  
		
    $k++;   
    $sno++;                       							  		
 }
 
 //echo "k=".$k;
 if($k==1)
 {							
    $total_engine_runhr[$j] = $engine_runhr[0];
    echo '<tr style="height:20px;background-color:lightgrey">
    <td class="text"><strong>Total<strong>&nbsp;</td>
    <td class="text"><strong>'.$date1.'</strong></td>	
    <td class="text"><strong>'.$date2.'</strong></td>';		
    echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';
		echo'</tr>'; 
    echo '</table>';  		     
 }
 
 echo "</div>";     

	echo'<form method = "post" action="src/php/report_getpdf_type4.php?size='.$vsize.'" target="_blank">';
	
	for($x=0;$x<=$j;$x++)
	{															
		$title=$vname1[$x][0].": SOS Report(hrs:min:sec) From DateTime : ".$date1." to ".$date2;
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		for($y=0;$y<$k;$y++)
		{
			//$k=$j-1;
			$sno++;
                    
      $datetmp1 = $datefrom1[$x][$y];	
			$datetmp2 = $dateto1[$x][$y];										
			$engine_runhr_tmp = $engine_runhr1[$x][$y];
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$engine_runhr_tmp\" NAME=\"temp[$x][$y][SOS(hrs.min)]\">";																	
		}		

		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Engine Run Hour(hrs.min)]\">";									
		
		$m = $y+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$total_engine_runhr[$x]\" NAME=\"temp[$x][$m][SOS(hrs.min)]\">";																																										
	}																						

	echo'	
    <table align="center">
		<tr>
			<td><input type="submit" value="Get PDF" class="noprint">&nbsp;
      <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;</td>
		</tr>
		</table>
		</form></center>
 ';  
           
unlink($xml_path);
                     
?>							 					