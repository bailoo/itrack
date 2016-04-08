<?php 
	include_once('Hierarchy.php');		
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];  	
	$filename1=$_POST['filename']; 
	$title1=$_POST['title'];
  //echo "filename1=".$filename1." title=".$title1; 
  echo'<form name="report1">
			<div class="report_div_height"></div>
			<center>
					<div class="report_title">
						<b>'.$title1.'</b>
					</div>
			</center>';  
			include_once('tree_hierarchy_information.php');	
			include_once('report_radio_account.php');				 
			$StartDate=date("Y/m/d");	
			$EndDate=date("Y/m/d");	
			echo'<table border=0 cellspacing=0 cellpadding=3 align="center">	
					<tr>';
						date_default_timezone_set('Asia/Calcutta');
						$Date=date("Y/m/d 00:00:00");
						$Date1=explode(" ",$Date);
						$Date2=explode("/", $Date1[0]);
						$currentyear=$Date2[0];
						$currentmonth=$Date2[1];
						$currentday=$Date2[2];
						if ($currentmonth==12)
						$month="Dec";
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
            
            $sel_month_id1 = "02";
            $sel_month_name1 = "Feb";	
            				
					echo'<td  class="text">
							Previous Month
						</td>
						<td>
							&nbsp;:&nbsp;
						</td>
						<td>
								<select name="prev_month" id="prev_month">
									<option value="'.$sel_month_id1.'" selected>'.$sel_month_name1.'</option>
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
							&nbsp;&nbsp;Year
						</td>
						<td>
							&nbsp;:&nbsp;
						</td>
						<td>
							<select name="prev_year" id="prev_year">
								<option value="'.$currentyear.'" selected>
									'.$currentyear.'
								</option>';
								for($i=date(Y);$i>=2007;$i--)
								{
								echo'<option value="';echo $i.'">';
										echo $i;echo
									'</option>';
								}
						echo'</select>
						</td>
					</tr>
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
						
            $sel_month_id2 = "03";
            $sel_month_name2 = "Mar";	
						
					echo'<td  class="text">
							Current Month
						</td>
						<td>
							&nbsp;:&nbsp;
						</td>
						<td>
							<select name="current_month" id="current_month">
								<option value="'.$sel_month_id2.'" selected>'.$sel_month_name2.'</option>
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
						<td class="text">
							&nbsp;&nbsp;Year
						</td>
						<td>
							&nbsp;:&nbsp;
						</td>
						<td>
							<select name="current_year" id="current_year">
								<option value="'.$currentyear.'" selected>
									'.$currentyear.'
								</option>';
							for($i=date(Y);$i>=2007;$i--)
							{
							echo'<option value="';echo $i.'">';
									echo $i;echo
								'</option>';
							}
						echo'</select>
						</td>
					</tr>
				</table>
				<br>
			<center>	
				<input type="button" value="Enter" onclick="javascript:action_report_moto_monthly_cmp(\''.$filename1.'\',\''.$title1.'\');">&nbsp;
			</center>
				
	</form>
  </center>';
?>