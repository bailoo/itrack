<?php
$plant_input = array();
$transporter_input_ev = array();
$transporter_input_mor = array();
$route_input_ev = array();
$route_input_type_ev = array();
$route_input_mor = array();
$route_input_type_mor = array();
$customer_input = array();
	
function get_master_detail($account_id, $shift_time)
{
	//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;
	global $plant_input;
	global $transporter_input_ev;

	global $customer_input;

	//$tpt_path_ev = $abspath."/".$account_id."/master/tpt_ev#1#20.csv";	
	//$tpt_path_mor = $abspath."/".$account_id."/master/tpt_mor#1#30.csv";

        $abspath = "/var/www/html/vts/test/src/php/gps_report";
        //$abspath = $pathToRoot."/s3/itrack/gps_report/";
        //$abspath = "C:\\xampp/htdocs/itrackDevelop/beta/src/php/gps_report";	
        $dir = $abspath."/".$account_id."/master";
        
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		//echo "<A HREF=\"$file\">$file</A><BR>\n";
		$file_tmp = explode("#",$file);
		$file_ext = explode(".",$file_tmp[2]);
		
		//echo "\nfile_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;  
		
		if($file_ext[0] == "4")		//###### MASTER FILE
		{
			//echo "\nEVVVV";
			$path = $dir."/".$file;

			$row = 0;
			if (($handle = fopen($path, "r")) !== FALSE) 
			{
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";
					$row++;					
					
					// echo "data[8]=".$data[8] . "\n";
					// echo "data[9]=".$data[9] . "\n";
										
					if($num<6)
					{
						continue;
					}
					if($row >1)
					{
						$vehicle_input_ev[] = $data[4];				
						$customer_input_ev[] = $data[5];
						$transporter_input_ev[] = $data[6];				
						//echo "<br>EVE::r=".$row." ,data[4]=".$data[4]." ,data[5]=".$data[5]." ,data[6]=".$data[6]."\n";
					} 					
				}
				fclose($handle);
				//echo "\nsizeof(route_input)=".sizeof($route_input);
			}
		}
	}  //
  
	//echo   
	closedir($dh);
	
	//######## MAKE VEHICLE ROUTE MASTER
	$vehicle_customer_write_path = $abspath."/".$account_id."/master/vehicle_customer#1#7.csv";
	//echo "<br>VehicleCustomerWritePath=".$vehicle_customer_write_path;
	//unlink($vehicle_customer_write_path_ev);
	//$plant_customer_write_path_ev = "C:\\xampp/htdocs/sorting_motherdairy/evening_plant_customer#1#7.csv";
	sort_station($vehicle_input_ev, $customer_input_ev, $transporter_input_ev, $vehicle_customer_write_path);
	//########


} //function closed
  

