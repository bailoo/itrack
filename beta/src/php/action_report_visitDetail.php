<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

set_time_limit(380);

include_once("calculate_distance.php");
include_once("report_title.php");
include_once("get_location.php");
include_once("get_location_cellname.php");
include_once("util.hr_min_sec.php");

include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once("sortXmlData.php");
include_once("getXmlData.php");

$DEBUG = 0;

$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$pserial = explode(':',$device_str);
$psize=count($pserial);

$startdate = str_replace("/","-",$_POST['start_date']);
$enddate = str_replace("/","-",$_POST['end_date']);
$sortBy='h';
$userInterval = $_POST['user_interval'];

global $imei0;
global $pmobile0;
global $visitStartDateTime;
global $visitEndDateTime;
global $duration0;
global $lat0;
global $lng0;
global $cellname0;
$imei0=array();
$pmobile0=array();
$pname0=array();
$visitStartDateTime=array();
$visitEndDateTime=array();
$duration0=array();
$lat0=array();
$lng0=array();
$cellname0=array();
                                  
for($i=0;$i<sizeof($pserial);$i++)
{ 
    $checkVehicleArr[$i]=$vserial[$i]; 
    $vehicle_info=get_vehicle_info($root,$pserial[$i]);
    $vehicle_detail_local=explode(",",$vehicle_info);	
    $pname = $vehicle_detail_local[0];
    $pmobile = $vehicle_detail_local[8];
    //echo   "<br>vserial[i] =".$vserial[$i];  
    //echo "pname=".$pname."pmobile=".$pmobile."<br>";
    get_visit_xml_data($pserial[$i], $pname, $pmobile, $startdate, $enddate, $userInterval);
    //echo   "t2".' '.$i;
}  

