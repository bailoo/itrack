<?php
function get_master_detail($account_id, $shift_time)
{
    //echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;
    global $plant_input;
    global $transporter_input_ev;
    global $transporter_input_mor;
    global $route_input_ev;
    global $route_input_mor;
    global $customer_input;
	
	global $plant_input_tst;
	global $route_input_tst;
	global $transporter_input_tst;
	global $customer_name_input_tst;
	global $customer_input_tst;
	

    $abspath = "/var/www/html/vts/beta/src/php/gps_report";
    //$abspath = $pathToRoot."/s3/itrack/gps_report/";
    //$abspath = "C:\\xampp/htdocs/itrackDevelop/beta/src/php/gps_report";	
    $dir = $abspath."/".$account_id."/master";
    $dh = opendir($dir);
    while (($file = readdir($dh)) !== false) 
    {
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
                    /*
                     echo "data[5]=".$data[5] . "\n";
                     echo "data[9]=".$data[9] . "\n";
                    */					
                    if($num<10)
                    {
                        continue;
                    }
                    if($row > 2)
                    {						
                        $plant_input_ev[] = $data[5];
                        $route_input_ev[] = $data[6];
                        $transporter_input_ev[] = $data[8];
                        $customer_name_input_ev[] = $data[9];
                        $customer_input_ev[] = $data[10];																
                        //echo "\nEVE::r=".$row." ,data[5]=".$data[5]." ,data[9]=".$data[9]."\n";
                    } 					
                }
                fclose($handle);
                //echo "\nsizeof(route_input)=".sizeof($route_input);
            }
        }     
        if(($file_ext[0] == "5") && ($shift_time=="ZPMM"))			//###### MORNING FILE
        {
            $path = $dir."/".$file;
            $row = 1;        
            if (($handle = fopen($path, "r")) !== FALSE) 
            {			
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
                {
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
                        $transporter_input_mor[] = $data[8];
                        $customer_name_input_mor[] = $data[9];
                        $customer_input_mor[] = $data[10];
                        //echo "\nMOR::r=".$row." ,data[5]=".$data[5]." ,data[9]=".$data[9]."\n";
                    }											
                }
                fclose($handle);			
            }
        }	
		
		if( ($file_ext[0] == "15") && ($shift_time=="ZPTST") )	
		{		//###### MORNING FILE
			//echo "\nZPTST UPLOAD ERROR";		{

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
						$plant_input_tst[] = $data[5];
						$route_input_tst[] = $data[6];
						$transporter_input_tst[] = $data[8];
						$customer_name_input_mor[] = $data[9];
						$customer_input_tst[] = $data[10];
						//echo "\nMOR::r=".$row." ,data[5]=".$data[5]." ,data[9]=".$data[9]."\n";
					}											
				}
				fclose($handle);			
			}
		}	            		
    }  //
    //echo   
    closedir($dh);
    if($shift_time == "ZPME")
    {		
        $plant_customer_write_path_ev = $abspath."/".$account_id."/master/evening_plant_customer#1#7.csv";	
        unlink($plant_customer_write_path_ev);
        
        $plantEvFileArr=explode("/",$plant_customer_write_path_ev);
        $delPlantEvFile="gps_report/".$account_id."/master/".$plantEvFileArr[sizeof($plantEvFileArr)-1];
        delFile($delPlantEvFile);
        
        //$plant_customer_write_path_ev = "C:\\xampp/htdocs/sorting_motherdairy/evening_plant_customer#1#7.csv";
        sort_station($plant_input_ev, $customer_input_ev, $customer_name_input_ev, $transporter_input_ev, $route_input_ev, $plant_customer_write_path_ev);
    }

    if($shift_time == "ZPMM")
    {
        $plant_customer_write_path_mor = $abspath."/".$account_id."/master/morning_plant_customer#1#8.csv";
        unlink($plant_customer_write_path_mor);
        
        $plantMorFileArr=explode("/",$plant_customer_write_path_mor);
        $delPlantMorFile="gps_report/".$account_id."/master/".$plantMorFileArr[sizeof($plantMorFileArr)-1];
        delFile($delPlantMorFile);
        
        //$plant_customer_write_path_mor = "C:\\xampp/htdocs/sorting_motherdairy/morning_plant_customer#1#8.csv";	
        sort_station($plant_input_mor, $customer_input_mor,  $customer_name_input_mor, $transporter_input_mor, $route_input_mor, $plant_customer_write_path_mor); 
    }
	
	if($shift_time == "ZPTST")
	{
            $plant_customer_write_path_tst = $abspath."/".$account_id."/master/tst_plant_customer#1#18.csv";
            unlink($plant_customer_write_path_tst);

            $plantTstFileArr=explode("/",$plant_customer_write_path_tst);
            $delPlantTstFile="gps_report/".$account_id."/master/".$plantTstFileArr[sizeof($plantTstFileArr)-1];
            delFile($delPlantTstFile);

            //$plant_customer_write_path_mor = "C:\\xampp/htdocs/sorting_motherdairy/morning_plant_customer#1#8.csv";	
			//echo "<br>Plant_input=".sizeof($plant_input_tst)." ,Customer_input=".sizeof($customer_input_tst)$customer_input_tst)." ,Route_input=".sizeof($route_input_tst);
            sort_station($plant_input_tst, $customer_input_tst,$customer_name_input_tst, $transporter_input_tst, $route_input_tst, $plant_customer_write_path_tst); 
	}	
} //function closed
  


