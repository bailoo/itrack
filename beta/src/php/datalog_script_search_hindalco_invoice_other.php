<?php	
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//global $str;
	$strun=$_POST['content'];

	global $ids;
	$ids = $_POST['ids'];	
	//$local_account_id1 = $_POST['local_account_id'];
	//$morFieldsValue1 = explode(",",$_POST['morFieldsValue']);

	$all_vehicles = str_replace('%20',' ',$_POST['all_vehicles']);
	$vehicle_input1 = explode(",",$all_vehicles);
	
	
	$box = $_POST['box'];
	global $parent_admin_id;
	$parent_admin_id = $_POST['parent_admin_id'];	
	global $user_name;

	$morFieldsValue1= explode("/",$vehicle_input1);
	
	echo "search_vehicle_hindalco_other##".$box."##";
	//arraySearch($vehicle_input1,$strun,$morFieldsValue1); // will return mysql
	arraySearch($vehicle_input1,$strun,$vehicle_input1); // will return mysql
	function arraySearch($array,$search,$vehicle_input1) 
	{ 
			//global $str;
			global $ids;
			echo"<table border =\"0\" width=\"100%\">##";
			if(!(preg_match("#/#", $search)))
			{	
				$eflag=0;
				foreach ($array as $a ) 
				{
					//echo "<br>A=".$a."<br>";					
					if(strstr( $a, $search))
					{					
						$country = str_ireplace($str,"<b>".$str."</b>",($a));
						//$country = $a;
						$flag=1;
						for($ui=0;$ui<sizeof($vehicle_input1);$ui++)
						{
							//echo "<br>Country=".$country." ,".$vehicle_input1[$ui]."<br>";
							if(trim($country)==trim($morFieldsValue1[$ui]))
							{														
								$efflag=0;
								$flag=0;
							}					
						}						
						if($flag==1)
						{						
							$eflag=1;
							$common_value = $country.":".$ids ;
							//$common_value = $local_vehicle_id1.":".$country;
							//echo "COMM=".$common_value."<br>";
							echo"<tr id=\"word".$country."\" onmouseover=\"highlight(1,'".$country."');\" onmouseout=\"highlight(0,'".$country."');\" onClick=\"display_hindalco_invoice_other_vehicle('".$common_value."');\">
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
				/*if(strpos($searchString,"@")===0)
				{
					$repeatFlag=1;
					$searchString=substr($searchString,1);
				}*/
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
							/*if(sizeof($str1)==1)
							{
								$inputFDValue=$inputString."@".$country; //FD =>Field Value
							}
							else
							{
								$inputFDValue=$inputString."/@".$country; //FD =>Field Value
							}*/
							$inputFDValue=$inputString."/".$country; //FD =>Field Value
							$common_value = $inputFDValue.":".$ids ;		
							//echo "common_value=".$common_value."<br>";							
							echo"<tr id=\"word".$country."\" onmouseover=\"highlight(1,'".$country."');\" onmouseout=\"highlight(0,'".$country."');\" onClick=\"display_hindalco_invoice_other_vehicle('".$common_value."');\">
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
							/*for($ui=0;$ui<sizeof($vehicle_input1);$ui++)
							{
								//echo "code1=".$country."code2=".$morFieldsValue1[$ui]."<br>";
								if(trim($country)==trim($vehicle_input1[$ui]))
								{
									//echo "<br>Matched";
									$efflag=0;
									$flag=0;
								}					
							}*/
							$inputStringA=explode("/",$inputString);
							foreach($inputStringA as $inputStringB){
								if(trim($country)==trim($inputStringB))
								{
									//echo "<br>Matched";
									$efflag=0;
									$flag=0;
									break;
								}
							}
								
							
							if($flag==1)
							{
							$eflag=1;
							$common_value = $country.":".$ids;
							$inputFDValue=$inputString."/".$country; 
							$common_value = $inputFDValue.":".$ids;
							//echo "COM2=".$common_value;
							echo"<tr id=\"word".$country."\" onmouseover=\"highlight(1,'".$country."');\" onmouseout=\"highlight(0,'".$country."');\" onClick=\"display_hindalco_invoice_other_vehicle('".$common_value."');\">
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
				/*if($eflag==0)
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
				}*/
								
			}			
			echo"</table>";
			return false; 
	}	



	


//############ FUNCTION ROUTE CLOSED ########################
?>
