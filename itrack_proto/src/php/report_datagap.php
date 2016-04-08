<?php
	echo"reportPrevPage##";
	include_once("report_hierarchy_header.php");
	include("user_type_setting.php");
        	
	$account_id_local1 = $_POST['account_id_local'];	
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];
  
	$options_value2=explode(",",$options_value1);			
	$option_size=sizeof($options_value2);
	$option_string="";  
  
	$function_string='get_'.$vehicle_display_option1.'_vehicle'; 		
	
 /* echo'<html>  
  <head>';         
        include_once('main_google_key.php'); 			
	include_once('map_js_css.php');
	include_once('common_js_css.php');
        include_once('util_calculate_distance_js.php');				
        //include('main_frame_part1.php');           
  echo'</head>
  
  <body>';*/
  		
  echo'<center>
    <table border=0 width = 100% cellspacing=2 cellpadding=0>
  		<tr>
  			<td height=10 class="report_heading" align="center">Data Gap Report</td>
  		</tr>
  	</table>    
    
  <form  method="post" name="thisform">
		
	<br>			
	<fieldset class="report_fieldset">
		<legend>Select '.$report_type.'</legend>		
		
		<table border=0  cellspacing=0 cellpadding=0  width="100%">
			<tr>
				<td align="center">							
					<div style="overflow: auto;height: 150px; width: 650px;" align="center">
						<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';								
							
						  echo'<tr><td height="5px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
              $function_string($account_id_local1,$options_value1);

						echo'</table>
					</div>
				</td>
			</tr>
		</table>
		</fieldset>
		<br>';							

	 echo'
	<input type="hidden" id="acc" value="'.$account_id.'"/>
   <fieldset class="report_fieldset">
		<legend>Select display Option</legend>';		
		
		echo'<br><center><SPAN STYLE="font-size: xx-small">Select NO GPS Interval </SPAN>
    <select id="no_gps_interval" name="no_gps_interval" onchange="javascript:add_options_nodata(this.value)">';
   // echo '<option value="5" selected>5 min</option>';		
   // echo '<option value="10">10 min</option>';
		
    echo '<option value="select">Select</option>';
    
    if($account_id=="1")
    {
    	echo '<option value="1">1 min</option>';
	echo '<option value="5">5 min</option>';
    }
    echo '<option value="10">10 min</option>';
    
    echo '<option value="15" selected>15 min</option>';
    
    echo '<option value="30">30 min</option>';
		
    echo '<option value="60">1 hr</option>';

    echo '<option value="120">2 hr</option>';

    echo '<option value="180">3 hr</option>';																					
		
	echo '<option value="240">4 hr</option>';
		
	echo '<option value="300">5 hr</option>';
		
	echo '<option value="360">6 hr</option>';
	
	echo '<option value="420">7 hr</option>';
									
	echo '<option value="480">8 hr</option>';
		
	echo '<option value="540">9 hr</option>';

	echo '<option value="600">10 hr</option>';

	echo '<option value="660">11 hr</option>';

	echo '<option value="720">12 hr</option>';							

	echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"></SPAN>
    
    &nbsp;&nbsp;<SPAN STYLE="font-size: xx-small">Select NO Data Interval </SPAN>
    <select id="no_data_interval" name="no_data_interval"> 
    <option value="select">Select</option>';
    
if($account_id=="1")
{
	echo '    <option value="1" selected>1 min</option>';
	echo ' <option value="5" selected>5 min</option>';
}
echo ' <option value="10" selected>10 min</option>
    </select>
    </center><br>';												
				
		//date_default_timezone_set('Asia/Calcutta');
		$start_date=date("Y/m/d 00:00:00");	
		$end_date=date("Y/m/d H:i:s");	

    //$start_date = "2011/04/20 00:00:00";	
		//$end_date= "2011/04/20 23:00:00";			
		
		echo'
		<br><table border=0 cellspacing=0 cellpadding=3 align="center">	
			<tr>
				<td  class="text"><b>Select Duration : </b></td>
				<td>
					<table>
						<tr>
							<td  class="text">	</td>
							<td class="text">
								Start Date
														
						<input type="text" id="date1" name="start_date" value="'.$start_date.'" size="10" maxlength="19">
				
									<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
										<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>
										&nbsp;&nbsp;&nbsp;End Date

						<input type="text" id="date2" name="end_date" value="'.$end_date.'" size="10" maxlength="19">
				
									<a href=javascript:NewCal("date2","yyyymmdd",true,24)>
										<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
									</a>													
							</TD>
																						
							</td>
						</tr>
					</table>
				<td>
			</tr>										
		</table>							
	 </fieldset>			
		<br>
		<table border=0 cellspacing=0 cellpadding=3 align="center">						
										
			<tr>
				<td align="center" colspan=2>
					<input type="button" onclick="javascript:action_report_datagap(this.form);" value="Enter">
						&nbsp;
					<input type="reset" value="Clear">
				</td>
			</tr>
		</table>
		
  <div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>
		
	</form>	
  </center>
  <!--</body>
  </html>-->
  ';

?>
