<?php
    global $report_station_halt_option;
    $report_station_halt_option="1";
    include_once("report_hierarchy_header.php");
    include_once("user_type_setting.php");
    $account_id_local1 = $_POST['account_id_local'];
    //echo 	"AC=".$account_id_local1
    $vehicle_display_option1 = $_POST['vehicle_display_option'];	
    $options_value1 = $_POST['options_value'];    
    $options_value2=explode(",",$options_value1);			
    $option_size=sizeof($options_value2);
    $option_string="";
    $function_string='get_'.$vehicle_display_option1.'_vehicle';	
    ////echo "function _name=".$function_string."<br>"; 
    //echo "function_name=".$function_string."<br>";
  
  /*$query_geo_exist = "SELECT geo_id,geo_name FROM geofence WHERE geo_id IN(SELECT geo_id FROM geo_assignment WHERE status=1) AND ".
                "user_account_id='$account_id' AND status=1"; */
                
  /*$query_geo_exist = "SELECT geo_id,geo_name FROM geofence WHERE ".
                "user_account_id='$account_id' AND status=1";                    
    	    
  $res_geo = mysql_query($query_geo_exist,$DbConnection);
  $num_geo = mysql_num_rows($res_geo); */                  
  
  //echo "<br>query_geo_exist:".$query_geo_exist." ,num=".$num_geo; 
	
  echo'
  <center>
  	<table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="report_heading" align="center">Supply Timing Report</td>
  		</tr>
  	</table>';
						
// ************AUTOMATIC
echo '<!--<div style="height:5px;" align="center"><input type="radio" name="mode" id="mode" value="1" Onclick="javascript:report_show_station_mode(this.value);">&nbsp;Upload Input File &nbsp;&nbsp;<input type="radio" name="mode" id="mode" value="2" Onclick="javascript:report_show_station_mode(this.value);">&nbsp;Select Manually</div><br><br>-->';
//echo $tmp_ac;

/*if ($_SERVER["HTTP_X_FORWARDED_FOR"] =="172.26.48.164")
{
//echo "TEST";
echo '<div style="height:5px;display:none;" id="automatic">

    <form id="file_upload_form" name="file_upload_form" target="_blank" method="post" enctype="multipart/form-data" action="src/php/action_report_station_upload_test.php">

    <input type="hidden" name="local_account_ids" id="local_account_ids" value="'.$account_id_local1.'"/>
    
    <table border="0" class="manage_interface">
    	<tr>
        <td>Upload Station Data </td><td>:</td>
        <td><input name="file" id="file" size="27" type="file" />(&nbsp;.csv format only)</td>
      </tr>
      <tr>
        <td colspan="3" align="center">
          <input type="hidden" name="action_type"/>                   
          <input type="button" value="Show Report" id="enter_button" onclick="javascript:return action_report_station_upload(\'add\')"/>&nbsp;<input type="reset"" value="Clear" />         
          <iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
        </td>
      </tr>
    </table>    
  </form>    

</div>'; 
}
else
{ */
echo '<!--<div style="height:5px;display:none;" id="automatic">

    <form id="file_upload_form" name="file_upload_form" target="_blank" method="post" enctype="multipart/form-data" action="src/php/action_report_station_upload.php">

    <input type="hidden" name="local_account_ids" id="local_account_ids" value="'.$account_id_local1.'"/>
    
    <table border="0" class="manage_interface">
    	<tr>
        <td>Upload Station Data </td><td>:</td>
        <td><input name="file" id="file" size="27" type="file" />(&nbsp;.csv format only)</td>
      </tr>
      <tr>
        <td colspan="3" align="center">
          <input type="hidden" name="action_type"/>                   
          <input type="button" value="Show Report" id="enter_button" onclick="javascript:return action_report_station_upload(\'add\')"/>&nbsp;<input type="reset"" value="Clear" />         
          <iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
        </td>
      </tr>
    </table>    
  </form>    

