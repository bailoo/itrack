<?php
	include_once('util_session_variable.php');
	include_once('util_php_mysql_connectivity.php');
	include_once('coreDb.php');	

	$DEBUG =0;	 
	$post_action_type = $_POST['action_type'];
	//echo "post_action_type=".$post_action_type."<br>";
	

	if($post_action_type =="add")
	{	
		$flag=0;
		if($upload_format_type=="master")
		{
			
			$NumRows=getNumRowFileFormat($account_id_local,$upload_format_id,$DbConnection);
			if($NumRows)
			{
				$message="<center><FONT color=\"blue\"><strong>This Format ID Already Exists</strong></font></center>";      
			}
			else
			{	
				$flag=1;
				$result = insertMasterFile($account_id_local,$upload_format_id,$file_format_names,$no_of_files,$file_names,$file_ids,$remark,$account_id,$date,$DbConnection);
			    if($result)
				{
					$message="<center><FONT color=\"green\"><strong>Format Added Successfully</strong></font></center>";
				}
				else
				{
					$message="<center><FONT color=\"red\"><strong>Format Not Added Successfully</strong></font></center>";
				}   	
			}
		}
		else if($upload_format_type=="get_report")
		{
			
			$NumRows=getNumRowGetReportFile($account_id_local,$upload_format_id,$DbConnection);
			if($NumRows)
			{
				$message="<center><FONT color=\"blue\"><strong>This Format ID Already Exists</strong></font></center>";      
			}
			else
			{	
				$flag=1;
				$result = insertGetReportFile($account_id_local,$upload_format_id,$file_format_names,$no_of_files,$file_names,$file_ids,$remark,$account_id,$date,$DbConnection);
				if($result)
				{
					$message="<center><FONT color=\"green\"><strong>Format Added Successfully</strong></font></center>";
				}
				else
				{
					$message="<center><FONT color=\"red\"><strong>Format Not Added Successfully</strong></font></center>";
				}  
			}
	    }

		
	} 
	
	else if($post_action_type =="edit")
	{
		if($upload_format_type=="master")
		{
			$ResultUpdate=updateMasterFile($file_format_names,$no_of_files,$file_names,$file_ids,$account_id,$date,$upload_format_id,$account_id_local,$DbConnection);
			if($ResultUpdate)
			{
				$message="<center><FONT color=\"green\"><strong>Format Updated Successfully</strong></font></center>";
			}
			else
			{
				$message="<center><FONT color=\"red\"><strong>Unable To Process Request</strong></font></center>";
			}	
		}
		else if($upload_format_type=="get_report")
		{
            $ResultUpdate= updateGetReportFile($file_format_names,$no_of_files,$file_names,$file_ids,$account_id,$date,$upload_format_id,$account_id_local,$DbConnection);
			if($ResultUpdate)
			{
				$message="<center><FONT color=\"green\"><strong>Format Updated Successfully</strong></font></center>";
			}
			else
			{
				$message="<center><FONT color=\"red\"><strong>Unable To Process Request</strong></font></center>";
			}					   
	    }	
		//echo "query=".$QueryUpdate."<br>";
		
	}
	
	else if($post_action_type =="delete")
	{
		if($upload_format_type=="master")
		{
			$ResultUpdate=deleteMasterFile($upload_format_id,$account_id_local,$DbConnection);
            if($ResultUpdate)
			{
				$message="<center><FONT color=\"green\"><strong>Format Deleted Successfully</strong></font></center>";
			}
			else
			{
				$message="<center><FONT color=\"red\"><strong>Unable To Process Request</strong></font></center>";
			}			
		}
		else if($upload_format_type=="get_report")
		{
			$ResultUpdate=deleteGetReportFile($upload_format_id,$account_id_local,$DbConnection);
            if($ResultUpdate)
			{
				$message="<center><FONT color=\"green\"><strong>Format Deleted Successfully</strong></font></center>";
			}
			else
			{
				$message="<center><FONT color=\"red\"><strong>Unable To Process Request</strong></font></center>";
			}			
	    }	
		
		
	}
	
    
  echo' <br>
  <table border="0" align=center class="manage_interface" cellspacing="2" cellpadding="2">
    <tr>
      <td colspan="3" align="center"><b>'.$message.'</td>    
    </tr>
  </table>';
  echo'<center><a href="javascript:show_option(\'manage\',\'upload_file_format\');" class="back_css">&nbsp;<b>Back</b></a></center>';          	
  //include_once("manage_device.php");
	
?> 
	

