<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');
	$DEBUG=0;
	
	$action_type1 = $_POST['action_type'];
	$local_account_ids = $_POST['account_id_local'];
	$local_account_ids=explode(",",$local_account_ids);
	$account_size=sizeof($local_account_ids);
		  
	if($action_type1=="add") 
	{ 
		$landmark_name1 = trim($_POST['landmark_name']);
		$landmark_point1 = $_POST['landmark_point'];
		$zoom_level1 = $_POST['zoom_level'];
		
		$max_no=getLandmarkMaxSerial($DbConnection);
		if($max_no=="")
		{
			$max_no=1;
		}		
				
        $result=insertLandmark($account_size,$local_account_ids,$max_no,$landmark_name1,$landmark_point1,$zoom_level1,$status,$account_id,$date,$DbConnection);		
		if($result)
		{
			$flag=1;
			$action_perform="Added";
		}     
	}
  
	else if($action_type1=="edit")
	{
		$landmark_id1 = trim($_POST['landmark_id']);
		$landmark_name1 = trim($_POST['landmark_name']);
		$landmark_point1 = $_POST['landmark_point'];
		$zoom_level1 = $_POST['zoom_level'];
		
        $result=updateLandmark($landmark_name1,$landmark_point1,$zoom_level1,$account_id,$date,$landmark_id1,$DbConnection);
	
       if($result)
	   {
		   $flag=1;
		   $action_perform="Updated";
	   } 
	}
	else if ($action_type1=="delete")
	{
		// $type="edit_delete";
		$landmark_id1 = $_POST['landmark_id']; 
		$result=deleteLandmark($account_id,$date,0,$landmark_id1,1,$DbConnection);
		if($result)
		{
			$flag=2;
			$action_perform="Deleted";
		}
	}
 
	//echo $query;
    if($flag==1 || $flag==2)
	{
		$msg = "Landmark ".$action_perform." Successfully";
		$msg_color = "black";				
	}		
	else
	{
		$msg = "Sorry! Unable to process request.";
		$msg_color = "red";		
	}
  echo "<center><br><b><FONT color=\"".$msg_color."\" size=\"2\"><b>".$msg."</b><br></strong></font></center>";
  echo'<center><a href="javascript:show_option(\'manage\',\'landmark\');" class="back_css">&nbsp;<b>Back</b></a></center>';  
  
?>
        