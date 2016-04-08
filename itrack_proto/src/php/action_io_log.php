<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("get_all_dates_between.php");
include_once("sort_xml_vtslog.php");
//include_once("calculate_distance.php");
//include_once("read_filtered_xml.php");
//include("user_type_setting.php");

set_time_limit(300);

//date_default_timezone_set('Asia/Calcutta');

$vserial = $_POST['imei'];

$startdate = $_POST['date1'];
$enddate = $_POST['date2'];

$startdate = str_replace('/','-',$startdate); 
$enddate = str_replace('/','-',$enddate); 
$rec = $_POST['record_len'];    

//echo "<br>vserial=".$vserial." ,st=".$startdate." ,ed=".$enddate." ,rec=".$rec;

$sts_arr = array();
$msgtype_arr = array();
$ver_arr = array();
$imei_arr = array();
$vname_arr = array();
$datetime_arr = array();                        
$sup_v_arr = array();                                         
$io1_arr = array();
$io2_arr = array();
$io3_arr = array();
$io4_arr = array();
$io5_arr = array();
$io6_arr = array();
$io7_arr = array();
$io8_arr = array();                  


if($vserial)
{
  //$current_dt = date("Y_m_d_H_i_s");	
  //$xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml"; 
  //echo "<br>vserial1=".$vserial[$i];
  get_log_xml_prev($vserial, $startdate, $enddate);
}

function get_log_xml_prev($vserial, $startdate, $enddate)
{
  //echo "<br>vserial=".$vserial;
  global $DbConnection;
  $maxPoints = 1000;
	$file_exist = 0;

	/*$fh = fopen($xmltowrite, 'w') or die("can't open file 3"); // new
	fwrite($fh, "<t1>");  
	fclose($fh); */

	//echo "<br>size=".sizeof($vserial);
	//for($i=0;$i<sizeof($vserial);$i++)
	//{  	
       
    $query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial' AND status=1) AND status=1";        
    //echo $query."<br>";
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname = $row->vehicle_name;    
    
    //echo   "<br>vname =".$vname;
    //echo "<br> ,vserial".$vserial[$i]." ,st=".$startdate." ,et=".$enddate;
     
	 get_log_xml_data($vserial, $vname, $startdate, $enddate);
    //echo   "t2".' '.$i;
	//}  
 
	/*$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh); */
}

