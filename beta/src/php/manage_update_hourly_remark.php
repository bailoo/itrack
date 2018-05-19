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
$ctype_post = $_POST['ctype'];
//echo "CTYPE=".$ctype_post;
$date_post = str_replace('/','-',$date_post);
//echo "DatePost=".$date_post." ,Shift=".$shift_post;

//EVENING SHIFT	
//$data = getHourlyRemarkDetail($date_post, $shift_post, $DbConnection);

echo'<input type="hidden" id="account_id_hidden" value=' . $common_id1 . '>';
echo'<input type="hidden" id="shift" value=' . $shift1 . '>';

echo '<center><Strong>Update Remark &nbsp;&nbsp; | &nbsp;&nbsp;</strong>
        <span><Strong>Select Status</strong>
             <select name="c_type" id="c_type" onchange="javascript:show_hourly_routes(2);">';
                 if($ctype_post=="") {
                    echo '<option value="0">All</option>';
                    echo '<option value="1">Completed</option>';
                    echo '<option value="2">Incompleted</option>';
                 } else {
                    if($ctype_post ==0) {
                        echo '<option value="0" selected>All</option>';
                        echo '<option value="1">Completed</option>';
                        echo '<option value="2">Incompleted</option>';
                    }
                    else if($ctype_post ==1) {
                        echo '<option value="0">All</option>';
                        echo '<option value="1" selected>Completed</option>';
                        echo '<option value="2">Incompleted</option>';
                    }                
                    else if($ctype_post ==2) {
                        echo '<option value="0">All</option>';
                        echo '<option value="1">Completed</option>';
                        echo '<option value="2" selected>Incompleted</option>';
                    }
                 }
             echo '</select>
         </span>
</center><br>';

//echo '<div style='width:200px;overflow:scroll;">';

echo '<div class="panel panel-default">
    <div class="panel-body" style="width:1100px;height:400px;overflow:scroll;">
        <table id="myTable" class="table table-fixedheader table-bordered table-striped" style="width:1900px;">
            <thead>
                <tr>
                    <th width="50px;"><strong>SNo</strong></th>
                    <th width="110px;"><strong>RouteNo</strong></th>
                    <th width="100px;"><strong>ReportShift</strong></th>
                    <th width="100px;"><strong>Date</strong></th>
                    
                    <th width="120"><strong>CompletedAuto</strong></th>
                    <th width="200px;"><strong>Remarks</strong></th>                    
                    <th width="100px;"><strong>Complete</strong></th>
                    <th width="150px;"><strong>Last UpdateTime</strong></th>
                    
                    <th width="300px;"><strong>Vehicles</strong></th>
                    <th width="300px;"><strong>PendingCustomers</strong></th>
                    <th width="300px;"><strong>CompletedCustomers</strong></th>						
                    <!--<th class="col-xs-8"><strong>RouteCompleted</strong></th>-->
                    
                </tr>
            </thead>';
                          
            echo '<tbody style="height:280px">';

               $data = getHourlyRemarkDetail($date_post, $shift_post, $DbConnection);
               $route_size = sizeof($data);  
               $sno = 1;
                
               if($ctype_post==0 || $ctype_post==1 || $ctype_post=="") {
                    for($i=0;$i<$route_size;$i++) {

                         if($data[$i]['RouteCompleted']==1) {

                             $routeCompleted = "Yes";
                             $routeCompletedAuto = "No";

                             if($data[$i]['CompletedAuto']==1) { 
                                 $routeCompletedAuto = "Yes";                                         
                             }

                             echo '<tr>';
                             echo '<td width="50px;">'.$sno.'</td>';
                             echo '<td width="110px;">'.$data[$i]['RouteNo'].'</td>';
                             echo '<td width="100px;">'.$data[$i]['ReportShift'].'</td>';
                             echo '<td width="100px;">'.$data[$i]['Date'].'</td>';    

                             echo '<td width="120px;"><strong>'.$routeCompletedAuto.'</strong></td>';
                             
                             if($routeCompletedAuto=="No") {
                                echo '<td width="200px;"><input type="text" width="5" value="'.$data[$i]['Remarks'].'" readOnly></td>';                             
                                echo '<td width="100px;"><input type="checkbox" checked disabled></td>';
                             } else {
                                echo '<td width="200px;"><input type="text" width="5" value="'.$data[$i]['Remarks'].'"></td>';                             
                                echo '<td width="100px;"><input type="checkbox" checked></td>';                                 
                             }
                             echo '<td width="150px;">'.$data[$i]['UpdateTime'].'</td>';

                             echo '<td width="300px">'.$data[$i]['Vehicles'].'</td>';
                             echo '<td width="300px">'.$data[$i]['PendingCustomers'].'</td>';
                             echo '<td width="300px">'.$data[$i]['CompletedCustomers'].'</td>';
                             //echo '<td><input type="text" width="5" size="3" value="'.$routeCompleted.'" id="routeCompleted" readOnly></td>';

                             echo '</tr>';
                             $sno++;
                         }                                
                     }                              
               }
                echo '<input type="hidden" id="route_size" value="'.$route_size.'"/>';

                if($ctype_post==0 || $ctype_post==2 || $ctype_post=="") {
                    for($i=0;$i<$route_size;$i++) {                             

                        if($data[$i]['RouteCompleted'] ==0) {

                            echo '<input type="hidden" id="valid_entries'.$i.'" value="1"/>';

                            $routeCompleted = "No";
                            $routeCompletedAuto = "No";

                            echo '<tr>';
                            echo '<td width="50px;">'.$sno.'</td>';
                            echo '<td width="110px;">'.$data[$i]['RouteNo'].'<input type="hidden" id="RouteNo'.$i.'" value="'.$data[$i]['RouteNo'].'"></td>';
                            echo '<td width="100px;">'.$data[$i]['ReportShift'].'<input type="hidden" id="ReportShift'.$i.'" value="'.$data[$i]['ReportShift'].'"></td>';
                            echo '<td width="100px;">'.$data[$i]['Date'].'<input type="hidden" id="ReportDate'.$i.'" value="'.$data[$i]['Date'].'"></td>';                                                            

                            echo '<td width="120"><strong>'.$routeCompletedAuto.'</strong></td>';
                            echo '<td width="200px;"><input type="text" width="5" id="Remarks'.$i.'" value="'.$data[$i]['Remarks'].'"></td>';                            
                            echo '<td width="100px;"><input type="checkbox" id="MarkCompleted'.$i.'"></td>'; 
                            echo '<td width="150px;">'.$data[$i]['UpdateTime'].'</td>';

                            echo '<td width="300px">'.$data[$i]['Vehicles'].'</td>';
                            echo '<td width="300px">'.$data[$i]['PendingCustomers'].'</td>';
                            echo '<td width="300px">'.$data[$i]['CompletedCustomers'].'</td>';
                            //echo '<td><input type="text" width="5" size="3" value="'.$routeCompleted.'" id="routeCompleted" readOnly></td>';

                            echo '</tr>';

                            $sno++;

                        } else {
                            echo '<input type="hidden" id="valid_entries'.$i.'" value="0"/>';
                        }                           
                    }
                }
                echo "</tbody>
            </table>
          </div>
      </div>
    </div>
    
<!--</div>-->
";

?>  
<br>
<center><input type="button" onclick="javascript:update_hourly_remark();" value="Update Hourly Remark"/></center>