<?php
echo"reportPrevPage##";
include_once("report_hierarchy_header.php");

$account_id_local1 = $_POST['account_id_local'];
$vehicle_display_option1 = $_POST['vehicle_display_option'];
$options_value1 = $_POST['options_value'];  
$options_value2=explode(",",$options_value1);			
$option_size=sizeof($options_value2);
$option_string="";  
  
    $function_string='get_'.$vehicle_display_option1.'_vehicle';   
  
 echo'<center>
    <table border=0 width = 100% cellspacing=2 cellpadding=0>
        <tr>
            <td height=10 class="report_heading" align="center">Inactive Data Report</td>
        </tr>
    </table>			
														
<form  method="post" name="thisform">							
    <br>
	<fieldset class="report_fieldset">
		<legend>Select Vehicle</legend>	
					
			<table border=0  cellspacing=0 cellpadding=0  width="100%">
				<tr>
					<td align="center">							
						<div style="overflow: auto;height: 150px; width: 650px;" align="center">
							<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';						
                
								 echo'<tr><td height="10px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
                 $function_string($account_id_local1,$options_value1);               														
								echo'
							</table>
						</div>
					</td>
				</tr>
			</table>
      </fieldset> <br>
      </form>';	
			
echo'<table border=0 cellspacing=0 cellpadding=3 align="center">	
        <tr>
            <td  class="text"><b>Select Duration : </b></td>
            <td>
                <table>
                    <tr>
                        <td  class="text">Duration</td>
                        <td class="text">
                        :
                        </td>
                        <td>
                            <select id="duration">
                                <option value="30">30 Min</opiton>
                                <option value="60">1 hr</opiton>
                                <option value="120">2 hr</opiton>
                                <option value="240">4 hr</opiton>
                            </select>																	
                        </td>
                    </tr>
                </table>
            <td>
        </tr>										
    </table>
			
    <br>  
    <table border=0 cellspacing=0 cellpadding=3 align="center">	
        <tr>
            <td align="center" colspan=2>
                <input type="button" onclick="javascript:action_report_inactive_data(this.form);" value="Enter">
                    &nbsp;
                <input type="reset" value="Clear">
            </td>
        </tr>
    </table>		
</form>  </center>     
<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>  ';
?>						
					 
