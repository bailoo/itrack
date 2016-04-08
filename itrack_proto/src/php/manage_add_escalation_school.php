<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');  
	$root=$_SESSION['root']; 
	$postPars = array('escalationschool_id' , 'action_type' , 'local_account_ids' , 'person_name', 'person_mob', 'person_email' , 'other_detail','student_id','studentcard_id','student_father_name','student_parent_mobile_no');
	include_once('action_post_data.php');
  $pd = new PostData();
	echo'<div style="height:10px"> </div>';
	include_once('manage_escalation_school.php');
	
	$common_id1=$_POST['common_id'];
	
	
	$local_account_ids=explode(",",$common_id1);
	$account_size=sizeof($local_account_ids);
	$selected_account_id=$local_account_ids[$account_size-1];
//	echo  $selected_account_id;
	//$local_account_ids=explode(",",$pd->data[local_account_ids]);
//echo $local_account_ids[1];
  //$account_size=sizeof($local_account_ids); 
  	$group_id="";
	 $query="SELECT group_id from account where account_id='$selected_account_id' AND status='1'";
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
	echo'<input type="hidden" id="selected_account_id" value='.$selected_account_id.'>';
	include_once('tree_hierarchy_information.php');
   
   
	echo'<div style="height:10px"> </div>  
				<table border="0" class="manage_interface" align="center">
				 <tr>
			<td>Class :
			
         <select name="student_classwise_id" id="student_classwise_id"  onchange="show_student_classwise(manage1);">
          <option value="select">Select</option>
            ';
    				$query="select * from studentclass where group_id ='$group_id' and status='1'";
    				
    				if($DEBUG==1){print $query;}
            $result=mysql_query($query,$DbConnection);            							
    				while($row=mysql_fetch_object($result))
    				{
    				  $studentclass_id=$row->studentclass_id;
              $studentclass_name=$row->studentclass_name;
              $studentclass_section=$row->studentclass_section;
              if($studentclass_section!="")
              {
                echo'<option value='.$studentclass_id.'>'.$studentclass_name.'[Section-'.$studentclass_section.']</option>';
              }
              else
              {
                echo'<option value='.$studentclass_id.'>'.$studentclass_name.'[Section N/A]</option>';
              }
    				}
  			    echo'
  		    </select>
      
      </td>
      </tr>
      <tr>
        <td> 
           <div id="display_area" style="display:none">
            <fieldset class="manage_fieldset1">
        	   <legend><strong>Select Student</strong></legend>
              	
          			<div id="class_student_name" name="class_student_name"></div>
          		  
        	</fieldset>
          </div>
        </td>
		  </tr> 
		  
		  <tr>
        <td> 
           <div id="display_area1" style="display:none">
            <fieldset class="manage_fieldset1">
        	   <legend><strong>Student Record</strong></legend>
              <table border="0" class="manage_interface">
			
            		<tr>
            			<td>Name</td>
            			<td> :</td>
            			<td><input type="text" name="student_name" id="student_name" Readonly=true ><input type="hidden" name="student_id" id="student_id"></td>
            		</tr>
            		<tr>
            			<td>Address</td>
            			<td> :</td>
            			<td><textarea style="width:350px;height:60px" name="student_address" id="student_address"  Readonly=true></textarea></td>
            		</tr>
            		<tr>
            			<td>Father Name</td>
            			<td> :</td>
            			<td><input type="text" name="student_father_name" id="student_father_name"  Readonly=true></td>
            		</tr>
            		<tr>
            			<td>Mother Name</td>
            			<td> :</td>
            			<td><input type="text" name="student_mother_name" id="student_mother_name"  Readonly=true></td>
            		</tr>
            		<tr>
            			<td>Roll Number</td>
            			<td> :</td>
            			<td><input type="text" name="student_roll_no" id="student_roll_no"  Readonly=true></td>
            		</tr>
            		<tr>
            			<td>Class</td>
            			<td> :</td>
            			<td><input type="text" name="student_class" id="student_class"  Readonly=true></td>
            		</tr>
            		<tr>
            			<td>Card ID</td>
            			<td> :</td>
            			<td><input type="hidden" name="studentcard_id" id="studentcard_id" ><input type="text" name="studentcard" id="studentcard"  Readonly=true></td>
            		</tr>
            		<tr>
            			<td>Student Mobile No</td>
            			<td> :</td>
            			<td><input type="text" name="student_student_mobile_no" id="student_student_mobile_no"  Readonly=true></td>
            		</tr>
            		<tr>
            			<td>Parent Mobile No</td>
            			<td> :</td>
            			<td><input type="text" name="student_parent_mobile_no" id="student_parent_mobile_no"  Readonly=true></td>
            		</tr>	
          		
          		  
        	</fieldset>
          </div>
        </td>
		  </tr> 
		  	<tr>
						<td valign="top">Person Email</td>
            <td>:</td>
            <td colspan=2>';
          if($pd->data[person_email]!="")
          {
						  echo'<input type="text" value="'.$pd->data[person_email].'" id="person_email">';
					 }
					 else
					 {
					   echo'<input type="text" id="person_email">';
           }
           echo'</td>
          </tr>  
		  
	<!--	  
				
					<tr>
						<td valign="top">Person Name</td>
            <td>:</td>
            <td colspan=2>';
          if($pd->data[person_name]!="")
          {
						  echo'<input type="text" value="'.$pd->data[person_name].'" id="person_name">';
					 }
					 else
					 {
					   echo'<input type="text" id="person_name">';
           }
           echo'</td>
          </tr>
          
					<tr>
						<td valign="top">Person Mobile No</td>
            <td>:</td>
            <td colspan=2>';
          if($pd->data[person_mob]!="")
          {
						  echo'<input type="text" value="'.$pd->data[person_mob].'" id="person_mob">';
					 }
					 else
					 {
					   echo'<input type="text" id="person_mob">';
           }
           echo'</td>
          </tr>
          
				        
                    
					<tr>
						<td valign="top">Other Detail</td>
            <td valign="top">:</td>
						<td valign="top">';
            if($pd->data[other_detail]!="")
            {
              echo'<textarea  style="width:350px;height:60px" id="other_detail">'.$pd->data[other_detail].'</textarea>';
            }
            else
            {
              echo'<textarea  style="width:350px;height:60px" id="other_detail"></textarea>';
            }                               
            echo'</td>
            <td valign="top"></td>	
					</tr>
	-->
					<tr>
						<td colspan="4" align="center"><input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_escalation_school(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
					</tr>
				</table></form>';
	include_once('availability_message_div.php'); 			
?>
