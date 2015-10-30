<?php 
	//echo "TEST";
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	
	//===Getting Plant 
        $final_plant_list_tmp=array();
        $final_plant_name_list_tmp=array();
        $final_plant_list_tmp[0]="";
        $final_plant_name_list_tmp[0]="";
	//plant
	/*$query_plant = "SELECT customer_no,station_name FROM station WHERE type=1 AND user_account_id='$account_id' AND status=1";
	$result_query = mysql_query($query_plant,$DbConnection);
	while($row=mysql_fetch_object($result_query))
	{
		//echo $row->customer_no;
		$final_plant_list_tmp[]=$row->customer_no;
		$final_plant_name_list_tmp[]=$row->station_name;
	}*/
	$result_data_p=getDetailAllCustomerNoStationNameStation($account_id,$DbConnection);
        foreach($result_data_p as $row)
        {
            $final_plant_list_tmp[]=$row['final_plant_list'];
            $final_plant_name_list_tmp[]=$row['final_plant_name_list'];
        }
	echo"&nbsp;<a href='#' onclick='javascript:open_pending_tanker_view();' class='hs2'><font color=brown><b>Show Pending Tanker</b></font></a>";
	echo'
	<center>
	
	<fieldset class="manage_fieldset_invoice">
		<legend><strong>Select Date</strong></legend>
	<table>
	<tr>
		<td id="startdatefrom">StartDate :
			<input type="text" name="startdate" id="startdate" value="" placeholder="StartDate"  onclick="javascript:NewCal_SD(this.id,\'yyyymmdd\',true,24);" /></td>	
		
		<td id="enddateto">EndDate :
			<input type="text" name="enddate" id="enddate" placeholder="EndDate" onclick="javascript:NewCal(this.id,\'yyyymmdd\',true,24);"/>	
			
		 </td>
	<td>Select Order:</td>
	<td><select id="order" onchange="javascript:show_targetplantwise(this.value);">
		<option value="3">All</option>
		<option value="1">Open with Date</option>
		<option value="6">OpenAll</option>
		<option value="2">Closed</option>
		<option value="0">Cancelled</option>		
		<option value="5">Pending</option>
		<option value="7">TargetDate</option>
                <option value="8">Posting Date/Time</option>
	</select></td>
	
	<td style=display:none id="target_plant" >Select Plant:
		<select name="targetplant" id="targetplant" style="width:170px;">
			';
				$i=0; 
				foreach($final_plant_list_tmp as $plantlist){
					if($i==0){
						echo"<option value='0'>All</option>";
					}
					else
					{
					echo"<option value=".$plantlist." >".$final_plant_name_list_tmp[$i]."(".$plantlist.")</option>";
					}
					$i++;
				}
			echo'
		</select>
	</td>
	<td><input type="button" value="Enter" onclick="javascript:action_manage_invoice_update_prev(\'src/php/manage_edit_raw_milk_admin.php\');"></td>		 
	</tr>
</table>
				
</fieldset>

<div id="edit_div" style="display:none;"></div>
</center>';


//include_once('manage_loading_message.php');
?>
<link rel="StyleSheet" href="src/css/pager/bootstrap.css">	
	<link rel="StyleSheet" href="src/css/pager/dataTables.bootstrap.css">
	<script type="text/javascript" src="src/js/pager/jquery.dataTables.js"></script>
	<script type="text/javascript" src="src/js/pager/ZeroClipboard.js"></script>
	<script type="text/javascript" src="src/js/pager/TableTools.js"></script>
	<script type="text/javascript" src="src/js/pager/dataTables.bootstrap.js"></script>
	
	

		<div id="blackout_pending_tanker"> </div>	
		<div id="divpopup_pending_tanker" style="border: red 1px solid;">
	   
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="skyblue" >							
			<tr bgcolor=silver>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_pending_tanker_view()" class="hs3"><b>[X]</b></a></td> 													
			</tr> 
			<tr bgcolor=silver>
				<td colspan="5" valign="top" align="CENTER">MANAGE PENDING TANKER <div id="loading_pending_tanker"></div></td>
			</tr>							
		</table>
		
		<table width="100%" height="90%" border="0" cellpadding="0" cellspacing="0"  style="background-color:ghostwhite;" align="center" >							
			
		
			<tr>
				<td colspan="2" valign="top" >
				<div id="manage_pending_tanker_show">
				
					
				
				</div>
					
				</td>
			</tr>
		</table>
		
		<?php  ?>
		
	</div>
  
