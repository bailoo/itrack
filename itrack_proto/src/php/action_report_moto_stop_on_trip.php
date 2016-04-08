<?php
	set_time_limit(2000);
	$current_time = date('Y/m/d H:i:s');
	$today_date=explode(" ",$current_time);
	$today_date1=$today_date[0];
	$today_date2 = str_replace("/","-",$today_date1);
	$standard_duration=array(array());
	$more_than_12_hr_arr=array(array());
	$between_6_hr_12_hr_arr=array(array());
	$between_1_hr_6_hr_arr=array(array());
	$between_0_hr_1_hr_arr=array(array());
	function vehicles_on_trip($AccountNode,$startdate,$enddate)
	{
		global $today_date2;
		global $standard_duration;
		global $more_than_12_hr_arr;
		global $between_6_hr_12_hr_arr;
		global $between_1_hr_6_hr_arr;
		global $between_0_hr_1_hr_arr;
		$date_1 = explode(" ",$startdate);
		$date_2 = explode(" ",$enddate);
		$datefrom = $date_1[0];
		$dateto = $date_2[0];
		$timefrom = $date_1[1];
		$timeto = $date_2[1];
		get_All_Dates($datefrom, $dateto, &$userdates);	
		
		$account_name=$AccountNode->data->AccountName;	
		//echo "account_name=".$account_name."<br>";
		if($AccountNode->data->VehicleCnt>0)
		{
			for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)   ///////this is for show root vehicle of any account /////////
			{								
				$vehicle_id = $AccountNode->data->VehicleID[$j];
				$vehicle_name = $AccountNode->data->VehicleName[$j];
				$vehicle_type = $AccountNode->data->VehicleType[$j];
				$vehicle_imei = $AccountNode->data->DeviceIMEINo[$j];				
				if($vehicle_id!=null)
				{
					for($i=0;$i<$vehicle_cnt;$i++)
					{
						if($vehicleid[$i]==$vehicle_id)
						{
							break;
						}
					}			
					if($i>=$vehicle_cnt)
					{
						$vehicleid[$vehicle_cnt]=$vehicle_id;
						$vehicle_cnt++;
						$xml_current = "../../../xml_vts/xml_last/".$vehicle_imei.".xml";
						//echo "xml_current=".$xml_current."<br>";
						if (file_exists($xml_current))      
						{ 
							//echo "in if<br>";
							$t=time();			
							$xml_original_tmp = "../../../xml_tmp/original_xml/tmp_".$vehicle_serial."_".$t."_".$i.".xml";		
							copy($xml_current,$xml_original_tmp); 
							$fexist =1;
							$xml = fopen($xml_original_tmp, "r") or $fexist = 0;               
							$format = 2;      
							if(file_exists($xml_original_tmp)) 
							{      
								while(!feof($xml))          // WHILE LINE != NULL
								{								
									$DataValid = 0;
									$line = fgets($xml);        // STRING SHOULD BE IN SINGLE QUOTE
									if(strpos($line,'fix="1"'))     // RETURN FALSE IF NOT FOUND
									{
										$fix_tmp = 1;
									}
									else if(strpos($line,'fix="0"'))
									{
										$fix_tmp = 0;
									}  				
									if((preg_match('/lat="\d+.\d+[a-zA-Z0-9]\"/', $line, $lat_match) ) && (preg_match('/lng="\d+.\d+[a-zA-Z0-9]\"/', $line, $lng_match)))
									{ 
										$lat_value = explode('=',$lat_match[0]);
										$lng_value = explode('=',$lng_match[0]);
										if((strlen($lat_value[1])>5) && ($lat_value[1]!="-") && (strlen($lng_value[1])>5) && ($lng_value[1]!="-") )
										{
											$DataValid = 1;
										}
									}
									if(($line[0] == '<') && ($line[strlen($line)-2] == '>') && ($fix_tmp==1) && ($DataValid == 1))
									{	
										$status = preg_match('/last_halt_time="[^"]+/', $line, $lasthalttime_tmp);
										$lasthalttime_tmp1 = explode("=",$lasthalttime_tmp[0]);
										$lasthalt_datetime = preg_replace('/"/', '', $lasthalttime_tmp1[1]);
										$lhdt_insecond=strtotime($lasthalt_datetime);
										//echo "halt_time=".$lhdt_insecond."<br>";
										$current_datetime=date('Y-m-d H:i:s');
										$current_datetime_cmp=strtotime($current_datetime);	
										//echo "current_datetime_cmp=".$current_datetime_cmp."<br>";
										//echo "difference=".($current_datetime_cmp-$lhdt_insecond)."<br>";
										if($current_datetime_cmp-$lhdt_insecond<=120)
										{
											$standard_duration[$account_name][]=$account_name.",".$vehicle_name.",".$vehicle_type.",".$vehicle_imei;
										}
										if($current_datetime_cmp-$lhdt_insecond>432000)
										{
											$more_than_12_hr_arr[$account_name][]=$vehicle_name.",".$vehicle_type.",".$vehicle_imei;
										}
										if(($current_datetime_cmp-$lhdt_insecond>21600) && ($current_datetime_cmp-$lhdt_insecond<432000))
										{
											$between_6_hr_12_hr_arr[$account_name][]=$vehicle_name.",".$vehicle_type.",".$vehicle_imei;
										}
										if(($current_datetime_cmp-$lhdt_insecond>3600) && ($current_datetime_cmp-$lhdt_insecond<21600))
										{
											//echo "test=".$account_name.",".$vehicle_name.",".$vehicle_type.",".$vehicle_imei."<br>";
											//$between_1_hr_6_hr_arr[$account_name][]=$vehicle_name.",".$vehicle_type.",".$vehicle_imei;
											$between_1_hr_6_hr_arr[$account_name][]=$vehicle_name.",".$vehicle_type.",".$vehicle_imei;
										}
										if(($current_datetime_cmp-$lhdt_insecond>60) && ($current_datetime_cmp-$lhdt_insecond<3600))
										{
											//echo "test1=".$account_name.",".$vehicle_name.",".$vehicle_type.",".$vehicle_imei."<br>";
											$between_0_hr_1_hr_arr[$account_name][]=$vehicle_name.",".$vehicle_type.",".$vehicle_imei;
										}
									}
								} // while closed
							}  // if original_tmp exist closed        
							if(strlen($linetowrite)!=0)
							{
								//echo "<br>".$xmltowrite."<br>";				
								$fh = fopen($xmltowrite, 'a') or die("can't open file 5"); //append
								fwrite($fh, $linetowrite);  
								fclose($fh);
								fclose($xml);
								unlink($xml_original_tmp);
								break;
							}
							fclose($xml);
							unlink($xml_original_tmp);						
						}											
					}
				}			
			}
		}		
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)   /////////////this is for show child vehicle only ///////////
		{    
			show_all_vehicle($AccountNode->child[$i]);
		} 
	}
	
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	
	$root=$_SESSION['root'];	
	$startdate = date('Y-m-d H:i:s');
	$enddate = date('Y-m-d 23:59:59');
	$date_1 = explode(" ",$startdate);
	$date_2 = explode(" ",$enddate);
	vehicles_on_trip($root,$startdate,$enddate);

	function get_All_Dates($fromDate, $toDate, &$userdates)
	{
		$dateMonthYearArr = array();
		$fromDateTS = strtotime($fromDate);
		$toDateTS = strtotime($toDate);
		for($currentDateTS = $fromDateTS; $currentDateTS <= $toDateTS; $currentDateTS += (60 * 60 * 24)) 
		{
			// use date() and $currentDateTS to format the dates in between
			$currentDateStr = date("Y-m-d",$currentDateTS);
			$dateMonthYearArr[] = $currentDateStr;
			//print $currentDateStr.”<br />”;
		}
		$userdates = $dateMonthYearArr;
	}
	
	$vs_standard_duration=0;
	foreach($standard_duration as $key=>$acc_name) 
	{	
		foreach ($acc_name as $vehicle_info) 
		{
			++$vs_standard_duration;
		}
	}	
	
	$vs_12_hr=0;
	foreach($more_than_12_hr_arr as $key=>$acc_name) 
	{
		//echo "acc_name2=".$key."<br>";
		foreach ($acc_name as $vehicle_info) 
		{
			++$vs_12_hr;
		}
	}
	
	$vs_6_12_hr=0;
	foreach($between_6_hr_12_hr_arr as $key=>$acc_name) 
	{
		//echo "acc_name3=".$key."<br>";
		foreach ($acc_name as $vehicle_info) 
		{
			++$vs_6_12_hr;
			//echo "vehicle_info=".$vehicle_info."<br>";
		}
	}
		
	$vs_1_6_hr=0;
	foreach($between_1_hr_6_hr_arr as $key=>$acc_name) 
	{	
		foreach($acc_name as $vehicle_info) 
		{
			++$vs_1_6_hr;
			//echo "vehicle_info=".$vehicle_info."<br>";
		}	
	}	

	$vs_0_1_hr=0;
	foreach($between_0_hr_1_hr_arr as $key=>$acc_name) 
	{
		//echo "acc_name4=".$key."<br>";
		foreach($acc_name as $vehicle_info) 
		{
			++$vs_0_1_hr;
			//echo "vehicle_info11=".$vehicle_info."<br>";
		}
	}
	
	//echo "vs_0_1_hr=".$vs_0_1_hr."<br>";
