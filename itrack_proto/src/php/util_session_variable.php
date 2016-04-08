<?php
$unique_page_name=basename($_SERVER['PHP_SELF']);
if(($unique_page_name=='index.php') || ($unique_page_name=='login.php') )
{	
	session_start();
	//Setting Session variables
	while (list($Skey, $Svalue) = each ($_SESSION)) 
	{
		$$Skey = $Svalue;
	} 
	//Setting Request variables
	while (list($key, $value) = each ($_REQUEST)) 
	{
		$$key = $value;
	} 

	//Setting post variables [optional]
	while (list($Pkey, $Pvalue) = each ($_POST)) 
	{
		$$Pkey = $Pvalue;
	} 
	//Setting get variables [optional]
	while (list($Gkey, $Gvalue) = each ($_GET)) 
	{
		$$Gkey = $Gvalue;
	}
}
else
{
//echo "<br>ACC=".$account_id;
	//echo "in else";
	session_start();	
	if(!$_SESSION)
	{
//echo $unique_page_name;
		if(($unique_page_name!='logout.php') && ($unique_page_name=="manage.php" || $unique_page_name=="home.php" || $unique_page_name=="report.php" || $unique_page_name=="help.php"|| $unique_page_name=="live.php"|| $unique_page_name=="setting.php"))
		{
			//echo "in if";
			print"<font color=\"Red\" size=4><strong>Session Expired! Please Wait ...</strong></font>";	
			echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=index.php\">";			
			exit();
		}
		else if($unique_page_name!='logout.php')
		{
			//echo "in else";
			echo"<center>
					<table style='color:red;size:14px;'>
						<tr>
							<td>
								<b>Session Expired! Unable to process request.</b>
							</td>
						</tr>
						<tr>
							<td align='center'>
								<b>Please logout and login again.</b>
							</td>
						</tr>
					</table>";
			echo"</center>";
			//echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0; URL=test_unique2.php\">";			
			exit();
		}
	}
	else
	{
		//Setting Session variables
		while (list($Skey, $Svalue) = each ($_SESSION)) 
		{
			$$Skey = $Svalue;
		} 
		//Setting Request variables
		while (list($key, $value) = each ($_REQUEST)) 
		{
			$$key = $value;
		} 

		//Setting post variables [optional]
		while (list($Pkey, $Pvalue) = each ($_POST)) 
		{
			$$Pkey = $Pvalue;
		} 
		//Setting get variables [optional]
		while (list($Gkey, $Gvalue) = each ($_GET)) 
		{
			$$Gkey = $Gvalue;
		}
	}	
}

 // set_time_limit();                

?>
