<?php
  include_once('util_session_variable.php');
  include_once('util_php_mysql_connectivity.php');
	
	$action_type1 = $_POST['action_type'];
	$query="SELECT account_id FROM account WHERE superuser='$superuser' AND user='$user' AND grp='admin' AND status='1'";
  $result=mysql_query($query,$DbConnection);
  $row=mysql_fetch_object($result);
  $user_account_id = $row->account_id;  
  
  $old_value= Array(); $new_value=Array(); $field_name=Array(); $table_name="landmark";
  
  if($action_type1=="add") 
  {
    $landmark_name1 = trim($_POST['landmark_name']); 
    $query1="SELECT landmark_name FROM landmark WHERE create_id='$account_id' AND landmark_name='$landmark_name1' AND status='1'";
    if($DEBUG ==1 )print_query($query1);
    $result1=mysql_query($query1,$DbConnection);
    $num_rows1=mysql_num_rows($result1);      
    if($num_rows1>0)
    {$flag=1;}
    else
    {
      $landmark_coord1 = base64_encode(trim($_POST['landmark_coord']));
      $zoom_level_1 = trim($_POST['zoom_level']);    
      $query2 ="select Max(sno)+1 as seiral_no from landmark";  ///// for auto increament of landmark_id ///////////
      $result2=mysql_query($query2,$DbConnection);
      $row2=mysql_fetch_object($result2);
      $max_no= $row2->seiral_no; 
      if($max_no==""){$max_no=1;} 	  
      $query3="INSERT INTO landmark(landmark_id,landmark_name,landmark_coord,user_account_id,zoom_level,status,create_id,create_date) VALUES('$max_no','$landmark_name1','$landmark_coord1','$user_account_id','$zoom_level_1','1','$account_id','$date')";    
      $result3=mysql_query($query3,$DbConnection);     	  
      if($result3){$flag=2; $action_perform="Added";} 
    }  
  }
  
  else if($action_type1=="edit")
  {
    $landmark_id1 = $_POST['landmark_id'];
    $edit_landmark_name1  =trim($_POST['edit_landmark_name']);
    $new_value[]=$edit_landmark_name1;
    $query1="SELECT landmark_name FROM landmark WHERE create_id='$account_id' AND landmark_name='$edit_landmark_name1' AND status='1'";
    if($DEBUG ==1 )print_query($query1);
    $result1=mysql_query($query1,$DbConnection);
    $num_rows1=mysql_num_rows($result1);      
    if($num_rows1>0)
    {$flag=1;}
    else
    {
      $edit_landmark_point1=base64_encode(trim($_POST['edit_landmark_point']));   $new_value[]=$edit_landmark_point1;
      $edit_zoom_level_1=trim($_POST['edit_zoom_level']);                         $new_value[]=$edit_zoom_level_1; 
      
      $query2="SELECT * FROM landmark where create_id='$account_id' AND status='1'";
      $result2=mysql_query($query2,$DbConnection);
      $row=mysql_fetch_object($result);
      $old_value[]=$row2->landmark_name;   $field_name[]="landmark_name";
      $old_value[]=$row2->landmark_coord;  $field_name[]="landmark_coord";
      $old_value[]=$row2->zoom_level;      $field_name[]="zoom_level";
      
      $query3="UPDATE landmark SET landmark_name='$edit_landmark_name1',landmark_coord='$edit_landmark_point1',zoom_level='$edit_zoom_level_1',edit_id='$account_id',edit_date='$date' WHERE landmark_id='$landmark_id1' AND status='1'";
      $result3=mysql_query($query3,$DbConnection);     
      $ret_result=track_table($landmark_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
      //echo "ret_result=".$ret_result; 
      if($result3 && $ret_result=="success"){$flag=2;$action_perform="Updated";}
     }      
  }
  
  else if ($action_type1=="delete")
  {
    $landmark_id1 = $_POST['landmark_id']; $old_value[]="1"; $new_value[]="0"; $field_name[]="status";        
    $query1="UPDATE landmark SET edit_id='$account_id',edit_date='$date',status='0' WHERE landmark_id='$landmark_id1' AND status='1'";
    $result1=mysql_query($query1,$DbConnection);    
    $ret_result=track_table($landmark_id1,$table_name,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);    
    if($ret_result=="success"  && $result1){$flag=1;$action_perform="Deleted";}
  }
  if($flag==1)
	{
	 $msg = "Landmark Name already exist so unable to process request";
	 $msg_color = "blue";
		// echo"<center><br><br><FONT color=\"blue\" size=\"2\"><strong>Geofence ".$action_perform." Added Successfully<br><br></strong></font></center>";		
	}	
  else if($flag==2)
	{
	 $msg = "Landmark ".$action_perform." Successfully";
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
  include_once('manage_landmark.php'); 
?>   