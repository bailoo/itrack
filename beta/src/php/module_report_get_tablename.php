<?php   
    function get_tablename($imeino, &$tablename, &$vname, $DbConnection)
    {
      $Query1 = "select VehicleName from vehicle where VehicleSerial='$imeino'";   
  		$Result1 = mysql_query($Query1,$DbConnection);
  		$row1 = mysql_fetch_object($Result1);
  		$vname = $row1->VehicleName;
  
  		$Query2 = "select track_table_id FROM device_lookup where device_imei_no='$imeino'";	 
  		$Result2 = mysql_query($Query2,$DbConnection);
  		$row = mysql_fetch_object($Result2);
  		$tableid = $row->track_table_id;  		
      $tablename = "t".$tableid;
    } 
   function get_tablename_1($imeino, &$tablename, &$vehicle_details, $DbConnection)
    {
      $Query1 = "SELECT * FROM vehicle where VehicleSerial='$imeino'";   
  		$Result1 = mysql_query($Query1,$DbConnection);
  		$row1 = mysql_fetch_object($Result1);
  		$vehicleid=$row1->VehicleID;
  		$vname = $row1->VehicleName;
  		$fuel_voltage=$row1->FuelVoltage;
  		$tank_capacity=$row1->TankCapacity;
  		$vehicle_details=$vehicleid.",".$vname.",".$fuel_voltage.",".$tank_capacity;
  
  		$Query2 = "select track_table_id FROM device_lookup where device_imei_no='$imeino'";	 
  		$Result2 = mysql_query($Query2,$DbConnection);
  		$row = mysql_fetch_object($Result2);
  		$tableid = $row->track_table_id;  		
      $tablename = "t".$tableid;
      
    //  echo "query1=".$Query1;
      
     // echo "query2=".$Query2;
    }
?>