</div>-->';  
//}
echo '<!--<div style="height:5px;display:none;" id="manual">-->
<form  method="post" name="thisform"><br>
	<fieldset class="report_fieldset">';
	if($report_type=='Person')
	{
		echo'<legend>Select Person</legend>';
	}
	else
	{
		echo'<legend>Select Vehicle</legend>';
	}	
	echo'<table border=0  cellspacing=0 cellpadding=0  width="100%">
			<tr>
				<td align="center">							
					<div style="overflow: auto;height: 150px; width: 650px;" align="center">
						<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';
						echo'<tr>
								<td height="5px" align="left" colspan="3" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' id=\'all\' value=\'1\' onClick=\'javascript:common_select_all(this.form,"all","station_vehicle_id[]");\'>
									Select All
								</td>
							</tr>';                 
								$function_string($account_id_local1,$options_value1);
					echo'</table>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>'; 
	
	echo'<fieldset class="report_fieldset">';
	$Query="SELECT customer_no,station_name,station_coord,distance_variable,google_location FROM station WHERE user_account_id=$account_id AND status=1";
	//echo "Query=".$Query."<br>";
	$Result=mysql_query($Query,$DbConnection);
//echo "result=".$Result."<br>";
		echo'<legend>Select Customer</legend>';
	
	echo'<table border=0  cellspacing=0 cellpadding=0  width="100%">
			<tr>
				<td align="center">							
					<div style="overflow: auto;height: 150px; width: 650px;" align="center">
						<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';
						echo'<tr>
								<td height="5px" align="left" colspan="3" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all_1\' id=\'all_1\' value=\'1\' onClick=\'javascript:common_select_all(this.form,"all_1","stationids[]");\'>
									Select All
								</td>
							</tr>'; 
							$cnt=0;
							while($row=mysql_fetch_object($Result))
							{							
								$cnt++;          
								if($cnt==1)
								{
								echo'<tr>';
								} 
								echo'<td align="left">
										<!--<INPUT TYPE="checkbox"  name="stationids[]" VALUE="'.$row->customer_no.':'.$row->station_name.':'.str_replace(', ',',',$row->station_coord).':'.$row->google_location.':'.$row->distance_variable.'">-->
									<INPUT TYPE="checkbox"  name="stationids[]" VALUE="'.$row->customer_no.'">
                                                                     </td>
									<td class=\'text\'>';								
										echo $row->customer_no;							
								echo'</td>';         
								if($cnt==3)
								{
									echo'</tr><tr>';
									$cnt=0;
								}
							}
					echo'</table>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>';
echo'<fieldset class="report_fieldset">
		<legend>
			Select display Option
		</legend>';	
		// *************MANUAL
		//date_default_timezone_set('Asia/Calcutta');
		//$StartDate=date("Y/m/d 00:00:00");	
		//$EndDate=date("Y/m/d H:i:s");	
                $StartDate=date("Y/m/d");	
		$EndDate=date("Y/m/d");	
	echo'<table border=0 cellspacing=0 cellpadding=3 align="center">	
			<tr>
				<td class="text">
					<b>Select Duration : </b>
				</td>
				<td>
					<table>
						<tr>
							<td  class="text">	</td>
							<td class="text">
								Start Date
								<input type="text" id="date1" name="StartDate" value="'.$StartDate.'" size="10" maxlength="19">
								<a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
									<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
								</a>
								&nbsp;&nbsp;&nbsp;End Date
								<input type="text" id="date2" name="EndDate" value="'.$EndDate.'" size="10" maxlength="19">
								<a href=javascript:NewCal("date2","yyyymmdd",false,24)>
									<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
								</a>
							</td>
							<input type="hidden" name="rep_uid" value="'.$uid.'">
						</tr>
					</table>
				<td>
			</tr>										
		</table>
	</fieldset>';
echo'<br>
		<table border=0  cellspacing=0 cellpadding=0  width="100%">
			<tr>
				<td align="center">
					<table>
						<tr>
							<td>			
								<input type="button" onclick="javascript:action_report_station_halt_1(this.form)" value="Enter">&nbsp;<input type="reset" value="Clear">
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>
</form>
<!--</div>-->        

<!--<br><font color=red><strong>PAGE IS UNDER TESTING</strong></font>-->

</center>                             


';


