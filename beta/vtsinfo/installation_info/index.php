<?php
?>

<html>
<head>
<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>
</head>

<body>
<div align="right"><a href="../logout.php" style="text-decoration:none;"><font color=green size=3><strong>Logout</strong></font></a></div> 
<!-- Table goes in the document BODY -->
<center>
<BR>
<font color="green"><strong>DEVICE HISTORY</strong></font>
<br><br>
<FORM method="POST" action="action_installation_info.php">
<table border="1" rules="all">
<tr>
	<td><strong>Vehicle Name&nbsp;:&nbsp;</strong></td><td><input type="text" size="20" name="vehicle_string"/></td>
</tr>
<tr>
	<td><strong>Device IMEI No&nbsp;:&nbsp;</strong></td><td><input type="text" size="20" name="device_string"/></td>
</tr>
</table>

<BR><BR>
<input type="submit" value="SUBMIT"/>
<br><br>
<a href="../home.php" style="text-decoration:none;"><font color=blue size=3><strong>Home</strong></font>&nbsp;

</form>
</center>

</body>

</html>
