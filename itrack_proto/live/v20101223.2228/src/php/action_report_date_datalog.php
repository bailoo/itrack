<?php

	include_once("../SessionVariable.php");
	include("../PhpMysqlConnectivity.php");

	$size_suid=sizeof($suid);	
	$vname = $_POST['vehiclename'];
	$sizev = sizeof($vname);


	$vehicles="";
	for($j=0;$j<=$sizev-1;$j++)
	{				
		if($j!=$sizev-1)
		{
			$vehicles = $vehicles."'".$vname[$j]."'";	
			$vehicles=$vehicles.",";
		}
		else
		{		
			$vehicles = $vehicles."'".$vname[$j]."'";
		}
	}

	$startdate = $_POST['StartDate'];
	$enddate = $_POST['EndDate'];

	$start_date = str_replace("/","-",$startdate);	
	$end_date = str_replace("/","-",$enddate);

	$e=explode(" ",$end_date);
	$enddatetotaltime=strtotime($end_date);
	$e_d=explode("-",$e[0]);	

	$ed_d=$e_d[2]*86400;
	$e_d1=$enddatetotaltime-$ed_d;
	$e_d1=date('Y-m-d H:i:s',$e_d1);
	date_default_timezone_set('Asia/Calcutta');
	$e_d2=explode(" ",$e_d1);
	$e_d3=$e_d2[0]." "."23:59:59";
	$e_d4=explode("-",$e_d2[0]);

	$Current_Date_Time=date('Y/m/d H:i:s');
	$Current_Date_Only=explode(" ",$Current_Date_Time);				
	$c_d=explode("/",$Current_Date_Only[0]);

	$start_date1=explode(" ",$start_date);				
	$s_d=explode("-",$start_date1[0]);

	$sdt=$e_d[0]."-".$e_d[1]."-"."01"." "."00:00:00";	
	$sdt2=$s_d[0]."-"."12"."-"."31"." "."23:59:59";
	$endt1=$e_d4[0]."-"."01"."-"."01"." "."00:00:00";
	$radio_button = $_POST['rec'];
?>

<HTML>
<TITLE>Vehicle Tracking Pro-Innovative Embedded Systems Pvt. Ltd.</TITLE>
<head>
	<link rel="shortcut icon" href=".././Images/iesicon.ico" >
	<LINK REL="StyleSheet" HREF="../menu.css">
	<script type=text/javascript src="../menu.js"></script>
	<script language="javascript">
	</script>

<style type="text/css">
	
	div.scrollTableContainer
	{
		height: 477px;
		overflow: auto;
		width: 1016px;	
		position: relative;
	}

	/* The different widths below are due to the way the scroll bar is implamented */

	/* All browsers (but especially IE) -18*/
	div.scrollTableContainer table {
	width: 1016px;
	overflow: auto;
	height: 440px;
	overflow-x: hidden;

	}

	/* Modern browsers (but especially firefox ) */
	div.scrollTableContainer table {
	width: 1016px;
	overflow: auto;
	height: 418px;
	overflow-x: hidden;
	}

	/* Modern browsers (but especially firefox ) */
	 div.scrollTableContainer table>tbody {
	
	height: 440px;
	width: 1016px;
	overflow-x: hidden;
	}

	div.scrollTableContainer thead tr {
	position:relative;
	top: expression(offsetParent.scrollTop); /*IE5+ only*/
	/* fixes the header being over too far in IE, doesn’t seem to affect FF */
	left: 0px;
	}


	/* Modern browsers (but especially firefox ) */
	 div.scrollTableContainer table>tfoot {
	overflow: auto;

	overflow-x: hidden;
	}

	div.scrollTableContainer tfoot tr {
	position:relative;
	top: expression(offsetParent.scrollTop); /*IE5+ only*/
	/* fixes the header being over too far in IE, doesn’t seem to affect FF */
	left: 0px;
	}

	/*prevent Mozilla scrollbar from hiding cell content*/
	div.scrollTableContainer td:last-child {padding-right: 20px;}

</style>

<SCRIPT TYPE="text/javascript" LANGUAGE="javascript">
function waitPreloadPage() 
{ 
	if(document.getElementById)
	{
	document.getElementById('prepage').style.visibility='hidden';
	document.getElementById('prepage1').style.visibility='visible';
	}
	else
	{
		if (document.layers)
		{ 
		document.prepage.visibility = 'hidden';
		document.getElementById('prepage1').style.visibility='visible';
		}
		else
		{ 
		document.all.prepage.style.visibility = 'hidden';
		document.getElementById('prepage1').style.visibility='visible';
		}
	}
}
</SCRIPT>

