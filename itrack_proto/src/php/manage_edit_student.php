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
  <td>&nbsp;Student Name&nbsp:&nbsp;
    
    <select name="student_id" id="student_id" onchange="show_student_record(manage1);">
      <option value="select">Select</option>
      <?php
			$query="select student.student_id,student.student_name from student,student_grouping where student.student_id=student_grouping.student_id and student_grouping.account_id='$common_id1' and student.status='1' and student_grouping.status='1'";
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
<tr> 		         
                          
  <td>
   <div id="display_area" style="display:none">
  <fieldset class="manage_fieldset1">
	<legend><strong>Student Record</strong></legend>
	<table border="0" class="manage_interface">
			<!--
    <tr>
			<td>Name</td>
			<td> :</td>
			<td><input type="text" name="add_school_name" id="add_school_name" onkeyup="manage_availability(this.value, 'school')" onmouseup="manage_availability(this.value, 'school')" onchange="manage_availability(this.value, 'school')"></td>
		</tr>
	
		<tr>
			<td>Coord</td>
			<td> :</td>
			<td><textarea readonly="readonly" style="width:350px;height:60px" name="route_coord" id="route_coord" onclick="javascript:showCoordinateInterface('route');"></textarea></td>	
		</tr>
		-->
		<tr>
			<td>Name</td>
			<td> :</td>
			<td><input type="text" name="edit_student_name" id="edit_student_name" ></td>
		</tr>
		<tr>
			<td>Address</td>
			<td> :</td>
			<td><textarea style="width:350px;height:60px" name="edit_student_address" id="edit_student_address"></textarea></td>
		</tr>
		<tr>
			<td>Father Name</td>
			<td> :</td>
			<td><input type="text" name="edit_student_father_name" id="edit_student_father_name" ></td>
		</tr>
		<tr>
			<td>Mother Name</td>
			<td> :</td>
			<td><input type="text" name="edit_student_mother_name" id="edit_student_mother_name" ></td>
		</tr>
		<tr>
			<td>Roll Number</td>
			<td> :</td>
			<td><input type="text" name="edit_student_roll_no" id="edit_student_roll_no" ></td>
		</tr>
		<tr>
			<td>Class</td>
			<td> :</td>
			<td>
         <select name="edit_student_class" id="edit_student_class" onchange="show_studentclass_section_edit(manage1)">
          <option value="select">Select</option>';
          <?php
    				$query="select * from studentclass where user_account_id ='$common_id1' and status='1'";
    				
    				if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $studentclass_id=$row->studentclass_id;
              $studentclass_name=$row->studentclass_name;
              $studentclass_section=$row->studentclass_section;
              if($studentclass_section!="")
              {
                echo'<option value='.$studentclass_id.":".$studentclass_section.'>'.$studentclass_name.'[Section-'.$studentclass_section.']</option>';
              }
              else
              {
                echo'<option value='.$studentclass_id.'":N/A">'.$studentclass_name.'[Section N/A]</option>';
              }
    				}
  				?>
  		    </select>
      
      </td>
		</tr>
		<tr>
			<td>Section</td>
			<td> :</td>
			<td><input type="text" name="edit_student_section" id="edit_student_section" readonly=true></td>
		</tr>
		<tr>
			<td>Student Mobile No</td>
			<td> :</td>
			<td><input type="text" name="edit_student_student_mobile_no" id="edit_student_student_mobile_no" ></td>
		</tr>
		<tr>
			<td>Parent Mobile No</td>
			<td> :</td>
			<td><input type="text" name="edit_student_parent_mobile_no" id="edit_student_parent_mobile_no" ></td>
		</tr>
		<tr>
        <td>Student Card</td>
        <td> :</td>
			   <td>
          <select name="studentcard_id" id="studentcard_id" >
          <option value="select">Select</option>';
          <?php
			$query="select studentcard.studentcard_id,studentcard.studentcard_number from studentcard where studentcard.user_account_id='$common_id1' and studentcard.status='1' AND studentcard.studentcard_id NOT IN(select studentcard_id from student where status='1')";
    				
            if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $studentcard_id=$row->studentcard_id; $studentcard_number=$row->studentcard_number;
    		      echo'<option value='.$studentcard_id.'>'.$studentcard_number.'</option>';
    				}
  				?>
  		    </select>
  		    <?php //echo $query; ?>
  		    </td>
  		</tr>
			<tr>
        <td>Shift Pick</td>
        <td> :</td>
			   <td>
          <select name="shift_id_pick" id="shift_id_pick" Onchange="javascript:return clear_all('clear','pick')">
          <option value="select">Select</option>';
          <?php
    				$query="select * from shift where group_id='$group_id' and status='1'";
    				if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $shift_id=$row->shift_id; $shift_name=$row->shift_name;
    		      echo'<option value='.$shift_id.'>'.$shift_name.'</option>';
    				}
  				?>
  		    </select>
      
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      
      Shift Drop
      &nbsp;   :  &nbsp;
			   
          <select name="shift_id_drop" id="shift_id_drop" Onchange="javascript:return clear_all('clear','drop')">
          <option value="select">Select</option>';
          <?php
    				$query="select * from shift where group_id='$group_id' and status='1'";
    				if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $shift_id=$row->shift_id; $shift_name=$row->shift_name;
    		      echo'<option value='.$shift_id.'>'.$shift_name.'</option>';
    				}
  				?>
  		    </select>
      </td>
   </tr>
   	<tr>
        <td>Bus Stop Pick</td>
        <td> :</td>
			   <td>
		<select name="busstop_id_pick" id="busstop_id_pick" Onchange="javascript:return get_busroute('get_busroute','pick')">
		<option value="select">Select</option>';
		<?php
			$query="select * from busstop where group_id='$group_id' and status='1'";
			if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $busstop_id=$row->busstop_id; $busstop_name=$row->busstop_name;
    		      echo'<option value='.$busstop_id.'>'.$busstop_name.'</option>';
    				}
		?>
		</select>
      
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      
      Bus Stop Drop
      &nbsp;   :  &nbsp;
			   
          <select name="busstop_id_drop" id="busstop_id_drop" Onchange="javascript:return get_busroute('get_busroute','drop')">
          <option value="select">Select</option>';
          <?php
    				$query="select * from busstop where group_id='$group_id' and status='1'";
    				if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $busstop_id=$row->busstop_id; $busstop_name=$row->busstop_name;
    		      echo'<option value='.$busstop_id.'>'.$busstop_name.'</option>';
    				}
  				?>
  		    </select>
      </td>
   </tr>
   <tr>
        <td>Route Pick</td>
        <td> :</td>
			   <td>
          <select name="busroute_id_pick" id="busroute_id_pick" Onchange="javascript:return get_bus('get_bus','pick')">
          <option value="select">Select</option>';
          <?php
    			/*	$query="select * from busroute where user_account_id='$common_id1' and status='1'";
    				if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $busroute_id=$row->busroute_id; $busroute_name=$row->busroute_name;
    		      echo'<option value='.$busroute_id.'>'.$busroute_name.'</option>';
    				}
  				*/?>
  		    </select>
      
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      
      Route Drop
      &nbsp;   :  &nbsp;
			   
          <select name="busroute_id_drop" id="busroute_id_drop" Onchange="javascript:return get_bus('get_bus','drop')">
          <option value="select">Select</option>';
          <?php
    			/*	$query="select * from busroute where user_account_id='$common_id1' and status='1'";
    				if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $busroute_id=$row->busroute_id; $busroute_name=$row->busroute_name;
    		      echo'<option value='.$busroute_id.'>'.$busroute_name.'</option>';
    				}
  				*/?>
  		    </select>
      </td>
   </tr>
   <tr>
        <td>Bus Pick</td>
        <td> :</td>
			   <td>
          <select name="bus_id_pick" id="bus_id_pick" >
          <option value="select">Select</option>';
          <?php
    			/*	$query="select * from vehicle where account_id='$common_id1' and vehicle_tag='bus' and status='1'";
    				if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $vehicle_id=$row->vehicle_id; $vehicle_name=$row->vehicle_name;
    		      echo'<option value='.$vehicle_id.'>'.$vehicle_name.'</option>';
    				}
  				*/?>
  		    </select>
      
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      
      Bus Drop
      &nbsp;   :  &nbsp;
			   
          <select name="bus_id_drop" id="bus_id_drop" >
          <option value="select">Select</option>';
          <?php
    			/*	$query="select * from vehicle where account_id='$common_id1' and vehicle_tag='bus' and status='1'";
    				if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $vehicle_id=$row->vehicle_id; $vehicle_name=$row->vehicle_name;
    		      echo'<option value='.$vehicle_id.'>'.$vehicle_name.'</option>';
    				}
  				*/?>
  		    </select>
      </td>
   </tr>
	  <!--
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="enter_button" value="Save" onclick="javascript:return action_manage_student('add')"/>&nbsp;<input type="reset"" value="Clear" />
			</td>
		</tr>
		-->
	</table>
	</fieldset>
</div>
</td>                                
</tr>        
<tr>
	<td colspan="3" align="center">
		<input type="button" id="enter_button" value="Update" onclick="javascript:return action_manage_student('edit')"/>&nbsp;
		<input type="button" value="Delete" onclick="javascript:action_manage_student('delete')"/>
	</td>
</tr>
</table>
	<?php
		include_once('availability_message_div.php');
	?>

  