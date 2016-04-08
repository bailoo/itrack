<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	$size_suid=sizeof($suid);

  echo'	
  <table border=0 width = 100% cellspacing=2 cellpadding=0>
    <tr>
    <td height=10 class="text" align="center"><strong>Monthly Distance Report</strong></td>
    </tr>
  </table>
		<form  method="post" name="thisform">
		
		<br>	
		
		<table border=1 rules=rows bordercolor="#e5ecf5" cellspacing=0 cellpadding=3 align="center">		
			<tr>
				<td class="text">
					<b>
						&nbsp;Select
					</b>
				</td>
				<td class="text">
					<b>
						&nbsp;Vehicle
					</b>
				</td>
			</tr>

			<tr>
				<td class="text" align="center">
					<input type="checkbox" name="all" value="1" onClick="javascript:All(this.form)">
				</td>
				<td class="text">
					Select All
				</td>
			</tr>
		</table>
		
		<br>
		<table border=0  cellspacing=0 cellpadding=0  width="100%">
			<tr>
				<td align="center">							
					<div style="overflow: auto;height: 150px; width: 650px;" align="center">
						<table border=1 rules=rows bordercolor="lightblue" cellspacing=0 cellpadding=0 align="center" width="100%">	';						
						
								include('module_manage_show_vehicle.php');
												
								echo'<input type="hidden" name="vehicleid[]">
						</table>
					</div>
				</td>
			</tr>
		</table>
		<br>';							
echo'
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
				echo'		<option value="'.$currentyear.'" selected>';
				echo			$currentyear;
				echo		'</option>';

							for($i=date(Y);$i>=2007;$i--)
							{
								echo'<option value="'.$i.'">';
										echo $i;
									echo'</option>';
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
					<input type="button" onclick="javascript:report_monthly_distance_res(this.form);" value="Enter">
						&nbsp;
					<input type="reset" value="Clear">
				</td>
			</tr>
		</table>
	</form>
';

?>			