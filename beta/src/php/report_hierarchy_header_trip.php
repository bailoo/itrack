<?php 
	
    include_once('Hierarchy.php');
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    
    /*include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
    include_once("../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/    //##### INCLUDE CASSANDRA API*/
    
    /*$o_cassandra = new Cassandra();	
    $o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);*/
    $div_option_values = "all";
    $local_account_id = $_POST['account_id'];
	
    $vehicle_color1=getColorFromAP($account_id,$DbConnection); /// A->Account P->Preference

    $vcolor = explode(':',$vehicle_color1); //account_name:active:inactive
    $vcolor1 = "#".$vcolor[0];
    $vcolor2 = "#".$vcolor[1];
    $vcolor3 = "#".$vcolor[2];

    $root=$_SESSION['root'];
    $vehicleid=array();
    $vehicle_cnt;	
    $td_cnt=0;
    $type_tag;
    $common_id_local=$_POST['common_id'];		
    $group_status1=$_POST['group_status'];	
    $report_type_title=$_POST['report_type1'];
    $logDate=date('Y-m-d');
  
    $current_date = date('Y-m-d');		
    /*else
    {
        echo"<form name='report1'>";
    }*/	
	echo "vehicle_trip##";
	
	echo'
	<fieldset class="report_fieldset">
		<legend>Select Vehicle</legend>	
					
			<table border=0  cellspacing=0 cellpadding=0  width="100%">
				<tr>
					<td align="center">							
						<div style="overflow: auto;height: 150px; width: 650px;" align="center">
							<table border=0 cellspacing=0 cellpadding=0 align="center" width="100%">';						
                
								 echo'<tr><td height="10px" align="center" colspan="6" class=\'text\'>&nbsp;<input type=\'checkbox\' name=\'all\' value=\'1\' onClick=\'javascript:select_all_vehicle(this.form);\'>&nbsp;&nbsp;Select All</td></tr>';                 
								 get_all_vehicle($local_account_id,$div_option_values);
                														
								echo'
							</table>
						</div>
					</td>
				</tr>
			</table>
      </fieldset>';
		

//////////////// FUNCTIONS ////////////////////////////////////////////		 

    function common_display_vehicle($vehicle_name_arr,$vehicleid_or_imei,$color,$vehicle_type_arr)
    {
        global $type;
        global $report_station_halt_option;	
	//echo "report_station_halt_option=".$report_station_halt_option."<br>";
	if(sizeof($vehicle_name_arr)>0)
	{
            natcasesort($vehicle_name_arr);
            foreach($vehicle_name_arr as $vehicle)
            { 
                //echo "<br>in common display";				
                $cnt++;          
                if($cnt==1)
                {
                echo'<tr>';
                }         		
                    echo'<td align="left">';
                if($report_station_halt_option=="1")
                {
                    //echo'<INPUT TYPE="radio"  name="station_vehicle_id" VALUE="'.$vehicleid_or_imei[$vehicle].','.$vehicle.'">';
                    echo'<INPUT TYPE="checkbox"  name="station_vehicle_id[]" VALUE="'.$vehicleid_or_imei[$vehicle].','.$vehicle.'">';
                }
                else
                {
                    echo'<INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$vehicleid_or_imei[$vehicle].'">';
                }
                echo'</td>
                    <td class=\'text\'> 				
                        <font color="'.$color.'">
                            '.$vehicle.'
                        </font>';
                if($type==1)
                {
                    echo ' ('.$vehicle_type_arr[$vehicle].')';
                }
                echo'</td>';         
                if($cnt==3)
                {
                    echo'</tr><tr>';$cnt=0;
                }
            }
        }            
    }
    						
    function get_all_vehicle($local_account_id,$div_option_values)
    {
        //echo "<br>accnode=".$AccountNode." ,div_option_values".$div_option_values;
        global $root;
        global $vehicleid;
		global $vehicle_cnt;  
		$vehicle_cnt=0;
		//$user_name = GetUserName($AccountNode,$div_option_values);	
        if($div_option_values == "all")
        {
            PrintAllVehicle($root, $local_account_id);
        }
    }	
	   	
    function PrintAllVehicle($root, $local_account_id)
    {
        global $vehicleid;
        global $vehicle_cnt;
        global $td_cnt;
        global $type_tag;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;
        global $title;
        //global $o_cassandra;
        //global $logDate;

        $type = 0;
        global $current_date;
        $vehicle_name_arr=array();
        $imei_arr=array();
        $vehicleid_or_imei_arr=array();
        $vehicle_color=array(); 

        if($root->data->AccountID==$local_account_id)
        {
            $td_cnt =0;
            for($j=0;$j<$root->data->VehicleCnt;$j++)
            {			    
                $vehicle_id = $root->data->VehicleID[$j];
                $vehicle_name = $root->data->VehicleName[$j];
                $vehicle_imei = $root->data->DeviceIMEINo[$j];

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
                        if($root->data->DeviceRunningStatus[$j]=="1")
                        {
                            $color = $vcolor2;
                            $vehicle_name_arr[$color][] =$vehicle_name;
                            /*if($title=="Fuel Report" || $title=="Fuel Halt Report")
                            { 
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;
                            }
                            else
                            {*/
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;
                            //}
                        }
                        else
                        { 
                            $color = $vcolor3;      					  
                            $vehicle_name_arr[$color][] =$vehicle_name; 
                            /*if($title=="Fuel Report")
                            { 
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;
                            }
                            else
                            {*/
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;
                            //}
                        }
                        //$td_cnt++;
                        //common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName); 											
                    }
                }
            }
        }
        $color = $vcolor2;
        common_display_vehicle($vehicle_name_arr[$color],$vehicleid_or_imei_arr[$color],$color,'');
        $color = $vcolor3; 
        common_display_vehicle($vehicle_name_arr[$color],$vehicleid_or_imei_arr[$color],$color,'');
        $ChildCount=$root->ChildCnt;
        for($i=0;$i<$ChildCount;$i++)
        { 
            PrintAllVehicle($root->child[$i],$local_account_id);
        }
    }
?>
