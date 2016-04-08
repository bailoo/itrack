<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('coreDb.php');
//echo "vehicleSerial=".$vehicleserial."<br>";
$vehicleserial_1=explode(":",$vehicleserial);
$consignment_type_str1=explode(",",$consignment_type_str);
//echo "consignment1=".$consignment_type_str1[0]." consignment2=".$consignment_type_str1[1]."<br>";

if($consignment_type_str1[0]=="live" && $consignment_type_str1[1]=="expired")
{ 
    for($i=0;$i<sizeof($vehicleserial_1);$i++)
    {
		$cDataArr=getConsignmentInfoParamDetail($vehicleserial_1[$i],$start_date,1,$DbConnection);
		foreach($cDataArr as $cValue)
		{       
			$docket_no[]= $cValue['docket_no'];
			$device_imei_no[]= $cValue['device_imei_no'];
			$vehicle_name[]= $cValue['vehicle_name'];
			$from_place[]= $cValue['from_place'];
			$to_place[]= $cValue['to_place'];
			$consignee_name[]= $cValue['consignee_name'];
			$start_date_1[]= $cValue['start_date'];
			$end_date_1[]= $cValue['end_date'];
		}
    } 
    //echo "count_arr111=<br>";
}
else if($consignment_type_str1[0]=="live")
{
    for($i=0;$i<sizeof($vehicleserial_1);$i++)
    {
		$cDataSDEDArr=getConsignmentInfoParamSDEDDetail($vehicleserial_1[$i],$start_date,$end_date,1,$DbConnection);
		foreach($cDataSDEDArr as $cSDEDValue)
		{       
			$docket_no[]= $cSDEDValue['docket_no'];
			$device_imei_no[]= $cSDEDValue['device_imei_no'];
			$vehicle_name[]= $cSDEDValue['vehicle_name'];
			$from_place[]= $cSDEDValue['from_place'];
			$to_place[]= $cSDEDValue['to_place'];
			$consignee_name[]= $cSDEDValue['consignee_name'];
			$start_date_1[]= $cSDEDValue['start_date'];
			$end_date_1[]= $cSDEDValue['end_date'];
		}    
	}		
}
else   ///// for expired docket no ///
{    
    for($i=0;$i<sizeof($vehicleserial_1);$i++)
    {
       $cDataSDEDArr=getConsignmentInfoParamSDEDDetail($vehicleserial_1[$i],$start_date,$end_date,1,$DbConnection);
		foreach($cDataSDEDArr as $cSDEDValue)
		{       
			$docket_no[]= $cSDEDValue['docket_no'];
			$device_imei_no[]= $cSDEDValue['device_imei_no'];
			$vehicle_name[]= $cSDEDValue['vehicle_name'];
			$from_place[]= $cSDEDValue['from_place'];
			$to_place[]= $cSDEDValue['to_place'];
			$consignee_name[]= $cSDEDValue['consignee_name'];
			$start_date_1[]= $cSDEDValue['start_date'];
			$end_date_1[]= $cSDEDValue['end_date'];
		} 
    } 
}
$count_arr=count($docket_no);
//echo "count_arr=".$count_arr."<br>";

if($count_arr==0)
{
    echo "<br><br>
        <center>
            <font color='red'><b>No Data Found </b>
           </center>";
}
else
{
         echo "<br>
        <center>
            <font color='black'><b>Consignment Report </b>
           </center>
           <br>";
echo'<form method = "post" target="_blank">
		<div style="height:450px;overflow:auto">
         <table width="100%">
            <tr>
                <td>
         <table align="center" border=1 rules=all align="center" cellspacing=0 cellpadding=3>
            <tr>
                <td><b>Serial</b></td>
                <td><b>Docket Number<b></td>
                <td><b>Vehicle Name</b></td>
                <td><b>Consignee Name</b></td>
                <td><b>From Place</b></td>
                <td><b>To Place</b></td>
                <td> <b>Start Date</b></td>
                <td> <b>End Date</b></td>
               <!--<td><b>Cancel</b></td>-->
              </tr>';
     $k=1;
     $title="Consignment Report FROM ".$start_date." To&nbsp;".$end_date; 
        $csv_string = "";
      	$csv_string = $csv_string.$title."\n";
      	$csv_string = $csv_string."SNo,Docket Number,Vehicle Name,Consignee Name,From Place,To Place,Start Date, End Date\n";
        echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[]\">";
     for($i=0;$i<sizeof($docket_no);$i++)
     {  
         $serial=$k++;
    echo"<tr>
                <td class='text'>&nbsp;".($i+1)."</td>
                <td class='text'>&nbsp;".$docket_no[$i]."</td>
                <td class='text'>&nbsp;".$vehicle_name[$i]."</td>
                <td class='text'>&nbsp;".str_replace(",","",$consignee_name[$i])."</td>
                <td class='text'>&nbsp;".str_replace(",","",$from_place[$i])."</td>
                <td class='text'>&nbsp;".str_replace(",","",$to_place[$i])."</td>
                <td class='text'>&nbsp;".$start_date_1[$i]."</td>
                <td class='text'>&nbsp;".$end_date_1[$i]."</td>
               <!--<td class='text'><input type='button' value='Cancel'></td>-->
          </tr>";
         echo"<input TYPE=\"hidden\" VALUE=\"$serial\" NAME=\"temp[$i][SNo]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$docket_no[$i]\" NAME=\"temp[$i][Docket Number]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$vehicle_name[$i]\" NAME=\"temp[$i][Vehicle Name]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$consignee_name[$i]\" NAME=\"temp[$i][Consignee Name]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$from_place[$i]\" NAME=\"temp[$i][From Place]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$to_place[$i]\" NAME=\"temp[$i][To Place]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$start_date_1[$i]\" NAME=\"temp[$i][Start Date]\">";
        echo"<input TYPE=\"hidden\" VALUE=\"$end_date_1[$i]\" NAME=\"temp[$i][End Date]\">";        
        $csv_string = $csv_string.$serial.','.$docket_no[$i].','.$vehicle_name[$i].','.str_replace(",","",$consignee_name[$i]).','.str_replace(",","",$from_place[$i]).','.str_replace(",","",$to_place[$i]).','.$start_date_1[$i].','.$end_date_1[$i]."\n"; 
				
     }
     echo"</table>
        </td>
    </tr>
    </table>
	</div>";
      $scnt_1=sizeof($docket_no);
echo'<input TYPE="hidden" VALUE="Consignment_Report" NAME="csv_type">';
  echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
 echo'<br><center><input type="button" onclick="javascript:report_csv(\'src/php/report_getpdf_type3.php?size='.$scnt_1.'\');" value="Get PDF" class="noprint">&nbsp;
      <input type="button" onclick="javascript:report_csv(\'src/php/report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
      </center>
      </form>';
}
echo'<center>
		<a href="javascript:showReportPrevPage(\'report_consignment_info.htm\',\''.$selected_account_id.'\',\''.$selected_options_value.'\',\''.$s_vehicle_display_option.'\');" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';


?>