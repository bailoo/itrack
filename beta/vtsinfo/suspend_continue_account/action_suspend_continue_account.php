<?php
	include_once('../util_session_variable.php');	
	include_once('../util_php_mysql_connectivity.php'); 
	$post_action_type = $_POST['action_type'];  
	//echo "post_action_type=".$post_action_type."<br>";
	if($account)
	{
		if($post_action_type == "suspend")
		{
			$query="SELECT user_id FROM account WHERE user_id='$account_user_id' AND (status=1 OR status=4)";	
			//echo "query=".$query."<br>";
			$result=mysql_query($query,$DbConnection);
			$num_rows=mysql_num_rows($result);
			if($num_rows<=0)
			{
			$message="<br>
						<font color=\"Red\" size=4>
							<strong>
								Account User Id Not Exist..
							</strong>
						</font>";
			}
			else
			{		
				$query="UPDATE account SET status=4,remark='$suspend_remark' WHERE user_id='$account_user_id' AND status=1";
				//echo "query=".$query."<br>";
				$result=mysql_query($query,$DbConnection);
				if ($result)
				{
				$message="<br>
							<font color=\"Red\" size=4>
								<strong>
									Account Suspended Successfully ...
								</strong>
							</font>";
				}			
				else 
				{ 		
				$message="<br>
							<font color=\"Green\" size=4>
								<strong>
									Unable To Prpcess Request!
								</strong>
							</font>";
				}	
			}
			$filename="suspend_account.php";
		} // IF POST_ACTION TYPE CLOSED

		else if($post_action_type =="continue")
		{	
			$query="UPDATE account SET status=1 WHERE user_id='$account_user_id' AND status=4";		
			$result=mysql_query($query,$DbConnection);
			if ($result)
			{
			$message="<br>
						<font color=\"Red\" size=4>
							<strong>
								Account Continue Successfully ...
							</strong>
						</font>";
			}			
			else 
			{ 		
			$message="<br>
						<font color=\"Green\" size=4>
							<strong>
								Unable To Prpcess Request!
							</strong>
						</font>";
			}
			$filename="continue_account.php";
		}
		echo "<center>".$message."</center>";	
	 echo'<center>
			<a href='.$filename.' tagget="_self" class="back_css">
				&nbsp;<b>Back</b>
			</a>
		</center>'; 
	}
	else
	{
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=../index.php\">";
	}

?>
        