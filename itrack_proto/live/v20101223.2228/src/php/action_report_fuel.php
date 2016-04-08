<?php

include_once('SessionVariable.php');
include_once("PhpMysqlConnectivity.php");
$size_suid=sizeof($suid);
$vsize=count($vehicleid);
$vsize=$vsize-1;

$StartDate = $_POST['StartDate'];
$EndDate = $_POST['EndDate'];

$user_interval = $_POST['user_interval'];

//echo "vsize=".$vsize;
///////////////////////////////////////////

//$StartDate = '2010-06-24 08:00:00';
//$EndDate = '2010-06-24 15:00:00';

if($vsize)
{
	for($i=0;$i<$vsize;$i++)		//UPPER LOOP		
	{								//fetch vehicle name, tablename="t".tableid (by vehicleid)		
		$sum_distance = 0;

		$Query1="select * from vehicle where VehicleID='$vehicleid[$i]'";   
		$Result1=mysql_query($Query1,$DbConnection);
		$row1=mysql_fetch_object($Result1);
		$vehiclename[$i]=$row1->VehicleName;
		$fuel_voltage[$i] = $row1->FuelVoltage;
		$tank_capacity[$i] = $row1->TankCapacity;

		//$max_fuel_12v = 340;
		//$max_fuel_24v = 680;
		
		$max_fuel_5v = 567;	//
		$max_fuel_12v = 1360;	//FOR 12 BIT ADC STM32
		$max_fuel_24v = 2720;	//FOR 12 BIT ADC STM32

		$Query2="select TableID from vehicletable where VehicleID='$vehicleid[$i]'";	 
		$Result2=mysql_query($Query2,$DbConnection);
		$row=mysql_fetch_object($Result2);
		$tableid=$row->TableID;

		$tablename = "t".$tableid;

		//echo "lastdat=".$lastday;

		$Query3="select DateTime,IO_Value8 from ".$tablename." where VehicleID='$vehicleid[$i]' and DateTime between '$StartDate' and '$EndDate'";
		///////////////////

		//echo $Query3.'<br>';
		$Result3=@mysql_query($Query3,$DbConnection);

		$j=0;
		
		if(mysql_num_rows($Result3)>0)
		{			
			$sumfuel=0;
			
			while($row3=@mysql_fetch_object($Result3))
			{							
				//$fuel = $row3->Fuel;
				$fuel = $row3->IO_Value8;
				
				if($fuel<30)
				{
					$fuel = 0;
				}						
				//echo "<br>fuel_v=".$fuel_voltage[$i]." fuel=".$fuel;

				//if( strcmp($fuel_voltage[$i],"12v")==0)
				
				if((strcmp($fuel_voltage[$i],"5v")==0))
				{						
					//if($fuel == $max_fuel_12v)
					if($fuel >= $max_fuel_5v)
					{
						//$litres = "Tank Full";
						//$level = "100%";
						$litres = $tank_capacity[$i];
						$level = 100;
					}

					else
					{
						if($max_fuel_5v>0)
						{
							$litres = ($fuel * $tank_capacity[$i])/$max_fuel_5v;
							$level = ($fuel *100)/$max_fuel_5v;
						}
					}
				}				
				else if((strcmp($fuel_voltage[$i],"12v")==0))
				{						
					//if($fuel == $max_fuel_12v)
					if($fuel >= $max_fuel_12v)
					{
						//$litres = "Tank Full";
						//$level = "100%";
						$litres = $tank_capacity[$i];
						$level = 100;
					}

					else
					{
						if($max_fuel_12v>0)
						{
							$litres = ($fuel * $tank_capacity[$i])/$max_fuel_12v;
							$level = ($fuel *100)/$max_fuel_12v;
						}
					}
				}

				else if( strcmp($fuel_voltage[$i],"inv12v")==0)
				{
					//if($fuel == $max_fuel)
					if($fuel >= $max_fuel_12v)
					{
						$litres = 0;	//Empty
						$level = 0;		//Zero
					}

					else
					{
						if($max_fuel_12v>0)
						{
							$litres = $tank_capacity[$i] - (($fuel * $tank_capacity[$i])/$max_fuel_12v);
							$level = 100 - (($fuel *100)/$max_fuel_12v);
						}
					}							
				}

				//else if( strcmp($fuel_voltage[$i],"24v")==0)
				else if((strcmp($fuel_voltage[$i],"24v")==0))
				{
					//if($fuel == $max_fuel_24v)
					if($fuel >= $max_fuel_24v)
					{
						//$litres = "Tank Full";
						$litres = $tank_capacity[$i];
						$level = 100;
					}
					
					else
					{
						if($max_fuel_24v)
						{
							$litres = ($fuel * $tank_capacity[$i])/$max_fuel_24v;
							$level = ($fuel *100)/$max_fuel_24v;

							//$litres = $tank_capacity - (($fuel * $tank_capacity)/$max_fuel);
							//$level = 100 - (($fuel *100)/$max_fuel);
						}
					}
				}

				else if( strcmp($fuel_voltage[$i],"inv24v")==0)
				{
					//if($fuel == $max_fuel)
					if($fuel >= $max_fuel_24v)
					{
						$litres = 0;	//Empty
						$level = 0;		//Zero
					}

					else
					{
						if($max_fuel_24v>0)
						{
							$litres = $tank_capacity[$i] - (($fuel * $tank_capacity[$i])/$max_fuel_24v);
							$level = 100 - (($fuel *100)/$max_fuel_24v);
						}
					}							
				}

							
				//echo "<br>j=".$litres;
				$datetime[$i][$j] = $row3->DateTime;
				$fuel_litres[$i][$j] = round($litres,2);		
				$fuel_level[$i][$j] = round($level,2);	
				$j++;				

			}	// inner data loop j closed		
		}
		else 
		{
			$datetime[$i][$j] = "-";
			$fuel_litres[$i][$j] = '-';
			$fuel_level[$i][$j] = '-';			
		}

		$data_length[$i] = $j;
				
	}// vehicle loop i closed
} // for vehicle closed 

