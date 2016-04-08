<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];

include_once('lib/BUG.php');
include_once('lib/Util.php');
include_once('lib/VTSFuel.php');
include_once("report_title.php");
include_once('lib/VTSMySQL.php'); 

set_time_limit(500);

include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("read_filtered_xml.php");
include_once("get_location.php");
include_once("report_title.php");
include_once("user_type_setting.php");
include_once("select_landmark_report.php");

include_once("area_violation/check_with_range.php");
include_once("area_violation/pointLocation.php");

//echo "<br><center><strong>This page is under updation</strong></center>";
$DEBUG = 0;

$v_size=count($vehicle_serial);       // VEHICLE IDS IN THIS CASE


if($DEBUG) echo "vsize=".$v_size;

$device_str= $_POST["vehicleserial"];
$vserial = explode(':',$device_str);        // VEHICLE IDS

//echo "<br>device_str=".$device_str;

$geo_id_str= $_POST["geo_id"];
$geo_id1 = explode(':',$geo_id_str);

//echo "geo_id_str=".$geo_id_str." ,size=".sizeof($geo_id1)."<br>";

//for($i=0;$i<sizeof($geo_id1);$i++)
   //echo "<br>value:".$geo_id1[$i];
//echo $vserial[0];
//$vehicleid_size=sizeof($vehicleid);

$date1 = $_POST["start_date"];
$date2 =  $_POST["end_date"];

$date1 = str_replace('/', '-', $date1);  
$date2 = str_replace('/', '-', $date2); 

$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);

$datefrom = $date_1[0];
$dateto = $date_2[0];

//echo "<br>datefrom=".$datefrom." dateto=".$dateto;
$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);

//date_default_timezone_set("Asia/Calcutta");
$current_date = date("Y-m-d");
//print "<br>CurrentDate=".$current_date;
//$date_size = sizeof($userdates);
//echo "<br>datesize=".$date_size."<br> v_size=".$v_size;
$k1 = 0;
$m_start=0;

$date1tmp = strtotime($date1);
$date2tmp = strtotime($date2);


$user_interval = $_POST['user_interval'];

for($i=0;$i<sizeof($vserial);$i++)
{
   /*$query = "SELECT device_imei_no,vehicle_name FROM vehicle,vehicle_assignment WHERE ".
    " vehicle.vehicle_id = vehicle_assignment.vehicle_id ".
    "AND vehicle.vehicle_id='$vserial[$i]' AND vehicle.status=1 AND vehicle_assignment.status=1";
    //echo $query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $imei[$i] = $row->device_imei_no;
    $vid[$i] = $vserial[$i];
    $vname[$i] = $row->vehicle_name;*/
    $vehicle_info=single_account_vehicle_info($root,$vserial[$i],$account_id);
    $vehicle_detail_local=explode(",",$vehicle_info);
    $imei[$i] = $vserial[$i];
    $vid[$i] = $vserial[$i];
    $vname[$i] = $vehicle_detail_local[0];    
}

$current_dt = date("Y_m_d_H_i_s");	
$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";

write_halt_report_xml($vid, $imei, $vname, $date1, $date2, $user_interval, $xmltowrite);

