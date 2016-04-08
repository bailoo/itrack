<?php
	include_once('SessionVariable.php');
	include_once("PhpMysqlConnectivity.php");
	
	$changeInterval = $_GET['changeInterval'];
	
	$xmin = $_GET['xmin'];
	$xmax = $_GET['xmax'];
	$ymin = $_GET['ymin'];
	$ymax = $_GET['ymax'];	
	
	if($changeInterval=="1")
	{
		$day = $_GET['day'];
		$month = $_GET['month'];
		$year = $_GET['year'];
		$vehicleid = $_GET['vehicleid'];
		
		//echo "<br>day=".$day." month=".$month." year=".$year." vid=".$vehicleid;
		//echo "<br> vid=".$vehicleid;
		
		/*echo '<input type="hidden" name="day" value="'.$day.'">';
		echo '<input type="hidden" name="month" value="'.$month.'">';
		echo '<input type="hidden" name="year" value="'.$year.'>';
		echo '<input type="hidden" name="vehicleid" value="'.$vehicleid.'">';*/		
	}	
?>
<HTML>
<TITLE>Vehicle Tracking Pro-Innovative Embedded Systems Pvt. Ltd.</TITLE>
<head>
	<link rel="shortcut icon" href="./Images/iesicon.ico" >
	<LINK REL="StyleSheet" HREF="menu.css">
	<script type=text/javascript src="menu.js"></script>
	
	<script language="javascript">
	function submit_form()
	{
		//alert("min="+ document.forms[0].xmin.value+" max="+document.forms[0].xmax.value);
		var xmin = parseInt(document.forms[0].xmin.value);
		var xmax = parseInt(document.forms[0].xmax.value);
		
		var ymin = parseInt(document.forms[0].ymin.value);
		var ymax = parseInt(document.forms[0].ymax.value);		
		
		if( (xmin > xmax) || (xmin == xmax) || (ymin > ymax) || (ymin == ymax) )
			alert("! INVALID INTERVAL");
		else
			document.forms[0].submit();
	}
	</script>
</head>

<body bgcolor="white">

<?php
		if($access=="0")
		{
		  include('menu.php');
		}
		else if($access=="1")
		{
			if($login=="demouser")
			{
				include('liveusermenu.php');
			}
			else
			{
				include('usermenu.php');
			}
		}
		else if($access=="-2" || $access=="Zone")
		{
		  include('usermenu.php');
		}
?>

		<td STYLE="background-color:white;width:85%;" valign="top">
			<TABLE BORDER="0" CELLSPACING="0" CELLPADDING="0" width="100%">
				<TR>
					<TD>
						<table border=0 width = 100% cellspacing=2 cellpadding=0>
							<tr>
								<td height=10 STYLE="background-color:#f0f7ff" class="text" align="center">Distance Graph</td>
							</tr>
						</table>
						<?php
							if($access=="0")
								include("set_height.php");
							else if($access=="1" || $access=="-2" || $access=="Zone")
								//include("set_user_height.php");
								echo'<div STYLE=" height:'.$height.'-5px; overflow:auto">';
						?>
						
						<br>
