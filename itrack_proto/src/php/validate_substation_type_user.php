<?php	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//global $str;
	$strun=$_POST['content'];
	$strArr = explode(",",$_POST['content']);	
	//$str=strtolower(
	$account_id = $_POST['local_account_id'];
	
	
	echo "validate_substation_type_user##".$account_id."##";
	function arraySearch($array,$search) 
		{ 
		$flag_search=0;
			foreach ($array as $a ) 
				{
					if($a==$search){
						$flag_search=1;
						break;
						//echo"Validate User";
					}
					else{
						$flag_search=0;
						
					}
				}
				//return false; 
				//echo "No Valid User";
				if($flag_search==0){
			
				echo "No Valid User";
				}
		}	
		
		$route_input = array();		
		get_route_detail($account_id, "ZPME");	
		//$route_input1 = array_unique($route_input);
				
		get_route_detail($account_id, "ZPMM");	
		$route_input1 = array_unique($route_input);

		//print_r($route_input1);
		//arraySearch(array("12345","1500","3400"),$str); // will return mysql
		arraySearch($route_input1,$strun); // will return mysql

function get_route_detail($account_id, $shift_time)
{
	//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;			
	global $route_input;

	//$dir = "c:\\gps_report/231/master";
	//$dir = "c:\\halt2/test_master";
	$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
	//$dir = "/var/www/html/vts/beta/src/php/gps_report/231/master";
	//$dir = "C:\/Program Files/Apache Software Foundation/Apache2.2/htdocs/vts_beta_new/src/php/gps_report/".$account_id."/master";
	$dh = opendir($dir);
	while (($file = readdir($dh)) !== false) {
		//echo "<A HREF=\"$file\">$file</A><BR>\n";
		$file_tmp = explode("#",$file);
		$file_ext = explode(".",$file_tmp[2]);
		if($file_ext[0]!="")
		{			
			//echo "\nfile_ext[0]=".$file_ext[0]." ,shift_time=".$shift_time;
			if( ($file_ext[0] == "4") && ($shift_time=="ZPME") )		//###### EVENING FILE
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
							//$shift_input[] = $data[4];
							$route_input[] = $data[5];
							//$vehicle_input[] = $data[7];
							//$transporter_input[] = $data[8];
							//echo "\nEV:r=".$row." ,data[4]=".$data[4]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[9]=".$data[9];
						}
					}
					fclose($handle);
					//echo "\nsizeof(route_input)=".sizeof($route_input);
				}
			}
			if( ($file_ext[0] == "5") && ($shift_time=="ZPMM") )		//###### EVENING FILE
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
							//$shift_input[] = $data[4];
							$route_input[] = $data[5];
							//$vehicle_input[] = $data[7];
							//$transporter_input[] = $data[8];
							//echo "\nEV:r=".$row." ,data[4]=".$data[4]." ,data[6]=".$data[6]." ,data[7]=".$data[7]." ,data[9]=".$data[9];
						}
					}
					fclose($handle);
					//echo "\nsizeof(route_input)=".sizeof($route_input);
				}
			}
		}  //
	}
	closedir($dh);
} //function closed			

//############ FUNCTION ROUTE CLOSED ########################
?>