<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];

set_time_limit(300);
include_once('common_xml_element.php');
include_once("get_all_dates_between.php");
include_once("sort_xml.php");
include_once("calculate_distance.php");
include_once("read_filtered_xml.php");
include_once("get_location.php");
include_once("report_title.php");
include_once("user_type_setting.php");
include_once("select_landmark_report.php");

include_once("tripinfo/check_with_range.php");
include_once("tripinfo/pointLocation.php");


$DEBUG = 0;

$v_size=count($vehicle_serial);

if($DEBUG) echo "vsize=".$v_size;

$device_str= $_POST["vehicleserial"];
$vserial = explode(':',$device_str);

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
   /* $query = "SELECT vehicle_name FROM vehicle WHERE ".
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

write_sector_report_xml($vserial, $vname, $date1, $date2, $user_interval, $xmltowrite);

function write_sector_report_xml($vserial, $vname, $startdate, $enddate, $user_interval, $xmltowrite)
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
     get_sector_xml_data($vserial[$i], $vname[$i], $startdate,$enddate,$user_interval, $xmltowrite);
    //echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_sector_xml_data($vehicle_serial, $vname, $startdate,$enddate,$user_interval, $xmltowrite)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
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
      
      $f=0;
      
      if (file_exists($xml_original_tmp)) 
      {
          // GET ROUTE AND SECTOR DETAIL
  				get_route_detail($vehicle_serial,&$route_id,&$route_name,$DbConnection);
  					
  				//echo "<br>route=".$route_id." ,name=".$route_name;
  				
          if($route_id && $route_name)
          {                                                                                            
            //echo "<br>In route_id";
            // GET SECTOR IDS, SECTOR NAMES
					   get_sorted_sector_coords($route_id,&$sector_coord,&$sector_name,$DbConnection);                								 
					                								
             for($y=0;$y<sizeof($sector_name);$y++)     // loop through sectors
             {
              //echo "<br><br>In sorted sector";
              $include_flag = 0;
              $include_status = 0;
                                                                                                                                 
  						//include('action_sector_inclusion.php');    //                  								
            	//$test = "MjEuMDIyMjgzMzk0MzYyNTA3IDg1Ljc0NjcwODUxMjMwNjIxLDIxLjAyMTM3NDU1NDM2ODkxNSA4NS43NDczOTc4NDAwMjMwNCwyMS4wMjIyMTU3OTQ4ODQyODYgODUuNzQ4NzY1NzY2NjIwNjQsMjEuMDIzMzM3NDQxNTIwOTE2IDg1Ljc1MDU5MjM1MDk1OTc4LDIxLjAyNDExMzU3NTk5NDU1NSA4NS43NDk4MDkxNDU5Mjc0MywyMS4wMjMxMzcxNDgwOTc1ODIgODUuNzQ4MDc5MTIxMTEyODI=";
              //$test1 = base64_decode($test);
              //echo "<br>TEST=".$test1."<br>";
              
              $sector_coord1 = base64_decode($sector_coord[$y]);              //  DECODE SECTOR COORD
            	//echo "<br>orignal_coord=".$sector_coord1;	
              
              $sector_coord1 = str_replace('),(',' ',$sector_coord1);
              //echo "<br>step1=".$sector_coord1;	
              $sector_coord1 = str_replace('(','',$sector_coord1);
              //echo "<br>step2=".$sector_coord1;	
              $sector_coord1 = str_replace(')','',$sector_coord1);
              //echo "<br>step3=".$sector_coord1;	
              $sector_coord1 = str_replace(', ',',',$sector_coord1);
              //echo "<br>step4=".$sector_coord1;
              
              $sector_coord1 = str_replace(',',':',$sector_coord1);
              //echo "<br>step5=".$sector_coord1;                                                
              $sector_coord1 = str_replace(' ',',',$sector_coord1);
              //echo "<br>step6=".$sector_coord1;
              $sector_coord_decoded[$y] = str_replace(':',' ',$sector_coord1);
              //echo "<br>sector_name=".$sector_name[$y];
              //echo "<br>sector=".$sector_coord_decoded[$y];        
             }
           } 
         // GET ROUTE ID AND SECTOR DETAIL CLOSED       
      
            set_master_variable($userdates[$i]);
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
				
  				if(strpos($line,''.$vc.'="1"'))     // RETURN FALSE IF NOT FOUND
  				{
  					$format = 1;
            $fix_tmp = 1;
  				}
                
  				if(strpos($line,''.$vc.'="0"'))
  				{
  				  $format = 1;
  					$fix_tmp = 0;
  				}
  				else
  				{
  				  $format = 2;
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
  					//preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
  					//echo "<br>str3tmp[0]=".$str3tmp[0];
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
  					
  					if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
  					{							           	
                //echo "<br>One";             
               /*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
                //echo "Status=".$status.'<BR>';
                //echo "test1".'<BR>';
                if($status==0)
                {
                  continue;
                }*/
                //echo "<textarea>".$line."</textarea>"; 
                //$line = '<marker msgtype="NORMAL" vehicleserial="354776033544005" vehiclename="SJ DKL-4005" ver="v1.34" fix="1" lat="26.60652N" lng="80.20758E" alt="123.0" speed="0.000000" datetime="2011-01-25 03:16:23" fuel="-" vehicletype="Heavy" no_of_sat="09" cellname="" distance="0.0" io1="7" io2="6" io3="387" io4="10" io5="6" io6="6" io7="4" io8="2" sig_str="0" sup_v="0.000000" speed_a="0" geo_in_a="0" geo_out_a="0" stop_a="0" move_a="0" lowv_a="0"/>';  
                /*$status = preg_match('/vehiclename="[^"]+/', $line, $vehiclename_tmp);          
                //echo "<br>vname=".$vehiclename_tmp[0];
                if($status==0)
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
                
                $status = preg_match('/'.$vh.'="[^" ]+/', $line, $speed_tmp);
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
                
               	 // GET LAST SECTOR VEHICLE ENTERES
                $lat_tmp1 = explode("=",$lat_tmp[0]);
                //echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1;
                $lat = preg_replace('/"/', '', $lat_tmp1[1]);
  
                $lng_tmp1 = explode("=",$lng_tmp[0]);
                $lng = preg_replace('/"/', '', $lng_tmp1[1]);
                                  
                //echo "<br>size=".sizeof($sector_name);
                                 
                 for($y=0;$y<sizeof($sector_name);$y++)
                 {                              	                  								                  								
    								if($sector_coord_decoded[$y]!="")
    								{                
    								  //echo "<br>latref=".$lat_ref." ,lng_ref=".$lng_ref." ,<br>sector_coord_decoded=".$sector_coord_decoded;
    								  //echo "<br><br>sector_coord_decoded=".$sector_coord_decoded;
                      //echo "<br>sector_name=".$sector_name[$y];
                      //echo "<br>points: lat=".$lat_ref." ,lng=".$lng_ref;
                      //echo "<br>coordinates=".$sector_coord_decoded."<br>"; 
                      
                      //tmp
                      /*$last_sector_point = $lat.",".$lng;
                      $last_sector_coord = $sector_coord_decoded[$y];
                      $last_sector_name = $sector_name[$y];*/
                      //
                                              
                      $dummy = "dummy";
                      check_with_range($lat, $lng, $dummy, $sector_coord_decoded[$y], &$sector_status);
    								  //echo "<br>".$sector_coord_decoded[$y];
                      //echo "<br>status=".$sector_status; 
                      if($sector_status)
                      {
                        $last_sector_point = $lat.",".$lng;
                        $last_sector_coord = $sector_coord_decoded[$y];
                        $last_sector_name = $sector_name[$y];
                      }                                       
    								}
                 }                 
                // LAST SECTOR CLOSED                     
                if($firstdata_flag==0)
                {
                  //echo "<br>FirstData";
                  $halt_flag = 0;
                  $firstdata_flag = 1;
                  
                 
                   $vserial=$vehicle_serial;
                  $lat_ref = $lat;
                  $lng_ref = $lng;
                  
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
                         //  GET NEXT RECO
                  $lat_cr = $lat;
                  $lng_cr = $lng;
    
                  //$datetime_tmp1 = explode("=",$datetime_tmp[0]);
                  $datetime_cr = $datetime;
                		               
              		//$time2 = $datetime[$i][$j];											
              		$date_secs2 = strtotime($datetime_cr);
                		
              		//echo "<br>str=".$lat_ref.", ".$lat_cr.", ".$lng_ref." ,".$lng_cr.", ". $datetime_cr;
              		calculate_distance($lat_ref, $lat_cr, $lng_ref, $lng_cr, &$distance);
                	
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
                            //echo "<br>Print:".$lat_ref.",".$lng_ref;
                            //echo "<br>last_sector_point=".$last_sector_point;
                            //echo "<br>last_sector_coord=".$last_sector_coord;
                            $halt_vehicle_sector = "\n< marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" duration=\"".$hrs_min."\" route_name=\"".$route_name."\" sector_name=\"".$last_sector_name."\"/>";						          						
                            //echo "<br>halt_vehicle_sector=".$halt_vehicle_sector;                            
                            $linetowrite = $halt_vehicle_sector; // for distance       // ADD DISTANCE
                            fwrite($fh, $linetowrite);  
                            
                      			$date_secs1 = strtotime($datetime_cr);
                      			$date_secs1 = (double)($date_secs1 + $interval);
                      			//break;
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

// GET ROUTE DETAIL
function get_route_detail($vserial,&$route_id, &$route_name, $DbConnection)
{
    $query = "SELECT vehicle_id FROM vehicle WHERE vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment WHERE device_imei_no='$vserial' AND status=1) AND status=1";
    //echo "<br>q1=".$query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vehicle_id = $row->vehicle_id;

    $query = "SELECT route_id FROM route_assignment WHERE vehicle_id = '$vehicle_id' AND status=1";
    //echo "<br>q2=".$query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $route_id = $row->route_id;

    $query = "SELECT route_name FROM route WHERE route_id = '$route_id' AND status=1";
    //echo "<br>q3=".$query;
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $route_name = $row->route_name;	
}

// GET SECTOR DETAIL
function get_sorted_sector_coords($route_id,&$sector_coord,&$sector_name,$DbConnection)
{
	$query = "SELECT route_sector_ids FROM route where route_id='$route_id' AND status=1";
	//echo "<br>sector_query1=".$query;
  $result = mysql_query($query,$DbConnection);
	if($row = mysql_fetch_object($result))
	{
		$sector_ids1 = $row->route_sector_ids;
		$sector_ids2 = explode(':',$sector_ids1);
		
		$i=0;
		for($k=0;$k<sizeof($sector_ids2);$k++)
		{
			//echo "<br>sector:".$sector_ids2[$k];

			$sector_id3 = explode(',',$sector_ids2[$k]);

			//echo "<br>size:sector_id3=".sizeof($sector_id3)." one=".$sector_id3[0]." ,two=".$sector_id3[1];			
			$query2 = "SELECT sector_name,sector_coord FROM sector WHERE sector_id = '$sector_id3[0]' AND status=1";
			//echo "<br>sector_query2=".$query2;
      $result2 = mysql_query($query2,$DbConnection);
			if($row2 = mysql_fetch_object($result2))
			{
				$sector_name[$i] = $row2->sector_name;
				$sector_coord[$i] = $row2->sector_coord;
				$sector_id[$i] = $sector_id3[0];
				$sequence[$i] = $sector_id3[1];
				
				//echo "<br>SectorString: name=".$sector_name[$i]." ,coord=".$sector_coord[$i]." ,id=".$sector_id[$i]." ,seq=".$sequence[$i];
			}
			
			$i++;
		}		
	}
	// SORTING CODE		
	for($x = 1; $x < $i; $x++) 
	{
		$tmp_sequence = $sequence[$x];
		$tmp_sector_id = $sector_id[$x];
		$tmp_sector_name = $sector_name[$x];
		$tmp_sector_coord = $sector_coord[$x];
								
		////////////////////////////////////////       				
		$z = $x - 1;
		$done = false;
		while($done == false)
		{
		   $sequence_tmp1 = $sequence[$z];

			if ($sequence_tmp1 >$tmp_sector_id)
			{
				$sequence[$z + 1] = $sequence[$z];
				$sector_id[$z + 1] = $sector_id[$z];
				$sector_name[$z + 1] = $sector_name[$z];
				$sector_coord[$z + 1] = $sector_coord[$z];
				
				$z = $z - 1;
				if ($z < 0)
				{
					$done = true;
				}
			}
			else
			{
				$done = true;
			}
		}
		
		$sequence[$z + 1] = $tmp_sequence;
		$sector_id[$z + 1] = $tmp_sector_id;	
		$sector_name[$z + 1] = $tmp_sector_name;
		$sector_coord[$z + 1] = $tmp_sector_coord;		
	}            
 
 /*for($y=0;$y<$i;$y++)
 {
	echo "<br>sector_detail:seq=".$sequence[$y].", id=".$sector_id[$y].", name=".$sector_name[$y].", coord=".$sector_coord[$y];		   
 }*/ 

} // function closed                                                                                                                                                           
	

/*function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
{	
	$lat1 = deg2rad($lat1);
	$lon1 = deg2rad($lon1);

	$lat2 = deg2rad($lat2);
	$lon2 = deg2rad($lon2);
	
	$delta_lat = $lat2 - $lat1;
	$delta_lon = $lon2 - $lon1;
	
	$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
	$distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));
	
	$distance = $distance*1.609344;	
}*/  

 echo '<center>';
    
/*    
    echo'<form method="post" action="HaltReportAction.php" target="_self">';
    //echo"<input TYPE=\"hidden\" VALUE=\"$access\" NAME=\"access\">";
    
    echo'<SPAN STYLE="font-size: xx-small">Select Intervals </SPAN><select name="user_interval" onChange="this.form.submit();">';
    	echo '<option value="0">0</option>';
    
    	if($user_interval==5)
    		echo '<option value="5" selected>5</option>';
    	else
    		echo '<option value="5">5</option>';
    
    	if($user_interval==10)
    		echo '<option value="10" selected>10</option>';
    	else
    		echo '<option value="10">10</option>';
    								
    	if($user_interval==15)
    		echo '<option value="15" selected>15</option>';
    	else
    		echo '<option value="15">15</option>';
    	
    	if($user_interval==20)
    		echo '<option value="20" selected>20</option>';
    	else
    		echo '<option value="20">20</option>';
    	
    	if($user_interval==25)
    		echo '<option value="25" selected>25</option>';
    	else
    		echo '<option value="25">25</option>';
    	
    	if($user_interval==30)
    		echo '<option value="30" selected>30</option>';
    	else
    		echo '<option value="30">30</option>';
    	
    	if($user_interval==35)
    		echo '<option value="35" selected>35</option>';
    	else
    		echo '<option value="35">35</option>';
    	
    	if($user_interval==40)
    		echo '<option value="40" selected>40</option>';
    	else
    		echo '<option value="40">40</option>';
    	
    	if($user_interval==45)
    		echo '<option value="45" selected>45</option>';
    	else
    		echo '<option value="45">45</option>';
    
    	if($user_interval==50)
    		echo '<option value="50" selected>50</option>';
    	else
    		echo '<option value="50">50</option>';
    
    	if($user_interval==55)
    		echo '<option value="55" selected>55</option>';
    	else
    		echo '<option value="55">55</option>';
    
    	if($user_interval==60)
    		echo '<option value="60" selected>60</option>';
    	else
    		echo '<option value="60">60</option>';
    
    	if($user_interval==90)
    		echo '<option value="90" selected>90</option>';
    	else
    		echo '<option value="90">90</option>';
    
    	if($user_interval==120)
    		echo '<option value="120" selected>120</option>';
    	else
    		echo '<option value="120">120</option>';
    
    	if($user_interval==150)
    		echo '<option value="150" selected>150</option>';
    	else
    		echo '<option value="150">150</option>';
    
    	if($user_interval==180)
    		echo '<option value="180" selected>180</option>';
    	else
    		echo '<option value="180">180</option>';
    
    	if($user_interval==210)
    		echo '<option value="210" selected>210</option>';
    	else
    		echo '<option value="210">210</option>';
    
    	if($user_interval==240)
    		echo '<option value="240" selected>240</option>';
    	else
    		echo '<option value="240">240</option>';
    
    	if($user_interval==270)
    		echo '<option value="270" selected>270</option>';
    	else
    		echo '<option value="270">270</option>';
    
    	if($user_interval==300)
    		echo '<option value="300" selected>300</option>';
    	else
    		echo '<option value="300">300</option>';
    
    	if($user_interval==330)
    		echo '<option value="330" selected>330</option>';
    	else
    		echo '<option value="330">330</option>';
    
    	if($user_interval==360)
    		echo '<option value="360" selected>360</option>';
    	else
    		echo '<option value="360">360</option>';
    
    echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> mins</SPAN>';
    
    //echo '<input type="hidden" name="uid1" value="'.$uid.'"';						

		for($i=0;$i<sizeof($vserial);$i++)
		{
			//echo "<br>vid=".$vehicleid[$i];
			echo '<input type="hidden" name="vehicleserial[]" value="'.$vserial[$i].'"';
		}
		//echo '<input type="hidden" name="vehicleid[]" value="-"';
		
		echo '<input type="hidden" name="start_date" value="'.$date1.'"';
		echo '<input type="hidden" name="end_date" value="'.$date2.'"';

		echo '</form>';
*/		
		
    include("map_window/mapwindow_jsmodule.php");		
    include("map_window/floating_map_window.php");
    
    $size_vserial = sizeof($vserial);
        			
		echo'<br>';
		$param1 = $date1;
		$param2 = $date2." &nbsp;-Interval:".$user_interval." mins";
		
		report_title("Sector Halt Report",$param1,$param2);
		
    echo'<div style="overflow: auto;height: 450px;" align="center">';
       
		$alt ="-";
						
    ///////////////////  READ HALT XML 	//////////////////////////////				                      
    $xml_path = $xmltowrite;
    //echo "<br>xml_path=".$xml_path;
		read_sector_data_xml($xml_path, &$imei_xml, &$vname_xml, &$lat_xml, &$lng_xml, &$arr_time_xml, &$dep_time_xml, &$duration_xml, &$route_name_xml, &$sector_name_xml);
		//convert_in_two_dimension
    /*if($_SERVER["REMOTE_ADDR"] == "202.3.77.206")
    {
      echo "<br>".$sector_name_xml[0]; 
    } */
    //echo "<br><br>size, vname=".sizeof($vname);
		//////////////////////////////////////////////////////////////////////
    $j=-1;
    
    $vsize = sizeof($imei_xml);
    
    for($i=0;$i<sizeof($imei_xml);$i++)
		{				
      /*echo "<br>a".$i."=".$vname[$i];
      echo "<br>lat".$i."=".$lat[$i];
      echo "<br>lng".$i."=".$lng[$i];
      echo "<br>arrival_time".$i."=".$arrival_time[$i];
      echo "<br>dep_time".$i."=".$dep_time[$i];
      echo "<br>duration".$i."=".$duration[$i]; */
            
      if(($i===0) || (($i>0) && ($imei_xml[$i-1] != $imei_xml[$i])) )
      {
        $k=0;
        $j++;
        $sno = 1;
        $title="Sector Halt Report : ".$vname_xml[$i]." &nbsp;<font color=red>(".$imei_xml[$i].")</font>-Interval:".$user_interval." mins";
        $vname1[$j][$k] = $vname_xml[$i];
        $imei1[$j][$k] = $imei_xml[$i];
        
        echo'
        <br><table align="center">
        <tr>
        	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
        </tr>
        </table>
        <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
        <tr>
        	<td class="text" align="left" width="4%"><b>SNo</b></td>
        	<!--<td class="text" align="left"><b>Location</b></td>-->
        	<td class="text" align="left"><b>Arrival Time</b></td>
        	<td class="text" align="left"><b>Departure Time</b></td>
        	<td class="text" align="left"><b>Halt Duration (Hrs.min)</b></td>        	
          <td class="text" align="left"><b>Sector Name</b></td>
          <td class="text" align="left"><b>Route Name</b></td>
        </tr>';  								
      }
            							                    
      if($report_type=='Person')
      {
        ///// 1.CONVERT DATE TIME IN DD, MM, YYYY FORMA
        $datestr = explode(' ',$arr_time_xml[$i]);
        $date_tmp = $datestr[0];
        $time_tmp = $datestr[1];
        
        $date_substr = explode('-',$date_tmp);
        $year = $date_substr[0];
        $month = $date_substr[1];
        $day = $date_substr[2];
        
        $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
        $arr_time[$i] = $display_datetime;
        
        ///// 2.CONVERT DATE TIME IN DD, MM, YYYY FORMA
        $datestr = explode(' ',$dep_time_xml[$i]);
        $date_tmp = $datestr[0];
        $time_tmp = $datestr[1];
        
        $date_substr = explode('-',$date_tmp);
        $year = $date_substr[0];
        $month = $date_substr[1];
        $day = $date_substr[2];
        
        $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
        $dep_time[$i] = $display_datetime;                
        ///////////////////////////////////////////////      
      }
            
     	//include("get_location_test.php");      
              	
			$lt1 = $lat_xml[$i];
			$lng1 = $lng_xml[$i];
			$alt1 = "-";								
			 
      /*$landmark="";
      get_landmark($lt1,$lng1,&$landmark);    // CALL LANDMARK FUNCTION
  		
      $place = $landmark;
      
      if($place=="")
      {
        get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
      }
  		
      //echo "P:".$place;
      
      $placename[$i] = $place;	*/
																
			echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';
												
			/*if($placename[$i]=="")
			{
				echo'<td class="text" align="left">&nbsp;</td>';
			}
			else
			{																													
				//echo "lat=".$latitude[$j];
        $lt_tmp = substr($lat[$i], 0, -1);
        $lng_tmp = substr($lng[$i], 0, -1);
        $type = "sector";
        echo'<td class="text" align="left">'.$placename[$i].'&nbsp;<a href="javascript:map_window(\''.$vname[$i].'\',\''.$arr_time[$i].'\',\''.$dep_time[$i].'\',\''.$lt_tmp.'\',\''.$lng_tmp.'\',\''.$type.'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';
        
			}	*/								
			
			echo'<td class="text" align="left">'.$arr_time_xml[$i].'</td>';			
			echo'<td class="text" align="left">'.$dep_time_xml[$i].'</td>';
			echo'<td class="text" align="left"><b>'.$duration_xml[$i].'</b></td>';      
      echo'<td class="text" align="left"><font color="green">'.$sector_name_xml[$i].'</font></td>';
      echo'<td class="text" align="left">'.$route_name_xml[$i].'</td>';						
			echo'</tr>';							
			
			//$placename1[$j][$k] = $placename[$i];
			$arr_time1[$j][$k] =  $arr_time_xml[$i];					
			$dep_time1[$j][$k] = $dep_time_xml[$i];	            
			$duration1[$j][$k] = $duration_xml[$i];			
			$sector_name1[$j][$k] = $sector_name_xml[$i];
			$route_name1[$j][$k] = $route_name_xml[$i];
			//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
			
      if( (($i>0) && ($imei_xml[$i+1] != $imei_xml[$i])) )
      {
        $no_of_data[$j] = $k;
        echo '</table>';
      }    	
  			
			$k++;   
      $sno++;      				  				
		}
		
		if($j==0)
      echo '</table>';							
		//PDF CODE

		echo '<form method="post" target="_blank">';
		
    $csv_string = "";    
          
    for($x=0;$x<=$j;$x++)
		{												
        for($y=0;$y<=$no_of_data[$x];$y++)
        {
          $alt_ref="-";
          
          //echo "<br>arr_time1[$x][$y]=".$arr_time1[$x][$y];          
          //$pdf_place_ref = $placename1[$x][$y];
          $pdf_arrival_time = $arr_time1[$x][$y];
          $pdf_depature_time= $dep_time1[$x][$y];
          $pdf_halt_duration = $duration1[$x][$y];                
          $pdf_sector_name = $sector_name1[$x][$y];
          $pdf_route_name = $route_name1[$x][$y];                
          
          if($y==0)
          {
          	$title="Sector Halt Report : ".$vname1[$x][$y]." (".$imei1[$x][$y].")-Interval:".$user_interval." mins";
          	//echo "<br>pl=".$pdf_place_ref;
          	$csv_string = $csv_string.$title."\n";
          	//$csv_string = $csv_string."SNo,Place,Arrival Time,Departure Time,Halt Duration (Hrs.min), Route Name, Sector Name\n";
          	$csv_string = $csv_string."SNo,Arrival Time,Departure Time,Halt Duration (Hrs.min), Sector Name, Route Name\n";
          	
            echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
          }
          														
          $sno_1 = $y+1;										
          echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_arrival_time\" NAME=\"temp[$x][$y][ArrivalTime]\">";
          //echo"<input TYPE=\"hidden\" VALUE=\"$pdf_place_ref\" NAME=\"temp[$x][$y][Place]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_depature_time\" NAME=\"temp[$x][$y][DepartureTime]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_halt_duration\" NAME=\"temp[$x][$y][Duration of Halt (hr.min)]\">";          
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_sector_name\" NAME=\"temp[$x][$y][Sector Name]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$pdf_route_name\" NAME=\"temp[$x][$y][Route Name]\">";
                
          /// CODE FOR CSV
          $pdf_place_ref = str_replace(',',':',$pdf_place_ref);
          //echo "<br>".$pdf_place_ref;
          //$csv_string = $csv_string.$sno_1.','.$pdf_place_ref.','.$pdf_arrival_time.','.$pdf_depature_time.','.$pdf_halt_duration.','.$pdf_route_name.','.$pdf_sector_name."\n"; 
          $csv_string = $csv_string.$sno_1.','.$pdf_arrival_time.','.$pdf_depature_time.','.$pdf_halt_duration.','.$pdf_sector_name.','.$pdf_route_name."\n";
          ////////////////////////////////////         	
        }	//inner for	
		} // outer for		
	
    	
		if($vsize==0)
		{						
			print"<center><FONT color=\"Red\" size=2><strong>Sorry! No Sector Halt Record Found</strong></font></center>";
			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
			echo'<br><br>';
		}	
		else
		{
      echo'<input TYPE="hidden" VALUE="sector halt" NAME="csv_type">';
      echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
      echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
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