?>
<html>
	<title>
		<?php echo $title; ?>
	</title>
	<head>
	<style>
	.headings
	{
		font-size: 9pt;	
		font-weight: bold;
		color:blue;
		text-align:center;
	}
	.main_tr
	{	
		text-align:left;
		background-color:#E3E3E3;
	}
	</style>	
	<script>
		function show_hide_option(func_hide_show_id,id_for_color)
		{
			//alert("hide_show_id="+document.getElementById("hide_show_id").value+"fun_id="+func_hide_show_id+"current_status_id="+document.getElementById("current_status_id").value);
			if((func_hide_show_id==document.getElementById("hide_show_id").value) && (document.getElementById("current_status_id").value=="off"))
			{
				document.getElementById(id_for_color).style.color="blue"; // for color change
				document.getElementById(func_hide_show_id).style.display="none";
				document.getElementById("current_status_id").value="on";				
			}
			else if((func_hide_show_id==document.getElementById("hide_show_id").value) && (document.getElementById("current_status_id").value=="on"))
			{	
				document.getElementById(id_for_color).style.color="red"; // for color change
				document.getElementById(func_hide_show_id).style.display="";					
				document.getElementById("current_status_id").value="off";				
			}
			else
			{
				var all_hide_show_ids=document.getElementById("all_hide_show_ids").value;
				all_hide_show_ids=all_hide_show_ids.split(",");
				var all_headings_id=document.getElementById("all_headings_id").value;
				all_headings_id=all_headings_id.split(",");
				
				for(var i=0;i<all_headings_id.length;i++)
				{
					if(func_hide_show_id==all_hide_show_ids[i])
					{
						document.getElementById(func_hide_show_id).style.display="";
						document.getElementById(id_for_color).style.color="red";
						document.getElementById("current_status_id").value="off";
						document.getElementById("hide_show_id").value=func_hide_show_id;
					}
					else
					{
						//alert("id1s="+all_headings_id[i]);
						document.getElementById(all_hide_show_ids[i].trim()).style.display="none";
						document.getElementById(all_headings_id[i].trim()).style.color="blue";					
					}				
				}			
			}				
					
		}
		function show_hide_intr(in_tr_id,outer_cnt)
		{
			//alert("in_tr_id="+in_tr_id+"outer_cnt="+outer_cnt);			
			var flag=0;
			//var in_tr_a_1=document.getElementById(in_tr_id+"_inner_"+outer_cnt).value;	
			var same_in_tr_id=in_tr_id+"_"+outer_cnt;
			//alert("same_tr="+same_in_tr_id+" current_tr="+document.getElementById(in_tr_id+"_currentid").value)
			if(document.getElementById(in_tr_id+"_currentid").value==same_in_tr_id)
			{
				//alert("in if");
				flag=1;
				document.getElementById(in_tr_id+"_currentid").value=in_tr_id+"_"+outer_cnt;
				//alert("text current tr="+document.getElementById(in_tr_id+"_currentid").value);
				var in_tr_a_1=document.getElementById(in_tr_id+"_inner_"+outer_cnt).value;
				//alert("on_off_value="+document.getElementById(in_tr_id+"_on_off").value);
					//alert("tr2="+in_tr_id+"_on_off");
				//alert("on_off_value2="+document.getElementById(in_tr_id+"_on_off").value);
				if(document.getElementById(in_tr_id+"_on_off").value=="1")
				{
					//alert("in if");					
					for(var i=1;i<(in_tr_a_1);i++)
					{
						document.getElementById(in_tr_id+"_"+outer_cnt+"_"+i).style.display="";
					}					
					document.getElementById(in_tr_id+"_on_off").value="0";
					//alert("tr="+in_tr_id+"_on_off");
					//alert("on_off_value1="+document.getElementById(in_tr_id+"_on_off").value);
				}
				else if(document.getElementById(in_tr_id+"_on_off").value=="0")
				{
					for(var i=1;i<(in_tr_a_1);i++)
					{
						document.getElementById(in_tr_id+"_"+outer_cnt+"_"+i).style.display="none";
					}
					document.getElementById(in_tr_id+"_on_off").value="1";
				}
			}	
			if(flag=="0")
			{
				//alert("in flag 0");
				var intr_headings_id=document.getElementById("intr_headings_id").value;
				var intr_headings_id_1= intr_headings_id.match(in_tr_id);
				var outer_ids=document.getElementById(in_tr_id+"_"+"outer").value;	
				//alert("ourter_ids="+outer_ids);
				var dif=outer_ids-1;
				//alert("ourter_ids="+dif);
				for(var i=1;i<(outer_ids);i++)
				{
					var intr_headings_id_cmp=intr_headings_id_1+"_"+i;
					var in_tr_id_cmp=in_tr_id+"_"+outer_cnt;
					//alert("intr_headings_id_cmp="+intr_headings_id_cmp);
					//alert("in_tr_id_cmp="+in_tr_id_cmp);
					if(in_tr_id_cmp==intr_headings_id_cmp)
					{	
						//alert("in if");
						var in_tr_a_1=document.getElementById(in_tr_id+"_inner_"+outer_cnt).value;						
						for(var j=1;j<(in_tr_a_1);j++)
						{
							//alert("tr="+in_tr_id+"_"+outer_cnt+"_"+j);
							document.getElementById(in_tr_id+"_"+outer_cnt+"_"+j).style.display="";																					
						}
						document.getElementById(in_tr_id+"_currentid").value=in_tr_id_cmp;
					}
					else
					{
						//alert("in else");
						var in_tr_a_1=document.getElementById(in_tr_id+"_inner_"+i).value;						
						for(var j=1;j<(in_tr_a_1);j++)
						{
							//alert("tr_1="+in_tr_id+"_"+outer_cnt+"_"+j);
							document.getElementById(in_tr_id+"_"+i+"_"+j).style.display="none";																					
						}						
					}
				}
				document.getElementById(in_tr_id+"_on_off").value="0";
			}
		} 
	</script>
	</head>