function sort_station($plant_input, $customer_input, $customer_name_input, $transporter_input, $route_input, $plant_customer_write_path)
{
    global $account_id;
    //echo "\nSORT STATION=".$plant_customer_write_path." ,size=".sizeof($customer_input)."\n";
    for($x = 1; $x < sizeof($customer_input); $x++) 
    {
		$tmp_customer_input = $customer_input[$x];
		$tmp_customer_name_input = $customer_name_input[$x];
		$tmp_plant_input = $plant_input[$x];   				     				
		$tmp_transporter_input = $transporter_input[$x];
		$tmp_route_input = $route_input[$x];		
		///////////      				

		$z = $x - 1;
		$done = false;
		while($done == false)
		{
			$customer_tmp1 = $customer_input[$z];

			if ($customer_tmp1 >$tmp_customer_input)
			{
				$customer_input[$z + 1] = $customer_input[$z];
				$customer_name_input[$z + 1] = $customer_name_input[$z];
				$plant_input[$z + 1] = $plant_input[$z];			                         			
				$transporter_input[$z + 1] = $transporter_input[$z];
				$route_input[$z + 1] = $route_input[$z];
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
		$customer_name_input[$z + 1] = $tmp_customer_name_input;
		$plant_input[$z + 1] = $tmp_plant_input;
		$transporter_input[$z + 1] = $tmp_transporter_input;
		$route_input[$z + 1] = $tmp_route_input;
                    
	}  // FOR CLOSED        

	
	
	///////////// SORTING CLOSED /////////////////////

	$linetowrite = "Customer,Plant,Transporter,Route,CustomerName";
	$repeated_index = array();
	$repeatflag_plant=false;
	$repeatflag_transporter=false;
	$repeatflag_route=false;
	
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
		$customer_name_string = $customer_name_input[$y];
		$plant_string = $plant_input[$y];
		$transporter_string = $transporter_input[$y];
		$route_string = $route_input[$y];		
	
		for($m=$y+1;$m<sizeof($customer_input);$m++)
		{
			if($customer_input[$y] == $customer_input[$m])
			{
				$repeatflag_plant=false;
				$repeatflag_transporter =false;
				$repeatflag_route =false;
				
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
				}
				if($repeatflag_plant==false)
				{
					$plant_string = $plant_string.'/'.$plant_input[$m];				
				}
				if($repeatflag_transporter==false)
				{
					$transporter_string = $transporter_string.'/'.$transporter_input[$m];				
				}
				if($repeatflag_route==false)
				{
					$route_string = $route_string.'/'.$route_input[$m];				
				}
			}			
			else
			{
				break;
			}
		}
		$y=$m-1;
		//echo "y=".$y."\n";
		
		if($plant_string == "") $plant_string = "-";
		$linetowrite =  $linetowrite."\n"." ".$customer_string.",".$plant_string.",".$transporter_string.",".$route_string.",".$customer_name_string;
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
 
?> 
