<?php

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('coreDb.php');
$DEBUG = 0;

$action_type1 = $_POST['action_type'];
$local_account_id = $_POST['account_id_local'];
$vehicle_ids_tmp = $_POST['vehicle_ids'];

$vehicle_ids = explode(",", $vehicle_ids_tmp);
$vehicle_size = sizeof($vehicle_ids);
//echo "\nVsize=".$vehicle_size;
//echo "ActionType=".$action_type1;
if ($action_type1 == "add") {
    $landmark_name1 = trim($_POST['landmark_name1']);
    $landmark_point1 = $_POST['landmark_point1'];
    $landmark_name2 = trim($_POST['landmark_name2']);
    $landmark_point2 = $_POST['landmark_point2'];    
    $trip_startdate = $_POST['trip_startdate'];

    $existing_trips = getAlreadyExistingTrips($vehicle_size,$vehicle_ids,$local_account_id,$landmark_name1,$landmark_point1,$landmark_name2,$landmark_point2,$DbConnection);
    
    if(sizeof($existing_trips)>0) {        
        echo "<div align=center><h3>Following trips are already added <h3><br><br>";
        
        for($i=0;$i<sizeof($existing_trips);$i++) {
            $existing_trips_str = explode(",",$existing_trips[$i]);
            echo "<font color=blue>[".$existing_trips_str[0]."]</font>::&nbsp;<strong>".$existing_trips_str[1]."->".$existing_trips_str[2]."</strong><br>";
        }
        echo "</div>";
    }
        
    $result = insertVtsTrip($vehicle_size,$vehicle_ids,$local_account_id,$landmark_name1,$landmark_point1,$landmark_name2,$landmark_point2,$trip_startdate,$DbConnection);
    if ($result) {
        $flag = 1;
        $action_perform = "Added";
    }
} else if ($action_type1 == "close") {
    $trip_ids = trim($_POST['trip_ids']);
    $result = closeTrips($trip_ids,$DbConnection);

    if ($result) {
        $flag = 1;
        $action_perform = "Closed";
    }
}

//echo $query;
if ($flag == 1 || $flag == 2) {
    $msg = "Trip " . $action_perform . " Successfully";
    $msg_color = "green";
} else {
    $msg = "Sorry! Unable to process request.";
    $msg_color = "red";
}
echo "<center><br><b><FONT color=\"" . $msg_color . "\" size=\"2\"><b>" . $msg . "</b><br></strong></font></center>";
echo'<center><a href="javascript:show_option(\'manage\',\'trip\');" class="back_css">&nbsp;<b>Back</b></a></center>';
?>
        
