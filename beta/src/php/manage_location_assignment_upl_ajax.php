 <?php
	$schedule_type=$_POST['schedule_type'];
	$account_id=$_POST['account_id'];
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$queryVillage="SELECT * FROM schedule_location_upl WHERE account_id='$account_id' AND status='1'";
	//echo "query=".$query."<br>";
	$resultVillage=mysql_query($queryVillage,$DbConnection);
	$row_resultVillage=mysql_num_rows($resultVillage);
	while($rowVillage=mysql_fetch_object($resultVillage))
	{
		$location_id[]=$rowVillage->location_id.":".$rowVillage->location_name;
		$location_name[]=$rowVillage->location_name;				
	}	
	$l=0;
	$cnt=0;
	$string_village="<table><tr><td colspan=5 align=left><input type=checkbox name='all' onclick='javascript:select_all_upl_village(this.form);' />SelectAll</td></tr><tr>";
	foreach($location_id as $lid)
	{
		if($cnt==5)
		{
			echo"</tr>
			<tr>";
			$cnt=0;
		}
		$string_village .= "<td><input type=checkbox value='$lid' id='upl_village[]' />".$location_name[$l]."</td>";
		$cnt++;
		$l++;
	}
	$string_village.="</tr></table>";
	
?>	
<link rel="stylesheet" href="src/css/jquery-ui.css">
<script src="src/js/jquery-1.js"></script>
<script src="src/js/jquery-ui.js"></script>
<input type="hidden" name="tnum"  id="tnum" value="0">
<?php
	
	if($schedule_type=='day')
	{
		echo'
		<fieldset>
		<legend>DayWise</legend>
		<table border="0" align=center class="menu" cellspacing="2" cellpadding="2"> 						
				<tr>
					<td>Date From</td>
					<td>&nbsp;:&nbsp;</td>
					<td>
						<input type="text" id="date1" value="" size="10" maxlength="19">
							<a href=javascript:NewCal_SD("date1","yyyymmdd",false,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>														
					</td>
					<td>
						<div style="width:8px"></div>
					</td>
					<td>Date to</td>
					<td>&nbsp;:&nbsp;</td>
					<td>
						<input type="text" id="date2" value="" size="10" maxlength="19">
							<a href=javascript:NewCal_SD("date2","yyyymmdd",false,24)>
								<img src="./images/cal.gif" width="16" height="16" border="0" alt="Pick a date">
							</a>												
					</td>
					<td>
						<div style="width:8px"></div>
					</td>
					
				</tr>
			</table>
			
		';
		
		?>
		
		<table  border="1" cellpadding="0" cellspacing="0" align="center" style="border-collapse: collapse"   width="470px" >
			<tr style="background-color:silver;">	
				<td style="width:30px;font-size: 11px" >SL</td>
				<td style="width:70px;font-size: 11px" >Day</td>
				<td style="width:300px;font-size: 11px">Villages</td>
				<td style="width:50px;font-size: 11px" >Action</td> 
			</tr>
			<tr>
				<td colspan="20">
					<table width="470px" >
						<tr>
							<td><input type="text" name="num" id="num:0" value="0" style="width:30px;" readonly />
								<input type="hidden" name="sno_id" id="sno_id:0" value=""  />
							</td>
							<td>
								<select name="day" id="day:0" style="width:70px;">
									<OPTION value="select">Select</OPTION>
									<OPTION value="1">Sunday</OPTION>
									<OPTION value="2">Monday</OPTION>
									<OPTION value="3">Tuesday</OPTION>
									<OPTION value="4">Wednesday</OPTION>
									<OPTION value="5">Thursday</OPTION>
									<OPTION value="6">Friday</OPTION>
									<OPTION value="7">Saturday</OPTION>
								</select>
							</td>
							<td><input type="text" name="village" id="village:0" value="" style="width:300px;" placeholder="VILLAGE" onclick="javascript:show_village_list_upl(this.id)"  readonly />
								<input type="hidden" name="village_id" id="village_id:0" value=""  />
							</td>
							<td><input type="button" style="width:70px;"  id="addfield1" onclick="javascript:addfield();" value="ADD"  /></td>
						</tr>
					</table>
				<span id="writeroot"></span>
			</td>
		</tr>
	</table>
	<div id="readroot" name="readroot" style="display:none;">
		<input name="num" id="num:" value="" style="width:30px;" readonly />
		<input type="hidden" name="sno_id" id="sno_id:" value=""  />
		<select name="day" id="day:" style="width:70px;">
			<OPTION value="select">Select</OPTION>
			<OPTION value="1">Sunday</OPTION>
			<OPTION value="2">Monday</OPTION>
			<OPTION value="3">Tuesday</OPTION>
			<OPTION value="4">Wednesday</OPTION>
			<OPTION value="5">Thursday</OPTION>
			<OPTION value="6">Friday</OPTION>
			<OPTION value="7">Saturday</OPTION>
		</select>			
		<input type="text" name="village" id="village:" value="" style="width:300px;" placeholder="VILLAGE" onclick="javascript:show_village_list_upl(this.id)" readonly />
		<input type="hidden" name="village_id" id="village_id:" value=""  />
		<input type="button" style="width:70px;" name="butt" id="butt:" value="" onclick="this.parentNode.parentNode.removeChild(this.parentNode);lessFieldswithUPL_day(this.id);" />
	</div>
	<input type="hidden" name="offset_sno_id" id="offset_sno_id"  />
	<input type="hidden" name="offset_day" id="offset_day"  />
	<input type="hidden" name="offset_village_id" id="offset_village_id"  />
	</fieldset>
		<?php
	}
	
	
	else if($schedule_type=='date')
	{
		//echo $string_village;	
						
		?>
		<fieldset>
		<legend>Datewise</legend>
		<table  border="1" cellpadding="0" cellspacing="0" align="center" style="border-collapse: collapse"   width="570px" >
			<tr style="background-color:silver;">	
				<td style="width:30px;font-size: 11px" >SL</td>
				<td style="width:90px;font-size: 11px" >DateFrom</td>
				<td style="width:90px;font-size: 11px" >DateTo</td>
				<td style="width:300px;font-size: 11px">Villages</td>
				<td style="width:50px;font-size: 11px" >Action</td> 
			</tr>
			<tr>
				<td colspan="20">
					<table width="570px" >
						<tr>
							<td><input type="text" name="num" id="num:0" value="0" style="width:30px;" readonly />
								<input type="hidden" name="sno_id" id="sno_id:0" value=""  />
							</td>
							<td><input type="text" name="datefrom" id="datefrom:0" value="" style="width:90px;" placeholder="DATE FROM" onclick="javascript:NewCal_SD(this.id,'yyyymmdd',false,24);"  readonly /></td>	
							<td><input type="text" name="dateto" id="dateto:0" value="" style="width:90px;" placeholder="DATE TO " onclick="javascript:NewCal_SD(this.id,'yyyymmdd',false,24);"  readonly /></td>	
							<td><input type="text" name="village" id="village:0" value="" style="width:300px;" placeholder="VILLAGE" onclick="javascript:show_village_list_upl(this.id)" readonly />
								<input type="hidden" name="village_id" id="village_id:0" value=""  />
							</td>
							<td><input type="button" style="width:70px;"  id="addfield1" onclick="javascript:addfield();" value="ADD"  /></td>
						</tr>
					</table>
				<span id="writeroot"></span>
			</td>
		</tr>
	</table>
	<div id="readroot" name="readroot" style="display:none;">
		<input name="num" id="num:" value="" style="width:30px;" readonly />
		<input type="hidden" name="sno_id" id="sno_id:" value=""  />
		<input type="text" name="datefrom" id="datefrom:" value="" style="width:90px;" placeholder="DATE FROM" onclick="javascript:NewCal_SD(this.id,'yyyymmdd',false,24);"  readonly />	
		<input type="text" name="dateto" id="dateto:" value="" style="width:90px;" placeholder="DATE TO " onclick="javascript:NewCal_SD(this.id,'yyyymmdd',false,24);"  readonly />	
										
		<input type="text" name="village" id="village:" value="" style="width:300px;" placeholder="VILLAGE" onclick="javascript:show_village_list_upl(this.id)" readonly />
		<input type="hidden" name="village_id" id="village_id:" value=""  />
		<input type="button" style="width:70px;" name="butt" id="butt:" value="" onclick="this.parentNode.parentNode.removeChild(this.parentNode);lessFieldswithUPL_date(this.id);" />
	</div>
	<input type="hidden" name="offset_sno_id" id="offset_sno_id"  />
	<input type="hidden" name="offset_datefrom" id="offset_datefrom"  />
	<input type="hidden" name="offset_dateto" id="offset_dateto"  />
	<input type="hidden" name="offset_village_id" id="offset_village_id"  />
	</fieldset>
		<?php
	
	}
?>


<div id="blackout"> </div>
	<div id="divpopup_plant">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="skyblue">							
			<tr>
				<td class="manage_interfarce" align="right"><a href="#" onclick="javascript:return close_village_list_upl()" class="hs3">Close</a></td> 													
			</tr> 
			<tr>
				<td colspan="5" valign="top" align="justify">ADD VILLAGES</td>
			</tr>							
		</table>
		<br>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" rules="all" style="background-color:ghostwhite;">							
			<tr>
				<td><?php echo $string_village; ?>	</td>
				
			</tr>
			<tr><td colspan="2">
					<input type="button" value="Add" onclick="javascript:close_village_list_upl();">
				</td></tr>
		</table>
		
	</div>
    <input type="hidden" id="tmp_serial"/>