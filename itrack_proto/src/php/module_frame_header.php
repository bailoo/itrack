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
    $query="SELECT name from account_detail where account_id='$account_id'";	
    $result=mysql_query($query,$DbConnection);	$row=mysql_fetch_object($result);
    $user_name=$row->name;
    $v_align='left';

    //echo"interface=".$interface;
    echo "<input type='hidden' id='vehicle_milstone'>";
    echo "<table border='0' width='100%' cellpadding='0' cellspacing='0' height='100%' class='frame_header_table'>  
                <tr>
                    <td width='2%'>
                        &nbsp;&nbsp;<img src='images/icon/welcome.png'".$img_size." style='border:none;'>
                    </td>
                    <td align='left' width='17%'>
                        <font color='blue'>Welcome </font>
                        <font color='green'>&nbsp;:&nbsp;".$user_name."</font>
                    </td>";
        
                    if($interface == "live.php")
                    {
                        $routeComboArr=getAccountRoutes($account_id,$DbConnection);
                        // print_r($routeComboArr);
                        if($routeComboArr!="No Data Found")
                        {
                           $jsonArray=json_encode($routeComboArr);
                           echo"<input type='hidden' value='".$jsonArray."' id='routeJsonData'>";
                        echo"
                            <td>
                                &nbsp;<select id='user_type_option' style='font-size:10px' onchange='javascript:showRouteOnLiveMap(this.value);'>
                                    <option value='select'>Routes</option>";  
                                    foreach($routeComboArr as $key=>$value)
                                    {
                                        echo"<option value=".$key.">".$routeComboArr[$key]['polylineName']."</option>";
                                    }
                                echo"</select>  &nbsp;   
                            </td>";
                        }
                        echo'<td>
                                <select id="mode_selector" style="font-size:10px" onchange="javascript:select_mode_dropdown(this.form);">
                                    <option value="1">Map Mode</option>
                                    <option value="2">Text Mode</option>							
                                </select>    
                            </td>				
                            <td align="right">
                                <a href="javascript:show_live_vehicles_hide_div();" style="text-decoration:none;">
                                    Select vehicle
                                </a>
                            </td>
                            <td>					
                                &nbsp;
                                <span id="ref_time" style="font-size:x-small;color:red;"></span>
                                    &nbsp;
                                    <input type="checkbox" checked id="trail_path">
                                        <span style="font-size:x-small;color:green;">
                                                Arrow
                                        </span>
                                        &nbsp;
                                    <input type="checkbox" id="trail_path_real"><span style="font-size:x-small;color:green;">
                                    Trail
                                </span>
                            </td>';
                    }
                            echo'<td align="right">
                                    <table class="frame_header_table" border="0" cellspacing=0 cellpadding=0>
                                        <tr>';
                                            echo'<td>
                                                    <table cellspacing=0 cellpadding=0>
                                                        <tr>
                                                            <td height="3px"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="home.htm" style="text-decoration:none;">
                                                                    <img src="images/icon/home1.png" '.$img_size.' style="border:none;">'.$set_nbsp.'
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>									
                                                </td>							
                                                <td '.$v_align.'>';	
                                                if($interface == "home.php")
                                                {
                                                    echo '<b class="hs1">Home</b>';
                                                } 
                                                else
                                                { 
                                                    echo '<a href="home.htm" class="hs2">Home</a> ';
                                                }
                                            echo'</td>';
                                            echo'<td>
                                                    '.$set_nbsp.'|'.$set_nbsp.'
                                                </td>
                                                <td>
                                                    <table cellspacing=0 cellpadding=0>
                                                        <tr>
                                                            <td height="3px"></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <a href="live.htm" style="text-decoration:none;">
                                                                    <img src="images/icon/live.png" '.$img_size.' style="border:none;">'.$set_nbsp.'
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td '.$v_align.'>';	
                                                    if($interface == "live.php")
                                                    {
                                                        echo '<b class="hs1">Live</b>';
                                                    } 
                                                    else
                                                    { 
                                                        echo '<a href="live.htm" class="hs2">Live</a>';
                                                    }
                                            echo'</td>';
                                                    if($session_user_permission==1)
                                                    {
                                                    echo'<td>
                                                            '.$set_nbsp.'|'.$set_nbsp.'
                                                        </td>
                                                        <td>
                                                            <table cellspacing=0 cellpadding=0>
                                                                <tr>
                                                                    <td height="3px"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>												
                                                                        <a href="manage.htm" 
                                                                            style="text-decoration:none;">
                                                                            <img src="images/icon/manage.png" 
                                                                            '.$img_size.' style="border:none;">
                                                                            '.$set_nbsp.'
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>							
                                                        <td '.$v_align.'>';	
                                                                if($interface == "manage.php")
                                                                {
                                                                echo '<b class="hs1">Manage</b>';
                                                                }
                                                                else
                                                                { 
                                                                echo '<a href="manage.htm" class="hs2">Manage</a>';
                                                                }
                                                    echo'</td>';
                                                    }
                                                    echo'<td>
                                                            '.$set_nbsp.'|'.$set_nbsp.'
                                                        </td>
                                                        <td>
                                                            <table cellspacing=0 cellpadding=0>
                                                                <tr>
                                                                    <td height="3px"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <a href="report.htm" style="text-decoration:none;">
                                                                            <img src="images/icon/report2.png" '.$img_size.' 
                                                                            style="border:none;">'.$set_nbsp.'
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                        <td '.$v_align.'>';	
                                                        if($interface == "report.php")
                                                        {
                                                            echo '<b class="hs1">Report</b>';
                                                        } 
                                                        else
                                                        { 
                                                            echo '<a href="report.htm" class="hs2">Report</a>';
                                                        }
                                                    echo'</td>';
                                                    if($session_user_permission==1)
                                                    {	
                                                    echo'<td>
                                                            '.$set_nbsp.'|'.$set_nbsp.'
                                                        </td>';
                                                    echo'<td '.$v_align.'>
                                                            <table cellspacing=0 cellpadding=0>
                                                                <tr>
                                                                    <td height="3px"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <a href="setting.htm" 
                                                                            style="text-decoration:none;">
                                                                                <img src="images/icon/setting1.png" 
                                                                                '.$img_size.' style="border:none;">
                                                                                '.$set_nbsp.'
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                                </table>
                                                        </td>
                                                        <td>';
                                                        if($interface == "setting.php")
                                                        {
                                                            echo'<b class="hs1">Setting</b>';
                                                        } 
                                                        else
                                                        { 
                                                            echo'<a href="setting.htm" class="hs2">Setting</a>';
                                                        }
                                                        echo'</td>';
                                                    }
                                                    echo'<td>
                                                            '.$set_nbsp.'|'.$set_nbsp.'
                                                        </td>
                                                        <td>
                                                            <table cellspacing=0 cellpadding=0>
                                                                <tr>
                                                                    <td height="3px"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <a href="help.htm" style="text-decoration:none;">
                                                                                <img src="images/icon/help1.png" 
                                                                                '.$img_size.' style="border:none;">
                                                                                '.$set_nbsp.'
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td> 
                                                        <td '.$v_align.'>';
                                                        echo '<a href="logout.htm" class="hs2">
                                                                Logout
                                                            </a>&nbsp;
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
				</table> '; 		
	?>  				  
    