</head>

<body bgcolor="white" onLoad="waitPreloadPage();">
	<DIV id="prepage" style="position:absolute; font-family:arial; font-size:16; left:0px; top:0px; background-color:white; layer-background-color:white; height:60%; width:100%;">
		<TABLE width=100%>
			<TR>
				<TD>
					<B>
						<font color="lightblue">
							Loading ... ... Please wait!
						</font>
					</B>
				</TD>
			</TR>
		</TABLE>
	</DIV>


	<?php
		include('user_datalog_menu.php');	
	?>

	
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<img src="header_main.png">
					</td>
				</tr>
			</table>

			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>								
				<?php
                                echo'
										<td height=10 class="header" align="center">
											<font color="black" size="2">
												<b>
													Vehicle
													Data Log Between Dates:
												</b>
											 </font>
													
											 <font color="green" size="2">
												 <b>
													'.$start_date.'
												</b>
											 </font>
										  
											 <font color="red" size="2">
												<b>
													and
												</b>
											 </font>
										  
											 <font color="green" size="2">												
												<b>
													'.$end_date.'
												</b>
											 </font>
										  </td>
									';
                ?>
		
						     </tr>
						</table>
						
						<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
								<TR>
									<TD>
										<img src="header_main.png">
									</td>
								</tr>
							</table>
		
		<!--<div STYLE=" height:<?php echo $height+70?>px; overflow:auto">-->	
