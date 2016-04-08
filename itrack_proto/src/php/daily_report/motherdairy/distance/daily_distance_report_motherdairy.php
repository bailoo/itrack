<?php  
set_time_limit(0);

//$file = "../../../setup/mysql.php"; if(file_exists($file)) { include_once($file); }
//$HOST = "111.118.181.156";
include_once("../../database_ip.php");
$DBASE = "iespl_vts_beta";
$USER = "root";
$PASSWD = "mysql";
//echo "\nDBASE=".$DBASE." ,User=".$USER." ,PASS=".$PASSWD;
$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Connection to server is down. Please try after few minutes.");
mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

$abspath = "/var/www/html/vts/beta/src/php";

include_once($abspath."/get_all_dates_between.php");
include_once($abspath."/sort_xml.php");
include_once($abspath."/calculate_distance.php");
include_once($abspath."/report_title.php");
include_once($abspath."/read_filtered_xml.php");
include_once($abspath."/select_landmark_report.php");
include_once($abspath."/util.hr_min_sec.php");
//include("get_location.php");

include_once($abspath."/area_violation/check_with_range.php");
include_once($abspath."/area_violation/pointLocation.php");
include($abspath."/user_type_setting.php");

require_once $abspath."/excel_lib/class.writeexcel_workbook.inc.php";
require_once $abspath."/excel_lib/class.writeexcel_worksheet.inc.php";

$timetmp1 = 0;
$breakflag = 0;

function tempnam_sfx($path, $suffix)
{
  do
  {
     //$file = $path."/".mt_rand().$suffix;
     $file = $path.$suffix;
     $fp = @fopen($file, 'x');
  }
  while(!$fp);

  fclose($fp);
  return $file;
}

$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));
//$previous_date = "2012-07-23";

$total_days=date('t',mktime(0,0,0,$previous_date));		// TOTAL DAYS IN PREVIOUS MONTH
////////////////  

$vserial_global = array();
$vname_global = array();
$vid_global = array();
$date_global = array();
$daily_dist_global = array();
$daily_halt_global = array();

$daily_dist_tmp =0;
$daily_halt_tmp =0;

$user_interval = "5";                                

/////********* CREATE EXCEL FILE *******************///////
$rno = rand();
$filename_title = 'DAILY_MOTHERDAIRY_VTS_REPORT_'.$previous_date.'_'.$rno;
//echo "\nfilename1=".$filename_title."\n";
$file_path = "/var/www/html/vts/beta/src/php/download/".$filename_title;
$fname = tempnam_sfx($file_path, ".xls");
$workbook =& new writeexcel_workbook($fname);                     //******* ADD WORKBOOK

//echo "\none";
# Create a border format
$title_format1 =& $workbook->addformat();
$title_format1->set_color('black');
$title_format1->set_bold();
$title_format1->set_size(13);
$title_format1->set_pattern(0x1);
$title_format1->set_fg_color('yellow');
$title_format1->set_border_color('black');

$heading_format1 =& $workbook->addformat();
$heading_format1->set_color('black');
$heading_format1->set_bold();
$heading_format1->set_size(11);
$heading_format1->set_pattern(0x1);
$heading_format1->set_fg_color('yellow');
$heading_format1->set_border_color('yellow');

$heading_format2 =& $workbook->addformat();
$heading_format2->set_color('white');
$heading_format2->set_bold();
$heading_format2->set_size(10);
$heading_format2->set_pattern(0x1);
$heading_format2->set_fg_color('green');
$heading_format2->set_border_color('yellow');

$heading_format3 =& $workbook->addformat();
$heading_format3->set_color('black');
$heading_format3->set_bold();
$heading_format3->set_align('left');
$heading_format3->set_size(10);
//$heading_format3->set_pattern(0x1);
//$heading_format3->set_fg_color('white');
//$heading_format3->set_border_color('black');

