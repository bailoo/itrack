<?php 
	include_once('util_session_variable.php');  	   //util_session_variable.php sets values in session
	include_once('util_php_mysql_connectivity.php');   //util_php_mysql_connectivity.php make set connection of user to database  
	$vendor_group_id = trim($_POST['vendor_group_id']);
	$login_id_1 =trim($_POST['user_id']);
	$password_1=md5(trim($_POST['password']));
	$imei_1=trim($_POST['imei']);
	$sync = $_POST['sync'];
	$vendor_group_id="0020";
	$login_id_1="SALESMOTHER";
	$password_1=md5('SALESMOTHER');	
	$imei_1= "123456789123453";		
	$sync = "orderitems";
	$flag=1;
	$in_data=0;
	if($sync=="expense")
	{	
		$flag=0;
	}
	if($flag==1)
	{
		if($sync=="check_connection")
		{
			echo "Success";
		}
		else if($vendor_group_id=="")
		{
			echo "Group Id is NULL. Please Enter Valid Group ID";
		}
		else if($login_id_1=="")
		{
			echo "Login Id is NULL. Please Enter Valid Login ID";
		}
		else if($password_1=="")
		{
			echo "Password is NULL. Please Enter Valid Password";
		}
		else
		{
			$in_data=1;
		}	
	}
	
	if($in_data==1 || $flag==0)
	{
		$query ="SELECT user_id from account where user_id='$login_id_1' AND status=1";  
		$result=mysql_query($query,$DbConnection);
		$row_result1=mysql_num_rows($result);
		if($row_result1==0)
		{
			$message="Login ID Dose Not Exist , ".$login_id_1;
			echo $message;
		}
		else
		{
			$query ="SELECT group_id FROM `group` where group_id='$vendor_group_id' AND status=1";  
			$result=mysql_query($query,$DbConnection);
			$row_result2=mysql_num_rows($result);
			if($row_result1==0)
			{
				$message="Group ID Dose Not Exist , ".$vendor_group_id;
				echo $message;
			}
			else
			{
				function get_sales_id($login_id_1,$vendor_group_id)	
				{
					global $DbConnection;				
					$query ="SELECT account_id from account where user_id='$login_id_1' and group_id='$vendor_group_id' and status=1";  
					$result=mysql_query($query,$DbConnection);
					$row=mysql_fetch_row($result);
					$account_id_local=$row[0];

					$query = "SELECT sales_person_id FROM sales_person WHERE sales_person_id IN (SELECT sales_person_id FROM sales_person_grouping WHERE account_id=$account_id_local AND status=1) AND status=1";
					$result=mysql_query($query,$DbConnection);
					$row=mysql_fetch_row($result);
					return $row[0];				
				}
				function validate_post_variable($validate_post_variable)
				{
					$validate_post_variable1=explode(":",$validate_post_variable);
					
					for($i=0;$i<sizeof($validate_post_variable1);$i++)
					{
						$validate_post_variable2=explode(",",$validate_post_variable1[$i]);
						if($validate_post_variable2[0]=="")
						{							
							$message="Please Enter Valid,".$validate_post_variable2[1].".";							
							return $message;
						}
					}				
				}
				function check_database_availability($database_param)
				{
					global $DbConnection;
					$database_param1=explode(":",$database_param);
					{
						for($i=0;$i<sizeof($database_param1);$i++)
						{
							$database_param2=explode(",",$database_param1[$i]);
							$query="SELECT ".$database_param2[1]." FROM ".$database_param2[2]." WHERE ".$database_param2[1]."=".$database_param2[0];
							$result=mysql_query($query,$DbConnection);
							$num_rows=mysql_num_rows($result);
							if($num_rows==0)
							{
								$message="Not Exist,".$database_param2[1].":".$database_param2[0];
								return $message;
							}
						}
					}				
				}
				
				if($sync=="login")
				{
					$query ="SELECT account_id from account where user_id='$login_id_1' AND password='$password_1' AND group_id='$vendor_group_id' AND status=1";
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$num_rows=mysql_num_rows($result);
					if($num_rows>0)
					{
						$row=mysql_fetch_row($result);
						$account_id_local=$row[0];		
						$query ="SELECT imei_no FROM sales_person WHERE imei_no='$imei_1' AND sales_person_id IN(SELECT sales_person_id FROM sales_person_grouping WHERE account_id=$account_id_local".
								" AND status=1) AND status=1"; 
						//echo "query=".$query."<br>";
						$result=mysql_query($query,$DbConnection);
						$num_row_1=mysql_num_rows($result);
						$row_test=mysql_fetch_row($result);
						if($num_row_1>0)
						{
							echo "success";
						}
						else
						{
							echo "failure";
							//echo "failure if else ,".$row_test[0].",".$query;
						}
					}
					else
					{
						echo "failure in else 2";
					}		
				}
				if($sync=="sync1")
				{
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					//echo "sales_person_id=".$sales_id."<br>";		
					$query_1 = "SELECT * FROM vendor_detail WHERE sales_person_id='$sales_id' AND vendor_id IN(SELECT vendor_id FROM vendor WHERE status=1)";
					//echo "squery=".$query_1;
					$result_1=mysql_query($query_1,$DbConnection);
					$final_string=""; 
					while($row_1=mysql_fetch_object($result_1))
					{
						$vendor_id = ($row_1->vendor_id=="")?'-': $row_1->vendor_id;	
						$vendor_name1=str_replace(",","",$row_1->vendor_name);
						$vendor_name= ($vendor_name1=="")?'-': $vendor_name1;						
						$vendor_type_id = ($row_1->vendor_type_id=="")?'-': $row_1->vendor_type_id;					
						$vendor_admin_id = ($row_1->vendor_admin_id=="")?'-': $row_1->vendor_admin_id;
						$vendor_code = ($row_1->vendor_code=="")?'-': $row_1->vendor_code;						
						$address=str_replace(",","",$row_1->address_1);
						$address_1 = ($address=="")?'-':$address;				
						$route_id = ($row_1->route_id=="")?'-':$row_1->route_id;
						$phone = ($row_1->phone=="")?'-':$row_1->phone;
						$final_string = $final_string.$vendor_id.",".$vendor_name.",".$vendor_type_id.",".$vendor_admin_id.",".$address_1.",".$vendor_code.",".$phone.",".$route_id.":";		    
					}
					echo $final_string; 
				}	
				if($sync=="sync2")         ///////expense type//////////////
				{		
					$query = "SELECT expense_type_id,expense_type_name FROM expense_type WHERE group_id='$vendor_group_id' AND status=1";		
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$expense_type_id[$i]=trim($row->expense_type_id);
						$expense_type_name[$i]=trim($row->expense_type_name);
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$expense_type_name[$j].",".$expense_type_id[$j].":";		       
					}
					echo $final_string;
				}
				if($sync=="subroutes")
				{
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					//echo "sales_person_id=".$sales_id."<br>";		
					$query_1 = "SELECT * FROM vendor_detail WHERE sales_person_id='$sales_id' AND vendor_id IN(SELECT vendor_id FROM vendor WHERE status=1) AND vendor_type_id='1'";
					//echo "squery=".$query_1;
					$result_1=mysql_query($query_1,$DbConnection);
					$final_string=""; 
					while($row_1=mysql_fetch_object($result_1))
					{
						$vendor_id = $row_1->vendor_id;	
						$query_2 = "SELECT route_id,route_name FROM sales_route WHERE route_id IN(SELECT route_id FROM sales_route_assignment WHERE primary_id='$vendor_id' AND status=1)".
								   " AND group_id='$vendor_group_id' AND status=1";
						$result_2=mysql_query($query_2,$DbConnection);
						while($row_2=mysql_fetch_object($result_2))
						{
							$route_id=$row_2->route_id;
							$route_name=$row_2->route_name;				
							$final_string = $final_string.$route_id.",".$route_name.",".$vendor_id.":";		    
						}			
					}
					echo $final_string; 
				}
				if($sync=="country")         ///////expense type//////////////
				{
					//echo "in else";
					$query = "SELECT country_id,country_name FROM country WHERE status=1";
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$country_id[$i]=$row->country_id;
						$country_name[$i]=$row->country_name;
						//echo "country_id=".$country_id[$i]."contry_name=".$country_name[$i];
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$country_id[$j].",".$country_name[$j].":";		       
					}
					echo $final_string;
				}
				if($sync=="orderreports")
				{
					date_default_timezone_set('Asia/Calcutta');
					$StartDate=date("Y-m-d 00:00:00");	
					$EndDate=date("Y-m-d H:i:s");
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					//echo "sales_person_id=".$sales_id."<br>";		
					$query = "SELECT * FROM vendor_detail WHERE sales_person_id='$sales_id' AND vendor_id IN(SELECT vendor_id FROM vendor WHERE status=1)";
					//echo "squery=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$vendor_id=""; 
					while($row=mysql_fetch_object($result))
					{
						$vendor_id=$vendor_id.$row->vendor_id.",";
					}
					$vendor_id=substr($vendor_id,0,-1);
					
					//echo "vendor_id=".$vendor_id."<br>";
					$query = "SELECT DISTINCT vendor_id FROM `order` WHERE vendor_id IN ($vendor_id)  AND order_date BETWEEN '$StartDate' AND '$EndDate' AND status=1";
					//echo "squery=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$final_string="";	
					while($row=mysql_fetch_object($result))
					{
						$query1 = "SELECT order_id FROM `order` WHERE vendor_id=$row->vendor_id AND status=1 AND order_date BETWEEN '$StartDate' AND '$EndDate'";			
						//echo "query=".$query1."<br>";
						$result1=mysql_query($query1,$DbConnection);
						$total_price_str="";
						$total_item_str="";
						while($row1=mysql_fetch_object($result1))
						{
							$query2 = "SELECT order_item,total_price FROM order_detail WHERE order_id='$row1->order_id' AND order_date BETWEEN '$StartDate' AND '$EndDate' AND current_status=1";
							//echo "Query2=".$query2."<br>";
							$result2=mysql_query($query2,$DbConnection);
							$row2=mysql_fetch_row($result2);							
							$order_item=explode(",",$row2[0]);
							//echo "item_size=".sizeof($order_item)."<br>";
							$total_item_str=$total_item_str+(sizeof($order_item));
							//echo "total_item_str=".$total_item_str."<br>";
							$total_price=explode(",",$row2[1]);
							for($i=0;$i<sizeof($total_price);$i++)
							{
								$total_price_str=$total_price_str+$total_price[$i];
								//echo "total_price_str=".$total_price_str."<br>";
							}
						}
						//$str1=$str1.$total_item_str.",".$total_price_str;
						$final_string=$final_string.$row->vendor_id.",".$total_item_str.",".$total_price_str.":";
						//$final_string = $final_string.$row->vendor_id.",".$total_item_str.",".$total_price_str.":";
					}
					echo $final_string;		
				}
				if($sync=="stockreports")
				{
					date_default_timezone_set('Asia/Calcutta');
					$StartDate=date("Y-m-d 00:00:00");	
					$EndDate=date("Y-m-d H:i:s");
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					//echo "sales_person_id=".$sales_id."<br>";		
					$query = "SELECT * FROM vendor_detail WHERE sales_person_id='$sales_id' AND vendor_id IN(SELECT vendor_id FROM vendor WHERE status=1)";
					//echo "squery=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$vendor_id=""; 
					while($row=mysql_fetch_object($result))
					{
						$vendor_id=$vendor_id.$row->vendor_id.",";
					}
					$vendor_id=substr($vendor_id,0,-1);
					
					//echo "vendor_id=".$vendor_id."<br>";
					$query = "SELECT DISTINCT vendor_id FROM `stock` WHERE vendor_id IN ($vendor_id)  AND stock_date BETWEEN '$StartDate' AND '$EndDate' AND status=1";
					//echo "squery=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$final_string="";	
					while($row=mysql_fetch_object($result))
					{
						$query1 = "SELECT stock_id FROM `stock` WHERE vendor_id=$row->vendor_id AND status=1 AND stock_date BETWEEN '$StartDate' AND '$EndDate'";			
						//echo "query=".$query1."<br>";
						$result1=mysql_query($query1,$DbConnection);
						$total_price_str="";
						$total_item_str="";
						while($row1=mysql_fetch_object($result1))
						{
							$query2 = "SELECT stock_item,total_price FROM stock_detail WHERE stock_id='$row1->stock_id' AND stock_date BETWEEN '$StartDate' AND '$EndDate' AND current_status=1";
							//echo "Query2=".$query2."<br>";
							$result2=mysql_query($query2,$DbConnection);
							$row2=mysql_fetch_row($result2);							
							$stock_item=explode(",",$row2[0]);
							//echo "item_size=".sizeof($order_item)."<br>";
							$total_item_str=$total_item_str+(sizeof($stock_item));
							//echo "total_item_str=".$total_item_str."<br>";
							$total_price=explode(",",$row2[1]);
							for($i=0;$i<sizeof($total_price);$i++)
							{
								$total_price_str=$total_price_str+$total_price[$i];
								//echo "total_price_str=".$total_price_str."<br>";
							}
						}
						//$str1=$str1.$total_item_str.",".$total_price_str;
						$final_string=$final_string.$row->vendor_id.",".$total_item_str.",".$total_price_str.":";
						//$final_string = $final_string.$row->vendor_id.",".$total_item_str.",".$total_price_str.":";
					}
					echo $final_string;		
				}
				if($sync=="orderdetails")
				{
					date_default_timezone_set('Asia/Calcutta');
					$StartDate=date("Y-m-d 00:00:00");	
					$EndDate=date("Y-m-d H:i:s");
					/*$StartDate=date("2011_12_28 00:00:00");	
					$EndDate=date("2011_12_28 23:59:59");*/
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					//echo "sales_person_id=".$sales_id."<br>";		
					$query = "SELECT * FROM vendor_detail WHERE sales_person_id='$sales_id' AND vendor_id IN(SELECT vendor_id FROM vendor WHERE status=1)";
					//echo "squery=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$vendor_id=""; 
					while($row=mysql_fetch_object($result))
					{
						$vendor_id=$vendor_id.$row->vendor_id.",";
					}
					$vendor_id=substr($vendor_id,0,-1);
					
					//echo "vendor_id=".$vendor_id."<br>";
					$query = "SELECT DISTINCT vendor_id FROM `order` WHERE vendor_id IN ($vendor_id)  AND order_date BETWEEN '$StartDate' AND '$EndDate' AND status=1";
					//echo "squery=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$final_string="";	
					while($row=mysql_fetch_object($result))
					{
						$query1 = "SELECT vendor_name FROM vendor_detail WHERE vendor_id=$row->vendor_id AND vendor_id IN (SELECT vendor_id FROM vendor WHERE vendor_id='$row->vendor_id'".
								  " AND status=1)";			
						//echo "query=".$query1."<br>";
						$result1=mysql_query($query1,$DbConnection);
						$row1=mysql_fetch_row($result1);
						$vendor_name=$row1[0];
						$query1 = "SELECT order_id FROM `order` WHERE vendor_id=$row->vendor_id AND status=1 AND order_date BETWEEN '$StartDate' AND '$EndDate'";			
						//echo "query=".$query1."<br><br><br>";
						$result1=mysql_query($query1,$DbConnection);
						$total_price_str="";
						$total_item_str="";
						while($row1=mysql_fetch_object($result1))
						{
							$query2 = "SELECT order_item,order_qty,uom_id,unit_price FROM order_detail WHERE order_id='$row1->order_id' AND order_date BETWEEN '$StartDate' AND '$EndDate' AND current_status=1";
							//echo "Query2=".$query2."<br>";
							$result2=mysql_query($query2,$DbConnection);
							$row2=mysql_fetch_row($result2);							
							$order_item=explode(",",$row2[0]);
							$order_qty=explode(",",$row2[1]);
							$uom_id=explode(",",$row2[2]);
							$unit_price=explode(",",$row2[3]);				
							for($i=0;$i<sizeof($order_item);$i++)
							{
								$item_query = "SELECT item_name FROM item_detail WHERE item_code='$order_item[$i]' AND status=1";
								//echo "Query2=".$item_query."<br>";
								$item_result=mysql_query($item_query,$DbConnection);
								$item_row1=mysql_fetch_row($item_result);
								$item_name=$item_row1[0];			
								
								$uom_query = "SELECT uom_name FROM unit_of_measure WHERE uom_id='$uom_id[$i]' AND status=1";
								//echo "Query2=".$uom_query."<br>";
								$uom_result=mysql_query($uom_query,$DbConnection);
								$uom_row1=mysql_fetch_row($uom_result);
								$uom_name=$uom_row1[0];
								//$total_price_str=$total_price_str+$total_price[$i];
								//echo "total_price_str=".$total_price_str."<br>";
								$final_string=$final_string.$vendor_name.",".$item_name.",".$order_qty[$i].",".$uom_name.",".$unit_price[$i].":";
							}
						}		
					}
					echo $final_string;		
				}
				if($sync=="stockdetails")
				{
					date_default_timezone_set('Asia/Calcutta');
					$StartDate=date("Y-m-d 00:00:00");	
					$EndDate=date("Y-m-d H:i:s");
					/*$StartDate=date("2011_12_28 00:00:00");	
					$EndDate=date("2011_12_28 23:59:59");*/
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					//echo "sales_person_id=".$sales_id."<br>";		
					$query = "SELECT * FROM vendor_detail WHERE sales_person_id='$sales_id' AND vendor_id IN(SELECT vendor_id FROM vendor WHERE status=1)";
					//echo "squery=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$vendor_id=""; 
					while($row=mysql_fetch_object($result))
					{
						$vendor_id=$vendor_id.$row->vendor_id.",";
					}
					$vendor_id=substr($vendor_id,0,-1);
					
					//echo "vendor_id=".$vendor_id."<br>";
					$query = "SELECT DISTINCT vendor_id FROM `stock` WHERE vendor_id IN ($vendor_id)  AND stock_date BETWEEN '$StartDate' AND '$EndDate' AND status=1";
					//echo "squery=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$final_string="";	
					while($row=mysql_fetch_object($result))
					{
						$query1 = "SELECT vendor_name FROM vendor_detail WHERE vendor_id=$row->vendor_id AND vendor_id IN (SELECT vendor_id FROM vendor WHERE vendor_id='$row->vendor_id'".
								  " AND status=1)";			
						//echo "query=".$query1."<br>";
						$result1=mysql_query($query1,$DbConnection);
						$row1=mysql_fetch_row($result1);
						$vendor_name=$row1[0];
						$query1 = "SELECT stock_id FROM `stock` WHERE vendor_id=$row->vendor_id AND status=1 AND stock_date BETWEEN '$StartDate' AND '$EndDate'";			
						//echo "query=".$query1."<br><br><br>";
						$result1=mysql_query($query1,$DbConnection);
						$total_price_str="";
						$total_item_str="";
						while($row1=mysql_fetch_object($result1))
						{
							$query2 = "SELECT stock_item,stock_qty,uom_id,unit_price FROM stock_detail WHERE stock_id='$row1->stock_id' AND stock_date BETWEEN '$StartDate' AND '$EndDate' AND current_status=1";
							//echo "Query2=".$query2."<br>";
							$result2=mysql_query($query2,$DbConnection);
							$row2=mysql_fetch_row($result2);							
							$stock_item=explode(",",$row2[0]);
							$stock_qty=explode(",",$row2[1]);
							$uom_id=explode(",",$row2[2]);
							$unit_price=explode(",",$row2[3]);				
							for($i=0;$i<sizeof($stock_item);$i++)
							{
								$item_query = "SELECT item_name FROM item_detail WHERE item_code='$stock_item[$i]' AND status=1";
								//echo "Query2=".$item_query."<br>";
								$item_result=mysql_query($item_query,$DbConnection);
								$item_row1=mysql_fetch_row($item_result);
								$item_name=$item_row1[0];			
								
								$uom_query = "SELECT uom_name FROM unit_of_measure WHERE uom_id='$uom_id[$i]' AND status=1";
								//echo "Query2=".$uom_query."<br>";
								$uom_result=mysql_query($uom_query,$DbConnection);
								$uom_row1=mysql_fetch_row($uom_result);
								$uom_name=$uom_row1[0];
								//$total_price_str=$total_price_str+$total_price[$i];
								//echo "total_price_str=".$total_price_str."<br>";
								$final_string=$final_string.$vendor_name.",".$item_name.",".$stock_qty[$i].",".$uom_name.",".$unit_price[$i].":";
							}
						}		
					}
					echo $final_string;		
				}
				if($sync=="state")         ///////expense type//////////////
				{
					$query = "SELECT state_id,state_name FROM state WHERE status=1";    
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$state_id[$i]=$row->state_id;
						$state_name[$i]=$row->state_name;
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$state_id[$j].",".$state_name[$j].":";		       
					}
					echo $final_string;
				}
				if($sync=="territory")         ///////expense type//////////////
				{
					$query = "SELECT territory_id,territory_name FROM territory WHERE group_id='$vendor_group_id' AND status=1";	
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$territory_id[$i]=$row->territory_id;
						$territory_name[$i]=$row->territory_name;
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$territory_id[$j].",".$territory_name[$j].":";		       
					}
					echo $final_string;
				}
				
				if($sync=="territoryassignment")         ///////expense type//////////////
				{
					$query = "SELECT state_id,territory_id FROM territory_assignment WHERE territory_id IN (SELECT territory_id FROM territory WHERE group_id='$vendor_group_id' AND status=1) AND status=1";    
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$territory_id[$i]=$row->territory_id;
						$state_id[$i]=$row->state_id;
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$territory_id[$j].",".$state_id[$j].":";		       
					}
					echo $final_string;
				}
				
				if($sync=="stateassignment")         ///////expense type//////////////
				{
					$query ="SELECT state_id,country_id FROM state_assignment WHERE status=1";    
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$country_id[$i]=$row->country_id;
						$state_id[$i]=$row->state_id;
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$state_id[$j].",".$country_id[$j].":";		       
					}
					echo $final_string;
				}
				
				if($sync=="uom")         ///////expense type//////////////
				{
					$query = "SELECT uom_id,uom_name FROM unit_of_measure WHERE group_id='$vendor_group_id' AND status=1";    
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$uom_id[$i]=$row->uom_id;
						$uom_name[$i]=$row->uom_name;
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$uom_id[$j].",".$uom_name[$j].":";		       
					}
					echo $final_string;
				}
				if($sync=="product")         ///////expense type//////////////
				{
					$query = "SELECT product_id,product_name,product_code FROM product_category WHERE group_id='$vendor_group_id' AND status=1";    
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$product_id[$i]=$row->product_id;
						$product_name[$i]=$row->product_name;
						$product_code[$i]=$row->product_code;
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$product_id[$j].",".$product_name[$j].",".$product_code[$j].":";		       
					}
					echo $final_string;
				}
				if($sync=="uom_vendor")         ///////expense type//////////////
				{
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					$query = "SELECT * FROM vendor_detail WHERE sales_person_id=$sales_id AND vendor_id IN(SELECT vendor_id FROM vendor WHERE status=1)";
					//echo"query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$vendors_str="";
					while($row=mysql_fetch_object($result))
					{
						$vendors_str=$vendors_str.$row->vendor_id.",";
					}
					$vendors_str1=substr($vendors_str,0,-1);
					
					$query = "SELECT vendor_id,uom_id FROM uom_vendor_assignment WHERE vendor_id IN ($vendors_str1) AND status=1";  
					//echo"query=".$query."<br>";		
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$vendor_id[$i]=$row->vendor_id;
						$uom_id[$i]=$row->uom_id;		
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$uom_id1=explode(",",$uom_id[$j]);
						for($k=0;$k<sizeof($uom_id1);$k++)
						{
							$final_string = $final_string.$vendor_id[$j].",".$uom_id1[$k].":";
						}					       
					}
					echo $final_string;
				}	
				if($sync=="route")
				{
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					$date1=explode(" ",$date);
					$query = "SELECT route_id FROM day_plan WHERE sales_person_id='$sales_id' AND status=1 AND DATE_FORMAT(create_date,'%Y-%m-%d')='$date1[0]'"; 
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					/*$row=mysql_fetch_row($result);
					$route_id=$row[0];*/
					$final_string="";
					while($row=mysql_fetch_object($result))
					{
						$query_1 = "SELECT route_name FROM sales_route WHERE route_id=$row->route_id AND status=1"; 
						//echo "query=".$query_1."<br>";
						$result_1=mysql_query($query_1,$DbConnection);
						$row_1=mysql_fetch_row($result_1);
						$route_name=$row_1[0];		
						$final_string = $final_string.$route_name.",:";
					}
					echo $final_string;
					//echo $final_string;
				}  
				if($sync=="sync3")         ///////call type//////////////
				{
					$query = "SELECT call_type_id,call_type_name FROM call_type WHERE group_id='$vendor_group_id' AND status=1";   
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$call_type_id[$i]=$row->call_type_id;
						$call_type_name[$i]=$row->call_type_name;
						$i++;
					} 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$call_type_name[$j].",".$call_type_id[$j].":";		       
					}
					echo $final_string;
				}
			  
				if($sync=="item")         ///////expense //////////////
				{
					$query="SELECT * FROM item_detail WHERE group_id='$vendor_group_id' AND status=1";
					//echo "query=".$query."<br>";
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$item_id[$i]=$row->item_id;
						$item_name[$i]=$row->item_name;
						$item_code[$i]=$row->item_code;
						$product_id[$i]=$row->product_id;
						$i++;
					}	 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$final_string = $final_string.$item_id[$j].",".$item_name[$j].",".$item_code[$j].",".$product_id[$j].":";		       
					}
					echo $final_string;
				}
				
				if($sync=="price")         ///////expense //////////////
				{
					$query="SELECT * FROM item_price WHERE group_id='$vendor_group_id' AND status=1";
					$result=mysql_query($query,$DbConnection);
					$i=0;
					while($row=mysql_fetch_object($result))
					{
						$item_code[$i]=$row->item_code;
						$uom_id[$i]=$row->uom_id;
						$unit_price[$i]=$row->unit_price;		
						$i++;
					}	 	
					$final_string="";    
					for($j=0;$j<$i;$j++)
					{
						$uom_id1=explode(",",$uom_id[$j]);
						$unit_price1=explode(",",$unit_price[$j]);
						for($k=0;$k<sizeof($uom_id1);$k++)
						{
							$final_string = $final_string.$item_code[$j].",".$uom_id1[$k].",".$unit_price1[$k].":";
						}
					}
					echo $final_string;
				}
				if($sync=="routeplan")
				{
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					/*$route="KARTAR AGENCY";
					$lat_lon_cellID="12.12,26.12,20003";		
					$lat_lan_cell_id=explode(",",$lat_lon_cellID);*/
					if($route=="")	
					{
						echo "Failure";		
					}
					else
					{
						$query="SELECT route_id from sales_route WHERE route_name='$route' AND group_id='$vendor_group_id' AND status=1";
						//echo "query=".$query."<br>";
						$result=mysql_query($query,$DbConnection);
						$row=mysql_fetch_row($result);
						$route_id=$row[0];
						$date1=explode(" ",$date);
						//$query="SELECT route_id FROM finalise_day_plan WHERE route_id=$route_id AND sales_person_id='$sales_id' AND DATE_FORMAT(create_date,'$date1[0]') AND status=1";
						$query="SELECT route_id FROM finalise_day_plan WHERE route_id=$route_id AND sales_person_id='$sales_id' AND status=1 order by create_date DESC";
						//echo "query=".$query."<br>";
						$result=mysql_query($query,$DbConnection);
						$row_result=mysql_num_rows($result);
						if($row_result>0)
						{
							echo "Already Exist";
						}
						else
						{
							$query="INSERT INTO finalise_day_plan(sales_person_id,route_id,status,create_id,create_date,latitude,longitude,cell_id) VALUES".
									"('$sales_id','$route_id','1','1','$date','$lat_lan_cell_id[0]','$lat_lan_cell_id[1]','$lat_lan_cell_id[2]')";
							//echo "query=".$query."<br>";
							$result=mysql_query($query,$DbConnection);		
							if($result)
							{
								echo "Success";
							}
							else
							{
								echo "Failure";
							}
						}
					}
				}
			  
				if($sync=="orderitems")         ///////expense //////////////
				{
					echo "in if";
					$order_items="1000GMX100 ABC1,1,CART,1000:100GMX50 ABC,5,CART,1000:500GMX50 ABC3,2,CART,1250:";
					$vid="181";
					$lat_lon_cellID="0.0,0.0,2341";
					$vtype="1";
					$transactionid="1340022746";

					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					if($transactionid=="")
					{			
						echo "Data can not be inserted cause transaction_id is Null";		
					}		
					/*else if($lat_lon_cellID=="")
					{
						echo "Data can not be inserted cause lat_lon_cellID is Null";
					}*/
					else if($vid=="")
					{
						echo "Data can not be inserted cause vendor_id is Null";
					}
					else if($order_items=="")
					{
						echo "Data can not be inserted cause order_items is Null";
					}
					else
					{
						$transaction_date=date("Y-m-d", $transactionid);
						$transaction_date1=explode('-',$transaction_date);
						$system_date1=explode(' ',$date);
						$system_date1=explode('-',$system_date1[0]);
						//echo "trannsaction_year=".$transaction_date1[0]."system_date=".$system_date1[0]."<br>";
						if($transaction_date1[0]!=$system_date1[0])
						{
							echo "Transaction ID does not belong to Current Year.";							
						}
						else if($transaction_date1[1]!=$system_date1[1])
						{
							echo "Transaction ID also does not belong to Current Month";
						}
						else
						{
							$query="SELECT vendor_id FROM vendor_detail WHERE vendor_id=$vid AND vendor_id IN(SELECT vendor_id FROM vendor WHERE status=1)";
							$result=mysql_query($query,$DbConnection);
							$num_rows=mysql_num_rows($result);
							if($num_rows==0)
							{
									echo "Vendor ID Does Not Exist";
							}
							else
							{			
								$query="SELECT transaction_id,order_id FROM order_detail WHERE transaction_id='$transactionid' AND order_id IN (SELECT order_id FROM `order` WHERE".
									   " vendor_id='$vid' AND status=1) AND current_status=1";
								//	echo"qyery=".$query."<br>";				   
								$result=mysql_query($query,$DbConnection);
								$row_result=mysql_num_rows($result);
								$row_order_id=mysql_fetch_row($result);
								if($row_result>0)
								{
									//echo "in if";
									echo"already added,".$row_order_id[1];
								}
								else
								{
									$query="SELECT vendor_admin_id,territory_id FROM vendor_detail WHERE vendor_id='$vid' AND vendor_id IN (SELECT vendor_id FROM vendor WHERE".
										   " vendor_id='$vid' AND status=1)";
									//echo"query1=".$query."<br>";
									$result=mysql_query($query,$DbConnection);				
									$row=mysql_fetch_row($result);
									$ref_id=$row[0];
									if($vtype=="1" || $vtype=="3")
									{
										$ref_type_id="0";
										$ref_id=$sales_id;
									}
									else
									{
										$ref_type_id="1";
									}								
									//$territory_id=$row[1];				
									/*$query="SELECT territory_code FROM territory WHERE territory_id='$territory_id' AND group_id=$vendor_group_id AND status=1";
									//echo"query2=".$query."<br>";
									$result=mysql_query($query,$DbConnection);				
									$row=mysql_fetch_row($result);		
									$territory_code=$row[0];*/				
									$query="SELECT order_id FROM `order` WHERE sno =(SELECT MAX(sno) FROM `order` WHERE order_id LIKE '%$login_id_1%' AND vendor_type_id='$vtype' AND status=1) AND status=1";
									$result=mysql_query($query,$DbConnection);				
									$row=mysql_fetch_row($result);		
									//$serial=$row[0]+1;
									if($row[0]=="")
									{
										$serial=1;
									}
									else
									{
										$serial=explode("/",$row[0]);
										$serial=$serial[3]+1;
									}
									
									$date_format="2012-2013";				
									if($vtype==1 || $vtype==3)
									{								
										if($vtype==1)
										{								
											$order_id="PR/".$date_format."/".$login_id_1."/".$serial;											
										}
										else
										{
											$order_id="CO/".$date_format."/".$login_id_1."/".$serial;			
										}												
									}	
									else
									{
										$order_id="SE/".$date_format."/".$login_id_1."/".$serial;
									}
									//echo "success,".$order_id; 
									$query="SELECT order_id FROM `order` WHERE order_id='$order_id' AND status=1";
									$result=mysql_query($query,$DbConnection);				
									$num_rows=mysql_num_rows($result);
									if($num_rows>0)
									{
										echo "Order ID Already Exist,".$order_id;
									}
									else
									{
										$item_code="";
										$item_qty="";			
										$uom_id="";
										$item_price="";
										$total_price="";
										$order_items=explode(":",$order_items);
										//echo"size_of_order=".sizeof($order_items)."<br>";
										for($i=0;$i<sizeof($order_items)-1;$i++)
										{							
											$item_detail=explode(",",$order_items[$i]);
											if($item_detail[0]=="")
											{
												echo "Product Name Is NULL";
												$flag=0;
												break;
											}
											else if($item_detail[1]=="")
											{
												echo "Item Quantity IS NULL";
												$flag=0;
												break;
											}
											else if($item_detail[2]=="")
											{
												echo "UOM Name IS NULL";
												$flag=0;
												break;
											}
											else if($item_detail[3]=="")
											{
												echo "Item Price IS NULL";
												$flag=0;
												break;
											}
											else
											{
												$flag=1;
												$item_name=trim($item_detail[0]);							
												$query="SELECT item_code FROM item_detail WHERE item_name='$item_name' AND group_id=$vendor_group_id AND status=1";
												//echo"query2=".$query."<br>";
												$result=mysql_query($query,$DbConnection);
												$item_code1=mysql_fetch_row($result);
												$item_code=$item_code.$item_code1[0].",";
												$item_qty=$item_qty.$item_detail[1].",";
												
												$uom_query = "SELECT uom_id FROM unit_of_measure WHERE uom_name='$item_detail[2]' AND status=1";
												//echo "Query2=".$uom_query."<br>";
												$uom_result=mysql_query($uom_query,$DbConnection);
												$uom_row1=mysql_fetch_row($uom_result);
												$query_uom_id=$uom_row1[0];				
												$uom_id=$uom_id.$query_uom_id.",";
												
												$item_price=$item_price.$item_detail[3].",";					
												$total_price=$total_price.($item_detail[1]*$item_detail[3]).",";						
											}
										}
										if($flag==1)
										{
											$item_code=substr($item_code,0,-1);						
											$item_qty=substr($item_qty,0,-1);
											$uom_id=substr($uom_id,0,-1);
											$item_price=substr($item_price,0,-1);
											$total_price=substr($total_price,0,-1);
											$lat_long_cell_id=explode(",",$lat_lon_cellID);	
											
											
											
											$query1 = "INSERT INTO order_detail(order_id,transaction_id,product_id,uom_id,order_item,order_qty,unit_price,order_date,latitude,longitude,".
													  " cell_id,total_price,current_status,create_id,remark) VALUES ('$order_id','$transactionid','$product_id','$uom_id','$item_code'".
													  ",'$item_qty','$item_price','$date','$lat_long_cell_id[0]','$lat_long_cell_id[1]','$lat_long_cell_id[2]','$total_price','1','1','$remark')";
											echo "query1=".$query1."<br>";
											$result1=mysql_query($query1,$DbConnection);    
											$query2 = "INSERT INTO `order`(order_id,vendor_id,vendor_type_id,group_id,ref_id,ref_type_id,order_date,status) VALUES('$order_id','$vid','$vtype','$vendor_group_id' ,'$ref_id','$ref_type_id','$date','1')";  
											echo "query2=".$query2."<br>";
											$result2=mysql_query($query2,$DbConnection);   
											if($result1 && $result2)
											{
												echo "success,".$order_id; 
											} 
											else
											{
												echo "failure";
											}
										}
									}
								}
							}
						}
					}	
				}
				
				if($sync=="stockitems")         ///////expense //////////////
				{				
					if($transactionid=="")
					{			
						echo "Data can not be inserted cause transaction_id is Null";		
					}		
					/*else if($lat_lon_cellID=="")
					{
						echo "Data can not be inserted cause lat_lon_cellID is Null";
					}*/
					else if($vid=="")
					{
						echo "Data can not be inserted cause vendor_id is Null";
					}
					else if($stock_items=="")
					{
						echo "Data can not be inserted cause stock_items is Null";
					}
					else
					{
						$transaction_date=date("Y-m-d", $transactionid);
						$transaction_date1=explode('-',$transaction_date);
						$system_date1=explode(' ',$date);
						$system_date1=explode('-',$system_date1[0]);
						//echo "trannsaction_year=".$transaction_date1[0]."system_date=".$system_date1[0]."<br>";
						if($transaction_date1[0]!=$system_date1[0])
						{
							echo "Transaction ID does not belong to Current Year.";							
						}
						else if($transaction_date1[1]!=$system_date1[1])
						{
							echo "Transaction ID also does not belong to Current Month";
						}
						else
						{
							$query="SELECT transaction_id FROM stock_detail WHERE transaction_id='$transactionid' AND stock_id IN (SELECT stock_id FROM `stock` WHERE".
								   " vendor_id='$vid' AND status=1) AND current_status=1";
							//echo"qyery=".$query."<br>";				   
							$result=mysql_query($query,$DbConnection);
							$row_result=mysql_num_rows($result);
							if($row_result>0)
							{
								echo"already added";
							}
							else
							{
								//echo "in else";
								$query="SELECT vendor_admin_id,territory_id FROM vendor_detail WHERE vendor_id='$vid' AND vendor_id IN (SELECT vendor_id FROM vendor WHERE".
									   " vendor_id='$vid' AND status=1)";
								//echo"query1=".$query."<br>";
								$result=mysql_query($query,$DbConnection);				
								$row=mysql_fetch_row($result);
								$refid=$row[0];
								$refid=$row[0];			
								$reftypeid="1";
								$territory_id=$row[1];
								//echo "territoty_id=".$territory_id."<br>";
						
								$query="SELECT territory_code FROM territory WHERE territory_id='$territory_id' AND group_id=$vendor_group_id AND status=1";
								///echo"query2=".$query."<br>";
								$result=mysql_query($query,$DbConnection);				
								$row=mysql_fetch_row($result);		
								$territory_code=$row[0];
								//echo "territory_code=".$territory_code."<br>";
								//$query="SELECT max(sno) as serial FROM stock_detail";
								$query="SELECT stock_id FROM `stock` WHERE sno =(SELECT MAX(sno) FROM `stock` WHERE stock_id LIKE '%$login_id_1%' AND vendor_type_id='$vtype' AND status=1) AND status=1";
								$result=mysql_query($query);				
								$row=mysql_fetch_row($result);		
								if($row[0]=="")
								{
									$serial=1;
								}
								else
								{
									$serial=explode("/",$row[0]);
									$serial=$serial[3]+1;
								}								
								$date_format="2012-2013";								
								if($vtype==1 || $vtype==3)
								{				
									if($vtype==1)
									{
										//$stock_id="PR/".$date_format."/".$territory_code."/".$serial;	
										$stock_id="PR/".$date_format."/".$login_id_1."/".$serial;													
									}
									else
									{
										//$stock_id="CO/".$date_format."/".$territory_code."/".$serial;	
										$stock_id="CO/".$date_format."/".$login_id_1."/".$serial;													
									}
									$refid="0";
									$reftypeid="0";			
								}	
								else
								{
									//$stock_id="SE/".$date_format."/".$territory_code."/".$serial;
									$stock_id="SE/".$date_format."/".$login_id_1."/".$serial;
								}
								//echo "stock_id=".$stock_id."<br>";
								$item_code="";
								$item_qty="";			
								$uom_id="";
								$item_price="";
								$total_price="";
								$stock_items=explode(":",$stock_items);
								//echo"size_of_order=".sizeof($order_items)."<br>";
								for($i=0;$i<sizeof($stock_items)-1;$i++)
								{
									$item_detail=explode(",",$stock_items[$i]);
									$query="SELECT item_code FROM item_detail WHERE item_name='$item_detail[0]' AND group_id=$vendor_group_id AND status=1";
									//echo"query2=".$query."<br>";
									$result=mysql_query($query);
									$item_code1=mysql_fetch_row($result);
									$item_code=$item_code.$item_code1[0].",";
									$item_qty=$item_qty.$item_detail[1].",";
									
									$uom_query = "SELECT uom_id FROM unit_of_measure WHERE uom_name='$item_detail[2]' AND status=1";
									//echo "Query2=".$uom_query."<br>";
									$uom_result=mysql_query($uom_query,$DbConnection);
									$uom_row1=mysql_fetch_row($uom_result);
									$query_uom_id=$uom_row1[0];				
									$uom_id=$uom_id.$query_uom_id.",";
									
									$item_price=$item_price.$item_detail[3].",";					
									$total_price=$total_price.($item_detail[1]*$item_detail[3]).",";			
								} 
								$item_code=substr($item_code,0,-1);
								$item_qty=substr($item_qty,0,-1);
								$uom_id=substr($uom_id,0,-1);
								$item_price=substr($item_price,0,-1);
								$total_price=substr($total_price,0,-1);		

								//echo "item_code=".$item_code."<br>item_qty=".$item_qty."<br>uom_id=".$uom_id."<br>item_price=".$item_price."<br>total_price=".$total_price."<br>order_code=".$order_id;
								$query1 = "INSERT INTO stock_detail(stock_id,transaction_id,product_id,uom_id,stock_item,stock_qty,unit_price,stock_date,latitude,longitude,".
										  " cell_id,total_price,current_status,create_id) VALUES ('$stock_id','$transactionid','$product_id','$uom_id','$item_code'".
										  ",'$item_qty','$item_price','$date','$lat_lan_cell_id[0]','$lat_lan_cell_id[1]','$lat_lan_cell_id[2]','$total_price','1','1')";
								//echo "query1=".$query1."<br>";
								$result1=mysql_query($query1,$DbConnection);    
								$query2 = "INSERT INTO `stock`(stock_id,vendor_id,vendor_type_id,group_id,ref_id,ref_type_id,stock_date,status) VALUES('$stock_id','$vid','$vtype','$vendor_group_id' ,'$ref_id','$ref_type_id','$date','1')";  
								//echo "query2=".$query2."<br>";
								$result2=mysql_query($query2,$DbConnection);   
								if($result1 && $result2)
								{
									//echo "stock_id_1=".$stock_id."<br>";
									echo "success,".$stock_id; 
								} 
								else
								{
									echo "failure";
								}
							}
						}
					}
				}
				if($sync=="expense")         ///////expense //////////////
				{
					$lat_lon_cell_id=explode(",",trim($_POST['lat_lon_cellID']));
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);
					$expensetypeid_and_amount1=$_POST['exp_amount'];
					//echo $expensetypeid_and_amount1;
					if($expensetypeid_and_amount1=="0" || $expensetypeid_and_amount1=="")
					{	
						echo "Failure";
					}
					else
					{
						$expensetypeid_and_amount2=explode(":",$expensetypeid_and_amount1);		
						$remark1=$_POST['remark'];
						$query1="INSERT INTO expense_detail(sales_person_id,expense_type_id,expense_amount,latitude,longitude,cell_id,remark,status,create_id,create_date) VALUES";
						for($i=0;$i<sizeof($expensetypeid_and_amount2)-1;$i++)
						{
							$expensetypeid_and_amount3=explode(",",$expensetypeid_and_amount2[$i]); ///$amount_with_salesid3[0]=expense_type_id , $amount_with_salesid3[0]=expense_amount
							$query="SELECT expense_type_id FROM expense_type WHERE expense_type_name='$expensetypeid_and_amount3[0]' AND status=1";
							$result=mysql_query($query,$DbConnection);  
							$row=mysql_fetch_row($result);			
							if($i==sizeof($expensetypeid_and_amount2)-2)
							{
								$query2.="('$sales_id' , '$row[0]' , '$expensetypeid_and_amount3[1]' ,'$lat_lon_cell_id[0]','$lat_lon_cell_id[1]','$lat_lon_cell_id[2]', '$remark1',  '1' , '5' , '$date')";
							}
							else
							{
								$query2.="('$sales_id' , '$row[0]' , '$expensetypeid_and_amount3[1]' ,'$lat_lon_cell_id[0]','$lat_lon_cell_id[1]','$lat_lon_cell_id[2]', '$remark1',  '1' , '5' , '$date'),";
							}		
						}
						$final_query=$query1.$query2;		
						//echo "query=".$final_query."<br>";
						$result=mysql_query($final_query,$DbConnection);                	  
						if($result)
						{
							echo "success";
						}
						else
						{
							echo "Failure";
						}
					}
				}
				if($sync=="callreport")         ///////expense //////////////
				{
					$call_report=substr($_POST['call_report'],0,-1);
					$call_report1=explode(",",$call_report);
					$vendorname=$call_report1[0];
					$callreportname=$call_report1[1];
					$lat_long_cellID=explode(",",$lat_lon_cellID);
					$remark = $_POST['remark'];	
					$sales_id=get_sales_id($login_id_1,$vendor_group_id);	 

					$query="SELECT vendor_id FROM vendor_detail WHERE vendor_name='$vendorname' AND vendor_id IN (SELECT vendor_id FROM vendor WHERE status=1)";
					$result=mysql_query($query,$DbConnection);
					$row=mysql_fetch_row($result);
					$vendor_id=$row[0];

					$query="SELECT call_type_id FROM call_type WHERE call_type_name='$callreportname' AND  group_id='$vendor_group_id' AND status=1";
					$result=mysql_query($query,$DbConnection);
					$row=mysql_fetch_row($result);
					$call_type_id=$row[0];

					$query="INSERT INTO call_detail(sales_person_id,vendor_id,call_type_id,latitude,longitude,cell_id,remark,status,create_id,create_date) VALUES".
							"('$sales_id' , '$vendor_id' , '$call_type_id' ,'$lat_long_cellID[0]','$lat_long_cellID[1]','$lat_long_cellID[2]','$remark',  '1' , '5' , '$date')";	
					$result=mysql_query($query,$DbConnection);
					if($result)
					{
						echo "success";
					}
					else
					{
						echo "failure";
					}
				}
				
				if($sync=="addvendor")
				{
					if($vcode1[0]=='NULL')  ////////// in the case of mobile force close
					{
						$query="SELECT sales_person_id FROM vendor_detail WHERE vendor_admin_id='$vendor_admin'";						
						$result=mysql_query($query,$DbConnection);
						$row=mysql_fetch_row($result);
						$sales_person_id=$row[0];						
						$query="SELECT user_id FROM account WHERE account_id IN (SELECT account_id FROM sales_person_grouping WHERE sales_person_id='$sales_person_id' AND status=1)";
						$result=mysql_query($query,$DbConnection);
						$row1=mysql_fetch_row($result);
						$user_id=$row1[0];					
						$final_vcode=$user_id."/".$vcode1[1];
						echo "User ID NULL,".$final_vcode;
					}
					else
					{
						$sales_id=get_sales_id($login_id_1,$vendor_group_id);
						$lat_lon_cell_id=explode(",",trim($_POST['lat_lon_cellID']));
						$validate_on_variables=$vcode.",Vendor Code:".$vendor_admin.",Vendor Admin:".$route_id.",Route ID";
						$validate_result=validate_post_variable($validate_on_variables);
						$validate_result1=explode(",",$validate_result);
						if($valiate_result1[0]=="Please Enter Valid")
						{
							echo $valiate_result1[0].$valiate_result1[1];
						}
						else
						{
							//$variables_for_database=$vcode.",vendor_code,vendor_detail,Vendor Code:".$vendor_admin.",vendor_admin_id,vendor_detail,Vendor Admin:".$routeid.",route_id,sales_route,Route ID";
							if($vendor_type=="2")
							{
								$variables_for_database=$vendor_admin.",vendor_id,vendor_detail,Vendor Admin:".$route_id.",route_id,sales_route,Route ID";
								$post_validate_result=check_database_availability($variables_for_database);
								$post_validate_result1=explode(",",$post_validate_result);
							}
							if($post_validate_result1[0]=='Not Exist')
							{
								echo $post_validate_result1[0].$post_validate_result1[1];
							}	
							else
							{
								
								/*$query="SELECT territory_id from territory WHERE territory_code='$territorycode' AND status=1";	
								//echo "Query4=".$query."<br>";
								$result=mysql_query($query,$DbConnection);
								$row=mysql_fetch_row($result);
								$territory_id=$row[0];*/
								$query="SELECT territory_id from vendor_detail WHERE vendor_id='$vendor_admin'";	
								//echo "Query4=".$query."<br>";
								$result=mysql_query($query,$DbConnection);
								$row=mysql_fetch_row($result);
								$territory_id=$row[0];								
								
								$query="SELECT vendor_code FROM vendor_detail WHERE vendor_code='$vcode'";	
								//echo "Query4=".$query."<br>";
								$result=mysql_query($query,$DbConnection);
								$num_rows=mysql_num_rows($result);
								if($num_rows>0)
								{
									$sales_id=get_sales_id($login_id_1,$vendor_group_id);									
									$query="SELECT max(vendor_code) as vendor_code FROM vendor_detail WHERE sales_person_id=$sales_id".
											" AND vendor_id IN(SELECT vendor_id FROM vendor WHERE status=1)";								
									$result=mysql_query($query,$DbConnection);
									$row=mysql_fetch_row($result);
									$vendor_code=$row[0];
									if($vendor_code=="")
									{
										$final_vendor_code=$login_id_1."/001";
									}
									else
									{
										$vendor_code=explode("/",$vendor_code);
										$vendor_code_cnt=$vendor_code[1]+1;
										$final_vendor_code=$login_id_1."/00".$vendor_code_cnt;
									}									
									echo "Vendor Code Already Exist, ".$final_vendor_code;        
								}
								else
								{
									//$password=md5($vcode);
									$query="INSERT into vendor(status,create_id,create_date) VALUES('1', '9', '$date')";
									//echo "Query5=".$query."<br>";			
									$result=mysql_query($query,$DbConnection);		
									if($result)
									{
										if($vendor_type=="3")
										{
											$route_id="";
										}
										$query="SELECT max(vendor_id) FROM vendor WHERE status=1";	
										//echo "Query6=".$query."<br>";				
										$result=mysql_query($query,$DbConnection);
										$row=mysql_fetch_row($result);
										
										$query="INSERT INTO vendor_detail(vendor_id,territory_id,vendor_code,vendor_name,contact_person_name,date_of_birth,address_1,address_2".
											   ",pincode,city,state,country,phone,email_id,latitude,longitude,cell_id,vendor_type_id,vendor_admin_id,sales_person_id,route_id,create_id,create_date)".
											   " VALUES('$row[0]', '$territory_id', '$vcode' , '$vendor_name' , '$contactpersonname', '$dateofbirth', '$addline1', '$addline2',".
											   "'$pincode','$city','$state','$country','$vendor_mob','$lat_lon_cell_id[0]','$lat_lon_cell_id[1]','$lat_lon_cell_id[2]','$email',".
											   "'$vendor_type','$vendor_admin','$sales_id','$route_id','1','$date')";
										//echo "Query7=".$query."<br>";
										$result=mysql_query($query,$DbConnection);
										if($result)
										{
											$query_uom="SELECT uom_id FROM unit_of_measure WHERE group_id='$vendor_group_id' AND status=1";	
										//echo "Query6=".$query."<br>";				
											$result_uom=mysql_query($query_uom,$DbConnection);											
											$uom_ids="";
											while($uom_row=mysql_fetch_object($result_uom))
											{
												$uom_arr[]=$uom_row->uom_id;
											}
											$uom_ids="";
											for($i=0;$i<sizeof($uom_arr);$i++)
											{
											  if($vendor_type=="2")
											  {
												$uom_ids=$uom_ids.$uom_arr[$i].",";
											  }
											  else
											  {
												if($i<(sizeof($uom_arr)-1))
												{
													$uom_ids=$uom_ids.$uom_arr[$i].",";
												}											  
											  }											  
											}
											$uom_ids_1=substr($uom_ids,0,-1);
											/*if($vendor_type=="2")
											{	
												$uom_ids="1,2,3,4";
											}
											else
											{
												$uom_ids="1,2,3";
											}*/
											$query="INSERT INTO uom_vendor_assignment(uom_id,vendor_id,create_id,create_date,status)VALUES('$uom_ids_1','$row[0]','1','$date','1')";
											$result=mysql_query($query,$DbConnection);
											if($result)
											{
												$query="SELECT max(serial) as serial FROM vendor_detail";
												$result=mysql_query($query);
												$row_1=mysql_fetch_row($result);
												$serial_no=$row_1[0];					
												echo "success,".$serial_no.",".$row[0];									
											}
											else
											{				    
												echo "failure"; 
											} 
										}
										else
										{
											echo "failure";
										}
									}
								}
							}
						}
					}
				}
			}
		}		
	}
  ?>
  