<?php
	////////////////////////////////displaying those vehicle whose grace period is over///////////////////////////////
	date_default_timezone_set('Asia/Calcutta');
	$curr_dt=date('Y/m/d');
	$curr_time = strtotime($curr_dt);	//currentdate
	$Query_payment_status = "select BillRefNumber,DueDate,DeviceID from bill where Status='Unpaid' and UserID='$login'";
	$res_st = mysql_query($Query_payment_status,$DbConnection);
	$flag=1;
	$count=0;

	echo '<div align="center">';

	while($row_st = mysql_fetch_object($res_st))
	{
		$duedate = $row_st->DueDate;
		$deviceid = $row_st->DeviceID;
		$billrefno = $row_st->BillRefNumber; 	
		$duetime = strtotime($duedate);	//duedate	
		$diff = $duetime - $curr_time;		

		if(($diff>=0 && $diff<=518400) || $diff<=0)		//in seconds (between 1 week)
		{
			if($flag==1)
			{
				echo'<div style="color:darkgreen;width:400px;font-size:11px;"><blink><strong>Payment has not been made for the devices : </strong></blink></div>
				';
				$flag=0;
			}
			
			if($diff<=-518400)
			{
				$disable_deviceid[$count] = $deviceid;
				$count++;
			}		

			$query_dname = "select VehicleName from vehicle where VehicleID='$deviceid'";
			$res_dname = mysql_query($query_dname,$DbConnection);

			if($row_dname = mysql_fetch_object($res_dname))
			{
				$devicename = $row_dname->VehicleName;
				echo'<table align="center"><tr><td bgcolor="lightyellow"><font color="blue" size="1">'.$devicename.'&nbsp;&nbsp;<font color="red" size="1"> Bill RefNumber :&nbsp;'.$billrefno.'</font></td></tr><table>';
			}		
		}	
	}
	echo '</div>';
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>	 
		<?php                      
			if($access==1)
			{
				date_default_timezone_set('Asia/Calcutta');
				$EndDate=date('Y/m/d H:i:s');
				$EndDate = str_replace("/","-",$EndDate);
				$EDate = explode(" ",$EndDate);
				$EDate = $EDate[0].' '.'00:00:00';
				$query_vehicle = "select TableName from `table`";								
				$result_vehicle = mysql_query($query_vehicle,$DbConnection);
				$num12 = mysql_num_rows($result_vehicle);
				$num_table_ids=0;

				while($row1 = mysql_fetch_object($result_vehicle))
				{
					$tablename_1[$num_table_ids]=$row1->TableName;	
					$num_table_ids++;
				}
					for($i=0;$i<$num_table_ids;$i++)
					{
						if($c_d[0]==$e_d[0] && $c_d[0]==$s_d[0] && $c_d[1]==$s_d[1] && $c_d[1]==$e_d[1]) 
						{
							if($i==0)
							{
								if($login=="demouser")
								{
									include("../custom_users.php");
									$Query3="Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.UserID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$startdate' and '$enddate' and vehicle.VehicleName IN($vehicles)";
								}
								else
								{
									$Query3="Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE vehicle.UserID='$login' and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$startdate' and '$enddate' and vehicle.VehicleName IN($vehicles)";
								}
							}
							else
							{
								if($login=="demouser")
								{
									include("../custom_users.php");
									$Query3 = $Query3." UNION Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$startdate' and '$enddate' and vehicle.VehicleName IN($vehicles)";
								}
								else
								{
									$Query3 = $Query3." UNION Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE vehicle.UserID='$login' and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$startdate' and '$enddate' and vehicle.VehicleName IN($vehicles)";
								}
							}
						}
						else
						{
								if($c_d[0]==$s_d[0] && $c_d[0]==$e_d[0] && $c_d[1]==$e_d[1])
								{									
									$yearly_table=$tablename_1[$i]."_".$s_d[0];
									if($i==0)
									{
										if($login=="demouser")
										{
											include("../custom_users.php");
											$Query3="Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table.ServerTS as ST,$yearly_table.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table.MsgType,$yearly_table.SendMode,$yearly_table.Latitude,$yearly_table.Longitude,$yearly_table.Altitude,$yearly_table.Speed,$yearly_table.Fix,$yearly_table.Signal_Strength,$yearly_table.No_Of_Satellites,$yearly_table.CBC,$yearly_table.CellName,$yearly_table.min_speed,$yearly_table.max_speed,$yearly_table.distance,$yearly_table.Last_Data from $yearly_table,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table.VehicleID=vehicle.VehicleID and $yearly_table.DateTime between '$start_date' and '$e_d3' and vehicle.VehicleName IN($vehicles)";
										}
										else
										{
											$Query3="Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE vehicle.UserID='$login' and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table.ServerTS as ST,$yearly_table.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table.MsgType,$yearly_table.SendMode,$yearly_table.Latitude,$yearly_table.Longitude,$yearly_table.Altitude,$yearly_table.Speed,$yearly_table.Fix,$yearly_table.Signal_Strength,$yearly_table.No_Of_Satellites,$yearly_table.CBC,$yearly_table.CellName,$yearly_table.min_speed,$yearly_table.max_speed,$yearly_table.distance,$yearly_table.Last_Data from $yearly_table,vehicle WHERE vehicle.UserID='$login' and $yearly_table.VehicleID=vehicle.VehicleID and $yearly_table.DateTime between '$start_date' and '$e_d3' and vehicle.VehicleName IN($vehicles)";
										}
									}
									else
									{
										if($login=="demouser")
										{
											include("../custom_users.php");
											$Query3 = $Query3." UNION All Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table.ServerTS as ST,$yearly_table.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table.MsgType,$yearly_table.SendMode,$yearly_table.Latitude,$yearly_table.Longitude,$yearly_table.Altitude,$yearly_table.Speed,$yearly_table.Fix,$yearly_table.Signal_Strength,$yearly_table.No_Of_Satellites,$yearly_table.CBC,$yearly_table.CellName,$yearly_table.min_speed,$yearly_table.max_speed,$yearly_table.distance,$yearly_table.Last_Data from $yearly_table,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table.VehicleID=vehicle.VehicleID and $yearly_table.DateTime between '$start_date' and '$e_d3' and vehicle.VehicleName IN($vehicles)";
										}
										else
										{
											$Query3 = $Query3." UNION All Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE vehicle.UserID='$login' and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table.ServerTS as ST,$yearly_table.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table.MsgType,$yearly_table.SendMode,$yearly_table.Latitude,$yearly_table.Longitude,$yearly_table.Altitude,$yearly_table.Speed,$yearly_table.Fix,$yearly_table.Signal_Strength,$yearly_table.No_Of_Satellites,$yearly_table.CBC,$yearly_table.CellName,$yearly_table.min_speed,$yearly_table.max_speed,$yearly_table.distance,$yearly_table.Last_Data from $yearly_table,vehicle WHERE vehicle.UserID='$login' and $yearly_table.VehicleID=vehicle.VehicleID and $yearly_table.DateTime between '$start_date' and '$e_d3' and vehicle.VehicleName IN($vehicles)";
										}
									}
								}
								else if($c_d[0]!=$s_d[0] && $c_d[0]==$e_d[0] && $c_d[1]==$e_d[1])
								{
									$yearly_table1=$tablename_1[$i]."_".$e_d4[0];
									$yearly_table2=$tablename_1[$i]."_".$s_d[0];
									if($i==0)
									{
										if($login=="demouser")
										{
											include("../custom_users.php");										
											$Query3="Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table1.ServerTS as ST,$yearly_table1.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table1.MsgType,$yearly_table1.SendMode,$yearly_table1.Latitude,$yearly_table1.Longitude,$yearly_table1.Altitude,$yearly_table1.Speed,$yearly_table1.Fix,$yearly_table1.Signal_Strength,$yearly_table1.No_Of_Satellites,$yearly_table1.CBC,$yearly_table1.CellName,$yearly_table1.min_speed,$yearly_table1.max_speed,$yearly_table1.distance,$yearly_table1.Last_Data from $yearly_table1,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table1.VehicleID=vehicle.VehicleID and $yearly_table1.DateTime between '$endt1' and '$e_d3' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table2.ServerTS as ST,$yearly_table2.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table2.MsgType,$yearly_table2.SendMode,$yearly_table2.Latitude,$yearly_table2.Longitude,$yearly_table2.Altitude,$yearly_table2.Speed,$yearly_table2.Fix,$yearly_table2.Signal_Strength,$yearly_table2.No_Of_Satellites,$yearly_table2.CBC,$yearly_table2.CellName,$yearly_table2.min_speed,$yearly_table2.max_speed,$yearly_table2.distance,$yearly_table2.Last_Data from $yearly_table2,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table2.VehicleID=vehicle.VehicleID and $yearly_table2.DateTime between '$start_date' and '$sdt2' and vehicle.VehicleName IN($vehicles)";
										}
										else
										{
											$Query3="Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE vehicle.UserID='$login' and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table1.ServerTS as ST,$yearly_table1.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table1.MsgType,$yearly_table1.SendMode,$yearly_table1.Latitude,$yearly_table1.Longitude,$yearly_table1.Altitude,$yearly_table1.Speed,$yearly_table1.Fix,$yearly_table1.Signal_Strength,$yearly_table1.No_Of_Satellites,$yearly_table1.CBC,$yearly_table1.CellName,$yearly_table1.min_speed,$yearly_table1.max_speed,$yearly_table1.distance,$yearly_table1.Last_Data from $yearly_table1,vehicle WHERE vehicle.UserID='$login' and $yearly_table1.VehicleID=vehicle.VehicleID and $yearly_table1.DateTime between '$endt1' and '$e_d3' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table2.ServerTS as ST,$yearly_table2.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table2.MsgType,$yearly_table2.SendMode,$yearly_table2.Latitude,$yearly_table2.Longitude,$yearly_table2.Altitude,$yearly_table2.Speed,$yearly_table2.Fix,$yearly_table2.Signal_Strength,$yearly_table2.No_Of_Satellites,$yearly_table2.CBC,$yearly_table2.CellName,$yearly_table2.min_speed,$yearly_table2.max_speed,$yearly_table2.distance,$yearly_table2.Last_Data from $yearly_table2,vehicle WHERE vehicle.UserID='$login' and $yearly_table2.VehicleID=vehicle.VehicleID and $yearly_table2.DateTime between '$start_date' and '$sdt2' and vehicle.VehicleName IN($vehicles)";
										}
									}
									else
									{
										if($login=="demouser")
										{
											include("../custom_users.php");
											$Query3 = $Query3." UNION All Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table1.ServerTS as ST,$yearly_table1.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table1.MsgType,$yearly_table1.SendMode,$yearly_table1.Latitude,$yearly_table1.Longitude,$yearly_table1.Altitude,$yearly_table1.Speed,$yearly_table1.Fix,$yearly_table1.Signal_Strength,$yearly_table1.No_Of_Satellites,$yearly_table1.CBC,$yearly_table1.CellName,$yearly_table1.min_speed,$yearly_table1.max_speed,$yearly_table1.distance,$yearly_table1.Last_Data from $yearly_table1,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table1.VehicleID=vehicle.VehicleID and $yearly_table1.DateTime between '$endt1' and '$e_d3' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table2.ServerTS as ST,$yearly_table2.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table2.MsgType,$yearly_table2.SendMode,$yearly_table2.Latitude,$yearly_table2.Longitude,$yearly_table2.Altitude,$yearly_table2.Speed,$yearly_table2.Fix,$yearly_table2.Signal_Strength,$yearly_table2.No_Of_Satellites,$yearly_table2.CBC,$yearly_table2.CellName,$yearly_table2.min_speed,$yearly_table2.max_speed,$yearly_table2.distance,$yearly_table2.Last_Data from $yearly_table2,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table2.VehicleID=vehicle.VehicleID and $yearly_table2.DateTime between '$start_date' and '$sdt2' and vehicle.VehicleName IN($vehicles)";
										}
										else
										{
											$Query3 = $Query3." UNION All Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].min_speed,$tablename_1[$i].max_speed,$tablename_1[$i].distance,$tablename_1[$i].Last_Data from $tablename_1[$i],vehicle WHERE vehicle.UserID='$login' and $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table1.ServerTS as ST,$yearly_table1.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table1.MsgType,$yearly_table1.SendMode,$yearly_table1.Latitude,$yearly_table1.Longitude,$yearly_table1.Altitude,$yearly_table1.Speed,$yearly_table1.Fix,$yearly_table1.Signal_Strength,$yearly_table1.No_Of_Satellites,$yearly_table1.CBC,$yearly_table1.CellName,$yearly_table1.min_speed,$yearly_table1.max_speed,$yearly_table1.distance,$yearly_table1.Last_Data from $yearly_table1,vehicle WHERE vehicle.UserID='$login' and $yearly_table1.VehicleID=vehicle.VehicleID and $yearly_table1.DateTime between '$endt1' and '$e_d3' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table2.ServerTS as ST,$yearly_table2.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table2.MsgType,$yearly_table2.SendMode,$yearly_table2.Latitude,$yearly_table2.Longitude,$yearly_table2.Altitude,$yearly_table2.Speed,$yearly_table2.Fix,$yearly_table2.Signal_Strength,$yearly_table2.No_Of_Satellites,$yearly_table2.CBC,$yearly_table2.CellName,$yearly_table2.min_speed,$yearly_table2.max_speed,$yearly_table2.distance,$yearly_table2.Last_Data from $yearly_table2,vehicle WHERE vehicle.UserID='$login' and $yearly_table2.VehicleID=vehicle.VehicleID and $yearly_table2.DateTime between '$start_date' and '$sdt2' and vehicle.VehicleName IN($vehicles)";
										}
									}
								}
								else if($s_d[0]!=$e_d[0])
								{
									$yearly_table1=$tablename_1[$i]."_".$s_d[0];
									$yearly_table2=$tablename_1[$i]."_".$e_d[0];
									if($i==0)
									{	
										if($login=="demouser")
										{
											include("../custom_users.php");											
											$Query3="Select $yearly_table1.ServerTS as ST,$yearly_table1.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table1.MsgType,$yearly_table1.SendMode,$yearly_table1.Latitude,$yearly_table1.Longitude,$yearly_table1.Altitude,$yearly_table1.Speed,$yearly_table1.Fix,$yearly_table1.Signal_Strength,$yearly_table1.No_Of_Satellites,$yearly_table1.CBC,$yearly_table1.CellName,$yearly_table1.min_speed,$yearly_table1.max_speed,$yearly_table1.distance,$yearly_table1.Last_Data from $yearly_table1,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table1.VehicleID=vehicle.VehicleID and $yearly_table1.DateTime between '$start_date' and '$sdt2' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table2.ServerTS as ST,$yearly_table2.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table2.MsgType,$yearly_table2.SendMode,$yearly_table2.Latitude,$yearly_table2.Longitude,$yearly_table2.Altitude,$yearly_table2.Speed,$yearly_table2.Fix,$yearly_table2.Signal_Strength,$yearly_table2.No_Of_Satellites,$yearly_table2.CBC,$yearly_table2.CellName,$yearly_table2.min_speed,$yearly_table2.max_speed,$yearly_table2.distance,$yearly_table2.Last_Data from $yearly_table2,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table2.VehicleID=vehicle.VehicleID and $yearly_table2.DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
										}
										else
										{
											$Query3="Select $yearly_table1.ServerTS as ST,$yearly_table1.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table1.MsgType,$yearly_table1.SendMode,$yearly_table1.Latitude,$yearly_table1.Longitude,$yearly_table1.Altitude,$yearly_table1.Speed,$yearly_table1.Fix,$yearly_table1.Signal_Strength,$yearly_table1.No_Of_Satellites,$yearly_table1.CBC,$yearly_table1.CellName,$yearly_table1.min_speed,$yearly_table1.max_speed,$yearly_table1.distance,$yearly_table1.Last_Data from $yearly_table1,vehicle WHERE vehicle.UserID='$login' and $yearly_table1.VehicleID=vehicle.VehicleID and $yearly_table1.DateTime between '$start_date' and '$sdt2' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table2.ServerTS as ST,$yearly_table2.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table2.MsgType,$yearly_table2.SendMode,$yearly_table2.Latitude,$yearly_table2.Longitude,$yearly_table2.Altitude,$yearly_table2.Speed,$yearly_table2.Fix,$yearly_table2.Signal_Strength,$yearly_table2.No_Of_Satellites,$yearly_table2.CBC,$yearly_table2.CellName,$yearly_table2.min_speed,$yearly_table2.max_speed,$yearly_table2.distance,$yearly_table2.Last_Data from $yearly_table2,vehicle WHERE vehicle.UserID='$login' and $yearly_table2.VehicleID=vehicle.VehicleID and $yearly_table2.DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
										}

									}
									else
									{
										if($login=="demouser")
										{
											include("../custom_users.php");
											$Query3=$Query3." UNION All Select $yearly_table1.ServerTS as ST,$yearly_table1.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table1.MsgType,$yearly_table1.SendMode,$yearly_table1.Latitude,$yearly_table1.Longitude,$yearly_table1.Altitude,$yearly_table1.Speed,$yearly_table1.Fix,$yearly_table1.Signal_Strength,$yearly_table1.No_Of_Satellites,$yearly_table1.CBC,$yearly_table1.CellName,$yearly_table1.min_speed,$yearly_table1.max_speed,$yearly_table1.distance,$yearly_table1.Last_Data from $yearly_table1,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table1.VehicleID=vehicle.VehicleID and $yearly_table1.DateTime between '$start_date' and '$sdt2' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table2.ServerTS as ST,$yearly_table2.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table2.MsgType,$yearly_table2.SendMode,$yearly_table2.Latitude,$yearly_table2.Longitude,$yearly_table2.Altitude,$yearly_table2.Speed,$yearly_table2.Fix,$yearly_table2.Signal_Strength,$yearly_table2.No_Of_Satellites,$yearly_table2.CBC,$yearly_table2.CellName,$yearly_table2.min_speed,$yearly_table2.max_speed,$yearly_table2.distance,$yearly_table2.Last_Data from $yearly_table2,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table2.VehicleID=vehicle.VehicleID and $yearly_table2.DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
										}
										else
										{
											$Query3=$Query3." UNION All Select $yearly_table1.ServerTS as ST,$yearly_table1.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table1.MsgType,$yearly_table1.SendMode,$yearly_table1.Latitude,$yearly_table1.Longitude,$yearly_table1.Altitude,$yearly_table1.Speed,$yearly_table1.Fix,$yearly_table1.Signal_Strength,$yearly_table1.No_Of_Satellites,$yearly_table1.CBC,$yearly_table1.CellName,$yearly_table1.min_speed,$yearly_table1.max_speed,$yearly_table1.distance,$yearly_table1.Last_Data from $yearly_table1,vehicle WHERE vehicle.UserID='$login' and $yearly_table1.VehicleID=vehicle.VehicleID and $yearly_table1.DateTime between '$start_date' and '$sdt2' and vehicle.VehicleName IN($vehicles)";
											$Query3=$Query3." UNION All Select $yearly_table2.ServerTS as ST,$yearly_table2.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table2.MsgType,$yearly_table2.SendMode,$yearly_table2.Latitude,$yearly_table2.Longitude,$yearly_table2.Altitude,$yearly_table2.Speed,$yearly_table2.Fix,$yearly_table2.Signal_Strength,$yearly_table2.No_Of_Satellites,$yearly_table2.CBC,$yearly_table2.CellName,$yearly_table2.min_speed,$yearly_table2.max_speed,$yearly_table2.distance,$yearly_table2.Last_Data from $yearly_table2,vehicle WHERE vehicle.UserID='$login' and $yearly_table2.VehicleID=vehicle.VehicleID and $yearly_table2.DateTime between '$sdt' and '$end_date' and vehicle.VehicleName IN($vehicles)";
										}
									}
								}
								else if($s_d[0]==$e_d[0])
								{
									$yearly_table=$tablename_1[$i]."_".$s_d[0];
									if($i==0)
									{
										if($login=="demouser")
										{
											include("../custom_users.php");
											$Query3="Select $yearly_table.ServerTS as ST,$yearly_table.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table.MsgType,$yearly_table.SendMode,$yearly_table.Latitude,$yearly_table.Longitude,$yearly_table.Altitude,$yearly_table.Speed,$yearly_table.Fix,$yearly_table.Signal_Strength,$yearly_table.No_Of_Satellites,$yearly_table.CBC,$yearly_table.CellName,$yearly_table.min_speed,$yearly_table.max_speed,$yearly_table.distance,$yearly_table.Last_Data from $yearly_table,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table.VehicleID=vehicle.VehicleID and $yearly_table.DateTime between '$start_date' and '$end_date' and vehicle.VehicleName IN($vehicles)";
										}
										else
										{
											$Query3="Select $yearly_table.ServerTS as ST,$yearly_table.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table.MsgType,$yearly_table.SendMode,$yearly_table.Latitude,$yearly_table.Longitude,$yearly_table.Altitude,$yearly_table.Speed,$yearly_table.Fix,$yearly_table.Signal_Strength,$yearly_table.No_Of_Satellites,$yearly_table.CBC,$yearly_table.CellName,$yearly_table.min_speed,$yearly_table.max_speed,$yearly_table.distance,$yearly_table.Last_Data from $yearly_table,vehicle WHERE vehicle.UserID='$login' and $yearly_table.VehicleID=vehicle.VehicleID and $yearly_table.DateTime between '$start_date' and '$end_date' and vehicle.VehicleName IN($vehicles)";
										}
									}
									else
									{
										if($login=="demouser")
										{
											include("../custom_users.php");
											$Query3=$Query3." UNION All Select $yearly_table.ServerTS as ST,$yearly_table.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table.MsgType,$yearly_table.SendMode,$yearly_table.Latitude,$yearly_table.Longitude,$yearly_table.Altitude,$yearly_table.Speed,$yearly_table.Fix,$yearly_table.Signal_Strength,$yearly_table.No_Of_Satellites,$yearly_table.CBC,$yearly_table.CellName,$yearly_table.min_speed,$yearly_table.max_speed,$yearly_table.distance,$yearly_table.Last_Data from $yearly_table,vehicle WHERE (vehicle.UserID='$user1' OR vehicle.UserID='$user2') and $yearly_table.VehicleID=vehicle.VehicleID and $yearly_table.DateTime between '$start_date' and '$end_date' and vehicle.VehicleName IN($vehicles)";
										}
										else
										{
											$Query3=$Query3." UNION All Select $yearly_table.ServerTS as ST,$yearly_table.DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.VehicleSerial,$yearly_table.MsgType,$yearly_table.SendMode,$yearly_table.Latitude,$yearly_table.Longitude,$yearly_table.Altitude,$yearly_table.Speed,$yearly_table.Fix,$yearly_table.Signal_Strength,$yearly_table.No_Of_Satellites,$yearly_table.CBC,$yearly_table.CellName,$yearly_table.min_speed,$yearly_table.max_speed,$yearly_table.distance,$yearly_table.Last_Data from $yearly_table,vehicle WHERE vehicle.UserID='$login' and $yearly_table.VehicleID=vehicle.VehicleID and $yearly_table.DateTime between '$start_date' and '$end_date' and vehicle.VehicleName IN($vehicles)";
										}
									}
								}
							}
					}
					
					$Query3 = $Query3." ORDER BY ST DESC";														
					$QueryResult = mysql_query($Query3, $DbConnection);	

					//echo $Query3;
			}

								/*else if($access==-2)
								{	
									for($i=0;$i<$size_suid;$i++)
									{				
										if($i==0)
										$Query="select VehicleName,VehicleID,UserID from vehicle where UserID='$suid[$i]'";
										else
										$Query=$Query."  OR UserID='$suid[$i]'";
										//echo $Query;
									}
									$Result = mysql_query($Query,$DbConnection);
								}*/
								
								$i=0;
								while($row = mysql_fetch_object($QueryResult))
								{									
									$sno_1[$i]=$i+1;
									$ServerTS[$i] = $row->ST;
									
									if($radio_button == "gmt")
									{
										$DateTime[$i]=$row->DateTime;				
										$start_timestamp=strtotime($DateTime[$i]);
										$start_gmt_time=$start_timestamp-5400;	
									
										$gmt_start_date=date('Y-m-d H:i:s',$start_gmt_time);
										date_default_timezone_set('Asia/Calcutta');
										$DateTime1[$i]=$gmt_start_date;										
									}
									else if($radio_button == "ist")
									{
										$DateTime1[$i] = $row->DateTime;									
									}
										$VehicleID[$i] = $row->VehicleID;
										$VehicleName[$i] = $row->VehicleName;
										//$UserID[$i] = $row->UserID;
										$VehicleSerial[$i] = $row->VehicleSerial;
										$MsgType[$i] = $row->MsgType;
										$SendMode[$i] = $row->SendMode;
										$Latitude[$i] = $row->Latitude;
										$Longitude[$i] = $row->Longitude;
										$Altitude[$i] = $row->Altitude;
										$Speed[$i] = $row->Speed;
										$Fix[$i] = $row->Fix;
										$Signal_Strength[$i] = $row->Signal_Strength;
										$No_Of_Satellites[$i] = $row->No_Of_Satellites;
										$CBC[$i] = $row->CBC;
										$CellName[$i] = $row->CellName;
										$min_speed[$i] = $row->min_speed;
										$max_speed[$i] = $row->max_speed;
										//$Distance[$i] = $row->distance;
										//$lastdata = $row->Last_Data;	
										$i++;											
								}
									if($i)
									{
				echo'<div class="scrollTableContainer" id="prepage1" style="visibility:hidden;">';	
						echo'<table width="90%" border="1" cellpadding="0" cellspacing="0">
								  <thead>
									<tr bgcolor="#C9DDFF"> 
										<th class="text"><b><font size="1">SNo</font></b></th>			
										<th class="text"><b><font size="1">STS</font></b></th>';
										if($radio_button == "gmt")
										{
											echo'<th class="text"><b><font size="1">DateTime</font>(<font color="blue" size="1">GMT+4</font>)</b</th>';
										}
										else
										{
											echo'<th class="text"><b><font size="1">DateTime</font>(<font color="blue">GMT+4</font>)</b</th>';
										}
									    
									    echo'<th class="text"><b><font size="1">VID</font></b></th>	
											<th class="text"><b><font size="1">Veh Name</font></b></th>	
											<th class="text"><b><font size="1">veh Serial</font></b></th>	
											<th class="text"><b><font size="1">MsgTp</font></b></th>
											<th class="text"><b><font size="1">SendMode</font></b></th>
											<th class="text"><b><font size="1">Latitude</font></b></th>
											<th class="text"><b><font size="1">Longitude</font></b></th>
											<th class="text"><b><font size="1">Altitude</font></b></th>			
											<th class="text"><b><font size="1">Speed</font></b></th>
											
											<!--<th class="text"><b><font size="1">Fix</font></b></th>
											<th class="text"><b><font size="1">SgnlSt</font></b></th>
											<th class="text"><b><font size="1">NofSat</font></b></th>
											<th class="text"><b><font size="1">CBC</font></b></th>-->

											<th class="text"><b><font size="1">CellName</font></b></th>
											<th class="text"><b><font size="1">MinSpeed</font></b></th>
											<th class="text"><b><font size="1">MaxSpeed</font></b></th>

											<!--<th class="text">Distance</th>
												<th class="text">LD</th>-->			
									</tr>
								</thead>
							<tbody>';
										for($j=0;$j<$i;$j++)
										{
											$k=$j+1;
										if ($j%2==0)
										{
										echo'<tr bgcolor="#F7FCFF">';
										}										
										else 
										{
										echo'<tr bgcolor="#E8F6FF">';
										}
                                      
												echo'<td class="text">'.$k.'</td>';
											if($ServerTS[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$ServerTS[$j].'</td>';
											}

											if($DateTime1[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$DateTime1[$j].'</td>';
											}

											if($VehicleID[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td align="left" class="text">'.$VehicleID[$j].'</td>';
											}

											if($VehicleName[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$VehicleName[$j].'</font></td>';
											}
                                             
											if($VehicleSerial[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$VehicleSerial[$j].'</td>';
											}

											if($MsgType[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$MsgType[$j].'</td>';
											}

											if($SendMode[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$SendMode[$j].'</td>';
											}

											if($Latitude[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$Latitude[$j].'</td>';
											}
											
											if($Longitude[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$Longitude[$j].'</td>';
											}

											if($Altitude[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$Altitude[$j].'</td>';
											}

											if($Speed[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.round($Speed[$j],2).'</td>';
											}
											
											/*if($Fix[$j] == "")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
											echo'<td class="text"><font size="2">'.$Fix[$j].'</td>';
											}

                                            if($Signal_Strength[$j] == "")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
											echo'<td class="text"><font size="2">'.$Signal_Strength[$j].'</td>';
											}                                             
												
											if($No_Of_Satellites[$j] == "")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
											echo'<td class="text"><font size="2">'.$No_Of_Satellites[$j].'</td>';
											}
                                                                                    
                                            if($CBC[$j] == "")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
											echo'<td class="text"><font size="2">'.$CBC[$j].'</td>';
											}*/  
                                                
                                            if($CellName[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$CellName[$j].'</td>';
											} 
											
											if($min_speed[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$min_speed[$j].'</td>';
											}
                                              

											if($max_speed[$j] == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$max_speed[$j].'</td>';
											} 
											
								echo'</tr>';
												
										}
									}
									else
									{							
										print"<center><FONT color=\"black\" size=\"2\"><strong>No Data Found During This Date </strong></font></center>";
										echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=user_datalog_by_dates.php\">";
									}

						?>				</tbody>
									</table>					
								</div>
							 </TD>
						 </TR>
					</TABLE>
				</td>
			</tr>
		</TABLE>
	<?php
	mysql_close($DbConnection);
	?>
	</BODY>
</HTML>
