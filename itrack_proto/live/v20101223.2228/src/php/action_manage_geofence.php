<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	$DEBUG=0;
	$action_type1 = $_POST['action_type'];
	$query="SELECT account_id FROM account WHERE superuser='$superuser' AND user='$user' AND grp='admin' AND status='1'";
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  $user_account_id = $row->account_id;   
  
  $old_value= Array();$new_value=Array();
  $field_name=Array();$table_name="geofence"; 
  
  if($action_type1=="add") 
  {     
    $geo_name1 = trim($_POST['geo_name']);     
    $query1="SELECT geo_name FROM geofence WHERE create_id='$account_id' AND geo_name='$geo_name1' AND status='1'";
    if($DEBUG ==1 )print_query($query1);
    $result1=mysql_query($query1,$DbConnection);
    $num_rows1=mysql_num_rows($result1);      
    if($num_rows1>0)
    {$flag=1;}
    else
    { 
      $geo_coord1 = $_POST['geo_coord'];
      $geo_coord1=trim($geo_coord1);    
      $geo_coord1 = base64_encode($geo_coord1); 
    
      $query2 ="select Max(sno)+1 as seiral_no from geofence";  ///// for auto increament of landmark_id ///////////   
      $result2=mysql_query($query2,$DbConnection);
      $row2=mysql_fetch_object($result2);
      $max_no= $row2->seiral_no;
      if($max_no==""){$max_no=1;}     
      $query3="INSERT INTO geofence(user_account_id,geo_id,geo_name,geo_coord,status,create_id,create_date) VALUES('$user_account_id','$max_no','$geo_name1','$geo_coord1','1','$account_id','$date')";
      if($DEBUG ==1 )print_query($query3);     
      $result3=mysql_query($query3,$DbConnection);          	  
      if($result3){$flag=2;$action_perform="Added";}    
    }   
  }
  
  else if($action_type1=="edit")
  {
    $geo_id1 = $_POST['geo_id'];    
    $edit_geo_name1 =trim($_POST['edit_geo_name']);
    $new_value[]=$edit_geo_name1;
    $query1="SELECT geo_name FROM geofence WHERE create_id='$account_id' AND geo_name='$edit_geo_name1' AND status='1'";
    if($DEBUG ==1 )print_query($query1);
    $result1=mysql_query($query1,$DbConnection);
    $num_rows1=mysql_num_rows($result1);      
    if($num_rows1>0)
    {$flag=1;}
    else
    {      
      $edit_geo_coord1 =base64_encode(trim($_POST['edit_geo_coord']));     
      $new_value[]=$edit_geo_coord1;           
           
      $query2="SELECT * FROM geofence where geo_id='$geo_id1' AND status='1'";
      $result2=mysql_query($query2,$DbConnection);
      $row2=mysql_fetch_object($result2);
      $geo_name1=$row2->geo_name;
      $old_value[] =$geo_name1;
      $field_name[]="geo_name";
      $geo_coord1=$row2->geo_coord;         
      $old_value[] = $geo_coord1;
      $field_name[]="geo_coord"; 
      
      $query3="UPDATE geofence SET geo_name='$edit_geo_name1',geo_coord='$edit_geo_coord1',edit_id='$account_id',edit_date='$date' WHERE geo_id='$geo_id1'";
      $result3=mysql_query($query3,$DbConnection); 
         
      $ret_result=track_table($geo_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
      if($ret_result=="success" && $result3){$flag=2;$action_perform="Updated";} 
    }     
  }
  else if ($action_type1=="delete")
  {
    $geo_id1 = $_POST['geo_id'];    
    $query1="UPDATE geofence SET edit_id='$account_id',edit_date='$date',status='0' WHERE geo_id='$geo_id1' AND status='1'"; 
    $result1=mysql_query($query1,$DbConnection);    
    $old_value[]="1";$new_value[]="0";$field_name[]="status";     
    $ret_result=track_table($geo_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
    if($ret_result=="success"  && $result1){$flag=1;$action_perform="Deleted";}
  }   
  if($flag==1)
	{
	 $msg = "Geofence Name already exist so unable to process request";
	 $msg_color = "red";
		//echo"<center><br><br><FONT color=\"blue\" size=\"2\"><strong>Geofence ".$action_perform." Added Successfully<br><br></strong></font></center>";		
	}
  else if($flag==2)
	{
	 $msg = "Geofence ".$action_perform." Successfully";
	 $msg_color = "green";
		//echo"<center><br><br><FONT color=\"blue\" size=\"2\"><strong>Geofence ".$action_perform." Added Successfully<br><br></strong></font></center>";		
	}	
	else
	{
	 $msg = "Sorry! Unable to process request.";
	 $msg_color = "red";
	  //echo"<center><br><br><FONT color=\"red\" size=\"2\">Sorry! Unable to process request.<br><br></strong></font></center>";
  }
  
  echo "<center><br><br><FONT color=\"".$msg_color."\" size=\"2\">".$msg."<br><br></strong></font></center>";
  include_once('manage_geofence.php');                  
  
?>
        