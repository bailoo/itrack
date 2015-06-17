<?php 
include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$root=$_SESSION['root'];
$vehicleid=array();
$vehicle_cnt;	
$td_cnt=0;
$type_tag;
//global $report_type_title;
$common_id_local=$_POST['common_id'];		
$group_status1=$_POST['group_status'];	
$report_type_title=$_POST['report_type1'];
//echo "repirt_trip_opto=".$report_type_title."<br>";
//date_default_timezone_set('Asia/Calcutta');	
$current_date = date('Y-m-d');

if($report_type_title=="Trip Report") //////coming from session
{
  $filename = 'report_trip.php'; 
}
if($report_type_title=="Trip Report New") //////coming from session
{
  $filename = 'report_trip_new.php'; 
}
else if($report_type_title=="Vehicle Movement Report") //////coming from session
{
  $filename = 'report_trip_vehicle_movement.php'; 
}
else if($report_type_title=="Trip Summary Report") //////coming from session
{
  $filename = 'report_trip_summary.php'; 
}

if($report_type_title=="Trip Report" || $report_type_title=="Trip Report New" || $report_type_title=="Vehicle Movement Report" || $report_type_title=="Trip Summary Report") //////coming from session
{
  echo"group_vehicles##";
  //echo  "T=".$report_type_title;
 // echo "filename=".$filename;
	include_once($filename);
  echo'<input type="hidden" id="common_id_local1" value="'.$common_id_local.'">';
  echo'<input type="hidden" id="report_type_title" value="'.$report_type_title.'">';
}
  function common_display_vehicle($vehicle_name_arr,$imei_arr,$color)
  { 
	//global $report_type_title;
    //echo "color=".$color;        	  
    if(sizeof($vehicle_name_arr)>0)
    {   
      natcasesort($vehicle_name_arr);
      foreach($vehicle_name_arr as $vehicle)
      { 
        $cnt++;          
      		if($cnt==1){echo'<tr>';}         		
          echo'<td align="left">';
		 /* if($report_type_title=="Trip Report New")
		  {
			echo'<INPUT TYPE="radio"  name="vehicleserial" VALUE="'.$imei_arr[$vehicle].'">';
		  }
		  else
		  {
			echo'<INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$imei_arr[$vehicle].'">';
		  }*/
		  echo'<INPUT TYPE="checkbox"  name="vehicleserial[]" VALUE="'.$imei_arr[$vehicle].'">';
            echo' </td>
                <td class=\'text\'> 				
                  <font color="'.$color.'">'.$vehicle.'</font>';echo'		
                </td>';         
        	if($cnt==3)
         	{echo'</tr><tr>';$cnt=0;}
      }
    }      
  }					

  //print_all_vehicle($root, $group_id);

	   	
	function print_group_vehicle($AccountNode,$groupid,$category1)
	{
	// echo "in group vehicle<br>";
		global $vehicleid;
		global $vehicle_cnt;
    global $current_date;     
    $vehicle_name_arr=array();
		$imei_arr=array();
		$vehicle_color=array(); 
   // echo "groupidlocal=".$AccountNode->data->AccountGroupID."group_id=".$groupid."<br>";
  	
		if($AccountNode->data->AccountGroupID==$groupid)
		{   
        echo'<tr>
              <td colspan=3>'.$AccountNode->data->AccountName.'</td>
            </tr>';		  
			for($j=0;$j<$AccountNode->data->VehicleCnt;$j++)
			{
			  //echo "category=".$category1."local=".$AccountNode->data->VehicleCategory[$j]."<br>";
				if($AccountNode->data->VehicleCategory[$j]==$category1)
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
						 // echo "in if<br>";
							$vehicleid[$vehicle_cnt]=$vehicle_id;
							$vehicle_cnt++;
							$xml_current = "../../../xml_vts/xml_data/".$current_date."/".$vehicle_imei.".xml";
						  //echo "xml_current=".$xml_current."<br>";
      					if(file_exists($xml_current))
      					{
      					// echo "in if";
      					 $color="green";
      					 $vehicle_name_arr[$color][] =$vehicle_name; 
      					 $imei_arr[$color][$vehicle_name]=$vehicle_imei;
      					}
      					else
      					{
      					  // echo "in else";
                  $color="gray";      					  
      						$vehicle_name_arr[$color][] =$vehicle_name; 
      					  $imei_arr[$color][$vehicle_name]=$vehicle_imei;
      					}
							//common_function_for_vehicle($vehicle_imei,$vehicle_id,$vehicle_name);					
						}
					}
				}
			}      
    }  
   // echo "sizeofgreen=".$vehicle_name_arr[$color]."sizeofgray=".$vehicle_name_arr[$color]."<br>";   
		$color="green";common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);
    $color="gray"; common_display_vehicle($vehicle_name_arr[$color],$imei_arr[$color],$color);
		$ChildCount=$AccountNode->ChildCnt;
		for($i=0;$i<$ChildCount;$i++)
		{ 
			print_group_vehicle($AccountNode->child[$i],$groupid,$category1);
		}
	}	
		


?>
