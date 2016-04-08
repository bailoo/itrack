<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(300);

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

//////// INITIALISE ALL GLOBAL VARIABLES
//================================

$route_id = "";
$route_name = "";

$vname_g = array();
$vserial_g = array();
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
  $query = "SELECT vehicle_name FROM vehicle WHERE ".
  " vehicle_id=(SELECT vehicle_id FROM vehicle_assignment ".
  "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
  //echo $query;
  $result = mysql_query($query, $DbConnection);
  $row = mysql_fetch_object($result);
  $vname[$i] = $row->vehicle_name;
}

$current_dt = date("Y_m_d_H_i_s");	
$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";

write_sector_graph_xml($vserial, $vname, $date1, $date2, $user_interval, $xmltowrite);

function write_sector_graph_xml($vserial, $vname, $startdate, $enddate, $user_interval, $xmltowrite)
{
  $maxPoints = 1000;
	$file_exist = 0;

	$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh);

	//$i=0;
	for($i=0;$i<sizeof($vserial);$i++)
	{  	
     //echo "<br>vserial[i] =".$vserial[$i];
     get_route_and_sector_info($vserial[$i]); // GET ROUTE AND SECTOR INFO          
     get_sector_xml_data($vserial[$i], $vname[$i], $startdate, $enddate,$user_interval, $xmltowrite);  // GET SORTED SECTOR_DATA     
	}
  plot_sector_graph();  // PLOT SECTOR GRAPH  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}

function get_sector_xml_data($vehicle_serial, $vname, $startdate, $enddate, $user_interval, $xmltowrite)
{
	global $DbConnection;
	global $account_id;
	global $geo_id1;
		
  global $vname_g;
  global $vserial_g;
  
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
                
                $status = preg_match('/speed="[^" ]+/', $line, $speed_tmp);
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
                  
                  $vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
                  $vserial = preg_replace('/"/', '', $vehicleserial_tmp1[1]);
                  
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
                    // UPDATE PREVIOUS AND LAST SECTOR
                    update_prev_last_sectors($prev_sector_id,$prev_sector_sequence,$prev_sector_name,$prev_datetime,$datetime,$last_sector_id,$last_sector_sequence,$last_sector_name);                                                  
                    
                    //echo "<br>vname=".$vname." ,prev_dt1=". $prev_datetime; 
                    //if($_SERVER['HTTP_X_FORWARDED_FOR'] == "172.26.48.181")
                    /*if($_SERVER["REMOTE_ADDR"] == "202.3.77.206")                    
                    {
                      echo "<br>vname=".$vname." ,prev_sector=".$prev_sector_name." (".$prev_sector_sequence.")"; 
                      echo "<br>vname=".$vname." ,current_sector=".$last_sector_name." (".$last_sector_sequence.")<br><br>";
                    } */            
                                                                  	                                            	                        
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
  
  if ($prev_sector_id_u != $last_sector_id_u)
	{                                           
    //echo "IN";
    $vname_g[] = $vname;
    $vserial_g[] = $vserial;
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
  }      			  				
	
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
			
      //if($_SERVER["REMOTE_ADDR"] == "115.248.114.51")
        //echo "<br>sector_query2=".$query2;
        
      $result2 = mysql_query($query2,$DbConnection);
			if($row2 = mysql_fetch_object($result2))
			{
				$sector_name[$i] = $row2->sector_name;
				$sector_coord[$i] = $row2->sector_coord;
				$sector_id[$i] = $sector_id3[0];
				$sequence[$i] = $sector_id3[1];				
				
       // if($_SERVER["REMOTE_ADDR"] == "115.248.114.51")
         // echo "<br>SectorString: name=".$sector_name[$i]." ,id=".$sector_id[$i]." ,seq=".$sequence[$i];
			}			
			$i++;
		}		
	}

    /*if($_SERVER["REMOTE_ADDR"] == "115.248.114.51")
    {
      echo "<br>Before:";
      for($a=0;$a<sizeof($sequence);$a++)
      {
        echo "Seq".$sequence[$a]." ,id=".$sector_name[$a]." ,id=".$sector_id[$a]."<br>";
      }
    } */
    
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
      
      if ($sequence_tmp1 >$tmp_sequence)
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
   
    if($_SERVER["REMOTE_ADDR"] == "115.248.114.51")
    {
      echo "<br>After:";
      for($a=0;$a<sizeof($sequence);$a++)
      {
        echo "Seq".$sequence[$a]." ,id=".$sector_name[$a]." ,id=".$sector_id[$a]."<br>";
      }
    }
               
} // function closed                                                                                                                                                           
	

