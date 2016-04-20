</td>
<td class="mystyle">
<form method="POST" action="customer_plant_home.php">
<input type="hidden" name="filter_mode" value="1">
<div style="background-color:lightgrey" align="center"><h3>Filter Customer Plant</h3></center></div>
<div align="left">
<?php
if($filter_mode ==1 && $customer=="") {
echo '<input type="checkbox" id="customer" name="customer" onclick="javascript:show_hide_option(\'customer\');">&nbsp;<strong>Customer</strong>';
} else {
echo '<div align="left"><input type="checkbox" id="customer" name="customer" checked onclick="javascript:show_hide_option(\'customer\');">&nbsp;<strong>Customer</strong>';
}

if($filter_mode ==1 && $plant=="") {
echo '&nbsp;&nbsp;&nbsp;<input type="checkbox" id="plant" name="plant" onclick="javascript:show_hide_option(\'plant\');">&nbsp;<strong>Plant</strong>';
} else {
echo '&nbsp;&nbsp;&nbsp;<input type="checkbox" id="plant" name="plant" checked onclick="javascript:show_hide_option(\'plant\');">&nbsp;<strong>Plant</strong>';
}
?>
</div>
<br>
<table align="left" width="100%">
		<tr><td colspan="2"></td></tr>

		<tr><td></td></tr>
		<?php
		if( ($filter_mode == "") || ($filter_mode ==1 && $customer=="on") ) {
			echo '
			<tr id="customer_row"><td><strong>Customer option</strong></td><td>
			
				<select id="customer_option" name="customer_option">
				<option value="All">Select filter type</option>';

					if($filter_mode ==1 && $customer_option!="") {
						echo '<option value="'.$customer_option.'" selected>'.$customer_option.'</option>';
					}
				echo '
				<option value="All">All</option>
				<option value="FS">FS</option>
				<option value="MS">MS</option>
				<option value="dISTT">dISTT</option>
				<option value="Dist">Dist</option>
				<option value="DMS">DMS</option>
				<option value="UPSC">UPSC</option>
				<option value="Institution">Institution</option>
				<option value="INS">INS</option>
					
				</select></td>
			</tr>';
		} else {
			echo '
			<tr id="customer_row" style="display:none;"><td><strong>Customer option</strong></td><td>			
				<select id="customer_option" name="customer_option">
				<option value="All">Select filter type</option>
				<option value="All">All</option>
				<option value="FS">FS</option>
				<option value="MS">MS</option>
				<option value="dISTT">dISTT</option>
				<option value="Dist">Dist</option>
				<option value="DMS">DMS</option>
				<option value="UPSC">UPSC</option>
				<option value="Institution">Institution</option>
				<option value="INS">INS</option>					
				</select></td>
			</tr>';		
		}
		
		if( ($filter_mode == "") || ($filter_mode ==1 && $plant=="on") ) {	
		
			echo '<tr id="plant_row"><td><strong>Plant option</strong></td><td>
				<select id="plant_option" name="plant_option">';

				if($filter_mode ==1 && $plant_option!="") {
					echo '<option value="'.$plant_option.'" selected>'.$plant_option.'</option>';
				}		
				echo '<option value="All">Select filter type</option>
				<option value="All">All</option>			
				
				</select>
				</td>
			</tr>';
		} else {
			echo '<tr id="plant_row" style="display:none;"><td><strong>Plant option</strong></td><td>
				<select id="plant_option" name="plant_option">
				<option value="All">Select filter type</option>
				<option value="All">All</option>				
				</select>
				</td>
			</tr>';
		}
		?>
		
</table>
<br><br><br><br>
<div align="left"><input type="Submit" value="Show List"></div>

<div align="left" style="background-color:lightgrey"><h4>Customer Plant List</h4></div>	
	
	
<div id="customer_plant_id" overflow="auto" style="border: 1px solid red; height:420px; width:250px; position:relative; overflow:auto">
<?php
/*for($i=0;$i<sizeof($customer_no);$i++) {
	
	echo '<a href="javascript:highlight_customer('.$customer_no[$i].','.$lat[$i].','.$lng[$i].');"><strong>'.$customer_no[$i].'&nbsp;(<font color=green>'.$customer_name[$i].'</font>)</strong></a><br><br>';	
}*/

?>
</div>

</form>

<script type="text/javascript">
	document.getElementById('customer_plant_id').innerHTML = document.getElementById('customer_list').value;
</script>

</td>
