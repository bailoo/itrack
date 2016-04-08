<?php

	   //$name_query="select FuelVoltage,TankCapacity from vehicle where VehicleSerial='$vehicleserial[$i]'";
	    $name_query="select fuel_voltage,tank_capacity from vehicle where vehicle_id IN (SELECT vehicle_id from vehicle_assignment where device_imei_no='$vehicleserial[$i]')";
	  // echo "query=".$name_query;
	   $query_res=mysql_query($name_query, $DbConnection);
	   $num_rows=mysql_num_rows($query_res);

		//echo "num_rows=".$num_rows."<br>";
		if($row=mysql_fetch_object($query_res))
		{	
			//$row = mysql_fetch_object($query_res);
			$fuel_voltage = $row->fuel_voltage;		
			$tank_capacity = $row->tank_capacity;
			$max_fuel=0;

			//echo "tank_capacity=".$fuel_voltage[$k]." tank_capacity=".$tank_capacity[$k];
			
			//echo "max_fuel1=".$max_fuel." fuel_voltage1=".$fuel_voltage;
									
			if((strcmp($fuel_voltage,"5v")==0))
			{
				//echo "in if";
				//$max_fuel = 340;	//FOR 10 BIT ADC
				$max_fuel = 567;	//FOR 12 BIT ADC STM32
			}
			else if((strcmp($fuel_voltage,"12v")==0)||(strcmp($fuel_voltage,"inv12v")==0))
			{
				//echo "in if";
				//$max_fuel = 340;	//FOR 10 BIT ADC
				$max_fuel = 1360;	//FOR 12 BIT ADC STM32
			}
		
			else if((strcmp($fuel_voltage,"24v")==0)||(strcmp($fuel_voltage,"inv24v")==0))
			{
				//echo "max_fuel2=".$max_fuel[$k]." fuel_voltage2=".$fuel_voltage;
								
				//$max_fuel = 680;	//FOR 10 BIT ADC
				$max_fuel = 2720;	//FOR 12 BIT ADC STM32
			}
				
			$litres = 0;

			if(strcmp($fuel_voltage,"5v")==0)
			{
				//if($fuel == $max_fuel)
				if($fuel >= $max_fuel)
				{
					$litres = $tank_capacity;
					$level = 100;
				}

				else
				{
					if($max_fuel>0)
					{
						$litres = ($fuel * $tank_capacity)/$max_fuel;
						$level = ($fuel *100)/$max_fuel;
					}
				}							
			}
			else if(strcmp($fuel_voltage,"12v")==0)
			{
				//if($fuel == $max_fuel)
				if($fuel >= $max_fuel)
				{
					$litres = $tank_capacity;
					$level = 100;
				}

				else
				{
					if($max_fuel>0)
					{
						$litres = ($fuel * $tank_capacity)/$max_fuel;
						$level = ($fuel *100)/$max_fuel;
					}
				}							
			}
			else if( strcmp($fuel_voltage,"inv12v")==0)
			{
				//if($fuel == $max_fuel)
				if($fuel >= $max_fuel)
				{
					$litres = 0;
					$level = 0;
				}

				else
				{
					if($max_fuel>0)
					{
						$litres = $tank_capacity - (($fuel * $tank_capacity)/$max_fuel);
						$level = 100 - (($fuel *100)/$max_fuel);
					}
				}							
			}
			else if( strcmp($fuel_voltage,"24v")==0)
			{
				if($fuel >= $max_fuel)
				{
					$litres = $tank_capacity;
					$level = 100;
				}
					
				else
				{
					//echo "max_fuel=".$max_fuel[$k];
					if($max_fuel>0)
					{						
						$litres = ($fuel * $tank_capacity)/$max_fuel;
						$level = ($fuel *100)/$max_fuel;
					}
				}
			}
			else if( strcmp($fuel_voltage,"inv24v")==0)
			{
				//if($fuel == $max_fuel)
				if($fuel >= $max_fuel)
				{
					$litres = 0;
					$level = 0;
				}

				else
				{
					if($max_fuel>0)
					{
						$litres = $tank_capacity - (($fuel * $tank_capacity)/$max_fuel);
						$level = 100 - (($fuel *100)/$max_fuel);
					}
				}							
			}
						
			$fuel_litres = round($litres,2);		
			$fuel_level = round($level,2);
			
			if($fuel_litres=="")
				$fuel_litres="-";
				
			if($fuel_level=="")
				$fuel_level="-";
				
			$fuel_lit_level=$fuel_litres.",".$fuel_level;
			//echo $fuel_lit_level;
		}
?>
