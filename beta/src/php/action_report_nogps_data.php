<?php
//error_reporting(-1);
//ini_set('display_errors', 'On');
include_once("main_vehicle_information_1.php");
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

include_once("report_hierarchy_header.php");

set_time_limit(380);

include_once("calculate_distance.php");
include_once("report_title.php");
include_once("get_location.php");
include_once("get_location_cellname.php");
include_once("util.hr_min_sec.php");

include_once('xmlParameters.php');
include_once('parameterizeData.php');
include_once('data.php');
include_once('sortXmlData.php');
include_once('getXmlData.php');

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
$visitStartDateTime=array();
$visitEndDateTime=array();
$duration0=array();
$lat0=array();
$lng0=array();
$cellname0=array();


//echo "accountId=".$account_id."<br>";
$vehicleDetailArr=getAllVehicleDetailsInArray($account_id);

//print_r($vehicleDetailArr);
date_default_timezone_set('Asia/Calcutta');
$enddate=date("Y-m-d H:i:s");

$curentDTseconds=strtotime($enddate);

$previousDateDuration=$curentDTseconds-($timeDuration*60);


$startdate=date("Y-m-d H:i:s",$previousDateDuration);

//echo "startDate=".$startdate."endDate=".$enddate."<br>";
$vehicleDetail=array();
foreach($vehicleDetailArr as $key=>$vdValue)
{  
    //echo "imei=".$vdValue['imeiNo']."name=".$vdValue[$key]['vehicleName']."<br>";  
    get_visit_xml_data($vdValue['imeiNo'], $vdValue['vehicleName'],$startdate, $enddate);
    //echo   "t2".' '.$i;
}

function get_visit_xml_data($person_serial, $pname, $startdate, $enddate)
{
    global $vehicleDetail;
    $date_1 = explode(" ",$startdate);
    $datefrom = $date_1[0];
    
    get_All_Dates($datefrom, $dateto, $userdates);    
    $date_size = sizeof($userdates);
    $inactiveVehicleFlag=1;
    
    $parameterizeData=null;
    $parameterizeData=new parameterizeData(); 
    $parameterizeData->latitude="d";
    $parameterizeData->longitude="e";   
    $parameterizeData->orderBy="DESC"; 
        
    $SortedDataObject=null;
    $SortedDataObject=new data();
    //echo "startDate=".$startdate." endDate=".$enddate." serial=".$person_serial."<br>";
    deviceDataBetweenDates($person_serial,$startdate,$enddate,$sortBy,$parameterizeData,$SortedDataObject);
    //var_dump($SortedDataObject);
    if(count($SortedDataObject->deviceDatetime)>0)
    {
        $inactiveVehicleFlag=0;
        $prevSortedSize=sizeof($SortedDataObject->deviceDatetime);                   
        for($obi=0;$obi<$prevSortedSize;$obi++)
        {
            $DataValid = 0;
            $lat = $SortedDataObject->latitudeData[$obi];
            $lng = $SortedDataObject->longitudeData[$obi];
            $datetime=$SortedDataObject->deviceDatetime[$obi];
            if((strlen($lat)>5) && ($lat!="0.0") && (strlen($lng)>5) && ($lng!="0.0"))
            {
                //echo "test";
                $vehicleDetail[$pname]=array();
                break;
            }
            if(($lat=="0.0" || $lng=="0.0") && ($DataValid!=1))
            {
                if($obi==0)
                {
                    $vehicleDetail[$pname]=array(
                                                'imeiNo'=>$person_serial,
                                                'noGpsOrInactiveDT'=>$SortedDataObject->deviceDatetime[$obi],
                                                'remark'=>'GPS Not Found',
                                                );
                }                   
            }                
        }
    } 
}

if(count($vehicleDetail)==0)
{
echo'<center>
    <br>
        <table border=0 width = 100% cellspacing=2 cellpadding=0>
                <tr>
                    <td height=10 class="report_heading" align="center">
                        No Gps Not Found For Any Vehicle
                    </td>
                </tr>
            </table>
    </center>';
exit();
}

//print_r($vehicleDetail);
echo'<form method="post" target="_blank">';
	 $title='No Gps Report';
	 echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
    echo'<br>
            <table border=0 width = 100% cellspacing=2 cellpadding=0>
                <tr>
                    <td height=10 class="report_heading" align="center">
                        No GPS Report
                    </td>
                </tr>
            </table>
		<br>';
    $csv_string = "";
    $csv_string = $csv_string." No Gps Report\n";
    $csv_string = $csv_string."SNo,Person Name,Imei No,From Date,Remark\n";
echo'<center><div style="overflow: auto;height: 300px; width: 880px;" align="center">
    <table border=1 width="95%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
        <tr bgcolor="grey">
            <td class="text" align="left"><b>Serial No</b></td>
            <td class="text" align="left"><b>Person Name</b></td>
            <td class="text" align="left"><b>Imei No</b></td>
            <td class="text" align="left"><b>From Date</b></td>
            <td class="text" align="left"><b>Remark</b></td>
        </tr>';
$sno=1;
$i=0;
foreach($vehicleDetail as $key=>$vDetailValue)
{
    //echo "key=".$key." imeiNo=".$vDetailValue['imeiNo']."<br> ";
    if($vDetailValue['imeiNo']!="")
    {
    echo'<tr>
            <td class="text" align="left" width="4%"><b>'.$sno.'</b></td>        												
            <td class="text" align="left">'.$key.'</td>
            <td class="text" align="left">'.$vDetailValue['imeiNo'].'</td>      	
            <td class="text" align="left">'.$vDetailValue['noGpsOrInactiveDT'].'</td>
            <td class="text" align="left">'.$vDetailValue['remark'].'</td>						
        </tr>';
        $imeiNo=$vDetailValue['imeiNo'];
        $noGpsOrInactiveDT=$vDetailValue['noGpsOrInactiveDT'];
        $remark=$vDetailValue['remark'];
        echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][Serial No]\">";	
        echo"<input TYPE=\"hidden\" VALUE=\"$key\" NAME=\"temp[$i][Person Name]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$imeiNo\" NAME=\"temp[$i][Imei No]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$noGpsOrInactiveDT\" NAME=\"temp[$i][From Date]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$remark\" NAME=\"temp[$i][Remark]\">";
        $csv_string=$csv_string.$sno.",".$key.",".$vDetailValue['imeiNo'].",".$vDetailValue['noGpsOrInactiveDT'].",".$vDetailValue['remark']."\n";
        $i++;
        $sno++;
    }
}
echo "</table>
</div></center>";
echo'<center>
	<input TYPE="hidden" VALUE="vehicle" NAME="csv_type">
	<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">			
	<input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$i.'\');" value="Get PDF" class="noprint">
	&nbsp;
	<input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">
	&nbsp;
	</center>';
echo"</form>";
echo'<center>		
        <a href="javascript:showReportPrevPageNew();" class="back_css">
            &nbsp;<b>Back</b>
        </a>
    </center>';

?>								