$dsize = $j;		//data size


/*else if($size_suid)
{
	for($i=0;$i<$size_suid;$i++)				//UPPER LOOP		
	{								//fetch vehicle name, tablename="t".tableid (by vehicleid)
		$sum_distance = 0;

		$Query1="select * from vehicle where VehicleID='$vehicleid[$i]'";   
		$Result1=mysql_query($Query1,$DbConnection);
		$row1=mysql_fetch_object($Result1);
		$vehiclename[$i]=$row1->VehicleName;
		$fuel_voltage[$i] = $row1->FuelVoltage;
		$tank_capacity[$i] = $row1->TankCapacity;

		$max_fuel_12v = 340;
		$max_fuel_24v = 680;

		$Query2="select TableID from vehicletable where VehicleID='$vehicleid[$i]'";	 
		$Result2=mysql_query($Query2,$DbConnection);
		$row=mysql_fetch_object($Result2);
		$tableid=$row->TableID;

		$tablename = "t".$tableid;

	//echo "lastdat=".$lastday;

		for($j=1;$j<=$lastday;$j++)					//fetch speed from tablename, find speed_sum & avg_speed
		{		
			if($j<=9)
				$Query4="select Fuel from ".$tablename." where VehicleID='$vehicleid[$i]' and DateTime between '".$year."-".$month."-0".$j."%'";
			else
				$Query4="select Fuel from ".$tablename." where VehicleID='$vehicleid[$i]' and DateTime Like '".$year."-".$month."-".$j."%'";
			/////////////

			//echo $Query4.'<br>';
			$Result4=@mysql_query($Query4,$DbConnection);


			if(mysql_num_rows($Result4)>0)
			{
				$sumfuel=0;
				
				while($row4=@mysql_fetch_object($Result4))
				{
					$fuel = $row4->Fuel;
							
					if( strcmp($fuel_voltage[$i],"12v")==0)
					{
						if($fuel == $max_fuel_12v)
						{
							$litres = "Tank Full";
							$level = "100%";
						}

						else
						{
							$litres = ($fuel * $tank_capacity[$i])/$max_fuel_12v;
							$level = ($fuel *100)/$max_fuel_12v;
						}
					}
					else if( strcmp($fuel_voltage[$i],"24v")==0)
					{
						if($fuel == $max_fuel_24v)
						{
							$litres = "Tank Full";
							$level = "100%";
						}
						
						else
						{
							$litres = ($fuel * $tank_capacity[$i])/$max_fuel_24v;
							$level = ($fuel *100)/$max_fuel_24v;
						}
					}
								
					//echo "<br>j=".$litres;

					$fuel_litres[$i][$j] = round($litres,2);		
					$fuel_level[$i][$j] = round($level,2);		
				}			
			}
			else 
			{
				$fuel_litres[$i][$j] = '-';
				$fuel_level[$i][$j] = '-';
			}
					
		} // j closed

	}//i closed
}*/

	
$m1=date('M',mktime(0,0,0,$month,1));
?>
<HTML>
<TITLE>Vehicle Tracking Pro-Innovative Embedded Systems Pvt. Ltd.</TITLE>
<head>
	<link rel="shortcut icon" href="./Images/iesicon.ico" >
	<LINK REL="StyleSheet" HREF="menu.css">

	<style type="text/css">
	@media print  { .noprint  { display: none; } }
	@media screen { .noscreen { display: none; } }
	</style>

	<script type=text/javascript src="menu.js"></script>
	<script language="javascript">
	</script>