function plot_sector_graph()
{   
  global $date1;
  global $date2;
    
  global $vname_g;
  global $vserial_g;
  
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
    
  $vname_skip = array();
  /*if($_SERVER["REMOTE_ADDR"] == "202.3.77.206")
  {     
      echo "<br>=========================================<br>sizevname=".sizeof($vname_g);
      
      for($i=0;$i<sizeof($vname_g);$i++)
      {
        echo "<br>prev=".$prev_sector_id_u." ,last=".$last_sector_id_u;                                                   
        echo "vname_g=".$vname_g[$i]."<br>";
        echo "vserial_g=".$vserial_g[$i]."<br>";
        echo "prev_sector_id_e=".$prev_sector_id_e[$i]."<br>";
        echo "prev_sector_sequence_e=".$prev_sector_sequence_e[$i]."<br>";
        echo "prev_sector_name_e=".$prev_sector_name_e[$i]."<br>";
        echo "prev_datetime_e=".$prev_datetime_e[$i]."<br>";                   
                            
        echo "datetime_entered=".$datetime_entered[$i]."<br>";
        echo "sector_id_entered=".$sector_id_entered[$i]."<br>";
        echo "sector_sequence_entered=".$sector_sequence_entered[$i]."<br>";
        echo "sector_name_entered=".$sector_name_entered[$i]."<br>";
      }      	  
  } */
  //PRINT SECTOR IMAGES
  //echo "<center><br><br><br><strong>Vehicle :</strong><font color=green><strong>".$vname."</strong></font> (<font color=red><strong>".$vserial."</strong></font>)";
  report_title("Sector Change Report",$date1,$date2);
  echo "<br><center><strong>RouteName : </strong><font color=green><strong>".$route_name." </strong></font><br></center>";
                                          
  echo "<br><center><font color=red>(&nbsp;Note :This Report shows last sector changes in graph <br>mouseover on dots to see vehicle name<br>Click on dot to see full detail &nbsp;)</font><br></center>";
  //echo "<br> sector_size_main=".sizeof($sector_name);
  //echo "<br> sector_size_entered=".sizeof($sector_id_entered);
                           
  $size_main = sizeof($sector_id);
  $size_entered = sizeof($sector_id_entered);
  
  echo '<div id="sector_area" align="center" style="position:absolute; left:800px; bottom:90px;"></div>';
	    
  echo "<table align='center'><tr>"; 
  
  //////// VEHICLE WILL LIE IN EITHER OF THE TWO SECTOR BLOCKS
  
  //1.************ W->E ****FIRST BLOCK*************************                      
  $up_route =0;
  
  //for($y=($size_main-2);$y>=0;$y--)
  for($y=1;$y<$size_main;$y++)
  {                              	                  								                  								
     if($_SERVER["REMOTE_ADDR"] == "115.248.114.51")
      echo "ASeq".$sequence[$y]."<br>";
    
    $vname_g_found = array();
    $p=0;
    
    if($sector_coord_decoded[$y]!="")
		{
        $flag_found = 0;
        for($z=0;$z<sizeof($sector_id_entered);$z++)
        {
          if( ($sector_id_entered[$z] == $sector_id[$y]) && ($sector_sequence_entered[$z] == $sequence[$y]) )
          {
            if($sector_sequence_entered[$z] > $prev_sector_sequence_e[$z])
            {
              $flag_found = 1;
              $vname_g_found[$p] = $vname_g[$z]; 
              $vname_skip[] = $vname_g[$z];
              $prev_sector_name_found[$p] = $prev_sector_name_e[$z];
              $prev_datetime_found[$p] = $prev_datetime_e[$z];             
              $prev_sector_seq_found[$p] = $prev_sector_sequence_e[$z]; 
              
              $current_sector_found[$p] = $sector_name_entered[$z];
              $current_datetime_found[$p] = $datetime_entered[$z];
              $current_sector_seq_found[$p] = $sector_sequence_entered[$z];
              
              $p++;             
              //$up_route =1;
            }                      
          }              
        }
       

        //echo '<!--<span class="vText1">-->'.$datetime_found.'<!--</span>-->';        
        if($flag_found)
        {
          $img = "we".$sequence[$y].".png";
          echo '<td>';
          
          for($q=0;$q<$p;$q++)
          {
            if($q==0) { $dotimg = "dot1.png"; }
            else if($q==1) { $dotimg = "dot3.png"; }
            else {$dotimg = "dot2.png"; }
                    
            $reverse = strrev( $vname_g_found[$q] );
            $lastchars = $reverse{3}.$reverse{2}.$reverse{1}.$reverse{0};

            echo '<a style="text-decoration:none;font-size:small;" href="javascript:showSectorInfo(\''.$vname_g_found[$q].'\',\''.$prev_sector_seq_found[$q].'\',\''.$prev_sector_name_found[$q].'\',\''.$prev_datetime_found[$q].'\',\''.$current_sector_seq_found[$q].'\',\''.$current_sector_found[$q].'\',\''.$current_datetime_found[$q].'\');"><img src="./images/report_icons/sector_graph/'.$dotimg.'" title="'.$vname_g_found[$q].'" style="cursor: pointer" >'.$lastchars.'</a>';            
          }
          
          echo'<img src="./images/report_icons/sector_graph/'.$img.'"></td>';                                                                
        }
        else
        {
          $img = "we".$sequence[$y].".png";
          echo '<td><img src="./images/report_icons/sector_graph/'.$img.'"></td>';                                                                
        }            
    }
  }      
  echo "</tr>";           
  
  
  //2.************ E->W **********SECOND BLOCK********************   
  $colspan = $size_main-1;
  echo "<tr>";                                          
  echo '<td colspan="'.$colspan.'" align="left"><img src="./images/report_icons/sector_graph/sn1.png"></td>';     
  echo '<td align="right"><img src="./images/report_icons/sector_graph/ns1.png"></td>';   
  echo "</tr>";
   
  echo "<tr>"; 
  //for($y=($size_main-2);$y>=0;$y--)
  for($y=1;$y<$size_main;$y++)
  {                              	                  								                  								    
     if($_SERVER["REMOTE_ADDR"] == "115.248.114.51")
      echo "BSeq".$sequence[$y]."<br>";
          
    $vname_g_found = array();
    $p=0;
        
    if($sector_coord_decoded[$y]!="")
		{
        $flag_found = 0;
        $vflag = 1;
        
        for($z=0;$z<sizeof($sector_id_entered);$z++)
        {
          if( ($sector_id_entered[$z] == $sector_id[$y]) && ($sector_sequence_entered[$z] == $sequence[$y]))
          {
            if($sector_sequence_entered[$z] < $prev_sector_sequence_e[$z])
            {
              for($s=0;$s<sizeof($vname_skip);$s++)
              {
                if($vname_g[$z] == $vname_skip[$s])
                {
                  $vflag =0;
                  break;
                }
              }
              
              if($vflag)             
              {  
                $flag_found = 1;
                $vname_g_found[$p] = $vname_g[$z]; 
                
                $prev_sector_name_found[$p] = $prev_sector_name_e[$z];
                $prev_datetime_found[$p] = $prev_datetime_e[$z];             
                $prev_sector_seq_found[$p] = $prev_sector_sequence_e[$z]; 
                
                $current_sector_found[$p] = $sector_name_entered[$z];
                $current_datetime_found[$p] = $datetime_entered[$z];
                $current_sector_seq_found[$p] = $sector_sequence_entered[$z];
                
                $p++;
              }                   
            }
          }              
        }  // for z closed
        
        if($flag_found)
        {
          $img = "ew".$sequence[$y].".png";
          echo '<td><img src="./images/report_icons/sector_graph/'.$img.'">';
          
          for($q=0;$q<$p;$q++)
          {
            if($q==0) { $dotimg = "dot1.png"; }
            else if($q==1) { $dotimg = "dot3.png"; }
            else {$dotimg = "dot2.png"; }

            //GET LAST 4 CHARACTERS OF VEHICLENAME           
            $reverse = strrev( $vname_g_found[$q] );
            $lastchars = $reverse{3}.$reverse{2}.$reverse{1}.$reverse{0};
            
            echo '<a style="text-decoration:none;font-size:small;" href="javascript:showSectorInfo(\''.$vname_g_found[$q].'\',\''.$prev_sector_seq_found[$q].'\',\''.$prev_sector_name_found[$q].'\',\''.$prev_datetime_found[$q].'\',\''.$current_sector_seq_found[$q].'\',\''.$current_sector_found[$q].'\',\''.$current_datetime_found[$q].'\');"><img src="./images/report_icons/sector_graph/'.$dotimg.'" title="'.$vname_g_found[$q].'" style="cursor: pointer" >'.$lastchars.'</a>';
          }
          
          echo '</td>';
        }
        else
        {  
          $img = "ew".$sequence[$y].".png";
          echo '<td><img src="./images/report_icons/sector_graph/'.$img.'"></td>';
        }                                                                
    }
  }      
  
  echo "</tr></table>";      

  echo "<input type='hidden' id='display_flag' value='0' />";
  
  echo "<br><br><table align='left'>";
  echo "<tr><td width='50%'></td><td colspan='2' align='left'><h3>Sector Legend ......</h3></td></tr>";
  //echo "<tr><td align='center'><font color=blue><strong>Sequence</strong></font></td><td style='height:10px'><font color=blue><strong>Sector Name</strong></font></td></tr>";
  
  $x = 0;
  //for($y=($size_main-1);$y>=0;$y--)
  for($y=0;$y<$size_main;$y++)
  {
    $x++;   
     
    if($prev_sector_name_e_found[$y]!="" && $prev_datetime_found[$y]!="")
    {            
      if($prev_sector_seq_entered[$y] > $current_sector_sequence_entered[$y])
      {     
        //PREVIOUS SECTOR
        echo "<tr><td></td><td align='center'><font color=green><strong>".$current_sector_sequence_entered[$y]."</strong></font>.</td><td><font color=green><strong>".$sector_name[$y]."</strong></font>&nbsp;&nbsp;&nbsp;<strong>(&nbsp;DateTime:</strong> <font color=red><strong>".$datetime_found[$y]."</strong></font><strong>&nbsp;)&nbsp;&nbsp;&nbsp;<!---current--></strong></td></tr>";
        $x++;
        echo "<tr><td></td><td align='center'><font color=red><strong>".$prev_sector_seq_entered[$y]."</strong></font>.</td><td><font color=green><strong>".$prev_sector_name_e_found[$y]."</strong></font>&nbsp;&nbsp;&nbsp;<strong>(&nbsp;DateTime:</strong> <font color=red><strong>".$prev_datetime_found[$y]."</strong></font><strong>&nbsp;)&nbsp;&nbsp;&nbsp;<!---previous--></strong></td></tr>";
        //NEXT SECTOR                
        $y--;
      }
      else
      {
        //PREVIOUS SECTOR
        echo "<tr><td></td><td align='center'><font color=red><strong>".$prev_sector_seq_entered[$y]."</strong></font>.</td><td><font color=green><strong>".$prev_sector_name_e_found[$y]."</strong></font>&nbsp;&nbsp;&nbsp;<strong>(&nbsp;DateTime:</strong> <font color=red><strong>".$prev_datetime_found[$y]."</strong></font><strong>&nbsp;)&nbsp;&nbsp;&nbsp;<!---previous--></strong></td></tr>";
        //NEXT SECTOR
        $x++;
        echo "<tr><td></td><td align='center'><font color=green><strong>".$current_sector_sequence_entered[$y]."</strong></font>.</td><td><font color=green><strong>".$sector_name[$y]."</strong></font>&nbsp;&nbsp;&nbsp;<strong>(&nbsp;DateTime:</strong> <font color=red><strong>".$datetime_found[$y]."</strong></font><strong>&nbsp;)&nbsp;&nbsp;&nbsp;<!---current--></strong></td></tr>";
        $y--;      
      }
    }
    else
    {
      echo "<tr><td></td><td align='center'><font color=red><strong>".$sequence[$y]."</strong></font>.</td><td><font color=green><strong>".$sector_name[$y]."</strong></font>&nbsp;&nbsp;&nbsp;<strong></td></tr>";
    }
  }
  echo "</table>";	
} // function plot sector graph closed      


echo '</center>';      	
?>					