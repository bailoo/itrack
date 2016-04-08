<?php
  include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
/*	include_once("report_hierarchy_header.php");
	$account_id_local1 = $_POST['account_id_local'];	
  $vehicle_display_option1 = $_POST['vehicle_display_option'];	
  $options_value1 = $_POST['options_value'];
  
  $options_value2=explode(",",$options_value1);			
  $option_size=sizeof($options_value2);
	$option_string="";  
  
	$function_string='get_'.$vehicle_display_option1.'_vehicle';   
  //echo 'Ac id :'.$account_id_local1;
  */
 
  $action_type1 = $_POST['action_type'];
  $classname1 = $_POST['classname'];
  $classname=explode(":",$classname1);
  //$section1 = $_POST['section'];
  $account_id_local1 = $_POST['account_id'];
  $td_cnt=1;
   echo "show_student##";
 echo'      
	<fieldset class="report_fieldset">
		<legend>Select Student</legend>
          		
			<table border=0  cellspacing=0 cellpadding=0  width="100%">
				<tr>
					<td align="center">							
						<div style="overflow: auto;height: 150px; width: 650px;" align="center">
							<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';						
                
								 echo'<tr><td height="10px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_students(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
                // $function_string($account_id_local1,$options_value1);
                $query="select distinct student_id,student_name from student where student_id IN(select student_id from student_grouping where account_id='$account_id_local1' and status='1') and `class`='$classname[0]' and section='$classname[1]' and status='1'";
        				 //echo $query;
                if($DEBUG==1){print $query;}
                $result=mysql_query($query,$DbConnection);            							
        				while($row=mysql_fetch_object($result))
        				{
							$student_id=$row->student_id; 
							$student_name=$row->student_name;
        		      if($td_cnt==1)
                  {
                    echo'<tr>';
                  }
                  
                  echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="studentserial[]" VALUE="'.$student_id.'"></td>
		                <td class=\'text\'>&nbsp;'.$student_name.'</td>';
  
                  
                  if($td_cnt==3)
                	{ 
                	   echo'</tr>';
                	}
                	$td_cnt++;
        				}
                														
								echo'
							</table>
						</div>
					</td>
				</tr>
			</table>
      </fieldset> <br>
      ';						
			
			echo'<fieldset class="report_fieldset">';
			echo'<legend>Select display Option</legend>';	
		/*	
			echo'<br><br><center><SPAN STYLE="font-size: xx-small">Select Interval </SPAN>
      <select name="user_interval" id="user_interval">';
			echo '<option value="1">1</option>';

			echo '<option value="2">2</option>';

			echo '<option value="3">3</option>';																					
			
			echo '<option value="4">4</option>';
			
			echo '<option value="5">5</option>';
			
			echo '<option value="6">6</option>';
			
			echo '<option value="7">7</option>';
										
			echo '<option value="8">8</option>';
			
			echo '<option value="9">9</option>';

			echo '<option value="10">10</option>';

			echo '<option value="11">11</option>';

			echo '<option value="12">12</option>';							

			echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> hr/hrs</SPAN></center><br>';
		*/											
			//date_default_timezone_set('Asia/Calcutta');
			$start_date=date("Y/m/d 00:00:00");	
			$end_date=date("Y/m/d H:i:s");	
			
			echo'
			<table border=0 cellspacing=0 cellpadding=3 align="center">	
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

							<input type="hidden" name="rep_uid" value="'.$uid.'">
							<input type="hidden" name="user_interval" id="user_interval" value="1"> 
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
						<input type="button" onclick="javascript:action_report_student(this.form);" value="Enter">
							&nbsp;
						<input type="reset" value="Clear">
					</td>
				</tr>
			</table>
			<div align="center" id="msg" style="display:none;"><br><font color="green">loading...</font></div>			
			<input type="hidden" name="vehicleserial[]" value="" />

    ';
?>						
					 
