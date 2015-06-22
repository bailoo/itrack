<?php 
    include_once('Hierarchy.php');
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
    include_once("../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/    //##### INCLUDE CASSANDRA API*/
    
    $o_cassandra = new Cassandra();	
    $o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);

    $query1="SELECT vehicle_color from account_preference WHERE account_id='$account_id'";
    $result1=mysql_query($query1,$DbConnection);
    $row1=mysql_fetch_object($result1);
    $vehicle_color1=$row1->vehicle_color;

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

    if($report_type_title=="Trip Report") //////coming from session
    {
        $filename = 'report_trip.php'; 
    }
    else if($report_type_title=="Vehicle Movement Report") //////coming from session
    {
        $filename = 'report_trip_vehicle_movement.php'; 
    }
    else if($report_type_title=="Trip Summary Report") //////coming from session
    {
        $filename = 'report_trip_summary.php'; 
    }

    if($report_type_title=="Trip Report" || $report_type_title=="Vehicle Movement Report" || $report_type_title=="Trip Summary Report") //////coming from session
    {
        echo"group_vehicles##";
        //echo  "T=".$report_type_title;
        include_once($filename);
        echo'<input type="hidden" id="common_id_local1" value="'.$common_id_local.'">';
        echo'<input type="hidden" id="report_type_title" value="'.$report_type_title.'">';
    }
    //date_default_timezone_set('Asia/Calcutta');
    $current_date = date('Y-m-d');		
    /*else
    {
        echo"<form name='report1'>";
    }*/	

