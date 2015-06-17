<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//include_once('manage_hierarchy_header1.php');
	$root=$_SESSION['root'];
	$common_id1=$_POST['common_id'];
	echo'<input type="hidden" id="account_id_hidden" value='.$common_id1.'>';
	echo"<br>			
			<form name='manage1' method='post'>
				<center>
				<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>
					<!--
          <tr>
						<td colspan='3'>
							&nbsp;&nbsp;<INPUT TYPE='checkbox' name='all_busstop' onclick='javascript:select_all_assigned_busstop(this.form);'>
							<font size='2'>Select All</font>"."												
						</td>																														
					</tr>
          -->
          ";
					//get_user_busstops($root,$common_id1);
			echo"</table>

				<br>
				<fieldset class='manage_fieldset'>
				<legend><strong>Bus Routes</strong></legend>
				<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu' align='center'> 
					";
					$td_cnt=0;							
					$query="SELECT * from busroute where group_id='$common_id1' AND status='1'";
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$row_result=mysql_num_rows($result);		
					if($row_result!=null)
					{
						while($row=mysql_fetch_object($result))
						{									
							$busroute_id=$row->busroute_id;
							$busroute_name=$row->busroute_name;
              $td_cnt++; 
						echo"<tr>
								<td>
									&nbsp<INPUT TYPE='radio' name='busroute_id' VALUE='$busroute_id' Onclick='javascript:return get_assigned_busstop(\"getbusstops\",\"$busroute_id\")' >
									<font color='blue' size='2'>".$busroute_name."</font>"."												
								</td>																														
							</tr>";
              if($td_cnt==3)
								{
									$td_cnt=0;
								} 
						}
					}
					else
					{
						echo"<font color='blue' size='2'>NO Bus Stop FOUND IN THIS ACCOUNT </font>";
					}
						echo"</td>";
					echo"</tr>";
			echo'</table>
			 </fieldset>
			 
			 <input type="hidden" name="busstops_assigned" id="busstops_assigned">  
			 
			<fieldset class=\'manage_fieldset\'>
				<legend><strong>Select Bus Stop</strong></legend>
				<table border=0 cellspacing=0 cellpadding=0 class=\'module_left_menu\' align="center">
				';
				  get_user_busstops($root,$common_id1);
				echo'
			   </table>
			</fieldset> 
      
      
      <fieldset class=\'manage_fieldset\'>
				<legend><strong>Selected Bus Stops</strong></legend>
				<table border=0 cellspacing=0 cellpadding=0 class=\'module_left_menu\' id="EntryTable" align="center">
			   </table>
			</fieldset>   
      
			 
				<br>
					<input type="button" id="enter_button" name="enter_button" Onclick="javascript:return action_manage_busstop(\'assign\')" value="Assign">&nbsp;<input type="reset" value="Cancel">
				<br><a href="javascript:show_option(\'manage\',\'busstop\');" class="back_css">&nbsp;<b>Back</b></a>
				</center>
			</form>';	

      function get_user_busstops($AccountNode,$common_id1)
      {
        global $DbConnection;
        echo'<tr>
        <td><b>Bus Stop &nbsp;&nbsp;</b></td>
        <td><b> : &nbsp;&nbsp;</b></td>
			   <td>
          <select name="busstop_id" id="busstop_id" >
          <option value="select">Select</option>';
        $query="SELECT busstop_id,busstop_name FROM busstop WHERE group_id='$common_id1' AND status='1'";
				echo $query;
        				$result=mysql_query($query,$DbConnection);
								$row_result=mysql_num_rows($result);								
								if($row_result!=null)
								{
									while($row=mysql_fetch_object($result))
									{							
										$busstop_id =$row->busstop_id;
										$busstop_name =$row->busstop_name;
									echo'<option value="'.$busstop_id.':'.$busstop_name.'">'.$busstop_name.'</option>';
									}
								}
								
								echo'
								    </select>
								    
								    <input type="button" value="Add Busstop" onClick="addRow(\'EntryTable\')">&nbsp; After 
								    <select name="busstop_after" id="busstop_after" >
                          <option value="select">Select</option>
                    </select>
                    <input type="hidden" name="rowcnt" id="rowcnt" value="0">
                    <input type="hidden" name="busstops" id="busstops">
								    </td>
								    </tr> 								    
								';
        //echo $query;
      }

  /*    function get_user_busstops($AccountNode,$account_id)
      {
        global $DbConnection;
        $query="SELECT busstop_id,busstop_name FROM busstop WHERE user_account_id='$account_id' AND status='1'";
				//echo $query;
        				$result=mysql_query($query,$DbConnection);
								$row_result=mysql_num_rows($result);
								$busstop_list="";
								$i=0;
								if($row_result!=null)
      					{
      						while($row=mysql_fetch_object($result))
      						{							
									$busstop_id =$row->busstop_id;
									$busstop_name =$row->busstop_name;
  									if($i==0){
  									   $busstop_list = $busstop_list.$busstop_id.":".$busstop_name;
                    }
                    else{
                        $busstop_list = $busstop_list.",".$busstop_id.":".$busstop_name;
                    }
                    $i++;  
                  
                  }
								}
								
								echo'
								      <input type="hidden" name="busstops" id="busstops" value="'.$busstop_list.'">
								';
      
      }
		*/	
      function get_user_busstops_old($AccountNode,$account_id)
      {
        global $DbConnection;
        $query="SELECT busstop_id,busstop_name FROM busstop WHERE group_id='$account_id' AND status='1'";
				//echo $query;
        				$result=mysql_query($query,$DbConnection);
								$row_result=mysql_num_rows($result);
								if($row_result!=null)
      					{
      						while($row=mysql_fetch_object($result))
      						{							
									$busstop_id =$row->busstop_id;
									$busstop_name =$row->busstop_name;
                  echo'
                  <tr>
                  <td align="left">&nbsp;
        							<INPUT TYPE="checkbox"  name="busstop_id[]" VALUE="'.$busstop_id.'">
        						</td>
        						<td class=\'text\'>
        							&nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$busstop_id.')">'.$busstop_name.'</A>
        						</td>
        						<td class=\'text\' width="100" align="right">
        						    <select name="'.$busstop_id.'" id="'.$busstop_id.'" >
                          <option value="select">Serial</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          <option value="10">10</option>
                          <option value="11">11</option>
                          <option value="12">12</option>
                          <option value="13">13</option>
                          <option value="14">14</option>
                          <option value="15">15</option>
                          <option value="16">16</option>
                          <option value="17">17</option>
                          <option value="18">18</option>
                          <option value="19">19</option>
                          <option value="20">20</option>                           
                        </select>
        						</td>
                    </tr>';
                    }
								}
      
      }


 /*

			function get_user_vehicle($AccountNode,$account_id)
			{
				global $vehicleid;
				global $vehicle_cnt;
				global $td_cnt;
				global $DbConnection;
				if($AccountNode->data->AccountID==$account_id)
				{
					$td_cnt =0;
					for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
					{			    
						$vehicle_id = $AccountNode->data->VehicleID[$j];
						$vehicle_name = $AccountNode->data->VehicleName[$j];
						$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];
						if($vehicle_id!=null)
						{
							for($i=0;$i<$vehicle_cnt;$i++)
							{
								if($vehicleid[$i]==$vehicle_id)
								{
									break;
								}
							}			
							if($i>=$vehicle_cnt)
							{
								$vehicleid[$vehicle_cnt]=$vehicle_id;
								$vehicle_cnt++;
								$td_cnt++;
								$query="SELECT vehicle_id FROM route_assignment WHERE vehicle_id='$vehicle_id' AND status='1'";
								$result=mysql_query($query,$DbConnection);
								$num_rows=mysql_num_rows($result);
								if($num_rows==0)
								{							
									common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName);
								}
								if($td_cnt==3)
								{
									$td_cnt=0;
								}
							}
						}
					}
				}
				$ChildCount=$AccountNode->ChildCnt;
				for($i=0;$i<$ChildCount;$i++)
				{ 
					get_user_vehicle($AccountNode->child[$i],$account_id);
				}
			}
 */
			
?>  