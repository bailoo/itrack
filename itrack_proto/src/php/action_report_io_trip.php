<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
set_time_limit(300);	
date_default_timezone_set("Asia/Kolkata");
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
$root=$_SESSION["root"];
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
$report = "door open";
include_once("calculate_distance.php");
include_once("report_title.php");	
include_once("util.hr_min_sec.php");

include_once('xmlParameters.php');
include_once("report_title.php");
include_once('parameterizeData.php');
include_once('data.php');
include_once("sortXmlData.php");
include_once("getXmlData.php");
include_once("get_location.php");

$device_str = $_POST['vehicleserial'];
//echo "<br>devicestr=".$device_str;
$vserial = explode(':',$device_str);
$vsize=count($vserial);

$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];
$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);
$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];


$userInterval = "0";
$requiredData="All";
$sortBy="h";
$firstDataFlag=0;
$endDateTS=strtotime($date2);
$dataCnt=0;

$ioFoundFlag=0;
$finalInverseArr=array();
$finalVNameArr=array();
for($i=0;$i<$vsize;$i++)
{
	$vehicle_info=get_vehicle_info($root,$vserial[$i]);
	$vehicle_detail_local=explode(",",$vehicle_info);
	//echo "vehicle_detail_local=".$vehicle_detail_local[7]."<br>";
	$finalVNameArr[$i]=$vehicle_detail_local[0];
	$ioArr=explode(":",$vehicle_detail_local[7]);
	
	$ioArrSize=sizeof($ioArr);
	for($z=0;$z<$ioArrSize;$z++)
	{
		$tempIo=explode("^",$ioArr[$z]);
		//echo "io=".$ioArr[$z]."<br>";
		if($tempIo[1]=="sos")
		{
			$ioFoundFlag=1;
			$parameterizeData->engineRunHr=$finalIoArr[$tempIo[0]];
		}
		if($tempIo[1]=="sos_type")
		{
			$finalInverseArr[$i]=1;
		}
		else
		{
			$finalInverseArr[$i]=1;
		}
	}
	//echo "tmpio=".$parameterizeData->temperature."<br>";
	if($ioFoundFlag==1)
	{
		//echo "temperature=".$parameterizeData->temperature."<br>";
		
		$LastSortedDate = getLastSortedDate($vserial[$i],$datefrom,$dateto);
		$SortedDataObject=new data();
		$UnSortedDataObject=new data();
		if(($LastSortedDate+24*60*60)>=$endDateTS) //All sorted data
		{	
			//echo "in if1";
			$type="sorted";
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		}
		else if($LastSortedDate==null) //All Unsorted data
		{
			//echo "in if2";
			$type="unSorted";
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}
		else //Partially Sorted data
		{
			$LastSDate=date("Y-m-d",$LastSortedDate+24*60*60);
			//echo "in else";
			$type="sorted";					
			readFileXml($vserial[$i],$date1,$date2,$datefrom,$LastSDate,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$SortedDataObject);
		
			$type="unSorted";
			readFileXml($vserial[$i],$date1,$date2,$LastSDate,$dateto,$userInterval,$requiredData,$sortBy,$type,$parameterizeData,$firstDataFlag,$UnSortedDataObject);
		}
		
		//var_dump($UnSortedDataObject);
		//var_dump($SortedDataObject);		
		
		if(count($SortedDataObject->deviceDatetime)>0)
		{
			$prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
			for($obi=0;$obi<$prevSortedSize;$obi++)
			{
				$finalLatitudeArr[$i][$dataCnt]=$SortedDataObject->latitudeData[$obi];
				$finalLongitudeArr[$i][$dataCnt]=$SortedDataObject->longitudeData[$obi];	
				$finalDateTimeArr[$i][$dataCnt]=$SortedDataObject->deviceDatetime[$obi];				
				$finalIODataArr[$i][$dataCnt]=$SortedDataObject->engineIOData[$obi];
				$dataCnt++;
			}
		}
		if(count($UnSortedDataObject->deviceDatetime)>0)
		{
			$sortObjTmp=sortData($UnSortedDataObject,$sortBy,$parameterizeData);
			//var_dump($sortObjTmp);
			$sortedSize=sizeof($sortObjTmp->deviceDatetime);
			for($obi=0;$obi<$sortedSize;$obi++)
			{
				$finalLatitudeArr[$i][$dataCnt]=$sortObjTmp->latitudeData[$obi];
				$finalLongitudeArr[$i][$dataCnt]=$sortObjTmp->longitudeData[$obi];	
				$finalDateTimeArr[$i][$dataCnt]=$sortObjTmp->deviceDatetime[$obi];					
				$finalIODataArr[$i][$dataCnt]=$sortObjTmp->engineIOData[$obi];
				$dataCnt++;
			}
		}
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
		$parameterizeData=null;
		
	}
	else
	{
		$SortedDataObject=null;			
		$sortObjTmp=null;
		$UnsortedDataObject =null;
		$parameterizeData=null;
	}
}
$o_cassandra->close();

