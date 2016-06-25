<?php
	echo"reportPrevPage##";
	include_once("report_hierarchy_header.php");
	
	$account_id_local1 = $_POST['account_id_local'];
	$vehicle_display_option1 = $_POST['vehicle_display_option'];	
	$options_value1 = $_POST['options_value'];

	$options_value2=explode(",",$options_value1);			
	$option_size=sizeof($options_value2);
	$option_string="";  
  	
  $function_string='get_'.$vehicle_display_option1.'_vehicle_radio';
  //echo $function_string; 

  echo'	
    <center>
    
    	<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">Monthly Distance Report</td>
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
						<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">	';					

 						  //echo'<tr><td height="5px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
              $function_string($account_id_local1,$options_value1);							

						echo'</table>
					</div>
				</td>
			</tr>
		</table>
		</fieldset> <br>';							
  
  
  echo'<table align="center">
  <tr>
  <td>
  <select name="day_opt" id="day_opt" onchange="javascript:day_option(this.form)">
    <option value="1" selected>All Days</option>
    <option value="2">Selected Days</option>
  </select>
  </td>
  </tr>
  </table>';	
  
  
  echo'<br>
	<div align="center">
  <fieldset class="text" style="width:10%;font-size: x-small">
		<legend>Select Days</legend>	          
    <div align="center" style="height:75px;overflow:auto;">
    <table align="center" id="alldays">';
    
    for($i=1;$i<=31;$i++)
    {
      echo '<tr><td style="font-size: x-small"><input type="checkbox" name="days[]" value="'.$i.'" disabled="true">&nbsp;day'.$i.'</td></tr>';
    }
    
    echo'</table>
    </div></fieldset></div>';	          
  						
  echo'<br><br>
			<table border=0 cellspacing=0 cellpadding=3 align="center">	
				<tr>';
					date_default_timezone_set('Asia/Calcutta');
					$Date=date("Y/m/d 00:00:00");
					$Date1=explode(" ",$Date);
					$Date2=explode("/", $Date1[0]);
					$currentyear=$Date2[0];
					$currentmonth=$Date2[1];
					$currentday=$Date2[2];
					if ($currentmonth==1)
					$month="Jan";
					if ($currentmonth==2)
					$month="Feb";
					if ($currentmonth==3)
					$month="Mar";
					if ($currentmonth==4)
					$month="Apr";
					if ($currentmonth==5)
					$month="May";
					if ($currentmonth==6)
					$month="Jun";
					if ($currentmonth==7)
					$month="Jul";
					if ($currentmonth==8)
					$month="Aug";
					if ($currentmonth==9)
					$month="Sep";
					if ($currentmonth==10)
					$month="Oct";
					if ($currentmonth==11)
					$month="Nov";
					if ($currentmonth==12)
					$month="Dec";
					
				echo'<td  class="text">
						<b>
							Select Duration
						</b>
					</td>								
					
					<td  class="text">
						&nbsp;Month&nbsp;
							<select name="month" id="month">
								<option value="'.$currentmonth.'" selected>'.$month.'</option>
								<option value="01">Jan</option>
								<option value="02">Feb</option>
								<option value="03">Mar</option>
								<option value="04">Apr</option>
								<option value="05">May</option>
								<option value="06">Jun</option>
								<option value="07">Jul</option>
								<option value="08">Aug</option>
								<option value="09">Sep</option>
								<option value="10">Oct</option>
								<option value="11">Nov</option>
								<option value="12">Dec</option>
							</select>
					</td>
					<td  class="text">
						&nbsp;Year&nbsp;
							<select name="year" id="year">';									
					echo'		<option value="';echo $currentyear.'" selected>';
					echo			$currentyear;
					echo		'</option>';

								for($i=date(Y);$i>=2007;$i--)
								{
									echo'<option value="';echo $i.'">';
											echo $i;echo
										'</option>';
								}
					echo'
							</select>
					</td>
				</tr>
			</table>

		<br>
		<table border=0 cellspacing=0 cellpadding=3 align="center">						
										
			<tr>
				<td align="center" colspan=2>
					<input type="button" class="btn btn-default btn-sm" onclick="javascript:action_report_monthly_distance(this.form);" value="Enter">
						&nbsp;
					<input type="reset" class="btn btn-default btn-sm" value="Clear">
				</td>
			</tr>
		</table>
	</form>
	
	<div align="center" id="loading_msg" style="display:none;"><br><font color="green">loading...</font></div>
	</center>
';

?>			