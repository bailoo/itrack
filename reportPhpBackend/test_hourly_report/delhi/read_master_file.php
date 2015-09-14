<?php

function read_expected_time($account_id, $shift) {
    global $DEBUG_OFFLINE;
    global $DEBUG_ONLINE;
    global $expected_customer_csv;
    global $expected_time_csv;

    global $expected_route_no;
    global $expected_plant;
    global $expected_plant_intime;
    global $expected_plant_outtime;

    if ($DEBUG_OFFLINE) {
        $dir="C:\\xampp/htdocs/itrack/beta/src/php/gps_report/".$account_id."/master";
    }else if($DEBUG_ONLINE){
        $dir = "/var/www/html/vts/beta/src/php/gps_report/" . $account_id . "/master";
    } else {
        $dir = "/var/www/html/vts/beta/src/php/gps_report/" . $account_id . "/master";
    }
    //echo "\nDir=".$dir;
    $dh = opendir($dir);
    while (($file = readdir($dh)) !== false) {
        //echo "<A HREF=\"$file\">$file</A><BR>\n";
        //echo "\nFileExpected=".$file;
        $file_tmp = explode("#", $file);
        $file_ext = explode(".", $file_tmp[2]);
        if ($file_ext[0] != "") {
            //echo "<br>file_ext=".$file_ext[0];
            if ($file_ext[0] == "1") {
                $path = $dir . "/" . $file;
                //echo "\nFileExpected=".$file;
                $row = 0;
                if (($handle = fopen($path, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $row++;
                        $num = count($data);
                        //echo "\nNumSize=".$num;
                        if (($num < 3) || ($row == 1)) {
                            continue;
                        }
                        //echo "<p> $num fields in line $row: <br /></p>\n";							
                        for ($c = 0; $c < $num; $c++) {
                            //echo $data[$c] . "<br />\n";
                            if ($c == 0) {
                                $shift_csv = $data[$c];
                            } else if ($c == 1) {
                                $point_csv = $data[$c];
                            } else if ($c == 2) {

$datec = new DateTime( $data[$c] );
//echo $date->format( 'H:i' );
                                $timing_csv =$datec->format('H:i');
                            }
                        }
                        //echo "\nShift_csv=".$shift_csv.", shift=".$shift;
                        if (trim($shift_csv) == trim($shift)) {
                            $expected_customer_csv[] = $point_csv;

$datec = new DateTime( $timing_csv );
//echo $date->format( 'H:i:s' );

                            $expected_time_csv[] = $datec->format( 'H:i:s' );
                        }
                    }
                    fclose($handle);
                }
            }
            if ($file_ext[0] == "2") {
                $path = $dir . "/" . $file;
                //echo "\nFileExpected=".$file;
                $row = 0;
                if (($handle = fopen($path, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $row++;
                        $num = count($data);
                        //echo "\nNumSize=".$num;
                        if (($num < 5) || ($row == 1)) {
                            continue;
                        }
                        //echo "<p> $num fields in line $row: <br /></p>\n";							
                        for ($c = 0; $c < $num; $c++) {
                            //echo $data[$c] . "<br />\n";
                            //Route No.,Plant,Schedule IN time,Schedule OUT time,Shift

                            if ($c == 0) {
                                $expected_route_no_tmp = $data[$c];
                            } else if ($c == 1) {
                                $expected_plant_tmp = $data[$c];
                            } else if ($c == 2) {
                                $expected_plant_intime_tmp = $data[$c];
                            } else if ($c == 3) {
                                $expected_plant_outtime_tmp = $data[$c];
                            } else if ($c == 4) {
                                $expected_plant_shift_tmp = $data[$c];
                            }
                        }
                        //echo "\nShift_csv=".$shift_csv.", shift=".$shift;
                        if (trim($expected_plant_shift_tmp) == trim($shift)) {
                            $expected_route_no[] = $expected_route_no_tmp;
                            $expected_plant[] = $expected_plant_tmp;
                            $expected_plant_intime[] = $expected_plant_intime_tmp;
                            $expected_plant_outtime[] = $expected_plant_outtime_tmp;
                        }
                    }
                    fclose($handle);
                }
            }
        }
    }
    //echo "\nExpectedCust1=".sizeof($expected_customer_csv)." ,ExpectedTime1=".sizeof($expected_time_csv);
}

function read_transporter($account_id, $shift) {
    global $DEBUG_OFFLINE;
    global $DEBUG_ONLINE;
    global $transporter_m;
    global $vehicle_m;

    if ($DEBUG_OFFLINE) {
        $dir="C:\\xampp/htdocs/itrack/beta/src/php/gps_report/".$account_id."/master";
    } else if($DEBUG_ONLINE) { 
        $dir = "/var/www/html/vts/beta/src/php/gps_report/" . $account_id . "/master";
    } else {
        $dir = "/var/www/html/vts/beta/src/php/gps_report/" . $account_id . "/master";
    }
    $dh = opendir($dir);
    while (($file = readdir($dh)) !== false) {
        //echo "<A HREF=\"$file\">$file</A><BR>\n";
        //echo "\nFileExpected=".$file;
        $file_tmp = explode("#", $file);
        $file_ext = explode(".", $file_tmp[2]);
        if ($file_ext[0] != "") {
            //echo "<br>file_ext=".$file_ext[0];
            if ($file_ext[0] == "6") { //######## TRANSPORTER FILE
                $path = $dir . "/" . $file;

                $row = 1;
                if (($handle = fopen($path, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $num = count($data);
                        //echo "<p> $num fields in line $row: <br /></p>\n";
                        $row++;
                        for ($c = 0; $c < $num; $c++) {
                            //echo "\ndata=".$data[$c] . "<br />\n";
                            if ($c == 0) {
                                $vehicle_m[] = $data[$c];
                            } else if ($c == 1) {
                                $transporter_m[] = $data[$c];
                            }
                        }
                    }
                    fclose($handle);
                }
            } //IF FORMAT 6
        }
    }

    echo "\nvehicle_m=" . sizeof($vehicle_m) . " ,transporter_m=" . sizeof($transporter_m);
}

?>
