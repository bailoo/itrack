<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php'); 	
	$DEBUG = 0;
	$action_type1 = $_POST['action_type'];
	$query="SELECT account_id FROM account WHERE superuser='$superuser' AND user='$user' AND grp='admin' AND status='1'";
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  $user_account_id = $row->account_id;  
  
  $old_value= Array();
  $new_value=Array();
  $field_name=Array(); 
  $table_name="route"; 
  
  include_once('track_log.php');
  
  if($action_type1=="add") 
  {     
    $route_name1=trim($_POST['route_name']);
    $query1="SELECT route_name FROM route WHERE create_id='$account_id' AND route_name='$route_name1' AND status='1'";
  if($DEBUG ==1 )
    print_query($query1);
    $result1=mysql_query($query1,$DbConnection);
    $num_rows1=mysql_num_rows($result1);
    
    if($num_rows1>0)
    {
      $flag=1;    
    }
    else
    {     
      $route_coord1=base64_encode(trim($_POST['route_coord']));
      
      $query2 ="select Max(sno)+1 as seiral_no from route";  ///// for auto increament of landmark_id ///////////   
      $result2=mysql_query($query2,$DbConnection);
      $row1=mysql_fetch_object($result2);
      $max_no= $row1->seiral_no;
      if($max_no==""){$max_no=1;}
      
      $query2="INSERT INTO route(user_account_id,route_id,route_name,route_coord,status,create_id,create_date) VALUES('$user_account_id','$max_no','$route_name1','$route_coord1','1','$account_id','$date')";    
      $result2=mysql_query($query2,$DbConnection);
      if($result2){$flag=2;$action_perform="Added";}
    }   
  }
  
  else if($action_type1=="edit")
  {
    $route_id1 = $_POST['route_id'];
    $edit_route_name1 =trim($_POST['edit_route_name']); 
    //echo "route_name=".$edit_route_name1;
    $query1="SELECT route_name FROM route WHERE create_id='$account_id' AND route_name='$edit_route_name1' AND status='1'";
    if($DEBUG ==1 )
    print_query($query1);
    $result1=mysql_query($query1,$DbConnection);
    $num_rows1=mysql_num_rows($result1);
    //echo "num rows".$num_rows1;
    if($num_rows1>0)
    {
      $flag=1;    
    }
    else
    {
      $new_value[]=$edit_route_name1;      
      $edit_route_coord1 =base64_encode(trim($_POST['edit_route_coord']));
      $new_value[]=$edit_route_coord1;           
           
      $query2="SELECT * FROM route where route_id='$route_id1' AND status='1'";
      if($DEBUG ==1 )
    print_query($query2);
      $result2=mysql_query($query2,$DbConnection);
      $row2=mysql_fetch_object($result2);
      $route_name1=$row2->route_name;
      $old_value[] =$route_name1;
      $field_name[]="route_name";
      $route_coord1=$row1->route_coord;         
      $old_value[] = $route_coord1;
      $field_name[]="route_coord"; 
        
      $query2="UPDATE route SET route_name='$edit_route_name1',route_coord='$edit_route_coord1',edit_id='$account_id',edit_date='$date' WHERE route_id='$route_id1'";
      if($DEBUG ==1 )
    print_query($query2);
      $result2=mysql_query($query2,$DbConnection); 
           
      $ret_result=track_table($route_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
      if($ret_result=="success" && $result2){$flag=2;$action_perform="Updated";} 
    }     
  }
  
  else if ($action_type1=="delete")
  {
    $route_id1 = $_POST['route_id'];    
    $query1="UPDATE route SET edit_id='$account_id',edit_date='$date',status='0' WHERE route_id='$route_id1' AND status='1'"; 
    $result1=mysql_query($query1,$DbConnection);    
    $old_value[]="1";
    $new_value[]="0";
    $field_name[]="status";     
    $ret_result=track_table($route_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
    if($ret_result=="success"  && $result1){$flag=2;$action_perform="Deleted";}
  } 
  
//echo "flag=".$flag; 
if($flag==1)
{
 $msg = "Vehicle Name already exist so unable to process request";
 $msg_color = "red";
	// echo"<center><br><br><FONT color=\"blue\" size=\"2\"><strong>Geofence ".$action_perform." Added Successfully<br><br></strong></font></center>";		
}	
else if($flag==2)
{
 $msg = "Route ".$action_perform." Successfully";
 $msg_color = "green";
	// echo"<center><br><br><FONT color=\"blue\" size=\"2\"><strong>Geofence ".$action_perform." Added Successfully<br><br></strong></font></center>";		
}	
else
{
 $msg = "Sorry! Unable to process request.";
 $msg_color = "red";
   //echo "<center><br><br><FONT color=\"red\" size=\"2\">Sorry! Unable to process request.<br><br></strong></font></center>";
}
  
echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
include_once('manage_route.php');  

  
?>   