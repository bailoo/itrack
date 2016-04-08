<?php
	include_once("../SessionVariable.php");
	include("../PhpMysqlConnectivity.php");
	$size_suid=sizeof($suid);
	$size1=sizeof($vehiclename);
	$size2=sizeof($vehicleserial);
	$size3=sizeof($phone);	

	$option = $_POST['option1'];
	if($option == 1)	
	{
		$vehiclename = $_POST['vehiclename'];
	}

	else if($option == 2)	
	{
		$vehicleserial = $_POST['vehicleserial'];
	}

	else if($option == 3)	
	{
		$userid = $_POST['userid'];
	}

	else if($option == 4)	
	{
		$phone = $_POST['phone'];
	}
?>

<HTML>
	<TITLE>
		Vehicle Tracking Pro-Innovative Embedded Systems Pvt. Ltd.
	</TITLE>
<head>
	<link rel="shortcut icon" href=".././Images/iesicon.ico" >
	<LINK REL="StyleSheet" HREF="../menu.css">
	<script type=text/javascript src="../menu.js"></script>
	<script language="javascript"></script>

	<style type="text/css">
		div.scrollTableContainer 
		{
			height: 510px;
			overflow: auto;
			width: 1016px;		
			position: relative;
		}

		/* The different widths below are due to the way the scroll bar is implamented */
		/* All browsers (but especially IE) -18*/

		div.scrollTableContainer table 
		{
		width: 1000px;
		overflow: auto;
		height: 440px;
		overflow-x: hidden;
		}

		/* Modern browsers (but especially firefox ) */

		div.scrollTableContainer table
		{
		width: 1016px;
		overflow: auto;
		height: 460px;
		overflow-x: hidden;
		
		}

		/* Modern browsers (but especially firefox ) */

		div.scrollTableContainer table>tbody 
		{		
		height: 492px;
		width: 1016px;
		overflow-x: hidden;
		}

		div.scrollTableContainer thead tr 
		{
		position:relative;
		top: expression(offsetParent.scrollTop);
		/*IE5+ only*/
		/* fixes the header being over too far in IE, doesn’t seem to affect FF */
		left: 0px;	
		}

		/* Modern browsers (but especially firefox ) */

		div.scrollTableContainer table>tfoot 
		{
		overflow: auto;
		overflow-x: hidden;
		}

		div.scrollTableContainer tfoot tr
		{
		position:relative;
		top: expression(offsetParent.scrollTop);
		/*IE5+ only*/
		/* fixes the header being over too far in IE, doesn’t seem to affect FF */
		left: 0px;
		}

		/*prevent Mozilla scrollbar from hiding cell content*/

		div.scrollTableContainer td:last-child 
		{			
			padding-right: 20px;
		}
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
	<DIV id="prepage" style="position:absolute; font-family:arial; font-size:16; left:0px; top:0px; background-color:white; layer-background-color:white; height:70%; width:100%;">
	<TABLE width=100%><TR><TD><B><font color="lightblue">Loading ... ... Please wait!</font></B></TD></TR></TABLE>
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

<?php
////////////////////////////////displaying those vehicle whose grace period is over///////////////////////////////
date_default_timezone_set('Asia/Calcutta');
$curr_dt=date('Y/m/d');
$curr_time = strtotime($curr_dt);	//currentdate

$Query_payment_status = "select BillRefNumber,DueDate,DeviceID from bill where Status='Unpaid' and UserID='$login'";
$res_st = mysql_query($Query_payment_status,$DbConnection);

