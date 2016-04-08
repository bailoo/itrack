<?php

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');

$case = $_POST['case'];
$vehicleid = $_POST['vehicleid'];
//echo "vid=".$vehicleid;

$tripid = $_POST['tripid'];
$startdate=$_POST['StartDate'];
$enddate=$_POST['EndDate'];

/*
echo "<br>vid=".$vehicleid;
echo "<br>tripid=".$tripid;
echo "<br>startdate=".$startdate;
echo "<br>enddate=".$enddate;
*/  

?>

<HTML>
<TITLE></TITLE>
<head>
	<LINK REL="StyleSheet" HREF="menu.css">
	
	<style type="text/css">
	@media print  { .noprint  { display: none; } }
	@media screen { .noscreen { display: none; } }
	</style>

	<script type=text/javascript src="menu.js"></script>
	
	<script type="text/javascript" language="javascript" src="TripInfo/Get_xmlData.js"></script>
	
</head>

<body bgcolor="white">

<form method="POST" action="trip_report_action.php">
<?php
	 
	$size = sizeof($vehicleid);
	//echo "size=".$size." st=".$startdate." ed=".$enddate;
	
	echo '<input type="hidden" name="case" value="'.$case.'">';
	
	for($i=0;$i<$size-1;$i++)
	{
		//echo "id=".$vehicleid[$i];
		echo '<input type="hidden" name="vehiclestatus[]" value="'.$vehicleid[$i].'">';
	}
	
	echo '<input type="hidden" name="StartDate" value="'.$startdate.'">';
	echo '<input type="hidden" name="EndDate" value="'.$enddate.'">';
	
	echo '<input type="hidden" name="vserial_arr">';
	echo '<input type="hidden" name="vehiclename_arr">';	
	echo '<input type="hidden" name="lat_arr">';
	echo '<input type="hidden" name="lng_arr">';
	echo '<input type="hidden" name="datetime_arr">';
?>

</form>

	<?php
		if($access=="0")
			include('menu.php');
		else if($access=="1" || $access=="Zone")
			include('usermenu.php');
	?>
		<td class="bg" valign="top">
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
								<td height=10 class="text" align="center"><br>Trip Details</b></td>
							</tr>
						</table>

						<?php
							if($access=="0")
								include("set_height.php");
							else if($access=="1" || $access=="-2" || $access=="Zone")
								include("set_user_height.php");
						?>

						<br>

					<center>Please... wait collecting data</center>
						<?php
						echo 
							'<script type="text/javascript">
								getData();
							</script>';
						?>
					 </TD>
				 </TR>
			</TABLE>
		</td>

	</tr>
</TABLE>
</BODY>
</HTML>