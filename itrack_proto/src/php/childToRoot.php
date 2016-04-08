<?php
	function assign_to_till_root($account_id_local1)
	{
		global $DbConnection;	
		global $parent_account_ids;	 
		global $acc_size;
	
		$admin_id=getAccountAdminId($account_id_local1,$DbConnection);		
		$function_account_id=getAccountIdByAdminId($admin_id,$DbConnection);
		
		if($function_account_id==0)
		{
			return $parent_account_ids;
		}
		else
		{			
			$final_account_id=assign_to_till_root($function_account_id,$vehicle_ids);			
			$parent_account_ids[]=$function_account_id;
			//echo"acc1=".$function_account_id."<br>"."acc1=".$function_account_id."<br>"."acc1=".$parent_account_ids."<br>";				
			return $parent_account_ids;					
		}
	}  
?>   