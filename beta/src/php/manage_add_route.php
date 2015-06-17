<?php 
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$root=$_SESSION['root'];
  
  echo "add##"; 
	include_once('tree_hierarchy_information.php');
	include_once('manage_checkbox_account.php'); 

	$total_account_str ="";
  for($i=0;$i<sizeof($total_account_ids);$i++)
	{
		if($i==0)
		{
		  $total_account_str = $total_account_ids[$i];
    }
    else
    {
      $total_account_str = $total_account_str.",".$total_account_ids[$i];
    }
   // echo "<br>".$total_account_ids[$i];
	}
		
	
	if($total_account_str == "")
	{
   $total_account_str = $account_id;
  }
	else
	{
	  $total_account_str = $total_account_str.",".$account_id;
  }
	
	$query = "SELECT DISTINCT sector_id,sector_name FROM sector WHERE user_account_id IN('$total_account_str') AND status=1";
	//echo $query;
  $result = mysql_query($query,$DbConnection);
	$num_rows = mysql_num_rows($result);
	
	
	echo'
				<table border="0" class="manage_interface">
					<tr>
						<td>Route Name</td><td>:</td>
						<td align="left"><input type="text" name="add_route_name" id="add_route_name" onkeyup="manage_availability(this.value, \'route\')" onmouseup="manage_availability(this.value,\'route\')" onchange="manage_availability(this.value, \'route\')"></td>
          </tr>				  
        
          <tr>
  						<td align="left" colspan="2" >Select Sectors</td><td>:</td>
          </tr>						

          <tr>
              <td colspan="2"></td>
              <td>';
        if($num_rows == 0)
				{
					echo "<font color=blue>Currently No Sector Added in Account. You can also Assign it in Edit Route Option</font>";
				}
				else
				{
					echo'
					<table border="1" class="manage_interface">	
					
					<tr bgcolor="lightgrey">
					  <td align="left"><input type="checkbox" id="all" name="all" onclick="javascript:select_all_sectors(this.form);">Select</td>
								<td align="left">Sectors Name</td>            						
					  <td align="left">Sequence</td>
							</tr>';										         
				
					$i=0;
					while($row = mysql_fetch_object($result))
					{
					  $sector_id = $row->sector_id;
					  $sector_name = $row->sector_name;
					  echo'<tr>
									<td><input type="checkbox" id="sector[]" name="sector[]" value="'.$sector_id.'"></td>
						<td>'.$sector_name.'</td>
						<td>';
						$seq = "seq".$i; 
						echo'<select name="sequence" id="'.$seq.'" onchange="javascript:validate_sector_sequence(this.id)">';
		  
						echo '<option value="0">Select</option>';
						
						for($j=1;$j<25;$j++)
						{                      
						  echo '<option value="'.$j.'">'.$j.'</option>';
						}  
						echo'</select>
						</td>   						  						
							 </tr>';
							 $i++;
					}					
							 echo'</table>
					';
				}
            echo'</td>
          </tr>
        </table>';			
  					
        echo'
        <br><table border="0" class="manage_interface">
          <tr>
        		<td align="center"><input type="button" id="enter_button" value="Save" onclick="javascript:action_manage_route(\'add\')"/>&nbsp;<input type="reset"" value="Clear" /></td>
        	</tr>
        </table>';								
				
	include_once('availability_message_div.php');
?>
  