</head>

<body bgcolor="white">
	<?php
		if($access=="0")
		{
		  include('menu.php');
		}
		else if($access=="1")
		{
			if($login=="demouser")
			{
				include('liveusermenu.php');
			}
			else
			{
				include('usermenu.php');
			}
		}
		else if($access=="-2" || $access=="Zone")
		{
		  include('usermenu.php');
		}

	?>
		<td STYLE="background-color:white;width:85%;" valign="top">
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<table border=0 width = 100% cellspacing=0 cellpadding=0>
							<tr>
								<td height=10 STYLE="background-color:#f0f7ff" class="text" align="center">Fuel Report</td>
							</tr>
						</table>
						<?php
							if($access=="0")
								include("set_height.php");
							else if($access=="1" || $access=="-2" || $access=="Zone")
								include("set_user_height.php");
						?>
							<br>
<?php
						echo '<center>';
						
						//echo "User_interval=".$user_interval;
						
						echo'
						<form method = "post" action="FuelReportAction.php" target="_self">

							<table align="center">
								<tr>
									<td class="text"></td>
									<td>&nbsp;</td>
									<td class="text"><b>From DateTime : ('.$StartDate.') and ('.$EndDate.')</td>
								</tr>
							</table>';

							echo'<br><SPAN STYLE="font-size: xx-small">Select Interval </SPAN><select name="user_interval" onChange="this.form.submit();">';
														
							if($user_interval=="all")
								echo '<option value="all" selected>All</option>';
							else
								echo '<option value="all">All</option>';
								
							if($user_interval=="10m")
								echo '<option value="10m" selected>10 mins</option>';
							else
								echo '<option value="10m">10 mins</option>';
								
							if($user_interval=="30m")
								echo '<option value="30m" selected>30 mins</option>';
							else
								echo '<option value="30m">30 mins</option>';								
								
							if($user_interval=="1h")
								echo '<option value="1h" selected>1 hr</option>';
							else
								echo '<option value="1h">1 hr</option>';

							if($user_interval=="2h")
								echo '<option value="2h" selected>2 hrs</option>';
							else
								echo '<option value="2h">2 hrs</option>';
														
							if($user_interval=="3h")
								echo '<option value="3h" selected>3 hrs</option>';
							else
								echo '<option value="3h">3 hrs</option>';
							
							if($user_interval=="4h")
								echo '<option value="4h" selected>4 hrs</option>';
							else
								echo '<option value="4h">4 hrs</option>';
							
							if($user_interval=="5h")
								echo '<option value="5h" selected>5 hrs</option>';
							else
								echo '<option value="5h">5 hrs</option>';
							
							if($user_interval=="6h")
								echo '<option value="6h" selected>6 hrs</option>';
							else
								echo '<option value="6h">6 hrs</option>';
							
							if($user_interval=="7h")
								echo '<option value="7h" selected>7 hrs</option>';
							else
								echo '<option value="7h">7 hrs</option>';
							
							if($user_interval=="8h")
								echo '<option value="8h" selected>8 hrs</option>';
							else
								echo '<option value="8h">8 hrs</option>';
							
							if($user_interval=="9h")
								echo '<option value="9h" selected>9 hrs</option>';
							else
								echo '<option value="9h">9 hrs</option>';

							if($user_interval=="10h")
								echo '<option value="10h" selected>10 hrs</option>';
							else
								echo '<option value="10h">10 hrs</option>';

							if($user_interval=="11h")
								echo '<option value="11h" selected>11 hrs</option>';
							else
								echo '<option value="11h">11 hrs</option>';

							if($user_interval=="12h")
								echo '<option value="12h" selected>12 hrs</option>';
							else
								echo '<option value="12h">12 hrs</option>';							


							echo'</select>&nbsp;<SPAN STYLE="font-size: xx-small"> mins/hrs</SPAN>';							
				 		  
						  echo'<br><table align="center" border="1" rules=all  width="40%" bordercolor="#FFFFFF"					cellspacing=0 cellpadding=4>
							<tr>
							 <td>	
							<div style="overflow: auto;height: 360px; width: 620px;" align="center">
							
							<table border=1  width="60%" bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=4>';

							if($user_interval=="all")
							{
								$interval = "0";
								$interval = (double)$interval;																																						
							}
							else if($user_interval=="10m")
							{
								$interval = "10";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60;	//In Seconds																
							}
							else if($user_interval=="30m")
							{
								$interval=="30";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60;	//In Seconds
							}
							else if($user_interval=="1h")
							{
								$interval=="1";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds								
							}
							else if($user_interval=="2h")
							{
								$interval=="2";
								//echo "<br>int0=".$interval;		
								$interval = (double)$interval;	
								//echo "<br>int1=".$interval;								
								$interval = (double)$user_interval*60*60;	//In Seconds	
								//echo "<br>int2=".$interval;
							}
							else if($user_interval=="3h")
							{
								$interval=="3";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
							else if($user_interval=="4h")
							{
								$interval=="4";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
							else if($user_interval=="5h")
							{
								$interval=="5";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
							else if($user_interval=="6h")
							{
								$interval=="6";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
							else if($user_interval=="7h")
							{
								$interval=="7";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
							else if($user_interval=="8h")
							{
								$interval=="8";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
							else if($user_interval=="9h")
							{
								$interval=="9";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
							else if($user_interval=="10h")
							{
								$interval=="10";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
							else if($user_interval=="11h")
							{
								$interval=="11";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
							else if($user_interval=="12h")
							{
								$interval=="12";
								$interval = (double)$interval;							
								$interval = (double)$user_interval*60*60;	//In Seconds							
							}
																				
							//echo "<br>FinalInterval=".$interval;
							
							for($i=0;$i<$vsize;$i++)
							{
								$sno=0;
								$k=0;
								
								echo'
								
								<br><br><div align="center" style="font-size:12px;"><strong>'.$vehiclename[$i].'</strong></div><br>

								<table border=1 width="90%" rules=all bordercolor="#689FFF" align="center" cellspacing=0 cellpadding=3>
								<tr>								
								<td class="text" align="left"><b>SNo</b></td>
								<td class="text" align="left"><b>DateTime </b></td>
								<!--<td class="text" align="left"><b>DateTime To</b></td>-->
								<td class="text" align="left"><b>Fuel litres</b></td>
								<td class="text" align="left"><b>Fuel level(%)</b></td>
								</tr>';

								
								if($data_length[$i]=="")
								{
									echo'<tr>
									<td class="text" align="left">-</td>
									<td class="text" align="left">-</td>
									<td class="text" align="left">-</td>
									<!--<td class="text" align="left">-</td>-->
									<td class="text" align="left">-</td>	
									</tr>';
								}
								else
								{								
									$m = 0;
									
									for($j=0;$j<$data_length[$i];$j++)
									{
										
										if($j==0)
										{
											$time1 = $datetime[$i][$j];
											$date_secs1 = strtotime($time1);
											//echo "<br>DateSec1 before=".$date_secs1." time_int=".$interval;
											$date_secs1 = (double)($date_secs1 + $interval);
											//echo "<br>DateSec1 after=".$date_secs1;
											$fuel_litres_prev = $fuel_litres[$i][$j];	
											$fuel_level_prev = $fuel_level[$i][$j];		
											
											$fuel_litres_start[$i] = $fuel_litres_prev;		// FOR TAKING OVERALL DIFF
											$fuel_level_start[$i] =	$fuel_level_prev;										
										}													
																				
										else
										{											
											$time2 = $datetime[$i][$j];											
											$date_secs2 = strtotime($time2);	

											//echo "<br>Time1=".$time1." date_secs2=".$date_secs2;	
											
											$speed_arr[$m++] = $speed[$i][$j];
											
											//echo "<br>Date1=".$date_secs1." Date2=".$date_secs2;
											//echo "time1 =".$time1." time2=".$time2." DIFF=".(double)($date_secs2 - $date_secs1);
											
											if($date_secs2 >= $date_secs1)
											{																														
												$sno++;
												
												echo'
												<tr>
												<td class="text" align="left">'.$sno.'</td>
												<td class="text" align="left">'.$time1.'</td>												
												<td class="text" align="left">'.$fuel_litres_prev.'</td>
												<td class="text" align="left">'.$fuel_level_prev.'</td>	
												</tr>
												';	
												
												//store values for pdf
												$datetime1[$i][$k] =  $time1;
												$datetime2[$i][$k] =  $datetime[$i][$j];
												$fuel_litres1[$i][$k] = $fuel_litres_prev;
												$fuel_level1[$i][$k] = $fuel_level_prev;

												$fuel_litres_end[$i] =  $fuel_litres[$i][$j];
												$fuel_level_end[$i] = $fuel_level[$i][$j];
												
												$k++;	
												
												//reassign time1
												$time1 = $datetime[$i][$j];
												$date_secs1 = strtotime($time1);
												$date_secs1 = (double)($date_secs1 + $interval);	

												$fuel_litres_prev = $fuel_litres[$i][$j];	
												$fuel_level_prev = $fuel_level[$i][$j];											
											}
										}
									}	
									$k_arr[$i] = $k;
								}							
								
								if($k_arr[$i]>0)
								{
									$total_litres = ($fuel_litres_start[$i] - $fuel_litres_end[$i]);
									$total_level = ($fuel_level_start[$i] - $fuel_level_end[$i]);
								}
								else
								{
									$total_litres =0;
									$total_level =0;
								}
								
								if($total_litres<=0)
								{
									$total_litres = "NA";
								}
								if($total_level<=0)
								{
									$total_level = "NA";
								}
								
								/*echo '<tr style="height:20px;background-color:lightgrey"><td class="text"><strong>Total<strong>&nbsp;</td>
								<td class="text" colspan="2">&nbsp;&nbsp;<strong>'.$StartDate.'&nbsp;&nbsp;-&nbsp;&nbsp;'.$EndDate.'</strong></td>								
								<td class="text"><font color="red"><strong>'.$total_litres.'</strong></font></td>
								<td class="text"><font color="red"><strong>'.$total_level.'</strong></font></td></tr>';
								echo'</tr>*/
								echo'</table>';
							}
							
							echo'</td></tr></table>';
							echo'</div></td></tr></table>';
							
							for($i=0;$i<$vsize;$i++)
							{
								//echo "<br>vid=".$vehicleid[$i];
								echo '<input type="hidden" name="vehicleid[]" value="'.$vehicleid[$i].'"/>';
							}
							echo '<input type="hidden" name="vehicleid[]" value="-"/>';
							
							echo '<input type="hidden" name="StartDate" value="'.$StartDate.'"/>';
							echo '<input type="hidden" name="EndDate" value="'.$EndDate.'"/>';								
							
							echo'</form>';

							echo'<form method = "post" action="getpdf4.php?size='.$vsize.'" target="_blank">';
							
							for($i=0;$i<$vsize;$i++)
							{
								$title=$vehiclename[$i].": Fuel Report(Litres) From DateTime : ".$StartDate."-".$EndDate;
								echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title[$i]\">";
								
								$sno=0;
								for($j=0;$j<$k_arr[$i];$j++)
								{
									//$k=$j-1;
									$sno++;
									$date1 = $datetime1[$i][$j];
									$date2 = $datetime2[$i][$j];
									$fuel1 = $fuel_litres1[$i][$j];
									$fuel2 = $fuel_level1[$i][$j];												
									
									echo"<input TYPE=\"hidden\" VALUE=\"$sno\" NAME=\"temp[$i][$j][SNo]\">";
									echo"<input TYPE=\"hidden\" VALUE=\"$date1\" NAME=\"temp[$i][$j][DateTime]\">";
									//echo"<input TYPE=\"hidden\" VALUE=\"$date2\" NAME=\"temp[$i][$j][DateTime To]\">";
									echo"<input TYPE=\"hidden\" VALUE=\"$fuel1\" NAME=\"temp[$i][$j][Fuel (litrs)]\">";
									echo"<input TYPE=\"hidden\" VALUE=\"$fuel2\" NAME=\"temp[$i][$j][Fuel level(%)]\">";																	
								}				

								echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][SNo]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][DateTime From]\">";
								//echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][DateTime To]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][Fuel (litrs)]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"\" NAME=\"temp[$i][$j][Fuel level(%)]\">";																	

								/*$m = $j+1;
								
								if($k_arr[$i]>0)
								{
									$total_litres = ($fuel_litres_start[$i] - $fuel_litres_end[$i]);
									$total_level = ($fuel_level_start[$i] - $fuel_level_end[$i]);
								}
								else
								{
									$total_litres = 0;
									$total_level = 0;
								}
								
								if($total_litres<=0)
								{
									$total_litres = "NA";
								}
								if($total_level<=0)
								{
									$total_level = "NA";
								}								

								echo"<input TYPE=\"hidden\" VALUE=\"Total\" NAME=\"temp[$i][$m][SNo]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$StartDate\" NAME=\"temp[$i][$m][DateTime From]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$EndDate\" NAME=\"temp[$i][$m][DateTime To]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$total_litres\" NAME=\"temp[$i][$m][Fuel (litrs)]\">";
								echo"<input TYPE=\"hidden\" VALUE=\"$total_level\" NAME=\"temp[$i][$m][Fuel level(%)]\">";*/																								
							}						
?>							 
					
						<table align="center">
							<tr>
								<td><input type="submit" value="Get PDF" class="noprint">&nbsp;<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;</td>
							</tr>
						</table>
					</form></center>
					 </TD>
				 </TR>
			</TABLE>
		</td>

	</tr>
</TABLE>
</BODY>
</HTML>