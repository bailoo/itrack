<?php	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');

	$strun=$_POST['key_vehicle'];
	
	
	
	
	$ids = $_POST['ids'];
	$all_vehicles = str_replace('%20',' ',$_POST['vehicle_list_all']);
	$vehicle_input1 = explode(",",$all_vehicles);

	echo "search_vehicle_raw_milk1##".$ids."##";
	



	arraySearch($vehicle_input1,$strun); // will return mysql
	function arraySearch($array,$search) 
	{ 
			//global $str;
			global $ids;
			echo"<table border =\"0\" width=\"100%\">##";
			//if(!(preg_match("#/#", $search)) && !(preg_match("#@#", $search)))
			//{	
				$eflag=0;
				foreach ($array as $a ) 
				{
					//echo "<br>A=".$a."<br>";					
					if(strstr( $a, $search))
					{					
						$country = str_ireplace($str,"<b>".$str."</b>",($a));
						//$country = $a;
						/*$flag=1;
						for($ui=0;$ui<sizeof($morFieldsValue1);$ui++)
						{
							//echo "<br>Country=".$country." ,".$morFieldsValue1[$ui]."<br>";
							if(trim($country)==trim($morFieldsValue1[$ui]))
							{														
								$efflag=0;
								$flag=0;
							}					
						}						
						if($flag==1)
						{*/						
							$eflag=1;
							$common_value = $country.":".$ids ;
							//$common_value = $local_vehicle_id1.":".$country;
							//echo "COMM=".$common_value."<br>";
							echo"<tr id=\"word".$country."\" onmouseover=\"highlight(1,'".$country."');\" onmouseout=\"highlight(0,'".$country."');\" onClick=\"display_raw_milk('".$common_value."','".$ids."');\">
									\n						
										<td style='font-size:11px;'>
											$country
										</td>
									\n
								</tr>\n";	
						/*}
						else if($efflag!=0)
						{
							$eflag=0;
						}*/					
					}
				}				
				if($eflag==0)
				{					
					echo"No Data Found##";									
				}
			//}
			/*else
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
						//$country = $a;
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
							
							$common_value = $inputFDValue.":".$local_vehicle_id1 ;		
							//echo "common_value=".$common_value."<br>";							
							echo"<tr id=\"word".$country."\" onmouseover=\"highlight(1,'".$country."');\" onmouseout=\"highlight(0,'".$country."');\" onClick=\"display_substation_1('".$common_value."');\">
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
									//echo "<br>Matched";
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
							//echo "COM2=".$common_value;
							echo"<tr id=\"word".$country."\" onmouseover=\"highlight(1,'".$country."');\" onmouseout=\"highlight(0,'".$country."');\" onClick=\"display_substation_1('".$common_value."');\">
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
								
			}	*/		
			echo"</table>";
			//return false; 
	}	


//############ FUNCTION ROUTE CLOSED ########################
?>
