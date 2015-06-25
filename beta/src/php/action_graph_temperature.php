<?php
    include_once('lib/Util.php');
    include_once('lib/VTSXMLRead_1.php');
    //include_once('lib/VTSIMEIData.php');
    include_once('lib/VTSVehicleData.php');
    include_once('lib/VTSMySQL.php');
    include_once('util_php_mysql_connectivity.php'); 
    include_once('util_session_variable.php');
    $vehicle_id = $_POST['vehicle_id_local'];   	
    $startDateTime = str_replace("/","-",$_POST['start_date']);
    $endDateTime = str_replace("/","-",$_POST['end_date']);
    $speed_status =1;
	$temperature_status =1;
?>

<?php   
	$speedData= VTSVehicleData::getTemperatureData($DbConnection, $vehicle_id, $startDateTime, $endDateTime); 
	// print_r($speedData['datetime']);
	$speed_data = $speedData['datetimeTS'];
	//echo "sizeofspeedData=".sizeof($speedData)."<br>";
	//date_default_timezone_set('Asia/Calcutta');
	$datetime_now = date("Y:m:d H:i:s", time());
	$startDateTimeTS = strtotime($startDateTime);
	$endDateTimeTS = strtotime($endDateTime);
	$datetime_nowTS = strtotime($datetime_now);

	if(sizeof($speed_data)>0)
	{
		foreach($speed_data as $datetimeTS => $speed_datetime)
		{ 
		  //echo "datetime=".$datetime."datetime1=".$speed_datetime."<br>";
			// $datetimeTS = strtotime($datetime); 
			//echo "datetime=".$datetimeTS."start_date=".$startDateTimeTS."end_date=".$endDateTimeTS."<br>";
			if(($datetimeTS >= $startDateTimeTS) && ($datetimeTS <=$endDateTimeTS))
			{
				//if($speed_datetime >= 0)
				{
					$datetmp = date('Y-m-d H:i:s',$datetimeTS);
                                        $speed[$datetmp] = $speed_datetime;
					//echo "speed_data=".$speed[$datetmp]."<br>";
				}
			}
		}
	}
?>

<?php

?>

<?php
$vname = VTSMySQL::getVehicleNameOfVehicle($DbConnection, $vehicle_id);
echo "<input type='hidden' id='vehicle_id_local' value=".$vehicle_id.">";
 
	echo'<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>';
  echo '<div style="height:5px;"></div>
        <center>
          <form method="post" name="thisform" onSubmit="javascript:return validate_form(thisform)">
          <table>
              <tr>
                <td>
                    <b><font size=2><b>Temperature Graph</font></b>
                </td>
              </tr>
            </table>
             <div style="height:17px;"></div>
            <table>
              <tr>
                <td>
                    <b><font color="blue" size2><b>Vehicle Name ('.$vname.')</b></font> : <font color="green" size=2><b>Date ('.$startDateTime.' To '.$endDateTime.')</font></b>
                </td>
              </tr>
            </table> 
            <div style="height:10px;"></div>
            <table align="center" border=0 cellpadding="3" cellspacing="2">
              <tr>                
                <td  class="text">
                  Select Date
                </td>
                </td>
                <td>
                  <input type="text" id="date1" name="start_date" value="'.$_POST['start_date'].'" size="12" maxlength="19">             
                  <a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
                    <img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                  </a>
                  &nbsp;&nbsp;&nbsp;End Date  
                  <input type="text" id="date2" name="end_date" value="'.$_POST['end_date'].'" size="12" maxlength="19">					
                  <a href=javascript:NewCal("date2","yyyymmdd",true,24)>
                    <img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                  </a>
                </td>
                <td>
                  <input type="button" onclick="javascript:action_graph_temperature(this.form);" value="Update">&nbsp;
                </td>
              </tr>
            </table>
          </center>
        </form>';
	if(sizeof($speed)>0)
	{
		// $speed_sortTS = UTIL::sort_datetimeTS($speed);
		$session_name = "SPEED_" . time();
		$_SESSION[$session_name] = $speed;
		print "<center><img src=\"graph/GraphDateLine.php?s=".$session_name."\"></center>";
		//print "<center><a href=\"graph/GraphDateLine.php?s=".$session_name."\">Show graph</a></center>";
	}
	else
	{
		print "<center><h3><font color='red'>No Data Found!</font></h3></center>";
	}
	echo'<br><center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';
?>
