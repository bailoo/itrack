<?php
	set_time_limit(3000);
	$abspath = "/var/www/html/vts/beta/src/php/";
	include_once($abspath."/common_xml_element.php");

	include_once($abspath.'lib/BUG.php');
	include_once($abspath.'lib/Util.php');
	include_once($abspath.'lib/VTSFuel.php');
	include_once($abspath."report_title.php");
	include_once($abspath.'lib/VTSMySQL.php');
	require_once $abspath."excel_lib/class.writeexcel_workbook.inc.php";
	require_once $abspath."excel_lib/class.writeexcel_worksheet.inc.php"; 
	//include_once($abspath.'util_session_variable.php');
	//include_once($abspath.'util_php_mysql_connectivity.php');
	 
	$DBASE = "iespl_vts_beta";
	$USER = "root";
	$PASSWD = "mysql";
	//$HOST = "111.118.181.156";
	include_once("../database_ip.php");  
	$DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("Could Not Connect to Server");
	mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");

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

  //$current_date = date("Y-m-d");
  //$start_time = date("Y-m-d")." 00:00:00";
  //$end_time = date("Y-m-d")." 23:59:59";  

/*
  $current_month=date('m');
  $current_year=date('Y');
  
  if($current_month==1)
  {
    $previous_month=12;
    $previous_year = $current_year -1;
  }
  else
  {
    $previous_month=$current_month-1;
    $previous_year = $current_year;
  }
  
 
  //COMMENT THIS BLOCK -TEMPORARY CODE FOR TESTING
  $previous_month = $current_month;
  $previous_year = $current_year;
  ///////////////////////////////////////////////  
  $previous_day = date('d', strtotime(' -1 day'));
*/

$date = date('Y-m-d');
$previous_date = date('Y-m-d', strtotime($date .' -1 day'));

