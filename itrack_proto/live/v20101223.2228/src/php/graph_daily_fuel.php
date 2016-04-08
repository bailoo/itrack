<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	$size_suid=sizeof($suid);
  echo'
	<table border=0 width = 100% cellspacing=2 cellpadding=0>
		<tr>
			<td height=10 class="text" align="center"><strong>Daily Fuel Graph</strong></td>
		</tr>
	</table>	
  <br>
	<table border=0 width = 100% cellpadding="3" cellspacing="2">
		<tr>							
			<td class="text" align="center">
				<font color="red">
					In vehicle name combo box
				</font>
				<font color="blue">
					&#42 
				</font>									
				<font color="red">
					indicates today\'s running vehicle
				</font>
			</td>
		</tr>
	</table>
	
	<br>
	
	<form method="post" name="thisform" onSubmit="javascript:return validate_form(thisform)"
		action="DailyFuelGraphAction.php">
	<table border=0 width = 87% cellpadding="3" cellspacing="2">
		<tr>
			<td width=22%></td>
			<td class="text" width="17%">
				Vehicle Name
			</td>
			<td>';							
					
			include('module_report_show_vehicle_combo.php');
										
		echo'	</td>
						</tr>
					</table>

	<br>
	<table border=0 width = 87% cellpadding="3" cellspacing="2">
		<tr>
			<td width=22%></td>
			<td  class="text">
				Select Date
			</td>
			<td>';

				date_default_timezone_set('Asia/Calcutta');
				$Date=date("Y/m/d 00:00:00");
				$Date1=explode(" ",$Date);
				$Date2=explode("/", $Date1[0]);
				$currentyear=$Date2[0];
				$currentmonth=$Date2[1];
				$currentday=$Date2[2];
				//echo $Date2;
				if($currentday==01)
				$currentday=1;
				if($currentday==02)
				$currentday=2;
				if($currentday==03)
				$currentday=3;
				if($currentday==04)
				$currentday=4;
				if($currentday==05)
				$currentday=5;
				if($currentday==06)
				$currentday=6;
				if($currentday==07)
				$currentday=7;
				if($currentday==08)
				$currentday=8;
				if($currentday==09)
				$currentday=9;

      echo'	<td  class="text">
      	Day&nbsp;
      		<select name="day" id="day">';
      echo'	<option value="'.$currentday.'" selected>';
      echo $currentday;
      echo'	</option>';
				
			for($i=1;$i<=31;$i++)
			{	
			 echo'<option value="'.$i.'">';
					echo $i;
			 echo'</option>';
									}
			echo'</select>
			</td>';
				//select day-month-year 
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
    
    echo'				
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
					<select name="year" id="year">
	     <option value="'.$currentyear.'" selected>';
				echo	$currentyear;
				echo	'</option>';
				for($i=date(Y);$i>=2007;$i--)
				{											
					echo'<option value="'.$i.'">';
							echo $i;
					echo'</option>';
				}
				echo'</select>
				</td>	
			</tr>
		</table>
					
		<br>
		<table border=0 width = 100% cellpadding="3" cellspacing="2">
			
				<tr>
					<td width=25%></td>
					<td width=20%></td>
					<td align="left">
						<input type="button" onclick="javascript:action_graph_daily_fuel(tihs.form);" value="Enter">&nbsp;<input type="reset" value="Clear">
					</td>
				</tr>
			</table>
		</form>
';
?>