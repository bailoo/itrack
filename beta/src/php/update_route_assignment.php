<?php
echo "TEST";
function update_vehicle_route_assignment($vehicle_input, $route_input, $account_id, $shift)
{
    global $DbConnection;
    //echo "<br>BeforeSort:sizev=".sizeof($vehicle_input)."<br>";
    for($x = 1; $x < sizeof($vehicle_input); $x++)
    {
        $tmp_vehicle_input = $vehicle_input[$x];
        $tmp_route_input = $route_input[$x];
        //echo "<br>Customer=".$tmp_vehicle_input." ,Plant=".$tmp_plant_input." ,Route=".$tmp_route_input;		

        $z = $x - 1;
        $done = false;
        while($done == false)
        {
            $vehicle_tmp1 = $vehicle_input[$z];

            if ($vehicle_tmp1 >$tmp_vehicle_input)
            {
                    $vehicle_input[$z + 1] = $vehicle_input[$z];
                    $route_input[$z + 1] = $route_input[$z];
                    //////////////////
                    $z = $z - 1;
                    if ($z < 0)
                    {
                            $done = true;
                    }
            }
            else
            {
                    $done = true;
            }
        } //WHILE CLOSED

        $vehicle_input[$z + 1] = $tmp_vehicle_input;
        $route_input[$z + 1] = $tmp_route_input;
    }  // FOR CLOSED    //SORTING CLOSED
    
    //echo "<br>AfterSort:sizev=".sizeof($vehicle_input)."<br>";
     
    $final_vehicle = array();
    $final_string = array();
    $tmp_string_arr = array();
    $unique_vehicles_arr = array_unique($vehicle_input);
    //echo "<br>Size-UniqueVehicle=".sizeof($unique_vehicles_arr);
    
    foreach($unique_vehicles_arr as $unique_vehicle)
    {
        $valid_vehicle = false;
        $query = "SELECT vehicle_name FROM route_assignment2 WHERE vehicle_name='$unique_vehicle' AND status=1";
        $result = mysql_query($query,$DbConnection);
        $numrows = mysql_num_rows($result);
        if($numrows > 0)
        {
            //echo "<br>Valid Vehicle";
            //###### STORE ALL ROUTES :UN-MATCHED VEHICLES
            $other_routes = array();
            //$other_routes_arr = array();
            for($i = 0; $i < sizeof($vehicle_input); $i++)
            {
                if($unique_vehicle != $vehicle_input[$i])               
                {                   
                    if (is_numeric($route_input[$i]))                       //## VALIDATE NUMERIC ROUTE
                    {                
                        $other_routes[] = $route_input[$i];
                    }
                }
            }
            $other_routes_arr = array_unique($other_routes);
            //####### STORE ALL ROUTES :MATCHING VEHICLES
            for($i = 0; $i < sizeof($vehicle_input); $i++)
            {
                if($unique_vehicle == $vehicle_input[$i])               
                {                    
                    $valid_route = true;
                    $route_tmp = $route_input[$i];
                    for($j=0;$j<sizeof($tmp_string_arr[$unique_vehicle]);$j++)
                    {
                        if(trim($tmp_string_arr[$unique_vehicle][$j]) == trim($route_input[$i]))
                        {
                            //$route_tmp = $route_input[$i];
                            $valid_route = false;
                            //echo "<Br>Duplicate Route=".$route_tmp;
                            break;
                        }
                    }                        
                    if($valid_route)
                    {
                       // echo "<br>Valid Route, sizeOfOtherRoute=".sizeof($other_routes_arr);
                        //for($j=0;$j<sizeof($other_routes);$j++)
                        foreach($other_routes_arr as $or)
                        {
                           if(trim($or) == trim($route_input[$i]))
                           {
                               //echo "<br>Flag=".$route_flag[$route_input[$i]]." ,route_input=".$route_input[$i];
                               if($route_flag[$route_input[$i]]==null)
                               {
                                   //echo ":FRESH"; 
                                   $route_tmp = $route_input[$i];
                                    $route_flag[$route_input[$i]]=1;
                               }
                               else
                               {
                                   //echo ":@";
                                   $route_tmp = "@".$route_input[$i];
                               }
                               
                               break;
                           }
                        }
                    }

                    if($valid_route)
                    {
                        //echo "<br>ValidRoute2=".$route_input[$i];
                        $tmp_string_arr[$unique_vehicle][] = $route_input[$i];
                        $final_string[$unique_vehicle] .= $route_tmp."/";
                        $valid_vehicle = true;
                    } 
                }
            }

            if($valid_vehicle)
            {
                $final_vehicle[] = $unique_vehicle;
            }
        } // IF NUMROWS CLOSED
    }
    

    $current_datetime = date("Y-m-d H:i:s");
    
    //echo "<br>sizeof(final_vehicle)=".sizeof($final_vehicle);
if(sizeof($final_vehicle)>0)
{
	$secondary_vehicle_list = "";
	//###### READ SECONDARY VEHICLES
        $query ="SELECT vehicle.vehicle_name FROM vehicle,secondary_vehicle WHERE secondary_vehicle.vehicle_id=vehicle.vehicle_id AND secondary_vehicle.status=1 AND vehicle.status=1 AND secondary_vehicle.shift='$shift' AND secondary_vehicle.create_id='$account_id'";
        echo $query."\n";
        $result = mysql_query($query,$DbConnection);
        while($row = mysql_fetch_object($result))
        {
                $secondary_vehicle_list.=$row->vehicle_name.",";
        }

	$secondary_vehicle_list = substr($secondary_vehicle_list, 0, -1);

	//######## 

        if($shift == "ev")
        {
           $query_update1 ="UPDATE route_assignment2 set route_name_ev='',evening_update_time='$current_datetime',remark_ev='UpdtBy-Master',edit_date='$current_datetime',edit_id='$account_id' WHERE user_account_id='$account_id' AND status=1 AND vehicle_name NOT IN($secondary_vehicle_list)";
           //echo "<br>".$query_update;
           $result_update1 = mysql_query($query_update1,$DbConnection);
	}
	else if($shift == "mor")
	{
           $query_update1 ="UPDATE route_assignment2 set route_name_mor='',morning_update_time='$current_datetime',remark_mor='UpdtBy-Master',edit_date='$current_datetime',edit_id='$account_id' WHERE user_account_id='$account_id' AND status=1 AND vehicle_name NOT IN($secondary_vehicle_list)";
           //echo "<br>".$query_update;
           $result_update1 = mysql_query($query_update1,$DbConnection);
	}
}

    
    for($i=0;$i<sizeof($final_vehicle);$i++)
    {
        $route_str = $final_string[$final_vehicle[$i]];
        $route_str = substr($route_str, 0, -1);
                
        if($shift == "ev")
        {
           $query_update2 ="UPDATE route_assignment2 set route_name_ev='$route_str',evening_update_time='$current_datetime',remark_ev='UpdtBy-Master',edit_date='$current_datetime',edit_id='$account_id' WHERE vehicle_name='$final_vehicle[$i]' AND user_account_id='$account_id' AND status=1 AND vehicle_name NOT IN($secondary_vehicle_list)";
           //echo "<br>".$query_update;
           $result_update2 = mysql_query($query_update2,$DbConnection);          
       }
        else if($shift == "mor")
        {
           $query_update2 ="UPDATE route_assignment2 set route_name_mor='$route_str',morning_update_time='$current_datetime',remark_mor='UpdtBy-Master',edit_date='$current_datetime',edit_id='$account_id' WHERE vehicle_name='$final_vehicle[$i]' AND user_account_id='$account_id' AND status=1 AND vehicle_name NOT IN($secondary_vehicle_list)";
           //echo "<br>".$query_update;
           $result_update2 = mysql_query($query_update2,$DbConnection);
        }
    }
}    

?>
