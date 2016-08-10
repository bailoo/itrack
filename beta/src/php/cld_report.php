<?php
    //error_reporting(-1);
    //ini_set('display_errors', 'On');
    set_time_limit(2000);
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    include_once("util.fuel_calibration.php");
    include_once("calculate_distance.php");   
    include_once("user_type_setting.php");
    include_once("coreDb.php");
    include_once("select_landmark_report.php");
    
    $lat_lng_permission=getLatLngPermission($account_id,$DbConnection);  
    $geocodes_string = $_POST['geocodedPostcodes'];  
    $geocodes_string = substr($geocodes_string, 0, -1);
    //echo "geoStr=".$geocodes_string."<br>";
    $geocodes_string = str_replace('"','',$geocodes_string);
    $location = explode(':',$geocodes_string);
    
    ///////////// this is all vehicle either data present or not
    $vserial_post = explode(',',$vserial);
    $io_element_post = explode(',',$text_report_io_element);
    $vname_post = explode(':',$vname);
    $vnumber_post = explode(':',$vnumber);
    
    /////// this all vehicle detail in which data present ///////
    $vehicleserial=unserialize($vehicleserial);
    //print_r($vehicleserial);
    $vehiclename=unserialize(base64_decode($vehiclename)); 
    //print_r($vehiclename);
    $vehiclenumber=unserialize($vehiclenumber);
     //print_r($vnumber_post);
    $speed=unserialize($speed);
    $lat=unserialize($lat);
    $lng=unserialize($lng);
    $datetime=unserialize(base64_decode($datetime));
    $last_halt_time=unserialize(base64_decode($last_halt_time));
    ///////////////////////////////////////////
    
    if($report_type=="Vehicle")
    {
        $io1 = unserialize($io1);
        $io2 = unserialize($io2);
        $io3 = unserialize($io3);
        $io4 = unserialize($io4);
        $io5 = unserialize($io5);
        $io6 = unserialize($io6);
        $io7 = unserialize($io7);
        $io8 = unserialize($io8);
    }
    $startdate = $_POST['startdate'];
    $enddate = $_POST['enddate'];
    $size = sizeof($vehicleserial);
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
        {
            color: black;
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
    </script>	
</head>
<body>
<?php			
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
            <?php
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
                            echo'<td class="text" width="5%"><b>Mobile Number</b></td';  							
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
                        echo'<td class="text" width="16%"><b>Last Halt</b></td>';
                echo'</tr>';
                    //include("get_location_test.php");
                    //include("get_location.php");								
                    $tmp_vname ="";
                    $m=0;
                    $size = sizeof($vehicleserial);
                    //echo "size=".$size."<br>";
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
                                    echo'<td class="text">'.$vehiclename[$i].'</td>';
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
                                                $io_value = ((${$tmp_io}[$i]>=-30) && (${$tmp_io}[$i]<=70))?${$tmp_io}[$i]:'-';
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
                                }
                                if($last_halt_time[$i]=="")
                                {
                                    echo'<td class="text">&nbsp;</td>';
                                }
                                else
                                {
                                    $tmp_sys_time=strtotime($date);
                                    $tmp_halt_time=strtotime($last_halt_time[$i]);
                                    if(($tmp_sys_time-$tmp_halt_time)<=300)
                                    {
                                    echo'<td class="text">
                                            <font color="green">
                                                '.$last_halt_time[$i].'
                                            </font>
                                        </td>';
                                    }
                                    else
                                    {
                                    echo'<td class="text">
                                            <font color="red">
                                                '.$last_halt_time[$i].'
                                            </font>
                                        </td>';
                                    }										
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
                                    <td class="text">'.$tmp_vname.'</td>
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
                                <font color="red">No Data Found</font>
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
                                        $csv_string = $csv_string."SNo,VehicleName,IMEI,DateTime,Location,Latitude,Longitude".$speed_status.",Last Halt\n";
                                    }
                                    else if($lat_lng_permission=="1")
                                    {
                                        $csv_string = $csv_string."SNo,VehicleName,IMEI,DateTime,Latitude,Longitude".$speed_status.",Last Halt\n";								
                                    }
                                    else if($data_with_location=="1")
                                    {
                                        $csv_string = $csv_string."SNo,VehicleName,IMEI,DateTime,Location".$speed_status.",Last Halt\n";
                                    }
                                    else
                                    {
                                        $csv_string = $csv_string."SNo,VehicleName,IMEI,DateTime".$speed_status.",Last Halt\n";
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
                                                //$placename[$i]=preg_replace('/???? ???????/', '', $placename[$i]);
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
                                                        echo"<input TYPE=\"hidden\" VALUE=\"$last_halt_time[$i]\" NAME=\"temp[$k][Last Halt]\">";																		
                                                        $csv_string = $csv_string.','.$speed1.",".$pdf_tmp_io.",".$last_halt_time[$i]."\n";
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
                                                        echo"<input TYPE=\"hidden\" VALUE=\"$last_halt_time[$i]\" NAME=\"temp[$k][Last Halt]\">";																		
                                                        $csv_string = $csv_string.','.$speed1.",".$pdf_tmp_io.",".$last_halt_time[$i]."\n"; 
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
                                                    echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Last Halt]\">";																		
                                                    $csv_string = $csv_string.','.$tmp.','.$tmp.','.$tmp."\n";  
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
                                                    echo"<input TYPE=\"hidden\" VALUE=\"$tmp\" NAME=\"temp[$k][Last Halt]\">";																		
                                                    $csv_string = $csv_string.','.$tmp.','.$tmp.','.$tmp."\n";  
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
                                echo'<br>';
                                print"<center><FONT color=\"Blue\" size=2><strong>No Data Found During These Date and Time For The Vehicle/Vehicles</strong></font></center>";
                                //echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"2; URL=user_main.php\">";
                            }	
?>			
    </BODY>
</HTML>
