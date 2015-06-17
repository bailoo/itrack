<?php

include_once('SessionVariable.php');
include_once("PhpMysqlConnectivity.php");

include_once("tripinfo/get_filtered_xml_zone.php");      // WRITE SORTED XML , FINAL XML NAME STORED IN 'xmltowirte' VARIABLE

$case = $_POST['case'];
$tripid = $_POST['tripid'];
$startdate=$_POST['StartDate'];
$enddate=$_POST['EndDate'];

//echo "xmltowrite=".$xmltowrite." startdate=".$startdate." enddate=".$enddate;
echo'

<FONT color="darkgreen" size="3"><strong>Loading Please wait .. </strong></font>
  
  <form method="post" action="trip_report_action.php" target="_blank">
    <!--<br><br><br><br><br><center><img src="images/loadData.gif"><br>
    <FONT color="#1485D8" font size="2"><strong>Please wait .. </strong></font></center>-->
  
   <input type="hidden" name="xmltowrite" value="'.$xmltowrite.'">
   <input type="hidden" name="startdate" value="'.$startdate.'">
   <input type="hidden" name="enddate" value="'.$enddate.'">
   <input type="hidden" name="case" value="'.$case.'">
   <input type="hidden" name="tripid" value="'.$tripid.'">  
  </form>

	<script type="text/javascript" language="javascript">
		document.forms[0].submit();	
	</script>
  
';
?>
