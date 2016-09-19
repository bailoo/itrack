<?php
	set_time_limit(2000);
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once("get_io.php");
	include_once("util.fuel_calibration.php");
	include_once("calculate_distance.php");
	include_once("user_type_setting.php");
	include_once("select_landmark_report.php");
	$query="SELECT field22 FROM account_feature WHERE account_id=$account_id";   
	$result = mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	$lat_lng_permission=$row[0];
	//$place_name_arr=$_SESSION['place_name_arr1'];
	$xml_path=$_POST["xmltowrite"];
	$vserial=$_POST["vserial"];
	$vname=$_POST["vname"];  
	$vnumber=$_POST["vnumber"];
	$geocodes_string = $_POST['geocodedPostcodes'];  
	$geocodes_string = substr($geocodes_string, 0, -1);
	$geocodes_string = str_replace('"','',$geocodes_string);

	//echo "<br>GEOCODES STRING=".$geocodes_string;

	$location = explode(':',$geocodes_string);
	$vserial_post = explode(',',$vserial);
	$io_element_post = explode(',',$text_report_io_element);
	$vname_post = explode(':',$vname);
	$vnumber_post = explode(':',$vnumber);

	//echo "<br>size:serial=".sizeof($vserial_post)." ,name:".sizeof($vname_post);

	//echo "xml_path=".$xml_path."<br>";
	//$xml_path= "xml_tmp/filtered_xml/tmp_1295703104897.xml";

	$startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
	if($data_with_category==6)
	{
		$report_type="Person";
	}

	//echo $xml_path."<br>";
	if($report_type=="Vehicle")
	{
		$vehicleserial=unserialize(base64_decode($vehicleserial));
		$lat = unserialize($lat);
		$lng = unserialize($lng);
		$datetime = unserialize(base64_decode($datetimeXml));	
		$vehiclename = unserialize(base64_decode($vehiclename));
		$vehiclenumber = unserialize(base64_decode($vehiclenumber));
		$vehicletype = unserialize(base64_decode($vehicletype));
		$speed = unserialize($speed);	
		$io1 = unserialize($io1);
		$io2 = unserialize($io2);
		$io3 = unserialize($io3);
		$io4 = unserialize($io4);
		$io5 = unserialize($io5);
		$io6 = unserialize($io6);
		$io7 = unserialize($io7);
		$io8 = unserialize($io8);
		$size = sizeof($vehicleserial);
	}
	else
	{
		$vehicleserial=unserialize($vehicleserial);
		$lat = unserialize($lat);
		$lng = unserialize($lng);
		$datetime = unserialize(base64_decode($datetimeXml));	
		$vehiclename = unserialize(base64_decode($vehiclename));
		$vehiclenumber = unserialize(base64_decode($vehiclenumber));
		$vehicletype = unserialize(base64_decode($vehicletype));
		$size = sizeof($vehicleserial);
	}
//echo "in Last Data<br>";
	if($text_report_io_element!="tmp_str")
	{
		$text_report_io_element=explode(":",$text_report_io_element);
		$io_count=count($text_report_io_element);
	}	
	
	//echo "<br>size:".$size;
	$vserail_io_type=array();
	for($io=0;$io<sizeof($vserial_post);$io++)
	{
		$vserial_tmp=$vserial_post[$io];
		$vserail_io_type[$vserial_tmp]=$io_element_post[$io];
	}  
	//echo "<br>size=========".$size;	
?>

<HTML>
	<TITLE>
		Vehicle Tracking Pro-Innovative Embedded Systems Pvt. Ltd.
	</TITLE>

