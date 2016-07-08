<?php
    /*error_reporting(-1);
    ini_set('display_errors', 'On');*/
    set_time_limit(300);	
    date_default_timezone_set("Asia/Kolkata");
    include_once("main_vehicle_information_1.php");
    include_once('Hierarchy.php');
    include_once('util_php_mysql_connectivity.php');	
    include_once('util_session_variable.php');    
    include_once("report_title.php");
    include_once("calculate_distance.php");

	//====cassamdra //////////////
    /*include_once('xmlParameters.php');
    include_once('parameterizeData.php'); /////// for seeing parameters
    include_once('data.php');   
    include_once("getXmlData.php");*/
    ////////////////////////

    $DEBUG = 0;
    $device_str = $_POST['vehicleserial'];
    //echo "<br>devicestr=".$device_str;
    $vserial = explode(':',$device_str);
    $vsize=count($vserial);

    $date1 = $_POST['start_date'];
    $date2 = $_POST['end_date'];
    $date1 = str_replace("/","-",$date1);
    $date2 = str_replace("/","-",$date2);
    
    ////////// cassandra //////////
    $date_1 = explode(" ",$date1);
    $date_2 = explode(" ",$date2);
    $datefrom = $date_1[0];
    $dateto = $date_2[0];	
    
    get_All_Dates($datefrom, $dateto, $userdates);    
    $date_size = sizeof($userdates); 

    //## MAKE Dates
    $time_1 = explode(":",$date_1[1]);
    $time_2 = explode(":",$date_2[1]);

    $t1 = intval($time_1[0]);
    $time1_hr = "";
    for($i=$t1;$i<=24;$i++) {

            if($i>0) {
            $hr = $i;

            if($i<=9) { $hr = "0".$hr; }

            $time1_hr.= "HR_".$hr.",";
            }
    }
    $time1_hr = substr($time1_hr, 0, -1);
    $time1_hr_fields = explode(",",$time1_hr);
    //====================================	

    $t2 = intval($time_2[0]);
    //echo "<br>t2=".$t2;
	$time2_hr = "";
	for($i=1;$i<=$t2;$i++) {
		
		$hr = $i;
		
		if($i<=9) { $hr = "0".$hr; }
		
		$time2_hr.= "HR_".$hr.",";
	}
	$time2_hr = substr($time2_hr, 0, -1);
	$time2_hr_fields = explode(",",$time2_hr);
	//=====================================
	
	//## Get Next and Previous Dates
	$tmpd1 = $date_1[0]." 00:00:00";
	$tmpd2 = $date_2[0]." 00:00:00";


	$multiple_date_flag = true;
	if($date_size > 1) {
		$dateA = date('Y-m-d', strtotime($date1 . ' +1 day'));
		$dateB = date('Y-m-d', strtotime($date2 . ' -1 day'));
	} else {
		$dateA = $date_1[0];
		$dateB = $date_2[0];
		$multiple_date_flag = false;
 	}
    
    for($i=0;$i<$vsize;$i++)
    {
        $dataCnt=0;
        $vehicle_info=get_vehicle_info($root,$vserial[$i]);
        $vehicle_detail_local=explode(",",$vehicle_info);
                
		//##BLOCK 1
		$QUERY1 = "SELECT imei,date,".$time1_hr." FROM distance_log WHERE date ='$datefrom' AND imei='$vserial[$i]' ORDER BY date ASC";
                //echo "<br>QUERY1=".$QUERY1.", DB=".$DbConnection."<br>";
		$RESULT1 = mysql_query($QUERY1,$DbConnection);
		
		while($ROW1 = mysql_fetch_object($RESULT1)) {
			
			$total_dist = 0.0;
			
			$reportDate = $ROW1->date;
			//echo "\nSizeField=".sizeof($time1_hr_fields);
			for($f=0;$f<sizeof($time1_hr_fields);$f++) {
				$col = $time1_hr_fields[$f];
				echo "<br>Col=".$col;
				$total_dist+=$ROW1->$col;
				echo "\nT=".$total_dist;
			}
							
                        //echo "<br>Dist=".$total_dist." ,imei=".$vserial[$i];
			$imei[]=$vserial[$i];
			$vname[]=$vehicle_detail_local[0];
			$dateDisplay[]=$reportDate;                                
			$distanceDisplay[]=$total_dist;
		}


		if($multiple_date_flag) {
		//##BLOCK 2
		$QUERY2 = "SELECT * FROM distance_log WHERE date BETWEEN '$dateA' AND '$dateB' AND imei='$vserial[$i]' ORDER BY date ASC";
                //echo "<br>QUERY2=".$QUERY2."<br>";
		$RESULT2 = mysql_query($QUERY2,$DbConnection);
		
		while($ROW2 = mysql_fetch_object($RESULT2)) {
			
			$total_dist = 0.0;
			
			$reportDate = $ROW2->date;
			$total_dist+= $ROW2->HR_01 + $ROW2->HR_02 + $ROW2->HR_03 + $ROW2->HR_04 + $ROW2->HR_05 + $ROW2->HR_06 + $ROW2->HR_07 +
						$ROW2->HR_08 + $ROW2->HR_09 + $ROW2->HR_10 + $ROW2->HR_11 + $ROW2->HR_12 + $ROW2->HR_13 + $ROW2->HR_14 +
						$ROW2->HR_15 + $ROW2->HR_16 + $ROW2->HR_17 + $ROW2->HR_18 + $ROW2->HR_19 + $ROW2->HR_20 + $ROW2->HR_21 +
						$ROW2->HR_22 + $ROW2->HR_23 + $ROW2->HR_24;
							
			$imei[]=$vserial[$i];
			$vname[]=$vehicle_detail_local[0];
			$dateDisplay[]=$reportDate;                                
			$distanceDisplay[]=$total_dist;
		}
    
        
		//##BLOCK 3
		$QUERY3 = "SELECT imei,date,".$time2_hr." FROM distance_log WHERE date ='$dateto' AND imei='$vserial[$i]' ORDER BY date ASC";
                //echo "<br>QUERY3<br>".$QUERY3;
		$RESULT3 = mysql_query($QUERY3,$DbConnection);
		
		while($ROW3 = mysql_fetch_object($RESULT3)) {
			
			$total_dist = 0.0;
			
			$reportDate = $ROW3->date;
			
			for($f=0;$f<sizeof($time2_hr_fields);$f++) {
				$col = $time2_hr_fields[$f];
				$total_dist+=$ROW3->$col;
			}
							
			$imei[]=$vserial[$i];
			$vname[]=$vehicle_detail_local[0];
			$dateDisplay[]=$reportDate;                                
			$distanceDisplay[]=$total_dist;
		}
		}
	}
	
  echo '<center>';
	  
  echo'<br>';
  report_title("History Distance Report",$date1,$date2);
  
	echo'<div style="overflow: auto;height: 485px; width: 800px;" align="center">';
							

	$j=-1;
	$k=0;
  	//print_r($imei);	
	$datefrom1=array(array());	
	$dateto1=array(array());
	$distance1=array(array());