function get_visit_xml_data($person_serial, $pname, $pmobile, $startdate, $enddate, $user_interval)
{
    global $imei0;
    global $pmobile0;
    global $pname0;
    global $visitStartDateTime;
    global $visitEndDateTime;
    global $duration0;
    global $lat0;
    global $lng0;
    global $cellname0;
    $fix_tmp = 1;
    $xml_date_latest="1900-00-00 00:00:00";
    $CurrentLat = 0.0;
    $CurrentLong = 0.0;
    $LastLat = 0.0;
    $LastLong = 0.0;
    $firstData = 0;
    $distance =0.0;
    
    
    $date_1 = explode(" ",$startdate);
    $date_2 = explode(" ",$enddate);
    $datefrom = $date_1[0];
    $dateto = $date_2[0];
    $linetowrite="";
    $firstdata_flag =0;
    
    
    $date_1 = explode(" ",$startdate);
    $date_2 = explode(" ",$enddate);

    $datefrom = $date_1[0];
    $dateto = $date_2[0];
    $timefrom = $date_1[1];
    $timeto = $date_2[1];

    get_All_Dates($datefrom, $dateto, $userdates);

    //date_default_timezone_set("Asia/Calcutta");
    $current_datetime = date("Y-m-d H:i:s");
    $current_date = date("Y-m-d");
    //print "<br>CurrentDate=".$current_date;
    $date_size = sizeof($userdates);

    $count = 0;
    $j = 0;
	
    $avg_speed = null;
    $max_speed = null;
	
    $total_avg_speed = null;
    $total_max_speed = null;
	
   
    for($i=0;$i<=($date_size-1);$i++)
    {
        $parameterizeData=null;
        $parameterizeData=new parameterizeData();  

        $parameterizeData->latitude="d";
        $parameterizeData->longitude="e";
        $parameterizeData->cellName="ci";
        
        
        $SortedDataObject=null;
        $SortedDataObject=new data();
        if($date_size==1)
        {
            $dateRangeStart=$startdate;
            $dateRangeEnd=$enddate;
        }
        else if($di==($date_size-1))
        {
            $dateRangeStart=$userdates[$i]." 00:00:00";
            $dateRangeEnd=$enddate;
        }
        else if($di==0)
        {
            $dateRangeStart=$startdate;
            $dateRangeEnd=$userdates[$i]." 23:59:59";
        }
        else
        {
           $dateRangeStart=$userdates[$i]." 00:00:00";
            $dateRangeEnd=$userdates[$i]." 23:59:59";
        }
        deviceDataBetweenDates($person_serial,$dateRangeStart,$dateRangeEnd,$sortBy,$parameterizeData,$SortedDataObject);
        //var_dump($SortedDataObject);
        if(count($SortedDataObject->deviceDatetime)>0)
        {
            $total_lines = count(file($xml_original_tmp));
            //echo "<br>Total lines orig=".$total_lines;

            $xml = @fopen($xml_original_tmp, "r") or $fexist = 0;  
            //$xmllog = fopen($xml_log, "w") or $fexist1 = 0;
            $logcnt=0;
            $DataComplete=false;
                  
            $personserial_tmp=null;
            $format =2;
      
            $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);                   
            for($obi=0;$obi<$prevSortedSize;$obi++)
            {
                $DataValid = 0;
                $lat = $SortedDataObject->latitudeData[$obi];
                $lng = $SortedDataObject->longitudeData[$obi];
                $datetime=$SortedDataObject->deviceDatetime[$obi];
                if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
                {
                    $DataValid = 1;
                }
                //echo fgets($file). "<br />";
                $xml_date = $datetime;	     				
                //echo "Final0=".$xml_date." datavalid=".$DataValid;
        
                if($xml_date!=null)
                {				  
                    //echo "<br>".$xml_date.",".$startdate.",".$enddate.",".$DataValid;
                    //$lat = $lat_value[1] ;
                    //$lng = $lng_value[1];
					
                    if( ($xml_date >= $startdate && $xml_date <= $enddate) && ($DataValid==1) )
                    { 
                       
                        $cellname = $SortedDataObject->cellNameData[$obi]; 
                        if($lat!="0.0" || $lng!="0.0")
                        {
                            if($firstdata_flag==0)
                            {					
                                $firstdata_flag = 1;      						           

                                $lat1 = $lat;
                                $lng1 = $lng;
                                $datetime1 = $datetime;                 		           
                            }
                            else
                            {              
                                $lat2 = $lat;      				        					
                                $lng2 = $lng;
                                $datetime2 = $datetime;

                                calculate_distance($lat1, $lat2, $lng1, $lng2, $distance);

                                //if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.90")
                                //echo "<br>distance".$distance;
                                //echo "<br>lat1=".$lat1." ,lng1=".$lng1." #lat2=".$lat2." ,lng2=".$lng2." dist=".$distance;
                                if($distance > 0.5)
                                {
                                    /////////////////////////////
                                    $stoptime = strtotime($datetime2); 
                                    $starttime = strtotime($datetime1);                 
                                    $visit_dur =  ($stoptime - $starttime)/3600;
                                    $halt_duration = round($visit_dur,2);										
                                    $total_min = $halt_duration * 60;
                                    $total_min1 = $total_min;
                                    $hr = (int)($total_min / 60);
                                    $minutes = $total_min % 60;
                                    $hrs_min = $hr.".".$minutes;       					
                                    /////////////////////////////                  

                                    if($pmobile=="")
                                    {
                                        $pmobile = "-";
                                    }                  
                                    
                                    $imei0[]=$person_serial;
                                    $pname0[]=$pname;
                                    $pmobile0[]=$pmobile;
                                    $visitStartDateTime[]=$datetime1;
                                    $visitEndDateTime[]=$datetime2;
                                    $duration0[]=$hrs_min;
                                    $lat0[]=$lat;
                                    $lng0[] =$lng;
                                    $cellname0[]=$cellname; 
                                    
                                    $lat1 = $lat2;
                                    $lng1 = $lng2;
                                    $datetime1 = $datetime2;                
                                }                		              
                            }
                        }
                    } // $xml_date_current >= $startdate closed
                }   // if xml_date!null closed
                $count++;
                $j++;
            }
        } // if (file_exists closed
    }  // for closed 
    //echo "Test1";
    fclose($fh);
}

