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
	global $transporter_input_mor;
	global $route_input_ev;
	global $route_input_type_ev;
	global $route_input_mor;
	global $route_input_type_mor;
	global $customer_input;

	//$tpt_path_ev = $abspath."/".$account_id."/master/tpt_ev#1#20.csv";	
	//$tpt_path_mor = $abspath."/".$account_id."/master/tpt_mor#1#30.csv";

	$abspath ="/var/www/html/vts/beta/src/php";	
	$dir = $abspath."/gps_report/".$account_id."/master";
	//echo "<br>dir=".$dir;
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		//echo "<A HREF=\"$file\">$file</A><BR>\n";
		$file_tmp = explode("#",$file);
		$file_ext = explode(".",$file_tmp[2]);
		
		//echo "\nfile_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;  
		
		if( ($file_ext[0] == "4") && ($shift_time=="ZPME") )		//###### EVENING FILE
		{
			//echo "\nEVVVV";
			$path = $dir."/".$file;

			$row = 1;
			if (($handle = fopen($path, "r")) !== FALSE) 
			{
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
				{
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";
					$row++;					
					
					// echo "data[8]=".$data[8] . "\n";
					// echo "data[9]=".$data[9] . "\n";
										
					if($num<10)
					{
						continue;
					}
					if($row > 2)
					{						
						$plant_input_ev[] = $data[5];
						$route_input_ev[] = $data[6];
						$vehicle_input_ev[] = $data[7];
						$transporter_input_ev[] = $data[8];
						$customer_input_ev[] = $data[9];																
						//echo "<br>EVE::r=".$row." ,data[5]=".$data[5]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[8]=".$data[8]." ,data[9]=".$data[9]."\n";
					} 					
				}
				fclose($handle);
				//echo "\nsizeof(route_input)=".sizeof($route_input);
			}
		}
     
		if( ($file_ext[0] == "5") && ($shift_time=="ZPMM") )			//###### MORNING FILE
		{
			//echo "\nMor";
			$path = $dir."/".$file;
			$row = 1;        
			if (($handle = fopen($path, "r")) !== FALSE) {
					
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					//echo "<p> $num fields in line $row: <br /></p>\n";              
					$row++;
					
					if($num<10)
					{
						continue;
					}
					if($row > 2)
					{				
						$plant_input_mor[] = $data[5];
						$route_input_mor[] = $data[6];
						//$route_input_type_mor[] = $data[7];
						$vehicle_input_mor[] = $data[7];
						$transporter_input_mor[] = $data[8];
						$customer_input_mor[] = $data[9];
						//echo "\nMOR::r=".$row." ,data[5]=".$data[5]." ,data[9]=".$data[9]."\n";
					}											
				}
				fclose($handle);			
			}
		}	
	}  //
  
	//echo   
	closedir($dh);
	
	//######## MAKE VEHICLE ROUTE MASTER
	if($shift_time == "ZPME")
	{

		//##CUSTOMER ROUTE #7 FILE
               $plant_customer_write_path_ev = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master/evening_plant_customer#1#7.csv";
                unlink($plant_customer_write_path_ev);
                //$plant_customer_write_path_ev = "C:\\xampp/htdocs/sorting_motherdairy/evening_plant_customer#1#7.csv";
                sort_station($plant_input_ev, $customer_input_ev, $transporter_input_ev, $route_input_ev, $route_input_type_ev, $plant_customer_write_path_ev);
		//########


	//echo "\nVehicleInputEv1=".sizeof($vehicle_input_ev);
		sort_all_vehicles($shift_time);
		//echo "<br>AS:SizeCustomerInputEv=".sizeof($customer_input_ev);
		
		########## ROUTE -VEHICLE CREATE DB FILE-EV
		$linetowrite = "";
		$linetowrite =  $linetowrite."Vehicle,Route";

	//echo "\nVehicleInputEv2=".sizeof($vehicle_input_ev);
	
		for($i=0;$i<sizeof($vehicle_input_ev);$i++)
		{
			$j=$i;
			$route_ev_str="";
			//echo "<br>V1=".$vehicle_input_db_ev[$j]." ,V2=".$vehicle_input_db_ev[$i];
			while($vehicle_input_ev[$j]==$vehicle_input_ev[$i])
			{			
				//echo "\nINNNNN2";
				$route_ev_str.= $route_input_ev[$j]."/";				
				if($vehicle_input_ev[$j]=="") break;
				$j++;				
			}
			//echo "<br>C=".$c;
			//echo "<br>AS3:i=".$i;
			
			if($route_ev_str!="") {$route_ev_str = substr($route_ev_str, 0, -1);}
			$route_ev_str = implode('/', array_unique(explode('/', $route_ev_str)));
			//$vehicle_input_db_ev_final[] = $vehicle_input_db_ev[$i];
			//$route_input_db_ev_final[] = $route_ev_str;
			$linetowrite =  $linetowrite."\n".$vehicle_input_ev[$i].",".$route_ev_str;
			//echo "<br>Line=".$linetowrite;			
			
			if($j>$i)
			{
				$i=$j-1;
			}
			//echo "\nDone=i=".$i." ,j=".$j;
		}
		
		//echo "\nLineToWrite=".$linetowrite;
		//echo "\nAS2:SizeCustomerInputEv=".sizeof($customer_input_ev);		
		$master_db_path = $abspath."/".$account_id."/master/vehicle_route_db_ev#1#40.csv";	
		//echo "\nMasterDbPath=".$master_db_path;
		if(file_exists($master_db_path)) unlink($master_db_path);
		$file = fopen($master_db_path,"w");
		fwrite($file,$linetowrite);
		fclose($file);
		//######### MASTER DB FILE CLOSED
		
		/*//######### MAKE TRANSPORTER FILE
		$linetowrite = "";
		$linetowrite =  $linetowrite."Vehicle,Transporter";
		
		for($i=0;$i<sizeof($vehicle_input_ev);$i++)
		{
			$j=$i;
			$transporter_ev_str="";
			while($vehicle_input_ev[$j]==$vehicle_input_ev[$i])
			{			
				$transporter_ev_str.= $transporter_input_ev[$j]."/";				
				if($vehicle_input_ev[$j]=="") break;
				$j++;				
			}
			
			if($transporter_ev_str!="") {$transporter_ev_str = substr($transporter_ev_str, 0, -1);}
			$transporter_ev_str = implode('/', array_unique(explode('/', $transporter_ev_str)));
			//$vehicle_input_db_ev_final[] = $vehicle_input_db_ev[$i];
			//$route_input_db_ev_final[] = $route_ev_str;
			$linetowrite =  $linetowrite."\n".$vehicle_input_ev[$i].",".$transporter_ev_str;			
			
			if($j>$i)
			{
				$i=$j-1;
			}
		}
		//$master_transporter_path = $abspath."/".$account_id."/master/vehicle_transporter#1#6.csv";	
		if(file_exists($tpt_path_ev)) unlink($tpt_path_ev);
		$file = fopen($tpt_path_ev,"w");
		fwrite($file,$linetowrite);
		fclose($file);
		//######### TRANSPORTER FILE CLOSED*/
	}
	
	//######## ZPMM
	if($shift_time == "ZPMM")
	{	
		//##### CUSTOMER ROUTE
                $plant_customer_write_path_mor = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master/morning_plant_customer#1#8.csv";
                unlink($plant_customer_write_path_mor);
                //$plant_customer_write_path_mor = "C:\\xampp/htdocs/sorting_motherdairy/morning_plant_customer#1#8.csv";
                sort_station($plant_input_mor, $customer_input_mor, $transporter_input_mor, $route_input_mor, $route_input_type_mor, $plant_customer_write_path_mor);
		//#################
		
		sort_all_vehicles($shift_time);
		
		########## ROUTE -VEHICLE CREATE DB FILE-MOR
		$linetowrite = "";
		$linetowrite =  $linetowrite."Vehicle,Route";
				
		for($i=0;$i<sizeof($vehicle_input_mor);$i++)
		{
			$j=$i;			
			//echo "<br>V1_mor:V1=".$vehicle_input_db_mor[$j]." ,V2=".$vehicle_input_db_mor[$i];
			$route_mor_str="";
			while(trim($vehicle_input_mor[$j])==trim($vehicle_input_mor[$i]))
			{							
				$route_mor_str.= $route_input_mor[$j]."/";
				if($vehicle_input_mor[$j]=="") break;
				$j++;
			}			
			
			if($route_mor_str!="") {$route_mor_str = substr($route_mor_str, 0, -1);}
			$route_mor_str = implode('/', array_unique(explode('/', $route_mor_str)));
			//echo "<br>RouteMorStr=".$route_mor_str." ,vehicle_input_db_mor=".$vehicle_input_db_mor[$i];
			//$vehicle_input_db_ev_final[] = $vehicle_input_db_ev[$i];
			//$route_input_db_ev_final[] = $route_ev_str;
			$linetowrite =  $linetowrite."\n".$vehicle_input_mor[$i].",".$route_mor_str;			
			
			if($j>$i)
			{
				$i=$j-1;
			}			
		}
		$master_db_path = $abspath."/".$account_id."/master/vehicle_route_db_mor#1#50.csv";	
		if(file_exists($master_db_path)) unlink($master_db_path);
		$file = fopen($master_db_path,"w");
		fwrite($file,$linetowrite);
		fclose($file);		
		//######### MASTER DB FILE CLOSED
		/*
		//######### MAKE TRANSPORTER FILE
		$linetowrite = "";
		$linetowrite =  $linetowrite."Vehicle,Transporter";
		
		for($i=0;$i<sizeof($vehicle_input_db_mor);$i++)
		{
			$j=$i;
			$transporter_mor_str="";
			while($vehicle_input_db_mor[$j]==$vehicle_input_db_mor[$i])
			{			
				$transporter_mor_str.= $transporter_input_mor[$j]."/";
				if($transporter_mor_str[$j]=="") break;
				$j++;				
			}			
			
			if($transporter_mor_str!="") {$transporter_mor_str = substr($transporter_mor_str, 0, -1);}
			$transporter_mor_str = implode('/', array_unique(explode('/', $transporter_mor_str)));
			//$vehicle_input_db_ev_final[] = $vehicle_input_db_ev[$i];
			//$route_input_db_ev_final[] = $route_ev_str;
			$linetowrite =  $linetowrite."\n".$vehicle_input_db_mor[$i].",".$transporter_mor_str;			
			
			if($j>$i)
			{
				$i=$j-1;
			}			
		}
		//$master_transporter_path_mor = $abspath."/".$account_id."/master/vehicle_transporter_ev.csv";	
		if(file_exists($tpt_path_mor)) unlink($tpt_path_mor);
		$file = fopen($tpt_path_mor,"w");
		fwrite($file,$linetowrite);
		fclose($file);
		//######### TRANSPORTER FILE CLOSED*/
	}

} //function closed
  


