<html>
	<title>Input Form</title>
	<head>
	<style>
	table.menu
{font-size: 10pt;
margin: 0px;
padding: 0px;
font-weight: normal;}
	</style>
	
	<script>
	function formValidation()
	{
		if(document.inputForm.vehicleName.value=="")
		{
			alert("Please Enter Vehicle Name");
			document.inputForm.vehicleName.focus();
			return false;
		}
	}
	</script>
	
	</head>
	<body>
		<form name="inputForm" method="post" action="klpWebServiceClient.php" target="_blank">
			<center>
				<table class="menu" cellspacing="10" cellpadding="10">
					<tr>
						<td>
						<b>Vehicle Name</b>
						</td>
						<td>
						:
						</td>
						<td>
						<input type="text" name="vehicleName">
						
						</td>
					</tr>
				</table>
				<input type="submit" value="Enter" onclick="javascript:return formValidation();">
			</center>		
		
		</form>
	</body>
</html>