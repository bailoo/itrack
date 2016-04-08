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
		background-color:#83A9D3;
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
						LR Number
					</td>
					<td align="center">
						From
					</td>
					<td align="center">
						To
					</td>
					<td align="center">
						Carrier
					</td>
					<td align="center">
						Dispatch Date
					</td>
					<td align="center">
						ETA
					</td>
					<td align="center">
						Closure Date
					</td>
					<td>
					</td>
				</tr>
				<tr class="main_tr">
					<td align="center">
						Vehicle Number
					</td>
					<td align="center" colspan="3">
						Location 
					</td>
					<td align="center">
						Weather
					</td>
					<td align="center">
						Date
					</td>
					<td align="center">
						Speed (in Kmph)(Halt)
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td>
						H123
					</td>
					<td>
						MSIL Gurgaon
					</td>
					<td>
						KLT PVT
					</td>
					<td>
						Malik Logistics
					</td>
					<td>
						2013-03-25 10:10:10
					</td>
					<td>
						2013-03-28 10:10:10
					</td>
					<td> 
						0:(12:02:45)
					</td>
				</tr>							
			</table>
		</td>
	</tr>
</table>