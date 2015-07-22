<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];
set_time_limit(800);
include_once('common_xml_element.php');
include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("report_title.php");
include_once("read_filtered_xml.php");


$DEBUG = 0;

$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
//$vserial = explode(':',$device_str);
//$vsize=count($vserial);

$vserial = $device_str;

$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];

$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);

$user_interval = $_POST['user_interval'];

//echo "vsize=".$vsize;
///////////////////////////////////////////////////////////////////////////////
//if($vsize>0)
//{
  //$current_dt = date("Y_m_d_H_i_s");	
  //$timeparts = explode(" ",microtime());
  //$currenttime = bcadd(($timeparts[0]*1000),bcmul($timeparts[1],1000));
  //echo $currenttime;  
  $t=time();
  $xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$t.".xml";
  //echo "<br>xml1=".$xmltowrite;
    write_distance_report_xml($vserial, $date1, $date2, $user_interval, $xmltowrite,$root);
//}

function write_distance_report_xml($vserial, $startdate, $enddate, $user_interval, $xmltowrite,$root)
{
  global $DbConnection;
  
  $maxPoints = 1000;
	$file_exist = 0;

	$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);

	//$i=0;
	//echo "<br>xml2=".$xmltowrite;
	
	//for($i=0;$i<sizeof($vserial);$i++)
	//{  	
   /* $query1 = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial' AND status=1) AND status=1";
    //echo $query1;
    //echo "<br>DB=".$DbConnection;
    $result = mysql_query($query1,$DbConnection);
    $row = mysql_fetch_object($result);
    $vname = $row->vehicle_name;  */
	$vehicle_info=get_vehicle_info($root,$vserial);
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $vname = $vehicle_detail_local[0];
	 
		get_distance_xml_data($vserial, $vname, $startdate, $enddate, $user_interval, $xmltowrite);
		//echo   "t2".' '.$i;
	//}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_distance_xml_data($vehicle_serial, $vname, $startdate, $enddate, $user_interval, $xmltowrite)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
	//include('common_xml_element_for_function.php');
	//new_xml_variables();
	//echo "<br>vserial=".$vehicle_serial." ,vname=".$vname." ,st=".$startdate." ,ed=".$enddate;
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

	////date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	$j = 0;
	$total_dist = 0.0;
 									
  for($i=0;$i<=($date_size-1);$i++)
	{
	 	/*if($date_size==1)
	  {
      $startdate1 = $startdate;
      $enddate1 = $enddate;
    }
	  else if($i==0)
	  {
	     $startdate1 = $startdate;
	     $enddate1 = $userdates[$i]." 23:59:59";
    }
    else if($i==($date_size-1))
    {
      $startdate1 = $userdates[$i]." 00:00:00";
	    $enddate1 = $enddate;
    }
    else
    {
       $startdate1 = $userdates[$i]." 00:00:00";
	     $enddate1 = $userdates[$i]." 23:59:59";
    } */
    
    $startdate1 = $startdate;
    $enddate1 = $enddate;
      
    $xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    /*if($_SERVER["HTTP_X_FORWARDED_FOR"]=="172.26.48.189")
    {
      echo "stardate=".$startdate1." ,enddate1=".$enddate1." ,xml_current=".$xml_current;
    }*/
    		
    if (file_exists($xml_current))      
    {		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../../sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml in get_halt_xml_data =".$xml_file;	    	
    if (file_exists($xml_file)) 
		{			
		  //$current_datetime1 = date("Y_m_d_H_i_s");
      //$timeparts = explode(" ",microtime());
      //$currenttime = bcadd(($timeparts[0]*1000),bcmul($timeparts[1],1000));		  
      $t=time();
      $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
      //$xml_log = "xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
									      
      if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
        copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
        //$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$current_datetime1."_unsorted.xml";
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
      $c = -1;
      
      $f=0;
      
      if (file_exists($xml_original_tmp)) 
      {              
        $daily_dist =0;
        //echo "<br>exist original";
		
		set_master_variable($userdates[$i]);
        //set_master_variable($userdates[$i]);
        while(!feof($xml))          // WHILE LINE != NULL
      	{
      		$c++;
          $DataValid = 0;
          //echo "<br>line";
          //echo fgets($file). "<br />";
      		$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
      				
      		if(strlen($line)>20)
      		{
      			$linetmp =  $line;
          }
      				
      		$linetolog =  $logcnt." ".$line;
      		$logcnt++;
      		//fwrite($xmllog, $linetolog);
			
      
      		if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
      		{
      			$format = 1;
      			$fix_tmp = 1;
      		}
                    
      		else if(strpos($line,''.$vc.'="0"'))
      		{
      			$format = 1;
      			$fix_tmp = 0;
      		}			
  				
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
          if( ($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($DataValid == 1) )   // FIX_TMP =1 COMES IN BOTH CASE     
      		{					
      			$status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
      			$datetime_tmp1 = explode("=",$datetime_tmp[0]);
      			$datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
      			$xml_date = $datetime;
      		}				
          //echo "Final0=".$xml_date." datavalid=".$DataValid;
              
          if($xml_date!=null)
      		{				  
      			//echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
      			//$lat = $lat_value[1] ;
      			//$lng = $lng_value[1];    					
      			//echo "<br>xml_date=".$xml_date." ,end_date=".$enddate." ,data_valide=".$DataValid;
      			
            //if( ($xml_date >= $startdate1 && $xml_date <= $enddate1) && ($xml_date!="-") && ($DataValid==1) )
            if( ($xml_date >= $startdate1 && $xml_date <= $enddate1) && ($xml_date!="-") && ($DataValid==1) )
            //if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
      			{							           	
      				//echo "<br>One";             
      				/*$status = preg_match('/'.$vv.'="[^" ]+/', $line, $vehicleserial_tmp);
      				//echo "Status=".$status.'<BR>';
      				//echo "test1".'<BR>';
      				if($status==0)
      				{
      					continue;
      				}*/
      				//echo "<textarea>".$line."</textarea>"; 
      				//$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
      				//$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);          
      				//echo "<br>vname=".$vehiclename_tmp[0];
      				/*if($status==0)
      				{
      				  continue;
      				} */
                  
      				$status = preg_match('/'.$vd.'="[^" ]+/', $line, $lat_tmp);
      				if($status==0)
      				{
      				  continue;               
      				}
      				//echo "test6".'<BR>';
      				$status = preg_match('/'.$ve.'="[^" ]+/', $line, $lng_tmp);
      				if($status==0)
      				{
      				  continue;
      				}     
                                           
      				//echo "test4".'<BR>';
      				/*$status = preg_match('/datetime="[^" ]+/', $line, $datetime_tmp);
      				if($status==0)
      				{
      				  continue;
      				} */
                               
    					$lat_tmp1 = explode("=",$lat_tmp[0]);
    					$lat = preg_replace('/"/', '', $lat_tmp1[1]);
    
    					$lng_tmp1 = explode("=",$lng_tmp[0]);
    					$lng = preg_replace('/"/', '', $lng_tmp1[1]);
                      				
              //echo "<br>first=".$firstdata_flag;                                        
      				if($firstdata_flag==0)
      				{					
      					$firstdata_flag = 1;
      							
						$lat1 = $lat;
						$lng1 = $lng;

						//echo "<br>DateSec1 before=".$date_secs1." time_int=".$user_interval;
						$interval = (double)$user_interval*60;							

						$time1 = $datetime;					
						$date_secs1 = strtotime($time1);					
						//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
						$date_secs1 = (double)($date_secs1 + $interval); 
						$date_secs2 = 0;  
						$last_time1 = $datetime;
						$latlast = $lat;
						$lnglast =  $lng;
                //echo "<br>FirstData:".$date_secs1;                 	
      				}           	
      				//echo "<br>k2=".$k2."<br>";
                  	
      				else
      				{                           					
               // echo "<br>Total lines orig=".$total_lines." ,c=".$c;
						$time2 = $datetime;											
						$date_secs2 = strtotime($time2);	
						//echo "<br>Next".$date_secs2;

						/*$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
						$vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);*/
						$vserial=$vehicle_serial;
						//$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);
						//$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);														                                      													      					
						$lat2 = $lat;      				        					
						$lng2 = $lng;  

						calculate_distance($lat1, $lat2, $lng1, $lng2, &$distance);
						//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance." ,total_dist=".$total_dist;
						//echo "<br>dist=".$distance;			      					
						//if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
						//echo '<br>Time:'.$datetime.' ,lat1='.$lat1.' ,lng1='.$lng1.', lat2='.$lat2.' ,lng2='.$lng2.' ,dist='.$distance.' totaldist='.$total_dist;                         
						//if($distance>0.025)                               

						//echo "<br>DISTANCE=".$distance." ,DATETIME=".$datetime." ,last_time1=".$last_time1;

						$tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;

						calculate_distance($latlast, $lat2, $lnglast, $lng2, &$distance1);
						if($tmp_time_diff1>0)
						{
						$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
						$last_time1 = $datetime;
						$latlast = $lat2;
						$lnglast =  $lng2;
						}
						$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;

						/*echo "<br>tmp_time_diff1=".$tmp_time_diff1." ,tmp_speed=".$tmp_speed;                
						echo "<br>lat1=".$lat1." ,lat2=".$lng1;
						echo "<br>lng1=".$lat2." ,lng2=".$lng2; 
						echo "<br>tmp_time_diff=".$tmp_time_diff." , last_time=".$last_time; 
						echo "<br>daily_dist=".$daily_dist."<br>";  */             
                             
                if($tmp_speed<500.0 && $distance>0.1 && $tmp_time_diff>0.0)
      					{														
                  $total_dist = (double)( $total_dist + $distance );

                  $daily_dist= (double) ($daily_dist + $distance);	
                  $daily_dist = round($daily_dist,2);							                          
                  //echo "<br>daily_dist=".$daily_dist;
                                    	
                  //echo "<br>dist greater than 0.025: dist=".$total_dist." time2=".$time2;
      						$lat1 = $lat2;
      						$lng1 = $lng2;
      						$last_time = $datetime;
      						
                  //////// TMP VARIABLES TO CALCULATE LAST XML RECORD  //////
                  $vname_tmp  = $vname;
                  $vserial_tmp = $vserial;
                  $time1_tmp = $time1;
                  $time2_tmp = $time2;
                  $total_dist_tmp = $total_dist;
                  //echo "<br>distance=".$distance." ,total_dist=".$total_dist;    			
                  ////// TMP CLOSED	////////////////////////////////////////                  		    						
      					}
      					
      					//echo "<br>REACHED-2";

                if( ($date_secs2 >= $date_secs1) || ($f == $total_lines-5))
      					{	
                // echo "<br>IN DATESEC";                                                  						
      						$distance_data = "\n<marker vname=\"".$vname."\" imei=\"".$vserial."\" datefrom=\"".$time1."\" dateto=\"".$time2."\" distance=\"".$total_dist."\"/>";						          						
      						//echo "<br>distance_data=".$distance_data;
      						$linetowrite = $distance_data; // for distance       // ADD DISTANCE
      						fwrite($fh, $linetowrite);  		
      					
      						//reassign time1
      						$time1 = $datetime;
      						$date_secs1 = strtotime($time1);
      						$date_secs1 = (double)($date_secs1 + $interval);		    									    						    						
      						//echo "<br>datesec1=".$datetime;    						                  
                  $total_dist = 0.0;	 
                  
      						$lat1 = $lat2;
      						$lng1 = $lng2;
      						///////////////////////																
      					}  //if datesec2 
      					
      					//echo "<br>REACHED-3";		                                                                        									                               
      				}   // else closed    				          
                //echo "<br>REACHED-4";     				    				
      			} // $xml_date_current >= $startdate closed
      			   //echo "<br>REACHED-5";      			
       		}   // if xml_date!null closed
       		
       		//echo "<br>REACHED-6";
              		    		    		
          $j++;          
          $f++;
          
          //echo "<br>REACHED-7";
        }   // while closed
      } // if original_tmp closed     	
    	    	      				
      fclose($xml);            
		 unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	
	//echo "Test1";
	fclose($fh);
}

echo '<center>';
	  
  echo'<br>';
  report_title("Distance Report",$date1,$date2);
  
	echo'<div style="overflow: auto;height: 300px; width: 620px;" align="center">';
							
  ///////////////////  READ SPEED XML 	//////////////////////////////				                      
  $xml_path = $xmltowrite;            
  //echo "<br>xml_path=".$xml_path;
  read_distance_xml($xml_path, &$imei, &$vname, &$datefrom, &$dateto, &$distance);
	//convert_in_two_dimension
  //echo "<br><br>size, vname=".sizeof($vname);
	//////////////////////////////////////////////////////////////////////
  $j=-1;
  $k=0;
  			             
  for($i=0;$i<sizeof($imei);$i++)
	{								              
    if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_dist =0.0;
      $total_distance[$j] =0;
      
      $sno = 1;
      $title='Distance Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
      $vname1[$j][$k] = $vname[$i];
      $imei1[$j][$k] = $imei[$i];   
      //echo  "vname1=".$vname1[$j][$k]." j=".$j." k=".$k;
      
      echo'
      <br><table align="center">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>
      <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
      <tr>
			<td class="text" align="left"><b>SNo</b></td>
			<td class="text" align="left"><b>Start DateTime</b></td>
			<td class="text" align="left"><b>End DateTime</b></td>
			<td class="text" align="left"><b>Distance (km)</b></td>								
      </tr>';  								
    }                                                                        		
		
    $sum_dist = $sum_dist + $distance[$i];
	              
    echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$datefrom[$i].'</td>';		
    echo'<td class="text" align="left">'.$dateto[$i].'</td>';			
		echo'<td class="text" align="left">'.round($distance[$i],2).'</td>';					
		echo'</tr>';	          		
		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
    
    $datefrom1[$j][$k] = $datefrom[$i];	
    $dateto1[$j][$k] = $dateto[$i];										
    $distance1[$j][$k] = round($distance[$i],2);       			    				  				
	
	  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {
      echo '<tr style="height:20px;background-color:lightgrey">
      <td class="text"><strong>Total<strong>&nbsp;</td>
			<td class="text"><strong>'.$date1.'</strong></td>	
      <td class="text"><strong>'.$date2.'</strong></td>';									
      
			if($k>0)
			{
				//echo  "<br>sum_avgspeed=".$sum_avgspeed."<br>";
        $total_distance[$j] = round($sum_dist,2);
        //echo  "<br>total_avgspeed[$j]=".$total_avgspeed[$j]."<br>";
			}
													
			echo'<td class="text"><font color="red"><strong>'.round($total_distance[$j],2).'</strong></font></td>';
			echo'</tr>'; 
      echo '</table>';
      
      $no_of_data[$j] = $k;
		}  
		
    $k++;   
    $sno++;                       							  		
 }
 
  echo "</div>";     

	echo'<form method = "post" target="_blank">';
	
  $csv_string = "";
	
  for($x=0;$x<=$j;$x++)
	{								
		$title = $vname1[$x][0]." (".$imei1[$x][0]."): Distance Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
    $csv_string = $csv_string."SNo,Start DateTime,End DateTime,Distance (km)\n";
    echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		for($y=0;$y<=$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;
                    
      $datetmp1 = $datefrom1[$x][$y];	
			$datetmp2 = $dateto1[$x][$y];										
			$disttmp = $distance1[$x][$y];
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][DateTime From]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][DateTime To]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$disttmp\" NAME=\"temp[$x][$y][Distance (km)]\">";
      
      $csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$disttmp."\n";      																	
		}		

		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Distance (km)]\">";									
		
		$m = $y+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][DateTime From]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][DateTime To]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$total_distance[$x]\" NAME=\"temp[$x][$m][Distance (km)]\">";
    $csv_string = $csv_string.'Total,'.$date1.','.$date2.','.$total_distance[$x]."\n";																																										
	}																						

      
  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		$vsize = sizeof($imei);
  		
      if($vsize==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>No Distance Record</strong></font></center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="distance" NAME="csv_type">';
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
					 	
echo '</center>';			 
?>