//echo $Query_payment_status;

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
	//echo $diff;

	if(($diff>=0 && $diff<=518400) || $diff<=0)		//in seconds (between 1 week)
	{
		
		if($flag==1)
		{
			echo'<div style="color:darkgreen;width:400px;font-size:11px;"><blink><strong>Payment has not been made for the devices : </strong></blink></div>
			';
			$flag=0;
		}
		//$flag=0;

		//echo "diff=".$diff;
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
						 if($size1)
                        {
							echo'<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td class="text" align="center"> 
										<font color="red">
											<strong>
												Data Log By Device Name
											</strong>
										</font>
									</td>
								</tr>
							</table>

							<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
								<TR>
									<TD>
										<img src="header_main.png">
									</td>
								</tr>
							</table>';

							echo'<div class="scrollTableContainer" id="prepage1" style="visibility:hidden;">';	
							echo'<table width="100%" border="1" cellpadding="0" cellspacing="0">
							  <thead>
								<tr bgcolor="#C9DDFF"> 
									<th class="text"><b><font size="1">SNo</font></b></th>			
									<th class="text"><b><font size="1">STS</font></b></th>
									<th class="text"><b><font size="1">DateTime</font></b</th>
									<th class="text"><b><font size="1">VID</font></b></th>	
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
									<th class="text"><b><font size="1">NofSat</font></b></th>-->
									<th class="text"><b><font size="1">CBC</font></b></th>
									<th class="text"><b><font size="1">CellName</font></b></th>
									<th class="text"><b><font size="1">MinSpeed</font></b></th>
									<th class="text"><b><font size="1">MaxSpeed</font></b></th>
									<!--<th class="text">Distance</th>
									<th class="text">LD</th>-->			
								</tr>
								</thead>
								<tbody>';

							for($j=0;$j<$size1;$j++)
							{
                                $Query = "Select VehicleID from `vehicle` where VehicleName = '$vehiclename[$j]'";
                                $QueryResult = mysql_query($Query,$DbConnection);

                                if($row = mysql_fetch_object($QueryResult))
                                {
                                        $vehicleid = $row->VehicleID;
                                }

                                ////////get tablename ///////////								
								///////////////////////////////////////////////////////////////////////////////
                                $sql1 = "select TableID from `vehicletable` where VehicleID='$vehicleid'";
								
                                $res1 = mysql_query($sql1,$DbConnection);

                                if($row1 = mysql_fetch_object($res1))
                                {
                                $tableid = $row1->TableID;

                                }

                                $sql2 = "select TableName from `table` where TableID ='$tableid'";
                                $res2 = mysql_query($sql2,$DbConnection);

                                if($row2 = mysql_fetch_object($res2))
                                {
                                $tablename = $row2->TableName;
                                }
								///////////////////////////////////////////////
								//echo "rec=".$rec;
								if($rec == "30")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";									
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
								else if($rec == "100")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 100";									
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 100";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
								else if($rec == "all")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC";	$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
								else
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
                                /////////////////////////////////                             
                             
                                for($i=1;$i<=$numrows;$i++)
                                {
                                    $row = mysql_fetch_object($QueryResult);									
									$vid = $row->VehicleID;
									$server_ts=$row->ServerTS;
									$date_time=$row->DateTime;
									$msg_type=$row->MsgType;
									$send_mode=$row->SendMode;
									$latitude=$row->Latitude;
									$longitude=$row->Longitude;
									$altitued=$row->Altitude;
									$speed=$row->Speed;
									$fix=$row->Fix;
									$Signal_Strength=$row->Signal_Strength;
									$no_of_satellites=$row->No_Of_Satellites;
									$cbc=$row->CBC;
									$cell_name=$row->CellName;
									$min_speed=$row->min_speed;
									$max_speed=$row->max_speed;

                                        $Query2 = "select VehicleName,VehicleSerial from vehicle where VehicleID='$vid'";
                                        $Result = mysql_query($Query2,$DbConnection);
                                        if($row3 = mysql_fetch_object($Result))
                                        {
                                                $vname = $row3->VehicleName;
                                                $vserial = $row3->VehicleSerial;
                                        }
											if ($i%2==0)
											{
												echo '<tr bgcolor="#F7FCFF">';
											}										
											else 
											{
											   echo '<tr bgcolor="#E8F6FF">';
											}
                                      
											echo'<td class="text">'.$i.'</td>';
											if($server_ts == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$server_ts.'</td>';
											}

											if($date_time == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$date_time.'</td>';
											}

											if($vid == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td align="left" class="text">'.$vid.'</td>';
											}

											if($vname == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												if ($j%2==0)
												{
												echo'<td class="text"><font color="red">'.$vname.'</font></td>';
												}
												else
												{
													echo'<td class="text"><font color="green">'.$vname.'</font></td>';
												}											
											}
                                             
											if( $vserial == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{	
											echo'<td class="text">'.$vserial.'</font></td>';
											}

											if($msg_type == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$msg_type.'</td>';
											}

											if($send_mode == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$send_mode.'</td>';
											}

											if($latitude == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$latitude.'</td>';
											}
											
											if($longitude == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$longitude.'</td>';
											}

											if($altitude == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$altitude.'</td>';
											}

											if($speed == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.round($speed,2).'</td>';
											}
											
											/*if($fix == "")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
											echo'<td class="text">'.$fix.'</td>';
											}

                                            if($signal_strength == "")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
											echo'<td class="text">'.$signal_strength.'</td>';
											}                                             
												
											if($no_of_satellites == "")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
											echo'<td class="text">'.$no_of_satellites.'</td>';
											}
                                            echo' <!--<td><font size="2">'.$vname.'</font></td>-->';*/ 
                                                
                                            if($cbc == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$cbc.'</td>';
											} 
                                                
                                            if($cell_name == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$cell_name.'</td>';
											} 
											
											if($min_speed == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$min_speed.'</td>';
											}
                                              

											if($max_speed == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$max_speed.'</td>';
											}
										
                                      
                                        echo'</tr>';
                                }
							}
							echo'</tbody></table></div>';
					}
					else if($size2)
					{
					echo'<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td class="text" align="center"> 
									<font color="red">
										<strong>
											Data Log By Device Serial
										</strong>
									</font>
								</td>
							</tr>
						</table>

						<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
							<TR>
								<TD>
									<img src="header_main.png">
								</td>
							</tr>
						</table>
				';
				
				echo'<div class="scrollTableContainer" id="prepage1" style="visibility:hidden;">';	
					echo'<table width="100%" border="1" cellpadding="0" cellspacing="0">
							  <thead>
								<tr bgcolor="#C9DDFF"> 
									<th class="text"><b><font size="1">SNo</font></b></th>			
									<th class="text"><b><font size="1">STS</font></b></th>
									<th class="text"><b><font size="1">DateTime</font></b</th>
									<th class="text"><b><font size="1">VID</font></b></th>	
									<th class="text"><b><font size="1">Veh Name</font></b></th>	
									<th class="text"><b><font size="1">veh Serial</font></b></th>	
									<th class="text"><b><font size="1">MsgTp</font></b></th>
									<th class="text"><b><font size="1">SendMode</font></b></th>
									<th class="text"><b><font size="1">Latitude</font></b></th>
									<th class="text"><b><font size="1">Longitude</font></b></th>
									<th class="text"><b><font size="1">Altitude</font></b></th>			
									<th class="text"><b><font size="1">Speed</font></b></th>
									<th class="text"><b><font size="1">Fix</font></b></th>
									<th class="text"><b><font size="1">SgnlSt</font></b></th>
									<th class="text"><b><font size="1">NofSat</font></b></th>
									<th class="text"><b><font size="1">CBC</font></b></th>
									<th class="text"><b><font size="1">CellName</font></b></th>
									<th class="text"><b><font size="1">MinSpeed</font></b></th>
									<th class="text"><b><font size="1">MaxSpeed</font></b></th>
									<!--<th class="text">Distance</th>
									<th class="text">LD</th>-->			
								</tr>
								</thead>
								<tbody>';
							
							for($j=0;$j<$size2;$j++)
							{
                                $Query = "Select VehicleID from vehicle where VehicleSerial = '$vehicleserial[$j]'";
                                $QueryResult = mysql_query($Query,$DbConnection);

                                if($row = mysql_fetch_object($QueryResult))
                                {
                                        $vehicleid = $row->VehicleID;
                                }
                                ////////get tablename ///////////
                                $sql1 = "select TableID from vehicletable where VehicleID='$vehicleid'";
                                $res1 = mysql_query($sql1,$DbConnection);

                                if($row1 = mysql_fetch_object($res1))
                                {
                                $tableid = $row1->TableID;
                                }

                                $sql2 = "select TableName from `table` where TableID ='$tableid'";
                                $res2 = mysql_query($sql2,$DbConnection);

                                if($row2 = mysql_fetch_object($res2))
                                {
                                $tablename = $row2->TableName;
                                }
								////////////////////////////////////////////
								if($rec == "30")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";									
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
								else if($rec == "100")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 100";									
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 100";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
								else if($rec == "all")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC";	$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
								else
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}

                                for($i=1;$i<=$numrows;$i++)
                                {
									$row = mysql_fetch_object($QueryResult);
									$vid = $row->VehicleID;
									$server_ts=$row->ServerTS;
									$date_time=$row->DateTime;
									$msg_type=$row->MsgType;
									$send_mode=$row->SendMode;
									$latitude=$row->Latitude;
									$longitude=$row->Longitude;
									$altitued=$row->Altitude;
									$speed=$row->Speed;
									$fix=$row->Fix;
									$Signal_Strength=$row->Signal_Strength;
									$no_of_satellites=$row->No_Of_Satellites;
									$cbc=$row->CBC;
									$cell_name=$row->CellName;
									$min_speed=$row->min_speed;
									$max_speed=$row->max_speed;
									
                                        $Query_vname = "select VehicleName,VehicleSerial from vehicle where VehicleID='$vid'";    
										$res3 = mysql_query($Query_vname,$DbConnection);
                                        if($row3 = mysql_fetch_object($res3))
                                        {
                                        $vname = $row3->VehicleName;
                                        $vserial = $row3->VehicleSerial;
                                        }

										if ($i%2==0)
											{
												echo '<tr bgcolor="#F7FCFF">';
											}										
											else 
											{
											   echo '<tr bgcolor="#E8F6FF">';
											}
                                      
											echo'<td class="text">'.$i.'</td>';
											if($server_ts == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$server_ts.'</td>';
											}

											if($date_time == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$date_time.'</td>';
											}

											if($vid == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$vid.'</td>';
											}

											if($vname == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$vname.'</font></td>';
											}
                                             
											if( $vserial == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												if ($j%2==0)
												{
												echo'<td class="text"><font color="red">'.$vserial.'</font></td>';
												}
												else
												{
													echo'<td class="text"><font color="green">'.$vserial.'</font></td>';
												}											
											}

											if($msg_type == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$msg_type.'</td>';
											}

											if($send_mode == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$send_mode.'</td>';
											}

											if($latitude == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$latitude.'</td>';
											}
											
											if($longitude == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$longitude.'</td>';
											}

											if($altitude == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$altitude.'</td>';
											}

											if($speed == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.round($speed,2).'</td>';
											}
											
											if($fix == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$fix.'</td>';
											}

                                            if($signal_strength == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$signal_strength.'</td>';
											}                                             
												
											if($no_of_satellites == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$no_of_satellites.'</td>';
											}
                                            echo' <!--<td><font size="2">'.$vname.'</font></td>-->';
                                                
                                            if($cbc == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$cbc.'</td>';
											}  
                                                
                                            if($cell_name == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$cell_name.'</td>';
											} 
											
											if($min_speed == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$min_speed.'</td>';
											}
                                              

											if($max_speed == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$max_speed.'</td>';
											}
									echo'</tr>
                                    ';
                                }
							}
								echo'</tbody></table></div>';
						}

                        else if($size3)
                        {
						echo'<table width="100%" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td class="text" align="center">
										<font color="red">
											<strong>
												Data Log By Phone No 
											</strong>
										</font>
									</td>
								</tr>
							</table>
							<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
								<TR>
									<TD>
										<img src="header_main.png">
									</td>
								</tr>
							</table>';

							echo'<div class="scrollTableContainer" id="prepage1" style="visibility:hidden;">';		
							echo'<table width="100%" border="1" cellpadding="0" cellspacing="0">
							  <thead>
								<tr bgcolor="#C9DDFF"> 
									<th class="text"><b><font size="1">SNo</font></b></th>			
									<th class="text"><b><font size="1">STS</font></b></th>
									<th class="text"><b><font size="1">DateTime</font></b</th>
									<th class="text"><b><font size="1">VID</font></b></th>	
									<th class="text"><b><font size="1">Veh Name</font></b></th>	
									<th class="text"><b><font size="1">veh Serial</font></b></th>	
									<th class="text"><b><font size="1">MsgTp</font></b></th>
									<th class="text"><b><font size="1">SendMode</font></b></th>
									<th class="text"><b><font size="1">Latitude</font></b></th>
									<th class="text"><b><font size="1">Longitude</font></b></th>
									<th class="text"><b><font size="1">Altitude</font></b></th>			
									<th class="text"><b><font size="1">Speed</font></b></th>
									<th class="text"><b><font size="1">Fix</font></b></th>
									<th class="text"><b><font size="1">SgnlSt</font></b></th>
									<th class="text"><b><font size="1">NofSat</font></b></th>
									<th class="text"><b><font size="1">CBC</font></b></th>
									<th class="text"><b><font size="1">CellName</font></b></th>
									<th class="text"><b><font size="1">MinSpeed</font></b></th>
									<th class="text"><b><font size="1">MaxSpeed</font></b></th>
									<!--<th class="text">Distance</th>
									<th class="text">LD</th>-->			
								</tr>
								</thead>
								<tbody>';
							
							for($j=0;$j<$size3;$j++)
							{
                                $Query = "Select VehicleID from vehicle where PhoneNo = '$phone[$j]'";                 $QueryResult = mysql_query($Query,$DbConnection);

                                if($row = mysql_fetch_object($QueryResult))
                                {
                                        $vehicleid = $row->VehicleID;
                                }

                                ////////get tablename///////////							
                                $sql1 = "select TableID from vehicletable where VehicleID='$vehicleid'";
                                $res1 = mysql_query($sql1,$DbConnection);

                                if($row1 = mysql_fetch_object($res1))                              
								{
                                $tableid = $row1->TableID;
                                }

                                $sql2 = "select TableName from `table` where TableID ='$tableid'";
                                $res2 = mysql_query($sql2,$DbConnection);

                                if($row2 = mysql_fetch_object($res2))
                                {
                                $tablename = $row2->TableName;
                                }
                                ////////////////////////////////
								if($rec == "30")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";									
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
								else if($rec == "100")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 100";									
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 100";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
								else if($rec == "all")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC";	$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}
								else
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
											//echo $Query;
											$QueryResult = mysql_query($Query,$DbConnection);
											//echo $QueryResult;
											$numrows = mysql_num_rows($QueryResult);
											//echo "num_rows=".$numrows;
											if($numrows)
											{	
												break;
											}
										}
									}
								}

                                for($i=1;$i<=$numrows;$i++)
                                {
                                    $row = mysql_fetch_object($QueryResult);
									$vid = $row->VehicleID;
									$server_ts=$row->ServerTS;
									$date_time=$row->DateTime;
									$msg_type=$row->MsgType;
									$send_mode=$row->SendMode;
									$latitude=$row->Latitude;
									$longitude=$row->Longitude;
									$altitued=$row->Altitude;
									$speed=$row->Speed;
									$fix=$row->Fix;
									$Signal_Strength=$row->Signal_Strength;
									$no_of_satellites=$row->No_Of_Satellites;
									$cbc=$row->CBC;
									$cell_name=$row->CellName;
									$min_speed=$row->min_speed;
									$max_speed=$row->max_speed;
								  
							   
								$Query_vname = "select VehicleName,VehicleSerial from vehicle where VehicleID='$vid'";
								$res3 = mysql_query($Query_vname,$DbConnection);
								if($row3 = mysql_fetch_object($res3))
								{
								$vname = $row3->VehicleName;
								$vserial = $row3->VehicleSerial;
								}

											if ($i%2==0)
											{
												echo '<tr bgcolor="#F7FCFF">';
											}										
											else 
											{
											   echo '<tr bgcolor="#E8F6FF">';
											}
                                      
											echo'<td class="text">'.$i.'</td>';
											if($server_ts == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$server_ts.'</td>';
											}

											if($date_time == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$date_time.'</td>';
											}

											if($vid == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td align="left" class="text">'.$vid.'</td>';
											}

											if($vname == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$vname.'</font></td>';
											}
                                             
											if( $vserial == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												
											echo'<td class="text">'.$vserial.'</font></td>';
																						
											}

											if($msg_type == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$msg_type.'</td>';
											}

											if($send_mode == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$send_mode.'</td>';
											}

											if($latitude == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$latitude.'</td>';
											}
											
											if($longitude == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$longitude.'</td>';
											}

											if($altitude == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$altitude.'</td>';
											}

											if($speed == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.round($speed,2).'</td>';
											}
											
											if($fix == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$fix.'</td>';
											}

                                            if($signal_strength == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$signal_strength.'</td>';
											}                                             
												
											if($no_of_satellites == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$no_of_satellites.'</td>';
											}
                                            echo' <!--<td><font size="2">'.$vname.'</font></td>-->';
                                                
                                            if($cbc == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$cbc.'</td>';
											}  
                                                
                                            if($cell_name == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$cell_name.'</td>';
											} 
											
											if($min_speed == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$min_speed.'</td>';
											}
                                              

											if($max_speed == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$max_speed.'</td>';
											}

                               /* echo'
                                  <td class="text">'.$vname.'</td>
                                  <td class="text">'.$vserial.'</td>
                                  <td class="text">'.$row->MsgType.'</td>
                                  <td class="text">'.$row->SendMode.'</td>
                                  <td class="text">'.$row->Latitude.'</td>
                                  <td class="text">'.$row->Longitude.'</td>
                                  <td class="text">'.$row->Altitude.'</td>
                                  <td class="text">'.$row->Speed.'</td>
                                  <td class="text">'.$row->Fix.'</td>
								  <td class="text">'.$row->Signal_Strength.'</td>
								  <td class="text">'.$row->No_Of_Satellites.'</td>
                                  <td class="text">'.$row->CBC.'</td>
                                  <td class="text">'.$row->CellName.'</td>
                                  <td class="text">'.$row->min_speed.'</td>
                                  <td class="text">'.$row->max_speed.'</td>  
								  <!--<td align="left"><font size="2">'.$row->distance.'</font></td>                       <td align="left"><font size="2">'.$row->Last_Data.'</font></td>-->*/
                                echo'</tr>
                                ';
                                }
							}
							echo'</tbody></table></div>';
						}
                ?>
<!--</table>-->	

<?php
mysql_close($DbConnection);
?>

</BODY>
</HTML>
