<?php			

 ///////// MOVING FLAGS ////////////////
//date_default_timezone_set('Asia/Calcutta');
$startdate = date('Y-m-d')." 00:00:00";
$enddate = date('Y-m-d H:i:s');

$total_time=strtotime($enddate);
$back_time = $total_time-4200;  // TWO MINUTES BACK TIME  120
$moving_flag = 0;

set_time_limit(0);

/*$running_vimei = array();
$running_vname = array();

$stopped_vimei = array();
$stopped_vname = array();

$idle_vimei = array();
$idle_vname = array(); */

///////////////////////////////////////

function show_live_vehicles_prev($imei_arr, $v_ldt_arr, $vehicle_name_arr)
{    
  //echo "<br>in show live vehicles prev";
  /*$running_vimei = array();
  $running_vname = array();
  $running_vlast_datetime = array();
  
  $stopped_vimei = array();
  $stopped_vname = array();
  $stopped_vlast_datetime = array();
  
  $idle_vimei = array();
  $idle_vname = array();
  $idle_vlast_datetime = array(); */ 
  
  $size_imei = sizeof($imei_arr);
  
  for($i=0;$i<$size_imei;$i++)
  {
    $imei_ldt = $imei_arr[$i]."#".$v_ldt_arr[$i];
    echo '<TR><TD>';
    //echo'<input type="hidden" value="'.$running_vimei[$i].'" name="vserial[]">';    
  	echo'<input type="checkbox" name="live_vehicles[]" value="'.$imei_ldt.'">'; 
    echo'<font color="#008C05" face="verdana" style="font-size:11px">'.$vehicle_name_arr[$i].'</FONT>&nbsp;[&nbsp;<blink><font color="#008C05">Running</font></blink>&nbsp;]';
    echo '</TD><TD><div style="height:1px;"></div></TD></TR>';    
  }    
  //$size_vname = sizeof($vehicle_name_arr);
   
  //echo "sizeimei=".$size_imei." ,sizevname=".$size_vname;
  
 /* foreach($imei_arr as $vehiclename)
  {
    $imei = $imei_arr[$vehiclename];
    $vname = $vehiclename;
    echo "<br>imei=".$imei." ,vname=".$vname;  
    show_live_vehicles($imei,$vname);   
  }*/
    
  /*for($i=0;$i<$size_imei;$i++)
  {
    show_live_vehicles($imei_arr[$i], $v_ldt_arr[$i], $vehicle_name_arr[$i],&$running_vimei, &$running_vname, &$running_vlast_datetime, &$stopped_vimei,&$stopped_vname,&$stopped_vlast_datetime, &$idle_vimei,&$idle_vname, &$idle_vlast_datetime);
  }
  
  ///// PRINT VEHICLES  //////        
  $size_r = sizeof($running_vimei);
  $size_s = sizeof($stopped_vimei);  
  $size_i = sizeof($idle_vimei); */
  
  //echo "<br>size_r=".$size_r." ,size_s=".$size_s." ,size_i=".$size_i;
  
  /*for($i=0;$i<$size_r;$i++)
  {
    echo '<TR><TD>';
    echo'<input type="hidden" value="'.$running_vimei[$i].'" name="vserial[]">';
  	echo'<a href="#" onclick="movingVehicle_prev('.$running_vimei[$i].');" onmouseover="this.color=red" onmouseout="style="font-color:#ffffff;""><font color="#008C05" face="verdana" style="font-size:11px">'.$running_vname[$i].'</FONT>&nbsp;[&nbsp;<blink><font color="#008C05">Running</font></blink>&nbsp;]</a>';
    echo '</TD><TD><div style="height:1px;"></div></TD></TR>';    
  }
  
  for($i=0;$i<$size_s;$i++)
  {
    echo '<TR><TD>';
    echo'<a href="#" onclick="LP_prev('.$stopped_vimei[$i].');" onmouseover="this.color=red" onmouseout="style="font-color:#ffffff;""><font color="green" face="verdana" style="font-size:11px">';  
  	echo'<font color="#FF0000" face="verdana" style="font-size:11px">'.$stopped_vname[$i].'</FONT>&nbsp; [&nbsp;<font color="#FF0000">Stopped</font>&nbsp;]';  
  	echo '</a>';
    echo '</TD><TD><div style="height:1px;"></div></TD></TR>';    					                                                                                                                       
  }
  
  for($i=0;$i<$size_i;$i++)
  {
    echo '<TR><TD>';
    echo'<a href="#" onclick="LP_prev('.$idle_vimei[$i].');" onmouseover="this.color=red" onmouseout="style="font-color:#ffffff;""><font color="green" face="verdana" style="font-size:11px">';  
  	echo'<font color="#811BE0" face="verdana" style="font-size:11px">'.$idle_vname[$i].'</FONT>&nbsp; [&nbsp;<font color="#571763">Idle</font>&nbsp;]';		  						
  	echo '</a>';
    echo '</TD><TD><div style="height:1px;"></div></TD></TR>';  	
  } */     
  
  /*for($i=0;$i<$size_r;$i++)
  {
    $imei_ldt = $running_vimei[$i]."#".$running_vlast_datetime[$i];
    echo '<TR><TD>';
    //echo'<input type="hidden" value="'.$running_vimei[$i].'" name="vserial[]">';    
  	echo'<input type="checkbox" name="live_vehicles[]" value="'.$imei_ldt.'">'; 
    echo'<font color="#008C05" face="verdana" style="font-size:11px">'.$running_vname[$i].'</FONT>&nbsp;[&nbsp;<blink><font color="#008C05">Running</font></blink>&nbsp;]';
    echo '</TD><TD><div style="height:1px;"></div></TD></TR>';    
  }
  
  for($i=0;$i<$size_s;$i++)
  {
    $imei_ldt = $stopeed_vimei[$i]."#".$stopped_vlast_datetime[$i];
    echo '<TR><TD>';
    echo'<input type="checkbox" name="live_vehicles[]" values="'.$imei_ldt.'">';  
  	echo'<font color="#FF0000" face="verdana" style="font-size:11px">'.$stopped_vname[$i].'</FONT>&nbsp; [&nbsp;<font color="#FF0000">Stopped</font>&nbsp;]';  
    echo '</TD><TD><div style="height:1px;"></div></TD></TR>';    					                                                                                                                       
  }
  
  for($i=0;$i<$size_i;$i++)
  {
    $imei_ldt = $idle_vimei[$i]."#".$idle_vlast_datetime[$i];
    echo '<TR><TD>';
    echo'<input type="checkbox" name="live_vehicles[]" value="'.$imei_ldt.'">';   
  	echo'<font color="#811BE0" face="verdana" style="font-size:11px">'.$idle_vname[$i].'</FONT>&nbsp; [&nbsp;<font color="#571763">Idle</font>&nbsp;]';  
    echo '</TD><TD><div style="height:1px;"></div></TD></TR>';  	
  }  */      
  ////////////////////////////          
}

