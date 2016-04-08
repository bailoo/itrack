<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  $DEBUG=0;
  echo "edit##"; 

	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';  
?>
<br> 
<table border="0" align="center" class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
  <td>&nbsp;Student Card </td> 
  <td>&nbsp;:&nbsp;</td>
  <td>
    
    <select name="studentcard_id" id="studentcard_id" >
      <option value="select">Select</option>
      <?php
			$query="select * from studentcard where user_account_id='$common_id1' and status='1' and studentcard_id NOT IN(select studentcard_id from student where status='1')";
			$result=mysql_query($query,$DbConnection);            							
			while($row=mysql_fetch_object($result))
			{
			  $studentcard_id=$row->studentcard_id; $studentcard_number=$row->studentcard_number;                								 
			  echo '<option value='.$studentcard_id.'>'.$studentcard_number.'</option>';
			}
			?>
    </select>
  </td>
</tr>
<tr>
  <td>&nbsp;Student </td>
  <td>&nbsp;:&nbsp; </td>
  <td>
    
    <select name="student_id" id="student_id" >
      <option value="select">Select</option>
      <?php
			$query="select * from student where user_account_id='$common_id1' and status='1' and studentcard_id='0'";
			$result=mysql_query($query,$DbConnection);            							
			while($row=mysql_fetch_object($result))
			{
			  $student_id=$row->student_id; $student_name=$row->student_name;                								 
			  echo '<option value='.$student_id.'>'.$student_name.'</option>';
			}
			?>
    </select>
  </td>
</tr>

</table>
<center>
  <br>
		<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_studentcard('assign')" value="Assign">&nbsp;<input type="reset" value="Cancel">
	<br>
    <a href="javascript:show_option('manage','studentcard');" class="back_css">&nbsp;<b>Back</b></a>
</center>
  <?php 
    include_once('availability_message_div.php');
  ?>