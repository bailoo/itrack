<?php
set_time_limit(300);
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];
include_once('common_xml_element.php');
include_once("get_all_dates_between.php");
$report = "engine";

include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("report_title.php");
include_once("read_filtered_xml.php");
include_once("util.hr_min_sec.php");
include_once("get_io.php");
include_once("new_xml_string_io.php");
include_once("get_location.php");

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
   /* $query = "SELECT vehicle_name FROM vehicle WHERE ".
    "vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
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
  write_io_trip_report_xml($vserial, $vname, $date1, $date2, $xmltowrite);
}

function write_io_trip_report_xml($vserial, $vname, $startdate, $enddate, $xmltowrite)
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
		get_io_trip_xml_data($vserial[$i], $vname[$i], $startdate, $enddate, $xmltowrite);
		//echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_io_trip_xml_data($vehicle_serial, $vname, $startdate, $enddate, $xmltowrite)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	global $DbConnection;
	$io = get_io($vehicle_serial,'sos');
	//echo "io=".$io."<br>";
  //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.15")
  //echo "<br>io=".$io;
 
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
	$continuous_running_flag =0;

	$previous_date = $startdate;

	for($i=0;$i<=($date_size-1);$i++)
	{
		//$xml_current = "/mnt/volume3/current_data/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
		$xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
		if(file_exists($xml_current))
		{			
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			//$xml_file = "/mnt/volume4/".$userdates[$i]."/".$vehicle_serial.".xml";
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml in get_xml_data =".$xml_file;	    	
    
    if (file_exists($xml_file)) 
	{	
		//echo "in exists";		
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
        SortFile($xml_unsorted, $xml_original_tmp,$userdates[$i]);    // SORT FILE
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
      $j=0;     
      if (file_exists($xml_original_tmp)) 
      {       
        $c = -1;
		set_master_variable($userdates[$i]);
		if($userdates[$i]>$old_xml_date)
		{
			//echo "in if";
			if($io=='io1')
			{
				$io='i';
			}
			if($io=='io2')
			{
				$io='j';
			}
			if($io=='io3')
			{
				$io='k';
			}
			if($io=='io4')
			{
				$io='l';
			}
			if($io=='io5')
			{
				$io='m';
			}
			if($io=='io6')
			{
				$io='n';
			}
			if($io=='io7')
			{
				$io='o';
			}
			if($io=='io8')
			{
				$io='p';
			}
			//echo "io=".$io;
		}
        while(!feof($xml))            // WHILE LINE != NULL
  			{
  				$c++;
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
  
  				/*if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
  				{
  					$format = 1;
            				$fix_tmp = 1;
  				}
                
  				else if(strpos($line,''.$vc.'="0"'))
  				{
  				  	$format = 1;
  					$fix_tmp = 0;
  				}*/			
  				
          //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
          //echo "format=".$format;
          if( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) &&  (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
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
          //if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE
         // if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') )     
  				if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )
				{
  					preg_match('/'.$vh.'="[^"]+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
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
  					
  					//if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
  					if( ($xml_date > $previous_date && $xml_date <= $enddate) && ($xml_date!="-") )
  					{							           	
              //echo "<br>One";                          
              /*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
              //echo "status1=".$status.'<BR>';
              if($status==0)
              {
                continue;
              }*/
              
              $status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
              //echo "<br>status2=".$status;
              /*if($status==0)
              {
                continue;               
              } */
              //echo "test6".'<BR>';
              
              $status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
              //echo "<br>status3=".$status;
              /*if($status==0)
              {
                continue;
              }*/     
                                       
              //echo "<br>Format=".$format;	
              //$status = preg_match('/io1="[^" ]+/', $line, $enginecount_tmp);
              //$str = "'/$io=\"[^\"]+/'";			  
			
			$io=get_io_to_new_method($userdates[$i],$old_xml_date,$io);			
			$status = preg_match('/'.$io.'=\"[^\"]+/', $line, $enginecount_tmp);
              /*if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
              {echo "<br>io=".$io." ,status=". $status;
             }*/
              //$status = preg_match('/'.$io.'="[^"]+/', $line, $enginecount_tmp);                        
              /*if($j<10)
              {
                echo '<textarea>'.$line.'</textarea><br>';
              }*/
              $j++;
              //echo "<br>status4=".$status;
              if($status==0)
              {
                continue;
              }
              
              //echo "<br>Engine test";
              $datetime = $xml_date;           
                          
              /*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);  
              $vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]); */                                           
			$vserial=$vehicle_serial;
              //$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);  
              //$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);                     
              
              $enginecount_tmp1 = explode("=",$enginecount_tmp[0]);  
              $engine_count = preg_replace('/"/', '', $enginecount_tmp1[1]);                                                                            	                         
              //echo "<br>enginecount=".$engine_count;
              
            	//$date_secs2 = strtotime($time2);                         	
              //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.15")
                //echo "<br>engine_count=". $engine_count." ,c=".$c." ,total_lines=".$total_lines;    			
				$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
				$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);

				$lat_tmp1 = explode("=",$lat_tmp[0]);  
				$lat = preg_replace('/"/', '', $lat_tmp1[1]);

				$lng_tmp1 = explode("=",$lng_tmp[0]);  
				$lng = preg_replace('/"/', '', $lng_tmp1[1]);
				
				
              if($engine_count>500)
              {
                $continuous_running_flag = 1;
              }
              
              if($engine_count>500 && $StartFlag==0)  //500
            	{                						
            		//echo "ONe";
					$time1 = $datetime;
					//$loc1 = get_location($lat_value,$lng_value,"-",&$placename,$DbConnection);
					//echo "<br>Lat=".$lat." lng_val=".$lng;
					get_location($lat,$lng,"-",&$loc1,$DbConnection);	
					//echo "<br>Loc1=".$loc1;					
            		$StartFlag = 1;
            	} 
            	else if( ($engine_count<500 && $StartFlag==1) || ( ($c==($total_lines-1)) && ($continuous_running_flag ==1) ) )   //500
            	{
            		//echo "Two";
					$StartFlag = 2;
            	}
				$time2 = $datetime;
            
            	if($StartFlag == 2)
            	{
            		//echo "Three";
					$StartFlag=0;
            		$runtime = strtotime($time2) - strtotime($time1);
            		if($runtime > 60)
            		{
						get_location($lat,$lng,"-",&$loc2,$DbConnection);	
						//echo "<br>runtime=".$runtime;
						//$runhr_duration = strtotime($runtime);
						/*$hr =  (int)(($runtime)/3600);	 
						//$runhr_duration = round($runhr_duration,2);
						$min =  ($runtime)%3600;
						$sec =  (int)(($min)%60);
						$min =  (int)(($min)/60); */
				  
						$engine_runhr_data = "\n< marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" loc1=\"".$loc1."\" dateto=\"".$time2."\" loc2=\"".$loc2."\"/>";						          						
						//echo "<br>".$engine_runhr_data;
						$linetowrite = $engine_runhr_data; // for distance       // ADD DISTANCE
						fwrite($fh, $linetowrite);
					} 
            	}                                     																                              										                               
  					}  // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed  			
        }   // while closed
      } // if original_tmp closed 
	  
  	 /* if($StartFlag == 1)
  	  {
    		$StartFlag=0;
    		$runtime = strtotime($time2) - strtotime($time1);
    		//echo "<br>runtime=".$runtime;
    		//$runhr_duration = strtotime($runtime);
    		if($runtime > 60)
    		{
      		//$engine_runhr_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" engine_runhr=\"".$hr.':'.$min.':'.$sec."\"/>";      		
      		$engine_runhr_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" engine_runhr=\"".$runtime."\"/>";
      		//echo "<br>".$engine_runhr_data;
      		$linetowrite = $engine_runhr_data; // for distance       // ADD DISTANCE
      		fwrite($fh, $linetowrite);
			} 
  	  }*/
          			
		fclose($xml);            
		 //unlink($xml_original_tmp);
		} // if (file_exists closed
		
		$previous_date = $userdates[$i]." 23:59:59";
	}  // for closed 	                		                 	
	
	//echo "Test1";
	fclose($fh);
}

	
$m1=date('M',mktime(0,0,0,$month,1));
  
  echo'<center>';   
  report_title("IO Trip Report",$date1,$date2);   
	echo'<div style="overflow: auto;height: 285px; width: 600px;" align="center">';
   
  ///////////////////  READ SPEED XML 	//////////////////////////////				                      
  $xml_path = $xmltowrite;
  //echo "<br>xml_path=".$xml_path;
	read_io_trip_xml($xml_path, &$imei, &$vname, &$datefrom, &$loc1, &$dateto, &$loc2);
	//convert_in_two_dimension
  //echo "<br><br>size, vname=".sizeof($imei).", dt=".$datefrom[0];
	//////////////////////////////////////////////////////////////////////
  			             
  $j=-1;
  $k=0;
    
  $single_data_flag=1;		
  		             
  for($i=0;$i<sizeof($imei);$i++)
	{	    						                  
    if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_engine_runhr =0;
      /*$sum_engine_runmin =0;
      $sum_engine_runsec =0; */
      $total_engine_runhr[$j] =0;
      
      $sno = 1;
      $title='IO Trip Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";;
      $vname1[$j][$k] = $vname[$i];
      $imei1[$j][$k] = $imei[$i];
      
      echo'
      <br><table align="center">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>
      <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
      <tr>
			<td class="text" align="left"><b>SNo</b></td>
			<td class="text" align="left"><b>Start Time </b></td>
			<td class="text" align="left"><b>Start Location</b></td>
			<td class="text" align="left"><b>End Time </b></td>
			<td class="text" align="left"><b>End Location</b></td>			
      </tr>';  								
    }                                                                        				
            		              
    echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
	echo'<td class="text" align="left">'.$datefrom[$i].'</td>';
	echo'<td class="text" align="left">'.$loc1[$i].'</td>';	
    echo'<td class="text" align="left">'.$dateto[$i].'</td>';
	echo'<td class="text" align="left">'.$loc2[$i].'</td>';						
	echo'</tr>';	          		
    
    $datefrom1[$j][$k] = $datefrom[$i];
	$loc1_1[$j][$k] = $loc1[$i];		
    $dateto1[$j][$k] = $dateto[$i];
	$loc2_1[$j][$k] = $loc2[$i];			
    //echo "<br>engine_run=".$engine_runhr1[$j][$k]." ,i=".$i." ,j=".$j." ,k=".$k;
    //echo "<br>imei[i+1]=".$imei[$i+1]." ,ime[i]=".$imei[$i];     			    				  				
	
	  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {       
        //echo "<br>IN";
        $single_data_flag = 0;
        /*echo '<tr style="height:20px;background-color:lightgrey">
        <td class="text"><strong>Total<strong>&nbsp;</td>
  		<td class="text"><strong>'.$date1.'</strong></td>	
        <td class="text"><strong>'.$date2.'</strong></td>
  		<td class="text"><strong></strong></td>';
		echo'</tr>'; */
        echo '</table>';
        
        $no_of_data[$j] = $k;        
			//}
      /*else
      {
        $hms_2 = secondsToTime($sum_engine_runhr);
        $total_engine_runhr[$j] = $hms_2[h].":".$hms_2[m].":".$hms_2[s];
         
         $total_engine_runhr[$j] = $engine_runhr[$i];
         echo'<td class="text"><font color="red"><strong>'.$total_engine_runhr[$j].'</strong></font></td>';
      }	*/																	
			
			//echo'</tr>'; 
      //echo '</table>';
      
      //$no_of_data[$j] = $k;
		}  
		
    //echo "<br>OUT";
    $k++;   
    $sno++;                       							  		
 }
 
 
