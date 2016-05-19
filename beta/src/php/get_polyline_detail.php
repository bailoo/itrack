<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php');
include_once('coreDb.php');
$route_names = $_POST['route_names'];   
$shift = $_POST['shift'];  
echo 'live_polyline##';
//echo "RouteName=".$route_names." ,Shift=".$shift;
$route_names = substr($route_names, 0, -1);
$final_route = '';
$route_tmp1 = explode(',',$route_names);
$k=0;
$temp_arr = array();
for($i=0;$i<sizeof($route_tmp1);$i++) {
	$route_tmp2 = explode('-',$route_tmp1[$i]);
	for($j=0;$j<sizeof($route_tmp2);$j++) {
            
            $route_tmp2[$j] = str_replace('@','',$route_tmp2[$j]);
            
            if($route_tmp2[$j]!='') {
                $exist = false;
                if(sizeof($temp_arr)>0) {
                    for($m=0;$m<sizeof($temp_arr);$m++) {
                        if($route_tmp2[$j]==$temp_arr[$m]) {
                            $exist = true;
                        }
                    }
                }
                if(!$exist) {
                    if($k==0) {
                        $final_route.="'".$route_tmp2[$j]."'";     
                        $temp_arr[] = $route_tmp2[$j];
                        $k++;
                    } else {
                        $final_route.=",'".$route_tmp2[$j]."'";
                        $temp_arr[] = $route_tmp2[$j];
                    }                            
                }
            }            
	}
}
//echo "final_route=".$final_route." ,shift=".$shift;
//exit();
$routeComboArr=getAccountRoutes($account_id, $final_route, $DbConnection);
//print_r($routeComboArr);
if($routeComboArr!="No Data Found")
 {
    $jsonArray=json_encode($routeComboArr);
    echo"<input type='hidden' value='".$jsonArray."' id='routeJsonData'>";
    echo"
     <td>
         &nbsp;<select id='user_type_option' style='font-size:10px' onchange='javascript:showRouteOnLiveMap(this.value,$shift);'>
             <option value='select'>Routes</option>";  
             foreach($routeComboArr as $key=>$value)
             {
                 echo"<option value=".$key.">".$routeComboArr[$key]['polylineName']."</option>";
             }
         echo"</select>  &nbsp;   
     </td>";
 }
            