////////////////////////////// XML CODE ENDS ////////////////////////////////////////////
global $imei0;
global $pmobile0;
global $pname0;
global $visitStartDateTime;
global $visitEndDateTime;
global $duration0;
global $lat0;
global $lng0;
global $cellname0;
$m1=date('M',mktime(0,0,0,$month,1));						
echo'<center>';	        
$size_pserial = sizeof($pserial);
        report_title("Person Visit Report",$startdate,$enddate);
        echo'<div style="overflow: auto;height: 300px; width: 820px;" align="center">';  
        $j=-1;
        $k=0;
  	$endtable=0;
  	$tempCelldata="";			             
        for($i=0;$i<sizeof($imei0);$i++)
        {								              
            //echo "<br>lat0[".$i."]=".$lat0[$i]." #lng[".$i."]=".$lng0[$i];
      
            if(($i===0) || (($i>0) && ($imei0[$i-1] != $imei0[$i])) )
            {
                $k=0;                
                $j++;
        
                $sno = 1;
                $title="Visit Report : ".$pname0[$i]."(".$imei0[$i].")";
                $pname1[$j][$k] = $pname0[$i];
        
        echo'<br>
            <table align="center">
                <tr>
                    <td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
                </tr>
            </table>
            <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
                <tr bgcolor="grey">
                    <td class="text" align="left"><b>SNo</b></td>
                    <td class="text" align="left"><b>Start Time</b></td>
                    <td class="text" align="left"><b>Stop Time</b></td>
                    <td class="text" align="left"><b>Location</b></td>
                    <td class="text" align="left"><b>Duration (hrs.min)</b></td>	
                    <!--<td class="text" align="left"><b>GPS</b></td>-->	
                </tr>';  								
            }
                $alt="0";		  
                if($lat0[$i]=="0.0" || $lng0[$i]=="0.0")
                {
                    if($cellname0[$i]!=$tempCelldata)
                    {		   
                        get_location_cellname($cellname0[$i],$cell_lat,$cell_lng);
			   
                        //echo "<br>celllat=".$cell_lat." len=".strlen($cell_lat);
                        if( (strlen($cell_lat) > 5)  && (strlen($cell_lng) > 5) )
                        {
                            //continue;
                            get_location($cell_lat,$cell_lng,$alt,$placename,$DbConnection);
                            $tempCelldata=$cellname0[$i];
                        }
                        else
                        {
                            continue;
                        }
                        //get_location($cell_lat,$cell_lng,$alt,&$placename,$DbConnection);
                        //$tempCelldata=$cellname0[$i];
                    }
                    $gps="NO";
                    //continue;
                }
                else
                {
                    get_location($lat0[$i],$lng0[$i],$alt,$placename,$DbConnection);    
                    $gps="YES";
                }
           
                ///// CONVERT DATE TIME IN DD, MM, YYYY FORMA
                $datestr = explode(' ',$datetime1[$i]);
                $date_tmp = $datestr[0];
                $time_tmp = $datestr[1];

                $date_substr = explode('-',$date_tmp);
                $year = $date_substr[0];
                $month = $date_substr[1];
                $day = $date_substr[2];

                $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
                $datetime1[$i] = $display_datetime;
                ///////////////////////////////////////////////

                ///// CONVERT DATE TIME IN DD, MM, YYYY FORMA
                $datestr = explode(' ',$datetime2[$i]);
                $date_tmp = $datestr[0];
                $time_tmp = $datestr[1];

                $date_substr = explode('-',$date_tmp);
                $year = $date_substr[0];
                $month = $date_substr[1];
                $day = $date_substr[2];

                $display_datetime = $day."-".$month."-".$year." ".$time_tmp;
                $datetime2[$i] = $display_datetime;
                ///////////////////////////////////////////////      
                 
            echo'<tr>
                    <td class="text" align="left" width="4%"><b>'.$sno.'</b></td>        												
                    <td class="text" align="left">'.$visitStartDateTime[$i].'</td>
                    <td class="text" align="left">'.$visitEndDateTime[$i].'</td>      	
                    <td class="text" align="left">'.$placename.'</td>
                    <td class="text" align="left">'.$duration0[$i].'</td>						
                </tr>';	          		
                //echo "<br>arr_time1[$j][$k]main=".$arr_time1[$j][$k];      
                $datetime_1[$j][$k] = $visitStartDateTime[$i];
                $datetime_2[$j][$k] = $visitEndDateTime[$i];
                $duration_1[$j][$k] = $duration0[$i];		
                $placename1[$j][$k] = $placename;										
                
                if( (($i>0) && ($imei0[$i+1] != $imei0[$i])) )
                {         
                    $endtable=1;
                    echo'</table>';        
                    $no_of_data[$j] = $k;
                    //echo "<br>NO data1=".$no_of_data[$j];      
                } 	
                $k++;   
                $sno++;                                   							  		
        }   
        if(!$endtable)
        {
            $no_of_data[$j] = $k-1;
            echo '</table>';
        }
   echo "</div>";
    ///////// GET PDF   
    //echo'<form method = "post" action="src/php/report_getpdf_type4.php?size='.$psize.'" target="_blank">';
