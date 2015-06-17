<?php
	include_once("report_hierarchy_header.php");
	$account_id_local1 = $_POST['account_id_local'];	
  $vehicle_display_option1 = $_POST['vehicle_display_option'];	
  $options_value1 = $_POST['options_value'];
  
  $options_value2=explode(",",$options_value1);			
  $option_size=sizeof($options_value2);
	$option_string="";  
  
	$function_string='get_'.$vehicle_display_option1.'_vehicle';   
  //echo 'Ac id :'.$account_id_local1;
 // echo "Group=".$group_id;
 echo'
  <center>
			<table border=0 width = 100% cellspacing=2 cellpadding=0>
				<tr>
					<td height=10 class="report_heading" align="center">Student Report</td>
				</tr>
			</table>			
														
			<form  method="post" name="thisform">
      <input type="hidden" id="account_id_hidden" value='.$account_id_local1.'>							
			<br>								
      <fieldset class="report_fieldset">
    		<legend>Select </legend>
        
        <table  border=0  cellspacing=0 cellpadding=0  width="100%" STYLE="font-size: xx-small">
           <tr>
			<td>Class :
			
         <select name="student_classwise_id" id="student_classwise_id" Onchange="javascript:return show_student_classwise(\'show_student\')">
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
                echo'<option value='.$studentclass_id.":".$studentclass_section.'>'.$studentclass_name.'[Section-'.$studentclass_section.']</option>';
              }
              else
              {
                echo'<option value='.$studentclass_id.":".$studentclass_section.'>'.$studentclass_name.'[Section N/A]</option>';
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
		  
        </table>	
     </fieldset> <br>
    
    <div id="student_div"> </div>

		</form>
    <center>
    ';
?>						
					 
