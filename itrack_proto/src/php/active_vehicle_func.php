<?php
	function get_acitve_vlastdate($vehicle_imei,$DbConnection)
	{
		$Query="SELECT last_date FROM active_vehicle WHERE imei_no=$vehicle_imei";
		//echo "Query=".$Query."<br>";
		$Result=mysql_query($Query,$DbConnection);
		if($Result)
		{
			$Row=mysql_fetch_row($Result);
			//echo "last_date=".$Row[0]."<br>";
			return $Row[0];
		}
	}

	function get_active_imeino($vehicle_imei,$DbConnection)
	{
		$Query="SELECT imei_no FROM active_vehicle WHERE imei_no='$vehicle_imei'";
		//echo "Query=".$Query."<br>";
		$Result=mysql_query($Query,$DbConnection);
		$Row=mysql_fetch_row($Result);
		//echo "last_date=".$Row[0]."<br>";
		return $Row[0];
	}

	function insert_active_vehicle($imeino,$lastdate,$DbConnection)
	{
		$Query="INSERT INTO active_vehicle (imei_no,last_date) VALUES($imeino,'$lastdate')";
		//echo "Query=".$Query."<br>";
		$Result=mysql_query($Query,$DbConnection);
		//$Row=mysql_fetch_row($Result);		
		//return $Row[0];
	}

	function update_active_vehicle($imeino,$lastdate,$DbConnection)
	{
		$Query="UPDATE active_vehicle set last_date='$lastdate' WHERE imei_no='$imeino'";
		// echo "Query=".$Query."<br>";
		$Result=mysql_query($Query,$DbConnection);
		//$Row=mysql_fetch_row($Result);		
		//return $Row[0];
	}
?>