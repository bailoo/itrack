<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
set_time_limit(80000);
//==========include libraray and class and function==============//
include('class_polyline_edge.php');
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once("report_title.php");

include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once("getXmlData.php");

$device_str = $_POST['vehicleserial'];
$vserial = $device_str;
//echo "vserial=".$vserial."<br>";
$date1 = $_POST['start_date'];
$date2 = $_POST['end_date'];
$date1 = str_replace("/","-",$date1);
$date2 = str_replace("/","-",$date2);
$date_1 = explode(" ",$date1);
$date_2 = explode(" ",$date2);
$datefrom = $date_1[0];
$dateto = $date_2[0];

get_All_Dates($datefrom, $dateto, $userdates);    
$date_size = sizeof($userdates);

$user_interval = $_POST['user_interval'];
//=======[FILE]Code for getting lat lng from FILE of selected vehicle with date time and interval=========//

$sortBy='h';
$requiredData="All";

$parameterizeData=new parameterizeData();
$parameterizeData->latitude="d";
$parameterizeData->longitude="e";
$parameterizeData->speed="f";

$vehicle_info=get_vehicle_info($root,$vserial);
$vehicle_detail_local=explode(",",$vehicle_info);	
$vname = $vehicle_detail_local[0];


$CurrentLat = 0.0;
$CurrentLong = 0.0;
$LastLat = 0.0;
$LastLong = 0.0;
$firstData = 0;
$distance =0.0;
$firstdata_flag =0;
$j = 0;

