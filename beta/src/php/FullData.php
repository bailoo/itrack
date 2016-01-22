<?php    
  set_time_limit(2000);
  
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once('common_xml_element.php');
  //include_once("get_io.php");
  include_once("util.fuel_calibration.php");
  include_once("calculate_distance.php");
  include_once("read_filtered_xml.php");     // INCLUDE READ FILTERED XML, PARAMETER PASSED FOR LAST DATA
  include_once("user_type_setting.php");
  include_once("select_landmark_report.php");  

	$query="SELECT field22 FROM account_feature WHERE account_id=$account_id";   
	$result = mysql_query($query,$DbConnection);
	$row=mysql_fetch_row($result);
	$lat_lng_permission=$row[0];
	$xml_path=$_POST["xmltowrite"];
	
  //echo "xmlpath=".$xml_path;
 // $place_name_arr=$_SESSION['place_name_arr1'];
  $startdate = $_POST['startdate'];
	$enddate = $_POST['enddate'];
	$time_interval = $_POST['time_interval'];
	$time_interval = $time_interval/60;
	
  $geocodes_string = $_POST['geocodedPostcodes'];  
  $geocodes_string = substr($geocodes_string, 0, -1);
  $geocodes_string = str_replace('"','',$geocodes_string);

  //echo "<br>GEOCODES STRING=".$geocodes_string;  
  $location = explode(':',$geocodes_string);
  //print_r($location);
	if($data_with_category==6)
	{
		$report_type="Person";
	}
	if($report_type=="Vehicle")
	{
		$vehicleserial=unserialize($vehicleserial);
		//print_r($vehicleserial);
		$lat = unserialize($lat);
		$lng = unserialize($lng);
		$datetime = unserialize(base64_decode($datetimeXml));
		//print_r($datetime);
		$vehiclename = unserialize($vehiclename);
		$vehicletype = unserialize($vehicletype);
		$speed = unserialize($speed);
		$cumdist = unserialize($cumdist);
		$io1 = unserialize($io1);
		$io2 = unserialize($io2);
		$io3 = unserialize($io3);
		$io4 = unserialize($io4);
		$io5 = unserialize($io5);
		$io6 = unserialize($io6);
		$io7 = unserialize($io7);
		$io8 = unserialize($io8);
		//read_track_xml($xml_path, &$vehicleserial, &$lat, &$lng, &$alt, &$datetime, &$vehiclename, &$vehicletype, &$speed, &$cumdist, &$io1, &$io2, &$io3, &$io4, &$io5, &$io6, &$io7, &$io8);
	}
	else
	{
            $vehicleserial=unserialize($vehicleserial);
		$lat = unserialize($lat);
		$lng = unserialize($lng);
               // print_r($lat);
		$datetime = unserialize(base64_decode($datetimeXml));	
                $vehiclename = unserialize(base64_decode($vehiclename));
                //echo "<br>";
               // print_r($vehiclename);
		$vehicletype = unserialize($vehicletype);		
		$cumdist = unserialize($cumdist);
                $cellname = unserialize($cellname);
                $serverDatetime = unserialize(base64_decode($serverDatetime));
                $ver =unserialize(base64_decode($ver));
		//read_track_xml_person($xml_path, &$vehicleserial, &$lat, &$lng, &$datetime, &$vehiclename, &$vehicletype, &$cumdist);
	}
	$size = sizeof($vehicleserial);	
	/*echo"size=".$size."<br>";
	for($i=0;$i<$size;$i++)
	{		
		echo "lat=".$lat."lng=".$lng."datetime=".$datetime."<br>";
	}*/
	
	if($text_report_io_element!="tmp_str")
	{
		$text_report_io_element=explode(":",$text_report_io_element);
		$io_count=count($text_report_io_element);
	}
    
  //echo "<br>6";
?>

<HTML>
	<TITLE>
		
	</TITLE>

