<?php
include_once('util_session_variable.php');
include_once('util_php_mysql_connectivity.php'); 
include_once('coreDb.php'); 
$action = $_POST['action_type'];
$table="milestone_assignment"; /////////////for track table
$DEBUG=0;
if($DEBUG==1)
{echo"action=".$action."<br>";}
	
if($action=="add")
{    
  $ms_name=$_POST['ms_name'];
  $ms_type=$_POST['ms_type']; 
  $pointstmp=$_POST['points'];  
  $points1 = base64_encode($pointstmp);
  $local_group_id1 = $_POST['local_group_id'];

	
	$max_no= getMaxCountMilestoneAssign($DbConnection);
	if($max_no=="")
	{
		$max_no=1; 
	}
    
  
  $result =insertMilestoneAssign($max_no,$local_group_id1,$ms_name,$ms_type,$points1,$account_id,$date,$DbConnection);
  if($result)
  {
	$message="<br>
	<center> 
		<font color=\"Green\" size=4>
			<strong>MileStone Added Successfully!</strong>
		</font>
	</center>";
  } 
  else
  {
	$message="<br>
	<center>
		<font color=\"Green\" size=4>
			<strong>Unable To Process Request!</strong>
		</font>
	</center>";
  }
}

else if($action == "edit")
{
  $ms_id=$_POST['ms_id'];
  
  $new_ms_name=$_POST['ms_name'];    $new_value[] = $ms_name;   
  $new_ms_type=$_POST['ms_type'];    $new_value[] = $ms_type; 
  $new_pointstmp=$_POST['points'];
  $new_points = base64_encode($new_pointstmp);  $new_value[] = $new_points; 
 
  $result = editMilestoneAssign($new_ms_name,$new_ms_type,$new_points,$ms_id,$DbConnection);
  if($result)
  {
    //$msg = track_table($ms_id,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
    $message='<br>
				<FONT color=\"green\">
					<strong>Milestone updated successfully</strong>
				</font>
			 <br>';
    
  }
  else
  {
	$message='<br>
				<FONT color=\"green\">
					<strong>Unable To Process Request</strong>
				</font>
		    <br>';
  }   
}

else if($action == "delete")
{
  $ms_id=$_POST['ms_id'];  
  $points1 = base64_encode($pointstmp);    
  $result = deleteMilestoneAssign($ms_id,$DbConnection);   
  if($result)
  {
    //$old_value[]="1";$new_value[]="0";$field_name[]="status";     
    //$msg = track_table($ms_id,$table,$field_name,$old_value,$new_value,$account_id,$date,$DbConnection);
    $message='<br><FONT color=\"green\"><strong>Milestone Deleted successfully</strong></font><br>';
   
  }
  else
  {
	$message='<br><FONT color=\"green\"><strong>Unable To Process Request</strong></font><br>';
  }  
}
echo "<center>".$message."</center>";
echo'<center><a href="javascript:show_option(\'manage\',\'milestone\');" class="back_css">&nbsp;<b>Back</b></a></center>';
?>
					