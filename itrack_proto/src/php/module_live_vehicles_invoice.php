<?php 

	include_once('util_php_mysql_connectivity.php');	
	include_once('coreDb.php');
        include_once("../../../phpApi/Cassandra/Cassandra.php");     //##### INCLUDE CASSANDRA API
        include_once("../../../phpApi/libLog.php");     //##### INCLUDE CASSANDRA API*/
	
        $o_cassandra = new Cassandra();	
        $o_cassandra->connect($s_server_host, $s_server_username, $s_server_password, $s_server_keyspace, $i_server_port);
        global $o_cassandra;
        //print_r($o_cassandra);
        $DEBUG = 0;    
	$display_type = $_POST['display_type1']; 
	$tot_vehicle_live=$_POST['tot_vehicle_live'];
	

	//date_default_timezone_set('Asia/Calcutta');
	$current_time = date('Y/m/d H:i:s');
	$today_date=explode(" ",$current_time);
	$today_date1=$today_date[0];
	$today_date2 = str_replace("/","-",$today_date1);
	
	echo "live_show_vehicle_open##";	
	//echo'<INPUT TYPE="checkbox"  name="live_vehicles[]" VALUE="aaa" checked >';
	echo"<table border=0 cellspacing=0 cellpadding=0 class='module_left_menu'>";
	if($display_type=="single")
	{ 		
		show_all_vehicle_live($tot_vehicle_live);
	} 
	 
	echo"</table>";
        $o_cassandra->close();

  function show_all_vehicle_live($tot_vehicle_live)
  {	
		$tot_vehicle=explode(":",$tot_vehicle_live);
		global $today_date2;
		global $vcolor1;
		global $vcolor2;
		global $vcolor3;
		global $DbConnection;
                global $o_cassandra;
		$vehicle_name_arr=array();
		$imei_arr=array();
		$vehicle_color=array();
		
		foreach($tot_vehicle as $vehicle_name1)
		{
			//echo $vehicle_name1;
			$vehicle_name_info=explode("/",$vehicle_name1);
			$vehicle_name=$vehicle_name_info[0];//'HR28G3286';//
			$dispatch_time=$vehicle_name_info[1];
			//echo $dispatch_time;
			$target_time=$vehicle_name_info[2];
			$plant_number=$vehicle_name_info[3];
			/*
			$query_vehicle = "SELECT vehicle_assignment.device_imei_no, vehicle.vehicle_id FROM vehicle_assignment, vehicle WHERE  vehicle.vehicle_name = '$vehicle_name' AND vehicle.status =1 AND vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.status=1";
			//echo $query_vehicle."<br>";
			$resultvehicle=mysql_query($query_vehicle,$DbConnection);
                        */
                        $resultvehicle=getVehicleAndImeiFromName($vehicle_name,$DbConnection);
			//if(mysql_num_rows($resultvehicle) >0)
                        //print_r($resultvehicle);
                        if(count($resultvehicle) >0)
			{		
				//echo $today_date2;
                                //$rowv=mysql_fetch_object($resultvehicle);
				//$vehicle_id=$rowv->vehicle_id;
				//$vehicle_imei=$rowv->device_imei_no;
                                foreach($resultvehicle as $rowv)
                                {
                                    $vehicle_id=$rowv['vehicle_id'];
                                    $vehicle_imei=$rowv['device_imei_no'];
                                    //echo"VIEM=". $vehicle_imei."<br>";
                                    $logResult=hasImeiLogged($o_cassandra, $vehicle_imei, $today_date2);
                                    //$xml_current = "../../../xml_vts/xml_data/".$today_date2."/".$vehicle_imei.".xml";
                                    //echo $xml_current;
                                    //if (file_exists($xml_current))
                                    if($logResult!='')
                            
                                    {
                                       // $st_results = getLastSeen($o_cassandra,$vehicle_imei);
                                       // print_r($st_results);
                                     $color="green";
                                     //$vehicle_name_arr[$color][] =$vehicle_name; 
                                     //$imei_arr[$color][$vehicle_name]=$vehicle_imei;
                                            echo '
                                      <tr>
                                            <td align="left">
                                            <span id="vcheckbox">
                                                    <INPUT TYPE="checkbox" name="live_vehicles[]" VALUE="'.$vehicle_imei.'" checked >
                                                    <input type="hidden"  name="dispatch_time[]" value="'.$dispatch_time.'">
                                                    <input type="hidden"  name="target_time[]" value="'.$target_time.'">
                                                    <input type="hidden"  name="plant_number[]" value="'.$plant_number.'">
                                            </span>          

                                            </td>
                                            <td>
                                              <font color="'.$color.'">'.$vehicle_name.'</font>
                                            </td>
                                      </tr>
                                      ';
                                    }
                                    else
                                    {
                                            $color="gray";				      					  
                                            //$vehicle_name_arr[$color][] =$vehicle_name; 
                                            //$imei_arr[$color][$vehicle_name]=$vehicle_imei;
                                            echo '
                                      <tr>
                                            <td align="left">
                                            <span id="vcheckbox">
                                                    <INPUT TYPE="checkbox"  name="live_vehicles[]" VALUE="'.$vehicle_imei.'" checked >
                                                    <input type="hidden" name="dispatch_time[]" value="'.$dispatch_time.'">
                                                    <input type="hidden" name="target_time[]" value="'.$target_time.'">
                                                    <input type="hidden"  name="plant_number[]" value="'.$plant_number.'">
                                            </span>          

                                            </td>
                                            <td>
                                              <font color="'.$color.'">'.$vehicle_name.'</font>
                                            </td>
                                      </tr>
                                      ';
                                    }
                             }
			}
		}
		/*$m=0;
		foreach($vehicle_name_arr as $vehicle)
        {
		  if($m==0)
		  {
			//echo "<br>ss=".$s;
			$vchk = "vcheckbox";
			$vrad = "vradio";
		  }
		  else
		  {
			$vchk = "vcheckbox".$m;
			$vrad = "vradio".$m;          
		  }
		  echo '
          <tr>
            <td align="left">
			<span id="'.$vchk.'"><INPUT TYPE="checkbox"  name="live_vehicles[]" VALUE="'.$imei_arr[$vehicle].'" checked ></span>          
              <span id="'.$vrad.'" style="display:none;"><INPUT TYPE="radio"  name="vehicleserial_radio" VALUE="'.$imei_arr[$vehicle].'"></span>              
            </td>
            <td>
              <font color="'.$color.'">'.$vehicle.'</font>
            </td>
          </tr>
          ';
          $m++;
		}*/
	
  }
  
	
?>