<body>
	<input type="hidden" id="hide_show_id">	
	<input type="hidden" id="current_status_id">
	<input type="hidden" id="all_headings_id" value="main_tr_1,main_tr_2,main_tr_3,main_tr_4,main_tr_5">
	<input type="hidden" id="all_hide_show_ids" value="stationary_vehicles,more_than_12_hr,between_6_to_12_hr,between_1_to_6_hr,between_0_to_1_hr">
	<input type="hidden" id="prev_status_id" value="off">
	
	<input type="hidden" id="intr_headings_id" value="in_tr_a,in_tr_b,in_tr_c,in_tr_d,in_tr_e">
<table width="100%" border=0>
	<tr>
		<td>
			<table align="center" width="95%" class="menu" border="1" rules="all" bordercolor="black" border=1>
				<tr onclick="javascript:show_hide_option('stationary_vehicles','main_tr_1')" class="main_tr">
					<td>
						<table class="headings">
							<tr id="main_tr_1">
								<td align="center">
									Total Stationary Vehicles
								</td>
								<td align="center">
									-
								</td>
								<td align="center">
									<?php echo "(".$vs_standard_duration.")"; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="stationary_vehicles" style="display:none">
					<td>
						<table border=1 cellspacing=0 cellpadding=0 width="100%">
							<tr bgcolor="#CFE0F7">
								<td align="left">
									<table cellspacing=2 cellpadding=2>
										<tr>
											<td align="center">
											<img src='../../images/icon/pdf_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/excel_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/email.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td valign="top">									
									<table border=1 width="100%">
										<tr>
											<td align="center" width="10%">
												Transporter
											</td>
											<td align="center">
												T
											</td>
											<td align="center">
												V
											</td>
											<td align="center">
												L
											</td>
											<td align="center">
												D
											</td>
											<td align="center">
												C
											</td>
											<td align="center">
												C
											</td>
										</tr>								
							<?php
								
								echo'<input type="hidden" id="in_tr_a_on_off" value="1">
								<input type="hidden" id="in_tr_a_currentid">';
								$outer_cnt=1;
								foreach($standard_duration as $key=>$acc_name)							
								{
									if($key!="0")
									{
										echo'<tr id="in_tr_a_'.$outer_cnt.'">
												<td>
													<a href="#" onclick="javascript:show_hide_intr(\'in_tr_a\',\''.$outer_cnt.'\');">'.$key.'</a>
												</td>
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;
												</td>
												<td>
													&nbsp;
												</td>										
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;											
												</td>
												<td>
													&nbsp;											
												</td>
											</tr>';
										$inner_cnt=1;
										foreach ($acc_name as $vehicle_info) 									
										{										
											$vehicle_info_1=explode(",",$vehicle_info);
										echo'<tr id="in_tr_a_'.$outer_cnt.'_'.$inner_cnt.'" style="display:none;">';	
											for($i=0;$i<sizeof($vehicle_info_1);$i++)
											{																			
											echo'<td width="10%">
													-
												</td>
												<td>
													'.$vehicle_info_1[$i].'
												</td>';
											}																																
										echo'</tr>';
											$inner_cnt++;
										}							
										echo "<input type='hidden' id='in_tr_a_inner_".$outer_cnt."' value='".$inner_cnt."'>";
										$outer_cnt++;
									}
								echo "<input type='hidden' id='in_tr_a_outer' value='".$outer_cnt."'>";	
								}
							?>								
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr onclick="javascript:show_hide_option('more_than_12_hr','main_tr_2')"  class="main_tr">
					<td>
						<table class="headings" >
							<tr id="main_tr_2">
								<td align="center">
									Stationary More Than 12 Hrs
								</td>
								<td align="center">
									-
								</td>
								<td align="center">
									<?php echo "(".$vs_12_hr.")"; ?>									
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="more_than_12_hr" style="display:none">
					<td align="center">
						<table cellspacing=0 cellpadding=0 width="100%">
							<tr bgcolor="#CFE0F7">
								<td align="left">
									<table cellspacing=2 cellpadding=2>
										<tr>
											<td align="center">
											<img src='../../images/icon/pdf_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/excel_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/email.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table border=1 width="100%">
										<tr>
											<td align="center">
												Transporter
											</td>
											<td align="center">
												T
											</td>
											<td align="center">
												V
											</td>
											<td align="center">
												L
											</td>
											<td align="center">
												D
											</td>
											<td align="center">
												C
											</td>
											<td align="center">
												C
											</td>
										</tr>
							<?php								
								echo'<input type="hidden" id="in_tr_b_on_off" value="1">
								<input type="hidden" id="in_tr_b_currentid">';
								$outer_cnt=1;
								foreach($more_than_12_hr_arr as $key=>$acc_name) 								
								{
									if($key!="0")
									{
										echo'<tr id="in_tr_b_'.$outer_cnt.'">
												<td>
													<a href="#" onclick="javascript:show_hide_intr(\'in_tr_b\',\''.$outer_cnt.'\');">'. $key.'</a>
												</td>
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;
												</td>
												<td>
													&nbsp;
												</td>										
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;											
												</td>
												<td>
													&nbsp;											
												</td>
											</tr>';
										$inner_cnt=1;
										foreach ($acc_name as $vehicle_info) 									
										{										
											$vehicle_info_1=explode(",",$vehicle_info);
										echo'<tr id="in_tr_b_'.$outer_cnt.'_'.$inner_cnt.'" style="display:none;">';	
											for($i=0;$i<sizeof($vehicle_info_1);$i++)
											{																			
											echo'<td width="10%">
													-
												</td>
												<td>
													'.$vehicle_info_1[$i].'
												</td>';
											}																																
										echo'</tr>';
											$inner_cnt++;
										}							
										echo "<input type='hidden' id='in_tr_b_inner_".$outer_cnt."' value='".$inner_cnt."'>";
										$outer_cnt++;
									}
								}
								echo "<input type='hidden' id='in_tr_b_outer' value='".$outer_cnt."'>";	
							?>								
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="main_tr" onclick="javascript:show_hide_option('between_6_to_12_hr','main_tr_3')">
					<td>
						<table class="headings" >
							<tr id="main_tr_3">
								<td align="center">
									Stationary between 6-12 Hrs
								</td>
								<td align="center">
									-
								</td>
								<td align="center">								
									<?php echo "(".$vs_6_12_hr.")"; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="between_6_to_12_hr" style="display:none">
					<td align="center">
						<table cellspacing=0 cellpadding=0 width="100%">
							<tr bgcolor="#CFE0F7">
								<td align="left">
									<table cellspacing=2 cellpadding=2>
										<tr>
											<td align="center">
											<img src='../../images/icon/pdf_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/excel_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/email.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table border=1 width="100%">
										<tr>
											<td align="center">
												Transporter
											</td>
											<td align="center">
												T
											</td>
											<td align="center">
												V
											</td>
											<td align="center">
												L
											</td>
											<td align="center">
												D
											</td>
											<td align="center">
												C
											</td>
											<td align="center">
												C
											</td>
										</tr>
							<?php								
							echo'<input type="hidden" id="in_tr_c_on_off" value="1">
								<input type="hidden" id="in_tr_c_currentid">';
								$outer_cnt=1;
								foreach($between_6_hr_12_hr_arr as $key=>$acc_name) 
								{	
									if($key!="0")
									{											
										echo'<tr id="in_tr_c_'.$outer_cnt.'">
												<td>
													<a href="#" onclick="javascript:show_hide_intr(\'in_tr_c\',\''.$outer_cnt.'\');">';												
														echo $key;											
												echo'</a>
												</td>
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;
												</td>
												<td>
													&nbsp;
												</td>										
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;											
												</td>
												<td>
													&nbsp;											
												</td>
											</tr>';
										$inner_cnt=1;
										foreach ($acc_name as $vehicle_info) 							
										{										
											$vehicle_info_1=explode(",",$vehicle_info);
										echo'<tr id="in_tr_c_'.$outer_cnt.'_'.$inner_cnt.'"  style="display:none;">';	
											for($i=0;$i<sizeof($vehicle_info_1);$i++)
											{																			
											echo'<td width="10%">
													-
												</td>
												<td>
													'.$vehicle_info_1[$i].'
												</td>';
											}																																
										echo'</tr>';
											$inner_cnt++;
										}							
										echo "<input type='hidden' id='in_tr_c_inner_".$outer_cnt."' value='".$inner_cnt."'>";
										$outer_cnt++;
									}
								}
								echo "<input type='hidden' id='in_tr_c_outer' value='".$outer_cnt."'>";	
							?>								
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="main_tr" onclick="javascript:show_hide_option('between_1_to_6_hr','main_tr_4')">
					<td>
						<table class="headings">
							<tr id="main_tr_4">
								<td align="center">
									Stationary between 1-6 Hrs
								</td>
								<td align="center">
									-
								</td>
								<td align="center">
									<?php echo "(".$vs_1_6_hr.")"; ?>									
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="between_1_to_6_hr" style="display:none">
					<td align="center">
						<table cellspacing=0 cellpadding=0 width="100%">
							<tr bgcolor="#CFE0F7">
								<td align="left">
									<table cellspacing=2 cellpadding=2>
										<tr>
											<td align="center">
											<img src='../../images/icon/pdf_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/excel_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/email.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>									
									<table border=1 width="100%">
										<tr>
											<td align="center">
												Transporter
											</td>
											<td align="center">
												T
											</td>
											<td align="center">
												V
											</td>
											<td align="center">
												L
											</td>
											<td align="center">
												D
											</td>
											<td align="center">
												C
											</td>
											<td align="center">
												C
											</td>
										</tr>
						<?php							    
							echo'<input type="hidden" id="in_tr_d_on_off" value="1">
								<input type="hidden" id="in_tr_d_currentid">';
								$outer_cnt=1;
								foreach($between_1_hr_6_hr_arr as $key=>$acc_name) 								
								{	
									if($key!="0")
									{
										echo'<tr id="in_tr_d_'.$outer_cnt.'">
												<td>
													<a href="#" onclick="javascript:show_hide_intr(\'in_tr_d\',\''.$outer_cnt.'\');">';
														echo $key;									
												echo'</a>
												</td>
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;
												</td>
												<td>
													&nbsp;
												</td>										
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;											
												</td>
												<td>
													&nbsp;											
												</td>
											</tr>';
										$inner_cnt=1;
										foreach ($acc_name as $vehicle_info)								
										{										
											$vehicle_info_1=explode(",",$vehicle_info);
										echo'<tr id="in_tr_d_'.$outer_cnt.'_'.$inner_cnt.'" style="display:none;">';	
											for($i=0;$i<sizeof($vehicle_info_1);$i++)
											{																			
											echo'<td width="10%">
													-
												</td>
												<td>
													'.$vehicle_info_1[$i].'
												</td>';
											}																																
										echo'</tr>';
											$inner_cnt++;
										}							
										echo "<input type='hidden' id='in_tr_d_inner_".$outer_cnt."' value='".$inner_cnt."'>";
										$outer_cnt++;
									}
								}
								echo "<input type='hidden' id='in_tr_d_outer' value='".$outer_cnt."'>";	
							?>								
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="main_tr" onclick="javascript:show_hide_option('between_0_to_1_hr','main_tr_5')">
					<td>
						<table class="headings">
							<tr id="main_tr_5">
								<td align="center">
									Stationary between 0-1 Hrs
								</td>
								<td align="center">
									-
								</td>
								<td align="center">
									<?php echo $vs_0_1_hr; ?>									
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr id="between_0_to_1_hr" style="display:none">
					<td align="center">
						<table cellspacing=0 cellpadding=0 width="100%">
							<tr bgcolor="#CFE0F7">
								<td align="left">
									<table cellspacing=2 cellpadding=2>
										<tr>
											<td align="center">
											<img src='../../images/icon/pdf_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/excel_1.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
											<td align="center">
												<img src='../../images/icon/email.jpeg' style='border:none;width:20px;height:20px;' class='help_img_css'>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<table border=1 width="100%">
										<tr>
											<td align="center">
												Transporter
											</td>
											<td align="center">
												T
											</td>
											<td align="center">
												V
											</td>
											<td align="center">
												L
											</td>
											<td align="center">
												D
											</td>
											<td align="center">
												C
											</td>
											<td align="center">
												C
											</td>
										</tr>
													<?php								
							echo'<input type="hidden" id="in_tr_e_on_off" value="1">
								<input type="hidden" id="in_tr_e_currentid">';
								$outer_cnt=1;
								foreach($between_0_hr_1_hr_arr as $key=>$acc_name)					
								{	
									if($key!="0")
									{
										echo'<tr id="in_tr_e_'.$outer_cnt.'">
												<td>
													<a href="#" onclick="javascript:show_hide_intr(\'in_tr_e\',\''.$outer_cnt.'\');">';
														echo $key;												
												echo'</a>
												</td>
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;
												</td>
												<td>
													&nbsp;
												</td>										
												<td>
												&nbsp;
												</td>
												<td>
													&nbsp;											
												</td>
												<td>
													&nbsp;											
												</td>
											</tr>';
										$inner_cnt=1;
										foreach ($acc_name as $vehicle_info) 								
										{										
											$vehicle_info_1=explode(",",$vehicle_info);
										echo'<tr id="in_tr_e_'.$outer_cnt.'_'.$inner_cnt.'" style="display:none;">';	
											for($i=0;$i<sizeof($vehicle_info_1);$i++)
											{																			
											echo'<td width="10%">
													-
												</td>
												<td>
													'.$vehicle_info_1[$i].'
												</td>';
											}																																
										echo'</tr>';
											$inner_cnt++;
										}							
										echo "<input type='hidden' id='in_tr_e_inner_".$outer_cnt."' value='".$inner_cnt."'>";
										$outer_cnt++;
									}
								}
								echo "<input type='hidden' id='in_tr_e_outer' value='".$outer_cnt."'>";	
							?>								
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>	
</body>
</html>

