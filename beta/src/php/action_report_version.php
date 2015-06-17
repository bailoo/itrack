<?php
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$root=$_SESSION["root"];

set_time_limit(200);
include_once('common_xml_element.php');
include_once("read_filtered_xml.php");
include("user_type_setting.php");

$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':',$device_str);
$vsize=count($vserial);

if($vsize>0)
{
  for($i=0;$i<$vsize;$i++)
  {
    /*$query = "SELECT vehicle_name FROM vehicle WHERE ".
    " vehicle_id IN(SELECT vehicle_id FROM vehicle_assignment ".
    "WHERE device_imei_no='$vserial[$i]' AND status=1) AND status=1";
    //echo $query."<br>";
    $result = mysql_query($query, $DbConnection);
    $row = mysql_fetch_object($result);
    $vname[$i] = $row->vehicle_name;*/
    $vehicle_info=get_vehicle_info($root,$vserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $vname[$i] = $vehicle_detail_local[0];
  }
  
  $current_dt = date("Y_m_d_H_i_s");	
  $xmltowrite = "../../../xml_tmp/filtered_xml/tmp_".$current_dt.".xml";
  write_version_xml($vserial, $vname, $xmltowrite);
}

function write_version_xml($vserial, $vname, $xmltowrite)
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
     get_version_data($vserial[$i], $vname[$i], $xmltowrite);
    //echo   "t2".' '.$i;
	}  
 
	$fh = fopen($xmltowrite, 'a') or die("can't open file 4"); //append
	fwrite($fh, "\n</t1>");  
	fclose($fh);
}


function get_version_data($vehicle_serial, $vname, $xmltowrite)
{
	global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
	global $old_xml_date;
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

	//date_default_timezone_set("Asia/Calcutta");
	$current_date = date("Y-m-d");
	//print "<br>CurrentDate=".$current_date;

	$fh = fopen($xmltowrite, 'a') or die("can't open file 6"); //append

	$j = 0;
 									
	global $DbConnection;
	global $account_id;
  
  $xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_serial.".xml";	
  				
	//echo "<br>xml in get_halt_xml_data =".$xml_file;	   	
  if (file_exists($xml_file)) 
	{			
    $t=time();
    $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$j.".xml";

    //$xml_unsorted = "../../../xml_tmp/unsorted_xml/tmp_".$vehicle_serial."_".$t."_".$j."_unsorted.xml";
		//echo  "<br>".$xml_file." <br>".$xml_unsorted."<br><br>";
		        
    copy($xml_file,$xml_original_tmp);        // MAKE UNSORTED TMP FILE
    //SortFile($xml_unsorted, $xml_original_tmp);    // SORT FILE
		//unlink($xml_unsorted);                // DELETE UNSORTED TMP FILE
    
    $total_lines = count(file($xml_original_tmp));
    //echo "<br>Total lines orig=".$total_lines;
    
    $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
    //$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
    $logcnt=0;
    $DataComplete=false;                  
    $vehicleserial_tmp=null;
    $format =2;
      
    $datetime = null;
    $hrs_min = null;
    $j=0; 
    $v=0;
    $f = 0;     
    
    if (file_exists($xml_original_tmp)) 
    {
      //echo "<br>file exists";              
      $entered_flag = 0;
      set_master_variable($current_date);
      while(!feof($xml))          // WHILE LINE != NULL
			{
				$DataValid = 0;
        //echo fgets($file). "<br />";
				$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE			
				
				if(strlen($line)>20)
				{
            //echo "<br>One";             
            /*$status = preg_match('/vehicleserial="[^" ]+/', $line, $vehicleserial_tmp);
            //echo "Status=".$status.'<BR>';
            //echo "test1".'<BR>';
            if($status==0)
            {
              continue;
            }*/
            
            $status = preg_match('/'.$vb.'="[^" ]+/', $line, $version_tmp);
            if($status==0)
            {
              continue;               
            }     
            $vserial=$vehicle_serial;
         

            $version_tmp1 = explode("=",$version_tmp[0]);
            $version = preg_replace('/"/', '', $version_tmp1[1]);             
                              
            if($version)
            {                                          		
                $version_data = "\n<marker imei=\"".$vserial."\" vname=\"".$vname."\" version=\"".$version."\"/>";
                $linetowrite = $version_data;
                fwrite($fh, $linetowrite);
                $version =0;
                break; 		                		                     
            }  // version closed                                  	                         																                              										                                 				
  				}   // if xml_date!null closed
  			 $j++;
  			 $f++;
        }   // while closed
      } // if original_tmp closed 
			
     fclose($xml);            
		 unlink($xml_original_tmp);
		} // if (file_exists closed
	//echo "Test1";
	fclose($fh);
}
	
echo '<center><h3>Version Report</h3><br>';

echo '<div align="center" style="width:100%;height:450px;overflow:auto;">'; 					
///////////////////  READ SPEED XML 	//////////////////////////////				                      
$xml_path = $xmltowrite;
//echo "<br>xml_path=".$xml_path;
read_version_xml($xml_path, &$imei, &$vname, &$version);
//convert_in_two_dimension
//echo "<br><br>size, vname=".sizeof($vname);
//////////////////////////////////////////////////////////////////////
$j=-1;

echo '<table border=1 width="60%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>    
    <tr>
				<td class="text" align="left" width="10%"><b>SNo</b></td>														
				<td class="text" align="left" width="20%"><b>IMEI</b></td>
				<td class="text" align="left"><b>VehicleName</b></td>
				<td class="text" align="left" width="15%"><b>Version</b></td>				
    </tr>';	

$sno = 1;
for($i=0;$i<sizeof($imei);$i++)
{				        														
	echo'<tr><td class="text" align="left" width="10%"><b>'.$sno.'</b></td>';
	echo'<td class="text" align="left">'.$imei[$i].'</td>';	
	echo'<td class="text" align="left">'.$vname[$i].'</td>';	
	echo'<td class="text" align="left">'.$version[$i].'</td>';							
	echo'</tr>';							
  $sno++;      				  				
}

echo '</table>';							
//PDF CODE

echo '<form method = "post" target="_blank">';
$csv_string = "";
    
for($i=0;$i<sizeof($imei);$i++)
{	                   
    if($i==0)
    {
    	$title="Version Report";
    	//echo "<br>pl=".$pdf_place_ref;
    	$csv_string = $csv_string.$title."\n";
    	$csv_string = $csv_string."SNo,IMEI, VehicleName, Version\n";
      echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
    }
    														
    $sno_1 = $i+1;										
    echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$i][SNo]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$imei[$i]\" NAME=\"temp[$i][IMEI]\">";
    echo"<input TYPE=\"hidden\" VALUE=\"$vname[$i]\" NAME=\"temp[$i][VehicleName]\">";    
    echo"<input TYPE=\"hidden\" VALUE=\"$version[$i]\" NAME=\"temp[$i][Version]\">";
    
    $csv_string = $csv_string.$sno_1.','.$imei[$i].','.$vname[$i].','.$version[$i]."\n"; 
    ////////////////////////////////////         	          
}		
				
if(sizeof($imei)==0)
{						
	print"<center><FONT color=\"Red\" size=2><strong>No Version Record found</strong></font></center>";
	echo'<br><br>';
}	
else
{
  echo'<input TYPE="hidden" VALUE="Version" NAME="csv_type">';
  echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
  echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.sizeof($imei).'\');" value="Get PDF" class="noprint">&nbsp;
  <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
  <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
}
echo '</form>';
 	
unlink($xml_path);
echo '</center>';
?>
					