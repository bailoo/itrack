<?php
	include_once('../util_session_variable.php');
	include_once('../util_php_mysql_connectivity.php');
	//echo "account=".$account."<br>";
	if($account)
	{
?>
<html>
	<title>IESPL</title>
	<head>
		<script language="javascript">
			function action_manage_suspend_account()
			{				
				if(document.getElementById("account_user_id").value=="select")
				{
					alert("Please Select Account User Id");
					document.getElementById("account_user_id").focus();
					return false;
				}		
			}
		</script>
		<style>
			.manage_account_interface
			{
			  font-size:12px;
			  font-family:Arial;
			}
			.fieldset_interface
			{
			  font-size:11px;
			  font-family:Arial;
			  font-weight:bold;
			}
		</style>
	</head>
<body>

<?php
	$Query="SELECT DISTINCT user_id FROM account WHERE status=4";
	$Result=mysql_query($Query,$DbConnection);	
	
	echo"
	<form name='manage1' action='action_suspend_continue_account.php' target='_blank' method='post'>
		<input type='hidden' name='action_type' value='continue'>";	
	echo'
		<table>
			<tr>
				<td height="80px">
				</td>
			</tr>
		</table>
		<table align="center">
			<tr>
				<td>
					<fieldset class="fieldset_interface">
						<legend>Account Continue Form</legend>
						<table border="0" align=center class="manage_account_interface" cellspacing="2" cellpadding="2">
							<tr> 
							  <td>User Id</td> 
							  <td>&nbsp;:&nbsp;</td>
							  <td>
								<select name="account_user_id" id="account_user_id">
									<option value"select">Select</option>';
									while($Row=mysql_fetch_object($Result))
									{
										echo '<option value='.$Row->user_id.'>'.$Row->user_id.'</option>';
									}				
							echo'</select> 
							</tr>		
						</table>';
						echo'<br>
							<center>			
								<input type="submit" value="Submit" id="enter_button">&nbsp
							</center>
					</fieldset>
				</td>
			</tr>
		</table>
		<center><br><br><a href="../home.php"><strong>Back</strong></a><br></center>
	</form>
	</body>
</html>';
}
else
{
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=../index.php\">";
}
?>