<head>	

  <link rel="stylesheet" type="text/css" href="../css/menu.css">
	
  <style type="text/css">
	
  .text
  {color: black;
  font-size: 8pt;
  margin: 0px;
  padding: 0px;
  font-weight: normal;
  }
	
  @media print 
	{
		.noprint
		{
			display: none;
		}
	}
	@media screen
	{ 
		.noscreen
		{ 
			display: none; 
		} 
	}

  .normal1 { background-color: #F8F8FF }
  .highlight1 { background-color: #C6DEFF }

  .normal2 { background-color: #FFFDF9 }
  .highlight2 { background-color: #C6DEFF }

	</style>
	
	
  <script type="text/javascript" src="src/js/drag.js"></script>
	<script type="text/javascript" src="../js/menu.js"></script>
	
	<script type="text/javascript">
		      
   /* function create_pdf()
		{	
			alert("pdf");
			document.text_data_report.target = "_blank";
			document.text_data_report.action="report_getpdf_type3.php?size=<?php echo $vserial_post; ?>";
		}	

		function mail_report()
		{
			document.text_data_report.target = "_self";
			document.text_data_report.action="mail_lastdata_report.php?size=<?php echo $vserial_post; ?>";
		}

		function call()
		{	
			//alert("test");
			document.forms[0].action="tempfiles/dl.php?filename=lastdatareport.csv";
			document.forms[0].target="_blank";
			document.forms[0].submit();	
		}*/
	</script>

<?php
	$root_dir = getcwd();
	$path_mapwindow_jsmodule = $root_dir."/map_window/mapwindow_jsmodule.php";	
	include($path_mapwindow_jsmodule);  
?>
	<script type="text/javascript">	
		//function MapWindow(vname,datetime,lat,lng)		
		function report_csv(target_file)
		{
			//alert("reportcsv");
			document.forms[0].action = target_file;    
			document.forms[0].submit();
		}

		function map_window(vname,datetime,lat,lng)
		{
			//alert(vname+" "+datetime+" "+lat+" "+lng);	
			//test2(vname,datetime,lat,lng);			
			document.getElementById("window").style.display = '';
			load_vehicle_on_map(vname,datetime,lat,lng);							
		}
                function submitDataLog(vSerial,startdate,enddate) // this function is used for datalog href option in google map track
                {
                    document.getElementById('dataLogImei').value=vSerial;
                    document.getElementById('start_date_map').value=startdate;
                    document.getElementById('end_date_map').value=enddate;
                    document.dataLogSubmit.target='_blank';
                    document.dataLogSubmit.submit();
                }
	</script>	
</head>
<body>
	<?php
		/*function calculate_mileage($lat1, $lat2, $lon1, $lon2, &$distance) 
		{	
		$lat1 = deg2rad($lat1);
		$lon1 = deg2rad($lon1);

		$lat2 = deg2rad($lat2);
		$lon2 = deg2rad($lon2);

		$delta_lat = $lat2 - $lat1;
		$delta_lon = $lon2 - $lon1;

		$temp = pow(sin($delta_lat/2.0),2) + cos($lat1) * cos($lat2) * pow(sin($delta_lon/2.0),2);
		$distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));

		$distance = $distance*1.609344;	
		} */	
		$root_dir = getcwd();
		$path_mapwindow = $root_dir."/map_window/floating_map_window.php";
		include($path_mapwindow);  
	?>

	<td STYLE="background-color:white;width:85%;">
		<br>			
			<?php 
				echo'<input TYPE="hidden" VALUE="$access" NAME="access">';
			?>				
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%" align="center">
				<TR>
					<TD>
						<table border=0 width = 100% cellspacing=2 cellpadding=0 align="center">
							<tr>
								<td width="8%"> &nbsp;</td>    
						<?php
							echo'<td height=10 class="text" align="center" width="79%">
									<b>
										<font color="#23238E">
											Last Data Report- Date Time:
										</font>											
									</b>
									<font color="green" size="2">
										<b>
											'.$startdate.'
										</b>
									</font>
									<font color="#23238E" size="2">
										<b>
											-
										</b>
									</font>
									<font color="green" size="2">	
										<b>
											'.$enddate.'
										</b>	
									</font>
								</td>
							</tr>
						</table>';							
						
						if($size>0)
						{	
							echo'<br>
								<table style="font-size: 9pt;" border="0" width="98%" bordercolor="#689FFF" align="center" cellspacing="4" cellpadding="4">
									<tr bgcolor="#EAC752">
										<td class="text"><b>SNo</b></td>
										<td class="text"><b>'.$report_type.' Name</b></td>
										<td class="text"><b>IMEI</b></td>
										<td class="text" width="7%"><b>Date</b></td>
										<td class="text" align="left" width="5%"><b>Time</b></td>';
										if($report_type=="Person")
										{   					
											echo'<td class="text" width="5%"><b>Mobile Number</b></td>';  							
										}
										
										if($data_with_location=="1")
										{
											echo'<td class="text" width="30%"><b>Location</b></td>';
										}
										if($lat_lng_permission=="1")
										{
										echo'<td class="text" width="6%"><b>Latitude</b></td>
											<td class="text" width="7%"><b>Longitude</b></td>';
										}									
										if($report_type!="Person")
										{
											echo'<td class="text" width="5%"><b>Speed</b></td>';
											echo'<td class="text" width="16%"><b>Status</b></td>';											
										}
								echo'</tr>';
								//include("get_location_test.php");
								//include("get_location.php");								
								$tmp_vname ="";
								$m=0;
								for($k=0;$k<sizeof($vserial_post);$k++)
								{                                  
									$flag_found = 0;
									for($i=0;$i<$size;$i++)
									{
										//echo "<br>".$vehicleserial[$i]." i=".$i." : ".$vserial_post[$k]." k=".$k;
										if($vehicleserial[$i] == $vserial_post[$k])
										{
											$flag_found = 1;  
											$lt1 = $lat[$i];
											$lng1 = $lng[$i];
											$alt1 = "-";								
											///////////////////
											if($data_with_location=="1")
											{
												$landmark="";
												get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION
												$place1 = $landmark;
												if($place1=="")
												{
													//get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
													//echo "sizeof=".sizeof($location)."<br>";
													for($gi=0;$gi<sizeof($location);$gi++)
													{
														$place = explode("#",$location[$gi]);
														//echo "imei1=".$place[1]." imei2=".$vehicleserial[$i]."<br>";
														if($place[1]==$vehicleserial[$i])
														{														
															$place1 = preg_replace('/भारत गणराज्य/', '' , $place[0]);
															$place1 = preg_replace('/-/', '' , $place1);															
														}												
													}												
												}
												$placename[$i] = $place1;  
											}
											$dt = explode(" ",$datetime[$i]);    
											if($i%2==0)
											{
												echo'<tr valign="top" bgcolor="#F8F8FF" onMouseOver="this.className=\'highlight1\'" onMouseOut="this.className=\'normal1\'">';
											}
											else
											{
												echo'<tr valign="top" bgcolor="#FFFDF9" onMouseOver="this.className=\'highlight2\'" onMouseOut="this.className=\'normal2\'">';
											}										
											$m++;    									
											echo'<td class="text">'.$m.'</td>';
											if($vehiclename[$i]=="")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
												if($report_type=="Person")
                                                                                                {
                                                                                                echo'<td class="text"><a href="#" onclick="javascript:submitDataLog(\''.$vehicleserial[$i].'\',\''.$startdate.'\',\''.$enddate.'\');" style="text-decoration:none">'.$vehiclename[$i].'</a></td>';
                                                                                                }
                                                                                                else
                                                                                                {
                                                                                                echo'<td class="text">'.$vehiclename[$i].'</td>';
                                                                                                }
											}
  										
											if($vehicleserial[$i]=="")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
												echo'<td class="text">'.$vehicleserial[$i].'</td>';
											}
                        										
											if($dt[0]=="")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
												echo'<td class="text">'.$dt[0].'</td>';
											}
											if($dt[1]=="")
											{
												echo'<td class="text">&nbsp;</td>';
											}
											else
											{
												echo'<td class="text">'.$dt[1].'</td>';
											} 
											if($report_type=="Person")
											{
												if($vehiclenumber[$i]=="")
												{
													echo'<td class="text">&nbsp;</td>';
												}
												else
												{
													echo'<td class="text">'.$vehiclenumber[$i].'</td>';
												}
											}											
											$dt_tmp1 = $dt[0];
											$dt_tmp2 = $dt[1];
											$datetime1 = $dt_tmp1." ".$dt_tmp2;  
											if($data_with_location=="1")
											{
												//echo "in if";
												if($placename[$i]=="")
												{
													echo'<td class="text">&nbsp;</td>';
												}
												else
												{
													$lt_tmp = substr($lat[$i], 0, -1);
													$lng_tmp = substr($lng[$i], 0, -1);
													$placename[$i] = $placename[$i]."-(".$lt_tmp.",".$lng_tmp.")";
													//echo'<td class="text" align="left" width="4%">'.$placename[$i].'</b>&nbsp;<a href="javascript:map_window(\''.$vehiclename[$i].'\',\''.$datetime1.'\','.$lt_tmp.','.$lng_tmp.');"><font color="green">Show location</font></a></td>';
													echo'<td class="text" align="left" width="4%">'.$placename[$i].'</b>&nbsp;</td>';
													//echo'<td class="text">'.$placename[$i].'<a href="javascript:MapWindow(\''.$vehiclename[$i].'\',\''.$datetime1.'\',\''.$lat[$i].'\',\''.$lng[$i].'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';
												}
											}
											if($lat_lng_permission=="1")
											{
												if($lt1=="")
												{
													echo'<td class="text">&nbsp;</td>';
												}
												else
												{
													echo'<td class="text">'.$lt1.'</td>';
												}
												if($lng1=="")
												{
													echo'<td class="text">&nbsp;</td>';
												}
												else
												{
													echo'<td class="text">'.$lng1.'</td>';
												}
											}
											//echo'<td class="text">'.$lat[$i].'</td>';
											//echo'<td class="text">'.$lng[$i].'</td>';
											if($report_type!="Person")
											{
												//echo'<td class="text">'.$alt[$i].'</td>';
												echo'<td class="text">'.round($speed[$i],2).'</td>';
												$vserial_tmp=$vserial_post[$k];
												if($vserail_io_type[$vserial_tmp]=="tmp_str")
												{
													echo'<td class="text">NA</td>';
												}	
												else
												{
													$io_typ_value=explode(":",$vserail_io_type[$vserial_tmp]);
													echo "<td>";
													$html_tmp_io="";
													for($j=0;$j<sizeof($io_typ_value);$j++)
													{
														$io_typ_value1=explode("^",$io_typ_value[$j]);
														$tmp_io="io".$io_typ_value1[0];										
														if($io_typ_value1[1]=="temperature")
														{
															$ioValuePrev=preg_replace('/[^0-9-]/s', '.', ${$tmp_io}[$i]);
															$io_value = (($ioValuePrev>=-30) && ($ioValuePrev<=70))?$ioValuePrev:'-';
															$html_tmp_io=$html_tmp_io.$io_typ_value1[1]." : ".$io_value."&#176;C : ";																																
														}
														else if($io_typ_value1[1]=="engine")
														{												
															if(${$tmp_io}[$i]=="")
															{
																$html_tmp_io=$html_tmp_io.$io_typ_value1[1]." : - : ";
															}
															else
															{
																if(${$tmp_io}[$i]<=500)
																{
																	$html_tmp_io=$html_tmp_io.$io_typ_value1[1]." : ON : ";
																}
																else
																{
																	$html_tmp_io=$html_tmp_io.$io_typ_value1[1]." : OFF : ";
																}													
															}																							
														}			
  														else if($io_typ_value1[1]=="ac")
														{
																if(${$tmp_io}[$i]=="")
																{
																		$html_tmp_io=$html_tmp_io.$io_typ_value1[1]." : - : ";
																}
																else
																{
																		if(${$tmp_io}[$i]<500)
																		{
																				$html_tmp_io=$html_tmp_io.$io_typ_value1[1]." : ON : ";
																		}
																		else
																		{
																				$html_tmp_io=$html_tmp_io.$io_typ_value1[1]." : OFF : ";
																		}                                                                                    
																}                                                                                            
														}
														else if($io_typ_value1[1]=="door_open")
														{
																if(${$tmp_io}[$i]=="")
																{
																		$html_tmp_io=$html_tmp_io."Delivery Door : - : ";
																}
																else
																{
																		if(${$tmp_io}[$i]<250)
																		{
																				$html_tmp_io=$html_tmp_io."Delivery Door : Close : ";
																		}
																		else
																		{
																				$html_tmp_io=$html_tmp_io."Delivery Door : Open : ";
																		}                                                                                    
																}                                                                                            
														}
														else if($io_typ_value1[1]=="door_open2")
														{
																if(${$tmp_io}[$i]=="")
																{
																		$html_tmp_io=$html_tmp_io."Manhole Door : - : ";
																}
																else
																{
																		if(${$tmp_io}[$i]<250)
																		{
																				$html_tmp_io=$html_tmp_io."Manhole Door : Close : ";
																		}
																		else
																		{
																				$html_tmp_io=$html_tmp_io."Manhole Door : Open : ";
																		}                                                                                    
																}                                                                                            
														}
														else if($io_typ_value1[1]=="door_open3")
														{
																if(${$tmp_io}[$i]=="")
																{
																		$html_tmp_io=$html_tmp_io."Manhole Door2 : - : ";
																}
																else
																{
																		if(${$tmp_io}[$i]<250)
																		{
																				$html_tmp_io=$html_tmp_io."Manhole Door2 : Close : ";
																		}
																		else
																		{
																				$html_tmp_io=$html_tmp_io."Manhole Door2 : Open : ";
																		}                                                                                    
																}                                                                                            
														}
						
													}
													if($html_tmp_io=="")
													{
														echo "NA";
													}
													else
													{							
														$html_tmp_io=substr($html_tmp_io,0,-2);
														echo $html_tmp_io;
													}								
													echo "</td>";
												}
												/*$queryc = "SELECT filename as fname FROM fuelcalib_file_assignment WHERE vehicle_id=(SELECT vehicle_id FROM vehicle_assignment ".
												"WHERE device_imei_no='$vehicleserial[$i]' AND status=1) AND status=1";
												$resc = mysql_query($queryc, $DbConnection);
												$rowc = mysql_fetch_object($resc);          
												$calibfile = $rowc->fname; 
												//echo "<br>query_c=".$queryc." ,fuel=".$fuel." calibfile=".$calibfile."<br>";
												if($fuel[$i]>30 && $fuel[$i]<4096)
												{
												$fuel_level_tmp = get_calibrated_fuel_level($fuel[$i], $calibfile); 
												}	
												else
												{
												$fuel_level_tmp = 0;
												}
												$fuel_level[$i] = round($fuel_level_tmp,2); */  
												//////////////////////////// 
												//echo'<td class="text">'.$fuel_level[$i].'</td>';
											}
											echo'</tr>';  									
											break;    									
										} // if closed
										else
										{
											$flag_found = 0;
											$tmp_vname = $vname_post[$k]; 
										}
									} //INNER LOOP CLOSED                  
									if($flag_found == 0)
									{
										$m++;
										if($report_type=="Person")
										{
                                                                                
											echo'<tr valign="top" bgcolor="#FFFAF5" onMouseOver="this.className=\'highlight1\'" onMouseOut="this.className=\'normal1\'">
                                                                                            <td class="text">'.$m.'</td>
                                                                                            <td class="text"><a href="#" onclick="javascript:submitDataLog(\''.$vserial_post[$k].'\',\''.$startdate.'\',\''.$enddate.'\');" style="text-decoration:none">'.$tmp_vname.'</a></td>
                                                                                            <td colspan="7" class="text">
                                                                                                    <font color="red">Inactive&nbsp;&nbsp;&nbsp;&nbsp;</font>
                                                                                                    <font color="blue">'.$vnumber_post[$k].'</font>
                                                                                            </td>
												</tr>';
										}
										else
										{
											echo'<tr valign="top" bgcolor="#FFFAF5" onMouseOver="this.className=\'highlight1\'" onMouseOut="this.className=\'normal1\'">
													<td class="text">'.$m.'</td>
                                                                                                       
													<td class="text">'.$tmp_vname.'</td>
													<td class="text">'.$vserial_post[$k].'</td>	
													<td colspan="7" class="text">
														<font color="red">Inactive</font>
													</td>
												</tr>';
										}
									}                                    									
								}   // FOR CLOSED    					
								$sizev = sizeof($vserial_post);
              
										echo'</table>
										<br>       
										<div align="center">
											<form name="text_data_report" method="post" action="" target = "_blank">                  			
												<TABLE align="center" BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">				
													<tr valign="top"> 
														<td width="40%">
															&nbsp;
														</td>
														<td align="right">								
															<a href="#" onclick="window.close()" style="text-decoration:none;">
																<font color="green" size="2"><b>Close</b></font>
															</a>
															<img src="../../images/close_win.png" align="absmiddle" style="widht:20px;height:20px;border:none;" onclick="window.close()">
														</td>';							
														$csv_string = "";
														//$csv_string = $csv_string."Title,SNo,VehicleName,DateTime,Location,Latitude,Longitude,Speed\n";
														$speed_status="";													
														if($report_type=='Person')
														{
															$title="Person Information-Last Data (".$startdate."-".$enddate.")";
															$csv_string = $csv_string." Person Last Position (".$startdate."-".$enddate."\n";														
														}
														else							
														{
															$title="Vehicle Information-Last Data (".$startdate." ".$enddate.")";
															$csv_string = $csv_string." Vehicle Last Position (".$startdate." ".$enddate."\n";
															$speed_status=",Speed,status";
														}								
							
														if($lat_lng_permission=="1" && $data_with_location=="1")
														{								
															$csv_string = $csv_string."SNo,VehicleName,IMEI,DateTime,Location,Latitude,Longitude".$speed_status."\n";
														}
														else if($lat_lng_permission=="1")
														{
															$csv_string = $csv_string."SNo,VehicleName,IMEI,DateTime,Latitude,Longitude".$speed_status."\n";								
														}
														else if($data_with_location=="1")
														{
															$csv_string = $csv_string."SNo,VehicleName,IMEI,DateTime,Location".$speed_status."\n";
														}
														else
														{
															$csv_string = $csv_string."SNo,VehicleName,IMEI,DateTime".$speed_status."\n";
														}							
														echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
														$m=0;
								
														for($k=0;$k<sizeof($vserial_post);$k++)
														{								
															$flag_found = 0; 
															
															for($i=0;$i<$size;$i++)
															{	
																if($vehicleserial[$i] == $vserial_post[$k])
																{
																	$latlng_location_flag=1;
																	$flag_found = 1;                      
																	$m++;
																	$vserial_tmp=$vserial_post[$k];
																	if($vserail_io_type[$vserial_tmp]=="tmp_str")
																	{										
																		$pdf_tmp_io='NA';
																	}
																	else
																	{
																		$io_typ_value=explode(":",$vserail_io_type[$vserial_tmp]);
																		$pdf_tmp_io="";
																		for($j=0;$j<sizeof($io_typ_value);$j++)
																		{
																			$io_typ_value1=explode("^",$io_typ_value[$j]);
																			$tmp_io="io".$io_typ_value1[0];
																			$pdf_io_value=${$tmp_io}[$i];								
																						
																			if($io_typ_value1[1]=="temperature")
																			{
																				$io_value = ((${$tmp_io}[$i]>=-30) && (${$tmp_io}[$i]<=70))?${$tmp_io}[$i]:'-';
																				$pdf_tmp_io=$pdf_tmp_io.$io_typ_value1[1]." : ".$io_value."&#176;C : ";																																
																			}
																			else if($io_typ_value1[1]=="engine")
																			{												
																				if(${$tmp_io}[$i]=="")
																				{
																					$pdf_tmp_io=$pdf_tmp_io.$io_typ_value1[1]." : - : ";
																				}
																				else
																				{
																					if(${$tmp_io}[$i]<=500)
																					{
																						$pdf_tmp_io=$pdf_tmp_io.$io_typ_value1[1]." : ON : ";
																					}
																					else
																					{
																						$pdf_tmp_io=$pdf_tmp_io.$io_typ_value1[1]." : OFF : ";
																					}													
																				}																							
																			}
																			else if($io_typ_value1[1]=="ac")
																			{                                                            
																					if(${$tmp_io}[$i]=="")
																					{
																							$pdf_tmp_io=$pdf_tmp_io.$io_typ_value1[1]." : - : ";
																					}
																					else
																					{
																							if(${$tmp_io}[$i]<500)
																							{
																									$pdf_tmp_io=$pdf_tmp_io.$io_typ_value1[1]." : ON : ";
																							}
																							else
																							{
																									$pdf_tmp_io=$pdf_tmp_io.$io_typ_value1[1]." : OFF : ";
																							}                                            
																					}                                                    
																			} 
																			else if($io_typ_value1[1]=="door_open")
																			{                                                            
																					if(${$tmp_io}[$i]=="")
																					{
																							$pdf_tmp_io=$pdf_tmp_io."Delivery Door : - : ";
																					}
																					else
																					{
																							if(${$tmp_io}[$i]<250)
																							{
																									$pdf_tmp_io=$pdf_tmp_io."Delivery Door : Close : ";
																							}
																							else
																							{
																									$pdf_tmp_io=$pdf_tmp_io."Delivery Door : Open : ";
																							}                                            
																					}                                                    
																			}
																			else if($io_typ_value1[1]=="door_open2")
																			{                                                            
																					if(${$tmp_io}[$i]=="")
																					{
																							$pdf_tmp_io=$pdf_tmp_io."Manhole Door : - : ";
																					}
																					else
																					{
																							if(${$tmp_io}[$i]<250)
																							{
																									$pdf_tmp_io=$pdf_tmp_io."Manhole Door : Close : ";
																							}
																							else
																							{
																									$pdf_tmp_io=$pdf_tmp_io."Manhole Door : Open : ";
																							}                                            
																					}                                                    
																			}
																			else if($io_typ_value1[1]=="door_open3")
																			{                                                            
																					if(${$tmp_io}[$i]=="")
																					{
																							$pdf_tmp_io=$pdf_tmp_io."Manhole Door2 : - : ";
																					}
																					else
																					{
																							if(${$tmp_io}[$i]<250)
																							{
																									$pdf_tmp_io=$pdf_tmp_io."Manhole Door2 : Close : ";
																							}
																							else
																							{
																									$pdf_tmp_io=$pdf_tmp_io."Manhole Door2 : Open : ";
																							}                                            
																					}                                                    
																			}
																		}
																		if($pdf_tmp_io=="")
																		{
																			$pdf_tmp_io="NA";
																		}
																		else
																		{
																			$pdf_tmp_io=substr($pdf_tmp_io,0,-2);
																		}
																	}
																	$speed1 = round($speed[$i],2);    									
																	echo"<input TYPE=\"hidden\" VALUE=\"$m\" NAME=\"temp[$k][SNo]\">";    									
																	if($report_type=='Person')
																	{
																		echo"<input TYPE=\"hidden\" VALUE=\"$vehiclename[$i]\" NAME=\"temp[$k][Person Name]\">";
																	}
																	else
																	{
																		echo"<input TYPE=\"hidden\" VALUE=\"$vehiclename[$i]\" NAME=\"temp[$k][Vehicle Name]\">";
																	}	
																	echo"<input TYPE=\"hidden\" VALUE=\"$vehicleserial[$i]\" NAME=\"temp[$k][IMEI]\">";
																	echo"<input TYPE=\"hidden\" VALUE=\"$datetime[$i]\" NAME=\"temp[$k][Date Time]\">";
																	$csv_string = $csv_string.$m.','.$vehiclename[$i].",".$vehicleserial[$i].','.$datetime[$i];
																	
																	if($report_type=='Person')
																	{
																		echo"<input TYPE=\"hidden\" VALUE=\"$vehiclenumber[$i]\" NAME=\"temp[$k][Mobile Number]\">";
																		$csv_string = $csv_string.','.$vehiclenumber[$i];
																	}
																	$placename[$i]=preg_replace('/भारत गणराज्य/', '', $placename[$i]);
																	$pdf_place_ref = $placename[$i];
																	$pdf_place_ref = str_replace(',',':',$pdf_place_ref);										
																	if($lat_lng_permission=="1" && $data_with_location=="1")
																	{
																													
																		echo"<input TYPE=\"hidden\" VALUE=\"$placename[$i]\" NAME=\"temp[$k][Place]\">";
																		echo"<input TYPE=\"hidden\" VALUE=\"$lat[$i]\" NAME=\"temp[$k][Latitude]\">";
																		echo"<input TYPE=\"hidden\" VALUE=\"$lng[$i]\" NAME=\"temp[$k][Longitude]\">";										
																		$csv_string = $csv_string.','.$pdf_place_ref.','.$lat[$i].','.$lng[$i]; 
																	}
																	else if($lat_lng_permission=="1")
																	{
																		echo"<input TYPE=\"hidden\" VALUE=\"$lat[$i]\" NAME=\"temp[$k][Latitude]\">";
																		echo"<input TYPE=\"hidden\" VALUE=\"$lng[$i]\" NAME=\"temp[$k][Longitude]\">";	
																		$csv_string = $csv_string.','.$lat[$i].','.$lng[$i];											
																	}
																	else if($data_with_location=="1")
																	{
																		echo"<input TYPE=\"hidden\" VALUE=\"$placename[$i]\" NAME=\"temp[$k][Place]\">";
																		$csv_string = $csv_string.','.$pdf_place_ref;											
																	}
																	else
																	{
																		if($report_type!='Person')
																		{
																			echo"<input TYPE=\"hidden\" VALUE=\"$speed1\" NAME=\"temp[$k][Speed(Kmph)]\">";	
																			echo"<input TYPE=\"hidden\" VALUE=\"$pdf_tmp_io\" NAME=\"temp[$k][Status]\">";
																			$csv_string = $csv_string.','.$speed1.",".$pdf_tmp_io."\n";
																			$latlng_location_flag=0;
																		}
																		else
																		{
																			$csv_string = $csv_string."\n";
																		}
																	}
																	if($latlng_location_flag==1)
																	{
																		if($report_type!='Person')
																		{
																			echo"<input TYPE=\"hidden\" VALUE=\"$speed1\" NAME=\"temp[$k][Speed(Kmph)]\">";	
																			echo"<input TYPE=\"hidden\" VALUE=\"$pdf_tmp_io\" NAME=\"temp[$k][Status]\">";
																			$csv_string = $csv_string.','.$speed1.",".$pdf_tmp_io."\n";
																		}
																		else
																		{
																			$csv_string = $csv_string."\n";
																		}
																	}
																	//echo"<input TYPE=\"hidden\" VALUE=\"$fuel_level[$i]\" NAME=\"temp[$i][Fuel(litres)]\">";                    
																	//$csv_string = $csv_string.$title.','.$m.','.$vehiclename[$i].','.$datetime[$i].','.$pdf_place_ref.','.$lat[$i].','.$lng[$i].','.$speed[$i]."\n"; 
																	break;
																}
																else
																{
																	$flag_found = 0;
																	$tmp_vname = $vname_post[$k]; 
																}
															}
															
															if($flag_found ==0)
															{
																$tmp="-";
																$m++;
																$latlng_location_flag=1;
																echo"<input TYPE=\"hidden\" VALUE=\"$m\" NAME=\"temp[$k][SNo]\">";
																if($report_type=='person')
																{
																	echo"<input TYPE=\"hidden\" VALUE=\"$tmp_vname\" NAME=\"temp[$k][Person Name]\">";
																}
																else
																{
																	echo"<input TYPE=\"hidden\" VALUE=\"$tmp_vname]\" NAME=\"temp[$k][Vehicle Name]\">";
																}
																
																echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][IMEI]\">";
																echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Date Time]\">";
																$csv_string = $csv_string.$m.','.$tmp_vname.','.$tmp.','.$tmp;
																if($lat_lng_permission=="1" && $data_with_location=="1")
																{
																	echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Place]\">";
																	echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Latitude]\">";
																	echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Longitude]\">";									
																	$csv_string = $csv_string.','.$tmp.','.$tmp.','.$tmp;  								
																}
																else if($lat_lng_permission=="1")
																{
																	echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Latitude]\">";
																	echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Longitude]\">";									
																	$csv_string = $csv_string.','.$tmp.','.$tmp;  								
																}
																else if($data_with_location=="1")
																{
																	echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Place]\">";
																	$csv_string = $csv_string.','.$tmp;  									
																}
																else
																{
																	if($report_type!='Person')
																	{
																		$latlng_location_flag=0;
																		echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Speed(Kmph)]\">";									
																		echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Status]\">";	
																		$csv_string = $csv_string.','.$tmp.','.$tmp."\n"; 
																	}
																	else
																	{
																		$csv_string = $csv_string."\n"; 
																	}
																}
																if($latlng_location_flag==1)
																{
																	if($report_type!='Person')
																	{
																		echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Speed(Kmph)]\">";									
																		echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Status]\">";	
																		$csv_string = $csv_string.','.$tmp.','.$tmp."\n"; 
																	}
																	else
																	{
																		$csv_string = $csv_string."\n"; 
																	}
																}
															}
														}
														echo'<input TYPE="hidden" VALUE="last data" NAME="csv_type">';
														echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
														echo'<br><center><input type="button" onclick="javascript:report_csv(\'report_getpdf_type3.php?size='.$sizev.'\');" value="Get PDF" class="noprint">&nbsp;
														<input type="button" onclick="javascript:report_csv(\'report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
														<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;';  						

													echo'</td>
													</tr>
												</table>
											</form>
										</div>';
						
						}
						else
						{
							echo'<br>						
							';
							print"<center><FONT color=\"Blue\" size=2><strong>No Data Found During These Date and Time For The Vehicle/Vehicles</strong></font></center>";
							//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=user_main.php\">";
						}
						
unlink($xml_path);
						
					?>
<?php
if($report_type=='Person')
{
    ?>
}
<?php
echo'<form name="dataLogSubmit" action="'.$pathToRoot.'/report.htm" method="POST"> <!-- this is for datlog option of person in google map track option-->';
?>
    
        <input type='hidden' name='dataLogImei' id='dataLogImei'>   
        <input type='hidden' name='start_date_map' id='start_date_map'> 
        <input type='hidden' name='end_date_map' id='end_date_map'> 
</form>
<?php
}
?>
<!--<br><br><center><input type="button" value="CLOSE" onlick="javascript:window.close();"></center>-->
					
		</BODY>
</HTML>
