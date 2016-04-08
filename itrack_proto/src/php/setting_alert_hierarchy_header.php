<?php
		$query="SELECT student_name,studentcard.studentcard_id,studentcard.studentcard_number from student,studentcard where student.studentcard_id=studentcard.studentcard_id and student.status=1 and studentcard.status=1 and student.student_id IN(select distinct student_id from student_grouping where account_id='$common_id1' and status='1')";
		//echo 'query : '.$query;
		$result=mysql_query($query,$DbConnection);
				$studentSize=0;
				while($row=mysql_fetch_object($result))
				{
				$student_name[$studentSize]=$row->student_name;
				$studentcard_id[$studentSize]=$row->studentcard_id;
				$studentcard_number[$studentSize]=$row->studentcard_number;
				$studentSize++;
				}
	$td_cnt=1;
	
	echo'<table align="center" width="70%">
	<tr>
		<td align="center" width="100%">
			<fieldset class="manage_fieldset">
				<legend><strong>Select Student</strong></legend>
				<table border="0" class="manage_interface">';
				echo'<tr><td height="10px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_students(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
				echo'<tr>';
				for($i=0;$i<$studentSize;$i++)
				{
									
					$disp_name=$student_name[$i]."(".$studentcard_number[$i].")";
					echo'<td align="left" valign="top">&nbsp;<INPUT TYPE="checkbox"  name="card_id[]" VALUE="'.$studentcard_id[$i].'"></td>
					<td width="250" class=\'text\' valign="middle">&nbsp;'.$disp_name.'&nbsp;'; echo'</td>';
				
				if($td_cnt==3)
					{ 
					   echo'</tr>';
					   echo'<tr>';
					   $td_cnt=1;
					}
				$td_cnt++;
					
				}
			
				echo'</table>	
		</td>
	</tr>
</table>';