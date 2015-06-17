<?php
  include_once("trip_hierarchy_header.php");

  echo'
	<style style type="text/css">
	<!--
	div.scroll {
	height: 200px;
	width: 650px;
	overflow: auto;
	border: 1px solid #666;
	padding: 8px;
	-->
	</style> ';
	

$case = $_GET['case'];
echo' 
		<center>
																				
			<form  method="post" name="thisform">
			<br>
    	<fieldset class="report_fieldset">
    		<legend>Select Vehicle</legend>	
			
			<input type="hidden" name="case" value="'.$case.'">						

			<!--<table border=1 rules=all bordercolor="#e5ecf5" cellspacing=0 cellpadding=0 align="center">		
				<tr>
					<td class="text"><b>&nbsp;Select&nbsp;Vehicle</b></td>					
				</tr>

				<tr>
					<td class="text" align="center">
						<input type="checkbox" name="all" value="1" onClick="javascript:All(this.form)">&nbsp;Select All
					</td>
				
				</tr>
			</table>-->

			<br>

			<table border=0  align="center" cellspacing=0 cellpadding=0  width="100%">
				<tr>
					<td align="center">							
						<div style="overflow: auto;height: 150px; width: 650px;" align="center">
							<table border=1 rules=rows bordercolor="lightblue" cellspacing=0 cellpadding=0 align="center" width="100%">	';						
								  echo'<tr><td height="5px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';
                	 $category1=5;
               print_group_vehicle($root, $common_id_local, $category1);	
                  //include('module_manage_show_vehicle.php');
									//echo'<input type="hidden" name="vehicleid[]">';
							echo'</table>
						</div>
					</td>
				</tr>
			</table>
			
   </fieldset>							
		<br>';	

	//date_default_timezone_set('Asia/Calcutta');
	$StartDate=date("Y/m/d 00:00:00");	
	$EndDate=date("Y/m/d H:i:s");	
		
   echo'<fieldset class="report_fieldset">
		<legend>Select display Option</legend>';		
    						
    //date_default_timezone_set('Asia/Calcutta');
    $date=date("Y/m/d");	
    //$EndDate=date("Y/m/d H:i:s");	
    
    echo'
    <table border=0 cellspacing=0 cellpadding=3 align="center">	
    	<tr>
    		<td  class="text"><b>Select Date : </b></td>
    		<td>
    			<table>
    				<tr>
    					<td  class="text">	</td>
    					<td class="text">
    				      <input type="text" id="date1" name="date" value="'.$date.'" size="10" maxlength="19">
    		
    							<!--<a href=javascript:NewCal_SD("date1","yyyymmdd",true,24)>
    								<img src="Images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
    							</a>-->											
    					</TD>
    																
    					</td>
    				</tr>
    			</table>
    		<td>
    	</tr>									
      </table>						
		</fieldset>
		<br>				
    	
		<table border=0 align="center">						
			<tr>
				<td class="text" align="center"><input type="button" onclick="javascript:action_report_trip_summary(this.form);" value="Show Trip Report"></td>
			</tr>
		</table>			
	</form> 
	
  <div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>	
	</center>';		
				
?>			