<?php 
						$Query="select TableID from vehicletable where VehicleID='$vehicleid'";
						//echo "query=".$Query;
						$Result=mysql_query($Query,$DbConnection);
						$row=mysql_fetch_object($Result);
						$tableid=$row->TableID;
						$tablename = "t".$tableid;						
						if($day<=9)
							$Query="select * from ".$tablename." where VehicleID='$vehicleid' and DateTime Like '".$year."-".$month."-0".$day."%' order by DateTime ASC";
						else
							$Query="select * from ".$tablename." where VehicleID='$vehicleid' and DateTime Like '".$year."-".$month."-".$day."%' order by DateTime ASC";
						//echo $Query;
						$Result=mysql_query($Query,$DbConnection);

						if($Result)
						{ 
							$num_rows=mysql_num_rows($Result);
							if($num_rows)
							{								
								echo'<form action="DailyDistanceGraphAction.php" method="GET">';
								//echo'<table border=0 width = 100% cellpadding=3><tr><td align="center">';
													
								echo '<input type="hidden" name="day" value="'.$day.'">';
								echo '<input type="hidden" name="month" value="'.$month.'">';
								echo '<input type="hidden" name="year" value="'.$year.'">';
								//echo "<br>vid2=".$vehicleid;
								echo '<input type="hidden" name="vehicleid" value="'.$vehicleid.'">';
								echo '<input type="hidden" name="changeInterval" value="1">';
					
								echo '<table border=0 width = 100% rules=all bordercolor="#689FFF" align="center" cellspacing=0 cellpadding=0>';

								echo '<tr>';
								
								//////// TIME INTERVAL  ////////////
								echo '<td align="center" class="text">(Hr) From ';			
								echo '<select name="xmin">';
								
								if($xmin)
									echo '<option value="'.$xmin.'" selected>'.$xmin.'</option>';
								else									
									echo '<option value="0" selected>0</option>';
									
								echo '<option value="0">0</option>';
								echo '<option value="1">1</option>';
								echo '<option value="2">2</option>';
								echo '<option value="3">3</option>';
								echo '<option value="4">4</option>';
								echo '<option value="5">5</option>';
								echo '<option value="6">6</option>';
								echo '<option value="7">7</option>';
								echo '<option value="8">8</option>';
								echo '<option value="9">9</option>';
								echo '<option value="10">10</option>';
								echo '<option value="11">11</option>';
								echo '<option value="12">12</option>';
								echo '<option value="13">13</option>';
								echo '<option value="14">14</option>';
								echo '<option value="15">15</option>';
								echo '<option value="16">16</option>';
								echo '<option value="17">17</option>';
								echo '<option value="18">18</option>';
								echo '<option value="19">19</option>';
								echo '<option value="20">20</option>';
								echo '<option value="21">21</option>';
								echo '<option value="22">22</option>';
								echo '<option value="23">23</option>';
								echo '<option value="24">24</option>';						
								echo '</select>'; 
								
								echo '&nbsp;To : ';								
								echo '<select name="xmax">';
								
								if($xmax)
									echo '<option value="'.$xmax.'" selected>'.$xmax.'</option>';
								else
									echo '<option value="24" selected>24</option>';
								
								echo '<option value="1">1</option>';
								echo '<option value="2">2</option>';
								echo '<option value="3">3</option>';
								echo '<option value="4">4</option>';
								echo '<option value="5">5</option>';
								echo '<option value="6">6</option>';
								echo '<option value="7">7</option>';
								echo '<option value="8">8</option>';
								echo '<option value="9">9</option>';
								echo '<option value="10">10</option>';
								echo '<option value="11">11</option>';
								echo '<option value="12">12</option>';
								echo '<option value="13">13</option>';
								echo '<option value="14">14</option>';
								echo '<option value="15">15</option>';
								echo '<option value="16">16</option>';
								echo '<option value="17">17</option>';
								echo '<option value="18">18</option>';
								echo '<option value="19">19</option>';
								echo '<option value="20">20</option>';
								echo '<option value="21">21</option>';
								echo '<option value="22">22</option>';
								echo '<option value="23">23</option>';
								echo '<option value="24">24</option>';						
								echo '</select>'; 
								echo '</td>';
								//////// TIME INTERVAL CLOSED ////////////								
						 		
								
								/////////////// CHANGE FUEL INTERVAL  ////////////
								echo'<td align="center" class="text">(Distance) From ';								
								echo '<select name="ymin">';
								
								if($ymin)
									echo '<option value="'.$ymin.'" selected>'.$ymin.'</option>';	
								else
									echo '<option value="0" selected>0</option>';
									
								for($f=0; $f<=500; $f++)
								{
									echo '<option value="'.$f.'">'.$f.'</option>';
								}														
									
								echo '</select>'; 
								
								echo '&nbsp;To : ';								
								echo '<select name="ymax">';
								
								if($ymax)
									echo '<option value="'.$ymax.'" selected>'.$ymax.'</option>';
								else
									echo '<option value="500" selected>500</option>';
									
								
								for($f=1; $f<=500; $f++)
								{
									echo '<option value="'.$f.'">'.$f.'</option>';
								}																
								
								echo '</select>'; 			
								
								echo'</td>';								
								/////////////// FUEL INTERVAL CLOSED ////////////	
																
								echo '<td>';
								echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Reflect" OnClick="submit_form()">';
								echo'</td></tr></table>';		
								echo '</form>';
								
								//echo "in if";
								echo'<table border=0 width = 100% cellpadding=3><tr><td align="center">';								
								echo'<IMG SRC="DistanceGraph.php?width='.$width.'&height='.$height.'&day='.$day.'&month='.$month.'&year='.$year.'&vehicleid='.$vehicleid.'&xmin='.$xmin.'&xmax='.$xmax.'&ymin='.$ymin.'&ymax='.$ymax.'">';
								echo'</td></tr></table>';
							}
						}
																	
						else
						{
							print"<center><FONT color=\"Red\"><strong>No Data Available</strong></font></center>";
							echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=DailyDistanceGraph.php\">";
																				
						}
?>
						</div>
					 </TD>
				 </TR>
			</TABLE>
		</td>

	</tr>
</TABLE>
</BODY>
</HTML>