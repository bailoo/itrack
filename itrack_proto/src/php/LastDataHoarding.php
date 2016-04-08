<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
  include_once("get_io.php");
  include_once("util.fuel_calibration.php");
  include_once("calculate_distance.php");
  include_once("read_filtered_xml.php");      // INCLUDE READ FILTERED XML, PARAMETER PASSED FOR LAST DATA
  include_once("user_type_setting.php");
  include_once("select_landmark_report.php");
  
  $xml_path=$_POST["xmltowrite"];
  $vserial=$_POST["vserial"];
  //$vname=$_POST["vname"];  
  
  $vserial_post = explode(',',$vserial);
  //$vname_post = explode(':',$vname);
  
  //echo "<br>size:serial=".sizeof($vserial_post)." ,name:".sizeof($vname_post);

  //echo "xml_path=".$xml_path."<br>";
  //$xml_path= "xml_tmp/filtered_xml/tmp_1295703104897.xml";
  
  //$startdate = $_POST['startdate'];
  //$enddate = $_POST['enddate'];
  //$linetowrite = "\n<marker sts=\"".$servertime."\" hname=\"".$hoarding_name."\" htype=\"".$hoarding_type."\"  hnumber=\"".$hoarding_number."\"  uploadedfrom=\"".$uploaded_from."\"  place=\"".$placename2."\"  picid=\"".$pic_id."\"  lname=\"".$landmark_name."\" imei=\"".$vserial[$i]."\" lat=\"".$lat_lng[0]."\" lng=\"".$lat_lng[1]."\" alt=\"".$alt."\"/>";	
  read_lp_xml_hoarding($xml_path, &$datetime, &$vehicleserial, &$vehiclename, &$lat, &$lng, &$vehicletype, &$vehiclenumber, &$place, &$uploadedfrom,&$picid, &$lname , &$remark , &$cellinfo);
  $size = sizeof($vehicleserial);
  //echo "<br>size:".$size;
  
  /*for($k=0;$k<sizeof($vserial_post);$k++)
	{
	  echo "<br>vname:".$vname_post[$k];
  }*  
  //echo "<br>size=========".$size;	
?>

<HTML>
	<TITLE>
		Hoarding Tracking Pro-Innovative Embedded Systems Pvt. Ltd.
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
  //include($path_mapwindow_jsmodule);	
  
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

  //include($path_mapwindow);
  
?>

	<td STYLE="background-color:white;width:85%;">
		<br>
			
			<?php echo'<input TYPE="hidden" VALUE="$access" NAME="access">';?>		
			
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
										Last Data Report- Hoarding
									</font>											
								</b>
								 
							  </td>';

						echo'
							</tr>
						</table>';							
						
						if($size>0)
						{	
							echo'<br>';

							echo' 
								<table style="font-size: 9pt;" width="100%"border="0" width="98%" bordercolor="#689FFF" align="center" cellspacing="4" cellpadding="4">
									<tr bgcolor="#EAC752">
										<td class="text" width="2%"><b>SNo</b></td>
                    <td class="text" width="10%"><b>'.$report_type.' Name</b></td>
                    <td class="text" width="10%"><b>Hoarding Number</b></td>
                    <td class="text" width="13%"><b>Hoarding Location</b></td>
                    <td class="text" width="8%"><b>Date</b></td>
                    <td class="text" align="left" width="7%"><b>Time</b></td>
                    <td class="text" width="18%"><b>Picture Location</b></td>
					<td class="text" width="6%"><b>Latitude</b></td>
					<td class="text" width="7%"><b>Longitude</b></td>
                    <td class="text" width="5%"><b>Cell Info</b></td>
										<td class="text" width="7%"><b>Uploaded From</b></td>
										<td class="text" width="10%"><b>Remarks</b></td>
										<td class="text" width="10%"><b>Picture</b></td>
                  </tr>   
								';

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
                       /*
                      //echo $vname1[$i];
    									$lt1 = $lat[$i];
    									$lng1 = $lng[$i];
    									$alt1 = "-";								
                      ///////////////////
                      $landmark="";
                      get_landmark($lt1,$lng1,&$landmark);    // CALL LANDMARK FUNCTION
                  		
                      $place = $landmark;
                      
                      if($place=="")
                      {
                        get_location($lt1,$lng1,$alt1,&$place,$DbConnection);    // CALL GOOGLE LOCATION FUNCTION
                      }
                  		
                      //echo "P:".$place;
                      $placename[$i] = $place;                  
                      /////////////////////	
    									*/
    									$dt = explode(" ",$datetime[$i]);
    
    									if($i%2==0)
    									{
    									echo'
    									<tr valign="top" bgcolor="#F8F8FF" onMouseOver="this.className=\'highlight1\'" onMouseOut="this.className=\'normal1\'">';
    									}
    									else
    									{
    									echo'
    									<tr valign="top" bgcolor="#FFFDF9" onMouseOver="this.className=\'highlight2\'" onMouseOut="this.className=\'normal2\'">';
    									}										
    									
    									echo'<td class="text">'.$m.'</td>';
  										if($vehiclename[$i]=="")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
  											echo'<td class="text">'.$vehiclename[$i].'</td>';
  										}
  										
  										if($vehiclenumber[$i]=="")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
  											echo'<td class="text">'.$vehiclenumber[$i].'</td>';
  										}
  										
  										if($lname[$i]=="")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
  											echo'<td class="text">'.$lname[$i].'</td>';
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
  													
  										$dt_tmp1 = $dt[0];
  										$dt_tmp2 = $dt[1];
  										$datetime1 = $dt_tmp1." ".$dt_tmp2;
  
  										
  										if($place[$i]=="")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
                			  $lt_tmp = substr($lat[$i], 0, -1);
                			  $lng_tmp = substr($lng[$i], 0, -1);
  											
												//$location_cord="(lat=".$lt_tmp.", lng =".$lng_tmp.")";

												//echo'<td class="text" align="left">'.$place[$i].''.$location_cord.'</b>&nbsp;</td>';

                        echo'<td class="text" align="left">'.$place[$i].'</b>&nbsp;</td>';
                        //echo'<td class="text">'.$placename[$i].'<a href="javascript:MapWindow(\''.$vehiclename[$i].'\',\''.$datetime1.'\',\''.$lat[$i].'\',\''.$lng[$i].'\');"><font color="green">&nbsp;(Show on map)</font></a></td>';
  										}
  										
  										//echo'<td class="text">'.$lat[$i].'</td>';
  										if($lat[$i]=="")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
  											echo'<td class="text">'.$lat[$i].'</td>';
  										}
										if($lng[$i]=="")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
  											echo'<td class="text">'.$lng[$i].'</td>';
  										}
  										if($cellinfo[$i]=="")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
  											echo'<td class="text">'.$cellinfo[$i].'</td>';
  										}
  										
  										//echo'<td class="text">'.$lng[$i].'</td>';
  										if($uploadedfrom[$i]=="")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
  											echo'<td class="text">'.$uploadedfrom[$i].'</td>';
  										}
                      
                      if($remark[$i]=="")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
  											echo'<td class="text">'.$remark[$i].'</td>';
  										}
                      
                      if($picid[$i]=="-")
  										{
  											echo'<td class="text">&nbsp;</td>';
  										}
  										else
  										{
  										  //$hoarding_pic_url= 'http://localhost/ivision/picture_receiver/mobile/decodebase.php?view_type=map&pic_id='.$picid[$i];
  										  $hoarding_pic_url= 'http://ivision.pitbull.co.in/picture_receiver/mobile/decodebase.php?view_type=map&pic_id='.$picid[$i];
  										 
                        echo'<td class="text"><a href="'.$hoarding_pic_url.'" target="_blank(new)">Show Image</a></td>';
                        //echo '<a href="'.$hoarding_pic_url.'" target="_blank">'.$hoarding_pic_url.'</a>';
  										}
                      $m++;		     			
  									 echo'
  									 </tr>
  									 ';  									
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
                  echo '<tr valign="top" bgcolor="#FFFAF5" onMouseOver="this.className=\'highlight1\'" onMouseOut="this.className=\'normal1\'"><td class="text">'.$m.'</td><td class="text">'.$tmp_vname.'</td><td colspan="7" class="text"><font color="red">No record found</font></td></tr>';
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
													<!--<td>
														<input name="submit1" type="submit" value="Get PDF Report" class="noprint">
														&nbsp;
														<input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;    													
													</td>-->							
													
                          <td align="right">								
														 <a href="#" onclick="window.close()" style="text-decoration:none;"><font color="green" size="2"><b>Close</b></font></a>
														 <img src="../../images/close_win.png" align="absmiddle" style="widht:20px;height:20px;border:none;" onclick="window.close()">
													</td>
							';							
																											
								$title="Hoarding Information-Last Data";
								
                $csv_string = "";
                //$csv_string = $csv_string."Title,SNo,VehicleName,DateTime,Location,Latitude,Longitude,Speed\n";
                $csv_string = $csv_string."Hoarding Last Position\n";
                $csv_string = $csv_string."SNo,HoardingName,HoardingNumber,DateTime,Place,UploadedFrom,Picture\n";

                
                echo"<input TYPE=\"hidden\" VALUE=\"$title\" NAME=\"title\">";
								$m=0;
								
                for($k=0;$k<sizeof($vserial_post);$k++)
								{								
                  $flag_found = 0;
                  
                  for($i=0;$i<$size;$i++)
  								{	
                    if($vehicleserial[$i] == $vserial_post[$k])
  								  {
  								    $flag_found = 1;                      
                      $m++;	
                     // $hoarding_pic_url= "http://localhost/ivision/picture_receiver/mobile/decodebase.php?view_type=map&pic_id="+$picid[$i];
                      $hoarding_pic_url= "http://ivision.pitbull.co.in/picture_receiver/mobile/decodebase.php?view_type=map&pic_id="+$picid[$i];    									
                      
                      echo"<input TYPE=\"hidden\" VALUE=\"$m\" NAME=\"temp[$k][SNo]\">";
                      echo"<input TYPE=\"hidden\" VALUE=\"$vehiclename[$i]\" NAME=\"temp[$k][Hoarding Name]\">";    									
                      echo"<input TYPE=\"hidden\" VALUE=\"$vehiclenumber[$i]\" NAME=\"temp[$k][Hoarding Number]\">";
                      echo"<input TYPE=\"hidden\" VALUE=\"$lname[$i]\" NAME=\"temp[$k][Hoarding Location]\">";
                      echo"<input TYPE=\"hidden\" VALUE=\"$datetime[$i]\" NAME=\"temp[$k][Date Time]\">";
    									echo"<input TYPE=\"hidden\" VALUE=\"$place[$i]\" NAME=\"temp[$k][Picture Location]\">";
    									echo"<input TYPE=\"hidden\" VALUE=\"$cellinfo[$i]\" NAME=\"temp[$k][Cell Info]\">";
    									//echo"<input TYPE=\"hidden\" VALUE=\"$lat[$i]\" NAME=\"temp[$k][Latitude]\">";
    									//echo"<input TYPE=\"hidden\" VALUE=\"$lng[$i]\" NAME=\"temp[$k][Longitude]\">";
    									//echo"<input TYPE=\"hidden\" VALUE=\"$alt1[$i]\" NAME=\"temp[$i][Altitude]\">";
    									echo"<input TYPE=\"hidden\" VALUE=\"$uploadedfrom[$i]\" NAME=\"temp[$k][Uploaded From]\">";
                      echo"<input TYPE=\"hidden\" VALUE=\"$remark[$i]\" NAME=\"temp[$k][Remarks]\">";	
    									echo"<input TYPE=\"hidden\" VALUE=\"$hoarding_pic_url\" NAME=\"temp[$i][Picture]\">";

                      $pdf_place_ref = $placename[$i];
                      $pdf_place_ref = str_replace(',',':',$pdf_place_ref);
                      //$csv_string = $csv_string.$title.','.$m.','.$vehiclename[$i].','.$datetime[$i].','.$pdf_place_ref.','.$lat[$i].','.$lng[$i].','.$speed[$i]."\n"; 
                      $csv_string = $csv_string.$m.','.$vehiclename[$i].','.$vehiclenumber[$i].','.$lname[$i].','.$datetime[$i].','.$place[$i].','.$uploadedfrom[$i].','.$hoarding_pic_url."\n";
    									
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
  								  //$i++;
                    $tmp="-";
                    $m++;
                    echo"<input TYPE=\"hidden\" VALUE=\"$m\" NAME=\"temp[$k][SNo]\">";
                    
                    echo"<input TYPE=\"hidden\" VALUE=\"$tmp_vname]\" NAME=\"temp[$k][Hoarding Name]\">";  									
                    echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Hoarding Number]\">";
                    echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Hoarding Location]\">";
                    echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Date Time]\">";
  									echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Picture Location]\">";
  									echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Cell Info]\">";
  									//echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Latitude]\">";
  									//echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Longitude]\">";
  									//echo"<input TYPE=\"hidden\" VALUE=\"$alt1[$i]\" NAME=\"temp[$i][Altitude]\">";
  									echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Uploaded From]\">";
                    echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Remarks]\">";	
    									echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$i][Picture]\">";
                    
                    $csv_string = $csv_string.$m.','.$tmp_vname.','.$tmp.','.$tmp.','.$tmp.','.$tmp.','.$tmp.','.$tmp."\n";  								
                  }
  						}
  						

            echo'<input TYPE="hidden" VALUE="last data" NAME="csv_type">';
            echo'<input TYPE="hidden" VALUE="'.$csv_string.'" NAME="csv_string">';                 
            echo'<br><center><input type="button" onclick="javascript:report_csv(\'report_getpdf_type3.php?size='.$sizev.'\');" value="Get PDF" class="noprint">&nbsp;
            <!--
            <input type="button" onclick="javascript:report_csv(\'report_csv.php\');" value="Get CSV" class="noprint">&nbsp;
            <input type="button" value="Print it" onclick="window.print()" class="noprint">&nbsp;
            -->
            ';  						

						echo'
											</td>	         
									 
								</tr>
							</table>  
              
              </form>
						</div> 
					 ';
						
						}
						else
						{
							echo'<br>						
							';
							print"<center><FONT color=\"Blue\" size=2><strong>No Data Found </strong></font></center>";
							//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=user_main.php\">";
						}
						
unlink($xml_path);
						
					?>	
<!--<br><br><center><input type="button" value="CLOSE" onlick="javascript:window.close();"></center>-->
					
		</BODY>
</HTML>
