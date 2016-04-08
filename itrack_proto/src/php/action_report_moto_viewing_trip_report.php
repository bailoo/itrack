<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	//echo "account_id_local=".$account_id_local."startdate=".$start_date;
	//echo "manage_id=".$manage_id."<br>";
?>
<html>
	<title>
		<?php echo $title; ?>
	</title>
	<head>
	<style>
	.headings
	{
		font-size: 9pt;	
		font-weight: bold;
		color:blue;
		text-align:center;
	}
	.main_tr
	{	
		text-align:left;
		background-color:#DEDBDE;
	}
	</style>	
	</head>
<body>	
<table width="100%" border=0>
	<tr>
		<td>
			<table align="center" width="95%" class="menu" border="1" rules="all" bordercolor="black" border=1>
				<tr onclick="javascript:show_hide_option('stationary_vehicles','main_tr_1')" class="main_tr">
					<td align="center">
						Vehicle Number
					</td>
					<td align="center">
						Consignee
					</td>
					<td align="center">
						Carrier
					</td>
					<td align="center" colspan="2">
						Distance (In Kmph)
					</td>
					<td align="center" colspan="3">
						Transit Time 
					</td>
					<td align="center" colspan="3">
						TAT
					</td>
					<td align="center" colspan="2">
						Stoppages On Trip
					</td>
					<td colspan="2"> 
						Over Speed (limit 50 km/Hr)
					</td>
				</tr>
				<tr class="main_tr" class="main_tr">
					<td align="center" colspan="3">
						
					</td>
					<td align="center">
						tmp str
					</td>
					<td align="center">
						GPS Distance
					</td>
					<td align="center">
						Planned
					</td>
					<td align="center">
						Actual
					</td>
					<td align="center">
						Variance
					</td>
					<td>
						Planned Date
					</td>
					<td>
						Actual Date
					</td>
					<td>
						Location On Planned
					</td>
					<td>
						Total
					</td>
					<td>
						Stop Location &
					</td>
					<td>
						Total Instancess
					</td>
					<td>
						Speed In Km/Hr
					</td>
				</tr>
				<tr>
					<td align="center">
						H123
					</td>
					<td align="center">
						dummy
					</td>
					<td align="center">
						ARVIND ROAD
					</td>
					<td align="center">
						2706
					</td>
					<td align="center">
						2719
					</td>
					<td align="center">
						9days
					</td>
					<td align="center">
						7days
					</td>
					<td>
						2days
					</td>
					<td>
						8aw899
					</td>
					<td>
						8aw899
					</td>
					<td>
						MP
					</td>
					<td>
						2days 20 hours
					</td>
					<td>
						2days 20 hours
					</td>
					<td>
						86
					</td>
					<td>
					77
					</td>
				</tr>
				<tr bgcolor="#BCCFE6">
					<td align="center">
						H123
					</td>
					<td align="center">
						dummy
					</td>
					<td align="center">
						ARVIND ROAD
					</td>
					<td align="center">
						2706
					</td>
					<td align="center">
						2719
					</td>
					<td align="center">
						9days
					</td>
					<td align="center">
						7days
					</td>
					<td>
						2days
					</td>
					<td>
						8aw899
					</td>
					<td>
						8aw899
					</td>
					<td>
						MP
					</td>
					<td>
						2days 20 hours
					</td>
					<td>
						2days 20 hours
					</td>
					<td>
						86
					</td>
					<td>
					77
					</td>
				</tr>				
			</table>
		</td>
	</tr>
</table>