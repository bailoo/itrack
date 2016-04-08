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
include_once("get_location.php");
include_once("report_title.php");
include_once("user_type_setting.php");

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

//////// INITIALISE ALL GLOBAL VARIABLES
//================================

$route_id = "";
$route_name = "";

$imei_g = array();
$vname_g = array();
$sector_coord = array();
$sector_id = array();
$sequence = array();
$sector_name = array();
$sector_coord_decoded = array();

//================================

$prev_sector_id_u = "";   
$prev_sector_sequence_u = "";
$prev_sector_name_u = "";
$prev_datetime_u = "";            
 
$last_datetime_u = "";
$last_sector_id_u = "";
$last_sector_sequence_u = "";
$last_sector_name_u = "";
 
$prev_sector_id_e = array(); 
$prev_sector_sequence_e = array();
$prev_sector_name_e = array();
$prev_datetime_e = array();                   
                              
$datetime_entered = array();
$sector_id_entered = array();
$sector_sequence_entered = array();
$sector_name_entered = array();

//=================================
////////////////////////////////////////

for($i=0;$i<sizeof($vserial);$i++)
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

//$current_dt = date("Y_m_d_H_i_s");	
//$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";

get_sector_change_data($vserial, $vname, $date1, $date2, $xmltowrite);

function get_sector_change_data($vserial, $vname, $startdate, $enddate, $xmltowrite)
{
  $maxPoints = 1000;
	$file_exist = 0;

	//$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	//fwrite($fh, "<t1>");  
	//fclose($fh);

	//$i=0;
	for($i=0;$i<sizeof($vserial);$i++)
	{  	
     //echo "<br>vserial[i] =".$vserial[$i];
     get_route_and_sector_info($vserial[$i]); // GET ROUTE AND SECTOR INFO          
     get_sector_xml_data($vserial[$i], $vname[$i], $startdate, $enddate);  // GET SORTED SECTOR_DATA     
	}
 
	//$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	//fwrite($fh, "\n</t1>");  
	//fclose($fh);
}

