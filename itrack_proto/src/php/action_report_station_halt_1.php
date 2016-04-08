<?php	
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    set_time_limit(1000);   
    include_once("report_title.php");  
    include("user_type_setting.php");
    
    $station_str_tmp = $_POST['station_str'];
    //echo "station=".$station_str_tmp."<br>";
    $station_str_tmp1 = explode('@',$station_str_tmp);
    $vstr = $_POST['vehicle_str'];  
    $vstr_arr = explode('@',$vstr);
    $date1 = str_replace("/","-",$start_date);
    $date2 = str_replace("/","-",$end_date);

    $aryRange_1=createDateRangeArray($date1, $date2);
   // print_r($aryRange_1);  
	?>	
     <html>
            <title>
              Supply Timing Report  
            </title>
        <head>
            
        </head>
        <body>
<?php	
    echo'<form method = "post" target="_blank">';
      	//echo "<br>pl=".$pdf_place_ref;
		$title="Supply Timing Report FROM ".$start_date." To&nbsp;".$end_date; 
		$csv_string = "";
      	$csv_string = $csv_string.$title."\n";
      	$csv_string = $csv_string."SNo,Vehicle Name,Customer Number,Google Location,Date,Enter Time,Leave Time,Duration (Hrs.min)\n";
        echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
    
    echo'
    <br>
    <table align="center">
        <tr>
            <td class="text" align="center">
                <b>'.$title.'</b> 
                <div style="height:8px;"></div>
            </td>
        </tr>
    </table>';
	$Query="SELECT format_id FROM account_report_format WHERE account_id='$accoun_id' AND status=1";
	$Result=mysql_query($Query,$DbConnection);
	$Row=mysql_fetch_row($Result);
    	
    $scnt=1;
	global $final_data_array;
	global $flag;
	$flag=0;
	for($i=0;$i<sizeof($aryRange_1);$i++)
	{
		if(($i+1)%2==0)
		{
			$vcolor="blue";
		}
		//$tmp_path="gps_report/".$account_id."/download/".$aryRange_1[$i]."/BVMFirstFile.csv";
		$file_path="";
		if($Row[0]=="#1") // row is the account format id
		{
			$file_path="daily_report/bvm/report_log/".$aryRange_1[$i];
		}
		else
		{
			$file_path="some other";
		}
		//echo "file_path=".$file_path."<br>";
		//echo "file_path=".$file_path."<br>";
		
	
		$file_flag=0;
		if ($handle = opendir($file_path)) 
		{  
			while ($file = readdir($handle)) 
			{
				if($file!="." && $file!="..")
				{
					//echo "file_name=".$$file."<br>";
					$file1=explode(".",$file);					
					if($Row[0]=="#1") // row is the account format id
					{
						if($file1[1]=="csv")
						{
							$file_name[]=$file;
							$file_flag=1;
							//echo "file_name1=".$file_name."<br>";
						}
					}
					else
					{
						$file_name[]=$file;
						$file_flag=1;
					}
				}
			}
			closedir($handle);
		}
		//echo "file_flag=".$file_flag."<br>";
		if($file_flag==1)
		{
			for($fi=0;$fi<sizeof($file_name);$fi++)
			{
				$file_path_1=$file_path."/".$file_name[$fi];
				if($Row[0]=="#1") // row is the account format id
				{
					get_csv_data($file_path_1,$vstr_arr,$station_str_tmp1);
				}
				else if($Row[0]=="#2")
				{
					get_excel_data($file_path_1,$vstr_arr,$station_str_tmp1);
				}
				else
				{
				
				}
			}			
			//echo "file_path_1=".$file_path_1."<br>";			
		}
	}		
	function get_excel_data($file_path_1,$vstr_arr,$station_str_tmp1)
	{
		global $flag;
		global $final_data_array;	
		$objPHPExcel = PHPExcel_IOFactory::load($file_path_1);
		$highestColumm = $objPHPExcel->setActiveSheetIndex(0)->getHighestColumn();
		$highestRow = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
		//echo "highestRow=".$highestRow." highestColumm=".$highestColumm."<br>";
		$k=0;	
		$excel_data_array=array();
		foreach ($objPHPExcel->setActiveSheetIndex(0)->getRowIterator() as $row) 
		{
			$k++;					
			if($k>1)
			{
				$cellIterator = $row->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells(false);						
				$ci=0;
				$excel_tmp_str="";
				foreach ($cellIterator as $cell) 
				{
					$ci++;		
					$column = $cell->getColumn();
					$row = $cell->getRow();
					$tmp_val=$column.$row;
					if($ci==1)
					{
						$excel_vehicle_name=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
					}
					if($ci==3)
					{
						$excel_station_no=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
					}
					if($ci==6)
					{
						$excel_in_date=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
					}
					if($ci==7)
					{
						$excel_in_time=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
					}
					if($ci==8)
					{
						$excel_out_date=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
					}
					if($ci==9)
					{
						$excel_out_time=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
					}
					if($ci==10)
					{
						$excel_halt_duration=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
					}
					if($ci==13)
					{
						$excel_shift=$objPHPExcel->getActiveSheet()->getCell($tmp_val)->getValue();
					}												
				}
				$excel_tmp_str=$excel_tmp_str.$excel_vehicle_name.",".$excel_station_no.",".$excel_in_date.",".$excel_in_time.",".$excel_out_date.",".$excel_out_time.",".$excel_halt_duration.",".$excel_shift.",";
				$excel_tmp_str=  substr($excel_tmp_str, 0, -1);
				$excel_data_array[]=$excel_tmp_str;
				for($vi=0;$vi<sizeof($vstr_arr);$vi++)
				{
					//echo "in for 1<br>";
					$vstr_arr1 = explode(',',$vstr_arr[$vi]);
					$vserial_post = $vstr_arr1[0];
					$vname_post = $vstr_arr1[1];

					for($cui=0;$cui<sizeof($station_str_tmp1);$cui++)
					{
						//echo "station_str_tmp1=".$station_str_tmp1[$cui]."<br>";                           
						for($exi=0;$exi<sizeof($excel_data_array);$exi++)
						{
							// echo "excel_data_array=".$excel_data_array[$exi]."<br>";
							$excel_data_array1=explode(",",$excel_data_array[$exi]);
							// Billing date Route Sold-to party VehicleNo Google Location IN TIME OUT TIME DURATION(H:m:s)
							//echo "sole_to_party=".$data[2]." vaname=".$data[3]." geoloction=".$data[4]." in time=".$data[5]." outtime=".$data[6]." duration=".$data[7]."<br>";
							// echo "customer=".$station_str_tmp1[$cui]." data=".$excel_data_array1[2]." vname=".$vname_post." data=".$excel_data_array1[3]."<br>";
							if(($station_str_tmp1[$cui]==$excel_data_array1[1]) && ($vname_post==$excel_data_array1[0]))                                           
							{ 
								$flag=1;
								$final_data_array[]=$scnt."@".$vname_post.'@'.$excel_data_array1[1]."@-@".$excel_data_array1[3]."@".$excel_data_array1[5]."@".$excel_data_array1[6]."@".$csv_data_array1[2]."@".$csv_data_array1[7];
								$scnt++;   
								//echo "geoloction=".$excel_data_array1[4]." in time=".$excel_data_array1[5]." outtime=".$excel_data_array1[6]." duration=".$excel_data_array1[7]."<br>";
							} 
						}                                
					}
				}				
			}
		}
	}	
	function get_csv_data($file_path_1)
	{
		global $flag;
		if(file_exists($file_path_1)) 
		{
			// echo "in if 123<br>";
			$size = filesize($file_path_1);
			//echo "file_size=".$size."<br>";
			if($size)
			{
				//echo "in if 1<br>";
				$file = fopen($file_path_1,"r");
				if($file)
				{
					//echo "in if 2<br>";
					$cnt=0;
					$csv_data_array=array();
					while(($data = fgetcsv($file, 1000, ",")) !== FALSE) 
					{
						// echo "in while<br>";
						if($cnt>0)
						{                                                              
							// Billing date Route Sold-to party VehicleNo Google Location IN TIME OUT TIME DURATION(H:m:s)
							$num = count($data);                            
							$tmp_str="";
							for ($c=0; $c < $num; $c++) 
							{
								$tmp_str=$tmp_str.$data[$c].",";                                   
							}
							// echo "tmp_str=".$tmp_str."<br>";
							$tmp_str=  substr($tmp_str, 0, -1);
							$csv_data_array[]=$tmp_str;
						}
						$cnt++;
					}
					//print_r($excel_data_array);
					for($vi=0;$vi<sizeof($vstr_arr);$vi++)
					{
						//echo "in for 1<br>";
						$vstr_arr1 = explode(',',$vstr_arr[$vi]);
						$vserial_post = $vstr_arr1[0];
						$vname_post = $vstr_arr1[1];

						for($cui=0;$cui<sizeof($station_str_tmp1);$cui++)
						{
							//echo "station_str_tmp1=".$station_str_tmp1[$cui]."<br>";                           
							for($exi=0;$exi<sizeof($csv_data_array);$exi++)
							{
								// echo "excel_data_array=".$excel_data_array[$exi]."<br>";
								$csv_data_array1=explode(",",$csv_data_array[$exi]);
								// Billing date Route Sold-to party VehicleNo Google Location IN TIME OUT TIME DURATION(H:m:s)
								//echo "sole_to_party=".$data[2]." vaname=".$data[3]." geoloction=".$data[4]." in time=".$data[5]." outtime=".$data[6]." duration=".$data[7]."<br>";
								// echo "customer=".$station_str_tmp1[$cui]." data=".$excel_data_array1[2]." vname=".$vname_post." data=".$excel_data_array1[3]."<br>";
								if(($station_str_tmp1[$cui]==$csv_data_array1[2]) && ($vname_post==$csv_data_array1[3]))                                           
								{ 
									$flag=1;
									$final_data_array[]=$scnt."@".$vname_post."@".$csv_data_array1[2]."@".$csv_data_array1[4]."@".$csv_data_array1[0]."@".$csv_data_array1[5]."@".$csv_data_array1[6]."@".$csv_data_array1[7];
									$scnt++;   
									//echo "geoloction=".$excel_data_array1[4]." in time=".$excel_data_array1[5]." outtime=".$excel_data_array1[6]." duration=".$excel_data_array1[7]."<br>";
								} 
							}                                
						}
					}
				}
			}
		} 
	}
 
	  
	  if($flag==0)
	  {
		echo'<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
				<tr>
					<td class="text" align="center" width="5%">
						<font color="red">
							<b>No Data Found</b>
						</font>
					</td> 
				</tr>
			</table>';
	  }
	  else
	  {
	echo'<table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
			<tr>
				<td class="text" align="left" width="5%">
					<b>SNo</b>
				</td>           
				 <td class="text" align="left">
					<b>Vehicle Name</b>
				</td>
				 <td class="text" align="left">
					<b>Customer Number</b>
				</td> ';
				if($Row[0]=="#1")
				{			
				echo'<td class="text" align="left">
						<b>Google Location</b>
					</td>';
				}
			echo'<td class="text" align="left">
					<b>Date</b>
				</td>
				<td class="text" align="left">
					<b>Enter Time</b>
				</td>
				<td class="text" align="left">
					<b>Leave Time</b>
				</td>                     
				<td class="text" align="left">
					<b>Duration (hr:min:sec)</b>
				</td>';
				if($Row[0]=="#2")
				{			
				echo'<td class="text" align="left">
						<b>Shift</b>
					</td>';
				}				
		echo'</tr>';
			for($i=0;$i<sizeof($final_data_array);$i++)
			{
				$final_data_array_1=explode("@",$final_data_array[$i]);
				echo"<tr>
						<td>".$final_data_array_1[0]."</td>
						<td>".$final_data_array_1[1]."</td>
						<td>".$final_data_array_1[2]."</td>";
						if($Row[0]=="#1")
						{
					echo"<td>".$final_data_array_1[3]."</td>";
						}
					echo"<td>".$final_data_array_1[4]."</td>
						<td>".$final_data_array_1[5]."</td>
						<td>".$final_data_array_1[6]."</td>
						<td>".$final_data_array_1[7]."</td>";
						if($Row[0]=="#2")
						{			
					echo"<td>".$final_data_array_1[8]."</td>";
						}
				echo"</tr>";
				echo"<input TYPE=\"hidden\" VALUE=\"$final_data_array_1[0]\" NAME=\"temp[$i][SNo]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$final_data_array_1[1]\" NAME=\"temp[$i][Vehicle Name]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$final_data_array_1[2]\" NAME=\"temp[$i][Customer Number]\">";
				if($Row[0]=="#1")
				{
					echo"<input TYPE=\"hidden\" VALUE=\"$final_data_array_1[3]\" NAME=\"temp[$i][Google Location]\">";
				}
				echo"<input TYPE=\"hidden\" VALUE=\"$final_data_array_1[4]\" NAME=\"temp[$i][Date]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$final_data_array_1[5]\" NAME=\"temp[$i][In Time]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$final_data_array_1[6]\" NAME=\"temp[$i][Out Time]\">";
				echo"<input TYPE=\"hidden\" VALUE=\"$final_data_array_1[7]\" NAME=\"temp[$i][Duration (hr:min:sec)]\">";
				if($Row[0]=="#2")
						{			
					echo"<input TYPE=\"hidden\" VALUE=\"$final_data_array_1[8]\" NAME=\"temp[$i][Sift]\">";
						}
				if($Row[0]=="#1")
				{
					$place_name_tmp=str_replace(",","",$excel_data_array1[2]);
					$csv_string = $csv_string.$final_data_array_1[0].','.$final_data_array_1[1].','.$final_data_array_1[2].','.$final_data_array_1[3].','.$final_data_array_1[4].','.$final_data_array_1[5].','.$final_data_array_1[6].','.$final_data_array_1[7]."\n"; 
				}
				else if($Row[0]=="#2")
				{
					$csv_string = $csv_string.$final_data_array_1[0].','.$final_data_array_1[1].','.$final_data_array_1[2].','.$final_data_array_1[4].','.$final_data_array_1[5].','.$final_data_array_1[6].','.$final_data_array_1[7].','.$final_data_array_1[8]."\n"; 
				}
			}
			  echo'</table>';
       $scnt_1=sizeof($final_data_array);
echo'<input TYPE="hidden" VALUE="Station_Halt" NAME="csv_type">';
  echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
  echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$scnt_1.'\');" value="Get PDF" class="noprint">&nbsp;
      <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
      <center>
      </form>';
	  }
	
	 echo'</body>';
    function createDateRangeArray($strDateFrom,$strDateTo)
    {
        $aryRange=array();
        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }

  //NEW CODE
 
  
 /* $date1 = $_POST['start_date'];
  $date2 = $_POST['end_date'];
  
  $date1 = str_replace("/","-",$date1);
  $date2 = str_replace("/","-",$date2);

	
$j=-1;

for($i=0;$i<sizeof($imei);$i++)
{				
  /*echo "<br>a".$i."=".$vname[$i];
  echo "<br>lat".$i."=".$lat[$i];
  echo "<br>lng".$i."=".$lng[$i];
  echo "<br>arrival_time".$i."=".$arrival_time[$i];
  echo "<br>dep_time".$i."=".$dep_time[$i];
  echo "<br>duration".$i."=".$duration[$i]; */
        
 /* if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
  {
    $k=0;
    $j++;
    $sno = 1;
    $title="Supply Timing Report : ".$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";
    $vname1[$j][$k] = $vname[$i];
    $imei1[$j][$k] = $imei[$i];
    
    echo'
    <br><table align="center">
    <tr>
    	<td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
    </tr>
    </table>
    <table border=1 width="90%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
    <tr>
				<td class="text" align="left" width="5%"><b>SNo</b></td>														
				<td class="text" align="left"><b>Enter Time</b></td>
				<td class="text" align="left"><b>Leave Time</b></td>
				<td class="text" align="left"><b>Station Name</b></td>
				<td class="text" align="left"><b>Google Location</b></td>
				<td class="text" align="left"><b>Duration (hr:min:sec)</b></td>				
    </tr>';  								
  }
        							                    
  /*include("get_location.php"); 
          	
	$lt1 = $lat[$i];
	$lng1 = $lng[$i];
	$alt1 = "-";								
	 
  if($access=='Zone')
	{
		get_location($lt1,$lng1,$alt1,&$place,$zoneid,$DbConnection);
	}
	else
	{
		get_location($lt1,$lng1,$alt1,&$place,$DbConnection);
	} 

	$placename[$i] = $place;*/
  
  ////////////////////////////
  /*$landmark="";
  get_landmark($lt1,$lng1,&$landmark);    // CALL LANDMARK FUNCTION
	
  $place = $landmark;
  
  if($place=="")
  {
    get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
  }
	
  //echo "P:".$place;
  
  $placename[$i] = $place;  */
  ////////////////////////////														
	/*echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';											
	echo'<td class="text" align="left">'.$enter_time[$i].'</td>';	
  echo'<td class="text" align="left">'.$leave_time[$i].'</td>';	
  echo'<td class="text" align="left">'.$station[$i].'</td>';
  echo'<td class="text" align="left">'.$google_loc[$i].'</td>';	
	echo'<td class="text" align="left">'.$duration[$i].'</td>';							
	echo'</tr>';							
	
	$enter_time1[$j][$k] = $enter_time[$i];
	$leave_time1[$j][$k] = $leave_time[$i];
	$station1[$j][$k] = $station[$i];
	$google_loc1[$j][$k] = $google_loc[$i];
  $duration1[$j][$k] = $duration[$i];
	
	//echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];
	$k++;   
  $sno++;      				  				
}

echo '</table>';							
//PDF CODE

$size_vserial = sizeof($imei);
echo '<form method = "post" target="_blank">';
$csv_string = "";
    
for($x=0;$x<=$j;$x++)
{												
    for($y=0;$y<$k;$y++)
    {          
      $pdf_enter_time = $enter_time1[$x][$y];
      $pdf_leave_time = $leave_time1[$x][$y];
      $pdf_station = $station1[$x][$y];
      $pdf_geo_loc = $google_loc1[$x][$y];
      $pdf_duration =  $duration1[$x][$y];
              
      if($y==0)
      {
      	$title="Supply Timing Report : ".$vname1[$x][$y]." (".$imei1[$x][$y].") (datefrom:".$date1." to dateto:".$date2.")";
      	//echo "<br>pl=".$pdf_place_ref;
      	$csv_string = $csv_string.$title."\n";
      	$csv_string = $csv_string."SNo,Enter Time,Leave Time, Station Name, Duration (Hrs.min)\n";
        echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
      }
      														
      $sno_1 = $y+1;										
      echo"<input TYPE=\"hidden\" VALUE=\"$sno_1\" NAME=\"temp[$x][$y][SNo]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_enter_time\" NAME=\"temp[$x][$y][Enter Time]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_leave_time\" NAME=\"temp[$x][$y][Leave Time]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_station\" NAME=\"temp[$x][$y][Station Name]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_geo_loc\" NAME=\"temp[$x][$y][Google Location]\">";
      echo"<input TYPE=\"hidden\" VALUE=\"$pdf_duration\" NAME=\"temp[$x][$y][Duration (hr:min:sec)]\">";
      
      $pdf_geo_loc = str_replace(',',':',$pdf_geo_loc);
      
      $csv_string = $csv_string.$sno_1.','.$pdf_enter_time.','.$pdf_leave_time.','.$pdf_station.','.$pdf_geo_loc.','.$pdf_duration."\n"; 
      ////////////////////////////////////         	          
    }		
}		
				
if($size_vserial==0)
{						
	print"<center><FONT color=\"Red\" size=2><strong>No Supply Timing Record found</strong></font></center>";
	//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
	echo'<br><br>';
}	
else
{
  echo'<input TYPE="hidden" VALUE="Station_Halt" NAME="csv_type">';
  echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
  echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type2.php?size='.$size_vserial.'\');" value="Get PDF" class="noprint">&nbsp;
  <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
  <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';
}
echo '</form>';
 	
unlink($xml_path);
echo '</center>';*/
?>