$heading_format4 =& $workbook->addformat();
$heading_format4->set_color('red');
$heading_format4->set_bold();
$heading_format4->set_align('right');
$heading_format4->set_size(10);
//$heading_format4->set_pattern(0x1);
//$heading_format4->set_fg_color('white');
//$heading_format4->set_border_color('black');

/*$heading_inactive =& $workbook->addformat();
$heading_inactive->set_color('red');
//$heading_format2->set_bold();
$heading_inactive->set_size(10);
$heading_inactive->set_pattern(0x1);
$heading_inactive->set_fg_color('purple');
$heading_inactive->set_border_color('red');*/

$text_format =& $workbook->addformat();
$text_format->set_size(10); 
$text_format->set_align('center');
$text_format->set_align('vcenter');
$text_format->set_align('vjustify');
$text_format->set_text_wrap();

//echo "\ntwo";
/*$heading_format1 =& $workbook->addformat(array(
	bold    => 1,
	//italic  => 1,
	color   => 'green',
	size    => 10,
	//font    => 'Comic Sans MS'
));

$heading_format2 =& $workbook->addformat(array(
	bold    => 1,
	//italic  => 1,
	color   => 'blue',
	size    => 9,
	//font    => 'Comic Sans MS'
)); */
                                                                              
$blank_format = & $workbook->addformat();                       //******* DEFINE BLANK STYLE
$blank_format->set_color('white');
$blank_format->set_bold();
$blank_format->set_size(12);
$blank_format->set_merge();
/////**********************      
$imei_msg = "";
$vids ="";

$r=0;
$worksheet_title = "DAILY VTS_REPORT -MOTHERDAIRY";
$worksheet =& $workbook->addworksheet($worksheet_title);  

//echo "\nthree";

for($b=0;$b<=2;$b++)
{
  $worksheet->write_blank($r, $b,$title_format1);
}  
$r++;

$report_title = "VTS TRACKING REPORT :MOTHERDAIRY -Date:".$previous_date;
$worksheet->write ($r, 0, $report_title, $title_format1);
for($b=1;$b<=2;$b++)
{
  $worksheet->write_blank($r, $b,$title_format1);
}  
$r++;
/////////////////////////////////////////////
//echo "\nfour";

//$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND status=1 limit 3";
//$query_account = "SELECT account_id,user_id FROM account WHERE group_id='0001' AND user_id NOT IN('rspl') AND status=1";
$query_account = "SELECT account_id,user_id FROM account WHERE account_id=230 AND status=1";  //FOR MOTHERDAIRY
$result_account = mysql_query($query_account,$DbConnection);

//echo "q1=".$query_account."\n";
$total_vehicles = 0;
$total_inactive_vehicles = 0;
$total_nogps_vehicles = 0;


