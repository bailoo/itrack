<?php
    include_once('util_session_variable.php');
    include_once('util_php_mysql_connectivity.php');
    include_once("coreDb.php");
    $DEBUG = 0; 
    
    $route_post = $_POST['RouteNo'];
    $shift_post = $_POST['ReportShift'];
    $date_post = $_POST['ReportDate'];
    $remarks_post = $_POST['Remarks'];
    $mark_completed_post = $_POST['MarkCompleted'];
      
    $route_post1 = explode("#",$route_post);
    $shift_post1 = explode("#",$shift_post);
    $date_post1 = explode("#",$date_post);
    $remarks_post1 = explode("#",$remarks_post);
    $mark_completed_post1 = explode("#",$mark_completed_post);
    
    for($i=0;$i<sizeof($route_post1);$i++) {
       
        $result = updateHourlyRemark($route_post1[$i], $shift_post1[$i], $date_post1[$i], $remarks_post1[$i], $mark_completed_post1[$i], $DbConnection);        
    }

    if($result) {
        $message="Remark updated Successfully";
    }
    else {
        $message="Unable to update remark";
    } 

    echo'<table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
            <tr>
                <td colspan="3" align="center"><b>'.$message.'</b></td>    
            </tr>
        </table><br>'; 
    echo'<center><a href="javascript:show_option(\'manage\',\'update_hourly_remark_prev\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
    
?> 
	

