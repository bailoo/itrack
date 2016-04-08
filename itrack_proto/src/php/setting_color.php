<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	$account_id_local=$_POST['setting_account_id'];
	$tmp = explode(',',$account_id_local);
	//echo $tmp[0].','.$tmp[1].'<BR>';
	$account_id1 = $tmp[0];
	$group_id1 = $tmp[1];
  
	$query1="SELECT * FROM color_setting WHERE user_account_id='$account_id_local' and status=1";
	//echo $query1."<br>";
	$result1=mysql_query($query1,$DbConnection);
	if($row1=mysql_fetch_object($result1))
	{
		$feature_name = $row1->feature_name;
		$color_code = $row1->color_code;
	}
	//echo "<br>FeatureName=".$feature_name." ,color_code=".$color_code;
	
	$color_arr = explode('@',$color_code);
	
	$color_var1 = explode(':',$color_arr[0]);
	$color_var2 = explode(':',$color_arr[1]);
	$color_var3 = explode(':',$color_arr[2]);
	$color_var4 = explode(':',$color_arr[3]);

	//INTIALISE COLOR VARIABLES
	$color_val[] = "#000000";
	$color_name[] = "Black";
	$color_val[] = "#0000FF";
	$color_name[] = "Blue";
	$color_val[] = "#A52A2A";
	$color_name[] = "Brown";
	$color_val[] = "#00FFFF";
	$color_name[] = "Cyan";
	$color_val[] = "#FF7F50";
	$color_name[] = "Coral";
	$color_val[] = "#00008B";
	$color_name[] = "DarkBlue";
	$color_val[] = "#A9A9A9";
	$color_name[] = "DarkGray";
	$color_val[] = "#8B0000";
	$color_name[] = "DarkRed";
	$color_val[] = "#FF1493";
	$color_name[] = "DeepPink";
	$color_val[] = "#00BFFF";
	$color_name[] = "DeepSkyBlue";
	$color_val[] = "#006400";
	$color_name[] = "DarkGreen";
	$color_val[] = "#FFD700";
	$color_name[] = "Gold";
	$color_val[] = "#008000";
	$color_name[] = "Green";
	$color_val[] = "#ADFF2F";
	$color_name[] = "GreenYellow";
	$color_val[] = "#4B0082";
	$color_name[] = "Indigo";
	$color_val[] = "#ADD8E6";
	$color_name[] = "LightBlue";
	$color_val[] = "#800000";
	$color_name[] = "Maroon";
	$color_val[] = "#FF00FF";
	$color_name[] = "Magenta";
	$color_val[] = "#000080";
	$color_name[] = "NavyBlue";
	$color_val[] = "#FFA500";
	$color_name[] = "Orange";
	$color_val[] = "#FFC0CB";
	$color_name[] = "Pink";
	$color_val[] = "#800080";
	$color_name[] = "Purple";
	$color_val[] = "#FF0000";
	$color_name[] = "Red";
	$color_val[] = "#87CEEB";
	$color_name[] = "SkyBlue";
	$color_val[] = "#FF6347";
	$color_name[] = "Tomato";
	$color_val[] = "#EE82EE";
	$color_name[] = "Violet";
	$color_val[] = "#FFFF00";
	$color_name[] = "Yellow";
	
	
  echo'<form method = "post"  name="setting">
			<input type="hidden" name="local_account_id" id="local_account_id" value="'.$account_id1.'">';
     echo'<table border="0" align=center class="manage_interface" cellspacing="3" cellpadding="3">
         <tr>
          <td colspan="6" align="center"><b>LIVE VEHICLE COLOR SETTING</b><div style="height:5px;"></div><br></td>    
        </tr>              
			<tr>
					<td>FeatureType</td>
					<td>&nbsp;:&nbsp;</td>
					<td>
						<select id="feature">
							<option value="live" SELECTED>Live</option>
						</select>
					</td>
					<td></td>					
					<td></td>
			</tr>
			<tr>
					<td>Setting for Time1</td>
					<td>&nbsp;:&nbsp;</td>
					<td><strong>Time <font color="red">></font>&nbsp;:&nbsp;<strong><input type ="text" id="range_a1" size="6" value="'.$color_var1[0].'">&nbsp;<font color="red">min</font></td>										
					<td><strong>Color &nbsp;:&nbsp;<strong>';										
					
					echo '<select id="color_a">';
					for($i=0;$i<sizeof($color_val);$i++)
					{
						if(trim($color_var1[1]) == trim($color_val[$i]))
						{
							echo '<option value="'.$color_val[$i].'" selected>'.$color_name[$i].'</option>';
						}
						else
						{
							echo '<option value="'.$color_val[$i].'">'.$color_name[$i].'</option>';
						}
					}
					echo '</select>';					
					
					echo'</td>';
					
			echo '</tr>
			<tr>
					<td>Setting for Time2</td>
					<td>&nbsp;:&nbsp;</td>
					<td><strong>Time <font color="red">></font>&nbsp;:&nbsp;<strong><input type ="text" id="range_b1" size="6" value="'.$color_var2[0].'">&nbsp;<font color="red">min</font></td>					
					<td><strong>Color &nbsp;:&nbsp;<strong>';
					
					echo '<select id="color_b">';
					for($i=0;$i<sizeof($color_val);$i++)
					{
						if(trim($color_var2[1]) == trim($color_val[$i]))
						{
							echo '<option value="'.$color_val[$i].'" selected>'.$color_name[$i].'</option>';
						}
						else
						{
							echo '<option value="'.$color_val[$i].'">'.$color_name[$i].'</option>';
						}
					}
					echo '</select>';		
					
					echo '</td>
			</tr> 
			<tr>
					<td>Setting for Time3</td>
					<td>&nbsp;:&nbsp;</td>
					<td><strong>Time <font color="red">></font>&nbsp;:&nbsp;<strong><input type ="text" id="range_c1" size="6" value="'.$color_var3[0].'">&nbsp;<font color="red">min</font></td>					
					<td><strong>Color &nbsp;:&nbsp;<strong>';
					
					echo '<select id="color_c">';
					for($i=0;$i<sizeof($color_val);$i++)
					{
						if(trim($color_var3[1]) == trim($color_val[$i]))
						{
							echo '<option value="'.$color_val[$i].'" selected>'.$color_name[$i].'</option>';
						}
						else
						{
							echo '<option value="'.$color_val[$i].'">'.$color_name[$i].'</option>';
						}
					}
					echo '</select>';		
					
					echo '</td>
			</tr> 
			<tr>
					<td>Setting for Time4</td>
					<td>&nbsp;:&nbsp;</td>
					<td><strong>Time <font color="red">></font>&nbsp;:&nbsp;<strong><input type ="text" id="range_d1" size="6" value="'.$color_var4[0].'">&nbsp;<font color="red">min</font></td>					
					<td><strong>Color &nbsp;:&nbsp;<strong>';
					
					echo '<select id="color_d">';
					for($i=0;$i<sizeof($color_val);$i++)
					{
						if(trim($color_var4[1]) == trim($color_val[$i]))
						{
							echo '<option value="'.$color_val[$i].'" selected>'.$color_name[$i].'</option>';
						}
						else
						{
							echo '<option value="'.$color_val[$i].'">'.$color_name[$i].'</option>';
						}
					}
					echo '</select>';
					
					echo '</td>
			</tr> 				       																		
				
        <tr>
          <td></td>
        </tr>
        			  
        <tr>                    									
			<td align="center" colspan="5"><input type="button" onclick="javascript:action_setting_color(setting)" value="Update" id="enter_button">&nbsp;<input type="reset" value="Clear"><br></td>
		</tr>
    </table>
  </form>';
  echo'<center><a href="javascript:show_option(\'setting\',\'account_color_prev\');" class="back_css">&nbsp;<b>Back</b></a></center>';
?>
