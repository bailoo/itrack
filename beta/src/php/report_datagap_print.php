<?php   
  include_once("calculate_distance.php");
  include_once("get_location.php");
  include_once("report_get_parsed_string.php");
  include_once("util.hrminsec.php");
  include_once("report_title.php");				  
  
  $date1 = $_POST['date1'];
  $date2 = $_POST['date2'];
  $title = $_POST['title'];
  $imei_datagap = $_POST['imei_datagap_tmp'];
  $vname_datagap = $_POST['vname_datagap_tmp'];
  //print_r($vname_datagap);
  
  $t1_nogps = $_POST['t1_nogps_tmp'];
  $t2_nogps = $_POST['t2_nogps_tmp'];
  $tdiff_nogps = $_POST['tdiff_nogps_tmp'];
  $lat1_nogps = $_POST['lat1_nogps_tmp'];
  $lng1_nogps 	= $_POST['lng1_nogps_tmp'];
  $lat2_nogps = $_POST['lat2_nogps_tmp'];
  $lng2_nogps = $_POST['lng2_nogps_tmp'];
  $speed1_nogps= $_POST['speed1_nogps_tmp'];
  $speed2_nogps= $_POST['speed2_nogps_tmp'];
  
  $t1_nodata = $_POST['t1_nodata_tmp'];
  $t2_nodata = $_POST['t2_nodata_tmp'];
  $tdiff_nodata = $_POST['tdiff_no_data_tmp'];
  $lat1_nodata = $_POST['lat1_nodata_tmp'];
  $lng1_nodata 	= $_POST['lng1_nodata_tmp'];
  $lat2_nodata = $_POST['lat2_nodata_tmp'];
  $lng2_nodata = $_POST['lng2_nodata_tmp'];
  $speed1_nodata= $_POST['speed1_nodata_tmp'];
  $speed2_nodata= $_POST['speed2_nodata_tmp'];
  
  //echo '<br>size='.sizeof($imei_datagap).' t1_nogps:'.$t1_nogps;
  //echo $lat1_nodata.",".$lng1_nodata." ".$lat2_nodata.",".$lng2_nodata;
    
  echo '<html>
  <head>
    <link rel="StyleSheet" href="../css/menu.css">	 
  </head>';
  echo '<body>';
  
  echo '<center>';
    
  report_title("DATA GAP- Missing Data/GPS Report",$date1,$date2);
  
  							
  $j=-1;
  $k=0;
  			             
  $flag_res = 0;
  $endtable =0;
  $fix_nogps_row_flag =0;  
  
  $DbConnection=null;
  $alt=null;
  	
  echo'
  <table border="1" width="100%" align="center" class="print_datagap_t1">
  <tr>
  <td align="center">';
        
  for($i=0;$i<sizeof($imei_datagap);$i++)
	{								              
    $flag_res =1;
    
    //echo "<br>t1_nogps=".$t1_nogps[$i]." ,t1_nodata=".$t1_nodata[$i];
    
    $p = $i-1;
    if(($i==0) || (($i>0) && ($imei_datagap[$p] != $imei_datagap[$i])) )
    {            
      $k=0;                                              
      $j++;
      
      $sno = 1;
      //$title='GPS GAP : '.$vname_datagap[$i];
      $title = 'GPS GAP : '.$vname_datagap[$i]." (".$imei_datagap[$i]."): DATA GAP Report, DateTime : ".$date1."-".$date2;
      $vname_datagap1[$j][$k] = $vname_datagap[$i];
      $imei_datagap1[$j][$k] = $imei_datagap[$i];   
      //echo  "vname1=".$vname1[$j][$k]." j=".$j." k=".$k;
      
      echo '<div style="align"><font color="#FFFFFF"><strong>'.$title.'</strong></font></span>';
      echo'
      <table border="1" width="100%" align="center" class="print_datagap_t2">	
      <tr style="color:red;"><th colspan="3" height="8%" align="center">NO GPS</td><th colspan="3" align="center">NO DATA</td></tr>
      
      <tr height="3%">
			<td class="text" align="left" width="16%"><b>StartTime/Position</b></td>
			<td class="text" align="left" width="16%"><b>EndTime/Position</b></td>
      <td class="text" align="left" width="6%"><b>Difference</b></td>
      
			<td class="text" align="left" width="16%"><b>StartTime/Position</b></td>
			<td class="text" align="left" width="16%"><b>EndTime/Position</b></td>
      <td class="text" align="left" width="6%"><b>Difference</b></td>      
      </tr>';  								
    }                                                                        		
        
    //$diff_nogps = sec_to_time($tdiff_nogps[$i]);
    //$diff_nodata = sec_to_time($tdiff_nodata[$i]);
    
    $diff_nogps ="";
    $diff_nodata ="";
    
    //echo "diff=".$tdiff_nogps[$i].",".$tdiff_nodata[$i];
    
    if($tdiff_nogps[$i]!="")
      $diff_nogps = sec_to_time($tdiff_nogps[$i]);
    
    if($tdiff_nodata[$i]!="")
      $diff_nodata = sec_to_time($tdiff_nodata[$i]);
          
    if($lat1_nogps[$i]=="")
      $lat1_nogps[$i] = "-";
    
    if($lng1_nogps[$i]=="")
      $lng1_nogps[$i] = "-";
    
    if($lat2_nogps[$i]=="")
      $lat2_nogps[$i] = "-";
    
    if($lng2_nogps[$i]=="")
      $lng2_nogps[$i] = "-";
    
    if($lat1_nodata[$i]=="")      
      $lat1_nodata[$i] = "-";
       
    if($lng1_nodata[$i]=="")    
      $lng1_nodata[$i] = "-";
      
    if($lat2_nodata[$i]=="")
      $lat2_nodata[$i] = "-";
      
    if($lat2_nodata[$i]=="")
      $lat2_nodata[$i] = "-";
              
    $m = $i+1;
    
    
    $latA1 = $lat1_nogps[$i];
    $lngA1 = $lng1_nogps[$i];
    $latA2 = $lat2_nogps[$i];
    $lngA2 = $lng2_nogps[$i];
    
    $latB1 = $lat1_nodata[$i];
    $lngB1 = $lng1_nodata[$i];
    $latB2 = $lat2_nodata[$i];
    $lngB2 = $lng2_nodata[$i];     
    
    if($latA1!="-" && $lngA1!="-" && $latA2!="-" && $lngA2!="-")
    {
      get_location($latA1,$lngA1,$alt,&$locationA1,$DbConnection);
      get_location($latA2,$lngA2,$alt,&$locationA2,$DbConnection);
      
      calculate_distance($latA1, $latA2, $lngA1, $lngA2, &$distanceA);
    }    
    else
    {
      if( ($latA1=="-" || $lngA1=="-") || ($latA1=="" || $lngA1=="") ) 
        $locationA1 = "No GPS found";
      if( ($latA2=="-" || $lngA2=="-") || ($latA2=="" || $lngA2=="") )  
        $locationA2 = "No GPS found";
    }        
      
    if($latB1!="-" && $lngB1!="-" && $latB2!="-" && $lngB2!="-")
    {      
      get_location($latB1,$lngB1,$alt,&$locationB1,$DbConnection);
      get_location($latB2,$lngB2,$alt,&$locationB2,$DbConnection);      
            
      calculate_distance($latB1, $latB2, $lngB1, $lngB2, &$distanceB);     
    }
    else
    {
      if( ($latB1=="-" || $lngB1=="-") || ($latB1=="" || $lngB1=="") ) 
        $locationB1 = "No GPS found";
      if( ($latB2=="-" || $lngB2=="-") || ($latB2=="" || $lngB2=="") )  
        $locationB2 = "No GPS found";
    }      
      
    
    if($tdiff_nogps[$i]!="")
      $diff_nogps = $diff_nogps.' hr<br>'.round($distanceA,2).' KM';
    
    if($tdiff_nodata[$i]!="")
      $diff_nodata = $diff_nodata.' hr<br>'.round($distanceB,2).' KM';
    
    //if( ($t1_nogps[$i] != $t1_nogps[$m]) )
    $m = $i+1;
    if( (($imei_datagap[$i] == $imei_datagap[$m]) && ($t1_nogps[$i] != $t1_nogps[$m])) || ($imei_datagap[$i] != $imei_datagap[$m]) )
    {    
      echo '<tr valign="top" height="2%" style="font-size:small;">';
      if($fix_nogps_row_flag==1)
      {        
        echo '<td colspan="3">&nbsp;</td>';       
      }
      else
      {
        echo'<td id="cellA'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$t1_nogps[$i].'<br>'.$locationA1.'</td>';		
        echo'<td id="cellB'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$t2_nogps[$i].'<br>'.$locationA2.'</td>';
        echo'<td id="cellC'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$diff_nogps.'</td>';      
      }
      echo'<td id="cellD'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$t1_nodata[$i].'<br>'.$locationB1.'</td>';
      echo'<td id="cellE'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$t2_nodata[$i].'<br>'.$locationB2.'</td>';
      echo'<td id="cellF'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$diff_nodata.'</td>';
      echo '</tr>';

      $fix_nogps_row_flag =0;
    }
    else
    {
      echo '<tr valign="top" style="font-size:small;">';
      if($fix_nogps_row_flag ==0)
      {     
        echo'<td id="cellA'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$t1_nogps[$i].'<br>'.$locationA1.'</td>';		
        echo'<td id="cellB'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$t2_nogps[$i].'<br>'.$locationA2.'</td>';
        echo'<td id="cellC'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$diff_nogps.'</td>';
        $fix_nogps_row_flag =1;
      }
      else
      { 
        echo '<td colspan="3">&nbsp;</td>'; 
      }  
      
      echo'<td id="cellD'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$t1_nodata[$i].'<br>'.$locationB1.'</td>';
      echo'<td id="cellE'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$t2_nodata[$i].'<br>'.$locationB2.'</td>';
      echo'<td id="cellF'.$i.'" class="text" align="left" style="background-color:#FFFFFF;">'.$diff_nodata.'</td>';
        
      echo '</tr>';                 
    } 
      
    /*$t1_nogps1[$j][$k] = $t1_nogps[$i];	
    $t2_nogps1[$j][$k] = $t2_nogps[$i];
    $tdiff_nogps1[$j][$k] = $tdiff_nogps[$i];	
    $lat1_nogps1[$j][$k] = $lat1_nogps[$i];	
    $lng1_nogps1[$j][$k] = $lng1_nogps[$i];	
    $lat2_nogps1[$j][$k] = $lat2_nogps[$i];	
    $lng2_nogps1[$j][$k] = $lng2_nogps[$i];	
    $speed1_nogps1[$j][$k] = $speed1_nogps[$i];
    $speed2_nogps1[$j][$k] = $speed2_nogps[$i];	
    
    $t1_nodata1[$j][$k] = $t1_nodata[$i];	
    $t2_nodata1[$j][$k] = $t2_nodata[$i];	
    $tdiff_nodata1[$j][$k] = $tdiff_nodata[$i];
    $lat1_nodata1[$j][$k] = $lat1_nodata[$i];	
    $lng1_nodata1[$j][$k] = $lng1_nodata[$i];	
    $lat2_nodata1[$j][$k] = $lat2_nodata[$i];	
    $lng2_nodata1[$j][$k] = $lng2_nodata[$i];	
    $speed_nodata1[$j][$k] = $speed_nodata[$i];*/	
    	                                    										      			    				  					
	  //if( (($i>0) && ($imei_datagap[$i+1] != $imei_datagap[$i])) )
	  $m = $i+1;
    if( (($i>0) && ($imei_datagap[$m] != $imei_datagap[$i])) )	  
    {
      $endtable=1;
      echo'</table>';      
      $no_of_data[$j] = $k;
		}  
		
    $k++;   
    $sno++;                       							  		
  }
 
  if($endtable==0)
  {
    echo'</table>';
  }
            
  echo '</td></tr></table>';
  
  echo '<br><input type="button" value="Print it" onclick="window.print()" class="noprint">';            
  echo'</center>';
  echo '</body>';
  
echo '</html>';    
      
?>