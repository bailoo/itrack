<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include("user_type_setting.php");
	
	$root=$_SESSION['root'];  
	$js_function_name="manage_select_by_entity";	
	echo "assign##"; 
	
	$DEBUG=0;
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_radio_account.php');
	
	//$query="SELECT vehicle_id,vehicle_name from vehicle where vehicle_id IN (SELECT vehicle_id FROM vehicle_grouping WHERE account_id=$account_id and status=1)";
	$query="SELECT student_id,student_name from student where student_id IN (SELECT student_id FROM student_grouping WHERE account_id=$account_id)";
	if($DEBUG==1){echo "query=".$query;}
	$result=mysql_query($query,$DbConnection);
	$v_s=0;
	while($row=mysql_fetch_object($result))
	{$v_s++;$student_id[$v_s]=$row->student_id;	$student_name[$v_s]=$row->student_name;}
	
	echo'<br>
		<input type="hidden" id="common_id">
		<input type="hidden" id="action_name" value="student">
	<center>
		<table width="70%" align="center">
			<tr>
				<td>
					<fieldset class=\'assignment_manage_fieldset\'>
						<legend>';
						
            	
            	 echo'<strong>Students</strong>';
						
            echo'</legend>
						<div style="height:180px;overflow:auto;">
							<table border=1 cellspacing=0 cellpadding=0 class="module_tree_hierarchy" rules="all">
								<tr>';
									for($i=1;$i<=$v_s;$i++)
									{
										if($DEBUG==1){echo "student_id=".$student_id[$i]."<br>"."student_name=".$student_name[$i];}
										echo'<td><input type="radio" name="manage_id" value='.$student_id[$i].' onclick="javascript:manage_show_account(this.value,\'src/php/manage_show_account.php\')">&nbsp;'.$student_name[$i].'</td>';
										if($i%3==0){echo'</tr><tr>';}	
									}			
							echo'</tr>
							</table>
						</div>
					</fieldset>
				</td>
			</tr>
		</table>
		<div align="center" id="portal_vehicle_information" style="display:none;"></div><br>			
	</center>';
?>
  