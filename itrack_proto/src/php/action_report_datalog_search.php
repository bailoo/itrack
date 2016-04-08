<?php
	include_once("../SessionVariable.php");
	include("../PhpMysqlConnectivity.php");
	$veh_name=$_POST['veh_name'];					
?>
	
<HTML>

	<TITLE>
		Vehicle Tracking Pro-Innovative Embedded Systems Pvt. Ltd.
	</TITLE>
<head>
	
	<link rel="shortcut icon" href="images/iesicon.ico" >
	<LINK REL="StyleSheet" HREF="../menu.css">
	
	
	<style type="text/css">
		div.scrollTableContainer 
		{
			height: 498px;
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
		height: 438px;
		overflow-x: hidden;
		}

			/* Modern browsers (but especially firefox ) */
		div.scrollTableContainer table>tbody 
		{		
		height: 472px;
		width: 1040px;
		overflow-x: hidden;
		}

		div.scrollTableContainer thead tr 
		{
		position:relative;
		top: expression(offsetParent.scrollTop); /*IE5+ only*/
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
		top: expression(offsetParent.scrollTop); /*IE5+ only*/
		/* fixes the header being over too far in IE, doesn’t seem to affect FF */
		left: 0px;
		}

			/*prevent Mozilla scrollbar from hiding cell content*/
		div.scrollTableContainer td:last-child 
		{
			padding-right: 20px;
		}
	</style>
</head>

<body bgcolor="white">
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
			
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
								
				<?php
							echo'

									<td height=10 class="header" align="center">
										<b>
											<font color="red" size="2">
												Vehicle Data Log By Device Name :
											</font></b>
											
											<b>
												<font color="green" size="2">
													'.$veh_name.'
												</font>
											</b>
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
						
				 <?php
							if($access)
							{    
							
                                $Query = "Select VehicleID from `vehicle` where VehicleName = '$veh_name'";
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
								//echo "tablename=".$tablename;
								//echo "rec=".$rec;
								//$Query1="SELECT DateTime FROM $tablename WHERE VehicleID='$vehicleid'

								if($rec == "30")
								{
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
									$QueryResult = mysql_query($Query,$DbConnection);
									$numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=20;$i++)
										{
												if($i<=9)
												{
												$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
												}
												else
												{
												$Query="SELECT * FROM $tablename"."_20"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
												}
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
											if($i<=9)
											{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 100";
											}
											else
											{
											$Query="SELECT * FROM $tablename"."_20"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 100";
											}

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
									$Query = "SELECT * FROM $tablename WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC";
									$QueryResult = mysql_query($Query,$DbConnection);
									    $numrows = mysql_num_rows($QueryResult);
									if( $numrows=="")
									{
										//echo "in if";
										for($i=1;$i<=9;$i++)
										{
											if($i<=9)
											{
											$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC";
											}
											else
											{
											$Query="SELECT * FROM $tablename"."_20"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC";
											}
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
											for($i=1;$i<=20;$i++)
											{
												if($i<=9)
												{
												$Query="SELECT * FROM $tablename"."_200"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
												}
												else
												{
												$Query="SELECT * FROM $tablename"."_20"."$i"." WHERE VehicleID='$vehicleid' ORDER BY ServerTS DESC limit 30";
												}
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
                               // $QueryResult = mysql_query($Query,$DbConnection);
                                //echo "Q=".$Query;
                                //echo "res=".$QueryResult;

                                $i=1;

                               // $numrows = mysql_num_rows($QueryResult);
								If($numrows)
								{
                                //echo "<br>numrows = ".$numrows."<br>";
								echo'
									 <div class="scrollTableContainer">	
									   <table width="100%" border="1" cellpadding="0" cellspacing="0">
										 <thead>
											<tr bgcolor="#9BB5C4"> 
												<th class="text"><b><font size="1">SNo</b></font></th>			
												<th class="text"><b><font size="1">STS</b></font></th>
												<th class="text"> <b><font size="1">DateTime</b></font></th>
												<th class="text"><b><font size="1">VID</b></font></th>	
												<!--<th><font size="2" face="Times New Roman, Times, serif">Veh Name</font></th>-->	
												<th class="text"><b><font size="1">veh Serial</b></font></th>	
												<th class="text"><b><font size="1">MsgTp</b></font></th>
												<th class="text"><b><font size="1">SendMode</b></font></th>
												<th class="text"><b><font size="1">Latitude</b></font></th>
												<th class="text"><b><font size="1">Longitude</b></font></th>
												<th class="text"><b><font size="1">Altitude</b></font></th>	
												<th class="text"><b><font size="1">Speed</b></font></th>
												<th class="text"><b><font size="1">Fix</b></font></th>
												<th class="text"><b><font size="1">SgnlSt</b></font></th>
												<th class="text"><b><font size="1">NofSat</b></font></th>
												<th class="text"><b><font size="1">CBC</b></font></th>
												<th class="text"><b><font size="1">CellName</b></font></th>
												<th class="text"><b><font size="1">Speed</b></font></th>
												<th class="text"><b><font size="1">MaxSpeed</b></font></th>
												<!--<th class="text"><b><font size="1">Distance</b></font></th>
												<th class="text"><b><font size="1">LD</b></font></th>-->		
											</tr>
										  </thead>
										<tbody>';
                                for($i=1;$i<=$numrows;$i++)
                                {
                                        $row = mysql_fetch_object($QueryResult);
                                       
										$vid = $row->VehicleID;
                                        $sts = $row->ServerTS;
                                        $dt = $row->DateTime;
										$msgtype=$row->MsgType;
										$sendmode=$row->SendMode;
										$lat=$row->Latitude;
										$long=$row->Longitude;
										$altitude=$row->Altitude;
										$speed=$row->Speed;
										$fix=$row->Fix;
										$sig_str=$row->Signal_Strength;
										$no_of_set=$row->No_Of_Satellites;
										$cbc=$row->CBC;
										$cellname=$row->CellName;
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
											if($sts == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
												echo'	 <td class="text">'.$sts.'</td>';
											}

											if($dt == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$dt.'</td>';
											}

											if($vid == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td align="left" class="text">'.$vid.'</td>';
											}

											if($vserial == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$vserial.'</font></td>';
											}
                                             
											if($msgtype == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text"><font size="2">'.$msgtype.'</td>';
											}

											if($sendmode == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$sendmode.'</td>';
											}

											if($lat == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$lat.'</td>';
											}
											
											if($long == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$long.'</td>';
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
											echo'<td class="text">'.$speed.'</td>';
											}
											
											if($fix == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$fix.'</td>';
											}

                                            if($sig_str == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$sig_str.'</td>';
											}                                             
												
											if($no_of_set == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$no_of_set.'</td>';
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
                                                
                                            if($cellname == "")
											{
												echo'<td class="text" align="center"><font color="red">-</font></td>';
											}
											else
											{
											echo'<td class="text">'.$cellname.'</td>';
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
                                            echo'<!--<td class="text">'.$row->distance.'</td>
                                                <td class="text">'.$row->Last_Data.'</td>-->
                                         </tr>
										
							 ';
                                }
									echo'
									 </tbody>
								</table>
							</div>';
							}
							else
							{
								echo'<br>';
								print"<center><FONT color=\"black\" size=\"2\"><strong>No Data Found For This Vehicle</strong></font></center>";
							//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"3; URL=searchbyvehiclename.php\">";
							}
							
						}
?>

					</td>
				</tr>
			</table>
		</td>
	</body>
</html>
