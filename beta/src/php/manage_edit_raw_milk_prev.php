<?php 
	//echo "TEST";
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once("util_account_detail.php");
	
	//===Getting Plant 
	$final_plant_list_tmp=array();
        $final_plant_name_list_tmp=array();
	$final_plant_list_tmp[0]="";
        $final_plant_name_list_tmp[0]="";
	
	if($user_type=='plant_raw_milk' || $user_type=='plant_admin'){
echo'
	<center>	
	<table>
	<tr>
		<td align="center">';
	echo"&nbsp;&nbsp; 	<a href='src/php/manage_add_raw_milk_usertype_approve_1.php?pending=2' class='hs2' target='_blank'><font color=red><b>Show Pending Invoice</b></a>";
	echo'</td>
	
	<td align="center">';
	echo"|&nbsp;<a href='#' onclick='javascript:open_pending_tanker_view();' class='hs2'><font color=brown><b>Show Pending Tanker</b></a>";
	echo'</td>
	</tr>
	</table>
	</center>';
	
	
	global $parent_admin_id_tmp;
           /* $query_account_admin_id_tmp="SELECT account_admin_id FROM account_detail WHERE account_id='$account_id'";
            //echo $query_account_admin_id;
            $result_account_admin_id_tmp = mysql_query($query_account_admin_id_tmp, $DbConnection);
            $row_account_admin_id_tmp =mysql_fetch_object($result_account_admin_id_tmp);

          

            $query_admin_id_tmp="SELECT account_id FROM account_detail WHERE admin_id='$row_account_admin_id_tmp->account_admin_id'";
            //echo $query_admin_id;
            $result_admin_id_tmp = mysql_query($query_admin_id_tmp, $DbConnection);
            $row_admin_id_tmp =mysql_fetch_object($result_admin_id_tmp);
            $parent_admin_id_tmp=$row_admin_id_tmp->account_id;


            //plant
            //$query_plant = "SELECT customer_no,station_name FROM station WHERE type=1 AND user_account_id='$parent_admin_id_tmp' AND status=1";
            $query_plant = "SELECT station.customer_no,station.station_name ,plant_user_assignment.plant_customer_no FROM station,plant_user_assignment WHERE station.type=1 AND station.user_account_id='$parent_admin_id_tmp' AND  station.status=1 AND plant_user_assignment.plant_customer_no= station.customer_no and plant_user_assignment.account_id='$account_id' AND plant_user_assignment.status=1";
            //echo $query_plant;
            $result_query = mysql_query($query_plant,$DbConnection);
            while($row=mysql_fetch_object($result_query))
            {
                    //echo $row->customer_no;
                    $final_plant_list_tmp[]=$row->customer_no;
                    $final_plant_name_list_tmp[]=$row->station_name;
            }
              */  
             $account_admin_id_tmp =getAccountAdminId($account_id,$DbConnection);
             $parent_admin_id_tmp=getAccountIdByAdminId($account_admin_id_tmp,$DbConnection);
             $data_row=getDetailSelfChildCustomerNoStationNameStation($parent_admin_id_tmp,$account_id,$DbConnection);
             foreach($data_row as $row)
             {
                   $final_plant_list_tmp[]=$row['final_plant_list'];
                   $final_plant_name_list_tmp[]=$row['final_plant_name_list'];
             }
	}
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
		';
		if($user_type=='plant_raw_milk')
		{
			echo'<option value="7">TargetDate</option>';
		}
		echo'
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
	
  