//////////////// FUNCTIONS ////////////////////////////////////////////		
    function common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$option_name)
    {	
        global $type_tag;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;

        //$td_cnt++;
        global $td_cnt;
        if($td_cnt==1)
        {
            echo'<tr>';
        }
        //date_default_timezone_set('Asia/Calcutta');
        $current_date = date('Y-m-d');

        $xml_file = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
        //echo "<br>xml_file=". $xml_file;	
        //echo $vehicleSerial[$i];
        if(file_exists($xml_file))
        {								  
        //echo "<br>1";
        //echo "td_cnt=".$td_cnt."<br>";
        echo'<td align="left">
                &nbsp;<INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$vehicle_imei.'">
            </td>
            <td class=\'text\'>
            &nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')">
                    <font color="'.$vcolor2.'">'.$vehicle_name.'</font>&nbsp;'; 
                        if($type_tag==1) 
                        {
                            echo'('.$option_name.')';                
                        } 
                echo'</font>
                </A>
            </td>';
        }
        else
        {
        //echo "<br>2";
        echo'<td align="left">
                &nbsp;
                <INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$vehicle_imei.'">
            </td>
             <td class=\'text\'>
                &nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$vehicle_id.')">
                        <font color="'.$vcolor3.'">'.$vehicle_name.'&nbsp;';
                        if($type_tag==1) 
                        {
                                echo'('.$option_name.')';                                
                        } 
                    echo'</font>
                    </A>
            </td>';
        }

        if($td_cnt==3)
        { 
            echo'</tr><tr>'; $td_cnt=0;
        }
    }

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
  
    function common_display_vehicle_radio($vehicle_name_arr,$vehicleid_or_imei,$color,$vehicle_type_arr)
    {
        global $type;        	  
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
            echo'<td align="left">
                  <INPUT TYPE="radio"  name="vehicleserial" VALUE="'.$vehicleid_or_imei[$vehicle].'">
                </td>
                <td class=\'text\'> 				
                  <font color="'.$color.'">'.$vehicle.'</font>';if($type==1){echo ' ('.$vehicle_type_arr[$vehicle].')';}echo'		
                </td>';         
                if($cnt==3)
         	{
                    echo'</tr><tr>';
                    $cnt=0;
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

    function get_all_vehicle_radio($local_account_id,$div_option_values)
    {
        //echo "<br>accnode=".$AccountNode." ,div_option_values".$div_option_values;
        global $root;
        global $vehicleid;
	global $vehicle_cnt;  
	$vehicle_cnt=0;
	//$user_name = GetUserName($AccountNode,$div_option_values);	
        if($div_option_values == "all")
        {
            PrintAllVehicleRadio($root, $local_account_id);
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
        global $o_cassandra;
        
       // var_dump($o_cassandra);
        global $logDate;

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
                        $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                        //$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
                        //if (file_exists($xml_current))
                        if ($logResult!="")
                        {
                            $color = $vcolor2;
                            $vehicle_name_arr[$color][] =$vehicle_name;
                            if($title=="Fuel Report" || $title=="Fuel Halt Report")
                            { 
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;
                            }
                            else
                            {
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
                        }
                        else
                        { 
                            $color = $vcolor3;      					  
                            $vehicle_name_arr[$color][] =$vehicle_name; 
                            if($title=="Fuel Report")
                            { 
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;
                            }
                            else
                            {
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
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

    function PrintAllVehicleRadio($root, $local_account_id)
    {
        global $vehicleid;
	global $vehicle_cnt;
	global $td_cnt;
	global $type_tag;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;
        global $title;	
        global $o_cassandra;
        global $logDate;
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
                        $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                        //$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
                        //if (file_exists($xml_current))
                        if($logResult!="")
                        {
                            $color = $vcolor2;
                            $vehicle_name_arr[$color][] =$vehicle_name;
                            if($title=="Fuel Report" || $title=="Fuel Halt Report")
                            { 
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;
                            }
                            else
                            {
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
                        }
                        else
                        { 
                            $color = $vcolor3;      					  
                            $vehicle_name_arr[$color][] =$vehicle_name; 
                            if($title=="Fuel Report")
                            { 
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_id;
                            }
                            else
                            {
                                $vehicleid_or_imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
                        }
                        //$td_cnt++;
                        //common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$AccountNode->data->AccountGroupName); 											
                    }
                }
            }
	}
	$color = $vcolor2;
        common_display_vehicle_radio($vehicle_name_arr[$color],$vehicleid_or_imei_arr[$color],$color,'');
        $color = $vcolor3; 
        common_display_vehicle_radio($vehicle_name_arr[$color],$vehicleid_or_imei_arr[$color],$color,'');
	$ChildCount=$root->ChildCnt;
        for($i=0;$i<$ChildCount;$i++)
	{ 
            PrintAllVehicleRadio($root->child[$i],$local_account_id);
	}
    }

    function get_all_persons($local_account_id)
    {
	//echo "<br>accnode=".$AccountNode." ,div_option_values".$div_option_values;
	//echo "<br>accnode=".$local_account_id;
        global $root;
        global $personid;
	global $person_cnt; 
        global $current_date;
	$vehicle_name_arr=array();
	$imei_arr=array();
	$vehicle_color=array();  

	$type=0;
	//$user_name = GetUserName($AccountNode,$div_option_values);	
        //if($div_option_values == "all")
        PrintAllPerson($root, $local_account_id);
    } 

    function PrintAllPerson($root, $local_account_id)
    {
        global $personid;
	global $person_cnt;
	global $td_cnt;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;
        global $o_cassandra;
        global $logDate;
	//global $type_tag;
	//$type_tag = 0;
	
	if($root->data->AccountID==$local_account_id)
	{
            $td_cnt =0;
            for($j=0;$j<$root->data->PersonCnt;$j++)
            {			    
                $person_id = $root->data->PersonID[$j];
                $person_name = $root->data->PersonName[$j];
                $person_imei = $root->data->PersonImeiNo[$j];
                if($person_id!=null)
                {
                    for($i=0;$i<$person_cnt;$i++)
                    {
                        if($personid[$i]==$person_id)
                        {
                            break;
                        }
                    }			
                    if($i>=$person_cnt)
                    {
                        $personid[$person_cnt]=$person_id;
                        $person_cnt++;
                        //$td_cnt++;
                        $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                        //$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
                        //if (file_exists($xml_current))
                        if($logResult!="")
                        {
                            $color = $vcolor2;
                            $vehicle_name_arr[$color][] =$vehicle_name; 
                            $imei_arr[$color][$vehicle_name]=$vehicle_imei;
                        }
                        else
                        { 
                            $color = $vcolor3;      					  
                            $vehicle_name_arr[$color][] =$vehicle_name; 
                            $imei_arr[$color][$vehicle_name]=$vehicle_imei;
                        }							
                    }
                }
            }
	}
	
	$color = $vcolor2;
        common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,'');
        $color = $vcolor3;
        common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,'');
	$ChildCount=$root->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
            PrintAllPerson($root->child[$i],$local_account_id);
	}
    }    

    /*function GetUserName($AccountNode,$userid)
    {
        if(($AccountNode->data->AccountID!=null) && ($AccountNode->data->AccountID==$userid))
	{
            return $AccountNode->data->AccountName;
	}
	else
	{
            $ChildCount=$AccountNode->ChildCnt;
            for($i=0;$i<$ChildCount;$i++)
            {
                $tmpUserName = GetUserName($AccountNode->child[$i],$userid);
                if($tmpUserName!=null)
                {
                    return $tmpUserName;
                }
            }
            return null;	
        }
    }*/
		
    function get_vehicle_tag_vehicle ($local_account_id,$div_option_values)
    {
        global $vehicleid;
        global $vehicle_cnt;
        global $root;
    	$vehicle_tag =explode(",",$div_option_values);
	$sizeofvehicletag=sizeof($vehicle_tag);
	for($j=0;$j<$sizeofvehicletag;$j++)
	{
            $vehicle_cnt=0;			
            PrintVehicleTag($root, $local_account_id,$vehicle_tag[$j]);
	}
    }		
	
    function PrintVehicleTag($AccountNode, $local_account_id,$vehicletag)
    {
	global $vehicleid;
	global $vehicle_cnt;
        global $td_cnt; 
        global $type;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;  
        global $o_cassandra;
        global $logDate;
        $type = 1;
  	global $current_date;
	$vehicle_name_arr=array();
	$imei_arr=array();
	$vehicle_color=array();
	$vehicle_tag_arr=array();
        if($AccountNode->data->AccountID == $local_account_id)
        {
            for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
            {
  		$vehicle_tag_local = $AccountNode->data->VehicleTag[$j];
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
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                            //$td_cnt++;
                            //common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$vehicle_tag_local);
                            $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                            //$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
                            //if (file_exists($xml_current))
                            if($logResult!="")
                            {
                                $color = $vcolor2;
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
                            else
                            { 
                                $color = $vcolor3;      					  
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
                            $vehicle_tag_arr[$color][$vehicle_name]=$vehicle_tag_local;
                        }
                    }
  		}
            }  
        }
	$color = $vcolor2; 
        common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_tag_arr[$color]);
        $color = $vcolor3;
        common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_tag_arr[$color]);
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
            PrintVehicleTag($AccountNode->child[$i],$local_account_id,$vehicletag);
	}
    }

    function get_vehicle_tag_vehicle_radio ($local_account_id,$div_option_values)
    {
	global $vehicleid;
	global $vehicle_cnt;
        global $root;
    	$vehicle_tag =explode(",",$div_option_values);
	$sizeofvehicletag=sizeof($vehicle_tag);
        for($j=0;$j<$sizeofvehicletag;$j++)
	{
            $vehicle_cnt=0;			
            PrintVehicleTagRadio($root, $local_account_id,$vehicle_tag[$j]);
	}
    }

    function PrintVehicleTagRadio($AccountNode, $local_account_id,$vehicletag)
    {
	global $vehicleid;
	global $vehicle_cnt;
        global $td_cnt; 
        global $type;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;
        global $o_cassandra;
        global $logDate;
        $type = 1;
  	global $current_date;
	$vehicle_name_arr=array();
	$imei_arr=array();
	$vehicle_color=array();
	$vehicle_tag_arr=array();
        if($AccountNode->data->AccountID == $local_account_id)
        {
            for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
            {   
  		$vehicle_tag_local = $AccountNode->data->VehicleTag[$j];
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
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                            //$td_cnt++;
                            //common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$vehicle_tag_local);
                            $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                            //$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
                            //if (file_exists($xml_current))
                            if($logResult!="")
                            {
                                $color = $vcolor2;
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
                            else
                            { 
                                $color = $vcolor3;      					  
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
                            $vehicle_tag_arr[$color][$vehicle_name]=$vehicle_tag_local;
                        }
                    }
                }
            }  
        }
	$color = $vcolor2; 
        common_display_vehicle_radio($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_tag_arr[$color]);
        $color = $vcolor3; 
        common_display_vehicle_radio($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_tag_arr[$color]);
	$ChildCount=$AccountNode->ChildCnt;
	for($i=0;$i<$ChildCount;$i++)
	{ 
            PrintVehicleTagRadio($AccountNode->child[$i],$local_account_id,$vehicletag);
	}
    }
	
    function  get_vehicle_type_vehicle($local_account_id,$div_option_values)
    {
        global $vehicleid;
	global $vehicle_cnt; 
        global $root; 
        global $type;
        $type =1;
        $vehicle_type =explode(",",$div_option_values);
	$sizeofvehicletype=sizeof($vehicle_type);
	for($j=0;$j<$sizeofvehicletype;$j++)
	{
            $vehicle_cnt=0;			
            //echo "<br>acnode=".$local_account_id." ,vtype=".$vehicle_type[$j];
            PrintVehicleType($root, $local_account_id, $vehicle_type[$j]);			
	}
    }
	
    function PrintVehicleType($AccountNode, $local_account_id, $vehicletype)
    {
        //echo "<br>Acnode1=".$AccountNode;    	
	//$ChildCount=$AccountNode->ChildCnt;
	//echo "<br>childcnt=".$ChildCount;	
        global $vehicleid;
	global $vehicle_cnt;
        global $td_cnt; 
        global $current_date;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;
        global $o_cassandra;
        global $logDate;
    
	$vehicle_name_arr=array();
	$imei_arr=array();
	$vehicle_color=array();
	$vehicle_type_arr=array();
		
        if($AccountNode->data->AccountID == $local_account_id)
        {  
            for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
            {
  		$vehicle_type_local = $AccountNode->data->VehicleType[$j];
  		if($vehicle_type_local==$vehicletype)
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
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                            $td_cnt++;
                            //common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$vehicle_type_local);
                            $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                            //$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
                            //if (file_exists($xml_current))
                            if($logResult!="")
                            {
                                $color = $vcolor2;
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei; 					
                            }
                            else
                            { 
                                $color = $vcolor3;      					  
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
                            $vehicle_type_arr[$color][$vehicle_name]=$vehicle_type_local;
                        }
                    }
  		}
            }
        }
        $color = $vcolor2; 
        common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_type_arr[$color]);
        $color = $vcolor3; 
        common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_type_arr[$color]);
	$ChildCount=$AccountNode->ChildCnt;
	//echo "<br>childcnt=".$ChildCount;
	for($i=0;$i<$ChildCount;$i++)
	{ 
            //echo "<br>acnodechild=".$AccountNode->child[$i]." ,vtype=".$vehicletype;
            PrintVehicleType($AccountNode->child[$i],$local_account_id,$vehicletype);
	}
    }

    function  get_vehicle_type_vehicle_radio($local_account_id,$div_option_values)
    {
        global $vehicleid;
	global $vehicle_cnt; 
        global $root; 
        global $type;
        $type =1;
  	$vehicle_type =explode(",",$div_option_values);
	$sizeofvehicletype=sizeof($vehicle_type);
	for($j=0;$j<$sizeofvehicletype;$j++)
	{
            $vehicle_cnt=0;			
            //echo "<br>acnode=".$local_account_id." ,vtype=".$vehicle_type[$j];
            PrintVehicleTypeRadio($root, $local_account_id, $vehicle_type[$j]);			
	}
    }

    function PrintVehicleTypeRadio($AccountNode, $local_account_id, $vehicletype)
    {
        //echo "<br>Acnode1=".$AccountNode;    	
	//$ChildCount=$AccountNode->ChildCnt;
	//echo "<br>childcnt=".$ChildCount;	
        global $vehicleid;
	global $vehicle_cnt;
        global $td_cnt; 
        global $current_date;
        global $vcolor1;
        global $vcolor2;
        global $vcolor3;
        global $o_cassandra;
        global $logDate;
    	$vehicle_name_arr=array();
	$imei_arr=array();
	$vehicle_color=array();
	$vehicle_type_arr=array();
        if($AccountNode->data->AccountID == $local_account_id)
        {  
            for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
            {
  		$vehicle_type_local = $AccountNode->data->VehicleType[$j];
  		if($vehicle_type_local==$vehicletype)
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
                            $vehicleid[$vehicle_cnt]=$vehicle_id;
                            $vehicle_cnt++;
                            $td_cnt++;
                            //common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name,$vehicle_type_local);
                            $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $logDate);
                            //$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
                            //if (file_exists($xml_current))
                            if($logResult!="")
                            {
                                $color = $vcolor2;
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei; 					
                            }
                            else
                            { 
                                $color = $vcolor3;      					  
                                $vehicle_name_arr[$color][] =$vehicle_name; 
                                $imei_arr[$color][$vehicle_name]=$vehicle_imei;
                            }
                            $vehicle_type_arr[$color][$vehicle_name]=$vehicle_type_local;
                        }
                    }
  		}
            }
        }
        $color = $vcolor2; 
        common_display_vehicle_radio($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_type_arr[$color]);
        $color = $vcolor3; 
        common_display_vehicle_radio($vehicle_name_arr[$color],$imei_arr[$color],$color,$vehicle_type_arr[$color]);
	$ChildCount=$AccountNode->ChildCnt;
	//echo "<br>childcnt=".$ChildCount;
	for($i=0;$i<$ChildCount;$i++)
	{ 
            //echo "<br>acnodechild=".$AccountNode->child[$i]." ,vtype=".$vehicletype;
            PrintVehicleTypeRadio($AccountNode->child[$i],$local_account_id,$vehicletype);
	}
    } 
    //////////////// FUNCTIONS FOR PERSON ////////////////////////////////////////////		
    function common_function_for_person($person_imei,$person_id,$person_name,$option_name)
    {	
        global $type_tag;
        //$td_cnt++;
        global $td_cnt;
        if($td_cnt==1)
        {
            echo'<tr>';
        }
        //date_default_timezone_set('Asia/Calcutta');
        $current_date = date('Y-m-d');
        $xml_file = "../../../xml_vts/xml_data/".$current_date."/".$person_imei.".xml";
        //echo "<br>xml_file=". $xml_file;	
	//echo $vehicleSerial[$i];
	if(file_exists($xml_file))
	{								  
            //echo "<br>exists";
            //echo "td_cnt=".$td_cnt."<br>";
	   echo'<td align="left">
                    &nbsp;<INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$person_imei.'">
                </td>
                <td class=\'text\'>
                    &nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$person_id.')">
                            <font color="darkgreen">
                                '.$person_name.'
                            </font>&nbsp;'; 
                    echo'</A>
                </td>';
        }
        else
        {
        echo'<td align="left">
                &nbsp;<INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$person_imei.'">
            </td>
             <td class=\'text\'>
                &nbsp;<A HREF="#" style="text-decoration:none;" onclick="main_vehicle_information('.$person_id.')">
                    '.$person_name.'&nbsp;';
                    if($type_tag==1) 
                    {
                        echo'('.$option_name.')';                        
                    } 
                    echo'</A><
            /td>';
        }
	if($td_cnt==3)
	{ 
	   echo'</tr>';
	}
    } 
?>
