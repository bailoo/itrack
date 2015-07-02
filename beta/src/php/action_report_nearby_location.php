<?php

//error_reporting(-1);
//ini_set('display_errors', 'On');
    include_once("main_vehicle_information_1.php");
    include_once('Hierarchy.php');
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    
    set_time_limit(300);  
    include_once("calculate_distance.php");
    include_once("report_title.php");
  
    include_once('xmlParameters.php');
    include_once('parameterizeData.php');
    include_once('lastRecordData.php');
    include_once("getXmlData.php");    

    $selectVehicleImei = $_POST['vehicleSerial'];
    $selectedAccountId = $_POST['selectedAccountId'];  
    $sortBy="h";    
    
    $parameterizeData=new parameterizeData();
    $parameterizeData->latitude='d';
    $parameterizeData->longitude='e';
    $LastRecordObject=new lastRecordData();	
    //echo "imei=".$imei."<br>";
    $LastRecordObject=getLastRecord($selectVehicleImei,$sortBy,$parameterizeData);
    //var_dump($LastRecordObject);
    if(!empty($LastRecordObject))
    {        
        $latFirst = $LastRecordObject->latitudeLR[0];
        $lngFirst = $LastRecordObject->longitudeLR[0];       
    }
    //echo "latFirst=".$latFirst."lngFirst=".$lngFirst."<br>";
    $LastRecordObject=null;
    $parameterizeData=null;
   
    global $distanceFlag;
    $distanceFlag=0;
	//echo "distance=".$distance."<br>";
echo "<br><center><b>Near By Vehicle</center><br>";
        echo'<div style="height:500px;overflow:auto">
<table border=1 width="55%" rules=all bordercolor="#e5ecf5" align="center" cellspacing=0 cellpadding=3>	
    <tr>
        <td class="text">
        <b>Serial
        </td>
        <td class="text">
        <b>Vehicle Name
        </td>
        <td class="text">
        <b>Distance
        </td>
        <td class="text">
        <b>Mobile Number
        </td>
    </tr>';
global $serial;
$serial=0;
PrintAllVehicle($root, $selectedAccountId,$selectVehicleImei,$latFirst,$lngFirst);
global $distanceFlag;
if($distanceFlag==0)
{
    echo "<tr>
                <td colspan='4' align='center' class='text'>No Data Found</td>
        </tr>";
}
echo"</table></div>";
echo'<center>		
		<a href="javascript:showReportPrevPageNew();" class="back_css">
			&nbsp;<b>Back</b>
		</a>
	</center>';
function PrintAllVehicle($root, $local_account_id,$selectVehicleImei,$latFirst,$lngFirst)
{
    global $va,$vb,$vc,$vd,$ve,$vf,$vg,$vh,$vi,$vj,$vk,$vl,$vm,$vn,$vo,$vp,$vq,$vr,$vs,$vt,$vu,$vv,$vw,$vx,$vy,$vz,$vaa,$vab;
    global $old_xml_date;
    global $vehicleid;
    global $serial;
    global $vehicle_cnt;
    global $distanceFlag;
    //$distanceFlag=0;
    global $title;	
    $type = 0;
    $sortBy="h";
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
            $mobile_number = $root->data->MobileNumber[$j];
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
                    ///echo "firstImei=".trim($selectVehicleImei)."SecondImei=".trim($vehicle_imei)."<br>";
                    if(trim($selectVehicleImei)!=trim($vehicle_imei))
                    {
                        
                        $parameterizeData=new parameterizeData();
                        $parameterizeData->latitude='d';
                        $parameterizeData->longitude='e';
                        $LastRecordObject=new lastRecordData();	
                        //echo "imei=".$imei."<br>";
                        $LastRecordObject=getLastRecord($vehicle_imei,$sortBy,$parameterizeData);

                        if(!empty($LastRecordObject))
                        {        
                            $latNext = $LastRecordObject->latitudeLR[0];
                            $lngNext = $LastRecordObject->longitudeLR[0]; 
                            calculate_distance($latFirst, $latNext, $lngFirst, $lngNext, $distance);
                            //echo "distance=".$distance."<br>";
                            if($distance<=10)
                            {
                                $distanceFlag=1;
                                $serial++;
                        echo "<tr>
                                <td class='text'>".$serial."</td>
                                <td class='text'>".$vehicle_name."</td>
                                <td class='text'>".round($distance,2)."</td>";
                                if($mobile_number=="")
                                {
                                echo"<td class='text'>-</td>";
                                }
                                else
                                {
                                    echo"<td class='text'>".$mobile_number."</td>";
                                }
                        echo"</tr>";
                            }
                        }
                        $LastRecordObject=null;
                        $parameterizeData=null;                      
                    }
                }
            }
        }
    }
	
    $ChildCount=$root->ChildCnt;
    for($i=0;$i<$ChildCount;$i++)
    { 
            PrintAllVehicle($root->child[$i],$local_account_id,$select_vehicle,$latFirst,$lngFirst);
    }
}


?>								
