<?php
set_time_limit(18000);
//get_master_detail("568", "ZBVM");
$vehicle_input_db_mor = array();
$route_input_db_mor = array();	
$transporter_input_mor = array();

$vehicle_input_db_ev = array();
$route_input_db_ev = array();
$transporter_input_ev = array();	 

function get_master_detail($account_id, $shift_time)
{
	//echo "<br>ShiftTime=".$shift_time;
	$abspath = "/var/www/html/vts/beta/src/php/gps_report";
	//$abspath = $pathToRoot."/s3/itrack/gps_report/";
	//$abspath = "C:\\xampp/htdocs/itrackDevelop/beta/src/php/gps_report";
	
	$tpt_path_ev = $abspath."/".$account_id."/master/tpt_ev#1#20.csv";	
	$tpt_path_mor = $abspath."/".$account_id."/master/tpt_mor#1#30.csv";	
	//$abspath = "/var/www/html/vts/beta/src/php/gps_report";		
	//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;
	global $plant_input;
	global $transporter_input_ev;
	global $transporter_input_mor;
	global $route_input_ev;
	global $route_input_type_ev;
	global $route_input_mor;
	global $route_input_type_mor;
	global $customer_input;
	
	//$dir = "c:\\gps_report/231/master";
	//$dir = "c:\\halt2/test_master";
	//$dir = "C:\\xampp/htdocs/sorting_motherdairy";	
	//$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
	$dir = $abspath."/".$account_id."/master";
	//echo "<br>Dir=".$dir;

	//######### GET PLANT MASTER

	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
                //echo "<A HREF=\"$file\">$file</A><BR>\n";
                $file_tmp = explode("#",$file);
                $file_ext = explode(".",$file_tmp[2]);

                //echo "<br>file_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;

                if($file_ext[0] == "2")
                {
                    	if( ($file_tmp[1] == "1") && (!$read_nine1) )           //###### DISTANCE BVM -EVENING FILE
                    	{
				$read_nine1 = true;
				//echo "<br>EV:9-1";
				$path = $dir."/".$file;
				$row = 1;
				if (($handle = fopen($path, "r")) !== FALSE)
				{
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
					{
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";
						$row++;

						if($num<5)
						{
							continue;
						}
						if($row > 2)
						{
							$plant_master[$data[0]] = $data[1];
                                                        $route_type_plant[$data[0]] = $data[5];
                                                        //echo "<br>RouteType=".$route_type_plant[$data[0]];
						}
					}

				}
			}
		}
	}
	closedir($dh);



	//#########################


	$dh = opendir($dir);
	$read_nine1 = false;
	$read_nine2 = false;
	$read_ten1 = false;
	$read_ten2 = false;
	
	while (($file = readdir($dh)) !== false) {
		//echo "<A HREF=\"$file\">$file</A><BR>\n";
		$file_tmp = explode("#",$file);
		$file_ext = explode(".",$file_tmp[2]);
		
		//echo "<br>file_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;  		
		
		if( ($file_ext[0] == "9") && ($shift_time=="ZBVE") )
		{			
			if( ($file_tmp[1] == "1") && (!$read_nine1) )		//###### DISTANCE BVM -EVENING FILE
			{
				$read_nine1 = true;
				//echo "<br>EV:9-1";
				$path = $dir."/".$file;

				$row = 1;
				if (($handle = fopen($path, "r")) !== FALSE)
				{
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
					{
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";
						$row++;					
						/*
						 echo "data[5]=".$data[5] . "\n";
						 echo "data[9]=".$data[9] . "\n";
						*/					
						if($num<6)
						{
							continue;
						}
						if($row > 2)
						{						
							$tranporter_input_distance_ev[] = $data[1];
							$route_input_distance_ev[] = $data[2];
							$vehicle_input_distance_ev[] = $data[3];															       			       $plant_master_ev[] = $plant_master[$data[2]];
							//echo "\nEVE:9:r=".$row." ,data[1]=".$data[1]." ,data[2]=".$data[2]."\n";
						} 					
					}
					fclose($handle);
					//echo "\nsizeof(route_input)=".sizeof($route_input);
				}
			}
			if( ($file_tmp[1] == "2") && (!$read_nine2) )		//###### BILLING BVM -EVENING FILE
			{
				$read_nine2 = true;
				//echo "<br>EV:9-2";
				$path = $dir."/".$file;
				//echo "\nPath=".$path;
				$row = 1;
				if (($handle = fopen($path, "r")) !== FALSE) 
				{
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
					{
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";
						$row++;					
						/*
						 echo "data[5]=".$data[5] . "\n";
						 echo "data[9]=".$data[9] . "\n";
						*/					
						if($num<3)
						{
							continue;
						}
						if($row > 1)
						{						
							$route_input_billing_ev[] = $data[1];
							$customer_input_billing_ev[] = $data[2];
                                                        $route_type_input_billing_ev[] = $route_type_plant[$data[1]];
							//echo "\nEVE:9:r=".$row." ,data[1]=".$data[1]." ,data[2]=".$data[2]."\n";
						} 					
					}
					fclose($handle);
					//echo "\nsizeof(route_input)=".sizeof($route_input);
				}
			}			
		}
     
		if( ($file_ext[0] == "10") && ($shift_time=="ZBVM") )			//###### MORNING FILE
		{
			//echo "<br>MorningFile: file_ext=".$file_tmp[1];
			
			if( ($file_tmp[1] == "1") && (!$read_ten1) )		//###### DISTANCE BVM -MORNING FILE
			{
				$read_ten1 = true;
				//echo "<br>MOR:10-1";
				$path = $dir."/".$file;
				$row = 1;        
				if (($handle = fopen($path, "r")) !== FALSE) {
						
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";              
						$row++;
						
						if($num<6)
						{
							continue;
						}
						if($row > 2)
						{				
							$tranporter_input_distance_mor[] = $data[1];
							$route_input_distance_mor[] = $data[2];
							$vehicle_input_distance_mor[] = $data[3];	
							$plant_master_mor[] = $plant_master[$data[2]];
							//echo "\nMOR::r=".$row." ,data[5]=".$data[5]." ,data[9]=".$data[9]."\n";
						}											
					}
					fclose($handle);			
				}
			}
			if( ($file_tmp[1] == "2") && (!$read_ten2) )		//###### BILLING BVM -MORNING FILE
			{
				$read_ten2 = true;
				//echo "<br>MOR:10-2";
				$path = $dir."/".$file;
				$row = 1;        
				if (($handle = fopen($path, "r")) !== FALSE) {
						
					while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
						$num = count($data);
						//echo "<p> $num fields in line $row: <br /></p>\n";              
						$row++;
						
						if($num<3)
						{
							continue;
						}
						if($row > 1)
						{				
							$route_input_billing_mor[] = $data[1];
							$customer_input_billing_mor[] = $data[2];
                                                        $route_type_input_billing_mor[] = $route_type_plant[$data[1]];
							//echo "\nMOR::r=".$row." ,data[5]=".$data[5]." ,data[9]=".$data[9]."\n";
						}											
					}
					fclose($handle);			
				}
			}			
		}	
	}  //
  
	//echo   
	closedir($dh);	
	##########

	//########## ZBVE SHIFT
	if(($shift_time == "ZBVE") && ($read_nine1 && $read_nine2))
	{		
		//######### MAKE MASTER DB FILE
		/*$route_input_distance_ev[] = $data[2];
		$vehicle_input_distance_ev[] = $data[3];		

		$route_input_billing_ev[] = $data[1];
		$customer_input_billing_ev[] = $data[2];*/
		
		//echo "<br>Size:Customer_InputBilling=".sizeof($customer_input_billing_ev);
		//echo "<br>Size:Vehicle_InputDistance=".sizeof($vehicle_input_distance_ev);

		for($i=0;$i<sizeof($customer_input_billing_ev);$i++)
		{			
			for($j=0;$j<sizeof($vehicle_input_distance_ev);$j++)
			{
				if((trim($route_input_distance_ev[$j])==trim($route_input_billing_ev[$i])) && ($route_input_distance_ev[$j]!=""))
				{
					$customer_input_ev[] = $customer_input_billing_ev[$i];
					$route_input_ev[] = $route_input_billing_ev[$i];						
					$transporter_input_ev[] = $tranporter_input_distance_ev[$j];
					$vehicle_input_db_ev[] = $vehicle_input_distance_ev[$j];
					$route_input_db_ev[] = $route_input_billing_ev[$i];
					$plant_input_ev[] = $plant_master_ev[$j];
					$route_input_type_ev[] = $route_type_input_billing_ev[$i];
					break;
				}
			}
		}
		
		//echo "<br>BS:SizeCustomerInputEv=".sizeof($customer_input_ev);
		//####### SORT BY VEHICLE -VEHICLE_INPUT_EV
		sort_all_vehicles($shift_time);
		//echo "<br>AS:SizeCustomerInputEv=".sizeof($customer_input_ev);
		
		########## ROUTE -VEHICLE CREATE DB FILE-EV
		$linetowrite = "";
		$linetowrite =  $linetowrite."Vehicle,Route";
		
		for($i=0;$i<sizeof($vehicle_input_db_ev);$i++)
		{
			$j=$i;
			$route_ev_str="";
			//echo "<br>V1=".$vehicle_input_db_ev[$j]." ,V2=".$vehicle_input_db_ev[$i];
			while($vehicle_input_db_ev[$j]==$vehicle_input_db_ev[$i])
			{			
				//echo "\nINNNNN2";
				$route_ev_str.= $route_input_db_ev[$j]."/";				
				if($vehicle_input_db_ev[$j]=="") break;
				$j++;				
			}
			//echo "<br>C=".$c;
			//echo "<br>AS3:i=".$i;
			
			if($route_ev_str!="") {$route_ev_str = substr($route_ev_str, 0, -1);}
			$route_ev_str = implode('/', array_unique(explode('/', $route_ev_str)));
			//$vehicle_input_db_ev_final[] = $vehicle_input_db_ev[$i];
			//$route_input_db_ev_final[] = $route_ev_str;
			$linetowrite =  $linetowrite."\n".$vehicle_input_db_ev[$i].",".$route_ev_str;			
			
			if($j>$i)
			{
				$i=$j-1;
			}
			//echo "\nDone=i=".$i." ,j=".$j;
		}
		
		//echo "\nAS2:SizeCustomerInputEv=".sizeof($customer_input_ev);
		
		$master_db_path = $abspath."/".$account_id."/master/vehicle_route_db_ev#1#40.csv";	
		if(file_exists($master_db_path)) unlink($master_db_path);
		$file = fopen($master_db_path,"w");
		fwrite($file,$linetowrite);
		fclose($file);
                $s3FilePath="gps_report/".$account_id."/master/vehicle_route_db_ev#1#40.csv";
                uploadFile($s3FilePath,$master_db_path,true);

                //$lines = file($master_db_path);
                //$lines = array_unique($lines);
                //file_put_contents($master_db_path, implode($lines));

		//######### MASTER DB FILE CLOSED
		
		//######### MAKE TRANSPORTER FILE
		$linetowrite = "";
		$linetowrite =  $linetowrite."Vehicle,Transporter";
		
		for($i=0;$i<sizeof($vehicle_input_db_ev);$i++)
		{
			$j=$i;
			$transporter_ev_str="";
			while($vehicle_input_db_ev[$j]==$vehicle_input_db_ev[$i])
			{			
				$transporter_ev_str.= $transporter_input_ev[$j]."/";				
				if($vehicle_input_db_ev[$j]=="") break;
				$j++;				
			}
			
			if($transporter_ev_str!="") {$transporter_ev_str = substr($transporter_ev_str, 0, -1);}
			$transporter_ev_str = implode('/', array_unique(explode('/', $transporter_ev_str)));
			//$vehicle_input_db_ev_final[] = $vehicle_input_db_ev[$i];
			//$route_input_db_ev_final[] = $route_ev_str;
			$linetowrite =  $linetowrite."\n".$vehicle_input_db_ev[$i].",".$transporter_ev_str;			
			
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
                $s3FilePath="gps_report/".$account_id."/master/tpt_ev#1#20.csv";
                uploadFile($s3FilePath,$tpt_path_ev,true);
		//######### TRANSPORTER FILE CLOSED
		
		//################### EVENING FINAL MASTER		
		$plant_customer_write_path_ev = $abspath."/".$account_id."/master/evening_plant_customer#1#11.csv";	
		if(file_exists($plant_customer_write_path_ev)) unlink($plant_customer_write_path_ev);
		//$plant_customer_write_path_ev = "C:\\xampp/htdocs/sorting_motherdairy/evening_plant_customer#1#7.csv";
		sort_station($plant_input_ev, $customer_input_ev, $transporter_input_ev, $route_input_ev, $route_input_type_ev, $plant_customer_write_path_ev);		
	}
	
	//######## ZBVM
	if(($shift_time == "ZBVM") && ($read_ten1 && $read_ten2))
	{	
		echo "<br>ZBVM Condition";	
		//######### MAKE MASTER DB FILE
		/*$route_input_distance_mor[] = $data[2];
		$vehicle_input_distance_mor[] = $data[3];		

		$route_input_billing_mor[] = $data[1];
		$customer_input_billing_mor[] = $data[2];*/
		//echo "<br>SizeCustomerBilling_MOR=".sizeof($customer_input_billing_mor);
		//echo "<br>SizeVehicleInputDistance_MOR=".sizeof($vehicle_input_distance_mor);
		
		for($i=0;$i<sizeof($customer_input_billing_mor);$i++)
		{
			for($j=0;$j<sizeof($vehicle_input_distance_mor);$j++)
			{
				if((trim($route_input_distance_mor[$j])==trim($route_input_billing_mor[$i])) && ($route_input_distance_mor[$j]!=""))
				{
					$customer_input_mor[] = $customer_input_billing_mor[$i];
					$route_input_mor[] = $route_input_billing_mor[$i];						
					$transporter_input_mor[] = $tranporter_input_distance_mor[$j];
					$vehicle_input_db_mor[] = $vehicle_input_distance_mor[$j];
					$route_input_db_mor[] = $route_input_billing_mor[$i];
					$plant_input_mor[] = $plant_master_mor[$j];
					$route_input_type_mor[] = $route_type_input_billing_mor[$i];
					break;
				}
			}
		}
		
		//echo "<br>BS:Size:vehicle_input_db_mor=".sizeof($vehicle_input_db_mor);
		//####### SORT BY VEHICLE -VEHICLE_INPUT_MOR
		//echo "<br>B-SAV=".$shift_time;
		sort_all_vehicles($shift_time);
		//echo "<br>AS:Size:vehicle_input_db_mor=".sizeof($vehicle_input_db_mor);		
		########## ROUTE -VEHICLE CREATE DB FILE-MOR
		$linetowrite = "";
		$linetowrite =  $linetowrite."Vehicle,Route";
				
		for($i=0;$i<sizeof($vehicle_input_db_mor);$i++)
		{
			$j=$i;			
			//echo "<br>V1_mor:V1=".$vehicle_input_db_mor[$j]." ,V2=".$vehicle_input_db_mor[$i];
			$route_mor_str="";
			while(trim($vehicle_input_db_mor[$j])==trim($vehicle_input_db_mor[$i]))
			{							
				$route_mor_str.= $route_input_db_mor[$j]."/";
				if($vehicle_input_db_mor[$j]=="") break;
				$j++;
			}			
			
			if($route_mor_str!="") {$route_mor_str = substr($route_mor_str, 0, -1);}
			$route_mor_str = implode('/', array_unique(explode('/', $route_mor_str)));
			//echo "<br>RouteMorStr=".$route_mor_str." ,vehicle_input_db_mor=".$vehicle_input_db_mor[$i];
			//$vehicle_input_db_ev_final[] = $vehicle_input_db_ev[$i];
			//$route_input_db_ev_final[] = $route_ev_str;
			$linetowrite =  $linetowrite."\n".$vehicle_input_db_mor[$i].",".$route_mor_str;			
			
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
                $s3FilePath="gps_report/".$account_id."/master/vehicle_route_db_mor#1#50.csv";
                uploadFile($s3FilePath,$master_db_path,true);

                //$lines = file($master_db_path);
                //$lines = array_unique($lines);
                //file_put_contents($master_db_path, implode($lines));

		//######### MASTER DB FILE CLOSED
		
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
                $s3FilePath="gps_report/".$account_id."/master/tpt_mor#1#30.csv";
                uploadFile($s3FilePath,$tpt_path_mor,true);
		//######### TRANSPORTER FILE CLOSED
		//################### MORNING FINAL MASTER
		$plant_customer_write_path_mor = $abspath."/".$account_id."/master/morning_plant_customer#1#12.csv";
		if(file_exists($plant_customer_write_path_mor)) unlink($plant_customer_write_path_mor);
		//$plant_customer_write_path_mor = "C:\\xampp/htdocs/sorting_motherdairy/morning_plant_customer#1#8.csv";	
		sort_station($plant_input_mor, $customer_input_mor, $transporter_input_mor, $route_input_mor, $route_input_type_mor, $plant_customer_write_path_mor); 	
	}

	//##### MAKE COMMON TRANSPORTER	
	create_transporter($abspath, $account_id, $tpt_path_ev, $tpt_path_mor);	
} //function closed
  

function sort_station($plant_input, $customer_input, $transporter_input, $route_input, $route_input_type, $plant_customer_write_path)
{
    global $account_id;
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
	
	//echo "linetowrite=".$linetowrite."\n";
	//FINAL FILE WRITE
	$file = fopen($plant_customer_write_path,"w");
	fwrite($file,$linetowrite);
	fclose($file); 
        $filePathTempArr=explode("/",$plant_customer_write_path);
        
        $s3FilePath="gps_report/".$account_id."/master/".$filePathTempArr[sizeof($filePathTempArr)-1];
        //echo "s3FilePath=".$s3FilePath." plant_customer_write_path=".$plant_customer_write_path."<br>";
        uploadFile($s3FilePath,$plant_customer_write_path,true);
   
} // FUNCTION CLOSED

//######### SORT WITH RESPECT TO VEHICLES ##################
function sort_all_vehicles($shift_time)
{
	//echo "SHIFT TIME=".$shift_time;
	if($shift_time=="ZBVE")
	{
		global $vehicle_input_db_ev;
		global $route_input_db_ev;
		global $transporter_input_ev;
		
		/*for($x = 0; $x < sizeof($RouteNo_CI); $x++) 
		{
			echo "<br>Vehicle:BEFORE_SORT=".$Vehicle_CI[$x];
		}*/
		//echo "\nBS";
		for($x = 1; $x < sizeof($vehicle_input_db_ev); $x++) 
		{
			$tmp_vehicle_ci = $vehicle_input_db_ev[$x];
			$tmp_route_ci = $route_input_db_ev[$x];
			$tmp_transporter_ci = $transporter_input_ev[$x];			
			///////////      				

			$z = $x - 1;
			$done = false;
			while($done == false)
			{
				$vehicle_tmp1 = $vehicle_input_db_ev[$z];			
				//echo "<br>RouteTmp1=".$route_tmp1." ,tmp_route_ci=".$tmp_route_ci;
				if (trim($vehicle_tmp1) > trim($tmp_vehicle_ci))
				{
					$vehicle_input_db_ev[$z + 1] = $vehicle_input_db_ev[$z];				
					$route_input_db_ev[$z + 1] = $route_input_db_ev[$z];
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

			$vehicle_input_db_ev[$z + 1] = $tmp_vehicle_ci;		
			$route_input_db_ev[$z + 1] = $tmp_route_ci;
			$transporter_input_ev[$z + 1] = $tmp_transporter_ci;				
		}
	}
	else if($shift_time=="ZBVM")
	{
		echo "<br>ZBVM SHIFT SORT, VehicleInputDB=".sizeof($vehicle_input_db_mor);
		global $vehicle_input_db_mor;
		global $route_input_db_mor;	
		global $transporter_input_mor;
		
		/*for($x = 0; $x < sizeof($RouteNo_CI); $x++) 
		{
			echo "<br>Vehicle:BEFORE_SORT=".$Vehicle_CI[$x];
		}*/
		for($x = 1; $x < sizeof($vehicle_input_db_mor); $x++) 
		{
			$tmp_vehicle_ci = $vehicle_input_db_mor[$x];
			$tmp_route_ci = $route_input_db_mor[$x];
			$tmp_transporter_ci = $transporter_input_mor[$x];			
			///////////      				

			$z = $x - 1;
			$done = false;
			while($done == false)
			{
				$vehicle_tmp1 = $vehicle_input_db_mor[$z];			
				//echo "<br>RouteTmp1=".$route_tmp1." ,tmp_route_ci=".$tmp_route_ci;
				if (trim($vehicle_tmp1) > trim($tmp_vehicle_ci))
				{
					$vehicle_input_db_mor[$z + 1] = $vehicle_input_db_mor[$z];				
					$route_input_db_mor[$z + 1] = $route_input_db_mor[$z];
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

			$vehicle_input_db_mor[$z + 1] = $tmp_vehicle_ci;		
			$route_input_db_mor[$z + 1] = $tmp_route_ci;
			$transporter_input_mor[$z + 1] = $tmp_transporter_ci;			
		}
		echo "AS:morning sort";
	}
}	
 
//######## COMBINE TRANSPORTER
$vehicle_tpt = array();
$transporter_tpt = array();

function create_transporter($abspath, $account_id, $tpt_path_ev, $tpt_path_mor)
{
	$dir = $abspath."/".$account_id."/master";
	$dh = opendir($dir);
	$read_nine1 = false;
	$read_nine2 = false;
	$read_ten1 = false;
	$read_ten2 = false;
		
	$linetowrite = "";
	$linetowrite = "Vehicle,Transporter";
	//##### READ EVENING TP FILE
	$row = 1;
		
	if(file_exists($tpt_path_ev))
	{	
		//echo "<br>EV_TPT=".$tpt_path_ev;
		if (($handle = fopen($tpt_path_ev, "r")) !== FALSE)
		{
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
			{
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;					
				/*
				 echo "data[5]=".$data[5] . "\n";
				 echo "data[9]=".$data[9] . "\n";
				*/					
				if($num<2)
				{
					continue;
				}
				if($row > 1)
				{						
					$vehicle_tpt[] = $data[0];
					$transporter_tpt[] = $data[1];
					//$linetowrite = $linetowrite."\n".$data[0].",".$data[1];
				} 					
			}
			fclose($handle);
			//echo "\nsizeof(route_input)=".sizeof($route_input);
		}
	}
	
	//##### READ MORNING TP FILE
	if(file_exists($tpt_path_mor))
	{
		//echo "<br>MOR_TPT=".$tpt_path_mor;
		$row = 1;
		if (($handle = fopen($tpt_path_mor, "r")) !== FALSE)
		{
			//echo "<br>In handle";
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
			{
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				$row++;					
				/*
				 echo "data[5]=".$data[5] . "\n";
				 echo "data[9]=".$data[9] . "\n";
				*/					
				if($num<2)
				{
					continue;
				}
				if($row > 1)
				{						
					$vehicle_tpt[] = $data[0];
					$transporter_tpt[] = $data[1];
					//echo "<Br>data[0]=".$data[0]." ,data[1]=".$data[1];
					//$linetowrite = $linetowrite."\n".$data[0].",".$data[1];
				} 					
			}
			fclose($handle);
			//echo "\nsizeof(route_input)=".sizeof($route_input);
		}
	}
	
	sort_all_transporter();
	//echo "<br>AS:SAT=".sizeof($vehicle_tpt);;
	
	for($i=0;$i<sizeof($vehicle_tpt);$i++)
	{
		$j=$i;
		//echo "<br>vehicle_tpt_j=".$vehicle_tpt[$j]." ,vehicle_tpt_i=".$vehicle_tpt[$i];
		$transporter_str = "";
		while(trim($vehicle_tpt[$j])==trim($vehicle_tpt[$i]))
		{
			//echo "<br>TPT MATCH";
			$transporter_str.= $transporter_tpt[$j]."/";
			if($vehicle_tpt[$j]=="") break;
			$j++;
		}
		
		if($transporter_str!="") {$transporter_str = substr($transporter_str, 0, -1);}
		$transporter_str = implode('/', array_unique(explode('/', $transporter_str)));
		$linetowrite =  $linetowrite."\n".$vehicle_tpt[$i].",".$transporter_str;	

		if($j>$i)
		{
			$i=$j-1;
		}			
	}
	
	$master_transporter_path_mor = $abspath."/".$account_id."/master/vehicle_transporter#1#6.csv";		
	if(file_exists($master_transporter_path_mor)) unlink($master_transporter_path_mor);
	$file = fopen($master_transporter_path_mor,"w");
	fwrite($file,$linetowrite);
	fclose($file);
        $s3FilePath="gps_report/".$account_id."/master/vehicle_transporter#1#6.csv";
        uploadFile($s3FilePath,$master_transporter_path_mor,true);
}

function sort_all_transporter()
{
	global $vehicle_tpt;
	global $transporter_tpt;

	for($x = 1; $x < sizeof($vehicle_tpt); $x++) 
	{
		$tmp_vehicle_ci = $vehicle_tpt[$x];
		$tmp_tpt_ci = $transporter_tpt[$x];		
		///////////      				

		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$vehicle_tmp1 = $vehicle_tpt[$z];			
			//echo "<br>RouteTmp1=".$route_tmp1." ,tmp_route_ci=".$tmp_route_ci;
			if (trim($vehicle_tmp1) > trim($tmp_vehicle_ci))
			{
				$vehicle_tpt[$z + 1] = $vehicle_tpt[$z];				
				$transporter_tpt[$z + 1] = $transporter_tpt[$z];						
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

		$vehicle_tpt[$z + 1] = $tmp_vehicle_ci;		
		$transporter_tpt[$z + 1] = $tmp_tpt_ci;		
	}	
}
	
?> 