while($row_account = mysql_fetch_object($result_account))
{
  $account_id = $row_account->account_id;
  $user_id = $row_account->user_id;
  
  $query_name = "SELECT name FROM account_detail WHERE account_id='$account_id'";
  $result_name = mysql_query($query_name,$DbConnection);
  
  $row_name = mysql_fetch_object($result_name);
  $user_name = $row_name->name;
  
  $query_assignment = "SELECT DISTINCT vehicle.vehicle_id,vehicle.vehicle_name FROM vehicle,vehicle_assignment,vehicle_grouping WHERE ".
                      "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id AND ".
                      "vehicle_grouping.account_id='$account_id' AND vehicle_grouping.status=1 AND vehicle.status=1 AND vehicle_assignment.status=1";              
                                              
  //echo "\nquery_name=".$query_name." ,query_assignment=".$query_assignment."\n";
  $result_assignment = mysql_query($query_assignment,$DbConnection);
  
  $v=0;
  $vehicle_id_tmp ="";
  
  //echo "\n\nUSERID::".$user_name."\n";
  //OPEN 
  $r++;
  $worksheet->write($r, 0, $user_name, $heading_format1);    //ACCOUNT DETAIL
  $worksheet->write_blank($r, 1, $heading_format1);
  $worksheet->write_blank($r, 2, $heading_format1);
  $r++;     

  $worksheet->write($r, 0, "SNO", $heading_format2);
  $worksheet->write($r, 1, "VEHICLE", $heading_format2);
  $worksheet->set_column(1, 1, 45);   
  $worksheet->write($r, 2, "DISTANCE(KM) / STATUS", $heading_format2);
  $worksheet->set_column(2, 2, 40); 
  $r++;  
    
  $j=0;
  $vname = array();
  $vserial = array();
  $vid = array();
  
  while($row_assignment = mysql_fetch_object($result_assignment))
  {    
    $vehicle_id_a = $row_assignment->vehicle_id;
    $vname[$j] = $row_assignment->vehicle_name;
    
    $query_imei = "SELECT device_imei_no FROM vehicle_assignment WHERE vehicle_id ='$vehicle_id_a' AND status=1";
    //echo "\nquery_imei=".$query_imei;
    $result_imei = mysql_query($query_imei, $DbConnection);
    $row_imei = mysql_fetch_object($result_imei);
    $vserial[$j] = $row_imei->device_imei_no;
    $vid[$j] = $vehicle_id_a;
     
    if($v==0) 
      $vehicle_id_tmp = $vehicle_id_tmp.$vehicle_id_a;
    else
      $vehicle_id_tmp = $vehicle_id_tmp.",".$vehicle_id_a;  
  
    $j++;
    $v++;     
  }
  
  $vsize = sizeof($vserial);
  //echo "TOTAL VEHICLES::".$vsize."\n";  
  
  $inactive_vehicle_counter = 0;
  $nogps_vehicle_counter = 0;
  
  $sno = 1;
  for($i=0;$i<$vsize;$i++)  
	{                      
    $total_dist_tmp = 0;
    $total_halt_tmp = 0;
    
    $daily_dist_tmp =0;             //RESET VARIABLES FOR INDIVIDUAL DAY
    //$daily_halt_tmp =0;

    $date1 = $previous_date." 00:00:00";
    $date2 = $previous_date." 23:59:59";      
            
    //CALL FUNCTION
    get_daily_distance_report_xml($vserial[$i], $vname[$i], $date1, $date2);      
   
    $worksheet->write($r, 0, $sno, $heading_format3);    
    $worksheet->write($r, 1, $vname[$i], $heading_format3);

    //$daily_dist_tmp = -1;
    
    if($daily_dist_tmp == -1)
    {
       $daily_dist_tmp = "INACTIVE";
       $inactive_vehicle_counter++;
       $worksheet->write($r, 2, $daily_dist_tmp, $heading_format4);
    }    
    else if($daily_dist_tmp == -2)
    {
      $daily_dist_tmp = "No GPS";
      $nogps_vehicle_counter++;
      $worksheet->write($r, 2, $daily_dist_tmp, $heading_format4);
    }
    else
    {
      $worksheet->write($r, 2, $daily_dist_tmp, $heading_format3);  
    }
        
    //$worksheet->write($r, 5, "Halt(H:m:s)", $text_format);  
    $r++;  
    //$daily_halt_global[$i] = $daily_halt_tmp;
    //echo "\nIMEI=".$vserial[$i]." ,Date1=".$date1." ,Date2=".$date2." dist=".$daily_dist_tmp;  
    $sno++;          
	}     
 
  $worksheet->write($r, 0, "TOTAL INACTIVE", $heading_format2);
  $worksheet->write_blank($r, 1,$heading_format2);   
  $worksheet->write($r, 2, $inactive_vehicle_counter, $heading_format2);

  $r++;
  $r++;
  //CLOSE 
  
  $total_vehicles = $total_vehicles + $vsize;
  $total_inactive_vehicles = $total_inactive_vehicles + $inactive_vehicle_counter;
  $total_nogps_vehicles = $total_nogps_vehicles + $nogps_vehicle_counter;
 
}  //ACCOUNT CLOSED