$date_tmp = explode('-',$previous_date);
$previous_year = $date_tmp[0];
$previous_month = $date_tmp[1];
$previous_day = $date_tmp[2]; 
 

  //echo "\nPREVYEAR=".$previous_year." ,PREV_MONTH1=".$previous_month." ,previous_day=".$previous_day; 
        
  // FUEL REPORT SECTION 
  //$imeis = explode(":",$_POST['vehicleserial']);  ////vehicleserial is actually vehicle_id for this report
	//$startDateTime = str_replace("/","-",$_POST['start_date']);
	//$endDateTime = str_replace("/","-",$_POST['end_date']);
	//$timeInterval = $_POST['user_interval'];
	
	$startDateTime = $previous_year."-".$previous_month."-".$previous_day." 00:00:00";
	$endDateTime = $previous_year."-".$previous_month."-".$previous_day." 23:59:59";
	$timeInterval = 30;
		
  $startDate = date('Y-m-d', strtotime($startDateTime));
	$endDate = date('Y-m-d', strtotime($endDateTime));
	$endDate1 = date('Y-m-d', strtotime($endDate)+(1*24*60*60));
	$timeIntervalTS = $timeInterval*60;

  
  /////********* CREATE EXCEL FILE *******************///////
  $filename_title = 'DAILY_FUEL_REPORT_VISIONMASS_'.$previous_year."-".$previous_month."-".$previous_day;  
  //$file_path = "/var/www/html/vts/beta/src/php/download/".$filename_title;
  //ONLINE
  $file_path = "/var/www/html/vts/beta/src/php/daily_report/vision_mass/excel_data/".$filename_title;
  
  //OFFLINE
  //$file_path = "D:\\SERVER_GO4HOSTING/ITRACKSOLUTION.CO.IN/MAIL_SERVICE/WOCKHARDT/REPORT_MONTHLY/REPORT3/excel_data/".$filename_title;
  
  $fname = tempnam_sfx($file_path, ".xls");    
  
  ////********** CREATE EXCEL WORKBOOK  ****************//////
  //echo "\nfname=".$fname;  
  $workbook =& new writeexcel_workbook($fname);                     //******* ADD WORKBOOK
  $worksheet =& $workbook->addworksheet("WORKSHEET1");
  //$worksheet->set_row(0, 25); 
  
  # Create a border format
	$title_format =& $workbook->addformat();
	$title_format->set_color('white');
	$title_format->set_bold();
	$title_format->set_size(8);
	$title_format->set_pattern(0x1);
	$title_format->set_fg_color('green');
	$title_format->set_border_color('yellow');
	
  $border2 =& $workbook->addformat();
  $border2->set_size(8); 
  
  $border2->set_align('center');
  $border2->set_align('vcenter');
  $border2->set_align('vjustify');
  $border2->set_text_wrap();
  
	$heading_format =& $workbook->addformat(array(
		bold    => 1,
		//italic  => 1,
		color   => 'blue',
		size    => 8,
		//font    => 'Comic Sans MS'
	));
	
  //$query1 = "SELECT account_id FROM account WHERE user_id='keonjharvts'";
  $query1 = "SELECT account_id FROM account WHERE user_id='visionmass'";
  //echo "\nq1=".$query1;
  $result1 = mysql_query($query1,$DbConnection);
  $row1 = mysql_fetch_object($result1);
  $account_id = $row1->account_id;
  
  $query2 = "SELECT vehicle_id FROM vehicle_grouping WHERE account_id='$account_id'";
  //echo "\nq3=".$query2;
  $result2 = mysql_query($query2,$DbConnection);
  $vehicle_id_str ="";
  while($row2 = mysql_fetch_object($result2))
  {
    $vehicle_id_str = $vehicle_id_str.$row2->vehicle_id.",";
  }
  $vehicle_id_str = substr($vehicle_id_str, 0, -1);
  
  //ALERT_ID=17 (fuel)
  $query = "SELECT DISTINCT vehicle_assignment.vehicle_id FROM vehicle_assignment, alert_assignment, alert WHERE ".
          "alert_assignment.alert_id = alert.alert_id AND alert.alert_id ='17' AND ".
          "vehicle_assignment.vehicle_id = alert_assignment.vehicle_id AND vehicle_assignment.status=1 AND ".
          "vehicle_assignment.vehicle_id IN($vehicle_id_str) AND alert_assignment.status=1";        
  //echo "\nquery3".$query;
  //echo "\nDBCon=".$DbConnection;
  $result = mysql_query($query,$DbConnection);
  
  while($row = mysql_fetch_object($result))
  {
    //$vid_tmp = $row->vehicle_id;
    $imeis[] = $row->vehicle_id;
  }  
?>