for($i=0;$i<$vsize;$i++)
{
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
	$runhr_duration =0 ;
	$flag =0;

	$StartFlag=0;
	$continuous_running_flag =0;
	$innerSize=sizeof($finalDateTimeArr[$i]);
	for($j=0;$j<$innerSize;$j++)
	{
		$datetime = $finalDateTimeArr[$i][$j]; 
		$engine_count = $finalIODataArr[$i][$j];                                                                            	                         
		//echo "<br>enginecount=".$engine_count;
 
		$lat = $finalLatitudeArr[$i][$j]; 
		$lng = $finalLongitudeArr[$i][$j];
		if($engine_count>500)
		{
			$continuous_running_flag = 1;
		}

		if($engine_count>500 && $StartFlag==0)  //500
		{                						
			//echo "ONe";
			$time1 = $datetime;
			get_location($lat,$lng,"-",&$loc1,$DbConnection);	
			//echo "<br>Loc1=".$loc1;					
			$StartFlag = 1;
		} 
		//else if( ($engine_count<500 && $StartFlag==1) || ( ($c==($total_lines-1)) && ($continuous_running_flag ==1) ) )   //500
		else if(($engine_count<500 && $StartFlag==1) || ($continuous_running_flag ==1))   //500
		{
			//echo "Two";
			$StartFlag = 2;
		}
		$time2 = $datetime;

		if($StartFlag == 2)
		{
			//echo "Three";
			$StartFlag=0;
			$runtime = strtotime($time2) - strtotime($time1);
			if($runtime > 60)
			{
				get_location($lat,$lng,"-",&$loc2,$DbConnection);
				$imei[]=$vserial[$i];
				$vname[]=$finalVNameArr[$i];
				$dateFromDisplay[]=$time1;
				$loc1[]=$loc1;
				$dateToDisplay[]=$$time2;
				$loc2[]=$loc2;
			} 
		}
	}
}


	
$m1=date('M',mktime(0,0,0,$month,1));
  
  echo'<center>';   
  report_title("IO Trip Report",$date1,$date2);   
	echo'<div style="overflow: auto;height: 285px; width: 600px;" align="center">';
 
  			             
  $j=-1;
  $k=0;
    
  $single_data_flag=1;	
