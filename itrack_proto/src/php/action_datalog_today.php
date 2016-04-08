<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');

	$size_suid=sizeof($suid);
	$start_date=$_POST['StartDate'];
	$fh=$_POST['hrfrom'];
	$fm=$_POST['mifrom'];
	$fs=$_POST['ssfrom'];
	$fh1=$_POST['hrto'];
	$fm1=$_POST['mito'];
	$fs1=$_POST['ssto'];
	$StartDate1=$start_date." ".$fh.":".$fm.":".$fs;
	$EndDate1=$start_date." ".$fh1.":".$fm1.":".$fs1;
	$radio_button = @$_POST['rec'];
	$StartDate1=str_replace("/","-",$StartDate1);
	$EndDate1=str_replace("/","-",$EndDate1);

	$st=explode(" ",$StartDate1);
	$et=explode(" ",$EndDate1);


////////////////////////////////displaying those vehicle whose grace period is over///////////////////////////////
//date_default_timezone_set('Asia/Calcutta');
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>							<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
								<TR>
									<TD>
										<img src="header_main.png">
									</td>
								</tr>
							</table>	
							<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
				<?php
                                echo'
									<td height=10 class="header" align="center" width="84%">
										<font color="red" size="2">
											<b>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Data Log Between Times:
											</b>
										 </font>
												
										 <font color="green" size="2">
											 <b>
												'.$st[1].'
											</b>
										 </font>
									  
										 <font color="red" size="2">
											<b>
												and
											</b>
										 </font>
									  
										 <font color="green" size="2">
											
											<b>
												'.$et[1].'
											</b>
										 </font>
									  </td>
                                ';
                ?>
								<td>
									<font color="green" size="2">											
										<b>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date :
										</b>
									 </font>
									 <font color="red" size="2">
										<b>
											<?php echo $st[0] ?>
										</b>
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
							<?php                           
							//date_default_timezone_set('Asia/Calcutta');
							$EndDate=date('Y/m/d H:i:s');
							$EndDate = str_replace("/","-",$EndDate);
							$EDate = explode(" ",$EndDate);
							$EDate = $EDate[0].' '.'00:00:00';

							$query_vehicle = "select TableName from `table`";

							//echo "<br>Query_v=".$query_vehicleid;
							$result_vehicle = mysql_query($query_vehicle,$DbConnection);
							//echo "<br>Query_v=".$result_vehicle;

							$num12 = mysql_num_rows($result_vehicle);

							$num_table_ids=0;
							while($row1 = mysql_fetch_object($result_vehicle))
							{
								$tablename_1[$num_table_ids]=$row1->TableName;	
								$num_table_ids++;
							}																							
						
							for($i=0;$i<$num_table_ids;$i++)
							{		
								if($i==0)
								{
									if($login=="demouser")
									{
										include("../custom_users.php");
										$Query3="(Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.UserID,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].Fuel from $tablename_1[$i],vehicle WHERE $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$StartDate1' and '$EndDate1' and (vehicle.UserID='$user1' OR vehicle.UserID='$user2'))";
									}
									else
									{
										$Query3="(Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.UserID,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].Fuel from $tablename_1[$i],vehicle WHERE $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$StartDate1' and '$EndDate1' and vehicle.UserID='$login')";
									}
								}
								else
								{
									if($login=="demouser")
									{
										include("../custom_users.php");
										$Query3 = $Query3." UNION ALL (Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.UserID,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].Fuel from $tablename_1[$i],vehicle WHERE $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$StartDate1' and '$EndDate1' and (vehicle.UserID='$user1' OR vehicle.UserID='$user2'))";
									}
									else
									{
										$Query3 = $Query3." UNION ALL (Select $tablename_1[$i].ServerTS as ST,$tablename_1[$i].DateTime,vehicle.VehicleID,vehicle.VehicleName,vehicle.UserID,vehicle.VehicleSerial,$tablename_1[$i].MsgType,$tablename_1[$i].SendMode,$tablename_1[$i].Latitude,$tablename_1[$i].Longitude,$tablename_1[$i].Altitude,$tablename_1[$i].Speed,$tablename_1[$i].Fix,$tablename_1[$i].Signal_Strength,$tablename_1[$i].No_Of_Satellites,$tablename_1[$i].CBC,$tablename_1[$i].CellName,$tablename_1[$i].Fuel from $tablename_1[$i],vehicle WHERE $tablename_1[$i].VehicleID=vehicle.VehicleID and $tablename_1[$i].DateTime between '$StartDate1' and '$EndDate1' and vehicle.UserID='$login')";
									}
								}
							}		
			
							$Query3 = $Query3." ORDER BY ST DESC";

							//echo"query=".$Query3;
                            $QueryResult = mysql_query($Query3,$DbConnection);
							$num_rows=mysql_num_rows($QueryResult);
							//date_default_timezone_set('Asia/Calcutta');
							$date = date('Y-m-d');
							$starttime = $date.' 00:00:00';
							$endtime = date('Y-m-d H:i:s');
							if($num_rows)
							{
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
                                $i = 1;
                                while($row = mysql_fetch_object($QueryResult))
                                {
								$sno = $i;
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
                                        $res = mysql_query($Query_vname,$DbConnection);
                                        if($row3 = mysql_fetch_object($res))
                                        {
                                        $vname = $row3->VehicleName;
                                        $vserial = $row3->VehicleSerial;
                                        }								
											if ($sno%2==0)
											{
												echo '<tr bgcolor="#F7FCFF">';
											}										
											else 
											{
											   echo '<tr bgcolor="#E8F6FF">';
											}
                                      
											echo'<td class="text">'.$sno.'</td>';
											if($Server_ts == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'<td class="text">'.$Server_ts.'</td>';
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
											echo'<td class="text">'.$vserial.'</td>';
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
                                
                               echo' </tr>
                                ';
                                $i++;
                                }
								echo'</tbody></table></div>'; 
							}
							else
							{
								echo'<br>';
								print"<center><FONT color=\"black\" size=\"2\"><strong>Sorry No Data Found </strong></font></center>";
								//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=searchbyvehiclename.php\">";
							}
                       
					?>
					</body>
					</html>
