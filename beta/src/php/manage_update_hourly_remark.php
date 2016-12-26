<?php

//error_reporting(-1);
//ini_set('display_errors', 'On');
//include_once('Hierarchy.php');
echo "result_hourly_remark##";

include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('coreDb.php');

//include_once('manage_hierarchy_header1.php');
//$root = $_SESSION['root'];

$date_post = $_POST['date1'];
$shift_post = $_POST['shift'];

$date_post = str_replace('/','-',$date_post);
//echo "DatePost=".$date_post." ,Shift=".$shift_post;

//EVENING SHIFT	
//$data = getHourlyRemarkDetail($date_post, $shift_post, $DbConnection);

echo'<input type="hidden" id="account_id_hidden" value=' . $common_id1 . '>';
echo'<input type="hidden" id="shift" value=' . $shift1 . '>';

echo "<center><Strong>Update Remark</Strong></center><br>";
echo"
    <!--<form name='manage1' method='post' target='_blank'>-->
    <table width='100%'>						
        <tr>
            <td align='center'>			
                <div style='overflow:auto;width:1000px;height:400px;'> 	
                    <table border=1 cellspacing=4 cellpadding=4 class='module_left_menu' rules='all' bordercolor='gray'>";
                    echo '<tr bgcolor="#d3d3d3">
                            <td><strong>SNo</strong></td>
                            <td><strong>RouteNo</strong></td>
                            <td><strong>ReportShift</strong></td>
                            <td><strong>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
                            <td><strong>Vehicles</strong></td>
                            <td><strong>PendingCustomers</strong></td>
                            <td><strong>CompletedCustomers</strong></td>						
                            <!--<td><strong>RouteCompleted</strong></td>-->
                            <td><strong>CompletedAuto</strong></td>
                            <td><strong>Remarks</strong></td>
                            <td><strong>Mark Complete</strong></td>                             
                          </tr>';
                          $data = getHourlyRemarkDetail($date_post, $shift_post, $DbConnection);
                          $route_size = sizeof($data);  
                            $sno = 1;
                            for($i=0;$i<$route_size;$i++) {
                                                         
                                if($data[$i]['RouteCompleted']==1) {
                                  
                                    $routeCompleted = "Yes";
                                    $routeCompletedAuto = "No";
                                    
                                    if($data[$i]['CompletedAuto']==1) { 
                                        $routeCompletedAuto = "Yes";                                         
                                    }

                                    echo '<tr>';
                                    echo '<td>'.$sno.'</td>';
                                    echo '<td>'.$data[$i]['RouteNo'].'</td>';
                                    echo '<td>'.$data[$i]['ReportShift'].'</td>';
                                    echo '<td>'.$data[$i]['Date'].'</td>';                                                            
                                    echo '<td width="150px">'.$data[$i]['Vehicles'].'</td>';
                                    echo '<td width="150px">'.$data[$i]['PendingCustomers'].'</td>';
                                    echo '<td width="150px">'.$data[$i]['CompletedCustomers'].'</td>';
                                    //echo '<td><input type="text" width="5" size="3" value="'.$routeCompleted.'" id="routeCompleted" readOnly></td>';

                                    echo '<td><strong>'.$routeCompletedAuto.'</strong></td>';
                                    echo '<td><input type="text" width="5" value="'.$data[$i]['Remarks'].'" readOnly></td>';
                                    echo '<td><input type="checkbox" checked disabled></td>';
                                    echo '</tr>';
                                    $sno++;
                                }                                
                            }                              
                                                                         
                            echo '<input type="hidden" id="route_size" value="'.$route_size.'"/>';
                            
                            for($i=0;$i<$route_size;$i++) {                             
                                                    
                                if($data[$i]['RouteCompleted'] ==0) {
                                    
                                    echo '<input type="hidden" id="valid_entries'.$i.'" value="1"/>';
                                    
                                    $routeCompleted = "No";
                                    $routeCompletedAuto = "No";
                                    
                                    echo '<tr>';
                                    echo '<td>'.$sno.'</td>';
                                    echo '<td>'.$data[$i]['RouteNo'].'<input type="hidden" id="RouteNo'.$i.'" value="'.$data[$i]['RouteNo'].'"></td>';
                                    echo '<td>'.$data[$i]['ReportShift'].'<input type="hidden" id="ReportShift'.$i.'" value="'.$data[$i]['ReportShift'].'"></td>';
                                    echo '<td>'.$data[$i]['Date'].'<input type="hidden" id="ReportDate'.$i.'" value="'.$data[$i]['Date'].'"></td>';                                                            
                                    echo '<td width="150px">'.$data[$i]['Vehicles'].'</td>';
                                    echo '<td width="150px">'.$data[$i]['PendingCustomers'].'</td>';
                                    echo '<td width="150px">'.$data[$i]['CompletedCustomers'].'</td>';
                                    //echo '<td><input type="text" width="5" size="3" value="'.$routeCompleted.'" id="routeCompleted" readOnly></td>';
             
                                    echo '<td><strong>'.$routeCompletedAuto.'</strong></td>';
                                    echo '<td><input type="text" width="5" id="Remarks'.$i.'" value="'.$data[$i]['Remarks'].'"></td>';
                                    echo '<td><input type="checkbox" id="MarkCompleted'.$i.'"></td>';  
                                    echo '</tr>';
                                    $sno++;
                                    
                                } else {
                                    echo '<input type="hidden" id="valid_entries'.$i.'" value="0"/>';
                                }                           
                            }                          
                        echo"</table>
                </div>
        </td>
    </tr>
</table>
       
<!--</form>-->";
if($route_size > 0) {
    echo '<br><center><input type="button" onclick="javascript:update_hourly_remark();" value="Update Hourly Remark"/></center>';
}
?>  

