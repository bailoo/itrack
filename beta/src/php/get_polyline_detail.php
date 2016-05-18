<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('coreDb.php');
$route_names = $_POST['route_names'];   
$shift = $_POST['shift'];  
echo 'live_polyline##';
$routeComboArr=getAccountRoutes($account_id,$route_names, $DbConnection);
// print_r($routeComboArr);
if($routeComboArr!="No Data Found")
 {
    $jsonArray=json_encode($routeComboArr);
    echo"<input type='hidden' value='".$jsonArray."' id='routeJsonData'>";
 echo"
     <td>
         &nbsp;<select id='user_type_option' style='font-size:10px' onchange='javascript:showRouteOnLiveMap(this.value,\''.$shift.'\');'>
             <option value='select'>Routes</option>";  
             foreach($routeComboArr as $key=>$value)
             {
                 echo"<option value=".$key.">".$routeComboArr[$key]['polylineName']."</option>";
             }
         echo"</select>  &nbsp;   
     </td>";
 }
            