<head>	

		<style type="text/css">
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
		</style>
	
		<link rel="stylesheet" type="text/css" href="../css/menu.css">
		<script type=text/javascript src="../js/menu.js"></script>
		
		<script type="text/javascript" language="javascript">
			function create_pdf()
			{	
				//alert("test");
				document.text_data_report.target = "_blank";
				document.text_data_report.action="report_getpdf_type3.php?size=<?php echo $size; ?>";
			}	

			function mail_report()
			{
				document.text_data_report.target = "_self";
				document.text_data_report.action="mail_lastdata_report.php?size=<?php echo $size; ?>";
			}
		</script>
	<?php
		echo'
		<style type="text/css">
			div.scrollTableContainer 
			{
				height: 475px;
				overflow: auto;
				<!--width: 980px;-->
				margin: 15px 0 0 0;
				position: relative;			
			}

			/* All browsers (but especially IE) -18*/
			div.scrollTableContainer table 
			{
				<!--width: 980px;-->
				overflow: auto;
				height: 475px;
				overflow-x: hidden;
			}';
			if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == false))
			{
				echo'/* Modern browsers (but especially firefox ) */
				div.scrollTableContainer table
				{
					<!--width: 980px;-->
					overflow: auto;
					height: 455px;
					overflow-x: hidden;
				}';
			}

			else if(isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == true))
			{
			/* Modern browsers (but especially firefox )*/
			echo'div.scrollTableContainer table
				{
					width: 980px;
					overflow: auto;
					height: 455px;
					overflow-x: hidden;
				}';
			}

			echo'div.scrollTableContainer table>tbody 
			{		
				height: 440px;
				width: 980px;
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
			.normal1 { background-color: #F8F8FF }
			.highlight1 { background-color:  #C6DEFF }
			.normal2 { background-color: #FFFDF9 }
			.highlight2 { background-color:  #C6DEFF }
		</style>
		
    <script type="text/javascript">
    ';	
		
    $root_dir = getcwd();
    $path_mapwindow_jsmodule = $root_dir."/map_window/mapwindow_jsmodule.php";	
    include($path_mapwindow_jsmodule);      
    //include("src/php/map_window/mapwindow_jsmodule.php");
	  
	  echo '</script>';
  
  ?>
	
	<script type="text/javascript">
		
    function report_pdf_csv(target_file)
    {
      //alert("reportcsv");
      document.forms[0].action = target_file;    
      document.forms[0].submit();
    }    
    /*function map_window(vname,datetime,lat,lng)
		{			
			document.getElementById("window").style.display = '';
			load_vehicle_on_map(vname,datetime,lat,lng);						
		}

		function showmsg()
		{
			alert(document.getElementById('d1'));
			document.getElementById('d1').style.display="";
			document.getElementById('d2').style.display="";
		}	*/
    			
	</script>	
</head>

<body>
<?php
//echo "report type=".$report_type."<br>";
//include("src/php/map_window/floating_map_window.php");
$root_dir = getcwd();
$path_mapwindow = $root_dir."/map_window/floating_map_window.php";
include_once($path_mapwindow);
?>
	<form  name="text_data_report" method="post" target="_blank"> 
	<?php 
	echo"<input TYPE=\"hidden\" VALUE=\"$access\" NAME=\"access\">";
	echo'<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%" align="center">
			<TR>
				<TD align="center">
					<table border="0" width = "100%" cellspacing="2" cellpadding="0" align="center">
						<tr>
							<td height=10 class="text" align="center" width="79%">
								<b>
								<font color="#23238E">
									Full Data Report :
								</font>
								<font color=green>
									'.$vehiclename[0].'</font>&nbsp;<font color=red>('.$vehicleserial[0].')
								</font> 
								<br> 
								<font color="#23238E">
									(Date Time:
								</font>											
								</b>
								<font color="green">
									<b>'.$startdate.'</b>
								</font>						  
								<font color="red">
								<b>-</b>
								</font>						  
								<font color="green">									
								<b>'.$enddate.'</b>)
								</font>
							</td>
						</tr>
					</table>';
						//echo 'time_interval='.$time_interval;
						echo'<center><div style="font-size:10px;"><strong>(&nbsp;Time Interval &nbsp;:&nbsp;'.$time_interval;
						echo'&nbsp; mins)</strong></center></div>';
						if($size>0)
						{
							$csv_string = "";
							echo'
							<!--<div class="scrollTableContainer">	-->	
							<div align="center" class="scrollTableContainer">
								<table align="center" border="0" width="100%" bordercolor="#689FFF"  cellspacing="6" cellpadding="8">
									<thead>
										<tr bgcolor="#EAC752">
											<th class="text" align="left" width="1%">
												<b>&nbsp;SNo</b>
											</th>';			
											//echo'<th class="text" align="left" width="8%"><b>&nbsp;'.$report_type.' Name</b></th>';				
											echo'<th class="text" align="left" width="4%">
													<b>Date</b>
												</th>				
												<th class="text" align="left" width="4%">
													<b>Time</b>
												</th>	';
											if($data_with_location=="1")
											{
											echo'<th class="text" align="left" width="18%">
													<b>Location</b>
												</th>';
											}
											echo'<!--<th class="text" align="left" width="12%">
													<b>GSM CBM</b>
												</th>-->';
											if($report_type!="Person")
											{
											echo'<th class="text" align="left" width="3%">
													<b>Speed</b>
												</th>';
											} 
                                                                                        if($report_type=="Person")
											{
											echo'<th class="text" align="left" width="3%">
                                                                                                    <b>Boot Status</b>
												</th>';
                                                                                        echo'<th class="text" align="left" width="3%">
                                                                                                    <b>Server Date Time</b>
												</th>';
                                                                                        echo'<th class="text" align="left" width="3%">
                                                                                                    <b>Version</b>
                                                                                            </th>';
											}
											/*echo'<th class="text" align="left" width="3%">
													<b>Dist.(Km)</b>
												</th>';*/
											if($lat_lng_permission)
											{
											echo'<th class="text" align="left" width="4%">
													<b>Latitude</b>
												</th>
												<th class="text" align="left" width="4%">
													<b>Longitude</b>
												</th>
												<th class="text" align="left" width="4%">
													<b>Altitude</b>
												</th>';
											}
											/*if($report_type!="Person")
											{
											echo'<th class="text" align="left" width="3%">
													<b>Fuel (litre)</b>
												</th>';
											}*/ 																					
											if($io_count>0)
											{
												for($k=0;$k<sizeof($text_report_io_element);$k++)
												{												
													$text_report_io_element1=explode("^",$text_report_io_element[$k]);											
													if($text_report_io_element1[1]!="")
													{
														if($text_report_io_element1[1]=="door_open")
														{
														echo'<th class="text" align="left" width="18%">
																<b>Delivery Door</b>
															</th>';
														}
														else if($text_report_io_element1[1]=="door_open2")
														{
														echo'<th class="text" align="left" width="18%">
																<b>Manhole Door</b>
															</th>';
														}
														else if($text_report_io_element1[1]=="door_open3")
														{
														echo'<th class="text" align="left" width="18%">
																<b>Manhole Door2</b>
															</th>';
														}
														else
														{
															echo'<th class="text" align="left" width="18%">
															<b>'.$text_report_io_element1[1].'</b>
															</th>';	
														}
													}
												}
											}
											echo'
											</tr>						
										</thead>
									<tbody>';
									//include("get_location.php");	
									$tmp = 0;
									//$j=0;
									$count=1;
									//echo "<br>size=========".$size;
									$fuel_level_tmp =0;	
									for($i=0;$i<$size;$i++)
									{ 
										if($i==0)
										{
											if($report_type=='Person')
											{
												$title1="Person :- ".$vehiclename[$i]." (".$vehicleserial[$i]." )  DateTime :".$startdate." - ".$enddate." )";
											}
											else
											{
												$title1="Vehicle :- ".$vehiclename[$i]." (".$vehicleserial[$i]." )  DateTime :".$startdate." - ".$enddate." )";;
											}
											echo"<input TYPE=\"hidden\" VALUE=\"$title1\" NAME=\"title\">";
											$csv_string = $csv_string.$title1."\n";
											if($io_count<=0)
											{
												if($data_with_location=="1")
												{
													if($report_type=="Person")
													{ 
														$csv_string = $csv_string."SNo,Date,Time,Location,Dist(km),Boot Status,Server Date Time,Version,Latitude,Longitude,Altitude\n"; 
													}
													else
													{
														$csv_string = $csv_string."SNo,Date,Time,Location,Speed,Dist(km),Latitude,Longitude,Altitude\n"; 
													}
												}
												else
												{
													if($report_type=="Person")
													{
														$csv_string = $csv_string."SNo,Date,Time,Dist(km),Boot Status,Server Date Time,Version,Latitude,Longitude,Altitude\n"; 
													}
													else
													{
														$csv_string = $csv_string."SNo,Date,Time,Speed,Dist(km),Latitude,Longitude,Altitude\n"; 
													}
												}
											}											
											if($io_count>0)
											{
												if($data_with_location=="1")
												{
													//$csv_string = $csv_string."SNo,Date,Time,Location,Speed,Dist(km),Latitude,Longitude,Altitude";
													//$csv_string = $csv_string."SNo,Date,Time,Location,Speed,Dist(km)";
                                                                                                    $csv_string = $csv_string."SNo,Date,Time,Location,Speed";
												}
												else
												{
													//$csv_string = $csv_string."SNo,Date,Time,Speed,Dist(km),Latitude,Longitude,Altitude";
													//$csv_string = $csv_string."SNo,Date,Time,Speed,Dist(km)";
                                                                                                        $csv_string = $csv_string."SNo,Date,Time,Speed";
												}
												for($k=0;$k<sizeof($text_report_io_element);$k++)
												{												
													$text_report_io_element1=explode("^",$text_report_io_element[$k]);	
													if($text_report_io_element1[1]=="door_open")
													{
														$csv_string=$csv_string.",Delivery Door ";
													}
													else if($text_report_io_element1[1]=="door_open2")
													{
														$csv_string=$csv_string.",Manhole Door ";
													}
													else if($text_report_io_element1[1]=="door_open3")
													{
														$csv_string=$csv_string.",Manhole Door2 " ;
													}
													else if($text_report_io_element1[1]!="")
													{
														$csv_string=$csv_string.",".$text_report_io_element1[1];																								
													}
												}
												$csv_string=substr($csv_string,0,-1);
												$csv_string=$csv_string."\n";
												//echo "csv_string=".$csv_string."<br>";
											}
										}
											
										$lt1 = $lat[$i];
										$lng1 = $lng[$i] ;
										$alt1 = "-";
										//echo "<br>lt1=".$lt1." lng1=".$lng1;
										/*if($access=='Zone')
										{
											get_location($lt1,$lng1,$alt1,&$place,$zoneid,$DbConnection);
										}
										else
										{
											get_location($lt1,$lng1,$alt1,&$place,$DbConnection);
										} 
										$placename[$i] = $place;*/
										///////////////////
										if($data_with_location=="1")
										{
                                                                                    if($report_type=="Person")
                                                                                    {
                                                                                        if((strlen($lt1)>5) && ($lt1!="0.0") && (strlen($lng1)>5) && ($lng1!="0.0"))
                                                                                        {
                                                                                            $landmark="";
                                                                                            get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION  		
                                                                                            $place = $landmark;      
                                                                                            if($place=="")
                                                                                            {
                                                                                                    //$place_name_arr[$i]=preg_replace('/???? ???????/', '', $place_name_arr[$i]);																		
                                                                                                    //get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
                                                                                                    $place = preg_replace('/भारत गणराज्य/', '' , $location[$i]);
                                                                                                    $place = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $location[$i]);
                                                                                            }  		
                                                                                            //echo "P:".$place;
                                                                                            $placename[$i] = $place;
                                                                                            $placename[$i] = $placename[$i]."-(".$lt1.",".$lng1.")";
                                                                                        }
                                                                                        else
                                                                                        {
                                                                                            $placename[$i]="(".$lt1.",".$lng1.")";
                                                                                        }
                                                                                    }
                                                                                    else
                                                                                    {
                                                                                        $landmark="";
											get_landmark($lt1,$lng1,$landmark);    // CALL LANDMARK FUNCTION  		
											$place = $landmark;      
											if($place=="")
											{
												//$place_name_arr[$i]=preg_replace('/???? ???????/', '', $place_name_arr[$i]);																		
												//get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
												$place = preg_replace('/भारत गणराज्य/', '' , $location[$i]);
												$place = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '' , $location[$i]);
											}  		
											//echo "P:".$place;
											$placename[$i] = $place;
											$placename[$i] = $placename[$i]."-(".$lt1.",".$lng1.")";
                                                                                    }
										}
										/////////////////////
										$dt2 = explode(" ",$datetime[$i]);  
										if($count%2==0)
										{
									echo'<tr valign="top" bgcolor="#F8F8FF" onMouseOver="this.className=\'highlight1\'" onMouseOut="this.className=\'normal1\'">';
										}
										else
										{
									echo'<tr valign="top" bgcolor="#FFFDF9" onMouseOver="this.className=\'highlight2\'" onMouseOut="this.className=\'normal2\'">';
										} 		
										echo'<td class="text" align="left"  width="1%">
												&nbsp;'.$count.'
											</td>';
											echo"<input TYPE=\"hidden\" VALUE=\"$count\" NAME=\"temp[$i][SNo]\">";
											$csv_string = $csv_string.$count;
										/*if($i%2==0)
										{
											echo'<td class="text" align="left">
													<font color="red">
														'.$vehiclename[$i].'
													</font>
												</td>';
										}
										else
										{
											echo'<td class="text" align="left">
													<font color="red">
														'.$vehiclename[$i].'
													</font>
												</td>';
										}*/
										
										if($dt2[0]=="")
										{
										echo'<td class="text" align="center">
												<font color="red">-</font>
											</td>';
											echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Date]\">";
											$csv_string = $csv_string.",-";
										}
										else
										{
										echo'<td class="text" align="left" width="4%">
												'.$dt2[0].'											
											</td>';
											echo"<input TYPE=\"hidden\" VALUE=\"$dt2[0]\" NAME=\"temp[$i][Date]\">";
											$csv_string = $csv_string.','.$dt2[0];
										}										
										if($dt2[1]=="")
										{
										echo'<td class="text" align="center">
												<font color="red">-</font>
											</td>';
										echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Time]\">";
										$csv_string = $csv_string.",-";
										}
										else
										{
										echo'<td class="text" align="left" width="4%">
												'.$dt2[1].'
											</td>';
											echo"<input TYPE=\"hidden\" VALUE=\"$dt2[1]\" NAME=\"temp[$i][Time]\">";
											$csv_string = $csv_string.','.$dt2[1];
										}
										if($data_with_location=="1")
										{
											$pdf_place_ref = $placename[$i];
											$pdf_place_ref = str_replace(',',':',$pdf_place_ref);
											if($placename[$i]=="")
											{
											echo'<td class="text" align="center">
													<font color="red">-</font>
												</td>';
												echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Place]\">"; 
												$csv_string = $csv_string.',-';
											}
											else
											{
												$lt_tmp = substr($lat[$i], 0, -1);
												$lng_tmp = substr($lng[$i], 0, -1);
												//echo'<td class="text" align="left" width="4%">'.$placename[$i].'</b>&nbsp;<a href="javascript:map_window(\''.$vehiclename[$i].'\',\''.$datetime1.'\','.$lt_tmp.','.$lng_tmp.');"><font color="green">Show location</font></a></td>';
											echo'<td class="text" align="left" width="4%">
													'.$placename[$i].'
													&nbsp;
												</td>';
												echo"<input TYPE=\"hidden\" VALUE=\"$placename[$i]\" NAME=\"temp[$i][Place]\">";
												$csv_string = $csv_string.','.$pdf_place_ref;
											}																
										}
                                                                        if($report_type=="Person")
									{
                                                                            if($cellname[$i]=="")
                                                                            {
                                                                            echo'<td class="text" align="center">
                                                                                    <font color="red">-</font>
                                                                                </td>';
                                                                            echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Boot Status]\">";
                                                                            $csv_string = $csv_string.',-';
                                                                            }
                                                                            else
                                                                            {
                                                                                $tmpCellNameArr=explode(",",$cellname[$i]);
                                                                                $bootStatus=$tmpCellNameArr[sizeof($tmpCellNameArr)-1];
                                                                            echo'<td class="text" align="left" width="4%">
                                                                                            '.$bootStatus.'
                                                                                    </td>';
                                                                                    echo"<input TYPE=\"hidden\" VALUE=\"$bootStatus\" NAME=\"temp[$i][Boot Status]\">";
                                                                                    $csv_string = $csv_string.','.$bootStatus;
                                                                            } 
                                                                            if($serverDatetime[$i]=="")
                                                                            {
                                                                            echo'<td class="text" align="center">
                                                                                    <font color="red">-</font>
                                                                                </td>';
                                                                            echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Server Date Time]\">";
                                                                            $csv_string = $csv_string.',-';
                                                                            }
                                                                            else
                                                                            {
                                                                            echo'<td class="text" align="left" width="4%">
                                                                                            '.$serverDatetime[$i].'
                                                                                    </td>';
                                                                                    echo"<input TYPE=\"hidden\" VALUE=\"$serverDatetime[$i]\" NAME=\"temp[$i][Server Date Time]\">";
                                                                                    $csv_string = $csv_string.','.$serverDatetime[$i];
                                                                            } 
                                                                            if($ver[$i]=="")
                                                                            {
                                                                            echo'<td class="text" align="center">
                                                                                    <font color="red">-</font>
                                                                                </td>';
                                                                            echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][version]\">";
                                                                            $csv_string = $csv_string.',-';
                                                                            }
                                                                            else
                                                                            {
                                                                            echo'<td class="text" align="left" width="4%">
                                                                                            '.$ver[$i].'
                                                                                    </td>';
                                                                                    echo"<input TYPE=\"hidden\" VALUE=\"$ver[$i]\" NAME=\"temp[$i][version]\">";
                                                                                    $csv_string = $csv_string.','.$ver[$i];
                                                                            } 
                                                                        }
									if($report_type!="Person")
									{      
										if($speed[$i]=="")
										{
										echo'<td class="text" align="center">
												<font color="red">-</font>
											</td>';
										echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Speed(Kmph)]\">";
										$csv_string = $csv_string.',-';
										}
										else
										{
										echo'<td class="text" align="left" width="4%">
												'.$speed[$i].'
											</td>';
											echo"<input TYPE=\"hidden\" VALUE=\"$speed[$i]\" NAME=\"temp[$i][Speed(Kmph)]\">";
											$csv_string = $csv_string.','.$speed[$i];
										}
									}			
									/*if($cumdist[$i]=="")
									{
										echo'<td class="text" align="center">
												<font color="red">-</font>
											</td>';
											echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Distance Covered(Km)]\">";
											$csv_string = $csv_string.',-';
									}
									else
									{
										echo'<td class="text" align="left" width="4%">
												'.$cumdist[$i].'
											</td>';
										echo"<input TYPE=\"hidden\" VALUE=\"$cumdist[$i]\" NAME=\"temp[$i][Distance Covered(Km)]\">";
										$csv_string = $csv_string.','.$cumdist[$i];
									}*/
									if($lat_lng_permission=="1")
									{ 
										if($lt1=="")
										{
											echo'<td class="text" align="center">
													<font color="red">-</font>
												</td>';
											echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Latitude]\">";
											$csv_string = $csv_string.',-';
										}
										else
										{
											echo'<td class="text" align="left" width="4%">
													'.$lt1.'
												</td>';
												echo"<input TYPE=\"hidden\" VALUE=\"$lt1\" NAME=\"temp[$i][Latitude]\">";
												$csv_string = $csv_string.','.$lt1;
										}
										if($lng1=="")
										{
										echo'<td class="text" align="center">
												<font color="red">-</font>
											</td>';
										echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Longitude]\">";
										$csv_string = $csv_string.',-';
										}
										else
										{
										echo'<td class="text" align="left" width="4%">
												'.$lng1.'
											</td>';
										echo"<input TYPE=\"hidden\" VALUE=\"$lng1\" NAME=\"temp[$i][Longitude]\">";
										$csv_string = $csv_string.','.$lng1;
										}
										if($alt1=="")
										{
										echo'<td class="text" align="center">
												<font color="red">-</font>
											</td>';
										echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][Altitude]\">";	
										$csv_string = $csv_string.',-';
										}
										else
										{
										echo'<td class="text" align="left" width="4%">
												'.$alt1.'
											</td>';
										echo"<input TYPE=\"hidden\" VALUE=\"$alt1\" NAME=\"temp[$i][Altitude]\">";
										$csv_string = $csv_string.','.$alt1;
										}						
									}								
									if($io_count<=0)
									{
									$csv_string=$csv_string."\n";
									}
									if($io_count>0)
									{										
										for($j=0;$j<sizeof($text_report_io_element);$j++)
										{
											$text_report_io_element1=explode("^",$text_report_io_element[$j]);
											$tmp_io="io".$text_report_io_element1[0];
											//echo "tmp_id1=".$tmp_io."tmp_io2=".${$tmp_io}[$i]."<br>";
											$pdf_io_value=${$tmp_io}[$i];
											if($text_report_io_element1[1]=="temperature")
											{
												//echo "in if<br>";												
											    if((${$tmp_io}[$i])>=-30 && (${$tmp_io}[$i]<=70))
												{
													echo'<td class="text" align="left" width="4%">'.${$tmp_io}[$i].'</b></td>';
													echo"<input TYPE=\"hidden\" VALUE=\"$pdf_io_value\" NAME=\"temp[$i][$text_report_io_element1[1]]\">";
													$csv_string=$csv_string.','.${$tmp_io}[$i];	
												}
												else
												{
													echo'<td class="text" align="left" width="4%">-</td>';
													echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][$text_report_io_element1[1]]\">";
													$csv_string=$csv_string.',-';
												}																								
											}
											else if($text_report_io_element1[1]=="engine")
											{												
												if(${$tmp_io}[$i]<=350)
												{
												echo'<td class="text" align="left">
														Off
													</td>';
												echo"<input TYPE=\"hidden\" VALUE=\"ON\" NAME=\"temp[$i][$text_report_io_element1[1]]\">";
													$csv_string=$csv_string.',ON';
												}
												else
												{
												echo'<td class="text" align="left" width="4%">
														ON
													</td>';
												echo"<input TYPE=\"hidden\" VALUE=\"$pdf_io_value\" NAME=\"temp[$i][$text_report_io_element1[1]]\">";
												$csv_string=$csv_string.',Off';	
												}
											}
											else if($text_report_io_element1[1]=="ac")
											{
													if(${$tmp_io}[$i]>500)
													{
													echo'<td class="text" align="left">
																	Off
															</td>';
													echo"<input TYPE=\"hidden\" VALUE=\"OFF\" NAME=\"temp[$i][$text_report_io_element1[1]]\">";
															$csv_string=$csv_string.',OFF';
													}
													else
													{
													echo'<td class="text" align="left" width="4%">
																	ON
															</td>';
													echo"<input TYPE=\"hidden\" VALUE=\"ON\" NAME=\"temp[$i][$text_report_io_element1[1]]\">";
													$csv_string=$csv_string.',ON';
													}
											}
											else if($text_report_io_element1[1]=="door_open")
											{
													if(${$tmp_io}[$i]<250)
													{
													echo'<td class="text" align="left">
																Close
														</td>';
													echo"<input TYPE=\"hidden\" VALUE=\"Close\" NAME=\"temp[$i][Delivery Door]\">";
															$csv_string=$csv_string.',Close';
													}
													else
													{
													echo'<td class="text" align="left" width="4%">
																Open
															</td>';
													echo"<input TYPE=\"hidden\" VALUE=\"Open\" NAME=\"temp[$i][Delivery Door]\">";
													$csv_string=$csv_string.',Open';
													}
											}
											else if($text_report_io_element1[1]=="door_open2")
											{
													if(${$tmp_io}[$i]<250)
													{
													echo'<td class="text" align="left">
																	Close
															</td>';
													echo"<input TYPE=\"hidden\" VALUE=\"Close\" NAME=\"temp[$i][Manhole Door]\">";
															$csv_string=$csv_string.',Close';
													}
													else
													{
													echo'<td class="text" align="left" width="4%">
																	Open
															</td>';
													echo"<input TYPE=\"hidden\" VALUE=\"Open\" NAME=\"temp[$i][Manhole Door]\">";
													$csv_string=$csv_string.',Open';
													}
											}
											else if($text_report_io_element1[1]=="door_open3")
											{
													if(${$tmp_io}[$i]<250)
													{
													echo'<td class="text" align="left">
																	Close
															</td>';
													echo"<input TYPE=\"hidden\" VALUE=\"Close\" NAME=\"temp[$i][Manhole Door2]\">";
															$csv_string=$csv_string.',Close';
													}
													else
													{
													echo'<td class="text" align="left" width="4%">
															Open
															</td>';
													echo"<input TYPE=\"hidden\" VALUE=\"Open\" NAME=\"temp[$i][Manhole Door2]\">";
													$csv_string=$csv_string.',Open';
													}
											}

											else
											{											
												if(${$tmp_io}[$i]=="")
												{
												echo'<td class="text" align="center">
														<font color="red">-</font>
													</td>';
												echo"<input TYPE=\"hidden\" VALUE=\"-\" NAME=\"temp[$i][$text_report_io_element1[1]]\">";
													$csv_string=$csv_string.',-';
												}
												else
												{
												echo'<td class="text" align="left" width="4%">
													'.${$tmp_io}[$i].'
												</td>';
												echo"<input TYPE=\"hidden\" VALUE=\"$pdf_io_value\" NAME=\"temp[$i][$text_report_io_element1[1]]\">";
												$csv_string=$csv_string.','.${$tmp_io}[$i];	
												}	
											}												
										}
										$csv_string=$csv_string."\n";										
									}
						
									// GET FUEL LEVEL
									/*$queryc = "SELECT filename as fname FROM fuelcalib_file_assignment WHERE vehicle_id=(SELECT vehicle_id FROM vehicle_assignment ".
									"WHERE device_imei_no='$vehicleserial[$i]' AND status=1) AND status=1";
									//echo $queryc;
									$resc = mysql_query($queryc, $DbConnection);
									$rowc = mysql_fetch_object($resc);          
									$calibfile = $rowc->fname; 
									//echo "<br>query_c=".$queryc." ,fuel=".$fuel[$i]." calibfile=".$calibfile."<br>";
									if($fuel[$i]>30 && $fuel[$i]<4096)
									{
										$fuel_level_tmp = get_calibrated_fuel_level($fuel[$i], $calibfile); 
									}*/	
									/*else
									{
										//$fuel_level_tmp = 0;
										$fuel_level_tmp = $fuel_display; 
									} */    	
									//$fuel_level[$i] = round($fuel_level_tmp,2);   
									////////////////////////////   
									/*if($report_type!="Person")
									{               			
										echo'<td class="text" align="left" width="4%">'.$fuel_level[$i].'</b></td>';
									} */		  
										echo'</tr>'; 										
										$count++;
									} //for i closed  			
									echo'</tbody>
									</table>
								</div>';	
								if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == false))
								{
								echo'<br>
										<div align="center">
											<TABLE align="center" BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">				
												<tr valign="top"> 
													<td width="40%">
														&nbsp;
													</td>
													<td>							
													</td>							
													<td align="right">								
														<a href="#" onclick="window.close()" style="text-decoration:none;">
															<font color="green" size="2"><b>Close</b></font>
														</a>
														<img src="../../images/close_win.png" align="absmiddle" style="widht:20px;height:20px;border:none;" onclick="window.close()">
													</td>';	
								}
								else if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') == true))
								{
								echo'<br>
										<div align="center">
											<TABLE align="center" BORDER="0" CELLPADDING="0" CELLSPACING="0" width="100%">				
												<tr valign="top"> 
													<td width="40%">
														&nbsp;
													</td>
													<td>								
													</td>							
													<td align="right">								
														<a href="#" onclick="window.close()" style="text-decoration:none;">
															<font color="green" size="2"><b>Close</b></font>
														</a>
														<img src="../../images/close_win.png" align="absmiddle" style="widht:20px;height:20px;border:none;" onclick="window.close()">
													</td>';	
								}
											echo'</tr>
										</table>
									</div>';		
									unlink($xml_path);
									echo'<input TYPE="hidden" VALUE="full data" NAME="csv_type">';
									echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
									echo'<br><center><input type="button" onclick="javascript:report_pdf_csv(\'report_getpdf_type3.php?size='.$size.'\');" value="Get PDF" class="noprint">&nbsp;
											<input type="button" onclick="javascript:report_pdf_csv(\'report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
											<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;';  						
						}
						else
						{
							echo'<br>'; 
							if($report_type!="Person")
							{
								print"<center>
										<FONT color=\"Blue\" size=2>
											<strong>
												No Data Found During These Date and Time For The Person/Persons
											</strong>
										</font>
									</center>";
							}
							else
							{
								print"<center>
										<FONT color=\"Blue\" size=2>
											<strong>
												No Data Found During These Date and Time For The Vehicle/Vehicles
											</strong>
										</font>
									</center>";
							}
						}
						?>

</form>		
</BODY>

</HTML>
