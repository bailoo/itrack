<?php
  function report_title($name,$date1,$date2)
  {
    echo '<br><table align="center"><tr>
      <td><strong>'.$name.' (DateTime : '.$date1.' - '.$date2.' )</strong></td>
    </tr></table><br>';
  }
	function newwindow_reporttitle($name,$date1,$date2)
	{
	echo'<table align="center" class="menu">
			<tr>
				<td>
					<strong>'.$name.' (DateTime : '.$date1.' - '.$date2.' )</strong>
				</td>
			</tr>
		</table>';
	}
?>	