for($di=0;$di<=($date_size-1);$di++)
{ 
    $SortedDataObject=new data();
    readFileXmlNew($vserial,$userdates[$di],$requiredData,$sortBy,$parameterizeData,$SortedDataObject);
    //var_dump($SortedDataObject);
    if(count($SortedDataObject->deviceDatetime)>0)
    {
       $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);
        for($obi=0;$obi<$prevSortedSize;$obi++)
        {
            $DataValid = 0;
            $lat = $SortedDataObject->latitudeData[$obi];
            $lng = $SortedDataObject->longitudeData[$obi];
            if((strlen($lat)>5) && ($lat!="-") && (strlen($lng)>5) && ($lng!="-"))
            {
                $DataValid = 1;
            }
            if(($DataValid==1) && ($SortedDataObject->deviceDatetime[$obi] > $date1 && $SortedDataObject->deviceDatetime[$obi] < $date2))
            {
                $datetime = $SortedDataObject->deviceDatetime[$obi];
               
                if($firstdata_flag==0)
                {
                    $firstdata_flag = 1;
                    $interval = (double)$user_interval*60;
                    $time1 = $datetime;					
                    $date_secs1 = strtotime($time1);								
                    $date_secs1 = (double)($date_secs1 + $interval); 
                    $date_secs2 = 0;
                }
                else
                { 
                    $time2 = $datetime;											
                    $date_secs2 = strtotime($time2);
                    
                    if( ($date_secs2 >= $date_secs1))// || ($f == $total_lines-5))
                    {
                        $dateToArr[]=$time2;
                        $latArr[]=$lat;
                        $lngArr[]=$lng;
                        //reassign time1
                        $time1 = $datetime;
                        $date_secs1 = strtotime($time1);
                        $date_secs1 = (double)($date_secs1 + $interval);
                    }								
                }
            } 
        }
        $dateToArr[]=$time2;
        $latArr[]=$lat;
        $lngArr[]=$lng;
        
	$time1 = $datetime;
	$date_secs1 = strtotime($time1);
	$date_secs1 = (double)($date_secs1 + $interval);		    						
	
    }
}
//print_r($latArr);
$o_cassandra->close();
$parameterizeData=null;
$rowArr=getPolyLineDetail($account_id,1,$vserial,$DbConnection);
if(count($rowArr)>0)
{
    $polyline_coord_tmp = $rowArr[0];
    $polyline_coord = base64_decode($polyline_coord_tmp);//polyline in base64 must be decode before use

    $polyline_coord = str_replace('),(',' ',$polyline_coord);
    $polyline_coord = str_replace('(','',$polyline_coord);
    $polyline_coord = str_replace(')','',$polyline_coord);
    $polyline_coord = str_replace(', ',',',$polyline_coord);	
    $polyline_data=explode(" ",$polyline_coord);
    $polyline_name=$rowArr[1];

    $chk_latlng_array=array();
    $data_date_array=array();
    //samples
    /*$chk_latlng_array[]="26.513034341866465, 80.24422645568848";
    $chk_latlng_array[]="26.512343102724035, 80.26216506958008";
    $chk_latlng_array[]="26.503587046862997, 80.27263641357422";
    $chk_latlng_array[]="26.494830323685452, 80.2796745300293";	*/
    ///////////////////  READ  XML 	//////////////////////////////	
    for($i=0;$i<sizeof($latArr);$i++)
    {
        $chk_latlng_array[]=substr($latArr[$i], 0,-1).",".substr($lngArr[$i], 0,-1);
        $data_date_array[]=$dateToArr[$i];		
    }
    //=======[Class/Object] Class called for checking point on edge=============//
    $get_data=new class_polyline_edge();	
    $data_result = $get_data->get_polyline($polyline_data,$chk_latlng_array,$data_date_array); //both parameters in array
    //print_r($data_result);//data_result in array 
    //=====To manipulate and display on HTML Table============================//
    if(count($data_result)>0)
    {
echo'<form method = "post" target="_blank">
        <center>
            <br>';
            report_title("Route Deviation Report",$date1,$date2);
    echo'<div style="overflow: auto;height: 500px; width: 100%" align="center">';
	$title="Route ($polyline_name) Deviation Report : $vname &nbsp;<font color=red>(".$vserial.")</font>";
	$title1="Route ($polyline_name) Deviation Report : $vname &nbsp;(".$vserial.")";
	echo'<br>
                <table align="center" width=100%>
                    <tr>
                        <td class="text" align="center"><b>'.$title.'</b> <div style="height:8px;"></div></td>
                    </tr>
                </table>
	<table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=1 cellpadding=3>	
				<tr>
					<td class="text" align="left"><b>SNo</b></td>
					<td class="text" align="left"><b>Date From</b></td>
					<td class="text" align="left"><b>Date To</b></td>
					<td class="text" align="left"><b>Status</b></td>					
					<td class="text" align="left"><b>Location</b></td>						
				</tr>
	';
	$csv_string = "";
	$csv_string = $csv_string.$title1.",,,,\n";
	//$csv_string = $csv_string."SNo,DateTime,Status,VehicleLocation \n";
	$csv_string = $csv_string."SNo,DateFrom,DateTo,Status,Location \n";
	
	$sno = 1;
	$j=0;
	$k=0;
	echo"<input TYPE=\"hidden\" VALUE=\"$title1\" NAME=\"title\">";
	for($i=0;$i<sizeof($data_result);$i++)
	{
		echo'<tr><td class="text" align="left" width="4%"><b>'.$sno.'</b></td>';        												
		//echo'<td class="text" align="left">'.$dateto[$i].'</td>';
		$result_route=explode(":",$data_result[$i]);
		$datetime_from=explode(" ",$result_route[4]);
		echo'<td class="text" align="left">'.$datetime_from[0].' '.str_replace('-',':',$datetime_from[1]).'</td>'; //date from
		//echo'<td class="text" align="left">'.$result_route[4].' - '.$result_route[5].'</td>'; //date between		
		$datetime_to=explode(" ",$result_route[5]);
		echo'<td class="text" align="left">'.$datetime_to[0].' '.str_replace('-',':',$datetime_to[1]).'</td>'; //date from
		echo'<td class="text" align="left">'.$result_route[0].'</td>';	//status	
		echo"<td class='text' align='left'>$result_route[6]</td>";	//location
		$datefrom1[$k] = $datetime_from[0].' '.str_replace('-',':',$datetime_from[1]);
		$dateto1[$k] = $datetime_to[0].' '.str_replace('-',':',$datetime_to[1]);	
		//$dateto1[$k] = $result_route[4].' - '.$result_route[5];	
		$route_msg[$k] = $result_route[0];
		$vehicle_point[$k] = $result_route[6];
		echo'</tr>';
		//for csv 
		//$csv_string = $csv_string.$sno.','.$dateto1[$k].','.$route_msg[$k].','.str_replace(',','.',$vehicle_point[$k])."\n"; 
		$csv_string = $csv_string.$sno.','.$datefrom1[$k].','.$dateto1[$k].','.$route_msg[$k].','.str_replace(',','.',$vehicle_point[$k])."\n"; 
		$sno++;
		$k++; 
	}
	
	echo '</table>';
	
	$sn=0;
	for($x=0;$x<$k;$x++)
	{
		$sn++;
		/*
		$datetmp1 = $dateto1[$x];														
		$route_msg1 = $route_msg[$x];
		$vehicle_point1 = $vehicle_point[$x];
		echo"<input TYPE=\"hidden\" VALUE=\"$sn\" NAME=\"temp[$x][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][DateTime]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$route_msg1\" NAME=\"temp[$x][Status]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_point1\" NAME=\"temp[$x][LatLng]\">";
		*/
		$datetmp0 = $datefrom1[$x];
		$datetmp1 = $dateto1[$x];														
		$route_msg1 = $route_msg[$x];
		$vehicle_point1 = $vehicle_point[$x];
		echo"<input TYPE=\"hidden\" VALUE=\"$sn\" NAME=\"temp[$x][SNo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$datetmp0\" NAME=\"temp[$x][DateFrom]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$datetmp1\" NAME=\"temp[$x][DateTo]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$route_msg1\" NAME=\"temp[$x][Status]\">";
		echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_point1\" NAME=\"temp[$x][Location]\">";
	}
	
	echo "</div>";
	//=====PDF and EXCEL REPORT===============================================//
	
	
	$vsize = sizeof($data_result);
	//echo"size=".$vsize;
	echo'	
    <table align="center">
		<tr>
			<td>'; 		
			echo'<input TYPE="hidden" VALUE="route_deviation" NAME="csv_type">';
			echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
			echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$vsize.'\');" value="Get PDF" class="noprint">&nbsp;<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
			&nbsp;';           
			echo'</td>		
		</tr>
	</table>
	</form>
	';
    }
    else
    {
      print"<center><FONT color=\"Red\" size=2><strong>No Data Record</strong></font></center>";  
    }
}
else
{
	print"<center><FONT color=\"Red\" size=2><strong>No Data Record</strong></font></center>";
}
echo '</center>';
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';	


?>