<?php
  $time_list = UTIL::getAllTimes($startDateTime, $endDateTime, $timeIntervalTS);
	$datetime_now = date("Y:m:d H:i:s", time()); 	
	$vsize =0; 	
  foreach($imeis as $imei)
	{ 	      
	  $fuel_data = VTSFuel::getFuelData($DbConnection, $imei, $startDate, $endDate1);		
		if(sizeof($time_list)>0)
		{
			foreach($time_list as $datetime)
			{
				if(strtotime($datetime) <= strtotime($datetime_now))
				{
					$fuel_datetime =  VTSFuel::interpolateFuelData($fuel_data, $datetime);
					if($fuel_datetime >= 0)
					{
						$fuel[$imei][$datetime] = $fuel_datetime;
					}
				}
			}
		}		
	 $vsize++;	
	}

        
  $csv_string = ""; 
  $cnt=0;  
  $x=0;                         
  $imei_size =0; 
  
  $fuel_io_global1 = VTSFuel::$fuel_io_global;
  $max_fuel_io1 = VTSFuel::$max_fuel_io;
  $max_fuel_value1 = VTSFuel::$max_fuel_value;
                                    
  $r=0;
  $c=0;  
    
  foreach($imeis as $imei)
  {
    $imei_size =1;    $fuel_size =0;
    foreach($fuel[$imei] as $datetime => $fuel_imei)
    {
    $fuel_size=1;
    }
    if($fuel_size)
    {
      $vname = VTSMySQL::getVehicleNameOfVehicle($DbConnection, $imei);
      $imei1 = VTSMySQL::getIMEIOfVehicle($DbConnection, $imei);
                 		
      $spaces="                                             ";          		
      $title = $vname."(".$imei1.") Fuel Report For : ".$startDateTime."-".$endDateTime.$spaces;         
      
			$c=0;      
			$worksheet->write      ($r, 0, $title, $title_format);
			for($b=1;$b<=6;$b++)
			{
				$worksheet->write_blank($r, $b, $title_format);
			}                   
			$r++;
			
			/*$title = $vname." (".$imei1.")";
      $c=0;      
      $worksheet->write($r,$c, "", $title_format);
      $c++;    
      $worksheet->write($r,$c, $title, $title_format);
      $c++;
      $worksheet->write($r,$c, "", $title_format);
      $c++;
      $r++; */             
      
      $c=0;
      $worksheet->write($r,$c, "Sr.No", $heading_format);
      $worksheet->set_column($c, $c, 6);
      $c++;
      $worksheet->write($r,$c, "Date Time ", $heading_format);
      $worksheet->set_column($c, $c, 20);
      $c++;
      $worksheet->write($r,$c, "Fuel", $heading_format);
      $worksheet->set_column($c, $c, 15);
      $c++;
      $r++;

      $sno=1;  $y=0;         
      foreach($fuel[$imei] as $datetime => $fuel_imei)
      {                                                                                         
        $cnt++;
        if($fuel_imei<1000)
        {
          /*if(($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189")
          {
            echo "<br>fuel_io_global1:".$fuel_io_global1." ,max_fuel_io1:".$max_fuel_io1." ,max_fuel_value1:".$max_fuel_value1." ,fuel_imei:".$fuel_imei;
          }*/                         
          $fuel_imei_1 = $fuel_imei;
          if($fuel_imei_1 > $max_fuel_value1)
          {
            //if(($_SERVER['HTTP_X_FORWARDED_FOR']) == "172.26.48.189") echo "<br>IN";
            $fuel_imei_1 = $max_fuel_value1;
          }                      
          
          /*echo'<tr>
            <td class="text" align="left"><b>'.$cnt.'</b></td>
            <td class="text" align="left">'.$datetime.'</td>
            <td class="text" align="left">'.$fuel_imei_1.'</td>
          </tr>'; */
              
          //echo "\nSerial=".$cnt." ,row=".$r." ,column=".$c." ,datetime=".$datetime." ,fuel=".$fuel_imei_1;
          $c=0;
          $worksheet->write($r,$c, $cnt, $border2);                    
          $c++;                       
          $worksheet->write($r,$c, $datetime, $border2);        
          $c++;
          $worksheet->write($r,$c, $fuel_imei_1, $border2);        
          $c++;
          
          $r++;
          $serial++;
                                             		
          $y++;
          $sno++;
        }             		
      }
      
      $r++;
      $r++;
      
      $cnt=0;    		
      $x++;
    } // if fuel_size closed
  }	
  
$workbook->close(); //CLOSE WORKBOOK
//echo "\nWORKBOOK CLOSED";
 
 
//########### SEND MAIL ##############//
//$to = 'rizwan@iembsys.com';
$to = 'info@visionmass.com';   
$subject = $filename_title;
$message = $filename_title; 
$random_hash = md5(date('r', time()));  
$headers = "From: support@iembsys.co.in\r\n";
//$headers .= "Reply-To: taseen@iembsys.com\r\n"; 
$headers .= "Cc: jyoti.jaiswal@iembsys.com";
//$headers .= "Cc: rizwan@iembsys.com";
//$headers .= "Cc: rizwan@iembsys.com,jyoti.jaiswal@iembsys.com";
$headers .= "\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-".$random_hash."\""; 
$filename_title = $filename_title.".xls";
$file_path = $file_path.".xls";

//echo "\nFILE PATH=".$file_path;

include_once("send_mail_api.php");
//####################################//

unlink($file_path);  
   
?>
