<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	//echo "account_id_local=".$account_id_local."startdate=".$start_date;
	$account_id_local_1=$_POST['account_id_local'];
	$vehicle_id=$_POST['vehicleserial'];
	//$QueryConsignment="SELECT consignment_id FROM consignment_assignment WHERE vehicle_id='$vehicle_id' AND status=1";
	$QueryConsignment="SELECT consignment_id FROM consignment WHERE consignment_code='$consignment_code' AND status=1";
	//echo "QueryConsignment=".$QueryConsignment."<br>";
	$ConsignmentResult=mysql_query($QueryConsignment,$DbConnection);
	$RowConsignment=mysql_fetch_row($ConsignmentResult);
	//echo "RowConsignment=".$RowConsignment[0]."<br>";
	$QueryTripDetails="SELECT * FROM trip_details_moto WHERE consignment_id='$RowConsignment[0]'";
	$TripDetailsResult=mysql_query($QueryTripDetails,$DbConnection);
	$ObjectTripDetails=mysql_fetch_object($TripDetailsResult);
	$RowTripDetails=mysql_num_rows($TripDetailsResult);
?>
<html>
	<head>
		<title><?php echo $title; ?></title>
		<link rel="StyleSheet" href="../css/menu.css">
	</head>
<body>
<?php
if($RowTripDetails==0)
{	
echo "<br>
		<center>
			
			<font color='red'>
				<b>
					No Trip Performance Report For This Vehicle
				</b>
			</font>
		</center>";			
}
else
{
?>
<input type="hidden" id="common_display_id">
<table width="100%">
	<tr>
		<td align="center">
			<table align="center" class="menu" border="1" rules="all" bordercolor="black">
				<tr>					
					<td align="center" class="moto_exceptions">
						Trip Info
					</td>	
					<td colspan="3">
				
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left"> 
						From
					</td>
					<td colspan="3" align="left">
					<?php echo $ObjectTripDetails->from; ?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left">
						To
					</td>
					<td colspan="3" align="left">
					<?php echo $ObjectTripDetails->to; ?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Dispatch Date
					</td>
					<td align="left">
						<?php 
							$DispatchDate=explode(" ",$ObjectTripDetails->dispatch_date); 
							echo $DispatchDate[0];
						?>
					</td>
					<td align="left">
						Time
					</td>
					<td align="left">
					<?php echo $DispatchDate[1]; ?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Dispatch Band
					</td>
					<td colspan="3" align="left">
					<?php echo $ObjectTripDetails->dispatch_bond; ?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Reached At
					</td>
					<td align="left">
						<?php 
							$ReachedAt=explode(" ",$ObjectTripDetails->reached_at); 
							echo $ReachedAt[0];
						?>
					</td>
					<td align="left">
						Time
					</td>
					<td align="left">
					<?php echo $ReachedAt[1]; ?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Delivered At
					</td>
					<td align="left">
						<?php 
							$DeliveredAt=explode(" ",$ObjectTripDetails->delivered_at); 
							echo $DeliveredAt[0];
						?>
					</td>
					<td align="left" >
						Time
					</td>
					<td align="left">
					<?php echo $DeliveredAt[1]; ?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Unload Period
					</td>
					<td align="left">
						<?php 
							$UnloadPeriod=explode(",",$ObjectTripDetails->unload_period); 
							echo $UnloadPeriod[0];
						?>
					</td>
					<td align="left" colspan="2">
						Area
					</td>					
				</tr>
				<tr>					
					<td align="center" class="moto_exceptions"> 
						Trip Performance
					</td>
					<td align="left" colspan="3">
						
					</td>					
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						
					</td>
					<td align="left">
						Planned
					</td>
					<td align="left">
						Actual
					</td>
					<td align="left">
						Variance
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left">
						Trnasit Time
					</td>
					<td align="left">
						<?php 
							$TransitTime=explode(",",$ObjectTripDetails->transit_time); 
							echo $TransitTime[0];
						?>
					</td>
					<td align="left">
						<?php echo $TransitTime[1]; ?>
					</td>
					<td align="left">
						<?php echo $TransitTime[2]; ?>
					</td>
				</tr>				
				<tr class="moto_trip_performance">					
					<td align="left" >
						Trip Distance
					</td>
					<td align="left">
						<?php 
							$TripDistance=explode(",",$ObjectTripDetails->trip_distance); 
							echo $TripDistance[0];
						?>
					</td>
					<td align="left">
						<?php echo $TripDistance[1]; ?>
					</td>
					<td align="left">
						<?php echo $TripDistance[2]; ?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Transit Around Time (TAT)
					</td>
					<td align="left">
						<?php					
							echo $ObjectTripDetails->transit_around_time;
						?>
					</td>
					<td align="left">						
					</td>
					<td align="left">						
					</td>
				</tr>
				<tr>				
					<td align="left" colspan="4">
						&nbsp;
					</td>					
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Drive Time For Trip
					</td>
					<td align="left" colspan="3">
						<?php					
							echo $ObjectTripDetails->drive_time_for_trip;
						?>
					</td>				
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Stop Time For Trip
					</td>
					<td align="left" colspan="3">
						<?php					
							echo $ObjectTripDetails->stop_time_for_trip;
						?>
					</td>				
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Max Stop Location On Trip
					</td>
					<td align="left">
						<?php					
							$MSLOT=explode("#",$ObjectTripDetails->max_stop_location_on_trip);
							echo $MSLOT[0];
						?>
					</td>
					<td align="left" >
						Stop
					</td>
					<td align="left" colspan="3">
						<?php
							echo $MSLOT[1];
						?>
					</td>					
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Max Speed During Trip
					</td>
					<td align="left">
						<?php					
							$MSDT=explode(",",$ObjectTripDetails->max_speed_during_trip);
							echo $MSDT[0];
						?>
					</td>
					<td align="left" >
						Max Allowed Speed
					</td>
					<td align="left" colspan="3">
						<?php
							echo $MSDT[1];
						?>
					</td>					
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Avg Speed During Trip
					</td>
					<td align="left" colspan="3">
						<?php					
							echo $ObjectTripDetails->avg_speed_during_trip;
						?>
					</td>				
				</tr>
				<tr>					
					<td align="center" class="moto_exceptions">
						Safety Compliance
					</td>
					<td align="center" colspan="3">
						
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="center">
						Overspeed Instance
					</td>
					<td align="left" colspan="3">
						<?php					
							echo $ObjectTripDetails->overspeed_instances;
						?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="center">
						Night Drive Instances
					</td>
					<td align="left" colspan="3">
						<?php					
							echo $ObjectTripDetails->night_drive_instances;
						?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="center">
						Power Disconnect
					</td>
					<td align="left" colspan="3">
						<?php					
							echo $ObjectTripDetails->power_disconnect;
						?>
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="center">
						Free Wheeling
					</td>
					<td align="left" colspan="3">
						<?php					
							echo $ObjectTripDetails->free_wheeling;
						?>
					</td>
				</tr>				
				<tr>					
					<td align="center" class="moto_exceptions">
						Benchmark Across Carriers
					</td>
					<td align="center" colspan="3">
						
					</td>					
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Transit Time For This Route
					</td>
					<td align="left">
						<?php					
							$TTFR=explode(",",$ObjectTripDetails->transit_time_for_route);
							echo $TTFR[0];
						?>
					</td>
					<td align="left">
						<?php
							echo $TTFR[1];
						?>
					</td>
					<td align="left">
						<?php
							echo $TTFR[2];
						?>
					</td>										
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Transit Distance For This Route
					</td>
					<td align="left">
						<?php					
							$TDFR=explode(",",$ObjectTripDetails->transit_distance_for_route);
							echo $TDFR[0];
						?>
					</td>
					<td align="left">
						<?php
							echo $TDFR[1];
						?>
					</td>
					<td align="left">
						<?php
							echo $TDFR[2];
						?>
					</td>										
				</tr>
				<tr>					
					<td align="center" class="moto_exceptions">
						Historic Performance
					</td>
					<td align="center" colspan="3">
						
					</td>					
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						Number Of Trip Done So Far
					</td>
					<td align="left" colspan="3">
						<?php					
							echo $ObjectTripDetails->number_of_done_trip;							 
						?>
					</td>														
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" >
						By This Vehicle
					</td>
					<td align="left">
						<?php					
							$ByVehicle=explode(",",$ObjectTripDetails->by_vehicle);
							echo $ByVehicle[0];
						?>
					</td>
					<td align="left">
						This Month
					</td>
					<td align="left">
						<?php
							echo $ByVehicle[1];
						?>
					</td>										
				</tr>
				<tr>					
					<td align="center" class="moto_exceptions">
						Driver Counselling/Recommendations
					</td>	
					<td align="center" colspan="3">
				
					</td>
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" colspan="4">
						Avoid Over Speeding Instances
					</td>					
				</tr>
				<tr class="moto_trip_performance">					
					<td align="left" colspan="4">
						Avoid Night Drive
					</td>					
				</tr>
			</table>
		</td>
	</tr>
</table>
<?php
	}
?>
</body>
</html>