$r++;
$r++; 

$worksheet->write($r, 0, "TOTAL VEHICLES", $heading_format2);
$worksheet->write_blank($r, 1,$heading_format2); 
$worksheet->write($r, 2, $total_vehicles, $heading_format1);
$r++;

$worksheet->write($r, 0, "TOTAL *INACTIVE VEHICLES", $heading_format2);
$worksheet->write_blank($r, 1,$heading_format2); 
$worksheet->write($r, 2, $total_inactive_vehicles, $heading_format1);
$r++;

$worksheet->write($r, 0, "TOTAL *NO GPS VEHICLES", $heading_format2);
$worksheet->write_blank($r, 1,$heading_format2); 
$worksheet->write($r, 2, $total_nogps_vehicles, $heading_format1);
$r++;


$workbook->close(); //CLOSE WORKBOOK
//echo "\nWORKBOOK CLOSED"; 



//GET XML_DATA
function get_daily_distance_report_xml($vehicle_serial, $vname, $startdate, $enddate)
{
	//echo "<br>vs=".$vehicle_serial." ,vname=".$vname." ,startdate=".$startdate." ,enddate=".$enddate." ,xmltowrite=".$xmltowrite;
  //echo "\nIn function -wockhardt report xml main";
  $fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$CurrentLat = 0.0;
	$CurrentLong = 0.0;
	$LastLat = 0.0;
	$LastLong = 0.0;
	$firstData = 0;
	$distance =0.0;
	$linetowrite="";
	$firstdata_flag =0;     //INITIALISE FIRST FLAG
	$breakflag = 0;
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];
	
	global $daily_dist_tmp;
	//global $daily_halt_tmp;	

	get_All_Dates($datefrom, $dateto, &$userdates);

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;
	$date_size = sizeof($userdates);

	//$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	//$j = 0;
	$total_dist = 0; 									  
  global $timetmp1;
  global $breakflag; 
  global $user_interval; 
  
  //echo "\nDate size=".$date_size;
  
  $nodata_flag = 1;
  $nogps_flag = 1;
  
  for($i=0;$i<=($date_size-1);$i++)
	{
		//echo "\nIn Date Loop";
    //echo "<br>time=".$timetmp1;
    $timetmp2 = date("Y-m-d H:i:s");	
    $timetmp2 = strtotime($timetmp2);    
    $difftmp = ($timetmp2 - $timetmp1);
    //echo "<br>diff=".$difftmp;
  
    $daily_dist = 0; 
    $daily_halt = 0;
       
    $abspath = "/var/www/html/itrack_vts";
    
    $xml_current = $abspath."/xml_vts/xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";	    		
    //echo "<br>xml_current=".$xml_current;
    
    if (file_exists($xml_current))      
    {		    		
			//echo "in else";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = $abspath."/sorted_xml_data/".$userdates[$i]."/".$vehicle_serial.".xml";
			$CurrentFile = 0;
		}
		
		//echo "<br>xml_file =".$xml_file;	    	
    if (file_exists($xml_file)) 
		{			
		  $nodata_flag = 0;             //SET NO DATA OFF  
      //echo "\nfile_exists xml_file";
      $t=time();
      $xml_original_tmp = $abspath."/xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
									      
      if($CurrentFile == 0)
			{
				//echo "<br>ONE<br>";
        copy($xml_file,$xml_original_tmp);
			}
			else
			{
				//echo "<br>TWO<br>";
				$xml_unsorted = $abspath."/xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$i."_unsorted.xml";
        //echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";
				        
        copy($xml_file,$xml_unsorted);        // MAKE UNSORTED TMP FILE
        SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
				unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
			}
      
      $total_lines = count(file($xml_original_tmp));
      //echo "<br>Total lines orig=".$total_lines;
      
      $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
      
      $logcnt=0;
      $DataComplete=false;
                  
      $vehicleserial_tmp=null;
      $format =2;      
      
      if (file_exists($xml_original_tmp)) 
      {              
        //echo "\nFileExists";
        //$daily_dist =0;
        //  $firstdata_flag =0;
                
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
              $nogps_flag = 0;     // SET NO GPS FLAG OFF
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
                           
              $lat_tmp1 = explode("=",$lat_tmp[0]);
              $lat = preg_replace('/"/', '', $lat_tmp1[1]);
              
              $lng_tmp1 = explode("=",$lng_tmp[0]);
              $lng = preg_replace('/"/', '', $lng_tmp1[1]); 
                                                          
              //echo "<br>first=".$firstdata_flag;                                        
              if($firstdata_flag==0)
              {
                //echo "<br>FirstData";
                $firstdata_flag = 1;
                //$halt_flag = 0;
                $lat1_dist = $lat;
                $lng1_dist = $lng;
                
                //$lat1_halt = $lat;
                //$lng1_halt = $lng;
                //$time1_halt = $datetime;
               
                $interval = $user_interval*60;		//30 mins interval
                $last_time1 = $datetime;                         
        				$latlast = $lat;
        				$lnglast =  $lng;                                                               													                 	
            	}           	
            	//echo "<br>k2=".$k2."<br>";              	
              else
              {                           
  							/*
                //********* HALT LOGIC BEGINS
                $lat2_halt = $lat;
                $lng2_halt = $lng;                
                $time2_halt = $datetime;
                 
            		calculate_distance($lat1_halt, $lat2_halt, $lng1_halt, $lng2_halt, &$distance_halt);
              	
          			//if( ($distance > 0.200) || ($f== $total_lines-2) )          			
                //echo "\nlat1_halt=".$lat1_halt.", lat2_halt=".$lat2_halt.", lng1_halt=".$lng1_halt.", lng2_halt=".$lng2_halt.", distance_halt=".$distance_halt;

                if( ($distance_halt > 0.0100) || ($f== $total_lines-2) )
          			{
          				 //echo "\nIn distance";
                    //echo "<br>In dist ".$distance." lat_ref ".$lat_ref." lng_ref ".$lng_ref." lat_cr ".$lat_cr." lng_cr ".$lng_cr."<br>";
          				if ($halt_flag == 1)
          				{				
            					//echo "\nIn Halt1";
                      $arrivale_time = $time1_halt;
            					$starttime = strtotime($time1_halt);
            					
            					//$stoptime = strtotime($datetime_cr);  
            					$stoptime = strtotime($time2_halt);
            					$depature_time = $time2_halt;
            					//echo "<br>".$starttime." ,".$stoptime;
            					
            					$halt_dur =  ($stoptime - $starttime);
            					
                      //echo "\nHalt Dur=".$halt_dur." ,interval=".$interval." ,time1_halt=".$time1_halt." ,time2_halt=".$time2_halt;
                      
                      if( ($halt_dur >= $interval) || ($f== $total_lines-2))
            					{
                        //echo "<br>In Halt else";
                        $daily_halt = $daily_halt + $halt_dur; 
                        //$total_halt_vehicle = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" lat=\"".$lat_ref."\" lng=\"".$lng_ref."\" arr_time=\"".$arrivale_time."\" dep_time=\"".$depature_time."\" duration=\"".$halt_dur."\"/>";						          						
                        //echo "<br>total halt vehicle=".$total_halt_vehicle;
                        $linetowrite = $total_halt_vehicle; // for distance       // ADD DISTANCE
                        fwrite($fh, $linetowrite); 
                        
                  			//$date_secs1 = strtotime($datetime_cr);
                  			//$date_secs1 = (double)($date_secs1 + $interval);                                                   
                      }		// IF TOTAL MIN										
              			}   //IF HALT FLAG
              			
            				$lat1_halt = $lat2_halt;
            				$lng1_halt = $lng2_halt;
            				$time1_halt = $time2_halt;
            				
            				$halt_flag = 0;
              		}
            			else
            			{            			
                      //echo "<br>normal flag set";
                      $halt_flag = 1;
            			}					                                              							  							
  							//********* HALT LOGIC CLOSED
                 */
                
                //********* DISTANCE LOGIC BEGINS
                $time2 = $datetime;											
  							$date_secs2 = strtotime($time2);	
  							
  							$vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
                $vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);
                                  
                $lat2_dist = $lat;
                $lng2_dist = $lng;  
                
  							calculate_distance($lat1_dist, $lat2_dist, $lng1_dist, $lng2_dist, &$distance);
  							//echo "<br>lat1=".$lat1." ,lat2=".$lat2." ,lng1=".$lng1." ,lng2=".$lng2." ,dist=".$distance;
  							
                calculate_distance($latlast, $lat2_dist, $lnglast, $lng2_dist, &$distance1);
                
                $tmp_time_diff1 = (double)(strtotime($datetime) - strtotime($last_time1)) / 3600;
                
        				if($tmp_time_diff1>0)
        				{
        					$tmp_speed = ((double) ($distance1)) / $tmp_time_diff1;
        					$last_time1 = $datetime;
        					$latlast = $lat2_dist;
        					$lnglast =  $lng2_dist;        					
        				}
        				
        				$tmp_time_diff = ((double)( strtotime($datetime) - strtotime($last_time) )) / 3600;
                                
                //if($tmp_speed <3000 && $distance>0.1)
                if($tmp_speed<500 && $distance>0.1 && $tmp_time_diff>0)
      					{		              
                  //echo "\nIndistance";
                  $daily_dist= (float) ($daily_dist + $distance);	
                  $daily_dist = round($daily_dist,2);							                          
                                    						                                       
     							///////////////////////////////////////////////////////////																							
                  $lat1_dist = $lat2_dist;
                  $lng1_dist = $lng2_dist;
                  
                  $last_time = $datetime;			
                }	
                //**** DISTANCE LOGIC CLOSED						                               
              }
  					} // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed
  			 //$j++;
        }   // while closed
      } // if original_tmp closed         
			
      //WRITE DAILY DISTANCE DATA
      /*$daily_distance_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" date=\"".$userdates[$i]."\" daily_dist=\"".$daily_dist."\"/>";						          						
      //echo "<br><br>".$daily_distance_data;
      $linetowrite = $daily_distance_data; // for distance       // ADD DISTANCE
      fwrite($fh, $linetowrite); */ 		
                    
      //$daily_halt_tmp = $daily_halt;     
     fclose($xml);            
		 unlink($xml_original_tmp);
		} // if (file_exists closed      
    
    if($nodata_flag)
    {
      $daily_dist_tmp = -1;   //NO DATA      
    }
    else if($nogps_flag)
    {
      $daily_dist_tmp = -2;   //NO GPS
    }
    else
    {
      $daily_dist_tmp = $daily_dist;
    }
	}  // for closed 
	//echo "Test1";
	//fclose($fh);
}
  
  
  //########### SEND MAIL ##############//
  //$to = 'rizwan@iembsys.com'; 
  $to = 'Amit.Patel@motherdairy.com,Ravindra.Negi@motherdairy.com,Vijay.Singh@motherdairy.com,vivek.chahal@motherdairy.com';
  $subject = 'VTS_REPORT_MOTHERDAIRY_'.$previous_date."/".$previous_year;
  $message = 'VTS_REPORT_MOTHERDAIRY_'.$previous_date."/".$previous_year; 
  $random_hash = md5(date('r', time()));  
  $headers = "From: support@iembsys.co.in\r\n";
  $headers .= "Cc: jyoti.jaiswal@iembsys.com,omvrat@iembsys.com,avanendra@iembsys.com";
  $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
  $filename_title = $filename_title.".xls";
  $file_path = $file_path.".xls";
  //echo "\nFILE PATH=".$file_path;
  
  include_once("send_mail_api.php");
  //####################################//
  
  unlink($file_path);
  
?>
