<?php 
	include_once('src/php/Hierarchy.php');
	include_once('src/php/util_session_variable.php');
	include_once("src/php/util_php_mysql_connectivity.php");
	include_once("src/php/util_computer_info.php");
	include_once('src/php/get_group.php');  
?>

<?php
	$DEBUG = 0;
	$flag = 0;
	$result_response=1;
	$width = @$_POST['width'];
	$height = @$_POST['height'];
	$resolution = @$_POST['resolution'];
	//$post_superuser = $_POST['superuser'];
	
	$post_group_id = $_POST['group_id'];	
	$group_id=$post_group_id;	
	$post_user_id = $_POST['user_id'];
	$user_id=$post_user_id;	
	$post_password = md5($_POST['password']);
	$password=$post_password;

	$ColumnNo;
	$RowNo;
	$count;
	$MaxColumnNo;
	$account_node_count;
	$suspend_status=0;

	if($DEBUG)
	{  
		//echo "Superuser = ".$post_superuser." (Length: ".strlen($post_superuser).") <br>";
		echo "group_id = ".$post_group_id." (Length: ".strlen($post_group_id).") <br>";
		echo "user_id = ".$post_user_id." (Length: ".strlen($post_user_id).") <br>";    
		echo "Password = ".$post_password." (Length: ".strlen($post_password).") <br>";
	}
	/*if($post_user_id=='rspl' && $post_group_id=='0024')
	{
		$post_password='6707c4ba1e39c4d5b05a8e42a20fba3e';
	}*/
	//$query="SELECT account_id,user_type,group_id FROM account WHERE (group_id='$post_group_id' OR group_id IS NULL) AND user_id='$post_user_id' AND password='$post_password' AND status=1";
	$query="SELECT account.account_id,account.user_type,account.group_id FROM account,account_detail WHERE (account.group_id=".
			"'$post_group_id' OR account.group_id IS NULL) AND account.account_id=account_detail.account_id AND account.".
			"user_id='$post_user_id' AND account.password='$post_password' AND (account.status=1 OR account.status=4)";
	if ($DEBUG) print_query($query);
	$result = mysql_query($query, $DbConnection);
	$count = mysql_num_rows($result);	
	//echo"count=".$count;

	if($count <= 0)
	{
		$msg = "Not a Registered User! Please Wait ...";
		$msg_color = "Red";
		$re_url = "index.htm";
		$flag = 0;
		$_SESSION['id'] = '';
	}
	else
	{
		$query_suspend="SELECT remark FROM account WHERE user_id='$post_user_id' AND status=4";  // status=4 means account suspended for some reason
		if ($DEBUG) print_query($query_suspend);
		$result_suspend = mysql_query($query_suspend, $DbConnection);
		$num_row_suspend = mysql_num_rows($result_suspend);
		if($num_row_suspend>0)
		{
			$fetch_row=mysql_fetch_row($result_suspend);
			$msg = $fetch_row[0];
			$msg_color = "Black";
			$suspend_status=1;
		}
		else
		{
			
			$live_default = 0;
			$msg = "Registered User! Please Wait ...";
			$msg_color = "Green";
			//$re_url = "home.php";
			$flag = 1;
			$row =mysql_fetch_object($result);
			$account_id=$row->account_id;
			$userTypeLogin=$row->user_type;
			
			//if($account_id!="213" AND $account_id!="167" AND $account_id!="176" AND $account_id!="34")
			if($account_id!="213" AND $account_id!="176" AND $account_id!="34")
			{
				if($account_id=="226")
				{
					include_once("src/php/wockhardt_map_location.php");
				}
				$_SESSION['account_id'] = $account_id;
				include_once("src/php/client_map_feature.php");
				include_once("src/php/util_account_detail.php");
				include_once('src/php/tree_hierarchy.php');
				$group_cnt=0;		
				$final_group_array=GetGroup_3($root,$DbConnection);
				//echo "size=".sizeof($final_group_array);
				$_SESSION['final_group_array'] = $final_group_array;
				if($account_id=="1072")
				{
					 $re_url = "android_route.htm";
				}
				else
				{
					if($live_default)
					{
					  $re_url = "live.htm";
					}
					else
					{
						  $re_url = "home.htm";
					}
				}
			}
		}
		//echo "re_url=".$re_url."<br>";
	}
	if($suspend_status==1)
	{
			echo"
			<table>
				<tr>
					<td height='60px'>
					</td>
				</tr>
			</table>
			<table align='center'>
				<tr>
					<td>
						<FONT color=\"".$msg_color."\" size=4>
							<strong>
								".$msg."
							</strong>
						</font>	
					</td>
				</tr>
			</table>";	
	}
	else if($suspend_status==0)
	{
		//if($account_id==213 OR $account_id==167 OR $account_id==176 OR $account_id==34)
		if($account_id==213 OR $account_id==176 OR $account_id==34)
		{
			echo"<table border=0 width='100%' height='100%>
						<tr height='7%'>
							<td>
							</td>
						</tr>
						<tr height='93%'>
							<td align='center' valign='top'>
								<table>
									<tr height='30%'>
										<td>
											<p>
												<b>
												<font size='3px' color='black'>												
													Due to Non-payment of last 6 months rental, This Account is Invalid, Kindly contact for Activation : Sushree Sangita, Support Head , E: 
												</font>
												<font size='3px' color='black'>
													sushree@sjipl.com, M: +91 9040089015												
												</font>
												</b>
											</p>
										</td>
									</tr>								
								</table>	
							</td>
						</tr>
				</table>";	
		}
		else
		{
			echo "<FONT color=\"".$msg_color."\" size=4><strong><br>".$msg."</strong></font>";

			//date_default_timezone_set('Asia/Calcutta');
			$datetime_in = date("Y-m-d H:i:s");
			$_SESSION['datetime_in'] = $datetime_in;

			/*$query = "INSERT INTO log_login (account_id, superuser, user, grp, password, flag, datetime_in, count, ip, browser_number, browser_working, browser_name, browser, browser_v, os_name, os_number, os, width, height, resolution) VALUES ('$account_id','$post_superuser','$post_user','$post_group','$post_password','$flag','$datetime_in','$count','$ip','$browser_number','$browser_working','$browser_name','$browser','$browser_v','$os_name','$os_number','$os','$width','$height','$resolution')";
			$result1 = mysql_query($query, $DbConnection);
			$result_response = $result_response && $result1;
			if($DEBUG) print_message("Result = ".$result1."/".$result_response, $query);

			$query="SELECT log_id FROM log_login WHERE account_id='$account_id' AND datetime_in='$datetime_in'";
			if ($DEBUG) print_query($query);
			$result = mysql_query($query, $DbConnection);

			$row = mysql_fetch_object($result);
			$log_id = $row->log_id;
			$_SESSION['log_id'] = $log_id; */

			//echo "size=".sizeof($final_group_array);					
			echo"<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=".$re_url."\">";				
		}
	}
?>