function write_halt_report_xml($vid, $imei, $vname, $startdate, $enddate, $user_interval, $xmltowrite)
{
    $maxPoints = 1000;
    $file_exist = 0;

    $fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
    fwrite($fh, "<t1>");  
    fclose($fh);

	//$i=0;
	for($i=0;$i<sizeof($imei);$i++)
	{  	
     //echo   "<br>vserial[i] =".$vserial[$i];
     get_halt_xml_data($vid[$i], $imei[$i], $vname[$i], $startdate,$enddate,$user_interval, $xmltowrite);
    //echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_halt_xml_data($vid, $vehicle_serial, $vname, $startdate,$enddate,$user_interval, $xmltowrite)
{
	global $DbConnection;
	global $account_id;
	global $geo_id1;
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

	$fuel_distance = 0;
	$fuel_distance_flag = 0;
	$lat_ref_prev = 0;
  $lng_ref_prev = 0; 
	
  for($i=0;$i<=($date_size-1);$i++)
	{
		//if($userdates[$i] == $current_date)
		//{	
    $xml_current = "../../../xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	
    		
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
      
      $f=0;
      
      if (file_exists($xml_original_tmp)) 
      {      
        while(!feof($xml))          // WHILE LINE != NULL
  			{
  				$DataValid = 0;
          //echo fgets($file). "<br />";
  				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
  				
  				if(strlen($line)>20)
  				{
  				  $linetmp =  $line;
          }
  				
  				$linetolog =  $logcnt." ".$line;
  				$logcnt++;
  				//fwrite($xmllog, $linetolog);
  
  				if(strpos($line,'Fix="1"'))     // RETURN FALSE IF NOT FOUND
  				{
  					$format = 1;
            $fix_tmp = 1;
  				}
                
  				if(strpos($line,'Fix="0"'))
  				{
  				  $format = 1;
  					$fix_tmp = 0;
  				}
  				else
  				{
  				  $format = 2;
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
  					//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
  					//echo "<br>str3tmp[0]=".$str3tmp[0];
  					$status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
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
  					
  					if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
  					{							           	
                //echo "<br>One";             
                $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
                //echo "Status=".$status.'<BR>';
                //echo "test1".'<BR>';
                if($status==0)
                {
                  continue;
                }
                //echo "<textarea>".$line."</textarea>"; 
                //$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
                /*$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);          
                //echo "<br>vname=".$vehiclename_tmp[0];
                if($status==0)
                {
                  continue;
                } */
                
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
                
                $status = preg_match('/speed="[^" ]+/', $line, $speed_tmp);
                if($status==0)
                {
                  continue;
                }                           
                //echo "test4".'<BR>';
                /*$status = preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $datetime_tmp);
                if($status==0)
                {
                  continue;
                }*/ 
                
                // HALT LOGIC   /////////////                             
                //echo "<br>first=".$firstdata_flag;
                if($firstdata_flag==0)
                {
                  //echo "<br>FirstData";
                  $halt_flag = 0;
                  $firstdata_flag = 1;
                  
                  $vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
                  $vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);
                  
                  //$vehiclename_tmp1 = explode("=",$vehiclename_tmp[0]);
                  //$vname = preg_replace('/"/', '', $vehiclename_tmp1[1]);
                  //echo "<br>vname=".$vehiclename_tmp1[1];
    
                  $lat_tmp1 = explode("=",$lat_tmp[0]);
                  //echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1;
                  $lat_ref = preg_replace('/"/', '', $lat_tmp1[1]);
    
                  $lng_tmp1 = explode("=",$lng_tmp[0]);
                  $lng_ref = preg_replace('/"/', '', $lng_tmp1[1]);
                  
                  //$datetime_tmp1 = explode("=",$datetime_tmp[0]);
                  $datetime_ref = $datetime;
                  
                  //echo "<br>datetime_ref=".$datetime_ref." ,dt0=".$datetime_tmp[0];                  	
                	$date_secs1 = strtotime($datetime_ref);
                	//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
                	$date_secs1 = (double)($date_secs1 + $interval);  
                  
                  //echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1[1];             	
              	}           	
              	//echo "<br>k2=".$k2."<br>";                	
                else
                {           
                  //echo "<br>Next";               
                  $lat_tmp1 = explode("=",$lat_tmp[0]);                           //  GET NEXT RECO
                  $lat_cr = preg_replace('/"/', '', $lat_tmp1[1]);
    
                  $lng_tmp1 = explode("=",$lng_tmp[0]);
                  $lng_cr = preg_replace('/"/', '', $lng_tmp1[1]);
    
                  //$datetime_tmp1 = explode("=",$datetime_tmp[0]);
                  $datetime_cr = $datetime;
                		               
              		//$time2 = $datetime[$i][$j];											
              		$date_secs2 = strtotime($datetime_cr);
                		
              		//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;
              		calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
                	
            			
                  /*if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.164")
                  {
                    echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";
                  }*/  
                                                   
                  /////////////********** TOTAL FUEL DISTANCE CODE ///////////
                  if($fuel_distance_flag ==1)
                  {
                    calculate_distance($lat_ref_prev, $lat_cr, $lng_ref_prev, $lng_cr, &$dist_tmp);
                    
                    $tmp_time_diff1 = (strtotime($datetime) - strtotime($last_time1)) / 3600;
                    if($tmp_time_diff1>0)
                    {
                      /*if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.164")
                      {
                        echo "<br>dist=".$dist_tmp." ,last_time=".$last_time." ,last_time1=".$last_time1." ,datetime=".$datetime;
                      }*/
                      
                      /*if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.164")
                      {
                        echo "<br>Next: Dist=".$dist_tmp." ,lat_ref_prev=".$lat_ref_prev." ,lng_ref_prev=".$lng_ref_prev." lat_cr=".$lat_cr." ,lng_cr=".$lng_cr." ,datetime=".$datetime;
                      }*/                        
                                                        	
                      $tmp_speed = $dist_tmp / $tmp_time_diff1;
                    	$last_time1 = $datetime;
                    }
                    $tmp_time_diff = (strtotime($datetime) - strtotime($last_time)) / 3600;
                    
                    
                    if($tmp_speed<500 && $dist_tmp>0.1 && $tmp_time_diff>0)
                    {                                                                     
                      $fuel_distance_flag =1;
                      $lat_ref_prev = $lat_cr;
                      $lng_ref_prev = $lng_cr;
                      $fuel_distance = $fuel_distance + $dist_tmp;                                    
                                                            
                      $last_time = $datetime;
                    } 
                  }                  
                  ////////////////******************************* ////////////
                  
                  if( ($distance > 0.200) || ($f== $total_lines-2) )
            			{
            				//echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";
            				if ($halt_flag == 1)
            				{				
              					//echo "<br>In Halt1";
              					//echo "<br>datetime_ref=".$datetime_ref;
                        $arrivale_time=$datetime_ref;
              					$starttime = strtotime($datetime_ref);
              					
              					//$stoptime = strtotime($datetime_cr);  
              					$stoptime = strtotime($datetime_cr);
              					$depature_time=$datetime_cr;
              					//echo "<br>".$starttime." ,".$stoptime;
              					
              					$halt_dur =  ($stoptime - $starttime)/3600;
              				
              					$halt_duration = round($halt_dur,2);										
              					$total_min = $halt_duration * 60;
              
              					$total_min1 = $total_min;
              					
              					//echo "<br>toatal_min=".$total_min1."user-interval=".$user_interval;
              
              					$hr = (int)($total_min / 60);
              					$minutes = $total_min % 60;										
              
              					$hrs_min = $hr.".".$minutes;
              					              					
              					if( ($total_min1 >= $user_interval) || ($f== $total_lines-2))
              					{
                            //echo "<br>In Halt2";
                            //echo "<br>sizeof geoc:".sizeof($geo_id);                            
                            //echo "<br> geo_id:size:".sizeof($geo_id1)." ,geoid=".$geo_id1[0];
                            if( (sizeof($geo_id1)>0) && ($geo_id1[0]!="") )
                            {                                                                                            
                                $exclude_flag = 1;
                                $geo_status = 1;
                                
                                for($j=0;$j<sizeof($geo_id1);$j++)
                                {                                                                                                    
                                    include('action_halt_exclusion.php');
                                    
                                    if($geo_coord!="")
                        		        {                
                                      check_with_range($lat_ref, $lng_ref, $geo_coord, &$geo_status);
                                      //echo "<Br>geo_status1:".$geo_status;                                        
                                    }
                                    if($geo_status == 1)
                                    {
                                      $exclude_flag = 0;
                                    }                                                                                          
                                    //echo "<br>geo_status=".$geo_status;                                
                                    //echo "<br>geo_id[i]=".$geo_id1[$j]." ,geo_status=".$geo_status;                                      
                                }	// FOR LOOP 
                                
                                //echo "<BR>geo_status2:".$geo_status." ,exclude_flag:".$exclude_flag;
                                
                                if(($geo_status==false) && ($exclude_flag==1))
                                {                                                														
                                  //echo "<br>Print:".$lat_ref.",".$lng_ref;
                                  include("report_fuel_module.php");
                                  
                                  if($fuel_distance_flag == 0)
                                  {
                                    $fuel_distance_flag =1;
                                    $fuel_distance =0;
                                    $lat_ref_prev = $lat_ref;
                                    $lng_ref_prev = $lng_ref;
                                    $last_time1 = $datetime;
                                  }                                                                                         
                                  
                                  /*$fuel_io_global1 = VTSFuel::$fuel_io_global;
                                  $max_fuel_io1 = VTSFuel::$max_fuel_io;
                                  $max_fuel_value1 = VTSFuel::$max_fuel_value;
                                  
                                  if( ($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189")
                                  {
                                    echo "<br>fuel_tmp=".$fuel_tmp." ,max_fuel_io1:".$max_fuel_io1;
                                  }
                                  $fuel_tmp = UTIL::getXMLData('/'.$fuel_io_global1.'="[^"]+"/', $line);
                                                                  
                                  if($fuel_tmp > $max_fuel_io1)
                                  {
                                    $fuel_data = $max_fuel_value1;
                                  }*/
                                  //echo "<br>maxcap1=".VTSFuel::$max_capacity;                                  
                                  $total_halt_vehicle = "\n<marker vid=\"".$vid."\" imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" duration=\"".$hrs_min."\" fuel_data=\"".$fuel_data."\" fuel_dist=\"".$fuel_distance."\"/>";						          						
                                  //echo "<br>total halt vehicle=".$total_halt_vehicle;
                                  $linetowrite = $total_halt_vehicle; // for distance       // ADD DISTANCE
                                  fwrite($fh, $linetowrite);  
                                  
                            			$date_secs1 = strtotime($datetime_cr);
                            			$date_secs1 = (double)($date_secs1 + $interval);
                            			//break;
                                }  // IF STATUS   
                                                             
                             } // SIZE OF GEO_ID
                             else
                             {
                                //echo "<br>In Halt else";
                                include("report_fuel_module.php");
                                
                                if($fuel_distance_flag == 0)
                                {
                                  $fuel_distance_flag =1;
                                  $fuel_distance =0;
                                  $lat_ref_prev = $lat_ref;
                                  $lng_ref_prev = $lng_ref;
                                  $last_time1 = $datetime;
                                  
                                  /*if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.164")
                                  {
                                    echo "<br>FirstData: lat_ref_prev=".$lat_ref_prev." ,lng_ref_prev=".$lng_ref_prev." ,datetime=".$datetime;
                                  }*/                                   
                                }
                                
                                /*$fuel_io_global1 = VTSFuel::$fuel_io_global;
                                $max_fuel_io1 = VTSFuel::$max_fuel_io;
                                $max_fuel_value1 = VTSFuel::$max_fuel_value;
                                                                
                                if( ($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189")
                                {
                                  echo "<br>fuel_total=".$fuel_total." ,fcount:".$fcount;
                                  echo "<br>fuel_tmp=".$fuel_tmp." ,max_fuel_io1:".$max_fuel_io1;
                                  echo "<br>fuel_data=".$fuel_data."<br>";
                                }*/
                                
                                
                                /*$fuel_tmp = UTIL::getXMLData('/'.$fuel_io_global1.'="[^"]+"/', $line);
                                                                
                                if($fuel_tmp > $max_fuel_io1)
                                {
                                  $fuel_data = $max_fuel_value1;
                                }*/                                                                                                                       
                                //echo "<br>maxcap2=".VTSFuel::$max_capacity;
                                //$max_count = VTSFuel::$max_capacity;
                                
                                $total_halt_vehicle = "\n<marker vid=\"".$vid."\" imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" duration=\"".$hrs_min."\" fuel_data=\"".$fuel_data."\" fuel_dist=\"".$fuel_distance."\"/>";						          						
                                //echo "<br>total halt vehicle=".$total_halt_vehicle;
                                $linetowrite = $total_halt_vehicle; // for distance       // ADD DISTANCE
                                fwrite($fh, $linetowrite);  
                                
                          			$date_secs1 = strtotime($datetime_cr);
                          			$date_secs1 = (double)($date_secs1 + $interval);                              
                             }  //ELSE CLOSED                      
                        }		// IF TOTAL MIN										
              			}   //IF HALT FLAG
              			
            				$lat_ref = $lat_cr;
            				$lng_ref = $lng_cr;
            				$datetime_ref= $datetime_cr;
            				
            				$halt_flag = 0;
              		}
            			else
            			{            			
                      //echo "<br>normal flag set";
                      $halt_flag = 1;
            			}					                              
                 // HALT LOGIC CLOSED 
                  //echo "<br>total halt vehicle=".$total_halt_vehicle;
                }
  					} // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed
  				$f++;
  			}   // while closed
      } // if original_tmp closed 
      
      fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	
	//echo "Test1";
	fclose($fh);
	//fclose($xmllog);
}                                                                                                                                                           
	
//////////// TEST CLOSED

echo '<center>';
    				
    include("map_window/mapwindow_jsmodule.php");		
    include("map_window/floating_map_window.php");
    
    $size_vserial = sizeof($vserial);
        			
		echo'<br>';
		$param1 = $date1;
		$param2 = $date2." &nbsp;-Interval:".$user_interval." mins";
		
		report_title("Fuel Halt Report",$param1,$param2);
		
    echo'<div style="overflow: auto;height: 450px;" align="center">';
       
		$alt ="-";
						
    ///////////////////  READ HALT XML 	//////////////////////////////				                      
    $xml_path = $xmltowrite;
    //echo "<br>xml_path=".$xml_path;
		read_fuel_halt_xml($xml_path, &$vid, &$imei_xml, &$vname_xml, &$lat, &$lng, &$arr_time, &$dep_time, &$duration, &$fuel_data, &$fuel_dist);
		//convert_in_two_dimension
    //echo "<br><br>size, vname=".sizeof($vname);
		//////////////////////////////////////////////////////////////////////
    $j=-1;
    
    $imei_xml_size = sizeof($imei_xml);
    
    for($i=0;$i<$imei_xml_size;$i++)
		{				
      /*echo "<br>a".$i."=".$vname[$i];
      echo "<br>lat".$i."=".$lat[$i];
      echo "<br>lng".$i."=".$lng[$i];
      echo "<br>arrival_time".$i."=".$arrival_time[$i];
      echo "<br>dep_time".$i."=".$dep_time[$i];
      echo "<br>duration".$i."=".$duration[$i]; */
            
      if(($i==0) || (($i>0) && ($imei_xml[$i-1] != $imei_xml[$i])) )      
      {
        $k=0;
        $j++;
        $sno = 1;
        $title="Fuel Halt Report : ".$vname_xml[$i]." &nbsp;<font color=red>(".$imei_xml[$i].")</font>-Interval:".$user_interval." mins";
        $vname_xml1[$j][$k] = $vname_xml[$i];
        $imei_xml1[$j][$k] = $imei_xml[$i];
        
        echo'
        <br><table align="center">
        <tr>
        	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
        </tr>
        </table>
        <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
        <tr>
        	<td class="text" align="left" width="4%"><b>SNo</b></td>
        	<td class="text" align="left"><b>Location</b></td>
        	<td class="text" align="left"><b>Start Time</b></td>
        	<td class="text" align="left"><b>Stop Time</b></td>
        	<td class="text" align="left"><b>Halt Duration (Hrs.min)</b></td>
        	<td class="text" align="left"><b>Fuel</b></td>
        	<td class="text" align="left"><b>Distance</b></td>
        </tr>';  								
      }
            							                    
      // FUEL MODULE
      
      //include("report_fuel_module.php");      
      //
      
      /*if($report_type=='Person')
      {
        ///// 1.CONVERT DATE TIME IN DD, MM, YYYY FORMA
        $datestr = explode(' ',$arr_time[$i]);
        $date_tmp = $datestr[0];
        $time_tmp = $datestr[1];
        
        $date_substr = explode('-',$date_tmp);
        $year = $date_substr[0];
        $month = $date_substr[1];
        $day = $date_substr[2];
        
        $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
        $arr_time[$i] = $display_datetime;
        
        ///// 2.CONVERT DATE TIME IN DD, MM, YYYY FORMA
        $datestr = explode(' ',$dep_time[$i]);
        $date_tmp = $datestr[0];
        $time_tmp = $datestr[1];
        
        $date_substr = explode('-',$date_tmp);
        $year = $date_substr[0];
        $month = $date_substr[1];
        $day = $date_substr[2];
        
        $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
        $dep_time[$i] = $display_datetime;                
        ///////////////////////////////////////////////      
      }*/
            
     	//include("get_location_test.php");      
              	
			$lt1 = $lat[$i];
			$lng1 = $lng[$i];
			$alt1 = "-";								
			 
      $landmark="";
      get_landmark($lt1,$lng1,&$landmark);    // CALL LANDMARK FUNCTION
  		
      $place = $landmark;
      
      if($place=="")
      {
        get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
      }
  		
      //echo "P:".$place;
      
      $placename[$i] = $place;	
																
			echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';
												
			if($placename[$i]=="")
			{
				echo'<td class="text" align="left">&nbsp;</td>';
			}
			else
			{																													
				//echo "lat=".$latitude[$j];
        $lt_tmp = substr($lat[$i], 0, -1);
        $lng_tmp = substr($lng[$i], 0, -1);
        $type = "halt";
        echo'<td class="text" align="left">'.$placename[$i].'&nbsp;<a href="javascript:map_window(\''.$vname_xml[$i].'\',\''.$arr_time[$i].'\',\''.$dep_time[$i].'\',\''.$lt_tmp.'\',\''.$lng_tmp.'\',\''.$type.'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';        
			}									
			
			$fuel_dist[$i] = round($fuel_dist[$i],2);
			
      echo'<td class="text" align="left">'.$arr_time[$i].'</td>';			
			echo'<td class="text" align="left">'.$dep_time[$i].'</td>';
			echo'<td class="text" align="left"><b>'.$duration[$i].'</b></td>';
			echo'<td class="text" align="left"><font color=blue><strong>'.$fuel_data[$i].'</strong></font></td>';
      echo'<td class="text" align="left"><b>'.$fuel_dist[$i].'</b></td>';						
			echo'</tr>';							
			
			$placename1[$j][$k] = $placename[$i];
			$arr_time1[$j][$k] =  $arr_time[$i];					
			$dep_time1[$j][$k] = $dep_time[$i];	            
			$duration1[$j][$k] = $duration[$i];
			$fuel1[$j][$k] = $fuel_data[$i];
			$fuel_dist1[$j][$k] = $fuel_dist[$i];
			//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
			
      //echo '<br>i='.$i.' , imei[1]:'.$imei[1].' ,imei[1+1]='.$imei[$i+1];
      if( (($i>0) && ($imei_xml[$i+1] != $imei_xml[$i])) )       
      {
        $no_of_data[$j] = $k;
        echo '</table>';
      }    	
  			
			$k++;   
      $sno++;      				  				
		}
		
		//echo "j=".$j;
    
    if($j==0)
    {      
      $no_of_data[$j] = $k-1;
      echo '</table>';
    }							
		//PDF CODE

		//echo "<br>one";
    echo '<form method="post" target="_blank">';
		
    $csv_string = "";    
          
    for($x=0;$x<=$j;$x++)
		{												
        //echo "<br>x=".$x;
        for($y=0;$y<=$no_of_data[$x];$y++)
        {
         
          $alt_ref="-";
          
          //echo "<br>arr_time1[$x][$y]=".$arr_time1[$x][$y];          
          $pdf_place_ref = $placename1[$x][$y];
          $pdf_arrival_time = $arr_time1[$x][$y];
          $pdf_depature_time= $dep_time1[$x][$y];
          $pdf_halt_duration = $duration1[$x][$y];
          $pdf_fuel = $fuel1[$x][$y];
          $pdf_fuel_dist = $fuel_dist1[$x][$y];             
                                                  
          //echo "<br>data in pdf:".$pdf_place_ref." : ".$pdf_arrival_time." : ".$pdf_depature_time." : ".$pdf_halt_duration." : ".$pdf_fuel;
          //echo "<br>y1=".$y;
          if($y==0)
          {
          	//echo '<br>in y';
            $title="Fuel Halt Report : ".$vname_xml1[$x][$y]." (".$imei_xml1[$x][$y].")-Interval:".$user_interval." mins";
          	//echo "<br>title:".$title;
          	$csv_string = $csv_string.$title."\n";
          	$csv_string = $csv_string."SNo,Place,Start Time,Stop Time,Halt Duration (Hrs.min), Fuel, Distance\n";
          	
            echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
          }
          														
          $sno_1 = $y+1;										
          //echo "<br>before";
          echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_arrival_time\" NAME=\"temp[$x][$y][ArrivalTime]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_place_ref\" NAME=\"temp[$x][$y][Place]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_depature_time\" NAME=\"temp[$x][$y][DepartureTime]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_halt_duration\" NAME=\"temp[$x][$y][Duration of Halt (hr.min)]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_fuel\" NAME=\"temp[$x][$y][Fuel]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_fuel_dist\" NAME=\"temp[$x][$y][Distance]\">";
                
          /// CODE FOR CSV
          $pdf_place_ref = str_replace(',',':',$pdf_place_ref);
          //echo "<br>plc:".$pdf_place_ref;
          $csv_string = $csv_string.$sno_1.','.$pdf_place_ref.','.$pdf_arrival_time.','.$pdf_depature_time.','.$pdf_halt_duration.','.$pdf_fuel.','.$pdf_fuel_dist."\n"; 
          //echo "<br>csv=".$csv_string;
          ////////////////////////////////////         	
        }	//inner for	
		} // outer for		
	
    //echo "<br>two";	
    
		//echo "imei:".$imei_xml_size;
		
    if($imei_xml_size ==0)
		{						
			print"<center><FONT color=\"Red\" size=2><strong>No Fuel Halt Found</strong></font></center>";
			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
			echo'<br><br>';
		}	
		else
		{
      echo'<input TYPE="hidden" VALUE="Fuel" NAME="csv_type">';
      echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
      echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$imei_xml_size.'\');" value="Get PDF" class="noprint">&nbsp;
      <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
      <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
    }
   	echo '</form>';


		echo'</td></tr></table>';   
    echo '</div>';
    
    /*if($report_type=='Person')
    {
      echo'<form method = "post" name="csv_form" action="src/php/report_csv.php" target="_blank">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';        
      echo '</form>';
    }	*/     
    
unlink($xml_path);    

echo '</center>';      	
?>					