function get_sector_xml_data($vehicle_serial, $vname, $startdate, $enddate)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
global $old_xml_date;
	global $DbConnection;
	global $account_id;
	global $geo_id1;
		
  global $vname_g;
  global $imei_g;
  
  global $route_id;
  global $route_name;

  global $sector_coord;
  global $sector_id;
  global $sequence;
  global $sector_name;
  global $sector_coord_decoded;

  global $prev_sector_id_u;   
  global $prev_sector_sequence_u;
  global $prev_sector_name_u;
  global $prev_datetime_u;            
 
  global $last_datetime_u;
  global $last_sector_id_u;
  global $last_sector_sequence_u;
  global $last_sector_name_u;
 
  global $prev_sector_id_e; 
  global $prev_sector_sequence_e;
  global $prev_sector_name_e;
  global $prev_datetime_e;                   
                              
  global $datetime_entered;
  global $sector_id_entered;
  global $sector_sequence_entered;
  global $sector_name_entered;

	
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

	//$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

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
        //echo "<br>exists";
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
  					if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($xml_date!="-") && ($DataValid==1) )
  					{							           	
                //echo "<br>One";             
               /* $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
                //echo "Status=".$status.'<BR>';
                //echo "test1".'<BR>';
                if($status==0)
                {
                  continue;
                }*/
                
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
                
                $status = preg_match('/'.$vf.'="[^" ]+/', $line, $speed_tmp);
                if($status==0)
                {
                  continue;
                }                           
                
               	// GET LAST SECTOR VEHICLE ENTERES
                $lat_tmp1 = explode("=",$lat_tmp[0]);
                //echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1;
                $lat = preg_replace('/"/', '', $lat_tmp1[1]);
  
                $lng_tmp1 = explode("=",$lng_tmp[0]);
                $lng = preg_replace('/"/', '', $lng_tmp1[1]);
                                  
                //echo "<br>size=".sizeof($sector_name);                                
                //echo "<br>SName".$_SERVER['REMOTE_ADDR'];
                 
                 for($y=0;$y<sizeof($sector_name);$y++)
                 {                              	                  								                  								
    								//echo "<br>seq_sorted=".$sequence[$y]."<br>";
    								
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
                        $last_sector_id = $sector_id[$y];
                        $last_sector_sequence = $sequence[$y];
                        $last_sector_name = $sector_name[$y];
                        
                        //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
                        /*if($_SERVER["REMOTE_ADDR"] == "202.3.77.206")
                        {
                          echo "<br>lat=".$lat." ,lng=".$lng;
                          echo "<br>seq=".$sequence[$y]." (".$sector_name[$y].")";
                          echo "<br>Datetime=".$datetime;
                        }*/
                        break;   
                      }                                       
    								}
                 }
                 //echo "<br>";                 
                // LAST SECTOR CLOSED                
                
                if($firstdata_flag==0)
                {
                  //echo "<br>FirstData";
                  $halt_flag = 0;
                  $firstdata_flag = 1;                  
                  
                  $vserial=$vehicle_serial;
                  $lat_ref = $lat;
                  $lng_ref = $lng;
                  
                  $prev_sector_id = $last_sector_id;
                  $prev_sector_sequence = $last_sector_sequence;
                  $prev_sector_name = $last_sector_name;
                  $prev_datetime = $datetime;                 
                  //echo "<br>lat_tmp=".$lat_tmp[0]." ,lat_tmp1=".$lat_tmp1[1];             	
              	}           	
              	//echo "<br>k2=".$k2."<br>";                	
                else
                {                           	
          	      if ($prev_sector_id != $last_sector_id)
          				{                                           
                    //echo "<br>sector change occured";
                    // UPDATE PREVIOUS AND LAST SECTOR
                    update_prev_last_sectors($prev_sector_id,$prev_sector_sequence,$prev_sector_name,$prev_datetime,$datetime,$last_sector_id,$last_sector_sequence,$last_sector_name);                                                  
                    
                    //echo "<br>vname=".$vname." ,prev_dt1=". $prev_datetime; 
                    //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
                    /*if($_SERVER["REMOTE_ADDR"] == "202.3.77.206")                    
                    {
                      echo "<br>vname=".$vname." ,prev_sector=".$prev_sector_name." (".$prev_sector_sequence.")"; 
                      echo "<br>vname=".$vname." ,current_sector=".$last_sector_name." (".$last_sector_sequence.")<br><br>";
                    } */            
                                                                  	                                            	                        
                    
                    //if($_SERVER["REMOTE_ADDR"] == "202.3.77.206")
                    //{
                      $imei_g[] = $vehicle_serial;
                      $vname_g[] = $vname;
                      //$vserial_g[] = $vserial;
                      $prev_sector_id_e[] = $prev_sector_id_u;
                      $prev_sector_sequence_e[] = $prev_sector_sequence_u;
                      $prev_sector_name_e[] = $prev_sector_name_u;
                      $prev_datetime_e[] = $prev_datetime_u;
                      //echo "<br>prev_dt=". $prev_datetime_u;                  
                                          
                      $datetime_entered[] = $last_datetime_u;
                      $sector_id_entered[] = $last_sector_id_u;
                      $sector_sequence_entered[] = $last_sector_sequence_u;
                      $sector_name_entered[] = $last_sector_name_u;
                      
                      $prev_sector_id_u = "";
                      $last_sector_id_u = "";
                    //}
                        
                    //RESET PREV SECTOR
                    $prev_sector_id = $last_sector_id;
                    $prev_sector_sequence = $last_sector_sequence;
                    $prev_sector_name = $last_sector_name;
                    $prev_datetime = $datetime;                                                                                    								
            			}				                                               
                } // else closed
  					} // $xml_date_current >= $startdate closed
  				}   // if xml_date!null closed
  				$f++;
  			}   // while closed  			
      } // if original_tmp closed 
      
      fclose($xml);            
			unlink($xml_original_tmp);
		} // if (file_exists closed
	}  // for closed 
	
	// GET PREVIOUS AND LAST SECTOR ENTRY
  //echo "prev=".$prev_sector_id_u; echo "last=".$last_sector_id_u;
  
  /*if( ($prev_sector_id_u != $last_sector_id_u) && ($_SERVER["REMOTE_ADDR"] != "202.3.77.206") )
	{                                           
    //echo "<br>stored in array".$vehicle_serial." ,".$vname." ,".$prev_datetime_u." ,".$last_datetime_u;
    $imei_g[] = $vehicle_serial;
    $vname_g[] = $vname;
    //$vserial_g[] = $vserial;
    $prev_sector_id_e[] = $prev_sector_id_u;
    $prev_sector_sequence_e[] = $prev_sector_sequence_u;
    $prev_sector_name_e[] = $prev_sector_name_u;
    $prev_datetime_e[] = $prev_datetime_u;
    //echo "<br>prev_dt=". $prev_datetime_u;                  
                        
    $datetime_entered[] = $last_datetime_u;
    $sector_id_entered[] = $last_sector_id_u;
    $sector_sequence_entered[] = $last_sector_sequence_u;
    $sector_name_entered[] = $last_sector_name_u;
    
    $prev_sector_id_u = "";
    $last_sector_id_u = "";
  }*/      			  				
	
	//echo "Test1";
	fclose($fh);
	//fclose($xmllog);
} 


