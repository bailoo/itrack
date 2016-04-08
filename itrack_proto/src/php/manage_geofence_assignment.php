<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('manage_hierarchy_header.php');
	
	echo"<br>
			<center>
				<form name='manage1' method='post'>
					<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>";
						$function_string($root,$div_option_values,$DbConnection);
				echo'</table>';
				
				echo"<br>
					<table border=1 cellspacing=0 cellpadding=0 class='module_left_menu' align='center' width='70%'>";
						account_column_count($root);
					echo"<tr>";
							for($i=0;$i<$MaxColumnNo;$i++)
							{
							echo"<td>&nbsp;".'Level'.$i."</td>";
							}
					echo"</tr>";

					$ColumnNo=0;
					$RowNo=0;
					$group_cnt=0;
					
					get_account_hierarchy($root);
				echo"</table><br>";				
				echo'<center>
							<input type="button" Onclick="javascript:action_manage_vehicle(this.form, \'grouping\')" value="Enter">&nbsp;
					</center>
					<center><a href="javascript:show_option(\'manage\',\'vehicle\');" class="back_css">&nbsp;<b>Back</b></a></center>
				</form>';
				function account_column_count($AccountNode)
				{ 
					global $ColumnNo;
					global $RowNo;
					global $MaxColumnNo;
					global $count;  
					$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;
					$account_name=$AccountNode->data->AccountName;
					$ChildCount=$AccountNode->ChildCnt;

					$ColumnNo++;
					$RowNo++;

					if($MaxColumnNo<$ColumnNo)
					{
						$MaxColumnNo = $ColumnNo;
					}
					for($i=0;$i<$ChildCount;$i++)
					{
						account_column_count($AccountNode->child[$i]);
					}
					$ColumnNo--;
				}

				function get_account_hierarchy($AccountNode)
				{
					global $ColumnNo;
					global $RowNo;
					global $count;
					global $MaxColumnNo;

					$hierarchy_level=$AccountNode->data->AccountHierarchyLevel;    
					$account_id_local=$AccountNode->data->AccountID;
					$group_id_local=$AccountNode->data->AccountGroupID;
					//echo "account_id=".$account_id_local."group_id=".$group_id_local."<br>";
					$account_name=$AccountNode->data->AccountName;
					$ChildCount=$AccountNode->ChildCnt;		

				echo"<tr>";
						for($k=0;$k<$ColumnNo;$k++)
						{
							echo"<td>&nbsp;".''."</td>";
						}
								echo"<td>&nbsp;                
										<INPUT TYPE='radio' name='manage_user' VALUE='$account_id_local'><a href='tree_account_detail.php?account_id_local=$account_id_local'>".$account_name."</a>
									</td>";
							for($l=($k+1);$l<$MaxColumnNo;$l++)
							{
								echo"<td>&nbsp;".''."</td>";
							}
				echo"</tr>";

					$ColumnNo++;
					$RowNo++;

					for($i=0;$i<$ChildCount;$i++)
					{     
						get_account_hierarchy($AccountNode->child[$i]);
					}  
					$ColumnNo--;
				}
  /*include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  
  $DEBUG=0;
   echo "assign##";
  $query = "SELECT superuser, user FROM account WHERE account_id='$account_id'";   	  
  if($DEBUG==1)
    print_query($query);	
  $result = @mysql_query($query, $DbConnection);      	
  $row = mysql_fetch_object($result);
 
  $user = $row->user;
  $superuser = $row->superuser;
  
  // GET USER DEVICES + NOT ASSIGNED 
  
  $query = "SELECT account_id from account WHERE superuser='$superuser' and user='$user' and grp='admin'";
  if($DEBUG==1)
    print_query($query);
    
  $result = @mysql_query($query, $DbConnection);
  $row = mysql_fetch_object($result);     	
  $user_account_id = $row->account_id;
  
  $query = "SELECT geo_id,geo_name FROM geofence WHERE ".
  "user_account_id='$user_account_id' AND ".
  "geo_id NOT IN(SELECT geo_id FROM vehicle WHERE geo_id!=null) AND status='1'"; 

  if($DEBUG==1)
    print_query($query);
      
  $result = @mysql_query($query, $DbConnection);
  $g_count=0;
  $geo_id=null;
  $geo_name=null;
  while($row = mysql_fetch_object($result))
  {
    $geo_id[$g_count] = $row->geo_id;
    $geo_name[$g_count] = $row->geo_name;
    $g_count++;
  }
  //print_query($query);  
  
  // GET ACCOUNT VEHICLES + NOT ASSIGNED
  $query = "SELECT VehicleID,VehicleName FROM vehicle WHERE ".
  "VehicleID IN(SELECT vehicle_id FROM vehicle_grouping WHERE ".
  "vehicle_group_id =(SELECT vehicle_group_id FROM ".
  "account_detail WHERE account_id='$account_id') AND ".
  "vehicle_id IN(SELECT VehicleID from vehicle WHERE geo_id IS NULL))";
  
  if($DEBUG==1)
    print_query($query);  
  
  $result = @mysql_query($query, $DbConnection);
      	
  $v=0;
  $vid=null;
  $vname=null;
  while($row = mysql_fetch_object($result))
  {
    $vid[$v] = $row->VehicleID;
    $vname[$v] = $row->VehicleName;
    $v++;
  }
      
echo'<center>
<form method = "post"  name="thisform">
  <input type="hidden" name="lh" id="lh" value="wrong"><input type="hidden" name="rh" id="rh" value="wrong">  	
  <fieldset class="manage_fieldset">
		<legend><strong>Geofence Assignment</strong></legend>	        
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
  <tr>
    <td>
      <fieldset class="manage_fieldset">
    		<legend>Select Geofence</legend>		
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">        
        <tr>
            <td><input type="radio" name="left_search" value="1" onclick="javascript:this.form.ls.disabled=false;this.form.lo.disabled=true;"></td>
            <td>Search</td>
            <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="ls" id="ls" onclick="javascript:this.form.ls.disabled=false;this.form.lo.disabled=true;this.form.left_search[0].checked=true;this.form.enter_button.disabled=true;" onFocus="javascript:thisform.geofence_option[0].checked=true;" onkeyup="manage_availability(this.value, \'ls\', \'existing_in_user\')" onmouseup="manage_availability(this.value, \'ls\', \'existing_in_user\')" onchange="manage_availability(this.value, \'ls\', \'existing_in_user\')">
            </td>
        </tr> 
        
        <tr valign="top">
            <td><input type="radio" name="left_search" value="2" onclick="javascript:this.form.lo.disabled=false;this.form.ls.disabled=true;"></td>
            <td>Select</td>
             <td>&nbsp;:&nbsp;</td>
            <td>';              
              if($g_count==0)
              {
                  echo '<font color=red>No geofence Found</font>';
              }
              else
              {
                echo'<select name="lo" id="lo" size="6" onclick="javascript:this.form.lo.disabled=false;this.form.ls.disabled=true" onFocus="javascript:thisform.left_search[1].checked=true;" onchange="javascript:set_option(\'lh\',\'ls\',\'lo\');">';              
                	        
                for($i=0; $i<$g_count; $i++)
                {
                  echo'<option value="'.$geo_id[$i].'#'.$geo_name[$i].'">'.$geo_name[$i].'</option>';
                }                                                            
                echo'</select>';
              }          
            echo'</td>
        </tr>
    </table>
    </fieldset>  
    </td>
    
   <td>
   <fieldset class="manage_fieldset">
		<legend>Select Vehicle to Assign</legend>		
   
    <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
        
        <tr>
            <td><input type="radio" name="right_search" value="1" onclick="javascript:this.form.rs.disabled=false;this.form.ro.disabled=true;"></td>
            <td>Search</td>
             <td>&nbsp;:&nbsp;</td>
            <td>
              <input type="text" name="rs" id="rs" onclick="javascript:this.form.rs.disabled=false;this.form.ro.disabled=true;this.form.right_search[0].checked=true;this.form.enter_button.disabled=true;" onFocus="javascript:thisform.vehicle_option[0].checked=true;" onkeyup="manage_availability(this.value, \'rs\', \'existing_not_assigned\')" onmouseup="manage_availability(this.value, \'rs\', \'existing_not_assigned\')" onchange="manage_availability(this.value, \'rs\', \'existing_not_assigned\')">
            </td>
        </tr> 
        
        <tr valign="top">
            <td><input type="radio" name="right_search" value="2" onclick="javascript:this.form.ro.disabled=false;this.form.rs.disabled=true;"></td>
            <td>Select</td>
             <td>&nbsp;:&nbsp;</td>
            <td>';
              if($v == 0)
              { 
                echo '<font color=red>No vehicle Found</font>';
              }
              else
              {           
                echo'<select name="ro" id="ro" size="6" onclick="javascript:this.form.ro.disabled=false;this.form.rs.disabled=true" onFocus="javascript:thisform.right_search[1].checked=true;" onchange="javascript:set_option(\'rh\',\'rs\',\'ro\');">';             
                                     
                 for($i=0;$i<$v;$i++)
                 {
                    echo '<option value="'.$vid[$i].'#'.$vname[$i].'">'.$vname[$i].'</option>';
                 }
                echo'</select>';
              }   
            echo'</td>
        </tr>
    </table>      
    </fieldset>
    </td>
    </tr>
      
	  <tr>                    									
			<td align="center" colspan="3"><br><input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_geofence_assignment(thisform)" value="Assign">&nbsp;<input type="reset" value="Cancel"></td>
		</tr>
		</table>
    
    </fieldset>  
   </form>
  <div id="available_message" align="center"></div>
  </center>
  ';*/
   
?>  