/*if($single_data_flag)
{
  echo '<tr style="height:20px;background-color:lightgrey">
  <td class="text"><strong>Total<strong>&nbsp;</td>
  <td class="text"><strong>'.$date1.'</strong></td>	
  <td class="text"><strong>'.$date2.'</strong></td>
  <td class="text"><strong></strong></td>';  
  echo'</tr>'; 
  echo '</table>';
  
  $no_of_data[$j] = $k; 
}*/

 
	echo "</div>";     

 	echo'<form method = "post" target="_blank">';
	
	$csv_string = "";
	//echo "<br>j=".$j;
	for($x=0;$x<=$j;$x++)
	{								
		$title = $vname1[$x][0]." (".$imei1[$x][0]."): IO Trip Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
		$csv_string = $csv_string."SNo,Start Time, Start Location,End Time,End Location\n";
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";

		$sno=0;
		//echo "<br>noofdata=".$no_of_data[$x];
		
		for($y=0;$y<=$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;
                    
			$datetmp1 = $datefrom1[$x][$y];
			$loc1_tmp = $loc1_1[$x][$y];			
			$datetmp2 = $dateto1[$x][$y];										
			$loc2_tmp = $loc2_1[$x][$y];	
			//echo "<br>x=".$x." ,y=".$y." ,temp_runhr=".$engine_runhr_tmp;
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][Start Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$loc1_tmp\" NAME=\"temp[$x][$y][Start Location]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][End Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$loc2_tmp\" NAME=\"temp[$x][$y][End Location]\">";
      
			$csv_string = $csv_string.$sno.','.$datetmp1.','.$loc1_tmp.','.$datetmp2.','.$loc1_tmp."\n";         																	
		}		

		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Start Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Start Location]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][End Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][End Location]\">";									
		
		$m = $y+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][Start Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$loc1_tmp\" NAME=\"temp[$x][$m][Start Location]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][End Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$loc2_tmp\" NAME=\"temp[$x][$m][End Location]\">";
		$csv_string = $csv_string."\nTotal,".$date1.",".$loc1_tmp.",".$date2.",".$loc2_tmp."\n\n";       
	
    //if($y>1)
		//{        		
    //}																																										
	}																						
      
  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		if(sizeof($imei)==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>Sorry! No IO Trip Record Found</strong></font></center>";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="engine_runhr" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }
                  
      echo'</td>		
    </tr>
		</table>
		</form>
 ';
					 
unlink($xml_path);				 
                     
?>							 					
