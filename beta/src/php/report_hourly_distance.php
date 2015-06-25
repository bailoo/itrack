<?php
    include_once("report_hierarchy_header.php");

    $account_id_local1 = $_POST['account_id_local'];	
    $vehicle_display_option1 = $_POST['vehicle_display_option'];	
    $options_value1 = $_POST['options_value'];

    $account_id_local1 = $_POST['account_id_local'];	
    echo "<input type='hidden' id='selected_account_id' value=".$account_id_local1.">";
    $vehicle_display_option1 = $_POST['vehicle_display_option'];

    echo "<input type='hidden' id='s_vehicle_display_option' value=".$vehicle_display_option1.">";
    $options_value1 = $_POST['options_value'];
    echo "<input type='hidden' id='selected_options_value' value='".$options_value1."'>";  
  
    $options_value2=explode(",",$options_value1);			
    $option_size=sizeof($options_value2);
    $option_string="";  
  
    $function_string='get_'.$vehicle_display_option1.'_vehicle';   
  
 echo'<center>

<table border=0 width = 100% cellspacing=2 cellpadding=0>
    <tr>
        <td height=10 class="report_heading" align="center">
            Hourly Report
        </td>
    </tr>
</table>			
<br>
<form  method="post" name="hourlyDistance">							
    <br>
	<input type="hidden" id="deviceStr" name="deviceStr">
	<fieldset class="report_fieldset">
            <legend>Select Vehicle</legend>	
                <table border=0  cellspacing=0 cellpadding=0  width="100%">
                    <tr>
                        <td align="center">							
                            <div style="overflow: auto;height: 150px; width: 650px;" align="center">
                                <table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">						
                                    <tr>
                                        <td height="10px" align="center" colspan="6" class=\'text\'>
                                            &nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>
                                            &nbsp;&nbsp;Select All
                                        </td>
                                    </tr>';                 
                                        $function_string($account_id_local1,$options_value1);							
                            echo'</table>
                            </div>
                        </td>
                    </tr>
                </table>
      </fieldset>
      <br>
      <fieldset class="report_fieldset">
        <legend>Select display Option</legend>
        <br><br>
        <center>';
        $dateCurrent=date("Y/m/d");
		
        echo'<table border=0 cellspacing=0 cellpadding=3 align="center">
				<!--<tr>
                    <td  class="text">
                        <b>Report Type</b>
                    </td>
                    <td>                       
						 <select id="report_type" onclick="javascript:setHourlyReportType(this.value);">
						  <option value="current_date">Current Date Report</option>
						  <option value="previous_date">Previous Date Report</option>						 
						</select> 
                    <td>
                </tr>-->	
                <tr>
                    <td  class="text">
                        <b>Select Duration : </b>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td  class="text"></td>
                                <td class="text">
                                    Start Date
                                    <input type="text" id="date1" name="start_date" value="'.$dateCurrent.'" size="10" maxlength="19">
                                    <a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
                                        <img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
                                    </a>
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
                    <input type="button" onclick="javascript:action_report_hourly_distance(this.form);" value="Enter">
                    &nbsp;
                    <input type="reset" value="Clear">
                </td>
            </tr>
        </table>		
    </form>       
    <div align="center" id="loading_msg" style="display:none;">
        <br>
        <font color="green">loading...</font>
    </div>		
    <center>';
        
/*echo'<div id="reportBlackout"> </div>
    <div id="reportDivPopup">
	<table border="0" class="module_left_menu" width="100%">
          <tr>
            <td class="manage_interfarce" align="right" colspan="7">
                <a href="#" onclick="javascript:closeReportBlackout()" class="hs3">
                    <img src="images/close.png" type="image" style="border-style:none;">
                </a>&nbsp;&nbsp;
            </td> 													
    	  </tr>
	</table>
	<div id="reportResultBlock" style="display:none;"></div>        
    </div>
	';*/
?>						
					 