function sort_station($vehicle_input, $customer_input,$transporter_input, $vehicle_customer_write_path)	//SORT BY VEHICLE
{
    global $account_id;
	//echo "\nSORT STATION=".$plant_customer_write_path." ,size=".sizeof($customer_input)."\n";
	for($x = 1; $x < sizeof($customer_input); $x++) 
	{
		$tmp_customer_input = $customer_input[$x];					     				
		$tmp_transporter_input = $transporter_input[$x];
		$tmp_vehicle_input = $vehicle_input[$x];					
		///////////      				

		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$vehicle_tmp1 = $vehicle_input[$z];

			if ($vehicle_tmp1 >$tmp_vehicle_input)
			{
				$customer_input[$z + 1] = $customer_input[$z];		                         			
				$transporter_input[$z + 1] = $transporter_input[$z];
				$vehicle_input[$z + 1] = $vehicle_input[$z];
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

		$customer_input[$z + 1] = $tmp_customer_input;
		$transporter_input[$z + 1] = $tmp_transporter_input;
		$vehicle_input[$z + 1] = $tmp_vehicle_input;
                    
	}  // FOR CLOSED        

	
	///////////// SORTING CLOSED /////////////////////
	$linetowrite = "Vehicle,Customer,Transporter";
	$repeated_index = array();
	$repeatflag_vehicle=false;
	$repeatflag_transporter=false;
	$repeatflag_customer=false;
	
	//echo "\nSizeofCustomer=".sizeof($customer_input);	
	for($y=0;$y<sizeof($vehicle_input);$y++)
	{		
		$continue_flag = false;
		
		$customer_string = $customer_input[$y];
		$transporter_string = $transporter_input[$y];
		$vehicle_string = $vehicle_input[$y];
	
		for($m=$y+1;$m<sizeof($vehicle_input);$m++)
		{
			if($vehicle_input[$y] == $vehicle_input[$m])
			{
				$repeatflag_customer =false;
				$repeatflag_transporter =false;
				
				for($i=$y+1;$i<=$m;$i++)
				{
					if($customer_input[$i]==$customer_input[$y])
					{
							$repeatflag_customer=true;
					}
					
					if($transporter_input[$i]==$transporter_input[$y])
					{
							$repeatflag_transporter=true;
					}											
				}
				if($repeatflag_customer==false)
				{
					$customer_string = $customer_string.'/'.$customer_input[$m];				
				}
				if($repeatflag_transporter==false)
				{			
					$transporter_string = $transporter_string.'/'.$transporter_input[$m];
				}
			}			
			else
			{
				break;
			}
		}
		$y=$m-1;
		//echo "y=".$y."\n";
		
		if($customer_string == "") $customer_string = "-";
		if($transporter_string == "") $transporter_string = "-";
		$linetowrite =  $linetowrite."\n"." ".$vehicle_string.",".$customer_string.",".$transporter_string;
		//echo "linetowrite=".$linetowrite."\n";		         
	}		
	
	//echo "<br>linetowrite=".$linetowrite."\n";
	//FINAL FILE WRITE
	$file = fopen($vehicle_customer_write_path,"w");
	fwrite($file,$linetowrite);
	fclose($file);
        $filePathTempArr=explode("/",$vehicle_customer_write_path);        
        $s3FilePath="gps_report/".$account_id."/master/".$filePathTempArr[sizeof($filePathTempArr)-1];
        //echo "s3FilePath=".$s3FilePath." plant_customer_write_path=".$plant_customer_write_path."<br>";
        uploadFile($s3FilePath,$vehicle_customer_write_path,true);  
   
} // FUNCTION CLOSED
 
 
 
//######### SORT WITH RESPECT TO VEHICLES ##################
function sort_all_vehicles($shift_time)
{
	//echo "SHIFT TIME=".$shift_time;
	if($shift_time=="ZPME")
	{
		global $vehicle_input_ev;
		global $route_input_ev;
		global $transporter_input_ev;
		
		/*for($x = 0; $x < sizeof($RouteNo_CI); $x++) 
		{
			//echo "<br>Vehicle:BEFORE_SORT=".$Vehicle_CI[$x];
		}*/
		//echo "\nBS";
		for($x = 1; $x < sizeof($vehicle_input_ev); $x++) 
		{
			$tmp_vehicle_ci = $vehicle_input_ev[$x];
			$tmp_route_ci = $route_input_ev[$x];
			$tmp_transporter_ci = $transporter_input_ev[$x];			
			///////////      				

			$z = $x - 1;
			$done = false;
			while($done == false)
			{
				$vehicle_tmp1 = $vehicle_input_ev[$z];			
				//echo "<br>RouteTmp1=".$route_tmp1." ,tmp_route_ci=".$tmp_route_ci;
				if (trim($vehicle_tmp1) > trim($tmp_vehicle_ci))
				{
					$vehicle_input_ev[$z + 1] = $vehicle_input_ev[$z];				
					$route_input_ev[$z + 1] = $route_input_ev[$z];
					$transporter_input_ev[$z + 1] = $transporter_input_ev[$z];					
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

			$vehicle_input_ev[$z + 1] = $tmp_vehicle_ci;		
			$route_input_ev[$z + 1] = $tmp_route_ci;
			$transporter_input_ev[$z + 1] = $tmp_transporter_ci;				
		}
	}
	else if($shift_time=="ZPMM")
	{
		//echo "<br>ZPMM SHIFT SORT";
		global $vehicle_input_mor;
		global $route_input_mor;	
		global $transporter_input_mor;
		
		/*for($x = 0; $x < sizeof($RouteNo_CI); $x++) 
		{
			//echo "<br>Vehicle:BEFORE_SORT=".$Vehicle_CI[$x];
		}*/
		for($x = 1; $x < sizeof($vehicle_input_mor); $x++) 
		{
			$tmp_vehicle_ci = $vehicle_input_mor[$x];
			$tmp_route_ci = $route_input_mor[$x];
			$tmp_transporter_ci = $transporter_input_mor[$x];			
			///////////      				

			$z = $x - 1;
			$done = false;
			while($done == false)
			{
				$vehicle_tmp1 = $vehicle_input_mor[$z];			
				//echo "<br>RouteTmp1=".$route_tmp1." ,tmp_route_ci=".$tmp_route_ci;
				if (trim($vehicle_tmp1) > trim($tmp_vehicle_ci))
				{
					$vehicle_input_mor[$z + 1] = $vehicle_input_mor[$z];				
					$route_input_mor[$z + 1] = $route_input_mor[$z];
					$transporter_input_mor[$z + 1] = $transporter_input_mor[$z];					
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

			$vehicle_input_mor[$z + 1] = $tmp_vehicle_ci;		
			$route_input_mor[$z + 1] = $tmp_route_ci;
			$transporter_input_mor[$z + 1] = $tmp_transporter_ci;			
		}
		//echo "AS:morning sort";
	}
}	 
?> 