$datefrom1=array(); 
$loc1_1=array();  
$dateto1=array();  
$loc2_1=array();   
  		             
  for($i=0;$i<sizeof($imei);$i++)
	{	    						                  
    if(($i==0) || (($i>0) && ($imei[$i-1] != $imei[$i])) )
    {
      $k=0;                                              
      $j++;
      $sum_engine_runhr =0;
      /*$sum_engine_runmin =0;
      $sum_engine_runsec =0; */
      $total_engine_runhr[$j] =0;
      
      $sno = 1;
      $title='IO Trip Report : '.$vname[$i]." &nbsp;<font color=red>(".$imei[$i].")</font>";;
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
			<td class="text" align="left"><b>SNo</b></td>
			<td class="text" align="left"><b>Start Time </b></td>
			<td class="text" align="left"><b>Start Location</b></td>
			<td class="text" align="left"><b>End Time </b></td>
			<td class="text" align="left"><b>End Location</b></td>			
      </tr>';  								
    }                                                                        				
            		              
    echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
	echo'<td class="text" align="left">'.$dateFromDisplay[$i].'</td>';
	echo'<td class="text" align="left">'.$loc1[$i].'</td>';	
    echo'<td class="text" align="left">'.$dateToDisplay[$i].'</td>';
	echo'<td class="text" align="left">'.$loc2[$i].'</td>';						
	echo'</tr>';	          		
    
    $datefrom1[$j][$k] = $datefrom[$i];
	$loc1_1[$j][$k] = $loc1[$i];		
    $dateto1[$j][$k] = $dateto[$i];
	$loc2_1[$j][$k] = $loc2[$i];			
    //echo "<br>engine_run=".$engine_runhr1[$j][$k]." ,i=".$i." ,j=".$j." ,k=".$k;
    //echo "<br>imei[i+1]=".$imei[$i+1]." ,ime[i]=".$imei[$i];     			    				  				
	
	  if( (($i>0) && ($imei[$i+1] != $imei[$i])) )
    {  
        $single_data_flag = 0;
       
        echo '</table>';
        
        $no_of_data[$j] = $k;        
		
		}  
    $k++;   
    $sno++;                       							  		
 }


 
	echo "</div>";     

 	echo'<form method = "post" target="_blank">';
	
	$csv_string = "";
	//echo "<br>j=".$j;
	for($x=0;$x<=$j;$x++)
	{								
		$title = $vname1[$x][0]." (".$imei1[$x][0]."): IO Trip Report- From DateTime : ".$date1."-".$date2;
		$csv_string = $csv_string.$title."\n";
		$csv_string = $csv_string."SNo,Start Time, Start Location,End Time,End Location\n";
		echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$x]\">";

		$sno=0;
		//echo "<br>noofdata=".$no_of_data[$x];
		
		for($y=0;$y<=$no_of_data[$x];$y++)
		{
			//$k=$j-1;
			$sno++;
                    
			$datetmp1 = $datefrom1[$x][$y];
			$loc1_tmp = $loc1_1[$x][$y];			
			$datetmp2 = $dateto1[$x][$y];										
			$loc2_tmp = $loc2_1[$x][$y];	
			//echo "<br>x=".$x." ,y=".$y." ,temp_runhr=".$engine_runhr_tmp;
			
			//echo "dt=".$datetmp1;								
			echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$x][$y][SNo]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][$y][Start Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$loc1_tmp\" NAME=\"temp[$x][$y][Start Location]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$datetmp2\" NAME=\"temp[$x][$y][End Time]\">";
			echo"<input TYPE=\"hidden\" VALUE=\"$loc2_tmp\" NAME=\"temp[$x][$y][End Location]\">";
      
			$csv_string = $csv_string.$sno.','.$datetmp1.','.$loc1_tmp.','.$datetmp2.','.$loc1_tmp."\n";         																	
		}		

		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Start Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][Start Location]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][End Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$x][$y][End Location]\">";									
		
		$m = $y+1;								
		
		echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$x][$m][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$x][$m][Start Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$loc1_tmp\" NAME=\"temp[$x][$m][Start Location]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$x][$m][End Time]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$loc2_tmp\" NAME=\"temp[$x][$m][End Location]\">";
		$csv_string = $csv_string."\nTotal,".$date1.",".$loc1_tmp.",".$date2.",".$loc2_tmp."\n\n";       
	
    //if($y>1)
		//{        		
    //}																																										
	}																						
      
  echo'	
    <table align="center">
		<tr>
			<td>';
      
  		if(sizeof($imei)==0)
  		{						
  			print"<center><FONT color=\"Red\" size=2><strong>Sorry! No IO Trip Record Found</strong></font></center>";
  			echo'<br><br>';
  		}	
  		else
  		{
        echo'<input TYPE="hidden" VALUE="engine_runhr" NAME="csv_type">';
        echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
        echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type4.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
        <!--<input type="button" value="Print it" onclick="window.print()" class="noprint">-->&nbsp;';      
      }
                  
      echo'</td>		
    </tr>
		</table>
		</form>';
echo '</center>';		
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';			
                     
?>							 					
