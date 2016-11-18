<?php

include_once('Hierarchy.php');
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('coreDb.php');

$DEBUG = 0;
$root = $_SESSION['root'];
$post_action_type = $_POST['action_type'];
$account_id1 = $_POST['account_id'];
$device_str = $_POST['vehicleserial'];
$apk_str_tmp = trim($_POST['apk_version']);
//echo "<br>AC=".$account_id1." ,device_str=".$device_str." ,apk_str_tmp=".$apk_str_tmp;
    
//$post_account_id = $_POST['account_id'];
$flag = 0;
if($post_action_type == "add") {
    // Date of account creation
    date_default_timezone_set('Asia/Calcutta'); // add conversion based on the time zone of user
    $datetime = date("Y-m-d H:i:s");
    $flag = 0;
    $result_response = 1;

    $apk_str = explode('$',$apk_str_tmp);
    
    $apk_version1 = $apk_str[0];
    $apk_url = $apk_str[1];
    //echo "<br>devicestr=".$device_str;
    //exit();
    $vserial = explode(':', $device_str);
    $vsize = count($vserial);
    $status = 1;    
    $GCM_registrationIds = array();
    
    for($i = 0;$i<$vsize;$i++) {
        //echo "<br>IMEINO=".$vserial[$i]." ,account_id=".$account_id1." ,apk_version=".$apk_version1;
        $count = getApk_Assignment_detail($account_id1, $vserial[$i], $DbConnection);
        if($count > 0) {
            $res = updateApk_Assignment_detail($apk_version1, $account_id1, $vserial[$i], $DbConnection);
        } else {
            $res = insertApk_Assignment_detail($vserial[$i], $account_id1, $apk_version1, $datetime, $status, $DbConnection);
        }
        
        $gcm_id_tmp = getGCM_Id_Detail($vserial[$i], $DbConnection);
        //echo "<br>gcm_id=".$gcm_id_tmp; //TEST
        if($gcm_id_tmp!='') {
            $GCM_registrationIds[] = $gcm_id_tmp;
        }
                
        $flag = 1;
    }
    

    //echo "<br>Size=".sizeof($GCM_registrationIds);
    if(sizeof($GCM_registrationIds) > 0) {
        //######## START: PUSH NOTIFICATION TO ANDROID DEVICE USING GCM_ID ##################
        //############################################################################
        // API access key from Google API's Console
        define( 'API_ACCESS_KEY', 'AIzaSyA7vdbw8zXvmDrrF-6VQOtL2o2RRveaQPQ' );
        //$GCM_registrationIds = array( $_GET['id'] );

        //prep the bundle
        //$title = "Version:".$apk_version;

        /*$msg = array
        (
            'message' 	=> $msg,
            'title'		=> $title,
            'subtitle'	=> $apk_version,
            'tickerText'	=> 'Please update to new person APK',
            'vibrate'	=> 1,
            'sound'		=> 1,
            'largeIcon'	=> 'large_icon',
            'smallIcon'	=> 'small_icon'
        );*/

        $msg = array
        (            
            'version'	=> $apk_version1,
            'apk_url'	=> $apk_url       
        );    

        $fields = array
        (
            'registration_ids' 	=> $GCM_registrationIds,
            'data'              => $msg
        );

        $headers = array
        (
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );

        //echo $result;    
        //######## END: PUSH NOTIFICATION #############################################
    }
}

if ($flag==1) {
    $message = "<br>
    <font color=\"Green\" size=4>
            <strong>
                    APK Assigned and Request has been sent to the selected Person
            </strong>
    </font>";
}
else if($flag==0) {
    $message = "<br>
    <font color=\"Red\" size=4>
            <strong>
                    Unable to Assign APK!
            </strong>
    </font>";
}

echo "<center>".$message."</center>";
//echo"result_status=".$result_status."<br>";

echo'<center>
        <a href="javascript:show_option(\'manage\',\'apk\');" class="back_css">
            <b>
                &nbsp;<b>Back
            </b>
        </a>
    </center>';
?>
        