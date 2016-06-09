<style>
.marginBottom-0 {margin-bottom:0;}
.dropdown-submenu{position:relative;}
.dropdown-submenu>.dropdown-menu{top:0;left:100%;margin-top:-6px;margin-left:-1px;-webkit-border-radius:0 6px 6px 6px;-moz-border-radius:0 6px 6px 6px;border-radius:0 6px 6px 6px;}
.dropdown-submenu>a:after{display:block;content:" ";float:right;width:0;height:0;border-color:transparent;border-style:solid;border-width:5px 0 5px 5px;border-left-color:#cccccc;margin-top:5px;margin-right:-10px;}
.dropdown-submenu:hover>a:after{border-left-color:#555;}
.dropdown-submenu.pull-left{float:none;}.dropdown-submenu.pull-left>.dropdown-menu{left:-100%;margin-left:10px;-webkit-border-radius:6px 0 6px 6px;-moz-border-radius:6px 0 6px 6px;border-radius:6px 0 6px 6px;}

.dropdown-submenu:hover .dropdown-menu {
		display: block;
	}
.dropdown-submenu .dropdown-menu {
		display: none;
	}
.scrollable-menu {
    height: auto;
    max-height: 530px;
    overflow-x: hidden;
}       
   
</style>

<?php
$flag_sector = 0;
$flag_distance=0;
$flag_mdistance = 0;
$flag_performance=0;
$flag_summary = 0;
$flag_travel=0;
$flag_engine = 0;
$flag_ac=0;
$flag_load_cell = 0;
$flag_station=0;
$flag_substation = 0;
$flag_visit=0;
$flag_fuel = 0;
$consignment_info=0;
$door_open_info = 0;
$fuel_lead_info=0;
$flag_vehicle_reverse = 0;
$flag_io_trip=0;
for($k=0;$k<$size_feature_session;$k++)
{
    if($feature_name_session[$k] == "sector")
    {
            $flag_sector = 1;
    }

    if($feature_name_session[$k] == "distance")
    {
            $flag_distance = 1;
    }

    if($feature_name_session[$k] == "monthly_distance")
    {
            $flag_mdistance = 1;
    }

    if($feature_name_session[$k] == "performance")
    {
            $flag_performance = 1;
    }

    if($feature_name_session[$k] == "summary")
    {
            $flag_summary = 1;
    }

    if($feature_name_session[$k] == "travel")
    {
            $flag_travel = 1;
    }

    if($feature_name_session[$k] == "engine")
    {
            $flag_engine = 1;
    }
    if($feature_name_session[$k] == "ac")
    {
            $flag_ac = 1;
    }		
    if($feature_name_session[$k] == "load_cell")
    {
            $flag_load_cell = 1;
    }

    if($feature_name_session[$k] == "station")
    {
            $flag_station = 1;
    } 

    if($feature_name_session[$k] == "substation")
    {
            $flag_substation = 1;
    } 

    if($feature_name_session[$k] == "visit_track")
    {
            $flag_visit = 1;
    }
    if($feature_name_session[$k] == "fuel")
    {
                    $flag_fuel = 1;
    }
    if($feature_name_session[$k] == "consignment")
    {
                    $consignment_info = 1;
    } 
    if($feature_name_session[$k] == "door_open")
    {
                    $door_open_info = 1;
    }
    if($feature_name_session[$k] == "fuel_lead")
    {
                    $fuel_lead_info = 1;
    }
    if($feature_name_session[$k] == "vehicle_reverse")
    {
                    $flag_vehicle_reverse = 1;
    }
    if($feature_name_session[$k] == "io_trip")
    {
                    $flag_io_trip = 1;
    }

    if($feature_name_session[$k] == "flowRate")
    {
                    $flag_flowRate = 1;
    }
    if($feature_name_session[$k] == "dispensing1")
    {
                    $flag_dispensing1 = 1;
    }
    if($feature_name_session[$k] == "dispensing2")
    {
                    $flag_dispensing2 = 1;
    }
    if($feature_name_session[$k] == "dispensing3")
    {
                    $flag_dispensing3 = 1;
    }
}
$download_path="src/php/gps_report/".$account_id."/download";
$master_download_path="src/php/gps_report/".$account_id."/master";        
$nbsp='&nbsp';
$contetnbsp="&nbsp;";
$js_function_name = "report_common_prev";    // FUNCTION NAME
//$js_function_name_station = "report_common_prev_station1";    // FUNCTION NAME
$js_function_name_station = "report_common_prev_station";    // FUNCTION NAME
$js_function_name_mining = "report_common_prev_mining"; 	
$js_function_name_person = "report_common_prev_person"; 
$js_function_name_jquery = "report_common_prev_jquery";    // FUNCTION NAME
//other action append
$js_function_name_switcher="report_show_file_switcher";
 echo'  
            <li><a href="report.htm" >Main-Report</a></li>
            
        ';
        echo'<li class="divider"></li>';
        echo'<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Regular</a>
                    <ul class="dropdown-menu scrollable-menu" style="width:200px" >
                    ';
                    if($person_user_type==1)
                    {
                    echo'
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_visitdetail.htm\',\'Visit%20Report\',\''.$js_function_name.'\');><i class="fa fa-coffee" aria-hidden="true"></i>'.$nbsp.'Visit Detail</a></li>
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_inactive_data.htm\',\'Inactive%20Data%20Report\',\''.$js_function_name.'\');><i class="fa fa-meh-o" aria-hidden="true"></i>'.$nbsp.'Inactive Data</a></li>
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_nogps_data.htm\',\'No%20Gps%20Data%20Report\',\''.$js_function_name.'\');><i class="fa fa-thumb-tack" aria-hidden="true"></i>'.$nbsp.'No Gps Data</a></li>
                    ';
                    }
                    if($person_user_type==1 || $fleet_user_type==1 || $mining_user_type==1 || $courier_user_type==1 || $school_user_type ==1 || $pos_user_type==1) //for all user except person
                    {
                    echo'
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_vehicle.htm\',\'Vehicle%20Report\',\''.$js_function_name.'\');><i class="fa fa-file-text" aria-hidden="true"></i>'.$nbsp.$report_type.' Report</a></li>
                        ';
                        if($person_user_type==1)
			{
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_hourly_distance.htm\',\'Hourly%20Report\',\''.$js_function_name.'\');><i class="fa fa-clock-o" aria-hidden="true"></i>'.$nbsp.'Hourly Report</a></li>
                        ';
                        }
                        if($flag_station==1)
                        {
                            echo'<li><a href=\'rhome.htm\' target=\'_blank\'><i class="fa fa-map-o" aria-hidden="true"></i>'.$nbsp.'Route On Map</a></li>                        
                            <li><a href=\'customer_plant_home.htm\' target=\'_blank\'><i class="fa fa-link" aria-hidden="true"></i>'.$nbsp.'Customer/Plant On Map</a></li>
                        ';
                        }
                        
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/action_report_device.htm\',\'Device%20Report\',\''.$js_function_name.'\');><i class="fa fa-wrench" aria-hidden="true"></i>'.$nbsp.'Device Report</a></li>
                        ';
                        if(($consignment_info==1) && (!$person_user_type))
			{
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_consignment_info.htm\',\'Consignment%20Report\',\''.$js_function_name.'\'); ><i class=\'fa fa-shopping-cart fa-1x\' aria-hidden=\'true\'></i>'.$nbsp.'Consignment Report</a></li>
                            ';
                        }
                        if($account_id==715)
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/klp_report_prev.htm\',\'Klp%20Input%20Report\',\'klp_report_prev\');><i class="fa fa-share" aria-hidden="true"></i>'.$nbsp.'Klp Input Report</a></li>
                           ';
                        }
                        if($flag_distance)
                        {
                            echo'<li><a href=javascript:javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_distance.htm\',\'Distance%20Report\',\''.$js_function_name.'\');><i class="fa fa-map-signs" aria-hidden="true"></i>'.$nbsp.'Distance Report</a></li>
                            ';
                        }
                        if($flag_mdistance)
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_daily_distance.htm\',\'Daily%20Distance%20Report\',\''.$js_function_name.'\');><i class="fa fa-check-circle-o" aria-hidden="true"></i>'.$nbsp.'Daily Distance</a></li>                        
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_monthly_distance.htm\',\'Monthly%20Distance%20Report\',\''.$js_function_name.'\');><i class="fa fa-calendar" aria-hidden="true"></i>'.$nbsp.'Monthly Distance</a></li>
                           ';
                        }
                        if($flag_fuel)
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_fuel.htm\',\'Fuel%20Report\',\''.$js_function_name.'\');><i class="fa fa-tachometer" aria-hidden="true"></i>'.$nbsp.'Fuel Report</a></li>
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_fuel_halt.htm\',\'Fuel%20Halt%20Report\',\''.$js_function_name.'\');><i class="fa fa-info" aria-hidden="true"></i>'.$nbsp.'Fuel Halt Report</a></li>
                            ';
                        }
                        if($flag_summary)
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_summary.htm\',\'Summary%20Report\',\''.$js_function_name.'\');><i class="fa fa-list-ol" aria-hidden="true"></i>'.$nbsp.'Summary Report</a></li>
                            ';
                        }
                        if($flag_engine)
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_engine_runhr.htm\',\'Engine%20RunHr%20Report\',\''.$js_function_name.'\');><i class="fa fa-tachometer" aria-hidden="true"></i>'.$nbsp.'Engine RunHr Report</a></li>
                            ';
                        }
                        if($flag_io_trip)
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_io_trip.htm\',\'IO%20Trip%20Report\',\''.$js_function_name.'\');><i class=\'fa fa-check-square-o  fa-1x\' aria-hidden=\'true\'></i>'.$nbsp.'IO Trip Report</a></li>
                            ';
                        }
                        if($flag_ac)
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_ac_runhr.htm\',\'AC%20RunHr%20Report\',\''.$js_function_name.'\');><i class="fa fa-list-alt" aria-hidden="true"></i>'.$nbsp.'AC RunHr Report</a></li>
                                ';
                        }
                        if($door_open_info=="1")
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_door_open.htm\',\'Door%20Open\',\''.$js_function_name.'\');><i class="fa fa-key" aria-hidden="true"></i>'.$nbsp.'Door Open</a></li>
                                ';
                        }
                        if($fuel_lead_info=="1")
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_fule_lead.htm\',\'Door%20Open\',\''.$js_function_name.'\');><i class="fa fa-info-circle" aria-hidden="true"></i>'.$nbsp.'Fuel Lead</a></li>
                                ';
                        }
                    }
                    if($mining_user_type==1)
                    {
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_trip.htm\',\'Trip%20Report\',\''.$js_function_name_mining.'\');><i class=\'fa fa-globe fa-1x\' aria-hidden=\'true\'></i>'.$nbsp.'Trip Report</a></li>
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_trip_new.htm\',\'Trip%20Report%20New\',\''.$js_function_name_mining.'\');><i class="fa fa-star" aria-hidden="true"></i>'.$nbsp.'Trip Report New</a></li>
                        <li><a javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_trip_vehicle_movement.htm\',\'Vehicle%20Movement%20Report\',\''.$js_function_name_mining.'\');><i class="fa fa-road" aria-hidden="true"></i>'.$nbsp.'Trip Vehicle Movement</a></li>                        
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_trip_summary.htm\',\'Trip%20Summary%20Report\',\''.$js_function_name_mining.'\');><i class="fa fa-outdent" aria-hidden="true"></i>'.$nbsp.'Trip Summary Report</a></li>
                     ';
                    }
                    if(!$person_user_type)
                    {
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_halt.htm\',\'Halt%20Report\',\''.$js_function_name.'\');><i class="fa fa-stop-circle" aria-hidden="true"></i>'.$nbsp.'Halt Report</a></li>
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_track_interval.htm\',\'Track%20Interval%20Report\',\''.$js_function_name.'\');><i class="fa fa-spinner" aria-hidden="true"></i>'.$nbsp.'Track Interval Report</a></li>
                    ';
                    }
                    if($flag_station)
                    {
                        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
                        { 
                            if($_SERVER['HTTP_X_FORWARDED_FOR']=="172.26.48.195")
                            {
                                echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_station_halt_1.htm\',\'Supply%20Timing%20Report\',\''.$js_function_name.'\');><i class="fa fa-retweet" aria-hidden="true"></i>'.$nbsp.'Supply Timing Report 1</a></li>
                              ';
                            }
                        }
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/action_report_station_list.htm\',\'Station%20Report\',\''.$js_function_name.'\');> <i class=\'fa fa-stop fa-1x\' aria-hidden=\'true\'></i>'.$nbsp.'Station Record</a></li>
                        ';
                    }
                    if($flag_travel)
                    {
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_travel.htm\',\'Travel%20Report\',\''.$js_function_name.'\');><i class="fa fa-suitcase" aria-hidden="true"></i>'.$nbsp.'Travel Report</a></li>
                        ';
                        if($account_id=="238" || $account_id == "615")
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_travel_summary.htm\',\'Travel%20Summary\',\''.$js_function_name.'\');><i class="fa fa-sort-alpha-asc" aria-hidden="true"></i>'.$nbsp.'Travel Summary</a></li>
                                ';
                        }
                    }
                    if($flag_load_cell )
                    {
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_load_cell.htm\',\'Load%20Cell%20Report\',\''.$js_function_name.'\');><i class="fa fa-battery-half" aria-hidden="true"></i>'.$nbsp.'Load Cell Report</a></li>
                            ';
                    }                            
                    echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_version.htm\',\'Version%20Report\',\''.$js_function_name.'\');><i class="fa fa-level-up" aria-hidden="true"></i>'.$nbsp.'Version Report</a></li>
                        ';
                    if($person_user_type!=1 || $account_id==1)
                    {
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_battery_voltage.htm\',\'Battery%20Voltage%20Report\',\''.$js_function_name.'\');><i class="fa fa-battery-full" aria-hidden="true"></i>'.$nbsp.'Battery Voltage Report</a></li>
                            ';
                    }
                    if($person_user_type!=1)
                    {
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_temperature.htm\',\'Temperature%20Report\',\''.$js_function_name.'\');><i class="fa fa-sun-o" aria-hidden="true"></i>'.$nbsp.'Temperature Report</a></li>
                        ';
                        if($flag_flowRate == 1)
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_flowRate.htm\',\'FlowRate%20Report\',\''.$js_function_name.'\');><i class="fa fa-paper-plane-o" aria-hidden="true"></i>'.$nbsp.'FlowRate Report</a></li>
                                ';
                        }
                        if($flag_dispensing1 == 1 || $flag_dispensing1 == 2 || $flag_dispensing1 == 3 )
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_dispensing.htm\',\'Dispensing%20Report\',\''.$js_function_name.'\');><i class="fa fa-outdent" aria-hidden="true"></i>'.$nbsp.'Dispensing Report</a></li>
                           ';
                        }
                    }
                    echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_nearby.htm\',\'Near%20By%20Report\',\''.$js_function_name.'\'); ><i class="fa fa-map-signs" aria-hidden="true"></i>'.$nbsp.'Near By Report</a></li>
                    ';
                    if($flag_visit == 1)
                    {
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_schedule_assignment.htm\',\'Schedule%20Assignment%20Report\',\''.$js_function_name.'\');><i class="fa fa-check-square" aria-hidden="true"></i>'.$nbsp.'Schedule Assignment</a></li>                        
                        <li><a href=javascript:schedule_location_prev(\'src/php/action_report_schedule_location.htm\',\'Schedule%20Location%20Report\',\'schedule_location_prev\');><i class="fa fa-external-link-square" aria-hidden="true"></i>'.$nbsp.'Schedule Location</a></li>
                            ';
                    }
                    if($flag_sector)
                    {
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_sector_halt.htm\',\'Sector%20Halt\',\''.$js_function_name.'\'); ><i class="fa fa-random" aria-hidden="true"></i>'.$nbsp.'Sector Halt</a></li>
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_sector_change.htm\',\'Sector%20Change\',\''.$js_function_name.'\');><i class="fa fa-random" aria-hidden="true"></i>'.$nbsp.'Sector Change</a></li>
                    ';
                    }
                    echo'
                 </ul>
            </li>
            <li class="divider"></li>
            ';
            if($account_id=="696")
            {
                echo'
                <li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Hero Moto Report</a>
                        <ul class="dropdown-menu" style="width:200px">

                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_moto_dispatch_master.htm\',\'Dispatch%20Master%20Report\',\'report_moto_master_prev\');>'.$nbsp.'Master Report</a></li>
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_moto_monthly_comparison.htm\',\'Previous%20To%20Current%20Month%20Comparison\',\'report_moto_monthly_prev\');>'.$nbsp.'Monthly Comparison</a></li>
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_moto_trip_performance.htm\',\'Trip%20Performance\',\'report_moto_trip_performance_prev\');>'.$contetnbsp.'Trip Performance</a></li>

                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/action_report_moto_stop_on_trip.htm\',\'Vehicle%20Stopped%20On%20Trip\',\'report_moto_prev\');>'.$nbsp.'Stop On Trip</a></li>
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/action_report_moto_stop_trip.htm\',\'On%20Trip\',\'report_moto_prev\');>'.$nbsp.'Vehicle On Trip</a></li>
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/action_report_moto_vehicle_violation.htm\',\'Vehicle%20Violation\',\'report_moto_prev\');>'.$nbsp.'Vehicle Violation</a></li>

                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_moto_load_planning.htm\',\'Vehicle%20Load%20Planning\',\''.$js_function_name.'\');>'.$nbsp.'Vehicle Load Planning</a></li>
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/action_report_carrier_performance_rating.htm\',\'Carrier%20Performance%20On%20Rating\',\'report_moto_prev\');>'.$nbsp.'Carrier Performance Rating</a></li>
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/action_report_moto_location_data.htm\',\'Location%20Data\',\'report_moto_prev\');>'.$nbsp.'Location Data</a></li>

                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/action_report_moto_viewing_trip_report.htm\',\'Viewing%20Trip%20Report\',\'report_moto_prev\');>'.$nbsp.'Viewing Trip Report</a></li>
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_moto_auto_route_manager.htm\',\'Auto%20Route%20Manage\',\'report_show_auto_manager\');>'.$nbsp.'Auto Route Manager</a></li>
                            <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_moto_geocode_management.htm\',\'Viewing%20Trip%20Report\',\''.$js_function_name.'\');>'.$nbsp.'Geocode Management</a></li>

                        </ul>
                </li>

            ';
            echo'<li class="divider"></li>';
        }
        if($flag_station)
        {
            echo'<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Download Data File</a>
                    <ul class="dropdown-menu" style="width:200px"> 
                    ';
                    if(count($contents)>0){
                        for($i=0;$i<sizeof($contents);$i++) 
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_download_files.htm\',\''.$contents[$i].'\',\'report_show_download_file\');><i class="fa fa-download" aria-hidden="true"></i>'.$contents[$i].'</a></li>
                         ';
                        }   
                    }
                    echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_master_download_files.htm\',\'master\',\'Vreport_show_download_file\');><i class="fa fa-download" aria-hidden="true"></i>'.$nbsp.'Download Master File</a></li>
                    </ul>
                </li>
            ';
            echo'<li class="divider"></li>';
            echo'<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Upload Data File</a>
                    <ul class="dropdown-menu" style="width:200px"> 
                        <li><a href=javascript:report_show_file_switcher_upload_file(\'report.htm\',\'src/php/report_upload_master_file.htm\',\'Master%20Report%20File\',\'master\',\'report_upload_file_1\');><i class="fa fa-upload" aria-hidden="true"></i>'.$nbsp.'Master Report File</a></li>
                        <li><a href=javascript:report_show_file_switcher_upload_file(\'report.htm\',\'src/php/report_upload_master_file.htm\',\'Get%20Report%20File\',\'get_report\',\'report_upload_file_1\'); ><i class="fa fa-upload" aria-hidden="true"></i>'.$nbsp.'Get Report File</a></li>
                    </ul>
                </li>
            ';
            echo'<li class="divider"></li>';
        }
        echo'<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Alert</a>
                    <ul class="dropdown-menu" style="width:200px">
                        ';
                        if($flag_vehicle_reverse)
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/alert_vehicle_reverse.htm\',\'Vehicle%20Reverse%20Alert\',\''.$js_function_name.'\');><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>'.$nbsp.'Vehicle Reverse</a></li>
                        ';
                        }   
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/alert_area_violation.htm\',\'Geofence%20Violation%20Report\',\''.$js_function_name.'\'); ><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>'.$nbsp.'Geofence violation</a></li>
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/alert_monthly_distance_geofence.htm\',\'Monthly%20Geofence%20Report\',\''.$js_function_name.'\');><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>'.$nbsp.'Monthly Geofence Report</a></li>                        
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/alert_polyline_route_violation.htm\',\'Route%20Violation%20Report\',\''.$js_function_name_jquery.'\'); ><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>'.$nbsp.'Route violation</a></li>
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/polyline_violation_history.htm\',\'Polyline%20Violation%20History\',\''.$js_function_name_jquery.'\'); ><i class="fa fa-info-circle" aria-hidden="true"></i>'.$nbsp.'Polyline violation History</a></li>
                        ';
                        if($fleet_user_type==1 || $mining_user_type==1 || $courier_user_type==1 || $school_user_type ==1 || $pos_user_type==1) // for all user except person
                        {
                            echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/alert_speed_violation.htm\',\'Speed%20Violation%20Report\',\''.$js_function_name.'\'); ><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>'.$nbsp.'Speed violation</a></li>
                        ';
                        }
                        echo'<li><a href=\'src/php/weather_forecasting_drive/index.htm\' target=\'_blank\' ><i class="fa fa-info-circle" aria-hidden="true"></i>'.$nbsp.'Route Weather Forecasting</a></li>
                       
                    </ul>
            </li>
        ';
        echo'<li class="divider"></li>';
        echo'<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">DataLog</a>
                    <ul class="dropdown-menu" style="width:200px">
                       <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/datalog_today_records.htm\',\'Today%20Datalog\',\''.$js_function_name.'\');><i class="fa fa-calendar-check-o" aria-hidden="true"></i>'.$nbsp.'Today Datalog</a></li>
                       <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/datalog_between_dates.htm\',\'Date%20Wise%20Report\',\''.$js_function_name.'\');><i class="fa fa-calendar-check-o" aria-hidden="true"></i>'.$nbsp.'Between dates</a></li>                       
                       ';
                       if($school_user_type==1)
                       {
                        echo'<li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_bus.htm\',\'Bus%20Report\',\''.$js_function_name.'\');><i class="fa fa-calendar-check-o" aria-hidden="true"></i>'.$nbsp.'Bus Report</a></li>
                        <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_student_prev.htm\',\'Student%20Report\',\''.$js_function_name.'\');><i class="fa fa-calendar-check-o" aria-hidden="true"></i>'.$nbsp.'Student Report</a></li>
                       ';
                       }
                       echo'
                    </ul>
            </li>
        ';
        echo'<li class="divider"></li>';
        echo'<li class="dropdown dropdown-submenu"><a href="#" class="dropdown-toggle" data-toggle="dropdown">DataGap</a>
                    <ul class="dropdown-menu" style="width:200px">
                       <li><a href=javascript:'.$js_function_name_switcher.'(\'report.htm\',\'src/php/report_datagap.htm\',\'No%20Data%20Report/%20%20No%20GPS%20Report\',\''.$js_function_name.'\');><i class="fa fa-chain-broken" aria-hidden="true"></i>'.$nbsp.'NoData/&nbsp;NoGPS</a></li>                      
                    </ul>
            </li>
         
        ';
?>