function get_log_xml_data($vserial, $vname, $startdate, $enddate)
{
  global $DbConnection;
  global $rec;
  global $sts_arr;
  global $msgtype_arr;
  global $ver_arr;
  global $imei_arr;
  global $vname_arr;
  global $datetime_arr;                        
  global $sup_v_arr;                                         
  global $io1_arr;
  global $io2_arr;
  global $io3_arr;
  global $io4_arr;
  global $io5_arr;
  global $io6_arr;
  global $io7_arr;
  global $io8_arr;

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
  $flag =0;	   
        
  //echo "<br>datesize=".$date_size;  
  /*if($date_size == 1)
    $date_limt = $date_size;
  else
    $date_limt = $date_size-1;  */
     
  //echo "<br>u[0]=".$userdates[0].", u[1]=".$userdates[1];	
  //for($i=$date_limt;$i>=0;$i--)
  $rec_count =0;
  
  //echo "<br>datesize=".$date_size;
  
  for($i=($date_size-1);$i>=0;$i--)
	{
	  //echo "<br>in date=".$userdates[$i];	  
    if( ($flag == 1) && ($rec!="all") )
	  {
	    //echo "<br>in break";
      break;   // BREAK LOOP AFTER TAKING ONE DAY RECORDS- LAST 30/100/ALL
	  }		
    
    //if($date_size == 1)
      //$userdates[$i] = $datefrom;
       
    //if($userdates[$i] == $current_date)
		//{	
    $xml_current = "../../xml_vts/xml_data/".$userdates[$i]."/".$vserial.".xml";	
    		
    //echo "<br>xml_current=".$xml_current;
    
    if (file_exists($xml_current))      
    {		    		
			//echo "<br>file exists0";
			$xml_file = $xml_current;
			$CurrentFile = 1;
		}		
		else
		{
			$xml_file = "../../sorted_xml_data/".$userdates[$i]."/".$vserial.".xml";
			$CurrentFile = 0;
		}		
		//echo "<br>xml file =".$xml_file;	
    	
    if (file_exists($xml_file)) 
		{			
		  //echo "file_exists1";
      //$current_datetime1 = date("Y_m_d_H_i_s");
		  $t=time();
      //$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vserial."_".$current_datetime1.".xml";
      $xml_original_tmp = "../../xml_tmp/original_xml/tmp_".$vserial."_".$t."_".$i.".xml";
      //$xml_log = "../../../xml_tmp/filtered_xml/tmp_".$current_datetime1.".xml";
      //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
									      
      copy($xml_file,$xml_original_tmp);
      
      $total_lines =0;
      $total_lines = count(file($xml_original_tmp));
      //echo "<br>Total lines orig=".$total_lines;
      
      $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
      //$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
      $logcnt=0;
      $DataComplete=false;
                  
      $vehicleserial_tmp=null;
     		   
      $format =2;
      
      $limit0 = $total_lines-10;
	    $limit1 = $total_lines-30;
      $limit2 = $total_lines-100;      
      
      
      if (file_exists($xml_original_tmp)) 
      {      
        //echo "file_exists2";
        
        while(!feof($xml))          // WHILE LINE != NULL
  			{
  				$line = fgets($xml); 
          //echo "<br>line";
          //echo "<textarea>".$line."</textarea>";
          $rec_count++;
            				
          //echo "<br>flag=".$flag." ,rec_count=".$rec_count." ,limit=".$limit1." rec=".$rec." strlen=".strlen($line);
          if( ( ($rec_count == $limit1) || ($total_lines<30) ) && (strlen($line)>10) && ($rec=="30") ) 
  				{                   
  				  //echo "<br>in 30 ,rec_count=".$rec_count;
            $flag =1;
  				}
  				else if( ( ($rec_count == $limit2) || ($total_lines<100) )  && (strlen($line)>10) && ($rec=="100") ) 
  				{
  				  //echo "<br>in 100";
            $flag =1;
  				}
				  else if( ( ($rec_count == $limit0) || ($total_lines<10) )  && (strlen($line)>10) && ($rec=="10") ) 
  				{
  				  //echo "<br>in 100";
            $flag =1;
  				}
          else if( (strlen($line)>10) && ($rec=="all") )
          {
  				  //echo "<br>in all";
            $flag =1;
  				}								
  				
  			  //echo "<br>flag=".$flag;
  			  
          if( ($flag == 1) && (strlen($line)>10))
          {
              //echo "<br>flag".$rec_count;
              $DataValid = 0;   				
      
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

          		$line_1 = preg_replace('/ /', '', $line);
          		$line_1 = preg_replace('/\//', '', $line_1);
          		$reg = '/^<[^<].*">$/';
                
              if(preg_match($reg, $line_1, $data_match))
              {                          				
                  //preg_match('/\d+-\d+-\d+ \d+:\d+:\d+/', $line, $str3tmp);    // EXTRACT DATE FROM LINE
                preg_match('/sts="[^"]+/', $line, $xmldatetime_tmp);
                $xmldatetime_tmp1 = explode("=",$xmldatetime_tmp[0]);
                $xml_date_current = preg_replace('/"/', '', $xmldatetime_tmp1[1]);					
      					//echo "0:xml_date_current=".$xml_date_current.'<BR>';	
      				}
      				if ($xml_date_current!=null)
      				{	
      					//echo "<br>xml_date_current=".$xml_date_current.' '."startdate=".$startdate.' '."enddate=".$enddate.'<BR>';
                if( ($xml_date_current >= $startdate && $xml_date_current <= $enddate) && ($xml_date_current!="-") )
      					{                            
                    //echo "<br>in condition";
                    $status = preg_match('/sts="[^"]+/', $line, $sts_tmp);  
                    $status = preg_match('/msgtype="[^"]+/', $line, $msgtype_tmp);    
                    $status = preg_match('/ver="[^"]+/', $line, $ver_tmp);                                                   
                    $status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);    
                    $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
                    $status = preg_match('/sup_v="[^" ]+/', $line, $sup_v_tmp);                           
                    $status = preg_match('/io1="[^" ]+/', $line, $io1_tmp);
                    $status = preg_match('/io2="[^" ]+/', $line, $io2_tmp);
                    $status = preg_match('/io3="[^" ]+/', $line, $io3_tmp);
                    $status = preg_match('/io4="[^" ]+/', $line, $io4_tmp);
                    $status = preg_match('/io5="[^" ]+/', $line, $io5_tmp);
                    $status = preg_match('/io6="[^" ]+/', $line, $io6_tmp);
                    $status = preg_match('/io7="[^" ]+/', $line, $io7_tmp);
                    $status = preg_match('/io8="[^" ]+/', $line, $io8_tmp);                    
                                                    
                    $sts_tmp1 = explode("=",$sts_tmp[0]);
                    $sts_arr[] = preg_replace('/"/', '', $sts_tmp1[1]);
  
                    $msgtype_tmp1 = explode("=",$msgtype_tmp[0]);
                    $msgtype_arr[] = preg_replace('/"/', '', $msgtype_tmp1[1]);
                    
                    $ver_tmp1 = explode("=",$ver_tmp[0]);
                    $ver_arr[] = preg_replace('/"/', '', $ver_tmp1[1]);
        
                    $vehicleserial_tmp1 = explode("=",$vehicleserial_tmp[0]);
                    $imei_arr[] = preg_replace('/"/', '', $vehicleserial_tmp1[1]);
                    
                    $vname_arr[] = $vname;
                    
                    $datetime_tmp1 = explode("=",$datetime_tmp[0]);
                    $datetime_arr[] = preg_replace('/"/', '', $datetime_tmp1[1]);                                
    
                    $sup_v_tmp1 = explode("=",$sup_v_tmp[0]);
                    $sup_v_arr[] = preg_replace('/"/', '', $sup_v_tmp1[1]);                                                

                    $io1_tmp1 = explode("=",$io1_tmp[0]);
                    $io1_arr[] = preg_replace('/"/', '', $io1_tmp1[1]);

                    $io2_tmp1 = explode("=",$io2_tmp[0]);
                    $io2_arr[] = preg_replace('/"/', '', $io2_tmp1[1]);
                    
                    $io3_tmp1 = explode("=",$io3_tmp[0]);
                    $io3_arr[] = preg_replace('/"/', '', $io3_tmp1[1]);
                    
                    $io4_tmp1 = explode("=",$io4_tmp[0]);
                    $io4_arr[] = preg_replace('/"/', '', $io4_tmp1[1]);
                    
                    $io5_tmp1 = explode("=",$io5_tmp[0]);
                    $io5_arr[] = preg_replace('/"/', '', $io5_tmp1[1]);
                    
                    $io6_tmp1 = explode("=",$io6_tmp[0]);
                    $io6_arr[] = preg_replace('/"/', '', $io6_tmp1[1]);
                    
                    $io7_tmp1 = explode("=",$io7_tmp[0]);
                    $io7_arr[] = preg_replace('/"/', '', $io7_tmp1[1]);
                    
                    $io8_tmp1 = explode("=",$io8_tmp[0]);
                    $io8_arr[] = preg_replace('/"/', '', $io8_tmp1[1]);
                                                                       
                    //echo "<br>log created";
                    /*$linetowrite = "\n< marker sts=\"".$sts."\" msgtype=\"".$msgtype."\" ver=\"".$ver."\" fix=\"".$fix."\" imei=\"".$vehicleserial."\" vname=\"".$vname."\" userid=\"".$user_id."\" datetime=\"".$datetime."\" lat=\"".$lat."\" lng=\"".$lng."\" alt=\"".$alt."\" speed=\"".$speed."\" fuel=\"".$fuel."\" vehicletype=\"".$vehicletype."\" no_of_sat=\"".$no_of_sat."\" cellname=\"".$cellname."\" distance=\"".$distance."\" io8=\"".$io8."\" sig_str=\"".$sig_str."\" sup_v=\"".$sup_v."\" speed_a=\"".$speed_a."\" geo_in_a=\"".$geo_in_a."\" geo_out_a=\"".$geo_out_a."\" stop_a=\"".$stop_a."\" move_a=\"".$move_a."\" lowv_a=\"".$lowv_a."\" />";						          					                    
                    //echo "wrote<br>";
                    fwrite($fh, $linetowrite);*/ 		
        					   ///////////////////////
                  } // if startdate and enddate end    
        				} // if xml_curren!=null closed
      			}
        }   // while closed
      }  // if original_tmp closed
			
      fclose($xml);            
      unlink($xml_original_tmp);
      
		} // if (file_exists closed
	}  // for closed 
	
	//echo "Test1";
	//fclose($fh);
}

  // echo'<div class="scrollTableContainer"><table width="100%" border="1" cellpadding="0" cellspacing="0">';
  //echo'<tr><td>';
  echo '<center>';
  echo '<div style="width:1025px;height:600px;overflow:auto;" align="center">';
  ///////////////////  READ SPEED XML 	//////////////////////////////				                      
  //$xml_path = $xmltowrite;
  //echo "<br>xml_path=".$xml_path;
  //read_datalog_report_xml($xml_path, &$sts, &$msgtype, &$ver, &$fix, &$imei, &$vname, &$datetime, &$sup_v, &$speed_a, &$geo_in_a, &$geo_out_a, &$stop_a, &$move_a, &$lowv_a);
  //////////////////////////////////////////////////////////////////////
  //echo "<br>size datalog=".sizeof($vname);         
  if(sizeof($imei_arr)==0)
  {
    echo '<br><font color=red><strong>Sorry No record found</strong></font>';
    echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=home.php\">";
  }  
  else
  {
    $sts_arr = array_reverse($sts_arr);
    $msgtype_arr = array_reverse($msgtype_arr);
    $ver_arr = array_reverse($ver_arr);    
    $imei_arr = array_reverse($imei_arr);
    $vname_arr = array_reverse($vname_arr);     
    $datetime_arr = array_reverse($datetime_arr);    
    $sup_v_arr = array_reverse($sup_v_arr);
    $io1_arr = array_reverse($io1_arr);
    $io2_arr = array_reverse($io2_arr);
    $io3_arr = array_reverse($io3_arr);
    $io4_arr = array_reverse($io4_arr);
    $io5_arr = array_reverse($io5_arr);
    $io6_arr = array_reverse($io6_arr);
    $io7_arr = array_reverse($io7_arr);
    $io8_arr = array_reverse($io8_arr);    
  }    				  
  
  $csv_string = ""; 
  
  for($i=0;$i<sizeof($imei_arr);$i++)
	{								              
    if(($i==0) || (($i>0) && ($imei_arr[$i-1] != $imei_arr[$i])) )
    {
      $sno = 1;
      $title = "VTS DataLog : ".$vname_arr[$i]." &nbsp;<font color=red>(".$imei_arr[$i].")</font>";
      echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
      echo'<form  name="text_data_report" method="post" target="_blank">
      <br><table align="center">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>                					
  
      <table border="1" id="filter_block" width="98%" rules="all" bordercolor="#e5ecf5" align="center" cellspacing="0" cellpadding="1">	
			 <tr bgcolor="#C9DDFF"> 
				<th class="text"><b><font size="1">SNo</font></b></td>   
				<th class="text"><b><font size="1">STS</font></b></td>  
				<th class="text"><b><font size="1">DateTime</font></b></td>           
				<!--<th class="text"><b><font size="1">IMEI</font></b></td>-->	  
				<th class="text"><b><font size="1">MsgTp</font></b></td>
        <th class="text"><b><font size="1">Ver</font></b></td>  
        <th class="text"><b><font size="1">SupplyV</font></b></td>  
        <th class="text"><b><font size="1">IO1</font></b></td>
        <th class="text"><b><font size="1">IO2</font></b></td>
        <th class="text"><b><font size="1">IO3</font></b></td>
        <th class="text"><b><font size="1">IO4</font></b></td>
        <th class="text"><b><font size="1">IO5</font></b></td>
        <th class="text"><b><font size="1">IO6</font></b></td>
        <th class="text"><b><font size="1">IO7</font></b></td>
        <th class="text"><b><font size="1">IO8</font></b></td>
      </tr>';      	
    }	

    if ($sno%2==0)
    {
      echo '<tr bgcolor="#F7FCFF">';
    }										
    else 
    {
      echo '<tr bgcolor="#E8F6FF">';	
    }
					                                     
    echo'<td class="text"><font size="1">'.$sno.'</font></td>';
    echo'<td class="text"><font size="1">'.$sts_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$datetime_arr[$i].'</font></td>';      
    echo'<td class="text"><font size="1">'.$msgtype_arr[$i].'</font></td>';      
    echo'<td class="text"><font size="1">'.$ver_arr[$i].'</font></td>'; 
    echo'<td class="text"><font size="1">'.$sup_v_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io1_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io2_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io3_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io4_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io5_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io6_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io7_arr[$i].'</font></td>';
    echo'<td class="text"><font size="1">'.$io8_arr[$i].'</font></td>';
   
	  echo'</tr>'; 
	  $sno++; 
    ///////////For PDF Report Only For mobile application /////////
    /*if($report_type=="Person")
    {
    echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$sts[$i]\" NAME=\"temp[$i][STS]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$datetime[$i]\" NAME=\"temp[$i][DateTime]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$msgtype[$i]\" NAME=\"temp[$i][MsgTp]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$fix[$i]\" NAME=\"temp[$i][Fix]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$lat[$i]\" NAME=\"temp[$i][Latitude]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$lng[$i]\" NAME=\"temp[$i][Longitude]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$ver[$i]\" NAME=\"temp[$i][Version]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$cellname[$i]\" NAME=\"temp[$i][CellName]\">";
		////////// For CSV Report
		$csv_string = $csv_string.$sno.','.$sts[$i].','.$datetime[$i].','.$msgtype[$i].','.$fix[$i].','.$lat[$i].','.$lng[$i].','.$ver[$i].','.$cellname[$i]."\n";
		*/
  }
  if( (($i>0) && ($imei_arr[$i+1] != $imei_arr[$i])) )
  {
  	echo '</table>';
	
  	/*if($report_type=="Person")
  	{
  	 echo'<input TYPE="hidden" VALUE="halt" NAME="csv_type">';
      echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';
    echo'<br>
  	<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size=1\');" value="Get PDF" class="noprint">&nbsp;
      <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">';
    }  */
	
  }

echo '</div></center>';
// <form  name="text_data_report" method="post" target="_blank">'
echo'</tbody></table></div></form>';

if(sizeof($imei)>0)
{
  echo'
  <script type="text/javascript">
    //alert("k");
    var table3Filters = {
  	paging:true,
  	sort_select:true,				
  	col_1:"select",
  	col_2:"select",
  	col_3:"none",
  	col_4:"none"
  }
  setFilterGrid("table1",0,table3Filters); 
  </script>
  ';
}

//unlink($xml_path);
//echo "test";

?>