echo'<form method = "post" target="_blank">';
	//echo "<br>j=".$j;
        $csv_string = "";
        $csv_string = $csv_string."Title,SNo,Datetime1,Datetime2,Location,Duration (hrs.min)\n";
  
        for($x=0;$x<=$j;$x++)
	{								
            $title=$pname1[$x][0].":Visit Report From DateTime : ".$date1."-".$date2;
            $title_csv = $pname1[$x][0];
            echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";		
            $sno=0;
            //echo "<br>nodata=".$no_of_data[$x];
            for($y=0;$y<=$no_of_data[$x];$y++)
            {
                //$k=$j-1;
                $sno++;
                    
                $datetimetmp1 = $datetime_1[$x][$y];
                $datetimetmp2 = $datetime_2[$x][$y];
                $durationtmp = $duration_1[$x][$y];	
                $placenametmp = $placename1[$x][$y];										
                //$gpttemp = $gps1[$x][$y];
			
                //echo "dt=".$datetmp1;				
                echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
                echo"<input TYPE=\"hidden\" VALUE=\"$datetimetmp1\" NAME=\"temp[$x][$y][Start Time]\">";
                echo"<input TYPE=\"hidden\" VALUE=\"$datetimetmp2\" NAME=\"temp[$x][$y][End Time]\">";
                echo"<input TYPE=\"hidden\" VALUE=\"$placenametmp\" NAME=\"temp[$x][$y][Location]\">";
                echo"<input TYPE=\"hidden\" VALUE=\"$durationtmp\" NAME=\"temp[$x][$y][Duration (hrs.min)]\">";
			
                //echo"<input TYPE=\"hidden\" VALUE=\"$gpttemp\" NAME=\"temp[$x][$y][GPS]\">";
      
                /// CODE FOR CSV
                $placenametmp = str_replace(',',':',$placenametmp);
                //$csv_string = $csv_string.$title_csv.','.$sno.','.$datetimetmp1.','.$datetimetmp2.','.$placenametmp.','.$gpttemp."\n";
                $csv_string = $csv_string.$title_csv.','.$sno.','.$datetimetmp1.','.$datetimetmp2.','.$placenametmp.','.$durationtmp."\n";  
      ////////////////////////////////////																					
            }		
	}			 
  echo'<table align="center">
        <tr>
            <td>';
  		if(sizeof($imei0)==0)
  		{						
                    print"<center>
                            <FONT color=\"Red\" size=2>
                                <strong>No Visit Record found</strong>
                            </font>
                        </center>";
  			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=HaltReport.php\">";
  			echo'<br><br>';
  		}	
  		else
  		{
                    echo'<input TYPE="hidden" VALUE="Visit" NAME="csv_type">';
                    echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
                    echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$psize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
                    <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
                }
                  
      echo'</td>		
        </tr>
    </table>
</form>';
echo'</center>';


echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	
?>								