for($i=0;$i<sizeof($imei);$i++)
{								              
    if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_dist =0.0;
      $total_distance[$j] =0;
      
      $sno = 1;
      $title='History Distance Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
      $vname1[$j][$k] = $vname[$i];
      $imei1[$j][$k] = $imei[$i];   
      //echo  "vname1=".$vname1[$j][$k]." j=".$j." k=".$k;
      
      echo'
      <br><table align="center">
      <tr>
      	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
      </tr>
      </table>
      <!--<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>-->	
      <table class="table table-condened table-hover table-striped">
      <thead>
       <tr>
        
            <th class="text" align="left"><b>SNo</b></th>
            <th class="text" align="left"><b>Date</b></th>            
            <th class="text" align="left"><b>Distance (km)</b></th>	
        
      </tr></thead><tbody>';  								
    }                                                                        		
		
    $sum_dist = $sum_dist + $distanceDisplay[$i];
	              
    echo'<tr>'
    . '<td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		echo'<td class="text" align="left">'.$dateDisplay[$i].'</td>';
		echo'<td class="text" align="left">'.round($distanceDisplay[$i],2).'</td>';		
		
		echo'</tr>';	          		
		//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
    
    $datefrom1[$j][$k] = $dateDisplay[$i];	
    //$dateto1[$j][$k] = $dateToDisplay[$i];	
    $distance1[$j][$k] = round($distanceDisplay[$i],2); 
	
	
    if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {
      echo '<tr style="height:20px;background-color:lightgrey">
      <td class="text"><strong>Total<strong>&nbsp;</td>
			<td class="text"><strong>'.$date1.'</strong></td>';      								
      
			if( ($k>0) || ($date_size==1))
			{
				//echo  "<br>sum_avgspeed=".$sum_avgspeed."<br>";
				$total_distance[$j] = round($sum_dist,2);
				//echo  "<br>total_avgspeed[$j]=".$total_avgspeed[$j]."<br>";
			}
													
			echo'<td class="text"><font color="red"><strong>'.round($total_distance[$j],2).'</strong></font></td>';
			echo'</tr>'; 
      echo '</tbody></table>';
      
      $no_of_data[$j] = $k;
    }  
		
    $k++;   
    $sno++;                       							  		
 }
 
  echo "</div>";     

	echo'<form method = "post" target="_blank">';
	
  $csv_string = "";
	
        for($x=0;$x<=$j;$x++)
	{								
		$title = $vname1[$x][0]." (".$imei1[$x][0]."): History Distance Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
		$csv_string = $csv_string."SNo,Date,Distance (km)\n";
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";
		
		$sno=0;
		for($y=0;$y<=$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;
                    
			$datetmp1 = $datefrom1[$x][$y];	
			$datetmp2 = $dateto1[$x][$y];										
			$disttmp = $distance1[$x][$y];
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][Date]\">";			
			echo"<input TYPE=\"hidden\" VALUE=\"$disttmp\" NAME=\"temp[$x][$y][Distance (km)]\">";
      
			$csv_string = $csv_string.$sno.','.$datetmp1.','.$datetmp2.','.$disttmp."\n";      																	
		}		

		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Date]\">";		
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Distance (km)]\">";									
		
		$m = $y+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][Date]\">";		
		echo"<input TYPE=\"hidden\" VALUE=\"$total_distance[$x]\" NAME=\"temp[$x][$m][Distance (km)]\">";
                $csv_string = $csv_string.'Total,'.$date1.','.$date2.','.$total_distance[$x]."\n";																																										
	}																						

      
  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		$vsize = sizeof($imei);
  		
      if($vsize==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>No Distance Record</strong></font></center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="distance" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }
                  
      echo'</td>		
    </tr>
		</table>
		</form>
 ';			 
					 	
echo '</center>';		
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	 
?>
