<?php
	echo"<table border='0' width='100%' cellpadding='0' cellspacing='0' height='100%' class='frame_header_table'>  
			<tr>
				<!--<td width='2%'>
					&nbsp;&nbsp;
					<img src='images/icon/welcome.png'".$img_size." style='border:none;'>
				</td>
				<td align='left' width='17%'>
					<font color='blue'>
						Welcome 
					</font>
					<font color='green'>
						&nbsp;:&nbsp;".$user_name."
					</font>
				</td>-->
				<td align='center'>
					<table class='frame_header_table' border='0' cellspacing=0 cellpadding=0>
						<tr>
							<td>
							<input type='button' value='RAW DATA' onclick='javascript:portal_android_information(\"RAW DATA\");'>
							</td>
							<td>
							<input type='button' value='FILTERED DATA' onclick='javascript:portal_android_information(\"FILTERED DATA\");'>
							</td>
							<td>
							<input type='button' value='CAMPARE DATA' onclick='javascript:portal_android_information(\"CAMPARE DATA\");'>
							</td>								
						</tr>
					</table>
				</td>
			</tr>
		</table>"; 		
	?>  				  
    
