<?php
include_once('main_vehicle_information_1.php');
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('common_xml_element.php');
include_once('calculate_distance.php');

$pathtowrite = $_REQUEST['xml_file']; 
$mode = $_REQUEST['mode'];
$vserial = $_REQUEST['vserial'];
$startdate = $_REQUEST['startdate'];
$enddate = $_REQUEST['enddate'];

set_time_limit(100);
//date_default_timezone_set('Asia/Calcutta');
$current_date=date("Y-m-d");

$fh = fopen($pathtowrite, 'w') or die("can't open file 1"); // new
fwrite($fh, "<t1>");  
fclose($fh);
$vserial_arr = explode(',',$vserial);
$vname1 ="";
//echo "t1=";
for($i=0;$i<sizeof($vserial_arr);$i++)
{
	$tmp = explode('#',$vserial_arr[$i]);
	$imei = $tmp[0];
	$last_time = $tmp[1];
	$vehicle_info=get_vehicle_info($root,$imei);
	$vehicle_detail_local=explode(",",$vehicle_info);	
	get_vehicle_last_data($current_date, $imei, $last_time, $vehicle_detail_local[0],$vehicle_detail_local[1], $pathtowrite);
}

$fh = fopen($pathtowrite, 'a') or die("can't open file 2"); //append
fwrite($fh, "\n<a1 datetime=\"unknown\"/>");       
fwrite($fh, "\n</t1>");  
fclose($fh);
  
$d=-1;