function update_prev_last_sectors($prev_sector_id,$prev_sector_sequence,$prev_sector_name,$prev_datetime,$datetime,$last_sector_id,$last_sector_sequence,$last_sector_name)
{
  global $prev_sector_id_u;   
  global $prev_sector_sequence_u;
  global $prev_sector_name_u;
  global $prev_datetime_u;            
   
  global $last_datetime_u;
  global $last_sector_id_u;
  global $last_sector_sequence_u;
  global $last_sector_name_u;
  
  $prev_sector_id_u = $prev_sector_id;
  $prev_sector_sequence_u = $prev_sector_sequence;
  $prev_sector_name_u = $prev_sector_name;
  $prev_datetime_u = $prev_datetime;
  $last_datetime_u = $datetime;
  $last_sector_id_u = $last_sector_id;
  $last_sector_sequence_u = $last_sector_sequence;
  $last_sector_name_u = $last_sector_name;  
}

function get_route_and_sector_info($vehicle_serial)	
{  
  global $DbConnection;
  global $route_id;
  global $route_name;  
  global $sector_coord;
  global $sector_id;
  global $sequence;
  global $sector_name;
  global $sector_coord_decoded;
  
  // GET ROUTE AND SECTOR DETAIL	
  get_route_detail($vehicle_serial);
		
	//echo "<br>route=".$route_id." ,name=".$route_name; 				
  if($route_id && $route_name)
  {                                                                                            
     //echo "<br>In route_id";
     // GET SECTOR IDS, SECTOR NAMES
	   get_sorted_sector_coords();   // GET SORTED SECTORS             								 
	                								
     for($y=0;$y<sizeof($sector_name);$y++)     // loop through sectors
     {
        //echo "<br><br>In sorted sector";
        $include_flag = 0;
        $include_status = 0;                                                                                                                                 
        
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
}


// GET ROUTE DETAIL
function get_route_detail($vserial)
{
    global $DbConnection;
    global $route_id;
    global $route_name;
    
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
function get_sorted_sector_coords()
{
  global $DbConnection;
  global $route_id;
  global $sector_coord;
  global $sector_id;
  global $sequence;
  global $sector_name;  
	
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
} // function closed

    
  
    // REPORT DISPLAY        			
		echo'<br>';
		$param1 = $date1;
		$param2 = $date2;
		
		report_title("Sector Change Report",$param1,$param2);
		
    echo'<div style="overflow: auto;height: 450px;" align="center">';
						
		//////////////////////////////////////////////////////////////////////

    $j=-1;   
    $vsize = sizeof($imei_g);
    //echo "vsize=".$vsize;
    
    for($i=0;$i<$vsize;$i++)
		{				            
      if(($i==0) || (($i>0) && ($imei_g[$i-1] != $imei_g[$i])) )
      {
        $k=0;
        $j++;
        $sno = 1;
        $title="Sector Change Report : ".$vname_g[$i]." &nbsp;<font color=red>(".$imei_g[$i].")</font>";
        $vname1[$j][$k] = $vname_g[$i];
        $imei1[$j][$k] = $imei_g[$i];
        
        echo'
        <br><table align="center">
        <tr>
        	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
        </tr>
        </table>
        <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
        <tr>
        	<td class="text" align="left" width="4%"><b>SNo</b></td>      
        	<td class="text" align="left"><b>Previous Sector</b></td>
        	<!--<td class="text" align="left"><b>Previous Time</b></td>-->
        	<td class="text" align="left"><b>Current Sector</b></td>
        	<td class="text" align="left"><b>Sector Change Time</b></td>
        	<td class="text" align="left"><b>Route Name</b></td>
        </tr>';  								
      }
      
      echo '<tr>';
      echo'<td class="text" align="left">'.$sno.'</td>';
      echo'<td class="text" align="left"><font color="green">'.$prev_sector_name_e[$i].'</font>&nbsp;<font color="red">('.$prev_sector_sequence_e[$i].')</font></td>';
      //echo'<td class="text" align="left"><font color="red">'.$prev_datetime_e[$i].'</font></td>';
      echo'<td class="text" align="left"><font color="green">'.$sector_name_entered[$i].'</font>&nbsp;<font color="red">('.$sector_sequence_entered[$i].')</font></td>';
      echo'<td class="text" align="left"><font color="red">'.$datetime_entered[$i].'</font></td>';
      echo'<td class="text" align="left"><font color="blue">'.$route_name.'</font></td>';
      echo '</tr>';
      
      $prev_sector_name_e1[$j][$k] = $prev_sector_name_e[$i];
			$prev_sector_sequence_e1[$j][$k] =  $prev_sector_sequence_e[$i];
      $sector_name_entered1[$j][$k] = $sector_name_entered[$i];
			$sector_sequence_entered1[$j][$k] =  $sector_sequence_entered[$i];										
			$datetime_entered1[$j][$k] = $datetime_entered[$i];	            			
			
      if( (($i>0) && ($imei_g[$i+1] != $imei_g[$i])) )
      {
        $no_of_data[$j] = $k;        
        echo '</table><br>';
      }    	
  			
			$k++;   
      $sno++;      				  				
		}
		
		if($j==0)
      echo '</table>';		       
 

		echo '<form method="post" target="_blank">';
		
    $csv_string = "";    
          
    for($x=0;$x<=$j;$x++)
		{												
        for($y=0;$y<=$no_of_data[$x];$y++)
        {          
          if($y==0)
          {
          	$title="Sector Change Report : ".$vname1[$x][$y]." (".$imei1[$x][$y].")";
          	//echo "<br>pl=".$pdf_place_ref;
          	$csv_string = $csv_string.$title."\n";
          	$csv_string = $csv_string."SNo, Vehicle Name, Previous Sector, Current Sector, Sector Change Time, Route Name\n";
          	
            echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
          }
          
          $previous_sector_tmp =  $prev_sector_name_e1[$x][$y]."(".$prev_sector_sequence_e1[$x][$y].")";
          $current_sector_tmp =  $sector_name_entered1[$x][$y]."(".$sector_sequence_entered1[$x][$y].")";
          $sector_change_time_tmp = $datetime_entered1[$x][$y];
                                         														
          $sno_1 = $y+1;										
          echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";                       
          echo"<input TYPE=\"hidden\" VALUE=\"$previous_sector_tmp\" NAME=\"temp[$x][$y][Previous Sector]\">";
          //echo"<input TYPE=\"hidden\" VALUE=\"$prev_datetime_e[$x]\" NAME=\"temp[$x][Previous Time]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$current_sector_tmp\" NAME=\"temp[$x][$y][Current Sector]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$sector_change_time_tmp\" NAME=\"temp[$x][$y][Sector Change Time]\">";
          echo"<input TYPE=\"hidden\" VALUE=\"$route_name\" NAME=\"temp[$x][$y][Route Name]\">";  
                
          /// CODE FOR CSV
          $csv_string = $csv_string.$sno_1.','.$vname_tmp.','.$previous_sector_tmp.','.$current_sector_tmp.','.$sector_change_time_tmp.','.$route_name."\n"; 
          ////////////////////////////////////         	
        }	//inner for	
		} // outer for	

    	
		if($vsize==0)
		{						
			print"<center><FONT color=\"Red\" size=2><strong>Sorry! No Sector Change Record Found</strong></font></center>";			
			echo'<br><br>';
		}	
		else
		{
      echo'<input TYPE="hidden" VALUE="sector change" NAME="csv_type">';
      echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
      echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;
      <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
      <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
    }
   	echo '</form>';


		echo'</td></tr></table>';   
    echo '</div>';   

echo '</center>';
     	
?>					