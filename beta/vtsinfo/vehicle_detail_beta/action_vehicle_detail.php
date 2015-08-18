<?php
include_once("../util_session_variable.php");

if($account)
{
  $HOST = "localhost";
  $DBASE = "iespl_vts_beta";
  $USER = "root";
  $PASSWD = "mysql";
  $DbConnection = mysql_connect($HOST,$USER,$PASSWD) or die("could not connect to DB test");
  mysql_select_db ($DBASE, $DbConnection) or die("could not find DB");
    
  $imei1 = $_POST['imei'];
  $vname1 = $_POST['vname'];  
  //echo "<br>REC".$imei1." ,".$vname1;
  
  date_default_timezone_set("Asia/Calcutta");
  $current_datetime = date("Y-m-d H:i:s");
  $current_date = date("Y-m-d");
  
  //echo "dbcon=".$DbConnection;
  echo '<center>';
  echo '<table align="center">';
  $detail ="";
    
  if($imei1)
  {
    //echo "one";
    $query = "SELECT DISTINCT vehicle.vehicle_name, vehicle_grouping.account_id FROM vehicle,vehicle_grouping,vehicle_assignment WHERE ".
    "vehicle.vehicle_id = vehicle_assignment.vehicle_id AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id ".
    "AND vehicle_assignment.device_imei_no='$imei1' AND vehicle.status=1 ".
    "AND vehicle_assignment.status=1";
    
    //echo $query."<br>";         
    $result = mysql_query($query,$DbConnection);
    $numrow = mysql_num_rows($result);
   
    //echo "numrows=".$numrow."<br>"; 
    $j=0;
    $account_id[0]="";
        
    while($row = mysql_fetch_object($result))
    {      
      $acc_id_tmp = $row->account_id;
      //echo "<br>acid=".$acc_id_tmp;      
      $account_id1[$j] = $acc_id_tmp;
      $vehicle_name1[$j] = $row->vehicle_name;
      //echo "<br>account_id[j]=".$account_id1[$j]." ,sizeof(account_id)=".sizeof($account_id1).",sizeof(vehicle_name)=".sizeof($vehicle_name);
      $j = $j + 1;         
    }
    
    $msg="VehicleName:";
    $size = sizeof($account_id1);
    //echo "<br>size=".$size; 
    for($i=0;$i<$size;$i++)
    {    
      $query = "SELECT user_id from account WHERE account_id='$account_id1[$i]' AND status=1";    
      $result = mysql_query($query,$DbConnection);
      //echo "<br>".$query;    
      if($row = mysql_fetch_object($result))
      {
        $userid = $row->user_id;      
        $detail = $detail."<tr><td>".$msg." : </td><td><font color=red><strong>".$vehicle_name1[$i]."</strong></font> </td><td><font color=green></strong>(".$userid.")</strong></font></td></tr>";
      }
    }
  }  
  
  else if($vname1)
  {
    //echo "one";
    $query = "SELECT DISTINCT vehicle_assignment.device_imei_no, vehicle_grouping.account_id FROM ".
    "vehicle_grouping,vehicle_assignment,vehicle WHERE ".
    "vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id ".
    "AND vehicle_grouping.vehicle_id = vehicle.vehicle_id  AND vehicle_assignment.vehicle_id = vehicle.vehicle_id ".
    "AND vehicle_assignment.vehicle_id = vehicle_grouping.vehicle_id ".
    "AND vehicle.vehicle_name='$vname1' AND vehicle.status=1 ".
    "AND vehicle_assignment.status=1 AND vehicle_grouping.status=1";  
    //echo $query;
    $result = mysql_query($query,$DbConnection);
    
    while($row = mysql_fetch_object($result))
    {
      $imei[] = $row->device_imei_no;  
      $account_id2[] = $row->account_id;       
    }
    
    $msg="IMEINo";
      
    for($i=0;$i<sizeof($account_id2);$i++)
    {    
      $query = "SELECT user_id from account WHERE account_id='$account_id2[$i]' AND status=1";    
      $result = mysql_query($query,$DbConnection);
      //echo "Q=".$query;    
      if($row = mysql_fetch_object($result))
      {
        $userid = $row->user_id;      
        $detail = $detail."<tr><td>".$msg." : </td><td><font color=red><strong>".$imei[$i]."</strong></font> </td><td><font color=green><strong>(".$userid.")</strong></font></td></tr>";
      }
    }  
  }
  
  //echo "<br><center>".$msg." : <font color=red><strong>".$vdetail."</font> <font color=green>(".$userid.")</font></center>";
  echo $detail;
  
  if($detail=="")
    echo '<tr><td><font color=red>No Record Found</font></td></tr>';
  
  echo '</table>';
  echo '</center>'; 
}
else
{
	echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"1; URL=../index.php\">";
} 
?>