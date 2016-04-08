<?php  
	include_once('util_session_variable.php');	
  include("user_type_setting.php");	
	$path = $_SERVER['SCRIPT_NAME'];	
	$url_substr = explode('/',$path);		
	$size = sizeof($url_substr);
	$interface = $url_substr[$size-1];				
	$div_height="<div style='height:2px;'></div>";		
	$set_nbsp="&nbsp;";		
	$img_size='width="15px" hight="14px"';
	$query="SELECT name from account_detail where account_id='$account_id'";	$result=mysql_query($query,$DbConnection);	$row=mysql_fetch_object($result);
	$user_name=$row->name;
	//echo"interface=".$interface;
	echo "<input type='hidden' id='vehicle_milstone'>";
	echo "<table border='0' width='100%' cellpadding='0' cellspacing='0' height='100%' class='frame_header_table'>  
			<tr>
				<td width='2%'>&nbsp;&nbsp;<img src='images/icon/welcome.png'".$img_size." style='border:none;'></td>
				<td align='left' width='17%'><font color='blue'>Welcome </font><font color='green'>&nbsp;:&nbsp;".$user_name."</font></td>";
			//if($interface=="home.php" || $interface=="live.php")
			
				if($size_utype_session>1)
				{
				echo'<input type="hidden" id="default_category" value="'.$user_typeid_array[0].'">';
				echo"<td align='right'>Category&nbsp;:&nbsp;</td>
					<td>
						<select id='category' onchange='javascript:setDisplayOption(this.value);'>";													
							for($i=0;$i<$size_utype_session;$i++)
							{	echo'<option value="'.$user_typeid_array[$i].'">'.$user_type_name_session[$i].'</option>';	}
					echo"</select>    
					</td>";
				}
				if($size_utype_session==1)
				{	echo '<input type="hidden" id="category" value="'.$user_typeid_array[0].'">';	}
	
				echo"<td align='right'>&nbsp;&nbsp;Display Option&nbsp;:&nbsp;</td>
					<td>
						<select id='user_type_option' style='font-size:10px' onchange='javascript:show_main_home_vehicle(this.value);'>
							<option value='all'>All</option>					<option value='group'>By Group</option>		<option value='user'>By User</option>
							<option value='vehicle_tag'>By Vehicle Tag</option>	<option value='vehicle_type'>By Vehicle Type</option>
							<!--<option value='vehicle'>By Vehicle</option>-->	
						</select>    
					</td>
					<td align='right'><a href='logout.htm' class='hs2'>
													Logout
												</a>&nbsp;</td>";	
			
				
        //if($_SERVER["HTTP_X_FORWARDED_FOR"] == "172.26.48.189")
        //{
          /*if( ($group_id == "0051") || ($group_id == "0004") )
          {
            echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<blink><font color=blue><strong>Welcome!</strong></font>&nbsp;&nbsp; <font color=red><strong>Mr Partha Sen Gupta</strong> &nbsp;(VP RM ,Tata Steel Ltd.)</font></blink></td>';
          } */
        //}
        
        
        
        echo'<td align="right"></td>
						</tr>
				</table> '; 		
	?>  				  
    
