<?php
function get_route_db_detail($shift,$route_type)
{
	global $DbConnection;
	global $account_id;
	global $vehicle_name_rdb;		//VEHICLE ROUTE DETAIL
	global $route_name_rdb;
        echo "\nRouteTypeDB=".$route_type;
	//global $remark_rdb;
	global $vehicle_imei_rdb;

	if($shift == "ZBVE")
	{
            echo "<br>IN ZBVE";
//          $query = "SELECT DISTINCT route_assignment2.vehicle_name,route_assignment2.route_name_ev FROM route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND NOT(route_assignment2.route_name_ev='') AND route_assignment2.vehicle_name=vehicle.vehicle_name AND route_assignment2.status=1 Order By route_assignment2.route_name_ev DESC";	
//          $result = mysql_query($query,$DbConnection); 

            if($DEBUG_OFFLINE)
            {		
                $path="D:\\test_app/gps_report/".$account_id."/master/vehicle_route_db_ev#1#40.csv";
            }
            else
            {
                $path = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master/vehicle_route_db_ev#1#40.csv";
            }

            $row = 1;
            if (($handle = fopen($path, "r")) !== FALSE) {

            $file_arr[] = $file;
            $vehicle_tmp = array();
            $route_tmp = array();

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $num = count($data);
                    //echo "<p> $num fields in line $row: <br /></p>\n";
                    $row++;
                    if($row>2)
                    {
                        for ($c=0; $c < $num; $c++) {
                            //echo "DataC=".$data[$c] . "<br />\n";
                            if($c==0)
                            {
                                    $vehicle_tmp[] = $data[$c];
                            }
                            else if($c==1)
                            {
                                    $route_tmp[] = $data[$c];
                            }						
                        }
                    }
                }
                fclose($handle);
            }		


            //while($row = mysql_fetch_object($result))
            for($i=0;$i<sizeof($vehicle_tmp);$i++)
            {
                //$remark_rdb[] = $row->remark_ev;
                $query2 = "SELECT DISTINCT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle,vehicle_grouping WHERE vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
                " vehicle.vehicle_name = '$vehicle_tmp[$i]' AND vehicle_assignment.status=1 AND vehicle_grouping.vehicle_id=vehicle_assignment.vehicle_id AND vehicle_grouping.status=1 AND vehicle_grouping.account_id='$account_id'";				
                //echo "\n".$query2;
                $result2 = mysql_query($query2,$DbConnection); 			
                $numrows = mysql_num_rows($result2);
                //echo "\nNUM=".$numrows;
                if($numrows>0)
                {				
                    $row2 = mysql_fetch_object($result2);
                    $vehicle_name_rdb[] = $vehicle_tmp[$i];
                    $route_name_rdb[] = $route_tmp[$i];
                    $vehicle_imei_rdb[] = $row2->device_imei_no;
                }
            } 
	}	
	else if($shift == "ZBVM")
	{
            echo "<br>IN ZBVM";
//		$query = "SELECT DISTINCT route_assignment2.vehicle_name,route_assignment2.route_name_mor FROM route_assignment2,vehicle WHERE route_assignment2.user_account_id='$account_id' AND NOT(route_assignment2.route_name_mor='') AND route_assignment2.vehicle_name=vehicle.vehicle_name AND route_assignment2.status=1 Order By route_assignment2.route_name_mor DESC";				
            //echo "<br>MOR:".$query;
//		$result = mysql_query($query,$DbConnection); 

            //$path="D:\\test_app/gps_report/".$account_id."/master/vehicle_route_db_ev.csv";
            if($DEBUG_OFFLINE)
            {
               $path="D:\\test_app/gps_report/".$account_id."/master/vehicle_route_db_mor#1#50.csv";	
            }
            else
            {
                $path = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master/vehicle_route_db_mor#1#50.csv";
            }

            $row = 1;
            if (($handle = fopen($path, "r")) !== FALSE) {

            $file_arr[] = $file;
            $vehicle_tmp = array();
            $route_tmp = array();

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $num = count($data);
                        //echo "<p> $num fields in line $row: <br /></p>\n";
                        $row++;
                        if($row>2)
                        {
                            if($data[0]=="DL1M5480")
                            {
                                for ($c=0; $c < $num; $c++) {
                                    //echo "DataC=".$data[$c] . "<br />\n";

                                    if($c==0)
                                    {
                                            $vehicle_tmp[] = $data[$c];
                                    }
                                    else if($c==1)
                                    {
                                            $route_tmp[] = $data[$c];
                                    }													
                                }
                            }
                        }
                }
                fclose($handle);
            }		

            //while($row = mysql_fetch_object($result))
            for($i=0;$i<sizeof($vehicle_tmp);$i++)
            //echo "<br>SizeV=".sizeof($vehicle_tmp);
            //for($i=0;$i<91;$i++)
            {
              //$remark_rdb[] = $row->remark_mor;
              $query2 = "SELECT DISTINCT vehicle_assignment.device_imei_no FROM vehicle_assignment,vehicle,vehicle_grouping WHERE vehicle_assignment.vehicle_id = vehicle.vehicle_id AND ".
                    " vehicle.vehicle_name = '$vehicle_tmp[$i]' AND vehicle_assignment.status=1 AND vehicle_grouping.vehicle_id=vehicle_assignment.vehicle_id AND vehicle_grouping.status=1 AND vehicle_grouping.account_id='$account_id'";				
                    //echo "<br>".$query2;
                    $result2 = mysql_query($query2,$DbConnection); 			
                    $numrows = mysql_num_rows($result2);
                    if($numrows>0)
                    {
                        $row2 = mysql_fetch_object($result2);				
                        //if(($vehicle_tmp[$i]!='DL1M4074') && ($vehicle_tmp[$i]=='DL1LM1328'))
                        //if($vehicle_tmp[$i]!='DL1M4074')
                        //{
                        $vehicle_name_rdb[] = $vehicle_tmp[$i];
                        //echo "\nRoute=".$route[$i];
                        $route_name_rdb[] = $route_tmp[$i];
                        //echo "<br>IMEI=".$row2->device_imei_no;
                        $vehicle_imei_rdb[] = trim($row2->device_imei_no);
                        //}
                    }
            } 
	}
	
	$last_time ="";
	echo "\nROUTEDB=".$route_type;
	updateAll_last_processed_time($shift,$last_time,$route_type);	
}
?>  
