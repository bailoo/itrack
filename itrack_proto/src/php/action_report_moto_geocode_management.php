<?php
	include_once('util_session_variable.php');		
	include_once('util_php_mysql_connectivity.php');
	//echo "account_id_local=".$account_id_local."startdate=".$start_date;
	//echo "manage_id=".$manage_id."<br>";
?>
<html>
	<title>
		<?php echo $title; ?>
	</title>
	<head>
	<style>
	.headings
	{
		font-size: 9pt;	
		font-weight: bold;
		color:blue;
		text-align:center;
	}
	.main_tr
	{	
		text-align:left;
		background-color:#E3E3E3;
	}
	</style>
	<script>
		function getGeoManagementData()
		{
			document.forms[0].submit();				
		}
	</script>
	</head>
<body>

  <form method="post" action="action_report_moto_geocode_management_1.htm" target="_self"> 
  
  </form>
<script type="text/javascript" language="javascript">
		getGeoManagementData();
</script>
	
</table>
</body>
</html>

