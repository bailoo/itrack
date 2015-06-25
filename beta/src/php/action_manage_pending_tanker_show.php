<?php
	include_once('Hierarchy.php');
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	//require 'PHPMailer_5.2.4/class.phpmailer.php';
	include_once("util_account_detail.php");
	
	$root = $_SESSION['root'];
	if($action_type =="edit")                     ///////// ADD
	{
		$vid_chk = explode(',',substr($_POST['vid_chk'],0,-1));
		$remarks = explode(',',substr($_POST['remarks'],0,-1));
		$vid_update_pre = explode(',',substr($_POST['vid_update_pre'],0,-1));
		$update_status = explode(',',substr($_POST['update_status'],0,-1));
		$type_new_old = explode(',',substr($_POST['type_new_old'],0,-1));
		$vname = explode(',',substr($_POST['vname'],0,-1));
		
		$cnt=0;
		foreach($vid_chk as $vid)
		{
			//echo $vid."=>". $type_new_old[$cnt]."<br>";
			if($type_new_old[$cnt]=="1") 
			{
				//$Query_FINDING="SELECT * from pending_plant_tanker WHERE vehicle_id='$vid' and status=1 ";
				$Query_FINDING="SELECT * from pending_plant_tanker WHERE vehicle_name='$vname[$cnt]' and status=1 ";
				//echo $Query_FINDING;
				$result_FINDING = mysql_query($Query_FINDING,$DbConnection);
				$vidno = mysql_fetch_object($result_FINDING);
				$max_no1=$vidno->vehicle_id;				
				if($max_no1!='')
				{
					if($vid=='N_'.$vname[$cnt])
					{
						$gps=0;//gps status not ok
					}
					else
					{
						$gps=1;//gps status ok
					}
					$query_update = "UPDATE pending_plant_tanker SET pending_status=1,create_id='$account_id',edit_id='$account_id',edit_date='$date' ,create_date='$date',remarks='$remarks[$cnt]',vehicle_id='$vid', gps='$gps' WHERE vehicle_name='$vname[$cnt]' AND status=1 ";
					//echo "1a=". $query_update;
					$result_update = mysql_query($query_update,$DbConnection);	
				}
				else
				{
					if($vid=='N_'.$vname[$cnt])
					{
						$gps=0;//gps status not ok
					}
					else
					{
						$gps=1;//gps status ok
					}
				$Query="INSERT INTO `pending_plant_tanker`(pending_status,create_id,edit_id,edit_date,create_date,remarks,vehicle_id,vehicle_name,status,gps) VALUES(1,'$account_id','$account_id','$date',".
						"'$date','$remarks[$cnt]','$vid','$vname[$cnt]',1,'$gps')";  
				//echo $Query;
					$Result=mysql_query($Query,$DbConnection);
				}
			}
			else
			{
				if($update_status[$cnt]=="0" && $vid_update_pre[$cnt]!=$update_status[$cnt] )
				{
					//$query_update = "UPDATE pending_plant_tanker SET pending_status=0,create_id='$account_id',edit_id='$account_id',edit_date='$date' ,create_date='$date',remarks='$remarks[$cnt]' WHERE vehicle_id='$vid' AND status=1 ";
					$query_update = "UPDATE pending_plant_tanker SET pending_status=0,create_id='$account_id',edit_id='$account_id',edit_date='$date' ,create_date='$date',remarks='$remarks[$cnt]' WHERE vehicle_name='$vname[$cnt]' AND status=1 ";
							//echo "1a=". $query_update;
							$result_update = mysql_query($query_update,$DbConnection);	
				}
				else if($update_status[$cnt]=="1"  && $vid_update_pre[$cnt]!=$update_status[$cnt] )
				{
					if($vid=='N_'.$vname[$cnt])
					{
						$gps=0;//gps status not ok
					}
					else
					{
						$gps=1;//gps status ok
					}
					//$query_update = "UPDATE pending_plant_tanker SET pending_status=1,create_id='$account_id',edit_id='$account_id',edit_date='$date' ,create_date='$date',remarks='$remarks[$cnt]' WHERE vehicle_id='$vid' AND status=1 ";
					$query_update = "UPDATE pending_plant_tanker SET pending_status=1,create_id='$account_id',edit_id='$account_id',edit_date='$date' ,create_date='$date',remarks='$remarks[$cnt]' ,vehicle_id='$vid', gps='$gps' WHERE vehicle_name='$vname[$cnt]' AND status=1 ";
							//echo "1a=". $query_update;
							$result_update = mysql_query($query_update,$DbConnection);	
				}
			}
			$cnt++;
			
		}
		echo'<br><center><font color=green><b>Action Performed Successfully</b></font></center>';
		echo'<br><center><a href="javascript:show_option(\'manage\',\'edit_raw_milk_prev\');" class="menuitem">&nbsp;<b>Back</b></a></center>';
	}
?>