function sort_station($plant_input, $customer_input, $transporter_input, $route_input, $route_input_type, $plant_customer_write_path)
{
	//echo "\nSORT STATION=".$plant_customer_write_path." ,size=".sizeof($customer_input)."\n";
	for($x = 1; $x < sizeof($customer_input); $x++) 
	{
		$tmp_customer_input = $customer_input[$x];
		$tmp_plant_input = $plant_input[$x];   				     				
		$tmp_transporter_input = $transporter_input[$x];
		$tmp_route_input = $route_input[$x];
		$tmp_route_input = $route_input[$x];
		$tmp_route_input_type = $route_input_type[$x];						
		///////////      				

		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$customer_tmp1 = $customer_input[$z];

			if ($customer_tmp1 >$tmp_customer_input)
			{
				$customer_input[$z + 1] = $customer_input[$z];
				$plant_input[$z + 1] = $plant_input[$z];			                         			
				$transporter_input[$z + 1] = $transporter_input[$z];
				$route_input[$z + 1] = $route_input[$z];
				$route_input_type[$z + 1] = $route_input_type[$z];
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
		$plant_input[$z + 1] = $tmp_plant_input;
		$transporter_input[$z + 1] = $tmp_transporter_input;
		$route_input[$z + 1] = $tmp_route_input;
		$route_input_type[$z + 1] = $tmp_route_input_type;
                    
	}  // FOR CLOSED        

	
	
	///////////// SORTING CLOSED /////////////////////
	$linetowrite = "Customer,Plant,Transporter,Route,RouteType";
	$repeated_index = array();
	$repeatflag_plant=false;
	$repeatflag_transporter=false;
	$repeatflag_route=false;
	$repeatflag_route_type=false;
	
	//echo "\nSizeofCustomer=".sizeof($customer_input);
	for($y=0;$y<sizeof($customer_input);$y++)
	{
		//echo "\nCustomer=".$customer_input[$y];
		//if($customer_input[$y] == "38") { echo "\nFound-38"; }
	}
	
	for($y=0;$y<sizeof($customer_input);$y++)
	{		
		//if($customer_input[$y] == "38") { echo "\nFound-38"; }
		//echo "\nRepeatIndexSize=".sizeof($repeated_index);
	//	echo "y1=".$y."\n";
		//echo "\nCustomer=".$customer_input[$y];
		
		$continue_flag = false;
		
		$customer_string = $customer_input[$y];
		$plant_string = $plant_input[$y];
		$transporter_string = $transporter_input[$y];
		$route_string = $route_input[$y];		
		$route_type_string = $route_input_type[$y];		
	
		for($m=$y+1;$m<sizeof($customer_input);$m++)
		{
			if($customer_input[$y] == $customer_input[$m])
			{
				$repeatflag_plant=false;
				$repeatflag_transporter =false;
				$repeatflag_route =false;
				$repeatflag_route_type =false;
				
				for($i=$y+1;$i<=$m;$i++)
				{
					if($plant_input[$i]==$plant_input[$y])
					{
							$repeatflag_plant=true;
					}
					
					if($transporter_input[$i]==$transporter_input[$y])
					{
							$repeatflag_transporter=true;
					}

					if($route_input[$i]==$route_input[$y])
					{
							$repeatflag_route=true;
					}
					if($route_input_type[$i]==$route_input_type[$y])
					{
							$repeatflag_route_type=true;
					}											
				}
				if($repeatflag_plant==false)
				{
					$plant_string = $plant_string.'/'.$plant_input[$m];				
				}
				/*if($repeatflag_transporter==false)
				{
					$transporter_string = $transporter_string.'/'.$transporter_input[$m];				
				}*/
				if($repeatflag_route==false)
				{
					$route_string = $route_string.'/'.$route_input[$m];
					$route_type_string = $route_type_string.'/'.$route_input_type[$m];				
					$transporter_string = $transporter_string.'/'.$transporter_input[$m];
				}
				/*if($repeatflag_route_type==false)
				{
					$route_type_string = $route_type_string.'/'.$route_input_type[$m];				
				}*/				
			}			
			else
			{
				break;
			}
		}
		$y=$m-1;
		//echo "y=".$y."\n";
		
		if($plant_string == "") $plant_string = "-";
		$linetowrite =  $linetowrite."\n"." ".$customer_string.",".$plant_string.",".$transporter_string.",".$route_string.",".$route_type_string;
		//echo "linetowrite=".$linetowrite."\n";		         
	}		
	
	//echo "<br>linetowrite=".$linetowrite."\n";
	//FINAL FILE WRITE
	$file = fopen($plant_customer_write_path,"w");
	fwrite($file,$linetowrite);
	fclose($file);         
   
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
