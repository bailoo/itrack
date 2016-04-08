<?php	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//global $str;
	$strun=$_POST['content'];
	$strArr = explode(",",$_POST['content']);	
	//$str=strtolower(
	global $local_vehicle_id1;
	$local_vehicle_id1 = $_POST['local_vehicle_id'];	
	$local_account_id1 = $_POST['local_account_id'];
	$morFieldsValue1 = explode(",",$_POST['morFieldsValue']);	
	//echo "str=".$str;
	echo "search_vehicle1##".$local_vehicle_id1."##";
	function arraySearch($array,$search,$morFieldsValue1) 
		{ 
			//global $str;
			global $local_vehicle_id1;
			echo"<table border =\"0\" width=\"100%\">##";
			if(!(preg_match("#/#", $search)) && !(preg_match("#@#", $search)))
			{	
				$eflag=0;
				foreach ($array as $a) 
				{
					if($search)
					{
						if(strstr( $a, $search))
						{					
							$country = str_ireplace($str,"<b>".$str."</b>",($a));
							$flag=1;
							for($ui=0;$ui<sizeof($morFieldsValue1);$ui++)
							{
								if(trim($country)==trim($morFieldsValue1[$ui]))
								{						
									$efflag=0;
									$flag=0;
								}					
							}						
							if($flag==1)
							{						
								$eflag=1;
								$common_value = $country.":".$local_vehicle_id1 ;								
								echo"<tr id=\"word".$country."\" onmouseover=\"highlight(1,'".$country."');\" onmouseout=\"highlight(0,'".$country."');\" onClick=\"display_1('".$common_value."');\">
										\n						
											<td style='font-size:11px;'>
												$country
											</td>
										\n
									</tr>\n";	
							}
							else if($efflag!=0)
							{
								$eflag=0;
							}					
						}
					}
				}
				//echo "eflag=".$eflag."<br>";
				if($eflag==0)
				{					
					echo"No Data Found##";									
				}
			}
			else
			{
				//echo "in else";
				//echo"search=".$search."<br>";
				$str1=explode("/",$search);
				$inputString="";
				for($ari=0;$ari<sizeof($str1);$ari++)
				{
					if($ari==(sizeof($str1)-1))
					{
						$searchString=$str1[$ari];
					}
					else
					{
						$inputString=$inputString.$str1[$ari]."/";
					}					
				}
				//echo"searchString=".$searchString."<br>";
				$inputString=substr($inputString,0,-1);
				//echo"inputString=".$inputString."<br>";
				//echo "size=".sizeof($str2)."<br>";
				$repeatFlag=0;
				if(strpos($searchString,"@")===0)
				{
					$repeatFlag=1;
					$searchString=substr($searchString,1);
				}
				//echo "searchString=".$searchString."<br>";
				if($searchString=="")
				{
					$eflag=1;
				}
				else
				{
					$eflag=0;
				}
				
				foreach ($array as $a ) 
				{
					if(strstr( $a, $searchString))
					{ 
						$country = str_ireplace($str,"<b>".$str."</b>",($a));
						if($repeatFlag==1)
						{
							$eflag=1;
							if(sizeof($str1)==1)
							{
								$inputFDValue=$inputString."@".$country; //FD =>Field Value
							}
							else
							{
								$inputFDValue=$inputString."/@".$country; //FD =>Field Value
							}
							//echo "country=".$country."<br>";
							$common_value = $inputFDValue.":".$local_vehicle_id1 ;								
							echo"<tr id=\"word".$country."\" onmouseover=\"highlight(1,'".$country."');\" onmouseout=\"highlight(0,'".$country."');\" onClick=\"display_1('".$common_value."');\">
									\n						
										<td style='font-size:11px;'>
											$country
										</td>
									\n
								</tr>\n";														
						}
						else if($repeatFlag==0)
						{
							//echo "in else if<br>";
							$flag=1;
							for($ui=0;$ui<sizeof($morFieldsValue1);$ui++)
							{
								//echo "code1=".$country."code2=".$morFieldsValue1[$ui]."<br>";
								if(trim($country)==trim($morFieldsValue1[$ui]))
								{
									$efflag=0;
									$flag=0;
								}					
							}
							if($flag==1)
							{
							$eflag=1;
							$common_value = $country.":".$local_vehicle_id1;
							$inputFDValue=$inputString."/".$country; 
							$common_value = $inputFDValue.":".$local_vehicle_id1;
							echo"<tr id=\"word".$country."\" onmouseover=\"highlight(1,'".$country."');\" onmouseout=\"highlight(0,'".$country."');\" onClick=\"display_1('".$common_value."');\">
									\n						
										<td style='font-size:11px;'>
											$country
										</td>
									\n
								</tr>\n";
							}
							else if($efflag!=0)
							{
								$eflag=0;
							}
						}
					} 			
				}
				if($eflag==0)
				{
					$str1=explode("/",$search);
					$inputString="";
					for($ari=0;$ari<sizeof($str1);$ari++)
					{
						if($ari<(sizeof($str1)-1))
						{
							$inputString=$inputString.$str1[$ari]."/";	
						}
					}						
					echo"No Data Found Next##".$inputString."##";
				}
								
			}			
			echo"</table>";
			return false; 
		}	

		$route_input = array();		
		get_route_detail($account_id, "ZPME");	
		$route_input1 = array_unique($route_input);
		//arraySearch(array("12345","1500","3400"),$str); // will return mysql
		arraySearch($route_input1,$strun,$morFieldsValue1); // will return mysql

function get_route_detail($account_id, $shift_time)
{
	//echo "\nAccount_id=".$account_id." ,shift_time=".$shift_time;			
	global $route_input;

	//$dir = "c:\\gps_report/231/master";
	//$dir = "c:\\halt2/test_master";
	$dir = "/var/www/html/vts/beta/src/php/gps_report/".$account_id."/master";
	//$dir = "C:\/Program Files/Apache Software Foundation/Apache2.2/htdocs/vts_beta_new/src/php/gps_report/".$account_id."/master";
	//$dir = "C:\\xampp/htdocs/beta/src/php/gps_report/".$account_id."/master";
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
							$route_input[] = $data[6];
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