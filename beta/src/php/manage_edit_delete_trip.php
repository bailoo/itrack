<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php'); 
	include_once('coreDb.php');	
	echo "edit##"; 
	$account_id_local=$_POST['common_id'];
	echo "<input type='hidden' id='account_id_local' value=".$account_id_local.">";
	$data = getVts_TripDetail($account_id_local, $DbConnection);
?>
	<input type="hidden" name="prev_landmark_point" id="prev_landmark_point"> 
        <center>
            <h3>Running Trips detail</h3><br>
	<table border="1" class="manage_interface" cellspacing="2" cellpadding="2">            
            
            <?php
                if(sizeof($data)>0) {
                    echo "<tr><td style='background-color:lightgrey;'><strong>Source Location</strong></td><td style='background-color:lightgrey;'><strong>Destination Location</strong></td><td style='background-color:lightgrey;'><strong>Trip StartDate</strong></td><td><font color=red><strong>Close</strong></font></td></tr>";

                    for($i=0;$i<sizeof($data);$i++) {
                        echo "<tr>";
                        echo "<td>".$data[$i]['source_name']."</td><td>".$data[$i]['destination_name']."</td><td>".$data[$i]['trip_start_date']."</td><td><input type=checkbox name=\"trip_id[]\" value='".$data[$i]['trip_id']."'></td>";                        
                        echo"</tr>";
                    }
                }
            ?>
	</table>
        <br>
        <?php
        if(sizeof($data)>0) {
            echo '<input type="button" value="Close Trips" id="enter_button" onclick="javascript:return action_manage_vts_trip(\'close\')"/>&nbsp;';
        } else {
            echo '<br><font color="red"><strong>Sorry! No Trip detail is found!</strong></font>';
        }
        ?>
        </center>
<?php
	include_once('manage_loading_message.php');
?>