/*function show_live_vehicles($imei, $vldt, $vname, $running_vimei,$running_vname, $running_vlast_datetime, $stopped_vimei,$stopped_vname, $stopped_vlast_datetime, $idle_vimei,$idle_vname, $idle_vlast_datetime)
{                
  global $startdate;
  global $enddate;
  global $total_time;  

  //echo "<br>".$vehicle_serial.", ".$startdate.", ".$enddate.", ".$xmltowrite."<br>";	
  //echo "<br>show moving vehicle";
  $fix_tmp = 1;
	$xml_date_latest="1900-00-00 00:00:00";
	$linetowrite="";
	$dataValid = 0;
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);

	$datefrom = $date_1[0];
	$dateto = $date_2[0];
	$timefrom = $date_1[1];
	$timeto = $date_2[1];
	$flag_current = 0; 

	//get_All_Dates($datefrom, $dateto, &$userdates);	
	//echo "3:datefrom=".$datefrom.' '."dateto=".$dateto.' '."userdates=".$userdates[0].'<BR>';

	//date_default_timezone_set("Asia/Calcutta");
	$current_datetime = date("Y-m-d H:i:s");
	$current_date = date("Y-m-d");
		
  $xml_file = "../../../xml_vts/xml_data/".$current_date."/".$imei.".xml";	
  			    
  if (file_exists($xml_file)) 
	{
	  //echo "<br>xml_file exists";
    //$current_datetime1 = date("Y_m_d_H_i_s");
    $t=time();
    //$xml_original_tmp = "xml_tmp/original_xml/tmp_".$current_datetime1.".xml";
    //$xml_original_tmp = "xml_tmp/original_xml/".$current_datetime1.".xml";
    $xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$imei."_".$t."_".$i.".xml";
    //echo "<br>xml_file=".$xml_file." <br>tmpxml=".$xml_original_tmp."<br>";
		copy($xml_file,$xml_original_tmp); 

		//echo "<br>orginal_tmp=".$xml_original_tmp."<br>";
		$fexist =1;
    $xml = fopen($xml_original_tmp, "r") or $fexist = 0;   
    $total_lines =0;
		$total_lines = count(file($xml_original_tmp));
    //echo "xml_obj=".$file.", fexist=".$fexist."<br>";          
    //fclose($file);  
    //$tmpcnt=0;               
    $format = 2;
          
    if (file_exists($xml_original_tmp)) 
    { 
      $c =0;     
      //echo "<br>xml_org exists";        
      while(!feof($xml))          // WHILE LINE != NULL
			{				  				  				          
        $line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
        $c++;
        
        if($c==($total_lines-1))
        {
					if ( (preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match)) && (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)) )
					{
              $status = preg_match('/datetime="[^"]+/', $line, $datetime_tmp);
              $datetime_tmp1 = explode("=",$datetime_tmp[0]);
              $datetime = preg_replace('/"/', '', $datetime_tmp1[1]);
              $xml_date = $datetime;
              
              $status = preg_match('/speed="[^"]+/', $line, $speed_tmp);
              $speed_tmp1 = explode("=",$speed_tmp[0]);
              $speed = preg_replace('/"/', '', $speed_tmp1[1]);
              
              $status = preg_match('/lat="[^"]+/', $line, $lat_tmp);
              $lat_tmp1 = explode("=",$lat_tmp[0]);
              $lat = preg_replace('/"/', '', $lat_tmp1[1]);
              
              $status = preg_match('/lng="[^"]+/', $line, $lng_tmp);
              $lng_tmp1 = explode("=",$lng_tmp[0]);
              $lng = preg_replace('/"/', '', $lng_tmp1[1]);                                                        
  					             
              $xml_date_sec = strtotime($xml_date);   
              $current_time_sec = strtotime($current_datetime);				
                        
              $diff = ($current_time_sec - $xml_date_sec);   // IN SECONDS
              
              if( ($diff < 120) && ($lat!="" || $lng!="") && ($speed>0) )
    					{
    						//echo "one";
                $running_vimei[] = $imei;
    						$running_vname[] = $vname;
                $running_vlast_datetime[] = $vldt;                						              
              }
    					//Stopped
    					else if( ($diff >120 && $diff<1200) && ($lat!="" || $lng!="") && ($speed>0))   // IDLE					
    					{
    						//echo "one3";
                $idle_vimei[] = $imei;
    						$idle_vname[] = $vname;
                $idle_vlast_datetime[] = $vldt;                						            
              }                        
              else // STOPPED
    					{
    						//echo "one1";
                $stopped_vimei[] = $imei;
    						$stopped_vname[] = $vname;
                $stopped_vlast_datetime[] = $vldt;               						              
              }

          } //outer if 1 closed													
				}	// if 2 closed																		
			}	// while closed		
			
		 fclose($xml);
		 unlink($xml_original_tmp);    		
		}  // if file exists closed				
	}	 // if file exists xml closed
}  */
      
?>																							