function get_vehicle_last_data($current_date, $imei, $last_time, $vname, $vtype, $pathtowrite)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
global $old_xml_date;
	//date_default_timezone_set('Asia/Calcutta');
  $current_time = date('Y-m-d H:i:s');
   $current_date_this = date('Y-m-d');
  global $d;
	$d++;
	
  $xml_file = "../../../xml_vts/xml_last/".$imei.".xml";
	$file = file_get_contents($xml_file);
  if(!strpos($file, "</t1>")) 
  {
    usleep(1000);
  }		
  
  $t=time();
  $rno = rand();			
	$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$rno.".xml";		
	copy($xml_file,$xml_original_tmp); 
	    
	if (file_exists($xml_original_tmp))
	{
	//echo "<br>exist2";
    $fexist =1;
		$fp = fopen($xml_original_tmp, "r") or $fexist = 0;   
		$total_lines =0;
		$total_lines = count(file($xml_original_tmp));
		//echo "<br>total_lines=".$total_lines;
		$c =0;
			set_master_variable($current_date_this);
		while(!feof($fp)) 
		{
			$line = fgets($fp);
			//echo "line=".$line;
			$c++;				
			//echo"vd=".$vd;
			if(strlen($line)>15)
			{
				if ( (preg_match('/'.$vd.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/'.$ve.'="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
				{
            $status = preg_match('/'.$vh.'="[^"]+/', $line, $datetime_tmp);
            $datetime_tmp1 = explode("=",$datetime_tmp[0]);
            $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
            $xml_date = $datetime;
            
            $status = preg_match('/'.$vf.'="[^"]+/', $line, $speed_tmp);
            $speed_tmp1 = explode("=",$speed_tmp[0]);
            $speed = preg_replace('/"/', '', $speed_tmp1[1]);
            
            $status = preg_match('/'.$vd.'="[^"]+/', $line, $lat_tmp);
            $lat_tmp1 = explode("=",$lat_tmp[0]);
            $lat = preg_replace('/"/', '', $lat_tmp1[1]);
            
            $status = preg_match('/'.$ve.'="[^"]+/', $line, $lng_tmp);
            $lng_tmp1 = explode("=",$lng_tmp[0]);
            $lng = preg_replace('/"/', '', $lng_tmp1[1]);
                        
            $status = preg_match('/'.$vs.'="[^"]+/', $line, $day_max_speed_tmp);
            $day_max_speed_tmp1 = explode("=",$day_max_speed_tmp[0]);
            $day_max_speed = preg_replace('/"/', '', $day_max_speed_tmp1[1]);

            $status = preg_match('/'.$vt.'="[^"]+/', $line, $day_max_speed_time_tmp);
            $day_max_speed_time_tmp1 = explode("=",$day_max_speed_time_tmp[0]);
            $day_max_speed_time = preg_replace('/"/', '', $day_max_speed_time_tmp1[1]);

            $status = preg_match('/'.$vu.'="[^"]+/', $line, $last_halt_time_tmp);
            $last_halt_time_tmp1 = explode("=",$last_halt_time_tmp[0]);
            $last_halt_time = preg_replace('/"/', '', $last_halt_time_tmp1[1]);                                                                 
                                                                  					             
            $xml_date_sec = strtotime($xml_date);
			$last_halt_time_sec = strtotime($last_halt_time);			
            $current_time_sec = strtotime($current_time);
            
            //////////////////////////////////////////
            //$diff = ($current_time_sec - $xml_date_sec);   // IN SECONDS
			$diff = ($current_time_sec - $last_halt_time_sec); 
            //echo "<br>Diff in Get filteredXml=".$diff;             
            //$status = "Stopped";                             
            //echo "<br>diff=".$diff." ,speed=".$speed;            
            //if( ($diff < 120) && ($lat!="" || $lng!="") && ($speed>=5) )         //< 2 min
            //if( ($diff < 180) && ($lat!="" || $lng!="") )
           //echo "diff=".$diff;
            //if($speed>=10 && $diff <=180)
            if($speed>=5 && $diff <=600)
	        {
              $status = "Running";
              //echo "<br>Running";
            }               
            /*else if((($diff < 120) || ($diff >180 && $diff <1200)) && ($speed<10))      //>2 and <20 min
            {
              $status = "Idle";
              //echo "<br>Idle";
            }
            //else if(($diff >1200) && ($speed <10))               //>20 min
            else if($diff >1200)        //>20 min
            {
              $status = "Stopped";
              //echo "<br>Stopped";
            } */
            else
            {
              $status = "Stopped";
            }                           
            if($current_date_this<$old_xml_date)
			{
				//echo "in replace 1";
				$line=str_replace("marker","x",$line);
				$line=str_replace("msgtype=","a=",$line);
				//$line=str_replace("vehicleserial=","v=",$line);
				$line=str_replace("ver=","b=",$line);
				$line=str_replace("fix=","c=",$line);
				$line=str_replace("lat=","d=",$line);
				$line=str_replace("lng=","e=",$line);
				$line=str_replace("day_max_speed=","s=",$line);				
				$line=str_replace("sts=","g=",$line);
				$line=str_replace("datetime=","h=",$line);
				$line=str_replace("io1=","i=",$line);
				$line=str_replace("io2=","j=",$line);
				$line=str_replace("io3=","k=",$line);
				$line=str_replace("io4=","l=",$line);
				$line=str_replace("io5=","m=",$line);
				$line=str_replace("io6=","n=",$line);
				$line=str_replace("io7=","o=",$line);
				$line=str_replace("io8=","p=",$line);
				$line=str_replace("sig_str=","q=",$line);
				$line=str_replace("sup_v=","r=",$line);
				$line=str_replace("speed=","f=",$line);
				$line=str_replace("day_max_speed_time=","t=",$line);
				$line=str_replace("last_halt_time=","u=",$line);
				$line=str_replace("cellname=","ab=",$line);
			}
            $line = substr($line, 0, -3);
			//echo $line;
           $line2 = "\n".$line.' v="'.$imei.'" w="'.$vname.'" y="'.$vtype.'" aa="'.$status.'"/>';                          									
		   //echo "line2=".$line2;
			//	$line2 = "\n".$line.' w="'.$vname.'" y="'.$vtype.'" aa="'.$status.'"/>';                          									
				}																			
			}			
		}
		
		//echo "<br>pathtowrite1:".$pathtowrite."<br>";			
    $len = strlen($line2);
    if($len>0)
		{
      //echo "<br>pathtowrite2:".$pathtowrite."<br>";				
      $fh = fopen($pathtowrite, 'a') or die("can't open file pathtowrite"); //append
      //$fh = fopen($pathtowrite, 'w') or die("can't open file 1");
      
			fwrite($fh, $line2);  
			fclose($fh);
			
			fclose($fp);
      unlink($xml_original_tmp);
			//break;
		}
		else
		{
		 fclose($fp);
		 unlink($xml_original_tmp);
    }							
	}	
}

?>
