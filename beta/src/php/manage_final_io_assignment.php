<?php 
    include_once('Hierarchy.php');	
    include_once('util_session_variable.php');	
    include_once('util_php_mysql_connectivity.php');
    include_once('coreDb.php');	

    $account_id_local1 = $_POST['account_id_local'];
    $vehicle_display_option1 = $_POST['vehicle_display_option'];
    $vehicle_option_values = $_POST['options_value'];

    $root=$_SESSION['root'];	
    $DEBUG = 0; 
    $feature_count=getFeatureCount($DbConnection);
    $list_fname=getIoAssignmentFnameListNextNew($feature_count,$DbConnection);
    $fname=getFeatureNameArr($DbConnection);

    //echo "listFname=".$list_fname."<br>";
    //date_default_timezone_set('Asia/Calcutta');
    $current_time = date('Y/m/d H:i:s');
    $today_date=explode(" ",$current_time);
    $today_date1=$today_date[0];
    $today_date2 = str_replace("/","-",$today_date1);	
    $vehicleid=array();
    $vehicle_cnt;
    
    include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
    include_once("../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/
    
    $o_cassandra = new Cassandra();	
    $o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
    $logDate=date('Y-m-d');
    //echo "lest_name=".$list_fname."feature_count=".$feature_count."<br>";	
    echo"<br>
            <form name='manage'>
                <table border=1 rules=all bordercolor='#e5ecf5' align='center' cellspacing=2 cellpadding=2 class='module_left_menu'>
                    <tr>
                        <td class='text'>
                            &nbsp;<input type='checkbox' name='all' value='1' onClick='javascript:IO_SelectAll(this.form);'>&nbsp;&nbsp;&nbsp;Select All				         
                        </td>";
                    for($i=1;$i<=$feature_count;$i++)
                    {
                        if($fname[$i]!="engine_type" && $fname[$i]!="ac_type" && $fname[$i]!="sos_type" && $fname[$i]!="")
                        {
                    echo"<td>&nbsp;".$fname[$i]."</td>";
                        }
                    }					
                echo"</tr>";			
                    if($vehicle_display_option1=="vehicle_tag")
                    {
                        GetVehicleTag($root,$account_id_local1,$vehicle_option_values); 
                    }
                    else if($vehicle_display_option1=="vehicle_type")
                    {
                        GetVehicleType($root,$account_id_local1,$vehicle_option_values); 
                    }
                    else if($vehicle_display_option1=="all")
                    {
                        show_all_vehicle($root,$account_id_local1);
                    }			
            echo"</table>";
            echo'<br>
                    <center> 
                        <input type="button" name="submit" value="Enter" onclick="javascript:manage_io_validation()">
                    </center>
                </form>';
            echo'<br>
                <center>
                    <a href="javascript:show_option(\'manage\',\'io_assignment\');" class="back_css">
                        &nbsp;<b>Back</b>
                    </a>
                </center>';  
            include_once('manage_loading_message.php');
	function show_all_vehicle($AccountNode,$account_id_local1)
	{
            global $vehicleid;		
            global $vehicle_cnt; 	
            global $DbConnection; 
            global $today_date2;
            global $list_fname;
            global $feature_count;
            global $fname;
            global $o_cassandra;
            //var_dump($o_cassandra);
            global $logDate;
            //echo "acc1=".$AccountNode->data->AccountID."<br>acc2=".$account_id_local1;
            if($AccountNode->data->AccountID==$account_id_local1)
            {
                for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
                { 				
                    $vehicle_id = $AccountNode->data->VehicleID[$j];
                    $vehicle_name = $AccountNode->data->VehicleName[$j];
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
                            $query="SELECT io FROM device_manufacturing_info WHERE device_imei_no='$vehicle_imei' and status=1";  ///get parent account feature	
                            //echo"Query=".$query."<br>";
                            $result=@mysql_query($query,$DbConnection);
                            $num_rows1=0;
                            $row=mysql_fetch_row($result);
                            if($row[0]!="")
                            {
                                $num_rows1=@mysql_num_rows($result);
                                $io_id=$row[0];
                                $io_id1=explode(",",$io_id);
                                $NoofIO=sizeof($io_id1);
                            }
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++; 					
                            $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                            if($logResult!='')
                            {									
                                if($num_rows1>0)
                                {
                                    echo'<tr>';
                                            echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'">						
                                            &nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')"><font color="green">'.$vehicle_name.' '.$vehicle_imei.'</font></A></td>';							
                                            include('exiting_io_status.php');
                                    echo'</tr>';
                                }								
                            }
                            else
                            {							
                                    if($num_rows1>0)
                                    {
                                            echo'<tr>';
                                            echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'">						
                                            &nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')"><font color="grey">'.$vehicle_name.' '.$vehicle_imei.'</font></A></td>';							
                                            include('exiting_io_status.php');
                                            echo'</tr>';
                                    }							
                            }
            }
          } 				
			}
		}
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{ 
			show_all_vehicle($AccountNode->child[$i],$account_id_local1);
		}   
	}
	
	function GetVehicleTag($AccountNode,$account_id_local1,$vehicle_option_values)
	{
		global $vehicleid;		global $vehicle_cnt;  
		$vehicle_tag =explode(",",$vehicle_option_values);		
		$sizeofvehicletag=sizeof($vehicle_tag);

		for($j=0;$j<$sizeofvehicletag;$j++)
		{
			$vehicle_cnt=0;			
			PrintVehicleTag($AccountNode,$account_id_local1,$vehicle_tag[$j]);		
		}
	}		
	
	function PrintVehicleTag($AccountNode,$account_id_local1,$vehicletag)
	{
		global $vehicleid;	
		global $vehicle_cnt; 	
		global $DbConnection; 
		global $today_date2;
		global $list_fname;
		global $feature_count;
		global $fname;
                //var_dump($o_cassandra);
                global $logDate;
		//echo "acc1=".$AccountNode->data->AccountID."<br>acc2=".$account_id_local1;
		if($AccountNode->data->AccountID==$account_id_local1)
		{
			for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
			{
				$vehicle_tag_local = $AccountNode->data->VehicleTag[$j];
				//echo "vehicle_tag_local=".$vehicle_tag_local."vehicletag=".$vehicletag."<br>";
				if($vehicle_tag_local==$vehicletag)
				{
					$vehicle_id = $AccountNode->data->VehicleID[$j];
					$vehicle_name = $AccountNode->data->VehicleName[$j];
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
							$query="SELECT io FROM device_manufacturing_info WHERE device_imei_no='$vehicle_imei' and status=1";  ///get parent account feature	
							//echo"Query=".$query."<br>";
							$result=@mysql_query($query,$DbConnection);
							$num_rows1=0;
							$row=mysql_fetch_row($result);
							if($row[0]!="")
							{
								$num_rows1=@mysql_num_rows($result);
								$io_id=$row[0];
								$io_id1=explode(",",$io_id);
								$NoofIO=sizeof($io_id1);
							}
							$vehicleid[$vehicle_cnt]=$vehicle_id;
							$vehicle_cnt++;
                                                        $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                            //$st_results = getCurrentDateTime($o_cassandra,$vehicle_imei,$sortFetchData);
                            //var_dump($st_results);
                            //$xml_current = "../../../xml_vts/xml_data/".$today_date2."/".$vehicle_imei.".xml";
                            if($logResult!='')
                            {
								if($num_rows1>0)
								{
  							echo'<tr>
  									<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'">						
  									&nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')"><font color="green">'.$vehicle_name.'</font>(<font color="blue">'.$vehicletag.')</font></A></td>';							
  									include('exiting_io_status.php');
  							echo'</tr>';
								}
  						}
  						else
  						{
							if($num_rows1>0)
							{
  						echo'<tr>
  									<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'">						
  									&nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')"><font color="gray">'.$vehicle_name.'</font>(<font color="blue">'.$vehicletag.')</font></A></td>';							
  									include('exiting_io_status.php');
  							echo'</tr>';
							}
              
              }
						}
					}
				}
			}
		}
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{ 
		PrintVehicleTag($AccountNode->child[$i],$account_id_local1,$vehicletag);
		}
	}
	
	function GetVehicleType($AccountNode,$account_id_local1,$vehicle_option_values)
	{
		global $vehicleid;	global $vehicle_cnt;  

		$vehicle_type =explode(",",$vehicle_option_values);
		$sizeofvehicletype=sizeof($vehicle_type);

		for($j=0;$j<$sizeofvehicletype;$j++)
		{
			$vehicle_cnt=0;			
			PrintVehicleType($AccountNode,$account_id_local1,$vehicle_type[$j]);	
		}
	}
	
	function PrintVehicleType($AccountNode,$account_id_local1,$vehicletype)
	{
		global $vehicleid;	
		global $vehicle_cnt;	
		global $DbConnection;     
		global $today_date2;
		global $list_fname;
		global $feature_count;
		global $fname;
                //var_dump($o_cassandra);
                global $logDate;
		//echo "acc1=".$AccountNode->data->AccountID."<br>acc2=".$account_id_local1;
		if($AccountNode->data->AccountID==$account_id_local1)
		{
			for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
			{
				if($AccountNode->data->VehicleType[$j]==$vehicletype)
				{
					$vehicle_id = $AccountNode->data->VehicleID[$j];
					$vehicle_name = $AccountNode->data->VehicleName[$j];
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
							$query="SELECT io FROM device_manufacturing_info WHERE device_imei_no='$vehicle_imei' and status=1";  ///get parent account feature	
							//echo"Query=".$query."<br>";
							$result=@mysql_query($query,$DbConnection);
							$num_rows1=0;
							$row=mysql_fetch_row($result);
							if($row[0]!="")
							{
								$num_rows1=@mysql_num_rows($result);
								$io_id=$row[0];
								$io_id1=explode(",",$io_id);
								$NoofIO=sizeof($io_id1);
							}
							$vehicleid[$vehicle_cnt]=$vehicle_id;
							$vehicle_cnt++;
						 $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                            //$st_results = getCurrentDateTime($o_cassandra,$vehicle_imei,$sortFetchData);
                            //var_dump($st_results);
                            //$xml_current = "../../../xml_vts/xml_data/".$today_date2."/".$vehicle_imei.".xml";
                            if($logResult!='')
                            {									
								if($num_rows1>0)
								{
								echo'<tr>';
									echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'">						
									&nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')"><font color="green">'.$vehicle_name.'</font></A></td>';							
									include('exiting_io_status.php');
								echo'</tr>';
								}								
							}
							else
							{							
								if($num_rows1>0)
								{
									echo'<tr>';
									echo'<td align="left">&nbsp;<INPUT TYPE="checkbox"  name="vehicle_id[]" VALUE="'.$vehicle_id.'">						
									&nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')"><font color="grey">'.$vehicle_name.'</font></A></td>';							
									include('exiting_io_status.php');
									echo'</tr>';
								}							
							}
						}
					}
				}
			}
		}
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{ 
		PrintVehicleType($AccountNode->child[$i],$account_id_local1,$vehicletype);
		}
	}
?>
