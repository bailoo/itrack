<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');	

	$DEBUG =0;	 
	$post_action_type = $_POST['action_type'];
	if($post_action_type =="assign")
	{
		$vehicleImeiArr=explode(",",$_POST['vehicleImei']);
		$vehicleImei=sizeof($vehicleImeiArr);
		$queryStr="";		
		$queryStr=$queryStr."INSERT INTO secondary_vehicle(vehicle_id,shift,status,create_id,create_date) VALUES";
		$iValues="";
		for($i=0;$i<$vehicleImei;$i++)
		{
			$iValues=$iValues."($vehicleImeiArr[$i],'$shift',1,$account_id,'$date'),";
		}
		$iValues=substr($iValues,0,-1);
		$finalQuery=$queryStr.$iValues;
		//echo "finsertQueryStr=".$finalQuery."<br>";
		
		$finalResult = mysql_query($finalQuery, $DbConnection);
		//echo "finalResult=".$finalResult."<br>";
		if($finalResult)
		{
			$message="<center><FONT color=\"green\"><strong>Secondary Vehicle Assign Successfully</strong></font></center>";
		}
		else
		{
			$message="<center><FONT color=\"red\"><strong>Unable To process request</strong></font></center>";
		} 
	
	} 
	if($post_action_type =="deassign")
	{
		$vehicleImeiArr=explode(",",$_POST['vehicleImei']);
		$vehicleImei=sizeof($vehicleImeiArr);	
		$updateFlag=0;
		for($i=0;$i<$vehicleImei;$i++)
		{
			$updateQuery="UPDATE secondary_vehicle set status=0 WHERE create_id='$account_id' AND shift='$shift' AND vehicle_id=".
					     "$vehicleImeiArr[$i] AND status=1";
			$rUQ=mysql_query($updateQuery, $DbConnection); // r->result ,U->Update, Q->Qyery
			if($rUQ)
			{
				$updateFlag=1;
			}
		}	
		//echo "finalResult=".$finalResult."<br>";
		if($updateFlag==1)
		{
			$message="<center><FONT color=\"green\"><strong>Secondary Vehicle De-Assign Successfully</strong></font></center>";
		}
		else
		{
			$message="<center><FONT color=\"red\"><strong>Unable To process request</strong></font></center>";
		} 
	
	} 


  echo' <br>
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
      <td colspan="3" align="center"><b>'.$message.'</td>    
    </tr>
  </table>';
  echo'<center><a href="javascript:show_option(\'manage\',\'secondary_vehicle\');" class="back_css">&nbsp;<b>Back</b></a></center>';          	
  //include_once("manage_device.php");
	
?> 
	

