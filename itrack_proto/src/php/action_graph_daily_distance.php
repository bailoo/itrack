<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	
	$changeInterval = $_POST['changeInterval'];
	$vserial = $_REQUEST['vehicleserial'];
	
  $xmin = $_POST['xmin'];
	$xmax = $_POST['xmax'];
	$ymin = $_POST['ymin'];
	$ymax = $_POST['ymax'];
	
	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];	
	
?>
	
	<script language="javascript">
	
	/*function submit_form()
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
	}*/
	</script>

						
<?php
echo'           
      <center>
      <table border=0 width = 100% cellspacing=2 cellpadding=0>
    		<tr>
    			<td height=10 class="text" align="center"><strong>Distance Graph</strong></td>
    		</tr>
    	</table>';

      /*
			echo'<form action="action_graph_daily_distance.php" method="POST">';
			echo '<input type="hidden" name="day" value="'.$day.'">';
			echo '<input type="hidden" name="month" value="'.$month.'">';
			echo '<input type="hidden" name="year" value="'.$year.'">';
			echo '<input type="hidden" name="vehicleserial" value="'.$vserial.'">';
			echo '<input type="hidden" name="changeInterval" value="1">';

			echo '<table border=0 width = 100% rules=all bordercolor="#689FFF" align="center" cellspacing=0 cellpadding=0>';
			echo '<tr>';								
			/////////////// CHANGE TIME INTERVAL  /////////////////		
			echo'<td align="center" class="text">(Hr) From ';									
									
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
			echo'</td>';
			//////// TIME INTERVAL CLOSED ////////////
			
			
			/////////////// CHANGE SPEED INTERVAL  ////////////
			echo'<td align="center" class="text">(Distance) From ';								
			echo '<select name="ymin">';
			
			if($ymin)
				echo '<option value="'.$ymin.'" selected>'.$ymin.'</option>';	
			else
				echo '<option value="0" selected>0</option>';
				
			echo '<option value="0">0</option>';
			echo '<option value="20">20</option>';
			echo '<option value="40">40</option>';
			echo '<option value="60">60</option>';
			echo '<option value="80">80</option>';
			echo '<option value="100">100</option>';
			echo '<option value="120">120</option>';
			echo '<option value="140">140</option>';
			echo '<option value="160">160</option>';					
			echo '</select>'; 
			
			echo '&nbsp;To : ';								
			echo '<select name="ymax">';
			
			if($ymax)
				echo '<option value="'.$ymax.'" selected>'.$ymax.'</option>';
			else
				echo '<option value="160" selected>160</option>';
				
			echo '<option value="20">20</option>';
			echo '<option value="40">40</option>';
			echo '<option value="60">60</option>';
			echo '<option value="80">80</option>';
			echo '<option value="100">100</option>';
			echo '<option value="120">120</option>';
			echo '<option value="140">140</option>';
			echo '<option value="160">160</option>';							
			echo '</select>'; 			
			
			echo'</td>';								
			/////////////// SPEED INTERVAL CLOSED ////////////
			
			echo'<td>';
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Reflect" OnClick="submit_form()">';
			echo'</td></tr></table>';		
			echo '</form>';
		*/
											
			echo'<table border=0 width = 100% cellpadding=0 cellspacing=0 height=100><tr><td align="center">';
			echo'<IMG SRC="./src/php/graph_final_distance.php?width='.$width.'&height='.$height.'&day='.$day.'&month='.$month.'&year='.$year.'&vserial='.$vserial.'&xmin='.$xmin.'&xmax='.$xmax.'&ymin='.$ymin.'&ymax='.$ymax.'">';
			//echo'<a href="./src/php/graph_final_distance.php?width='.$width.'&height='.$height.'&day='.$day.'&month='.$month.'&year='.$year.'&vserial='.$vserial.'&xmin='.$xmin.'&xmax='.$xmax.'&ymin='.$ymin.'&ymax='.$ymax.'">Distance graph</a>';
			echo'</td></tr>
			</table>
      </center>';																								
	/*else
	{
		print"<center><FONT color=\"Red\"><strong>No Data Available</strong></font></center>";
		echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=DailySpeedGraph.php\">";																				
	} */
	
?>