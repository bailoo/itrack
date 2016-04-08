<?php 
	//include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//$root=$_SESSION['root'];  
	$DEBUG=0;
	$parameter_type="student";
	echo "edit##";
	$common_id1=$_POST['common_id'];
	
	$group_id="";
	 $query="SELECT group_id from account where account_id='$common_id1' AND status='1'";
		//echo "query=".$query."<br>";
		$result=mysql_query($query,$DbConnection);
		$row_result=mysql_num_rows($result);		
		if($row_result!=null)
		{
		$row=mysql_fetch_object($result);
		$group_id=$row->group_id;
		}
		
	
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo'<input type="hidden" id="group_id_hidden" value='.$group_id.'>';
	echo'<input type="hidden" id="selected_account_id_hidden" value='.$common_id1.'>';
	//include_once('tree_hierarchy_information.php');
	//include_once('manage_radio_account.php');     
 ?>  
	<br>
	<table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
  <td>&nbsp;Class Name&nbsp:&nbsp;
    
    <select name="studentclass_id" id="studentclass_id" onchange="show_studentclass_record(manage1);">
      <option value="select">Select</option>
      <?php
			$query1="select studentclass.studentclass_id,studentclass.studentclass_name ,studentclass.studentclass_section from studentclass where studentclass.user_account_id ='$common_id1' and studentclass.status='1' ";
			
      $result1=mysql_query($query1,$DbConnection);            							
			
				while($row1=mysql_fetch_object($result1))
    				{
    				  $studentclass_id=$row1->studentclass_id;
              $studentclass_name=$row1->studentclass_name;
              $studentclass_section=$row1->studentclass_section;
              if($studentclass_section!="")
              {
                echo'<option value='.$studentclass_id.'>'.$studentclass_name.'[Section-'.$studentclass_section.']</option>';
              }
              else
              {
                echo'<option value='.$studentclass_id.'>'.$studentclass_name.'[Section N/A]</option>';
              }
    				}
			?>
    </select>
  </td>
</tr>
<tr> 		         
                          
  <td>
   <div id="display_area" style="display:none">
  <fieldset class="manage_fieldset1">
	<legend><strong>Student Class Record</strong></legend>
	<table border="0" class="manage_interface">
		
		<tr>
			<td>Class Name</td>
			<td> :</td>
			<td><input type="text" name="studentclass_name" id="studentclass_name" readonly="true"></td>
		</tr>
		<tr>
			<td>Section</td>
			<td> :</td>
			<td><input type="text"  name="studentclass_section" id="studentclass_section" readonly="true"/></td>
		</tr>
		<tr>
			<td>Class Lat</td>
			<td> :</td>
			<td><input type="text" name="class_lat" id="class_lat" ></td>
		</tr>
		<tr>
			<td>Class Lng</td>
			<td> :</td>
			<td><input type="text" name="class_lng" id="class_lng" ></td>
		</tr>
   
	</table>
	</fieldset>
</div>
</td>                                
</tr>        
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_manage_studentclass('edit')"/>&nbsp;
		<input type="button" value="Delete" onclick="javascript:action_manage_studentclass('delete')"/>
	</td>
</tr>
</table>
	<?php
		include_once('availability_message_div.php');
	?>

  